<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

//Create user 

Route::post('/users/create', function(Request $request){
    $data = $request->all();
    if(!User::where('email', '=', $data['email'])->exists()){
        $user = User::create([
            "name" => $data["name"],
            "email" => $data["email"],
            "password" => Hash::make($data["password"])
        ]);
        if(empty($user->id)){
            return[
                "success" => false,
                "response" => [
                    "error" => "An unexpected error has occured"
                ]
            ];
        }else{
            return[
                "success" => true,
                "response" => [
                    "user" => $user
                ]
            ];
        }
    }else{
        return[
            "success" => false,
            "response" => [
                "error" => "The user already exists"
            ]
        ];
    }
});

//Get all users

Route::get("/users/all", function(){
   $users = User::all();
   
   if(empty($users)){
       return[
        "success" => false,
        "response" =>[
            "error" => "No users found"
        ]
    ];
   }
    return[
        "success" => true,
        "response" => [
            "users" => $users
        ]
    ];
});

//Get User

Route::get("/users/{id}", function (Request $request, $id){
    $user = User::find($id);
    if(empty($user)){
        return[
            "success" => false,
            "response" =>[
                "error" => "No user found"
            ]
        ]; 
    }
    return[
        "success" => true,
        "response" => [
            "user" => $user
        ]
    ];
});

//Delete User

Route::delete("/users/delete/{id}", function (Request $request, $id){
    $user = User::find($id);
    if(empty($user)){
        return[
            "success" => false,
            "response" =>[
                "error" => "No user found"
            ]
           ]; 
    }else{
        return[
            "success" => $user->delete(),
            "response" =>[
                "message" => "User deleted"
            ]
        ]; 
    }
});

//Update User

Route::put("/users/update/{id}", function (Request $request, $id){
    $data =$request->all();

    $user = User::find($id);

    foreach($data as $key => $value){
        $user->{$key} = $value;
    }

    $result = $user->save();
    return ["success" => $result,"message" => "User updated", "response" => ["user" => $user]];
});