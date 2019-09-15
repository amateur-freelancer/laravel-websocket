<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Dashboard;
use App\Helpers\Database;
use DB;

class CreateDashboard extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:dashboard {code} {title} {info} {color} {profile_key?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create dashboard in cli mode';

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
     * @return mixed
     */
    public function handle()
    {
        $dashboard = new Dashboard([
            'code' => $this->argument('code'),
            'title' => $this->argument('title'),
            'info' => $this->argument('info'),
            'color' => $this->argument('color'),
            'profile_key' => $this->argument('profile_key')
        ]);

        try {
            DB::beginTransaction();
            $dashboard->save();
            Database::createHistoryTable($dashboard);
            DB::commit();
        } catch (Exception $exception) {
            DB::rollback();
        }
    }
}
