<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Reservation;
use App\Time;
use App\Timeline;
use carbon\carbon;
use App\User;
use DateTime;

class TimeController extends Controller
{
        	 public $message=array();
        	 
        	  public function myadmin_login(Request $request)
     {
      try{
         $email=$request->input('email');
         $pass=$request->input('passwords');
              $updated_at = carbon::now()->toDateTimeString();
              $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($updated_at)));
         
        
              $userexist=User::select('users.id','users.first_name','users.last_name','users.email','users.password as passwods','users.phone','roles.name as state','users.image','users.user_token','users.created_at','users.updated_at')->join('roles','users.state','=','roles.id')
              ->where([['users.email',$email],['users.password',$pass],['users.state','=',1]])->first();
              
              
              
                try{
                    
		                  
		                    $user_token=sha1($userexist['id']);
		                    //rand(1000000,9999999).time();// give a unique name to file to be saved
		                    
		                    $insert=User::where([['users.email',$email],['users.password',$pass],['users.state','=',1]])->update([
		                        'user_token'=>$user_token,
		                        'updated_at'=>$dateTime
		                    ]);
		                    
		           }catch(Exception $ex){
		                $message['error']=4;
                   $message['message']='error in generate key';    
		          }
              
              
              
               $userexist=User::select('users.id','users.first_name','users.last_name','users.email','users.password as passwods','users.phone','roles.name as state','users.image','users.user_token','users.created_at','users.updated_at')->join('roles','users.state','=','roles.id')
              ->where([['users.email',$email],['users.password',$pass],['users.state','=',1]])->first();
              

