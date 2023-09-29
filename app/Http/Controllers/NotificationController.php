<?php

namespace App\Http\Controllers;

use App\Models\Child;
use App\Models\Notification;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function sendNotification(Request $request)
    {




        Notification::create([
            'title'=>$request->title,
            'body'=>$request->body,
        ]);



        $firebaseToken = User::whereNotNull('device_token')->where('role_id', 5)->pluck('device_token')->all();


        if(count($firebaseToken) == 0){
            return response(['massage'=>'faild to send']);
        }

        $SERVER_API_KEY = env('FCM_SERVER_KEY');



            $data = [
                "registration_ids" => $firebaseToken,
                "notification" => [
                    "title" => $request->title,
                    "body" => $request->body,
                ],
                'data' => [
                    "type" => 'normal',
                ]
            ];
            $dataString = json_encode($data);

            $headers = [
                'Authorization: key=' . $SERVER_API_KEY,
                'Content-Type: application/json',
            ];

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

            $response = curl_exec($ch);
            $responseJson = json_decode($response);



        return response(['message'=>'send to '.$responseJson->success. ' successfully']);
    }

    public static function sendStatusNotification(string $childId)
    {

         $child = Child::find($childId);

        $currentDate = Carbon::now('Asia/Damascus');
        $readablDate =  $currentDate->format('Y-m-d H:i:s');

         $parent = User::find($child->parent_id);
        $firebaseToken = $parent->device_token;

        $SERVER_API_KEY = env('FCM_SERVER_KEY');

        $data = [
            "to" => $firebaseToken,
            "notification" => [
                "title" => 'New status!',
                "body" => 'Check out '.$child->name.'\'s new statuses',
            ],
            'data' => [
                "type" => 'status',
                "body"=>['child'=>$child ,'date'=>$readablDate ],
            ]
        ];
        $dataString = json_encode($data);

        $headers = [
            'Authorization: key=' . $SERVER_API_KEY,
            'Content-Type: application/json',
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

        $response = curl_exec($ch);

        return response(['message'=>$response]);
    }

    public function index(){
        $response = Notification::all();
        return response(['notifications'=>$response]);
    }


    public function destroy(string $id){
        $notification = Notification::find($id);
        if(!$notification){
            return response(['message'=>'not found'],404);
        }
        $notification->delete();

        return response(['message'=>'deleted successfully']);
    }


}
