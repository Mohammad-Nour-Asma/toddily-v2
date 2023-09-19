<?php

namespace App\Http\Controllers;

use App\Models\Child;
use App\Models\User;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function sendNotification(Request $request)
    {
        $firebaseToken = User::whereNotNull('device_token')->pluck('device_token')->all();

        $SERVER_API_KEY = env('FCM_SERVER_KEY');

        $data = [
            "registration_ids" => $firebaseToken,
            "notification" => [
                "title" => $request->title,
                "body" => $request->body,
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

//    public static function sendStatusNotification(string $childId)
//    {
//
//         $child = Child::find($childId);
//
//         $parent = User::find($child->parent_id);
//        $firebaseToken = $parent->device_token;
//
//        $SERVER_API_KEY = env('FCM_SERVER_KEY');
//
//        $data = [
//            "registration_ids" => $firebaseToken,
//            "notification" => [
//                "title" => 'status notification',
//                "body" => $child,
//            ]
//        ];
//        $dataString = json_encode($data);
//
//        $headers = [
//            'Authorization: key=' . $SERVER_API_KEY,
//            'Content-Type: application/json',
//        ];
//
//        $ch = curl_init();
//
//        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
//        curl_setopt($ch, CURLOPT_POST, true);
//        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
//        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
//
//        $response = curl_exec($ch);
//
//        return response(['message'=>$response]);
//    }

    public static function sendStatusNotification(string $childId)
    {
        try {
            $URL = 'https://fcm.googleapis.com/fcm/send';
            $child = Child::find($childId);

            $parent = User::find($child->parent_id);
            $firebaseToken = $parent->device_token;
            $data = [
                'to' => $firebaseToken,
                'notification' => [
                    'title' => 'status message ',
                    'body' => $child,
                ],
                'data' => [
                    "type" => 'status message',
                    "body" => $child
                ]
            ];

            $json_data = json_encode($data);

            $crl = curl_init();

            $header = array();
            $header[] = 'Content-type: application/json';
            $header[] = 'Authorization: key=' . env('SERVER_API_KEY');
            curl_setopt($crl, CURLOPT_SSL_VERIFYPEER, false);

            curl_setopt($crl, CURLOPT_URL, $URL);
            curl_setopt($crl, CURLOPT_HTTPHEADER, $header);

            curl_setopt($crl, CURLOPT_POST, true);
            curl_setopt($crl, CURLOPT_POSTFIELDS, $json_data);
            curl_setopt($crl, CURLOPT_RETURNTRANSFER, true);
            curl_exec($crl);
        } catch (Exception $e) {
            return "NOTIFICATION FAILED !";
        }}
}
