<?php

namespace Modules\Api\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Models\Device;

class DeviceController extends Controller
{
    // public function __construct()
    // {
    //     parent::__construct();
    //     $this->middleware('auth:sanctum');
    // }

    public function register(Request $request)
    {

        if ($request->fcm_token) {
            return Device::firstOrCreate([
                'fcm_token' => $request->fcm_token
            ], [
                'fcm_token' => $request->fcm_token
            ]);
        }


        // if($request->fcm_token && Device::exists()){

        // }
    }
}
