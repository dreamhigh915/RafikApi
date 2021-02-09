<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Wallet;
use App\Reservation;
use App\User;
use App\Payment;
use App\setting;
use App\Timeline;
use carbon\carbon;
use App\Notification;

 define( 'API_ACCESS_KEY12','AAAAz-GV50A:APA91bFXom0sxT5MNl_IQUbzEEsxJK55cgoI6k4ucPJctyN8wshU_ZOGZnADHyFfKOvcOxLsfuaAKrTJ3-2lGjmzdDAFH2IhiP0dtEtLMUxdyt5ZOPacOq5D9wHk0W0Wgxlncu3iMPl7');

class CourseController extends Controller
{
	 public $message=array(); 
	 
	 
	 
	 public function upload_video(Request $request)
    {
       try{
             
              $token=$request->input('user_token');
              $video=$request->file('video');

                      
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){

                $created_at = carbon::now()->toDateTimeString();
          $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($created_at)));
           

             if(isset($video)) {
                        $new_name = $video->getClientOriginalName();
                        $savedFileName = rand(100000,999999).time()."_".$new_name; // give a unique name to file to be saved
                        $destinationPath_id = 'uploads/users';
                        $video->move($destinationPath_id, $savedFileName);
            
                        $videos = $savedFileName;
                        
                        
                          $update=User::where('id',$check_token['id'])->update([
                            
                             'video'=>$videos,
                             'updated_at'=>$dateTime
                            ]);


                  if($update == true){
        
                    $message['error']=0;
                    $message['message']='video is uploaded';
        
                  }else{
                   
                    $message['error']=1;
                    $message['message']='error in upload this video';
                  }
                      
               }else{
                   $message['error']=4;
                    $message['message']='choose video to upload';    
              }
                       
                      
              
          }else{
             $message['error']=3;
            $message['message']='this token is exist'; 
          }

       }catch(Exception $ex){
         
            $message['error']=2;
            $message['message']='error'.$ex->getMessage();

       }
       return response()->json($message);
    }
    
     public function show_video(Request $request)
    {
       try{
             
              $token=$request->input('user_token');


                      
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){

                $created_at = carbon::now()->toDateTimeString();
                $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($created_at)));
           

                  $select=User::where('id',$check_token['id'])->value('video');
                  
                  if($select != null){
        
                    $message['video']=$select;
                    $message['error']=0;
                    $message['message']='video show ';
        
                  }else{
                     $message['video']=NULL;
                    $message['error']=1;
                    $message['message']='error in show this video';
                  }
              
          }else{
             $message['error']=3;
            $message['message']='this token is exist'; 
          }

       }catch(Exception $ex){
         
            $message['error']=2;
            $message['message']='error'.$ex->getMessage();

       }
       return response()->json($message);
    }
    
    
     public function reserve_timeline(Request $request)
    {
       try{
             
              $timeline=$request->input('timeline_id');
              $token=$request->input('user_token');
              $payment=$request->input('payment_id');//1=>wallet  2=>mastercard
              $pay=$request->input('pay');
           
                      
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){

              $created_at = carbon::now()->toDateTimeString();
              $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($created_at)));
           

             $price=Timeline::where('id',$timeline)->value('price');
             $trainer=Timeline::where('id',$timeline)->value('trainer_id');
             $date=Timeline::where('id',$timeline)->value('date');
             $now= date('Y-m-d');
             
             
             if( $trainer != $check_token['id']){
                 
                 if($date >= $now){
                 
                    $com = 0;
                  $check=Timeline::where('id',$timeline)->value('state_id');
                  $user_setting=setting::where('user_id',$trainer)->first();
                  $com = str_replace('%', '', $user_setting['commission']);
              
         
                $state=2;
                
                if($check ==1){

                	 $getTime = Timeline::select('time_from' , 'time_to')->where([['id' , $timeline ],['date',$date]])->first();

                    $check_reserveCount = Timeline::where([['date',$date] , ['time_from' , $getTime->time_from] , ['time_to' , $getTime->time_to], ['state_id' , '2']])->get();

                    if( count($check_reserveCount) == 2){
                        $message['error']=4;
                        $message['message']='this timeline is reserved';
                                      
                       $check_reserveCount = Timeline::where([['date',$date] , ['time_from' , $getTime->time_from] , ['time_to' , $getTime->time_to], ['state_id' , '1']])->update(['state_id' =>'3']);

                        return response()->json($message);

                    }
                   if($payment ==1){
                       
                        $credit = Wallet::where('user_id',$check_token['id'])->value('credit');
                        
                        
                        if($price <= $credit){
                            
                             $update=Wallet::where('user_id',$check_token['id'])->decrement('credit',$price);
                             
                               $com = 0;
                         
                             $cal_com=($com/$price)*100;
                             
                             $trainer_cash=round($price-(($com/$price)*100));
                             $update_admin_setting=Wallet::where('user_id',1)->increment('credit',round($cal_com));
                             $update_setting=setting::where('user_id',$trainer)->increment('credit',$trainer_cash);
                             
                             if($update_setting){
                                   $pay=1;
                                 
                             }else{
                                   $pay=0;
                             }
                           
                        }else{
                            
                            $message['error']=6;
                            $message['message']='recharge your account';
                            return response()->json($message);
                        }
                        
                        
                   }elseif($payment ==2){
                       
                          if($pay==1){
                              
                             $trainer_cash=round($price-(($com/$price)*100));
                             $update_admin_setting=Wallet::where('user_id',1)->increment('credit',round($com));
                             $update_setting=setting::where('user_id',$trainer)->increment('credit',$trainer_cash);
                              
                          }else{
                              
                              $state=1;
                          }
                       
                       
                   }elseif($payment ==3){
                       
                       $state=1;
                   }
                 
                  $reserve=new Reservation;
                  $reserve->user_id=$check_token['id'];
                  $reserve->timeline_id=$timeline;
                  $reserve->price=$price;
                  $reserve->status_id=$state;
                  $reserve->payment_id=$payment;
                  $reserve->paid=$pay;
                  $reserve->created_at=$dateTime;
                  $reserve->save();


          if($reserve == true){

           $update=Timeline::where('id',$timeline)->update([
                'state_id'=>2,
                'updated_at'=>$dateTime
           ]);
           
           $user=Timeline::where('id',$timeline)->value('trainer_id');
           
                               try{       
                       //  $cc = 0;
                         
                         $title ='Timeline is reserved';
                         $body ='someone reserve your timeline';
                         
                
                
                       $get_user_token =User::select('firebase_token')->where('id', '=',$user)->first();
                       
                      // return $get_user_token[0]->firebase_token;
                
                
                                     ////////////////////////////////////////////////////////
                                     
                                       $msg = array
                                              (
                                    		'body' 	=> $body,
                                    		'title'	=> $title,
                                            "click_action" => "1" ,    	
                                              );
                                    	$fields = array
                                    			(
                                    				'to'		=> $get_user_token['firebase_token'],
                                    				'data' => $msg,
                                    				'notification'	=> $msg
                                    			);
                                    	
                                    	                 
                
                                    	$headers = array
                                    			(
                                    				'Authorization: key=' . API_ACCESS_KEY12,
                                    				'Content-Type: application/json'
                                    			);
                                    #Send Reponse To FireBase Server	
                                    		$ch = curl_init();
                                    		curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
                                    		curl_setopt( $ch,CURLOPT_POST, true );
                                    		curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
                                    		curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
                                    		curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
                                    		curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
                                    		$result = curl_exec($ch );
                                    		//echo $result;
                                    		curl_close( $ch );
                                 
                        
                        
                            $save=new \App\Notification;
                            $save->sender_id=$check_token['id'];
                            $save->title=$title;
                            $save->message=$body;
                            $save->title_ar = "تم حجز الجلسه بنجاح";
                            $save->message_ar = "شخص ما حجز جلسه الخاصه بيك";
                            $save->user_id=$user;
                            $save->created_at=$dateTime;
                            $save->save();
                            
                            
                         }catch(Exception $ex){
                              $message['error']=5;
                              $message['message']='error in send notification';
                              
                         }
                         $message['cash']=$price;
           
           if($payment == 3){
                $message['error'] = 8;
                $message['message'] = "waiting to paid";
           }else{
                $message['error']=0;
                $message['message']='session is reserved';
           }
          }else{
           
            $message['error']=1;
            $message['message']='error in reserve this session';
          }
                }else{
                     $message['error']=4;
                     $message['message']='this timeline is reserved';
                }
                 }else{
                     
                        $message['error']=8;
                     $message['message']=' you can\'t reserve old timeline ' ;
                 }
                
                
             }else{
                 
                 $message['error']=7;
                     $message['message']=' you can\'t reserve a timeline of you' ;
                
                 
             }
          }else{
             $message['error']=3;
            $message['message']='this token is exist'; 
          }

       }catch(Exception $ex){
         
            $message['error']=2;
            $message['message']='error'.$ex->getMessage();

       }
       return response()->json($message);
    }

       public function choose_payment(Request $request)
    {
       try{
            $id=$request->input('reservation_id');
            $payment=$request->input('payment_id');
            $token=$request->input('user_token');
            $lang=$request->input('lang');
                      
          
          $check_token=User::select('id')->where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $request->has('user_token') && $check_token !=NULL){
            

                $created_at = carbon::now()->toDateTimeString();
          $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($created_at)));

          $update=Reservation::where('id',$id)->update([
                 'payment_id'=>$payment,
                 'updated_at'=>$dateTime
          ]);
 
          if($update == true){

        
            $message['error']=0;
            $message['message']='session is reserved';

          }else{
           
            $message['error']=1;
            $message['message']='error in reserve this session';
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

//payments way
    public function show_payments(Request $request)
     {
      try{
          $token=$request->input('user_token');
           $lang=$request->input('lang');
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
              
              if($lang=='ar' || $lang=='AR'){
                  
                  $about=Payment::select('id','name','created_at','updated_at')->get();
              }else{
                   $about=Payment::select('id','E_name as name','created_at','updated_at')->get();
              }
        
          

                  if( count($about)>0){
                        $message['data']= $about;
                         $message['error']=0;
                        $message['message']='show payment data';
                    }else{
                         $message['data']= $about;
                         $message['error']=1;
                         $message['message']='no data ';
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
      public function show_allpayments(Request $request)
     {
      try{
          
              $lang=$request->input('lang');
              
              if($lang=='ar' || $lang=='AR'){
                  
                  $about=Payment::select('id','name','created_at','updated_at')->get();
              }else{
                   $about=Payment::select('id','E_name as name','created_at','updated_at')->get();
              }
        
          

                  if( count($about)>0){
                        $message['data']= $about;
                         $message['error']=0;
                        $message['message']='show payment data';
                    }else{
                         $message['data']= $about;
                         $message['error']=1;
                         $message['message']='no data ';
                    }

          
           
            }catch(Exception $ex){
                 $message['error']=2;
                 $message['message']='error'.$ex->getMessage();
            }  
         

       
         return response()->json($message);
     }

    public function show_paymentByid(Request $request)
    {
       try{
             $token=$request->input('user_token');
           $id=$request->input('id');
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
              
               
                  $select=Payment::select('id','name','E_name','created_at','updated_at')->where('id',$id)->first();
            
        
          

          if( $select !=null){
              $message['data']=$select;
              $message['error']=0;
              $message['message']='show payment';
          }else{
              $message['data']=$select;
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
    
     public function delete_payment(Request $request)
    {
       try{
            $token=$request->input('user_token');
           $lang=$request->input('lang');
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
       
           $id=$request->input('id');
            $select=Payment::where('id',$id)->delete();

          if( $select ==true){
             
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
    
     public function insert_payment(Request $request)
    {
       try{
           
            $token=$request->input('user_token');
           $lang=$request->input('lang');
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
       
           $name=$request->input('name');
             $e_name=$request->input('E_name');
                $created_at = carbon::now()->toDateTimeString();
          $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($created_at)));

           
            $select=new Payment;
            $select->name=$name;
            $select->E_name=$e_name;
            $select->created_at=$dateTime;
            $select->save();

          if( $select ==true){
              $message['data']=$select;
              $message['error']=0;
              $message['message']='insert payment';
          }else{
              $message['data']=$select;
              $message['error']=1;
              $message['message']='error in insert data';
          }
          }else{
               $message['error']=3;
              $message['message']='this token not exist';
          }
        }catch(Exception $ex){
             
              $message['error']=2;
              $message['message']='error'.$ex->getMessage();

        }
       return response()->json($message);
    }
    
    
     public function update_payment(Request $request)
    {
       try{
            $token=$request->input('user_token');
           $lang=$request->input('lang');
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
       
           $id=$request->input('id');
           $name=$request->input('name');
            $e_name=$request->input('E_name');
                $created_at = carbon::now()->toDateTimeString();
          $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($created_at)));

           
            $update=Payment::where('id',$id)->update([
                'name'=>$name,
                'E_name'=>$e_name,
                'updated_at'=>$dateTime
                
                ]);
            
             $select=Payment::where('id',$id)->first();
         
          if( $update ==true){
              $message['data']=$select;
              $message['error']=0;
              $message['message']='insert payment';
          }else{
              $message['data']=$select;
              $message['error']=1;
              $message['message']='error in insert data';
          }
          }else{
                $message['error']=3;
              $message['message']='this token not exist';
          }
       
        }catch(Exception $ex){
             
              $message['error']=2;
              $message['message']='error'.$ex->getMessage();

        }
       return response()->json($message);
    }

//user reservations
     public function show_usernext_session(Request $request)
     {
      try{
          $data = array();
      	  $token=$request->input('user_token');
      	   $lang=$request->input('lang');
                      
          
          $check_token=User::where('user_token',$token)->first();
          
          if($request->has('user_token') && $check_token !=NULL){
      	   $nowdate= date('Y-m-d');
        
      if($check_token['state']==4){
 
         $nowdate= date('Y-m-d');
        
                
                 if($lang=='ar' || $lang=='AR')
                 {
                     
                     
                     $about=Reservation::select('reservations.id as reservation_id','users.id as user_id','users.first_name as trainer_name','reservations.price','timeline.date','timeline.time_from','timeline.time_to','payment.name as payment_way','reservation_state.name as state','reservations.paid','reservations.created_at','reservations.updated_at')
                   ->join('timeline','reservations.timeline_id','=','timeline.id')
                    ->join('users','reservations.user_id','=','users.id')
                   
                   ->join('reservation_state','reservations.status_id','=','reservation_state.id')
                   ->leftJoin('payment','reservations.payment_id','=','payment.id')
                   ->where([['timeline.trainer_id',$check_token['id']],['timeline.date','>=',$nowdate] , ['reservations.status_id' , '!=' , 3] , ['reservations.status_id' , '!=' , 1]])->get();
                              
                               

                 }else{
                      $about=Reservation::select('reservations.id as reservation_id','users.id as user_id','users.first_name as trainer_name','reservations.price','timeline.date','timeline.time_from','timeline.time_to','payment.E_name as payment_way','reservation_state.E_name as state','reservations.paid','reservations.created_at','reservations.updated_at')
                     ->join('timeline','reservations.timeline_id','=','timeline.id')
                    ->join('users','reservations.user_id','=','users.id')
                  
                   ->join('reservation_state','reservations.status_id','=','reservation_state.id')
                    ->leftJoin('payment','reservations.payment_id','=','payment.id')
                   ->where([['timeline.trainer_id',$check_token['id']],['timeline.date','>=',$nowdate] , ['reservations.status_id' , '!=' , 3] , ['reservations.status_id' , '!=' , 1]])->get();
                     
                     
                     
                 }
                 $count=Reservation::join('timeline','reservations.timeline_id','=','timeline.id')->where([['timeline.trainer_id',$check_token['id']],['timeline.date','>=',$nowdate] , ['reservations.status_id' , '!=' , 3] , ['reservations.status_id' , '!=' , 1]])->count();
        }else{ 
             if($lang=='ar' || $lang=='AR'){
             
             
              $about=Reservation::select('reservations.id as reservation_id','users.id as user_id','users.first_name as trainer_name','users.image','reservations.price','timeline.date','timeline.time_from','timeline.time_to')
           ->join('timeline','reservations.timeline_id','=','timeline.id')
            ->join('users','timeline.trainer_id','=','users.id')
           ->leftjoin('payment','reservations.payment_id','=','payment.id')
           ->join('reservation_state','reservations.status_id','=','reservation_state.id')
           ->where([['reservations.user_id',$check_token['id']],['timeline.date','>=',$nowdate] , ['reservations.status_id' , '!=' , 3] , ['reservations.status_id' , '!=' , 1]])->get();
             
         }else{
              $about=Reservation::select('reservations.id as reservation_id','users.id as user_id','users.first_name as trainer_name','users.image','reservations.price','timeline.date','timeline.time_from','timeline.time_to')
           ->join('timeline','reservations.timeline_id','=','timeline.id')
            ->join('users','timeline.trainer_id','=','users.id')
           ->leftjoin('payment','reservations.payment_id','=','payment.id')
           ->join('reservation_state','reservations.status_id','=','reservation_state.id')
           ->where([['reservations.user_id',$check_token['id']],['timeline.date','>=',$nowdate] , ['reservations.status_id' , '!=' , 3] , ['reservations.status_id' , '!=' , 1]])->get();
             
             
             
         }
         
           $count=Reservation::join('timeline','reservations.timeline_id','=','timeline.id')->where([['reservations.user_id',$check_token['id']],['timeline.date','>=',$nowdate] , ['reservations.status_id' , '!=' , 3] , ['reservations.status_id' , '!=' , 1]])->count();

          
        }
        
         foreach($about as $each){
            
            $meetingData =  \App\Meeting::join('reservations' , 'meeting.timeline_id' ,'=' ,'reservations.timeline_id')
                                        ->where('reservations.id' , $each->reservation_id)->first();
            
            if($meetingData != NULL){
                array_push($data , (object)array(
                    "reservation_id" => $each->reservation_id,
                    "user_id" => $each->user_id,
                    "trainer_name" => $each->trainer_name,
                    "image" => $each->image,
                    "price" => $each->price,
                    "date" => $each->date,
                    "time_from" => $each->time_from,
                    "time_to" => $each->time_to,
                    "meeting_id" => $meetingData->meeting_id,
                    "host_id" => $meetingData->host_id,
                    "host_email" => $meetingData->host_email,
                    "topic" => $meetingData->topic,
                    "type" => $meetingData->type,
                    "status" => $meetingData->status,
                    "timezone" => $meetingData->timezone,
                    "create_time" => $meetingData->create_time,
                    "start_url" => $meetingData->start_url,
                    "join_url" => $meetingData->join_url,
                    "password" => $meetingData->password,
    
                    ));
            }else{
             array_push($data , (object)array(
                    "reservation_id" => $each->reservation_id,
                    "user_id" => $each->user_id,
                    "trainer_name" => $each->trainer_name,
                    "image" => $each->image,
                    "price" => $each->price,
                    "date" => $each->date,
                    "time_from" => $each->time_from,
                    "time_to" => $each->time_to,
                    "meeting_id" => NULL,
                    "host_id" => NULL,
                    "host_email" => NULL,
                    "topic" => NULL,
                    "type" => NULL,
                    "status" => NULL,
                    "timezone" => NULL,
                    "create_time" => NULL,
                    "start_url" => NULL,
                    "join_url" => NULL,
                    "password" => NULL,
    
                    ));
            }
                
        }


            if( count($about)>0){
              
                $message['data']= $data;
                $message['count']=$count;
                $message['error']=0;
                $message['message']='show  data';
            }else{
                $message['data']= $data;
                $message['count']=0;
                $message['error']=1;
                $message['message']='no data ';
            }
        }else{
           $message['error']=3;
           $message['message']='this token is not exist '; 
        }

            }catch(Exception $ex){
                 $message['error']=2;
                 $message['message']='error'.$ex->getMessage();
            }  
         

       
         return response()->json($message);
     }
     
    public function show_trainernext_session(Request $request)
     {
      try{
            $data = array();
      	    $token=$request->input('user_token');
      	    $lang=$request->input('lang');
                      
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
      	   $nowdate= date('Y-m-d');
        
     
        $nowdate= date('Y-m-d');
        
                
            if($lang=='ar' || $lang=='AR')
         {
             
             
              $about=Reservation::select('reservations.id as reservation_id','users.first_name as trainer_name','users.image','reservations.price','timeline.date','timeline.time_from','timeline.time_to')
           ->join('timeline','reservations.timeline_id','=','timeline.id')
            ->join('users','timeline.trainer_id','=','users.id')
           ->leftjoin('payment','reservations.payment_id','=','payment.id')
           ->join('reservation_state','reservations.status_id','=','reservation_state.id')
           ->where([['reservations.user_id',$check_token['id']],['timeline.date','>=',$nowdate], ['reservations.status_id' , '!=' , 1]])->get();
             
         }else{
              $about=Reservation::select('reservations.id as reservation_id','users.first_name as trainer_name','users.image','reservations.price','timeline.date','timeline.time_from','timeline.time_to')
           ->join('timeline','reservations.timeline_id','=','timeline.id')
            ->join('users','timeline.trainer_id','=','users.id')
           ->leftjoin('payment','reservations.payment_id','=','payment.id')
           ->join('reservation_state','reservations.status_id','=','reservation_state.id')
           ->where([['reservations.user_id',$check_token['id']],['timeline.date','>=',$nowdate] , ['reservations.status_id' , '!=' , 1]])->get();
             
             
             
         }
         
         
        foreach($about as $each){
            
            $meetingData =  \App\Meeting::join('reservations' , 'meeting.timeline_id' ,'=' ,'reservations.timeline_id')
                                        ->where('reservations.id' , $each->reservation_id)->first();
            
            array_push($data , (object)array(
                "reservation_id" => $each->reservation_id,
                "trainer_name" => $each->trainer_name,
                "image" => $each->image,
                "price" => $each->price,
                "date" => $each->date,
                "time_from" => $each->time_from,
                "time_to" => $each->time_to,
                "meeting_id" => $meetingData->meeting_id,
                "host_id" => $meetingData->host_id,
                "host_email" => $meetingData->host_email,
                "topic" => $meetingData->topic,
                "type" => $meetingData->type,
                "status" => $meetingData->status,
                "timezone" => $meetingData->timezone,
                "create_time" => $meetingData->create_time,
                "start_url" => $meetingData->start_url,
                "join_url" => $meetingData->join_url,
                "password" => $meetingData->password,

                ));
                
        }
         
           $count=Reservation::join('timeline','reservations.timeline_id','=','timeline.id')->where([['reservations.user_id',$check_token['id']],['timeline.date','>=',$nowdate] , ['reservations.status_id' , '!=' , 1]])->count();

        
            

                  if( count($about)>0){
                      
                        $message['data']= $data;
                        $message['count']=$count;
                         $message['error']=0;
                        $message['message']='show  data';
                    }else{
                         $message['data']= $data;
                           $message['count']=0;
                         $message['error']=1;
                         $message['message']='no data ';
                    }
          }else{
              $message['error']=3;
              $message['message']='this token is not exist '; 
          }

            }catch(Exception $ex){
                 $message['error']=2;
                 $message['message']='error'.$ex->getMessage();
            }  
         

       
         return response()->json($message);
     }
     
      public function show_wlynext_session(Request $request)
     {
      try{
      	  $token=$request->input('user_token');
      	   $lang=$request->input('lang');
                      
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
      	   $nowdate= date('Y-m-d');
        
     
        $nowdate= date('Y-m-d');
        
                
                 if($lang=='ar' || $lang=='AR')
                 {
                     
                     
                     $about=Reservation::select('reservations.id as reservation_id','users.first_name as user_name','reservations.price','timeline.date','timeline.time_from','timeline.time_to','payment.name as payment_way','reservation_state.name as state','reservations.paid','reservations.created_at','reservations.updated_at')
                   ->join('timeline','reservations.timeline_id','=','timeline.id')
                    ->join('users','reservations.user_id','=','users.id')
                   ->leftJoin('payment','reservations.payment_id','=','payment.id')
                   ->join('reservation_state','reservations.status_id','=','reservation_state.id')
                   ->where([['timeline.trainer_id',$check_token['id']],['timeline.date','>',$nowdate],['users.state',3]])->get();
                     
                 }else{
                      $about=Reservation::select('reservations.id as reservation_id','users.first_name as user_name','reservations.price','timeline.date','timeline.time_from','timeline.time_to','payment.E_name as payment_way','reservation_state.E_name as state','reservations.paid','reservations.created_at','reservations.updated_at')
                     ->join('timeline','reservations.timeline_id','=','timeline.id')
                    ->join('users','reservations.user_id','=','users.id')
                   ->leftJoin('payment','reservations.payment_id','=','payment.id')
                   ->join('reservation_state','reservations.status_id','=','reservation_state.id')
                   ->where([['timeline.trainer_id',$check_token['id']],['timeline.date','>',$nowdate],['users.state',3]])->get();
                     
                     
                     
                 }
                 $count=Reservation::join('timeline','reservations.timeline_id','=','timeline.id')->where([['timeline.trainer_id',$check_token['id']],['timeline.date','>',$nowdate],['users.state',3]])->count();

       


                  if( count($about)>0){
                      
                        $message['data']= $about;
                        $message['count']=$count;
                         $message['error']=0;
                        $message['message']='show  data';
                    }else{
                         $message['data']= $about;
                           $message['count']=0;
                         $message['error']=1;
                         $message['message']='no data ';
                    }
          }else{
              $message['error']=3;
              $message['message']='this token is not exist '; 
          }

            }catch(Exception $ex){
                 $message['error']=2;
                 $message['message']='error'.$ex->getMessage();
            }  
         

       
         return response()->json($message);
     }
     
     
     
        public function show_sugesstedsessions(Request $request)
     {
      try{
      	  $token=$request->input('user_token');
      	   $lang=$request->input('lang');
                      
          
          $check_token=User::select('id')->where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
      	   $nowdate= date('Y-m-d');
      	   
      	   
      	  $about=Reservation::join('timeline','reservations.timeline_id','=','timeline.id')
         
            ->join('users','timeline.trainer_id','=','users.id')
            ->join('trainer_specialties','timeline.trainer_id','=','trainer_specialties.trainer_id')
           // ->join('specialties','trainer_specialties.specialist_id','=','specialties.id')
            ->where('reservations.user_id',$check_token['id'])
        
             ->pluck('trainer_specialties.id')->toArray();
             
        //  
           
            $array= implode(',', $about);
            
            
             $myarr=array_map('intval', explode(',', $array));
           //  $array= $myarr;
            // var_dump($myarr);
          //   return $myarr;
           
           
           
           $data=DB::select('select DISTINCT users.first_name,users.last_name,users.image,users.rate,(SELECT MIN(service.price)from service WHERE service.trainer_id=users.id) as price,timeline.date,timeline.time_from,timeline.time_to from users
         JOIN timeline ON users.id =timeline.trainer_id
          JOIN trainer_specialties ON timeline.trainer_id =trainer_specialties.trainer_id
         where  timeline.date > '.$nowdate.'AND trainer_specialties.id IN '.$myarr);
        
        
           
             
              /*$data=Timeline::select('timeline.id','users.first_name as trainer_name','users.image','timeline.date','timeline.time_from','timeline.time_to')
              
                  ->join('time','timeline.time_id','=','time.id')
              //->join('service','timeline.time_id','=','service.time_id')  
            ->join('users','timeline.trainer_id','=','users.id')
             ->join('trainer_specialties','timeline.trainer_id','=','trainer_specialties.trainer_id')
           ->where('timeline.date','>',$nowdate)->whereIn('trainer_specialties.id',$about)->distinct()->get();*/
             
        
          
      	  


                  if( count($data)>0){
                      
                    //  $count=Reservation::join('timeline','reservations.timeline_id','=','timeline.id')->where([['reservations.user_id',$check_token['id']],['timeline.date','>',$nowdate]])->count();
                        $message['data']= $data;
                     //   $message['count']=$count;
                         $message['error']=0;
                        $message['message']='show  data';
                    }else{
                         $message['data']= $data;
                           $message['count']=0;
                         $message['error']=1;
                         $message['message']='no data ';
                    }
          }else{
              $message['error']=3;
              $message['message']='this token is not exist '; 
          }

            }catch(Exception $ex){
                 $message['error']=2;
                 $message['message']='error'.$ex->getMessage();
            }  
         

       
         return response()->json($message);
     }

      public function show_user_previouse_sessions(Request $request)
     {
      try{
          $token=$request->input('user_token');
      	   $lang=$request->input('lang');
                      
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
      	   $nowdate= date('Y-m-d');
        
       
         if($check_token['state']==4){
              $nowdate= date('Y-m-d');
        
                
                 if($lang=='ar' || $lang=='AR')
                 {
                     
                     
                      $about=Reservation::select('reservations.id as reservation_id','users.first_name as user_name','reservations.price','timeline.date','timeline.time_from','timeline.time_to','payment.name as payment_way','reservation_state.name as state','reservations.paid','reservations.created_at','reservations.updated_at')
                   ->join('timeline','reservations.timeline_id','=','timeline.id')
                    ->join('users','reservations.user_id','=','users.id')
                   ->leftJoin('payment','reservations.payment_id','=','payment.id')
                   ->join('reservation_state','reservations.status_id','=','reservation_state.id')
                   ->where([['timeline.trainer_id',$check_token['id']],['timeline.date','<',$nowdate]])->get();
                     
                 }else{
                      $about=Reservation::select('reservations.id as reservation_id','users.first_name as user_name','reservations.price','timeline.date','timeline.time_from','timeline.time_to','payment.E_name as payment_way','reservation_state.E_name as state','reservations.paid','reservations.created_at','reservations.updated_at')
                   ->join('timeline','reservations.timeline_id','=','timeline.id')
                    ->join('users','reservations.user_id','=','users.id')
                   ->leftJoin('payment','reservations.payment_id','=','payment.id')
                   ->join('reservation_state','reservations.status_id','=','reservation_state.id')
                   ->where([['timeline.trainer_id',$check_token['id']],['timeline.date','<',$nowdate]])->get();
                     
                     
                     
                 }
                         $count=Reservation::join('timeline','reservations.timeline_id','=','timeline.id')->where([['timeline.trainer_id',$check_token['id']],['timeline.date','<',$nowdate]])->count();
                 }else{
                     
                     
                if($lang=='ar' || $lang=='AR')
                 {
                     
                     
                      $about=Reservation::select('reservations.id as reservation_id','users.trainer_name as user_name','users.image','reservations.price','timeline.date','timeline.time_from','timeline.time_to')
                   ->join('timeline','reservations.timeline_id','=','timeline.id')
                    ->join('users','timeline.trainer_id','=','users.id')
                   ->leftjoin('payment','reservations.payment_id','=','payment.id')
                   ->join('reservation_state','reservations.status_id','=','reservation_state.id')
                   ->where([['reservations.user_id',$check_token['id']],['timeline.date','<',$nowdate]])->get();
                     
                 }else{
                      $about=Reservation::select('reservations.id as reservation_id','users.first_name as trainer_name','users.image','reservations.price','timeline.date','timeline.time_from','timeline.time_to')
                   ->join('timeline','reservations.timeline_id','=','timeline.id')
                    ->join('users','timeline.trainer_id','=','users.id')
                   ->leftjoin('payment','reservations.payment_id','=','payment.id')
                   ->join('reservation_state','reservations.status_id','=','reservation_state.id')
                   ->where([['reservations.user_id',$check_token['id']],['timeline.date','<',$nowdate]])->get();
                     
                     
                     
                 }
                  
             $count=Reservation::join('timeline','reservations.timeline_id','=','timeline.id')->where([['reservations.user_id',$check_token['id']],['timeline.date','<',$nowdate]])->count();

                 }




                  if( count($about)>0){
                      
                           $message['data']= $about;
                        
                            $message['count']= $count;
                         
                         $message['error']=0;
                        $message['message']='show  data';
                    }else{
                         $message['data']= $about;
                           $message['count']=0;
                         $message['error']=1;
                         $message['message']='no data ';
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

     public function cancel_usersession(Request $request)
     {
      try{
            $id=$request->input('reservation_id');
            $token=$request->input('user_token');
      	    $lang=$request->input('lang');
                      
          
          $check_token=User::where('user_token',$token)->first();

          
          if($request->has('user_token') && $check_token !=NULL){

            $updated_at = carbon::now()->toDateTimeString();
            $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($updated_at)));

              $check=Reservation::where('id',$id)->first();
              $time_id=Reservation::where('id',$id)->value('timeline_id');
     
                 $com = 0;     

                $trainer=Timeline::where('id',$check['timeline_id'])->value('trainer_id');
                $price=Reservation::where('id',$id)->value('price');
                $user_setting=setting::where('user_id',$trainer)->first();
                $com = str_replace('%', '', $user_setting['commission']);

                $user_data = \App\User::where('id' , $check->user_id)->first();
                
                        $cal_com=($com/$price)*100;
            if($check_token['id']==$check['user_id']){

           
           
                $order=Reservation::where('id',$id)->delete();


                
                       $get_user_token =User::select('firebase_token')->where('id', '=', $trainer)->first();
                       
                      // return $get_user_token[0]->firebase_token;
                
                               
                                 $title = 'Timeline is canceled';
                                 $body = $user_data->first_name.' '.$user_data->last_name." canceled the session ";
                                 $body_ar = "ألغت ".$user_data->first_name.' '.$user_data->last_name."الجلسة";
                
                                     ////////////////////////////////////////////////////////
                                     
                                       $msg = array
                                              (
                                    		'body' 	=> $body,
                                    		'title'	=> $title,
                                            'click_action' => "2" ,    	
                                              );
                                    	$fields = array
                                    			(
                                    				'to'		=> $get_user_token->firebase_token,
                                    				'data' =>$msg,
                                    				'notification'	=> $msg
                                    			);
                                    	
                                    	                 
                
                                    	$headers = array
                                    			(
                                    				'Authorization: key=' . API_ACCESS_KEY12,
                                    				'Content-Type: application/json'
                                    			);
                                    #Send Reponse To FireBase Server	
                                    		$ch = curl_init();
                                    		curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
                                    		curl_setopt( $ch,CURLOPT_POST, true );
                                    		curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
                                    		curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
                                    		curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
                                    		curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
                                    		$result = curl_exec($ch );
                                    		//echo $result;
                                    		curl_close( $ch );
                                 
                        
                        
                            $save=new \App\Notification;
                            $save->sender_id=$check_token['id'];
                            $save->title=$title;
                            $save->message=$body;
                            $save->title_ar = " تم الاغاء الحجز";
                            $save->message_ar = $body_ar;
                            $save->user_id=$trainer;
                            $save->created_at=$dateTime;
                            $save->save();

                 
            }else{
                

                $order=Reservation::where('id',$id)->update(['status_id'=>3,'updated_at'=>$dateTime]);

                  
                               try{       
                       //  $cc = 0;
                         
                         $title ='Timeline is canceled';
                         $body ='your timeline is canceled ';
                         $body_ar = "تم ألغاء حجزك";

                
                       $get_user_token =User::select('firebase_token')->where('id', '=',$check['user_id'])->first();
                       
                      // return $get_user_token[0]->firebase_token;
                
                
                                     ////////////////////////////////////////////////////////
                                     
                                       $msg = array
                                              (
                                    		'body' 	=> $body,
                                    		'title'	=> $title,
                                                 	
                                              );
                                    	$fields = array
                                    			(
                                    				'to'		=> $get_user_token['firebase_token'],
                                    				'notification'	=> $msg
                                    			);
                                    	
                                    	                 
                
                                    	$headers = array
                                    			(
                                    				'Authorization: key=' . API_ACCESS_KEY12,
                                    				'Content-Type: application/json'
                                    			);
                                    #Send Reponse To FireBase Server	
                                    		$ch = curl_init();
                                    		curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
                                    		curl_setopt( $ch,CURLOPT_POST, true );
                                    		curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
                                    		curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
                                    		curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
                                    		curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
                                    		$result = curl_exec($ch );
                                    		//echo $result;
                                    		curl_close( $ch );
                                 
                        
                        
                            $save=new \App\Notification;
                            $save->sender_id=$check_token['id'];
                            $save->title=$title;
                            $save->message=$body;
                            $save->title_ar = " تم الاغاء الحجز";
                            $save->message_ar = $body_ar;
                            $save->user_id=$check['user_id'];
                            $save->created_at=$dateTime;
                            $save->save();
                            
                            
                         }catch(Exception $ex){
                              $message['error']=5;
                               $message['message']='error in send notification';
                              
                         }
                
                
            }
            
             
          
                  
            
                     if($order ==true){

                     $update=Timeline::where('id',$time_id)->update([
                      'state_id'=>1,
                      'updated_at'=>$dateTime

                     ]);
                     
                     
                     
                               $trainer_cash=round($price-(($com/$price)*100));
                               $update_admin_setting=Wallet::where('user_id',1)->decrement('credit',round($cal_com));
                               $update_setting=setting::where('user_id',$trainer)->decrement('credit',$trainer_cash);                  
                               $update=Wallet::where('user_id',$check['user_id'])->increment('credit',$price);
                  
                   
                         $message['error']=0;
                        $message['message']='delete  data';
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

     //trainer reservation
     public function show_trainerWait_orders(Request $request)
     {
      try{
      	  // $user=$request->input('trainer_id');
      	      $token=$request->input('user_token');
           $lang=$request->input('lang');
                      
          
          $check_token=User::select('id')->where('user_token',$token)->first();
          
          
          if( $request->has('user_token') && $check_token !=NULL){
      	   $nowdate= date('Y-m-d');
        
        
         if($lang=='ar' || $lang=='AR')
         {
             
             
              $about=Reservation::select('reservations.id as reservation_id','users.first_name as user_name','reservations.price','timeline.date','timeline.time_from','timeline.time_to','payment.name as payment_way','reservation_state.name as state','reservations.paid','reservations.created_at','reservations.updated_at')
           ->join('timeline','reservations.timeline_id','=','timeline.id')
           ->join('time','timeline.time_id','=','time.id')
            ->join('users','reservations.user_id','=','users.id')
           ->join('payment','reservations.payment_id','=','payment.id')
           ->join('reservation_state','reservations.status_id','=','reservation_state.id')
           ->where([['timeline.trainer_id',$check_token['id']],['timeline.date','>',$nowdate]])->get();
             
         }else{
              $about=Reservation::select('reservations.id as reservation_id','users.first_name as user_name','reservations.price','timeline.date','timeline.time_from','timeline.time_to','payment.E_name as payment_way','reservation_state.E_name as state','reservations.paid','reservations.created_at','reservations.updated_at')
           ->join('timeline','reservations.timeline_id','=','timeline.id')
           ->join('time','timeline.time_id','=','time.id')
            ->join('users','reservations.user_id','=','users.id')
           ->join('payment','reservations.payment_id','=','payment.id')
           ->join('reservation_state','reservations.status_id','=','reservation_state.id')
           ->where([['timeline.trainer_id',$check_token['id']],['timeline.date','>',$nowdate]])->get();
             
             
             
         }
          


                  if( count($about)>0){
                        $message['data']= $about;
                         $message['error']=0;
                        $message['message']='show data';
                    }else{
                         $message['data']= $about;
                         $message['error']=1;
                         $message['message']='no data ';
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
           public function show_trainerprevious_orders(Request $request)
     {
      try{
      	   $token=$request->input('user_token');
           $lang=$request->input('lang');
                      
          
          $check_token=User::select('id')->where('user_token',$token)->first();
          
          
          if( $request->has('user_token') && $check_token !=NULL){
      	   $nowdate= date('Y-m-d');
        
        
        
         if($lang=='ar' || $lang=='AR')
         {
             
             
              $about=Reservation::select('reservations.id as reservation_id','users.first_name as user_name','reservations.price','timeline.date','timeline.time_from','timeline.time_to','payment.name as payment_way','reservation_state.name as state','reservations.paid','reservations.created_at','reservations.updated_at')
           ->join('timeline','reservations.timeline_id','=','timeline.id')
           ->join('time','timeline.time_id','=','time.id')
            ->join('users','reservations.user_id','=','users.id')
           ->join('payment','reservations.payment_id','=','payment.id')
           ->join('reservation_state','reservations.status_id','=','reservation_state.id')
           ->where([['timeline.trainer_id',$check_token['id']],['timeline.date','<',$nowdate]])->get();
             
         }else{
              $about=Reservation::select('reservations.id as reservation_id','users.first_name as user_name','reservations.price','timeline.date','timeline.time_from','timeline.time_to','payment.E_name as payment_way','reservation_state.E_name as state','reservations.paid','reservations.created_at','reservations.updated_at')
           ->join('timeline','reservations.timeline_id','=','timeline.id')
           ->join('time','timeline.time_id','=','time.id')
            ->join('users','reservations.user_id','=','users.id')
           ->join('payment','reservations.payment_id','=','payment.id')
           ->join('reservation_state','reservations.status_id','=','reservation_state.id')
           ->where([['timeline.trainer_id',$check_token['id']],['timeline.date','<',$nowdate]])->get();
             
             
             
         }


                  if( count($about)>0){
                        $message['data']= $about;
                         $message['error']=0;
                        $message['message']='show orders data';
                    }else{
                         $message['data']= $about;
                         $message['error']=1;
                         $message['message']='no data ';
                    }
          }else{
               $message['error']=3;
               $message['message']='this token is not exist ';
          }

            }catch(Exception $ex){
                 $message['error']=2;
                 $message['message']='error'.$ex->getMessage();
            }  
         

       
         return response()->json($message);
     }


     public function acceptReservation(Request $request){
    try{
        
         $reservation_id =$request->input('reservation_id');
        


          $created_at = carbon::now()->toDateTimeString();
          $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($created_at)));

            $order=Reservation::where('id',$reservation_id)->update(['status_id'=>2,'updated_at'=>$dateTime]);

             $about=Reservation::select('reservations.id','reservations.price','timeline.date','timeline.time_from','timeline.time_to','payment.name','reservations.created_at','reservations.updated_at')
                              ->leftjoin('timeline','reservations.timeline_id','=','timeline.id')
                              ->leftjoin('time','timeline.time_id','=','time.id')
                              ->leftjoin('payment','reservations.payment_id','=','payment.id')
                              ->where('reservations.id',$reservation_id)->first();

           
       
              if($order==true){
                  
                  $user_id = \App\Reservation::where('reservations.id',$reservation_id)->value('user_id');
                  
                   $get_user_token =User::select('firebase_token')->where('id', '=',$user_id)->first();
                       
                       $title = "Rafiiq Sessions";
                       $body  = "Your reservation session is accepted ";
                              $msg = array
                                      (
                            		'body' 	=> $body,
                            		'title'	=> $title,
                                    'click_action' => '4',     	
                                      );
                            	$fields = array
                            			(
                            				'to'		=> $get_user_token['firebase_token'],
                            				'data'      => $msg,
                            				'notification'	=> $msg
                            			);
                            	
                            	                 
        
                            	$headers = array
                            			(
                            				'Authorization: key=' . API_ACCESS_KEY12,
                            				'Content-Type: application/json'
                            			);
                            #Send Reponse To FireBase Server	
                            		$ch = curl_init();
                            		curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
                            		curl_setopt( $ch,CURLOPT_POST, true );
                            		curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
                            		curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
                            		curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
                            		curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
                            		$result = curl_exec($ch );
                            		//echo $result;
                            		curl_close( $ch );
                         
                        
                        
                            $save=new \App\Notification;
                            $save->sender_id=1;
                            $save->title=$title;
                            $save->message=$body;
                            $save->title_ar = "أشعارات رفيق";
                            $save->message_ar = "تم قبول جلسة الحجز الخاصة بك";
                            $save->user_id=$user_id;
                            $save->created_at=$dateTime;
                            $save->save();
                  $message['reservation_data']=$about;
                  $message['error']=0;
                  $message['message']=' reservation state is changed';

              }else{
                  $message['reservation_data']=$about;
                  $message['error']=1;
                  $message['message']='can not change reservation state';
              }

    }catch(Exception $ex){
              $message['error']=3;
             $message['message']='error'.$ex->getMessage();

    }

    return response()->json($message);

  }
  
  
   public function cancel_Reservation(Request $request){
    try{
        
         $reservation_id =$request->input('reservation_id');
        


          $created_at = carbon::now()->toDateTimeString();
          $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($created_at)));

          $order=Reservation::where('id',$reservation_id)->update(['status_id'=>3,'updated_at'=>$dateTime]);

             $about=Reservation::select('reservations.id','reservations.price','timeline.date','timeline.time_from','timeline.time_to','payment.name','reservations.created_at','reservations.updated_at')
                              ->leftjoin('timeline','reservations.timeline_id','=','timeline.id')
                              ->leftjoin('time','timeline.time_id','=','time.id')
                              ->leftjoin('payment','reservations.payment_id','=','payment.id')
                              ->where('reservations.id',$reservation_id)->first();

           
       
              if($order==true){
                  $message['reservation_data']=$about;
                  $message['error']=0;
                  $message['message']=' reservation state is changed';

              }else{
                  $message['reservation_data']=$about;
                  $message['error']=1;
                  $message['message']='can not change reservation state';
              }

    }catch(Exception $ex){
              $message['error']=3;
             $message['message']='error'.$ex->getMessage();

    }

    return response()->json($message);

  }
  public function cancelReservation(Request $request){
    try{
        
         $order=$request->input('reservation_id');
        


          $created_at = carbon::now()->toDateTimeString();
          $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($created_at)));
          
          $reserve=Reservation::where('id',$order)->first();
          
          
          
         $trainer=Timeline::where('id',$reserve['timeline_id'])->value('trainer_id');

          $price=Reservation::where('id',$order)->value('price');
            $user_setting=setting::where('user_id',$trainer)->first();
            $com = str_replace('%', '', $user_setting['commission']);
            
            $cal_com=($com/$price)*100;

          $order=Reservation::where('id',$order)->update(['state'=>3,'updated_at'=>$dateTime]);

  

           
       
              if($order==true){
                  
                             
                            $trainer_cash=round($price-(($com/$price)*100));
                            $update_admin_setting=Wallet::where('user_id',1)->decrement('credit',round($cal_com));
                            $update_setting=setting::where('user_id',$trainer)->decrement('credit',$trainer_cash);                  
                            $update=Wallet::where('user_id',$reserve['user_id'])->increment('credit',$price);
                  
                  $message['error']=0;
                  $message['message']=' reservation state is changed';

              }else{
                  $message['error']=1;
                  $message['message']='can not change reservation state';
              }

    }catch(Exception $ex){
              $message['error']=3;
             $message['message']='error'.$ex->getMessage();

    }

    return response()->json($message);

  }

  //for admin
  
   public function search_reservations(Request $request)
     {
      try{
           
        
             $token=$request->input('user_token');
             $lang=$request->input('lang');
             $from=$request->input('date_from');
             $to=$request->input('date_to');
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
              
              
              
              
               if($lang=='ar' || $lang=='AR'){
                    $about=Reservation::select('reservations.id','user.first_name as user_name','trainer.first_name as trainer_name','reservations.price','timeline.date','timeline.time_from','timeline.time_to','payment.name as payment','reservation_state.name as state','reservations.created_at','reservations.updated_at')
           ->join('timeline','reservations.timeline_id','=','timeline.id')
           ->join('users as trainer','timeline.trainer_id','=','trainer.id')
           ->leftjoin('payment','reservations.payment_id','=','payment.id')
           ->join('reservation_state','reservations.status_id','=','reservation_state.id')
           ->join('users as user','reservations.user_id','=','user.id')
           ->whereBetween('reservations.created_at',array($from, $to))
            ->where('reservations.status_id',1)->orWhere('reservations.status_id',2)->orWhere('reservations.status_id',4)->orderBy('reservations.id')
           ->get();
               }else{
                    $about=Reservation::select('reservations.id','user.first_name as user_name','trainer.first_name as trainer_name','reservations.price','timeline.date','timeline.time_from','timeline.time_to','payment.E_name as payment','reservation_state.E_name as state','reservations.created_at','reservations.updated_at')
           ->join('timeline','reservations.timeline_id','=','timeline.id')
           ->join('users as trainer','timeline.trainer_id','=','trainer.id')
           ->leftjoin('payment','reservations.payment_id','=','payment.id')
           ->join('reservation_state','reservations.status_id','=','reservation_state.id')
           ->join('users as user','reservations.user_id','=','user.id')
           ->whereBetween('reservations.created_at',array($from, $to))
           ->where('reservations.status_id',1)->orWhere('reservations.status_id',2)->orWhere('reservations.status_id',4)->orderBy('reservations.id')

           ->get();
               }
        
           




                  if( count($about)>0){
                        $message['data']= $about;
                         $message['error']=0;
                        $message['message']='show  data';
                    }else{
                         $message['data']= $about;
                         $message['error']=1;
                         $message['message']='no data ';
                    }

         
          }else{
                  $message['error']=3;
                 $message['message']='this token is not exit';
          }
         
            }catch(Exception $ex){
                 $message['error']=2;
                 $message['message']='error'.$ex->getMessage();
            }  
         

       
         return response()->json($message);
     }
  
  
  ///for dash
  
    public function show_allreservations(Request $request)
     {
      try{
           
        
             $token=$request->input('user_token');
             $lang=$request->input('lang');
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
              
               if($lang=='ar' || $lang=='AR'){
                    $about=Reservation::select('reservations.id','user.first_name as user_name','trainer.first_name as trainer_name','reservations.price','timeline.date','timeline.time_from','timeline.time_to','payment.name as payment','reservation_state.name as state','reservations.created_at','reservations.updated_at')
           ->join('timeline','reservations.timeline_id','=','timeline.id')
           ->join('users as trainer','timeline.trainer_id','=','trainer.id')
           ->leftjoin('payment','reservations.payment_id','=','payment.id')
           ->join('reservation_state','reservations.status_id','=','reservation_state.id')
           ->join('users as user','reservations.user_id','=','user.id')
           ->where('reservations.status_id',1)->orWhere('reservations.status_id',2)->orderBy('reservations.id')
           ->get();
               }else{
                    $about=Reservation::select('reservations.id','user.first_name as user_name','trainer.first_name as trainer_name','reservations.price','timeline.date','timeline.time_from','timeline.time_to','payment.E_name as payment','reservation_state.E_name as state','reservations.created_at','reservations.updated_at')
           ->join('timeline','reservations.timeline_id','=','timeline.id')
           ->join('users as trainer','timeline.trainer_id','=','trainer.id')
           ->leftjoin('payment','reservations.payment_id','=','payment.id')
           ->join('reservation_state','reservations.status_id','=','reservation_state.id')
           ->join('users as user','reservations.user_id','=','user.id')
           ->where('reservations.status_id',1)->orWhere('reservations.status_id',2)->orderBy('reservations.id')

           ->get();
               }
        
           




                  if( count($about)>0){
                        $message['data']= $about;
                         $message['error']=0;
                        $message['message']='show  data';
                    }else{
                         $message['data']= $about;
                         $message['error']=1;
                         $message['message']='no data ';
                    }

         
          }else{
                  $message['error']=3;
                 $message['message']='this token is not exit';
          }
         
            }catch(Exception $ex){
                 $message['error']=2;
                 $message['message']='error'.$ex->getMessage();
            }  
         

       
         return response()->json($message);
     }
     
     
       public function show_acceptedreservations(Request $request)
     {
      try{
           
        
             $token=$request->input('user_token');
             $lang=$request->input('lang');
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
              
               if($lang=='ar' || $lang=='AR'){
                    $about=Reservation::select('reservations.id','user.first_name as user_name','trainer.first_name as trainer_name','reservations.price','timeline.date','timeline.time_from','timeline.time_to','payment.name as payment','reservation_state.name as state','reservations.created_at','reservations.updated_at')
           ->join('timeline','reservations.timeline_id','=','timeline.id')
           ->join('users as trainer','timeline.trainer_id','=','trainer.id')
           ->leftjoin('payment','reservations.payment_id','=','payment.id')
           ->join('reservation_state','reservations.status_id','=','reservation_state.id')
           ->join('users as user','reservations.user_id','=','user.id')
           ->where('reservations.status_id',2)->orderBy('reservations.id')
           ->get();
               }else{
                    $about=Reservation::select('reservations.id','user.first_name as user_name','trainer.first_name as trainer_name','reservations.price','timeline.date','timeline.time_from','timeline.time_to','payment.E_name as payment','reservation_state.E_name as state','reservations.created_at','reservations.updated_at')
           ->join('timeline','reservations.timeline_id','=','timeline.id')
           ->join('users as trainer','timeline.trainer_id','=','trainer.id')
           ->leftjoin('payment','reservations.payment_id','=','payment.id')
           ->join('reservation_state','reservations.status_id','=','reservation_state.id')
           ->join('users as user','reservations.user_id','=','user.id')
           ->where('reservations.status_id',2)->orderBy('reservations.id')

           ->get();
               }
        
           




                  if( count($about)>0){
                        $message['data']= $about;
                         $message['error']=0;
                        $message['message']='show  data';
                    }else{
                         $message['data']= $about;
                         $message['error']=1;
                         $message['message']='no data ';
                    }

         
          }else{
                  $message['error']=3;
                 $message['message']='this token is not exit';
          }
         
            }catch(Exception $ex){
                 $message['error']=2;
                 $message['message']='error'.$ex->getMessage();
            }  
         

       
         return response()->json($message);
     }
     
     
       public function show_canceledreservations(Request $request)
     {
      try{
           
        
             $token=$request->input('user_token');
             $lang=$request->input('lang');
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
              
               if($lang=='ar' || $lang=='AR'){
                    $about=Reservation::select('reservations.id','user.first_name as user_name','trainer.first_name as trainer_name','reservations.price','timeline.date','timeline.time_from','timeline.time_to','payment.name as payment','reservation_state.name as state','reservations.created_at','reservations.updated_at')
           ->join('timeline','reservations.timeline_id','=','timeline.id')
           ->join('users as trainer','timeline.trainer_id','=','trainer.id')
           ->leftjoin('payment','reservations.payment_id','=','payment.id')
           ->join('reservation_state','reservations.status_id','=','reservation_state.id')
           ->join('users as user','reservations.user_id','=','user.id')
           ->where('reservations.status_id',3)->orderBy('reservations.id')
           ->get();
               }else{
                    $about=Reservation::select('reservations.id','user.first_name as user_name','trainer.first_name as trainer_name','reservations.price','timeline.date','timeline.time_from','timeline.time_to','payment.E_name as payment','reservation_state.E_name as state','reservations.created_at','reservations.updated_at')
           ->join('timeline','reservations.timeline_id','=','timeline.id')
           ->join('users as trainer','timeline.trainer_id','=','trainer.id')
           ->leftjoin('payment','reservations.payment_id','=','payment.id')
           ->join('reservation_state','reservations.status_id','=','reservation_state.id')
           ->join('users as user','reservations.user_id','=','user.id')
           ->where('reservations.status_id',3)->orderBy('reservations.id')

           ->get();
               }
        
           




                  if( count($about)>0){
                        $message['data']= $about;
                         $message['error']=0;
                        $message['message']='show  data';
                    }else{
                         $message['data']= $about;
                         $message['error']=1;
                         $message['message']='no data ';
                    }

         
          }else{
                  $message['error']=3;
                 $message['message']='this token is not exit';
          }
         
            }catch(Exception $ex){
                 $message['error']=2;
                 $message['message']='error'.$ex->getMessage();
            }  
         

       
         return response()->json($message);
     }
     
       public function show_reservationshistory(Request $request)
     {
      try{
           
        
             $token=$request->input('user_token');
             $lang=$request->input('lang');
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
              
               if($lang=='ar' || $lang=='AR'){
                    $about=Reservation::select('reservations.id','user.first_name as user_name','trainer.first_name as trainer_name','reservations.price','timeline.date','timeline.time_from','timeline.time_to','payment.name as payment','reservation_state.name as state','reservations.created_at','reservations.updated_at')
           ->join('timeline','reservations.timeline_id','=','timeline.id')
           ->join('users as trainer','timeline.trainer_id','=','trainer.id')
           ->leftjoin('payment','reservations.payment_id','=','payment.id')
           ->join('reservation_state','reservations.status_id','=','reservation_state.id')
           ->join('users as user','reservations.user_id','=','user.id')
           ->where('reservations.status_id',4)->orderBy('reservations.id')

           ->get();
               }else{
                    $about=Reservation::select('reservations.id','user.first_name as user_name','trainer.first_name as trainer_name','reservations.price','timeline.date','timeline.time_from','timeline.time_to','payment.E_name as payment','reservation_state.E_name as state','reservations.created_at','reservations.updated_at')
           ->join('timeline','reservations.timeline_id','=','timeline.id')
           ->join('users as trainer','timeline.trainer_id','=','trainer.id')
           ->leftjoin('payment','reservations.payment_id','=','payment.id')
           ->join('reservation_state','reservations.status_id','=','reservation_state.id')
           ->join('users as user','reservations.user_id','=','user.id')
           ->where('reservations.status_id',4)->orderBy('reservations.id')

           ->get();
               }
        
           




                  if( count($about)>0){
                        $message['data']= $about;
                         $message['error']=0;
                        $message['message']='show  data';
                    }else{
                         $message['data']= $about;
                         $message['error']=1;
                         $message['message']='no data ';
                    }

         
          }else{
                  $message['error']=3;
                 $message['message']='this token is not exit';
          }
         
            }catch(Exception $ex){
                 $message['error']=2;
                 $message['message']='error'.$ex->getMessage();
            }  
         

       
         return response()->json($message);
     }
     
     
     
     
     

    public function show_sessionـby_id(Request $request)
    {
         try{
             
                $token=$request->input('user_token');


                      
          
                $check_token=User::where('user_token',$token)->first();
          
          
                if($request->has('user_token') && $check_token !=NULL){



                    $select=User::where('id',$check_token['id'])->value('video');
                  
                    if($select != null){
        
                        $message['data']=$select;
                        $message['error']=0;
                        $message['message']='video show ';
            
                    }else{
                        $message['data']=NULL;
                        $message['error']=1;
                        $message['message']='error in show this video';
                    }
              
          }else{
             $message['error']=3;
            $message['message']='this token is exist'; 
          }

       }catch(Exception $ex){
         
            $message['error']=2;
            $message['message']='error'.$ex->getMessage();

       }
       return response()->json($message);  
    }
}
