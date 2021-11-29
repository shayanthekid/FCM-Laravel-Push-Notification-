<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Redirect;
use Illuminate\Support\Facades\Validator;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $data = User::all();
        return view('home',['data'=>$data]);
    }
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function saveToken(Request $request)
    {
        auth()->user()->update(['device_token'=>$request->token]);
        return response()->json(['token saved successfully.']);
    }

    /**
     * Write code on Method
     *
     * @return response()
     */

  

    public function sendNotification(Request $request)
    {

        $validated = $request->validate([
        'title' => 'required|max:45',
        'body' => 'required|max:50',
        'action'=>'required',
        
    ]);
        $firebaseToken = User::whereNotNull('device_token')->pluck('device_token')->all();
        $ResponseAction;
        // $testToken = [$request->user];
        if($request->user!="All"){
            $ResponseAction= [$request->user];
        }
        else{
            $ResponseAction = User::whereNotNull('device_token')->pluck('device_token')->all();
        }

        $img = $request->img;
        $SERVER_API_KEY = 'AAAAQ1SQO3M:APA91bFtARUX4-NUPqWDDObXVfvwzJiD0F2T_v7a-FPhmf814fdjs03kjIB940qyarbihJBDGnmtwQRNMElNnEWluODkHXrRrHOMS9gp5ZMXj-oGfpfeedy0jcghwzfGf6ZZRUHl6Mi8';
        $Image =\Session::get('img'); //Getting the icon temp name
        
        $data = [
            "registration_ids" => $ResponseAction,
            "notification" => [
                "title" => $request->title,
                "body" => $request->body,
                "icon"=> "storage/".$Image,
                'image' =>"adzapicon.png",
                'click_action' => 'https://devops.adzappr.com/' . $request->action, //Change to https when hosted. Only works with system url
            //for some reason, icon isnt changed in chrome, but works in Internet explorer
            //must test in devops
            //time to kill myself
            
            ],
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

        dd($dataString);
      
    }


    
}
