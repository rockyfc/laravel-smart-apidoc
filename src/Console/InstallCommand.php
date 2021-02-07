<?php

namespace Smart\ApiDoc\Console;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'doc:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '安装所有的doc资源';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->comment('Publishing Doc Service Provider...');
        $this->callSilent('vendor:publish', ['--tag' => 'doc-provider']);

        $this->comment('Publishing Doc Assets...');
        $this->callSilent('vendor:publish', ['--tag' => 'doc-assets']);

        $this->comment('Publishing Doc Configuration...');
        $this->callSilent('vendor:publish', ['--tag' => 'doc-config']);

        $this->info('Doc installed successfully.');
    }
}
