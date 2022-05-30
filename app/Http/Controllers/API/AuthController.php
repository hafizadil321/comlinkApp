<?php
   
namespace App\Http\Controllers\API;
   
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Models\User;
   
class AuthController extends BaseController
{

    public function signin(Request $request)
    {
        // if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){ 
        //     $authUser = Auth::user(); 
        //     $success['token'] =  $authUser->createToken('comlinkApp')->plainTextToken; 
        //     $success['name'] =  $authUser->name;
   
        //     return $this->sendResponse($success, 'User signed in');
        // }
        $customer = User::where('email', $request->email)->where('provider_id', 109)->first();
        if ($customer) {
            return $this->sendResponse($customer, 'User signed in');
        }
        else{ 
            return $this->sendError('Unauthorised.', ['error'=>'Unauthorised'], 401);
        } 
    }

    public function signup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'confirm_password' => 'required|same:password',
        ]);
   
        if($validator->fails()){
            return $this->sendError('Error validation', $validator->errors());       
        }
   
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] =  $user->createToken('comlinkApp')->plainTextToken;
        $success['name'] =  $user->name;
   
        return $this->sendResponse($success, 'User created successfully.');
    }
   
}