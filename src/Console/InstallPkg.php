<?php

namespace beatstar\pkg\Console;

use Illuminate\Console\Command;

use Illuminate\Support\Facades\File;

class InstallPkg extends Command
{
	protected $signature = 'pkg:install';

	protected $description = 'Install Beatstar Pkg Package.';

	public function handle()
	{
		$this->info('Installing Beatstar Pkg Package...');

        $this->call('vendor:publish', [
            '--provider' => "beatstar\pkg\HashServiceProvider"
        ]);

        $this->call('vendor:publish', [
            '--provider' => "beatstar\pkg\TokenServiceProvider"
        ]);

        $this->config();

        $this->info('Installed Beatstar Pkg Package.');
	}

	public function config(){

        if (File::exists(base_path('/config/beatstar.php'))) {

            File::delete(base_path('/config/beatstar.php'));

            File::copy(__DIR__.'./../Config/beatstar.php', base_path('/config/beatstar.php'));

        }else{

            File::copy(__DIR__.'./../Config/beatstar.php', base_path('/config/beatstar.php'));

        }

    }

}