<?php

namespace Modules\Api\Controllers;

use App\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Matrix\Exception;
use Modules\Core\Models\Device;
use Modules\User\Events\SendMailUserRegistered;
use Modules\User\Resources\UserResource;
use Validator;

class AuthController extends Controller
{

    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:sanctum', ['except' => ['login', 'register', 'updateProfile', 'exists', 'profile']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     */
    public function login(Request $request)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError('', ['errors' => $validator->errors()]);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return $this->sendError(__("Password is not correct"), ['code' => 'invalid_credentials']);
        }

        if (!empty($user->avatar_url)) {
            $user['avatar_url'] = $user->avatar_url;            
        } else {
            $user['avatar_url'] = get_file_url(1, 'full');
        }

        // echo $user->getAvatarUrl();

        // $user['joining_date'] = $user->created_at->format('Y-m-d');

        if($request->fcm_token){
            $user->fcm_token = $request->fcm_token;
            $user->update();
        }

        return [
            'access_token' => $user->createToken($request->device_name)->plainTextToken,
            // 'user' => new UserResource($user),
            'user' => $user,
            'status' => 1
        ];
    }

    public function register(Request $request)
    {
        if (!is_enable_registration()) {
            return $this->sendError(__("You are not allowed to register"));
        }
        $rules = [
            'first_name' => [
                'required',
                'string',
                'max:255'
            ],
            'last_name'  => [
                'required',
                'string',
                'max:255'
            ],
            'email'      => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users'
            ],
            'password'   => [
                'required',
                'string'
            ],
            'term'       => ['required'],
        ];
        $messages = [
            'email.required'      => __('Email is required field'),
            'email.email'         => __('Email invalidate'),
            'password.required'   => __('Password is required field'),
            'first_name.required' => __('The first name is required field'),
            'last_name.required'  => __('The last name is required field'),
            'term.required'       => __('The terms and conditions field is required'),
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        } else {
            $user = \App\User::create([
                'first_name' => $request->input('first_name'),
                'last_name'  => $request->input('last_name'),
                'email'      => $request->input('email'),
                'password'   => Hash::make($request->input('firebase_token')),
                'publish'    => $request->input('publish'),
                'phone'    => $request->input('phone'),
                'sign_in_provider' => $request->input('sign_in_provider'),
                'firebase_token' => $request->input('firebase_token'),
                'fcm_token' => $request->input('fcm_token'),
                'avatar_url' => $request->input('avatar_url'),
                'email_verified_at' => date('Y-m-d H:i:s'),

            ]);
            event(new Registered($user));
            //Auth::loginUsingId($user->id);
            Device::where(['fcm_token' => $request->fcm_token])->update(['user_id' => $user->id]);

            //syn to device Id 


            try {
                event(new SendMailUserRegistered($user));
            } catch (Exception $exception) {
                Log::warning("SendMailUserRegistered: " . $exception->getMessage());
            }
            $user->assignRole(setting_item('user_role'));
            // return $this->sendSuccess(__('Register successfully'));
            return $this->sendSuccess([
                'user_id' => $user->id
            ]);
        }
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        $user = auth()->user();

        if (!empty($user['avatar_id'])) {
            $user['avatar_url'] = get_file_url($user['avatar_id'], 'full');
            $user['avatar_thumb_url'] = get_file_url($user['avatar_id']);
        }

        return $this->sendSuccess([
            'data' => $user
        ]);
    }

    /**
     * Get user profile using email
     */
    public function profile(Request $request)
    {
        $user = User::whereFirebaseToken($request->token)->first();

        if (!empty($user->avatar_url)) {
            $user['avatar_url'] = $user->avatar_url;            
        } else {
            $user['avatar_url'] = get_file_url(1, 'full');
        }

        $user['joining_date'] = $user->created_at->format('Y-m-d');
        return $this->sendSuccess([
            'data' => $user
        ]);
    }

    public function exists(Request $request)
    {
        $user = User::whereEmail($request->email)->first();

        return $this->sendSuccess([
            'data' => $user ? true : false
        ]);
    }

    public function updateUser(Request $request)
    {
        $user = Auth::user();
        $rules = [
            'first_name' => 'required|max:255',
            'last_name'  => 'required|max:255',
            'email'      => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id)
            ],
        ];
        $messages = [
            'first_name.required' => __('The first name is required field'),
            'last_name.required'  => __('The last name is required field'),
            'email.required'       => __('The email field is required'),
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }
        $user->fill($request->input());
        $user->birthday = date("Y-m-d", strtotime($user->birthday));
        $user->save();
        return $this->sendSuccess(__('Update successfully'));
    }

    public function updateProfile(Request $request)
    {
        // $user = Auth::user();
        $user = User::whereFirebaseToken($request->firebase_token)->first();

        if($name = $request->name){
            $user->first_name = $name;
            $user->last_name = '';
        }

        if($avatarUrl = $request->avatar_url){
            $user->avatar_url = $avatarUrl;
        }
                
        // $user->fill($request->input());
        
        if($user->save()){
            return $this->sendSuccess(__('Update successfully'));
        }else{
            return $this->sendSuccess(__('Fail Update'));
        }
        
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return $this->sendSuccess(__('Successfully logged out'));
    }

    public function changePassword(Request $request)
    {

        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return $this->sendError('', ['errors' => $validator->errors()]);
        }
        $user = auth()->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return $this->sendError(__("Current password is not correct"), ['code' => 'invalid_current_password']);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        // Invalidate all Tokens
        $user->tokens()->delete();

        return $this->sendSuccess(['message' => __("Password updated. Please re-login"), 'code' => "need_relogin"]);
    }
}