              if($userexist !=null){
               
                   $message['data']=$userexist;
                   $message['error']=0;
                   $message['message']='login success';

             }else{
                    $message['data']=$userexist;
                    $message['error']=1;
                     $message['message']='data is wrong';
             }
            
            }catch(Exception $ex){
               $message['error']=2;
               $message['message']='error'.$ex->getMessage();
            }
      
            return response()->json($message);
     }
     public function show_Time(Request $request)
     {
      try{
          $token=$request->input('user_token');
           $lang=$request->input('lang');
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
              
              if($lang=='ar'){
                 $show=Time::select('id','value','a_name as name','created_at','updated_at')->get(); 
              }else{
                   $show=Time::select('id','value','E_name as name','created_at','updated_at')->get(); 
              }
           
           

              if(count($show)>0){
               
                   $message['data']=$show;
                   $message['error']=0;
                   $message['message']='show success';

             }else{
                    $message['data']=$show;
                    $message['error']=1;
                     $message['message']='no types';
             }
             
            } else{
                   $message['error']=3;
                     $message['message']='this token is not exist';
             }
            
            }catch(Exception $ex){
               $message['error']=2;
               $message['message']='error'.$ex->getMessage();
            }
      
            return response()->json($message);
     }
     
      public function show_allTime(Request $request)
     {
      try{
         
           $lang=$request->input('lang');
          
        
              
              if($lang=='ar'){
                 $show=Time::select('id','value','a_name as name','created_at','updated_at')->get(); 
              }else{
                   $show=Time::select('id','value','E_name as name','created_at','updated_at')->get(); 
              }
           
           

              if(count($show)>0){
               
                   $message['data']=$show;
                   $message['error']=0;
                   $message['message']='show success';

             }else{
                    $message['data']=$show;
                    $message['error']=1;
                     $message['message']='no types';
             }
             
         
            }catch(Exception $ex){
               $message['error']=2;
               $message['message']='error'.$ex->getMessage();
            }
      
            return response()->json($message);
     }


       public function show_TimeByid(Request $request)
     {
      try{
           $token=$request->input('user_token');
           $lang=$request->input('lang');
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
              
           $id=$request->input('id');
           
           
                   if($lang=='ar'){
                         $show=Time::select('id','value','a_name as name','created_at','updated_at')->where('id',$id)->first(); 
                      }else{
                           $show=Time::select('id','value','E_name as name','created_at','updated_at')->where('id',$id)->first(); 
                      }
        
                      if( $show !=null){
                       
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

        public function delete_Time(Request $request)
     {
      try{
             $token=$request->input('user_token');
           $lang=$request->input('lang');
                      $id=$request->input('id');
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){

           $show=Time::where('id',$id)->delete();

              if( $show ==true){
               
                   
                   $message['error']=0;
                   $message['message']='delete success';

             }else{
                   
                    $message['error']=1;
                     $message['message']='error in delete';
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

        public function insert_Time(Request $request)
     {
      try{
           $token=$request->input('user_token');
           $lang=$request->input('lang');
                      $id=$request->input('id');
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
           $value=$request->input('value');
            $name=$request->input('a_name');
             $e_name=$request->input('E_name');

               $updated_at = carbon::now()->toDateTimeString();
              $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($updated_at)));

           $insert=new Time;
           $insert->value=$value;
           $insert->a_name=$name;
           $insert->E_name=$e_name;
           $insert->created_at=$dateTime;
           $insert->save();

              if($insert==true){
               
                   $message['data']=$insert;
                   $message['error']=0;
                   $message['message']='insert success';

             }else{
                    $message['data']=$insert;
                    $message['error']=1;
                     $message['message']='error in insert';
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

       public function update_Time(Request $request)
     {
      try{
            $token=$request->input('user_token');
           $lang=$request->input('lang');
                      $id=$request->input('id');
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
             $id=$request->input('id');
             $value=$request->input('value');
             $name=$request->input('a_name');
             $e_name=$request->input('E_name');

               $updated_at = carbon::now()->toDateTimeString();
              $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($updated_at)));

            $insert=Time::where('id',$id)->update([
            'value'=>$value,
             'a_name'=>$name,
             'E_name'=>$e_name,
             'updated_at'=>$dateTime

           ]);
           
            $select=Time::where('id',$id)->first();

              if($insert==true){
               
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

       public function show_Timeline(Request $request)
     {
      try{
          
          // $id=$request->input('trainer_id');
            $token=$request->input('user_token');
           $lang=$request->input('lang');
           
            $month=date('F');
           $year=date('Y');
                      
          
          $check_token=User::select('id')->where('user_token',$token)->first();
           if($request->has('user_token') && $check_token !=NULL){
          
                     if($lang=='ar' ||$lang=='AR'){
                         
           //          $day=Timeline::select('day')->where([['timeline.trainer_id',$check_token['id']],['timeline.month',$month],['timeline.year',$year]])->get();
                 
                    
                           $timeline=Timeline::select('timeline.id','timeline.date','day', 'timeline.time_from', 'timeline.time_to', 'timeline.price')
                                            ->leftjoin('timeline_state','timeline.state_id','=','timeline_state.id')

                                ->where('timeline.trainer_id',$check_token['id'])->get();
                                //   ->where([['timeline.trainer_id',$check_token['id']],['timeline.month',$month],['timeline.year',$year]])->get();
                                
                          
                        
                     }else{
                         
                          // $day=Timeline::select('E_day as day')->where([['timeline.trainer_id',$check_token['id']],['timeline.month',$month],['timeline.year',$year]])->get();

                     $timeline=Timeline::select('timeline.id','date','timeline.E_day as day', 'timeline.time_from', 'timeline.time_to', 'timeline.price' )
                        ->where('timeline.trainer_id',$check_token['id'])->get();
                         
                     
                         
                     }
              if(count($timeline)>0){
               
                 //  $message['data']=$show;
                   $message['day']=$timeline;
                   $message['error']=0;
                   $message['message']='show success';

             }else{
                    $message['data']=$timeline;
                    $message['error']=1;
                     $message['message']='no timeline for you';
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

     
     public function show_Timelinebyid(Request $request)
     {
      try{
           $id=$request->input('id');
               $token=$request->input('user_token');
           $lang=$request->input('lang');
                      
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
      $show=Timeline::select('timeline.id','timeline.time_from', 'timeline.time_to','timeline.price')
          
                ->where('timeline.id',$id)->first();

              if($show !=null){
               
                   $message['data']=$show;
                   $message['error']=0;
                   $message['message']='show success';

             }else{
                    $message['data']=$show;
                    $message['error']=1;
                     $message['message']='no data';
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
     
       public function show_oneTimeline(Request $request)
     {
      try{
           $id=$request->input('id');
           $token=$request->input('user_token');
                      
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
              
              
                  $show=Timeline::select('timeline.id','timeline.date','timeline.time_from', 'timeline.time_to','timeline.price','users.first_name as doctor_name')
                        ->join('users','timeline.trainer_id','=','users.id')
          
                         ->where('timeline.id',$id)->get();
                
                
                
            //    $user_data=User::select('id','first_name','last_name','image','email')->where('id',$check_token['id'])->get();
                
                

              if($show !=null){
               
                   $message['data']=$show;
                 //  $message['user_data']=$user_data;
                   $message['error']=0;
                   $message['message']='show success';

             }else{
                    $message['data']=$show;
                    $message['error']=1;
                     $message['message']='no data';
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
     
      public function Book_now(Request $request)
     {
      try{
           $id=$request->input('timeline_id');
               $token=$request->input('user_token');
           $lang=$request->input('lang');
                      
          $now= date('Y-m-d');
          $check_token=User::where('user_token',$token)->first();
                 $created_at = carbon::now()->toDateTimeString();
          $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($created_at)));
          
          if($request->has('user_token') && $check_token !=NULL){
              
               $date=Timeline::where('id',$id)->value('date');
       
                if($date >= $now){
              
                      $message['data']=0;
                     $message['message']='this timeline is good';
    
                }else{
                     $message['data']=1;
                     $message['message']='this timeline old';
                    
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
     
     
    public function show_Timelinebyday(Request $request)
     {
      try{
          // $day=$request->input('day');
           
           
           
           $day=$request->json()->all();
           
           var_dump($day);
           
           return $day;
           
          $s= strpos($day, "y:");
         
           $st=substr($day, $s);
           
           return $st;
           $day=json_decode($day,true);
          $token=$request->input('user_token');
           $lang=$request->input('lang');
         $month=date('F');
           $year=date('Y');
          
          
          
          var_dump($day);
          echo "<br/>";
          return $day;
          $check_token=User::select('id')->where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
              
      $show=Timeline::select('timeline.id','timeline.day', 'timeline.time_from', 'timeline.time_to', 'timeline.trainer_id', 'timeline.price','timeline_state.name as state', 'timeline.created_at', 'timeline.updated_at' )
           ->join('timeline_state','timeline.state_id','=','timeline_state.id')
           
                ->where([['timeline.day_num',$day],['timeline.month',$month],['timeline.year',$year]])->get();

              if(count($show )>0){
               
                   $message['data']=$show;
                   $message['error']=0;
                   $message['message']='show success';

             }else{
                    $message['data']=$show;
                    $message['error']=1;
                     $message['message']='no data';
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
     
     public function days(Request $request){
         try{
             
             
             $lang=$request->input('lang');
             
             if($lang =='AR' ||$lang=='ar'){
               
           $Days = [ "Saturday" => "السبت", "Sunday" => "الاحد", "Monday" => "الاثتين", "Tuesday" => "الثلاثاء", "Wednesday" => "الاربعاء", "Thursday" => "الخميس", "Friday" => "الجمعه" ];
              
 

             }else{
                 
                 
             }
         }catch(Exception $ex){
             
             $message['error']=2;
             $message['message']='error '.$ex->getMessage();
         }
         return response()->json($message);
         
     }
     
     
     /**************************************** reserve timeline and make meeting ********************************************/
    public function getUser($email){
        
      $token ="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhdWQiOm51bGwsImlzcyI6IlkzVXBDdlJwUk0tLW02SEVBXzBTcFEiLCJleHAiOjE2OTU0NzU3NDAsImlhdCI6MTYwMDg2MjM2OH0.vwX1MJtLG7IPFvjoZFcXQzoPZcxSMvQEA4nE2fTKNfE";    
      $headers = [
          'Authorization' => 'Bearer ' . $token,
          'Accept'        => 'application/json',
          'content-type' => 'application/json'
      ];        
    //   $email = $request->input('email');
      
      // Create a client with a base URI
      $client = new \GuzzleHttp\Client(['base_uri' => 'https://api.zoom.us/v2/']);
      // Send a request to https://foo.com/api/test
      $response = $client->request('GET', 'users?mail='.$email, ['headers' => $headers]);
      // Send a request to https://foo.com/root
      // $response = $client->request('GET', '/root');
       
        $data = json_decode($response->getBody());
        $data->page_count;
    
      return $data->users[0]->id;  
        
    }
    
    
     public function createMeeting($userId, $start_time, $end_time , $date , $host_email){
      
      $token ="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhdWQiOm51bGwsImlzcyI6IlkzVXBDdlJwUk0tLW02SEVBXzBTcFEiLCJleHAiOjE2OTU0NzU3NDAsImlhdCI6MTYwMDg2MjM2OH0.vwX1MJtLG7IPFvjoZFcXQzoPZcxSMvQEA4nE2fTKNfE";    
      $headers = [
          'Authorization' => 'Bearer ' . $token,
          'Accept'        => 'application/json',
          'content-type' => 'application/json'
      ];        
      
    //   $userId = $request->input('userId');
    //   $start_time = $request->input('start_time');  //"2020-09-24T20:30:00"
    //   $end_time = $request->input('end_time');
    //   $date = $request->input('date'); // 30
      $password = "123456" ; //$request->input('password'); 
    //   $host_email = $request->input('host_email');

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
              "host_email" => $host_email,
          ])
          ]);
      // Send a request to https://foo.com/root
      // $response = $client->request('GET', '/root');
      return $response;
  }
  
    public function insert_timeline(Request $request)
     {
         try{
           
             $date=$request->input('date');
           //  $month=$request->input('month');
           
             $from = $request->input('time_from');//9:00
             $to   = $request->input('time_to');//12:00
             $price = $request->input('price');
             $token = $request->input('user_token');
           //$year=$request->input('year');
           
            if(!$request->has('date')){
                $message['error'] = 5;
                $message['message'] = 'You must enter Date value';
                return response()->json($message);
            }
           
            if(!$request->has('time_from')){
                $message['error'] = 6;
                $message['message'] = 'You must enter Time From value';
                return response()->json($message);
            }
           
            if(!$request->has('time_to')){
                $message['error'] = 6;
                $message['message'] = 'You must enter Time To value';
                return response()->json($message);
            }
           
            if(!$request->has('price')){
                $message['error'] = 7;
                $message['message'] = 'You must enter Price value';
                return response()->json($message);
            }
           
            $e_day=date('l',strtotime($date));

           $month=date('F',strtotime($date));
           $year=date('Y',strtotime($date));
           
           $day_num=date('j',strtotime($date));
           
           
             $now= date('Y-m-d');
             
             
             if($date >= $now){

           
           $Days = [ "Saturday" => "السبت", "Sunday" => "الاحد", "Monday" => "الاثتين", "Tuesday" => "الثلاثاء", "Wednesday" => "الاربعاء", "Thursday" => "الخميس", "Friday" => "الجمعه" ];
                
                  $day=$Days[$e_day];      
          
          $check_token=User::select('id')->where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
              
           
                //state
                  $updated_at = carbon::now()->toDateTimeString();
		          $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($updated_at)));

		         


	 $check=Timeline::where([['date',$date],['trainer_id',$check_token['id']],['time_from',$from],['time_to',$to]])->get();


	       if(count($check)>0){
                   $message['error']=4;
                   $message['message']='this time line is exist';
                   
	       }else{
	       
	       
                $insert=new Timeline;
                $insert->day=$day;
                $insert->E_day=$e_day;
                $insert->day_num=$day_num;
                $insert->month=$month;
                $insert->year=$year;
                $insert->date=$date;
                $insert->trainer_id=$check_token['id'];
                $insert->price=$price;
                $insert->time_from=$from;
                $insert->time_to=$to;
                $insert->state_id=1;
                $insert->created_at=$dateTime;
                $insert->save();
       
            if($insert==true){
               
               $userId = $this->getUser($check_token->email);
               
               $creatMeeting = $this->createMeeting($userId , $from , $to , $date , $check_token->email);
               
               $meeting_data = json_decode($creatMeeting->getBody());

              
               $add_meeting = new \App\Meeting;

               $add_meeting->timeline_id = $insert->id;
               $add_meeting->meeting_id  = $meeting_data->id."";
               $add_meeting->host_id = $meeting_data->host_id;
               $add_meeting->host_email = $meeting_data->host_email;
               $add_meeting->topic = $meeting_data->topic;
               $add_meeting->type = $meeting_data->type;
               $add_meeting->status = $meeting_data->status;
               $add_meeting->timezone = $meeting_data->timezone;
               $add_meeting->create_time = $meeting_data->created_at;
               $add_meeting->start_url = $meeting_data->start_url;
               $add_meeting->join_url = $meeting_data->join_url;
               $add_meeting->password = $meeting_data->password;
               $add_meeting->created_at =  $dateTime;
               $add_meeting->save();
               
               $message['meeting'] = $meeting_data->id;
               $message['error']=0;
               $message['message']='insert success';
            }else{
                $message['error']=1;
                $message['message']='error in insert';
             }

	      }
        
          }else{
              $message['error']=3;
              $message['message']='this token is not exist';
          }

             }else{
                 $message['error']=4;
              $message['message']='this timeline is old ';
              
          } 
            
            }catch(Exception $ex){
               $message['error']=2;
               $message['message']='error'.$ex->getMessage();
            }
      
            return response()->json($message);

     }

     /*public function insert_timeline(Request $request)
     {
         try{
           
           //$day=$request->input('day');
            $date=$request->input('date');
             $from=$request->input('time_from');//9:00
              $to=$request->input('time_to');//12:00
             
             //   $time=$request->input('time_id');
                
                   $token=$request->input('user_token');
           $lang=$request->input('lang');
           $e_day=date('l',strtotime($date));
           
           $Days = [ "Saturday" => "السبت", "Sunday" => "الاحد", "Monday" => "الاثتين", "Tuesday" => "الثلاثاء", "Wednesday" => "الاربعاء", "Thursday" => "الخميس", "Friday" => "الجمعه" ];
                      
          
          $check_token=User::select('id')->where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
              
              $day=$Days[$e_day];
                //state
                  $updated_at = carbon::now()->toDateTimeString();
		          $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($updated_at)));
		          
		          
		            $date1=new DateTime($from);
                    $date2=new DateTime($to);
                    $diff=date_diff($date1,$date2);
                    echo $diff->format("%h:%I:%S hours").'<br>';
                    
                    $count=($diff->format('%h'));
                    $result=$from;
                    echo $count.'<br>';
                    $myarr=array();
                    for($i=0;$i<$count;$i+=0.5){
                        $myarr[]=array('trainer_id'=>$check_token['id'],'day'=>$day,'E_day'=>$e_day,'date'=>$date,'time_from'=>$result,'time_to'=>($result=date('h:i:s',strtotime('+30 minutes',strtotime($result)))),'state_id'=>1,'created_at'=>$dateTime);
                  //  $result=date('h:i:s',strtotime('+30 minutes',strtotime($result)));
                    //echo $result.'<br>';
                    }
                   // return $myarr;


	 $check=Timeline::where([['date',$date],['trainer_id',$check_token['id']],['time_from',$from]])->orWhere([['date',$date],['trainer_id',$check_token['id']],['time_to',$to]])->get();


	       if(count($check)>0){
                   $message['error']=4;
                   $message['message']='this time line is exist';
	       }else{
	       
	       
                $insert=Timeline::insert($myarr);
       

              if($insert==true){
               
                   //$message['data']=$insert;
                   $message['error']=0;
                   $message['message']='insert success';

             }else{
                 //   $message['data']=$insert;
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

     }*/

     public function update_timeline(Request $request)
     {
         try{
           $id=$request->input('id');
             $from=$request->input('time_from');
              $to=$request->input('time_to');
             
                $price=$request->input('price');
                
                     $token=$request->input('user_token');
           $lang=$request->input('lang');
                      
          
          $check_token=User::select('id')->where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
                //state

                  $updated_at = carbon::now()->toDateTimeString();
		          $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($updated_at)));

                $insert=Timeline::where('id',$id)->update([
                        
                         'price'=>$price,
                        'time_from'=>$from,
                         'time_to'=>$to,
                          
			                'updated_at'=>$dateTime

                ]);
                   $select=Timeline::where('id',$id)->first();

              if($insert==true){
               
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

       public function delete_Timeline(Request $request)
     {
      try{
           $id=$request->input('timeline_id');
            $token=$request->input('user_token');
           $lang=$request->input('lang');
                      
          
          $check_token=User::select('id')->where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){

           $show=Timeline::where('id',$id)->delete();

              if($show== true){
               
              
                   $message['error']=0;
                   $message['message']='delete success';

             }else{
                  
                    $message['error']=1;
                     $message['message']='error in delete';
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
     
     public function show_allSessions(Request $request){
       
          $trainer_id = $request->input('trainer_id');
          

          $get_data = \App\Timeline::where('trainer_id' , $trainer_id)->get();
          
          if( count($get_data)>0 ){
              $message['data'] = $get_data;
              $message['error'] = 0;
              $message['message'] = "all sessions for this trainner";
          }else{
              $message['data'] = $get_data;
              $message['error'] = 1;
              $message['message'] = "no sessions for this trainer";
          }
           return response()->json($message);

     }
     
     
     
      public function delete_Timeline_range(Request $request)
     {
      try{
            $id=$request->input('timeline_id');
            
         
            $token=$request->input('user_token');
            $lang=$request->input('lang');
                      
          
            $check_token=User::select('id')->where('user_token',$token)->first();
          
          
            if($request->has('user_token') && $check_token !=NULL){


                   try {
                        $ids = explode(",", $id);
                        //$org->products()->find($ids)->delete();
                        
                        $show=Timeline::whereIn('id',$ids)->delete();
                        
                        
                         if($show== true){
                   
                  
                       $message['error']=0;
                       $message['message']='delete success';

                     }else{
                          
                            $message['error']=1;
                             $message['message']='error in delete';
                     }
                    }
                    catch(Exception $ex) {
                        $message['error']=1;
                        $message['message']='error in delete';
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


}
?>
