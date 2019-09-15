<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Jobs\MessageConsumer;
use Bschmitt\Amqp\Facades\Amqp;
use App\Dashboard;

class MessageController extends Controller
{
    /**
     * Publish message to RabbitMQ.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function publish(Request $request)
    {
        $boxId = $request->box_id;
        $queueName = 'box' . '-' . $boxId;
        $message = [
            'code' => $request->box_id,
            'title' => $request->title,
            'info' => $request->info,
            'color' => $request->color,
            'sort_order' => $request->sort_order,
            'profile_key' => $request->profile_key
        ];

        Amqp::publish('neu-alert', serialize($message) , ['queue' => $queueName]);

        $this->dispatch(new MessageConsumer($queueName));

        $dashboard = Dashboard::where('code', $boxId)->first();
        return response()->json(['status' => 'success', 'dashboard' => $dashboard], 200);
    }
    
    /**
     * Publish messages to RabbitMQ.
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function bulk_publish(Request $request)
    {
        $dashboards = [];
        try {
            $messages = json_decode($request->getContent(), true);
        } catch(Exception $e) {
            return response()->json(['status' => 'fail', 'message' => "Please send data in a valid JSON format"], 422);
        }
        if(is_array(reset($messages))) {
            foreach ($messages as $message) {
                $boxId = $message['box_id'];
                $queueName = 'box' . '-' . $message['box_id'];
                $message = [
                    'code' => $message['box_id'],
                    'title' => $message['title'],
                    'info' => $message['info'],
                    'color' => $message['color'],
                    'sort_order' => $message['sort_order'],
                    'profile_key' => $message['profile_key']
                ];

                Amqp::publish('neu-alert', serialize($message) , ['queue' => $queueName]);

                $this->dispatch(new MessageConsumer($queueName));

                $dashboard = Dashboard::where('code', $boxId)->first();
                $dashboards[] = $dashboard;
            }
            return response()->json(['status' => 'success', 'dashboards' => $dashboards], 200);
        }
        else {
            return response()->json(['status' => 'fail', 'message' => "Please send a list of objects"], 422);
        }
    }
}
