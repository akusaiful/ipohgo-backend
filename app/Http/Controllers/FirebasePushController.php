<?php

namespace App\Http\Controllers;

use Kreait\Laravel\Firebase\Facades\Firebase;
use Kreait\Firebase\Messaging\CloudMessage;

use Illuminate\Http\Request;

class FirebasePushController extends Controller
{
    protected $notification;
    public function __construct()
    {
        $this->notification = Firebase::messaging();
    }

    public function setToken(Request $request)
    {
        $token = $request->input('device_token');
        $request->user()->update([
            'device_token' => $token
        ]); //Get the currrently logged in user and set their token
        return response()->json([
            'message' => 'Successfully Updated Device Token'
        ]);
    }

    public function sendPushNotification(Request $request)
    {
        // $deviceToken = auth()->user()->device_token;
        $deviceToken = 'eumSh8ucRoe_gE0OKqjKtE:APA91bFvqv2nwad9jmVxja3rt394i1RfgPzCZOYoJuS5U8OcIaJT2YYyuIkNmSQyHFY40G5MRvWVjbqWItk_7kUMsVtxPQzobl32-3ahCHfOyQCGrfJ3JTtw0B1VSFDiw3K8bcbwwSpy';
        $title = $request->input('title');
        $body = $request->input('body');
        $message = CloudMessage::fromArray([
            'token' => $deviceToken,
            'notification' => [
                'title' => $title,
                'body' => $body
            ],
        ]);

        $this->notification->send($message);
    }
}
