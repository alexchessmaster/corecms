<?php

namespace App\Modules\Shared\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Illuminate\Support\Facades\File;

class ProcessImageJob //implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private string $imagePath;
    private bool $resizeRequired; // images inside the widgets don't need resize

    public function __construct(string $imagePath, $resizeRequired = true)
    {
        $this->imagePath = $imagePath;
        $this->resizeRequired = $resizeRequired;
    }

    public function handle()
    {
        // \Log::info('$this->imagePath' . json_encode($this->imagePath));
        $originalPath = realpath($this->imagePath) ?: $this->imagePath;
        $filename = basename($originalPath);
        $directory = dirname($originalPath);

        if ($this->resizeRequired) {
            $thumbnailDir = $directory . '/thumbnail';
            $mediumDir = $directory . '/medium';
            // dd($this->imagePath);
            // Ensure directories exist
            foreach ([$thumbnailDir, $mediumDir] as $dir) {
                if (!file_exists($dir)) {
                    mkdir($dir, 0777, true);
                }
            }
        }

        $extension = strtolower(pathinfo($originalPath, PATHINFO_EXTENSION));

        // Step 1: Optimize original image
        $this->optimize($originalPath);

        if ($this->resizeRequired) {
            // Step 2: Generate thumbnail (150x150)
            $thumbnailPath = $thumbnailDir . '/' . $filename;
            $process = new Process([
                "convert",
                $originalPath,
                "-resize",
                "150x",           // width 150, height auto
                $thumbnailPath
            ]);
            $process->run();

            // Step 3: Generate medium size (600px max)
            $mediumPath = $mediumDir . '/' . $filename;
            $process = new Process([
                "convert",
                $originalPath,
                "-resize",
                "600x>",          // width max 600, only shrink, height auto
                $mediumPath
            ]);
            $process->run();

            // Step 4: Optimize thumbnail and medium images
            $this->optimize($thumbnailPath);
            $this->optimize($mediumPath);
        }
    }

    private function optimize(string $path)
    {
        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        if ($extension === 'png') {
            $process = new Process(["pngquant", "--quality=60-80", "--ext", ".png", "--force", $path]);
            $process->run();
        } elseif (in_array($extension, ['jpg', 'jpeg'])) {
            $process = new Process(["jpegoptim", "--max=80", "--strip-all", $path]);
            $process->run();
        }
    }
}
