<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User; /* User model */
use Illuminate\Database\Eloquent\ModelNotFoundException; 


class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function formatValidationErrors(\Illuminate\Contracts\Validation\Validator $validator)
    {
        $status = 422;
        return [
            "message" => $status . " error",
            "errors" => [
                "message" => $validator->getMessageBag()->first(),
                "info" => [$validator->getMessageBag()->keys()[0]],
            ],
            "status_code" => $status
        ];
    }
    public function edit($user_id)
{
    try{
        //Find the user object from model if it exists
        $user= User::findOrFail($user_id);
        //Redirect to edit user form with the user info found above.
        return view('add',['user'=>$user]);
    }
    catch(ModelNotFoundException $err){
        //redirect to your error page
    }
}
public function update(Request $request, $user_id)
{
    try{
        //Find the user object from model if it exists
        $user= User::findOrFail($user_id);
        //Set user object attributes
        //the $request index should match your form field ids!!!!!
        //you can exclude any field you want.
        $user->description = $request['idemployee'];
        $user->contactName = $request['contactName'];
        $user->contactPhone = $request['contactPhone'];
        $user->timeZone = $request['timeZone'];
        //Save/update user.
        $user->save();

        //redirect to somewhere?
    }
    catch(ModelNotFoundException $err){
        //Show error page
    }       
}      
}
class ExampleController extends Controller
{
    public function index()
    {
        $beads = Post::get();

        return response()->success(compact('beads'));
    
    }
}


