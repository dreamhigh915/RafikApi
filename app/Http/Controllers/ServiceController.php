<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Service;
use carbon\carbon;

class ServiceController extends Controller
{
          	 public $message=array();
     public function show_services(Request $request)
     {
      try{
          
            $token=$request->input('user_token');
           $lang=$request->input('lang');
          
          $check_token=User::select('id')->where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
              
              
               if($lang=='ar'){
           
              $show=Service::select('service.id','service.name','time.value as duration','time.a_name as time_name','service.price','service.created_at','service.updated_at')
                ->join('time','service.time_id','=','time.id')
                ->where('service.trainer_id',$check_token['id'])->get();
                   
               }else{
                     $show=Service::select('service.id','service.E_name as name','time.value as duration','time.E_name as time_name','service.price','service.created_at','service.updated_at')
                ->join('time','service.time_id','=','time.id')

                ->where('service.trainer_id',$check_token['id'])->get();
                }
                
         //  $show=Service::where('trainer_id',$id)->get();

              if(count($show)>0){
               
                   $message['data']=$show;
                   $message['error']=0;
                   $message['message']='show success';

             }else{
                    $message['data']=$show;
                    $message['error']=1;
                     $message['message']='no types';
             }
          }else{
               $message['error']=3;
                $message['message']='this token is not exist';
          }
            
            }catch(Exception $ex){
               $message['error']=2;
               $message['message']='error'.$ex->getMessage();
            }
      
