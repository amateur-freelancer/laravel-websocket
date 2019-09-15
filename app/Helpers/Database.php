<?php
namespace App\Helpers;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\DashboardHistory;
class Database
{
    public static function createHistoryTable($dashboard)
    {
        $tableName = 'dashboard_' . $dashboard->code . '_histories';
        if (!Schema::hasTable($tableName)) {
            Schema::create($tableName, function(Blueprint $table)
            {
                $table->increments('id');
                $table->string('title');
                $table->string('info');
                $table->string('color');
                $table->string('profile_key');

                $table->timestamps();
            });
        } else {
            // Insert dashboard data
            $dashboardHistory = new DashboardHistory;
            $dashboardHistory->setTable($tableName);
            $dashboardHistory->title = $dashboard->title;
            $dashboardHistory->info = $dashboard->info;
            $dashboardHistory->color = $dashboard->color;
            $dashboardHistory->profile_key = $dashboard->profile_key;

            $dashboardHistory->save();
        }
    }

    public static function dropHistoryTable($dashboardId)
    {
        $tableName = 'dashboard_' . $dashboardId . '_histories';
        Schema::dropIfExists($tableName);
    }
}
