<?php

namespace App\Modules\Shared\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class DeployFrontendJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 300;
    public int $tries = 1;

    public function handle(): void
    {
        $appRoot = env('APP_FRONTEND_ROOT');
        $repoUrl = env('FRONTEND_REPOSITORY');

        if (!$appRoot || !$repoUrl) {
            throw new RuntimeException('Missing APP_FRONTEND_ROOT or FRONTEND_REPOSITORY in .env');
        }

        $script = <<<BASH
#!/bin/bash
set -e

export HOME=/home/alex
export GIT_SSH_COMMAND="ssh -i /var/www/.ssh/id_rsa -o StrictHostKeyChecking=no"

APP_ROOT="{$appRoot}"
REPO_URL="{$repoUrl}"
RELEASES="\$APP_ROOT/releases"
CURRENT="\$APP_ROOT/current"
TIMESTAMP=\$(date +%Y%m%d%H%M%S)
NEW_RELEASE="\$RELEASES/\$TIMESTAMP"

echo "---- Deploy started \$(date) ----"

mkdir -p "\$NEW_RELEASE"
git clone --depth=1 "\$REPO_URL" "\$NEW_RELEASE"
cd "\$NEW_RELEASE"

export NVM_DIR="\$HOME/.nvm"
[ -s "\$NVM_DIR/nvm.sh" ] && \. "\$NVM_DIR/nvm.sh"
nvm use

npm install
npx nuxi generate

ln -sfn "\$NEW_RELEASE" "\$CURRENT"

cd "\$RELEASES"
ls -1d [0-9]* 2>/dev/null | sort | head -n -3 | while IFS= read -r old; do
    echo "Removing old release: \$old"
    rm -rf "\$old"
done

echo "---- Deploy finished \$(date) ----"
BASH;

        $logFile = storage_path('logs/frontend_deploy.log');
        $tmpScript = tempnam(sys_get_temp_dir(), 'deploy_') . '.sh';

        try {
            file_put_contents($tmpScript, $script);
            chmod($tmpScript, 0755);

            $command = "bash {$tmpScript} >> {$logFile} 2>&1";

            Log::info('DeployFrontendJob: starting deploy');

            $this->rotateDeployLog($logFile);

            $exitCode = 0;
            exec($command, $output, $exitCode);

            if ($exitCode !== 0) {
                throw new RuntimeException(
                    "Deploy failed (exit {$exitCode}). Check: {$logFile}"
                );
            }

            Log::info('DeployFrontendJob: deploy finished successfully');
        } finally {
            if (file_exists($tmpScript)) {
                unlink($tmpScript);
            }
        }
    }

    private function rotateDeployLog(string $logFile): void
    {
        if (!file_exists($logFile)) {
            return;
        }

        $maxAgeDays = 3;
        $cutoff = strtotime("-{$maxAgeDays} days");
        $rotated = [];
        $keep = [];

        foreach (file($logFile) as $line) {
            if (preg_match('/^---- Deploy started (.+?) ----$/', trim($line), $m)) {
                $ts = strtotime($m[1]);
                $rotated[] = ['ts' => $ts ?: 0, 'lines' => [$line]];
            } elseif (!empty($rotated)) {
                $rotated[array_key_last($rotated)]['lines'][] = $line;
            }
        }

        foreach ($rotated as $entry) {
            if ($entry['ts'] >= $cutoff) {
                $keep[] = implode('', $entry['lines']);
            }
        }

        file_put_contents($logFile, implode('', $keep));
    }

    public function failed(\Throwable $e): void
    {
        Log::error('DeployFrontendJob: failed', ['error' => $e->getMessage()]);
    }
}