            return response()->json($message);
     }

      public function show_serviceByid(Request $request)
     {
      try{
            $id=$request->input('id');
             $token=$request->input('user_token');
           $lang=$request->input('lang');
          
          $check_token=User::select('id')->where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
              
              
               if($lang=='ar'){
           
              $specialtype=Service::select('service.id','service.name','time.value as duration','time.a_name as time_name','service.price','service.created_at','service.updated_at')
                ->join('time','service.time_id','=','time.id')
                ->where('service.id',$id)->first();
                   
               }else{
                     $specialtype=Service::select('service.id','service.E_name as name','time.value as duration','time.E_name as time_name','service.price','service.created_at','service.updated_at')
                ->join('time','service.time_id','=','time.id')

                ->where('service.id',$id)->first();
                }
                

       

              if($specialtype !=null){
               
                   $message['data']=$specialtype;
                   $message['error']=0;
                   $message['message']='show success';

             }else{
                    $message['data']=$specialtype;
                    $message['error']=1;
                     $message['message']='no types';
             }
          }else{
              $message['error']=3;
              $message['message']='this token is not exist';
          }
            }catch(Exception $ex){
               $message['error']=2;
               $message['message']='error'.$ex->getMessage();
            }
      
            return response()->json($message);
     }
       public function insert_service(Request $request)
     {
      try{
            $name=$request->input('name');
              $e_name=$request->input('E_name');
            $trainer=$request->input('trainer_id');
            $time=$request->input('time_id');
            $price=$request->input('price');
               $token=$request->input('user_token');
           $lang=$request->input('lang');
          
          $check_token=User::select('id')->where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){

            $updated_at = carbon::now()->toDateTimeString();
		        $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($updated_at)));


            $check=Service::where([['trainer_id',$check_token['id']],['time_id',$time],['name',$name]])->get();


            if(count($check)>0){
               $message['error']=4;
               $message['message']='service exist';

            }else{

            $specialtype=new Service;
            $specialtype->name=$name;
            $specialtype->E_name=$e_name;
            $specialtype->trainer_id=$check_token['id'];
            $specialtype->time_id=$time;
            $specialtype->price=$price;

            $specialtype->created_at=$dateTime;
            $specialtype->save();

              if($specialtype ==true){
               
                   $message['data']=$specialtype;
                   $message['error']=0;
                   $message['message']='insert success';

             }else{
                    $message['data']=$specialtype;
                    $message['error']=1;
                     $message['message']='error in insert';
             }
            
            }
          }else{
                $message['error']=3;
                $message['message']='this token is not exist';
          }

  	      
            }catch(Exception $ex){
               $message['error']=2;
               $message['message']='error'.$ex->getMessage();
            }
      
            return response()->json($message);
     }
    public function update_service(Request $request)
    {
      try{
      	     $id=$request->input('id');
      	     $time=$request->input('time_id');
           $name=$request->input('name');
             $e_name=$request->input('E_name');
            $price=$request->input('price');
               $token=$request->input('user_token');
           $lang=$request->input('lang');
          
          $check_token=User::select('id')->where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){


            $updated_at = carbon::now()->toDateTimeString();
		    $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($updated_at)));

	        $specialtype=Service::where('id',$id)->update([
             'name'=>$name,
             'E_name'=>$e_name,
             'time_id'=>$time,
	         'price'=>$price,
	        'updated_at'=>$dateTime
	        ]);
	     
	        $select=Service::where('id',$id)->first();

              if($specialtype ==true){
               
                   $message['data']=$select;
                   $message['error']=0;
                   $message['message']='update success';

             }else{
                    $message['data']=$select;
                    $message['error']=1;
                     $message['message']='error in update';
             }
          }else{
                  $message['error']=3;
                  $message['message']='this token is not exist';
          }
            
            }catch(Exception $ex){
               $message['error']=2;
               $message['message']='error'.$ex->getMessage();
            }
      
            return response()->json($message);
    }

      public function delete_service(Request $request)
     {
      try{
            $id=$request->input('id');
              $token=$request->input('user_token');
           $lang=$request->input('lang');
          
          $check_token=User::select('id')->where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){

           $specialtype=Service::where('id',$id)->delete();

              if($specialtype ==true){
               
             
                   $message['error']=0;
                   $message['message']='delete success';

             }else{
                  
                    $message['error']=3;
                     $message['message']='this token is not exist';
             }
          }else{
                 $message['error']=1;
                 $message['message']='error in delete';
          }
            
            }catch(Exception $ex){
               $message['error']=2;
               $message['message']='error'.$ex->getMessage();
            }
      
            return response()->json($message);
     }



     /**********************************************************Zoooooooom Apis************************************* */


 public function showMeeting(Request $request){

    $meeting_number = $request->input('meetingNumber');
    $role = $request->input('role');
    $api_key = "DA03n53dQhWDORRiTcjmvQ";           
    $api_secret =  "as3xbFQAD2oN9qbzb4oNsXifUjliXscd9mnC";
   
      
	$time = time() * 1000 - 30000;//time in milliseconds (or close enough)
	$data = base64_encode($api_key . $meeting_number . $time . $role);
	$hash = hash_hmac('sha256', $data, $api_secret, true);
	$_sig = $api_key . "." . $meeting_number . "." . $time . "." . $role . "." . base64_encode($hash);
	//return signature, url safe base64 encoded
	
	
	$signature = [
	    "signature" => rtrim(strtr(base64_encode($_sig), '+/', '-_'), '=')
	    ];
 
    $message['data'] = $signature;
    
    return response()->json($message);

     
 }

     public function getUser(Request $request){
        
      $token ="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhdWQiOm51bGwsImlzcyI6IlkzVXBDdlJwUk0tLW02SEVBXzBTcFEiLCJleHAiOjE2OTU0NzU3NDAsImlhdCI6MTYwMDg2MjM2OH0.vwX1MJtLG7IPFvjoZFcXQzoPZcxSMvQEA4nE2fTKNfE";    
      $headers = [
          'Authorization' => 'Bearer ' . $token,
          'Accept'        => 'application/json',
          'content-type' => 'application/json'
      ];        
      $email = $request->input('email');
      
      // Create a client with a base URI
      $client = new \GuzzleHttp\Client(['base_uri' => 'https://api.zoom.us/v2/']);
      // Send a request to https://foo.com/api/test
      $response = $client->request('GET', 'users?mail='.$email, ['headers' => $headers]);
      // Send a request to https://foo.com/root
      // $response = $client->request('GET', '/root');

      
      
      //echo $response;
      return $response;

  }
  
  
  public function createMeeting(Request $request){
      
      $token ="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhdWQiOm51bGwsImlzcyI6IlkzVXBDdlJwUk0tLW02SEVBXzBTcFEiLCJleHAiOjE2OTU0NzU3NDAsImlhdCI6MTYwMDg2MjM2OH0.vwX1MJtLG7IPFvjoZFcXQzoPZcxSMvQEA4nE2fTKNfE";    
      $headers = [
          'Authorization' => 'Bearer ' . $token,
          'Accept'        => 'application/json',
          'content-type' => 'application/json'
      ];        
      
      $userId = $request->input('userId');
      $start_time = $request->input('start_time');  //"2020-09-24T20:30:00"
      $end_time = $request->input('end_time');
      $date = $request->input('date'); // 30
      $password = $request->input('password'); //"123456"

        $start = Carbon::parse($start_time);
        $end = Carbon::parse($end_time);
        $duration = $end->diffInMinutes($start);
    
    
        $time =  $date.'T'.$start_time;   
                

      // Create a client with a base URI
      $client = new \GuzzleHttp\Client(['base_uri' => 'https://api.zoom.us/v2/']);
      // Send a request to https://foo.com/api/test
      $response = $client->request('post', 'users/'.$userId.'/meetings', [
          'headers' => $headers,
          'body' => json_encode([
              "topic"=> "test",
              "type"=> "3",
              "start_time"=> $time ,
              "duration"=> $duration,
              "password"=> $password,
              "host_email" => $request->host_email,
          ])
          ]);
      // Send a request to https://foo.com/root
      // $response = $client->request('GET', '/root');
      return $response;
  }
}
