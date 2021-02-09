<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use carbon\carbon;
use App\Social_status;
use App\Payment;
use App\Notification;
use App\Wallet;
use App\setting;
use App\User;
use App\Test_result;

define( 'API_ACCESS_KEY12','AAAAz-GV50A:APA91bFXom0sxT5MNl_IQUbzEEsxJK55cgoI6k4ucPJctyN8wshU_ZOGZnADHyFfKOvcOxLsfuaAKrTJ3-2lGjmzdDAFH2IhiP0dtEtLMUxdyt5ZOPacOq5D9wHk0W0Wgxlncu3iMPl7');

class PaymentController extends Controller
{
    public $message=array();
      
  
  
  
     public function show_Notifications(Request $request)
    {
       try{
           $token=$request->input('user_token');
          
          
          $check_token=User::select('id')->where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
        
        

         $role=Notification::where('user_id',$check_token['id'])->get();
          

                  if(count($role) >0){
        
                    $message['data']=$role;
                    $message['error']=0;
                    $message['message']='show successfully';
        
                  }else{
                    $message['data']=$role;
                    $message['error']=1;
                    $message['message']='no data ';
                  }
          }else{
              $message['error']=3;
            $message['message']='this token is\'t exist';
          }

       }catch(Exception $ex){
         
            $message['error']=2;
            $message['message']='error'.$ex->getMessage();

       }
       return response()->json($message);
    }
    
     public function show_social_status(Request $request)
    {
       try{
         
        

         $role=Social_status::select('id','name')->get();
          

                  if(count($role) >0){
        
                    $message['data']=$role;
                    $message['error']=0;
                    $message['message']='show successfully';
        
                  }else{
                    $message['data']=$role;
                    $message['error']=1;
                    $message['message']='no data ';
                  }
          

       }catch(Exception $ex){
         
            $message['error']=2;
            $message['message']='error'.$ex->getMessage();

       }
       return response()->json($message);
    }
    
    
   public function show_result(Request $request)
      {
       try{
          $token=$request->input('user_token');
          
          $check_token=User::select('id')->where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
              
           $pay=Test_result::select('category.name as test_type','subcategory.name as test_name','test_result.result','test_result.created_at')
           ->join('subcategory','test_result.test_id','=','subcategory.id')
           ->join('category','subcategory.cat_id','=','category.id')
           ->where('test_result.user_id',$check_token['id'])->get();
       
     
    
            if($pay != null){
                 $message['data']=$pay;
                 $message['error']=0;
                 $message['message']='show data success';
             }else{
                 $message['data']=$pay;
                 $message['error']=1;
                 $message['message']='';
             }
          }else{
               $message['error']=3;
                 $message['message']='this token is not exist';
          }
       }catch(Exception $ex){

           $message['error']=2;
           $message['message']='error'.$ex->getMessage();
       }


         return  response()->json($message);
      } 
  
  
  
  



      public function show_wallet(Request $request)
      {
       try{
          $token=$request->input('user_token');
          
          $check_token=User::select('id')->where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
              
           $pay=Wallet::where('user_id',$check_token['id'])->value('credit');
           $has=setting::where('user_id',$check_token['id'])->value('credit');
       
     
    
            if($pay != null){
                 $message['credit']=$pay;
                 $message['has']=$has;
                 $message['error']=0;
                 $message['message']='show payments  in wallet';
             }else{
                 $message['credit']=$pay;
                 $message['error']=1;
                 $message['message']='no payments choosed yet';
             }
          }else{
               $message['error']=3;
                 $message['message']='this token is not exist';
          }
       }catch(Exception $ex){

           $message['error']=2;
           $message['message']='error'.$ex->getMessage();
       }


         return  response()->json($message);
      } 
      
      

   public function show_PreviousDealings(Request $request)
      {
       try{
          $token=$request->input('user_token');
          $id=$request->input('id');
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
              
              
              if($check_token['state']==1 && $request->has('id')){
                  
                    $pay=Wallet::select('sender.first_name as sender','receiver.first_name as receiver_name','wallet.value','dealing_type.name as dealing_name','wallet.created_at')
                    ->join('users as sender','wallet.user_id','=','sender.id') 
                    ->join('users as receiver','wallet.receiver_id','=','receiver.id') 
                    ->join('dealing_type','wallet.type','=','dealing_type.id')
                    ->where('user_id',$id)->orWhere('receiver_id',$id)->get();
                  
                  
              }else{
              
                     $pay=Wallet::select('sender.first_name as sender','receiver.first_name as receiver_name','wallet.value','dealing_type.name as dealing_name','wallet.created_at')
                    ->join('users as sender','wallet.user_id','=','sender.id') 
                    ->join('users as receiver','wallet.receiver_id','=','receiver.id') 
                    ->join('dealing_type','wallet.type','=','dealing_type.id')
                    ->where('user_id',$check_token['id'])->orWhere('receiver_id',$check_token['id'])->get();
        
              }
    
            if(count($pay)>0){
                 $message['data']=$pay;
                 $message['error']=0;
                 $message['message']='show previous dealing';
             }else{
                 $message['data']=$pay;
                 $message['error']=1;
                 $message['message']='no dealings yet';
             }
          }else{
               $message['error']=3;
                 $message['message']='this token is not exist';
          }
       }catch(Exception $ex){

           $message['error']=2;
           $message['message']='error'.$ex->getMessage();
       }


         return  response()->json($message);
      } 
      
      
          
          
     public function enter_copon(Request $request)
      {
       try{
           $user=$request->input('user_id');
           $copon=$request->input('copon');
           
       $pay_copon=Wallet::where('user_id',$user)->update(['copon'=>$copon]);

            $pay['wallet']=Wallet::select('credit','copon')->where('user_id',$user)->get();
             $pay['payments']=Payment::where('user_id',$user)->get();
    
            if($pay_copon ==0){
                 $message['payment_data']=$pay;
                 $message['error']=0;
                 $message['message']='add coupon to  payments';
             }else{
                 $message['payment_data']=$pay;
                 $message['error']=1;
                 $message['message']='can not add copoun to this payments ';
             }
       }catch(Exception $ex){

           $message['error']=2;
           $message['message']='error'.$ex->getMessage();
       }


         return  response()->json($message);
      }   


     public function update_commission(Request $request)
      {
       try{
            $token=$request->input('user_token');
            $user=$request->input('id');
            $comission=$request->input('comission');
            
                $created_at = carbon::now()->toDateTimeString();
          $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($created_at)));
          
          $check_token=User::select('id')->where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
              
              
       $update=setting::where('user_id',$user)->update([
           'commission'=>$comission,
           'updated_at'=>$dateTime]);

       
    
            if($update ==true){
               
                 $message['error']=0;
                 $message['message']='update commission ';
             }else{
                
                 $message['error']=1;
                 $message['message']='can not update data';
             }
         }else{
               $message['error']=3;
                 $message['message']='this token is not exist';
          }     
       }catch(Exception $ex){

           $message['error']=2;
           $message['message']='error'.$ex->getMessage();
       }


         return  response()->json($message);
      }   



      Public function recharge_wallet(Request $request)
      {
          try{

               $token=$request->input('user_token');
          
          $check_token=User::select('id')->where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
              
                $cash=$request->input('cash');
                $user=$request->input('user_id');
                $pass=$request->input('password');
                $payment=$request->input('payment'); //vodavon cash    mastercard
                 

          $created_at = carbon::now()->toDateTimeString();
          $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($created_at)));
          
          
          if($request->has('user_id')){
              
             $increasecash=Wallet::where('user_id',$user)->increment('credit',$cash);
             $updatesetting=setting::where('user_id',$user)->decrement('credit',$cash);
             $updatecash=Wallet::where([['user_id',$user],['credit','!=','NULL']])->update(['updated_at'=>$dateTime]);
             $updatesetting=setting::where('user_id',$user)->update(['updated_at'=>$dateTime]);
             
                $updatesetting=Wallet::where('user_id',1)->decrement('credit',$cash);

             
             
                  if($increasecash ==true){
                        
                                        $insert=new Wallet;
                                        $insert->user_id=$check_token['id'];
                                        $insert->receiver_id=$user;
                                        $insert->value=$cash;
                                        $insert->type=3;
                                        $insert->created_at=$dateTime;
                                        $insert->save();
                                        
                                        
                                        
                                                    try{       
                       //  $cc = 0;
                         
                         $title ='the admin recharge your account';
                         $body ='your account is recharged with  value ='.$cash;
                         $title_ar = "يقوم المسؤول بشحن حسابك";
                         $body_ar =   " تم شحن حسابك بقيمة :" .$cash;
                
                       $get_user_token =User::select('firebase_token')->where('id', '=',$user)->first();
                       
                      // return $get_user_token[0]->firebase_token;
                
                
                                     ////////////////////////////////////////////////////////
                                     
                                       $msg = array
                                              (
                                    		'body' 	=> $body,
                                    		'title'	=> $title,
                                            'click_action' => '5',     	
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
                            $save->sender_id=$check_token['id'];
                            $save->title=$title;
                            $save->message=$body;
                            $save->title_ar = $title_ar;
                            $save->message_ar = $body_ar;
                            $save->user_id=$user;
                            $save->created_at=$dateTime;
                            $save->save();
                            
                            
                         }catch(Exception $ex){
                               $message['error']=5;
                               $message['message']='error in send notification';
                              
                         }
                        
                         $message['error']=0;
                         $message['message']='recharge success';
                     }else{
                        
                         $message['error']=1;
                         $message['message']='can not add creait to wallet';
                     }

              
              
          }else{

                      $increasecash=Wallet::where('user_id',$check_token['id'])->increment('credit',$cash);
                      
                     $updatecash=Wallet::where([['user_id',$check_token['id']],['credit','!=','NULL']])->update(['updated_at'=>$dateTime]);
               
                   
                   
                    if($increasecash ==true){
                        
                        $insert=new Wallet;
                        $insert->user_id=$check_token['id'];
                      //  $insert->receiver_id=$check['id'];
                        $insert->value=$cash;
                        $insert->type=3;
                        $insert->created_at=$dateTime;
                        $insert->save();
                        
                         $message['error']=0;
                         $message['message']='increase credit in wallet';
                     }else{
                        
                         $message['error']=1;
                         $message['message']='can not add creait to wallet';
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


         return  Response()->json($message);

      }
      
        Public function transfer_money(Request $request)
      {
          try{

               $token=$request->input('user_token');
          
          $check_token=User::select('id')->where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
              
              $value=$request->input('balnce');
                 $email=$request->input('emails');

          $created_at = carbon::now()->toDateTimeString();
          $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($created_at)));

              $check=User::where('email',$email)->orwhere('phone',$email)->orwhere('code',$email)->first();
            
              
            if($check !=null){
            
            if($request->has('balnce') && $request->has('emails')){      
                   $cash=Wallet::where('user_id',$check_token['id'])->value('credit');
                    
                    if($cash >= $value){
                        
                         $update=Wallet::where('user_id',$check_token['id'])->decrement('credit',$value);
                          $update=Wallet::where('user_id',$check['id'])->increment('credit',$value);
                          
                          
                            if($update ==true){
                                
                                
                                $insert=new Wallet;
                                $insert->user_id=$check_token['id'];
                                $insert->receiver_id=$check['id'];
                                $insert->value=$value;
                                $insert->type=2;
                                $insert->created_at=$dateTime;
                                $insert->save();
                                 
                                 
                                             try{       
                       //  $cc = 0;
                         
                         $title ='User :'.$check_token['first_name'].' transefer you money';
                         $body ='Transfering money by '.$check_token['first_name'].' for your account value '.$value;
                         
                         $title_ar = "حول لك مبلغ مالى ".$check_token['first_name'];
                         $body_ar  =  " تم تحويل مبلغ ".$value." الى محفظتك عن طريق ".  $check_token['first_name'];
                         
                       $get_user_token =User::select('firebase_token')->where('id', '=',$check['id'])->first();
                       
                      // return $get_user_token[0]->firebase_token;
                
                
                                     ////////////////////////////////////////////////////////
                                     
                                       $msg = array
                                              (
                                    		'body' 	=> $body,
                                    		'title'	=> $title,
                                            'click_action' => '5'    	
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
                            $save->sender_id=$check_token['id'];
                            $save->title=$title;
                            $save->message=$body; 
                            $save->title_ar = $title_ar; 
                            $save->message_ar = $body_ar;
                            $save->user_id=$check['id'];
                            $save->created_at=$dateTime;
                            $save->save();
                            
                            
                         }catch(Exception $ex){
                              $message['error']=5;
                              $message['message']='error in send notification';
                              
                         }
                                
                             $message['cash']=$value;
                             $message['error']=0;
                             $message['message']='increase credit in wallet and your wallet has decreased a value of  ';
                         }else{
                            
                             $message['error']=1;
                             $message['message']='can not add creait to wallet';
                         }
                  
                    }else{
                        
                         $message['error']=5;
                      $message['message']='your credit is not Enough';
                  
                        
                    }
                      
              
                }else{
                    $message['error'] = 6;
                    $message['message'] = "Please fill all the data";
                }
            }else{
                  
                  $message['error']=4;
                      $message['message']='this email is not exist in our system';
                  
                   
                  
                  
                 
              }
              
          
             
       
           
           
          
          }else{
               $message['error']=3;
               $message['message']='this token is not exist';
          }
          
       }catch(Exception $ex){

           $message['error']=2;
           $message['message']='error'.$ex->getMessage();
       }


         return  Response()->json($message);

      }





}
