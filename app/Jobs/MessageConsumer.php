<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use App\Dashboard;
use App\Helpers\Database;
use Bschmitt\Amqp\Facades\Amqp;
use App\Events\DashboardUpdated;
use Validator;
use DB;

class MessageConsumer implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $queueName;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($queueName)
    {
        $this->queueName = $queueName;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Amqp::consume($this->queueName, function ($message, $resolver) {

            $dashboard = $this->process_message($message->body);
            if ($dashboard) {
                $dashboards = Dashboard::all();

                $channel1 = 'dashboard-all';
                $ch1Data = $dashboards;
                broadcast(new DashboardUpdated($channel1, $ch1Data));

                $channel2 = 'dashboard-' . $dashboard->profile_key;
                $ch2Data = $dashboards->where('profile_key', $dashboard->profile_key);
                broadcast(new DashboardUpdated($channel2, $ch2Data));

            }

            $resolver->acknowledge($message);
            $resolver->stopWhenProcessed();
         });
    }

    public function process_message($rawMessage)
    {
        $message = unserialize($rawMessage);

        // Create new or update existing in dashboard
        $validator = Validator::make($message, [
            'code' => 'required|max:255',
            'title' => 'string|max:255',
            'info' => 'string|max:255',
            'color' => 'string|max:20',
            'sort_order' => 'integer',
            'profile_key' => 'sometimes|nullable|string|max:255'
        ]);

        if ($validator->fails()) {
            return null;
        }

        DB::beginTransaction();
        $dashboard = Dashboard::where('code', $message['code'])->first();
        if (!$dashboard) {
            $dashboard = new Dashboard();
        }

        $dashboard->code = $message['code'];
        $dashboard->title = $message['title'];
        $dashboard->info = $message['info'];
        $dashboard->color = $message['color'];
        $dashboard->sort_order = $message['sort_order'];

        if ($message['profile_key']) {
            $dashboard->profile_key = $message['profile_key'];
        }

        try {
            $dashboard->save();
            Database::createHistoryTable($dashboard);

            DB::commit();
        } catch (Exception $exception) {
            DB::rollback();

            return null;
        }

        return $dashboard;
    }
}
