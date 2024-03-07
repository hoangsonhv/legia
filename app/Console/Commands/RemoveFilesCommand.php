<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use File;

class RemoveFilesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'remove:files';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $folderPath = base_path(); // Thư mục gốc của dự án Laravel
        File::cleanDirectory($folderPath);
        $this->info('Files removed successfully.');
        return true;
    }
}
