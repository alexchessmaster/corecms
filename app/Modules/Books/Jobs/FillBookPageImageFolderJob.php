<?php

namespace App\Modules\Books\Jobs;

use App\Modules\Books\Models\Book;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class FillBookPageImageFolderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private Book $book;
    /** @var callable|null */
    private $outputCallback = null;

    /**
     * Create a new job instance.
     */
    public function __construct(Book $book)
    {
        $this->book = $book;
    }

    public function processWithOutput(callable $outputCallback): void
    {
        $this->outputCallback = $outputCallback;
        $this->handle();
    }

    private function logMessage(string $level, string $message): void
    {
        if ($this->outputCallback) {
            ($this->outputCallback)(sprintf('[%s] %s', strtoupper($level), $message));
        }

        if (method_exists(app('log'), $level)) {
            app('log')->{$level}($message);
        } else {
            app('log')->info($message);
        }
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // This job depends on ImageMagick's `convert` command being available.
        // On Ubuntu/Debian servers install:
        //   sudo apt update
        //   sudo apt install imagemagick -y
        //
        // If the server is changed or does not have the necessary packages,
        // make sure `convert` is installed and accessible in PATH. Some
        // Linux distributions may also require Ghostscript or poppler for
        // PDF rendering support.
        //
        // If `convert` is missing, the job will fail with a ProcessFailedException.
        // In that case, install the appropriate ImageMagick package for your OS.

        $slugTranslations = $this->book->getTranslations('slug');
        if (empty($slugTranslations)) {
            $this->logMessage('warning', "FillBookPageImageFolderJob: no slug translations available for book id {$this->book->id}");
            return;
        }

        $processed = 0;
        $book = $this->book->fresh();

        foreach ($slugTranslations as $locale => $slug) {

            if (empty($slug)) {
                continue;
            }


            $pdfRelativePath = $this->book->getTranslation('pdf', $locale, false);
            if (empty($pdfRelativePath)) {
                $this->logMessage('warning', "FillBookPageImageFolderJob: no PDF available for book id {$this->book->id} in locale {$locale}");
                continue;
            }

            $pdfPath = public_path($pdfRelativePath);
            if (!File::exists($pdfPath)) {
                $this->logMessage('warning', "FillBookPageImageFolderJob: PDF file not found for book id {$this->book->id} in locale {$locale} at {$pdfPath}");
                continue;
            }

            if (empty($slug)) {
                $slug = Str::slug($this->book->getTranslation('title', $locale, false) ?? 'book-' . $this->book->id);
            }

            $slug = ltrim($slug, '/');
            $slugLastPart = Str::afterLast($slug, '/');
            $slug = Str::slug($slugLastPart);
            $outputFolder = public_path('books/' . $slug);
            $book->setTranslation('page_image_folder', $locale, '/books/' . $slug);
            $book->save();
            
            if (File::exists($outputFolder)) {
                File::deleteDirectory($outputFolder);
            }
            File::ensureDirectoryExists($outputFolder, 0755, true);

            $outputPattern = $outputFolder . '/page-%03d.png';

            // Get page count via Ghostscript (lightweight, doesn't rasterize)
            $countProcess = new Process([
                'gs',
                '-q',
                '-dNODISPLAY',
                '-dNOSAFER',
                '-c',
                "({$pdfPath}) (r) file runpdfbegin pdfpagecount = quit",
            ]);
            $countProcess->run();

            if (!$countProcess->isSuccessful() || !ctype_digit(trim($countProcess->getOutput()))) {
                $this->logMessage('error', "FillBookPageImageFolderJob: could not determine page count for book id {$this->book->id} in locale {$locale}");
                continue;
            }

            $pageCount = (int) trim($countProcess->getOutput());

            for ($i = 0; $i < $pageCount; $i++) {
                $pageOutput = sprintf('%s/page-%03d.png', $outputFolder, $i + 1);

                $process = new Process([
                    'convert',
                    '-limit',
                    'memory',
                    '4GiB',
                    '-limit',
                    'map',
                    '2GiB',
                    '-limit',
                    'disk',
                    '8GiB',
                    '-density',
                    '150',
                    "{$pdfPath}[{$i}]",
                    '-background',
                    'white',
                    '-alpha',
                    'remove',
                    '-alpha',
                    'off',
                    $pageOutput,
                ]);
                $process->setTimeout(1200);
                $process->run();

                if (!$process->isSuccessful()) {
                    $this->logMessage('error', "FillBookPageImageFolderJob: ImageMagick convert failed on page {$i} for book id {$this->book->id} in locale {$locale}: {$process->getErrorOutput()}");
                    throw new ProcessFailedException($process);
                }
            }

            $generatedFiles = File::glob($outputFolder . '/page-*.png');
            if (empty($generatedFiles)) {
                $this->logMessage('error', "FillBookPageImageFolderJob: no page images were generated for book id {$this->book->id} in locale {$locale}");
                continue;
            }

            sort($generatedFiles);
            foreach ($generatedFiles as $index => $path) {
                $pageNumber = $index + 1;
                $newPath = $outputFolder . '/' . $pageNumber . '.png';
                if ($newPath !== $path) {
                    File::move($path, $newPath);
                }
            }


            $processed++;
            $this->logMessage('info', "FillBookPageImageFolderJob: created " . count($generatedFiles) . " page images for book id {$this->book->id} in locale {$locale}");
        }

        if ($processed > 0) {
        } else {
            $this->logMessage('warning', "FillBookPageImageFolderJob: no page images were generated for any locale for book id {$this->book->id}");
        }
    }
}
