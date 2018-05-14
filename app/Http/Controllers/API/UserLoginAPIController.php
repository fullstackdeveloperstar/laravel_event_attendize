<?php
namespace app\Http\Controllers\API;

use App\Models\Event;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\User;
use App\Attendize\Utils;
use App\Models\Account;
use Validator;
use Hash;
use Mail;


class UserLoginAPIController extends Controller
{
    public function __construct(){
        // $this->middleware('guest', ['except' => 'getLogout']);
         // $this->middleware('jwt.auth');
    }

    public function login(Request $request)
    {

        $credentials = $request->only('email', 'password');
        try{
            if(!$token = JWTAuth::attempt($credentials)){
                return response()->json(['error' => "User credentials are not correct"], 401);
            }
        } catch(JWTException $ex){
            return response()->json(['error' => "Something went wrong"], 500);
        }

        return response()->json(compact('token'))->setStatusCode(200);
    }

    public function signup(Request $request){
        $credentials = $request->only( 'email', 'password', 'first_name', 'last_name','password_confirmation');
        
        $rules = [
            'email'         => 'required|email|max:255|unique:users',
            'password'      => 'required|min:5|confirmed',
            'first_name'    => 'required',
            'last_name'     => 'required'
        ];
        $validator = Validator::make($credentials, $rules);
        if($validator->fails()) {
            return response()->json(['success'=> false, 'error'=> $validator->messages()]);
        }

        $account_data = $request->only(['email', 'first_name', 'last_name']);
        $account_data['currency_id'] = config('attendize.default_currency');
        $account_data['timezone_id'] = config('attendize.default_timezone');
        $account = Account::create($account_data);

        $user = new User();
        $user_data = $request->only(['email', 'first_name', 'last_name']);
        $user_data['password'] = Hash::make($request->get('password'));
        $user_data['account_id'] = $account->id;
        $user_data['is_parent'] = 1;
        $user_data['is_registered'] = 1;
        $user = User::create($user_data);

        Mail::send('Emails.ConfirmEmail',
            ['first_name' => $user->first_name, 'confirmation_code' => $user->confirmation_code],
            function ($message) use ($request) {
                $message->to($request->get('email'), $request->get('first_name'))
                    ->subject('Thank you for registering for Attendize');
            });

        return response()->json(['success' => "signup success!"], 200);
    }

    public function getToken(){
        $token = JWTAuth::getToken();
        if (! $token) {
            return response()->json(['error' => "Token is invalid."]);
        }

        try {
            $refreshedToken = JWTAuth::refresh($token);
        } catch (JWTException $ex) {
            response()->json(['error' => 'Something went wrong']);
        }

        return response()->json(compact('refreshedToken'));
        // return $refreshedToken;
    }

    public function index(){
        return User::all();
    }

    public function show($id) {
        return User::find($id);
    }

    public function getLoggedinUser(){
       try{
        $user = JWTAuth::parseToken()->toUser();
        if(!$user) {
            return response()->json(["error" => "something went wrong"]);
        }
       } catch(JWTException $ex){
            return response()->json(['error' => 'something went wrong']);
       }

       return response()->json(compact('user'))->setStatusCode(200);
        // return response()->json(['success' => "signup success!"], 200);
    }

}