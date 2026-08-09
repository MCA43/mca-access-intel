<?php

namespace Mca\AccessIntel\Console;

use Illuminate\Console\Command;
use Mca\AccessIntel\Support\McaAccessIntelLocale;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'mca:access-intel:install')]
class InstallAccessIntelCommand extends Command
{
    protected $signature = 'mca:access-intel:install
                            {--no-assets : Skip CSS publish}';

    protected $description = 'Install MCA Access Intel (config, assets)';

    public function handle(): int
    {
        McaAccessIntelLocale::apply();

        $this->components->info(mca_intel('console.install.start'));

        if (! file_exists(config_path('access-intel.php'))) {
            $this->callSilent('vendor:publish', ['--tag' => 'mca-access-intel-config']);
        }
        $this->components->task(mca_intel('console.install.config_ready'), fn () => true);

        if (! $this->option('no-assets')) {
            $this->callSilent('vendor:publish', [
                '--tag' => 'mca-access-intel-assets',
                '--force' => true,
            ]);
            $this->components->task(mca_intel('console.install.assets_published'), fn () => true);
        }

        if (! class_exists(\Mca\AccessLog\Models\AccessLog::class)) {
            $this->components->warn(mca_intel('console.install.need_access_log'));
        }

        $this->newLine();
        $this->components->info(mca_intel('console.install.done'));
        $this->line('  '.mca_intel('console.install.web_ui', [
            'prefix' => config('access-intel.routes.web.prefix', 'mca/access-intel'),
        ]));

        return self::SUCCESS;
    }
}
