<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Child;
use App\User;
use App\Reports;
use carbon\carbon;

define( 'API_ACCESS_KEY12', 'AAAAz-GV50A:APA91bFXom0sxT5MNl_IQUbzEEsxJK55cgoI6k4ucPJctyN8wshU_ZOGZnADHyFfKOvcOxLsfuaAKrTJ3-2lGjmzdDAFH2IhiP0dtEtLMUxdyt5ZOPacOq5D9wHk0W0Wgxlncu3iMPl7');

class childController extends Controller
{
    
    	 public $message=array();


    public function add_child(Request $request)
     {
      try{
          $token=$request->input('user_token');

          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
            
            
          $name=$request->input('first_name');
          $username=$request->input('user_name');
          $image=$request->file('image');
          $email = $request->input('email');
          $pass=$request->input('passwords');
         
         
          $check_username = \App\User::where('user_name' , $username)->first();
          $check_email = \App\User::where('email' , $email)->first();

          if($check_username != NULL){
              $message['error'] = 5;
              $message['message'] = "Username is already exist";
              return response()->json($message);
          }
          if($check_email != NULL){
              $message['error'] = 6;
              $message['message'] = "Email is already exist";
              return response()->json($message);
          }

          $updated_at = carbon::now()->toDateTimeString();
          $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($updated_at)));
          
                if(isset($image)) {
                    $new_name = $image->getClientOriginalName();
                    $savedFileName = rand(100000,999999).time()."_".$new_name; // give a unique name to file to be saved
                    $destinationPath_id = 'uploads/users';
                    $image->move($destinationPath_id, $savedFileName);
        
                    $images = $savedFileName;
		                  
		          }else{
		              $images =NULL;       
		          }
		          
		          
		          
		          
              try{
                  $code=  rand(1000,10000);
                 //rand(1000000,9999999).time();// give a unique name to file to be saved
                      
               }catch(Exception $ex){
                    $message['error']=6;
                   $message['message']='error in generate key';    
              }

          $check=User::where([['first_name',$name],['parent_id',$check_token['id']]])->get();
          
          if(count($check)>0){
              
            $message['error']=4;
            $message['message']='this child exist ';
          }else{
              
                  $insert=new User;
                  $insert->parent_id=$check_token['id'];
                  $insert->first_name=$name;
                  $insert->last_name=$check_token['first_name'].' '.$check_token['last_name'];
                  $insert->user_name=$username;
                  $insert->image=$images;
                  $insert->password=$pass;
                  $insert->code=$code;
                  $insert->state=2;
                  $insert->created_at=$dateTime;
                  $insert->save();

                  if($insert == true){
                         $message['error']=0;
                        $message['message']='insert data success';
                    }else{
                         $message['error']=1;
                         $message['message']='error in insert data ';
                    }
              
          }

                 
          }else{
              
               $message['error']=3;
              $message['message']='this token is\'t exist ';
          }

            }catch(Exception $ex){
                 $message['error']=2;
                 $message['message']='error'.$ex->getMessage();
            }  
            
         return response()->json($message);
     }
     
     
    
    public function add_child1(Request $request){
        
        $token=$request->input('user_token');
          
        $child_id  = $request->input('child_id');
          
        $check_token=User::where('user_token',$token)->first();
          
        $updated_at = carbon::now()->toDateTimeString();
        $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($updated_at)));
            
        if($request->has('user_token') && $check_token !=NULL){
              
            if($request->has('child_id')){  
            
                $check = \App\User::where([['id' , $child_id] , ['parent_id' , NULL]])->first();
                
                $check_request = \App\parentChild_state::where([['parent_id' , $check_token->id] , ['child_id' , $child_id]])->first();
                
                if($check != NULL){
    
                    if($check_request == NULL){
                        $insert_new = new \App\parentChild_state;
                        $insert_new->parent_id =  $check_token['id'];
                        $insert_new->child_id  =  $child_id;
                        $insert_new->state  =  "wait";
                        $insert_new->created_at = $dateTime;
                        $insert_new->save();
        
                        if($insert_new == true){
                            $get_user_token = \App\User::select('id','firebase_token')->where([['id', $child_id] ])->first();

                            $title = "Rafiiq notification";
                            $body = $check_token->first_name." ".$check_token->last_name." send you a request to be your parent ";
                            $body_ar = " أرسل لك طلبًا ليكون والدك "  .$check_token->first_name." ".$check_token->last_name;
                            
                            if($get_user_token != NULL){
                                 $msg = array(
                                		'body' 	=> $body,
                                		'title'	=> $title,
                                        'click_action' => '7',     	
                                          );
                                	$fields = array
                                			(
                                				'to'		=> $get_user_token->firebase_token,
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
                                $save->sender_id = $check_token->id;
                                $save->title   = $title;
                                $save->message = $body;
                                $save->title_ar ="أشعارات رفيق";
                                $save->message_ar = $body_ar;
                                $save->user_id = $child_id;
                                $save->click_action = 7;
                                $save->created_at = $dateTime;
                                $save->save();
                            }
                            $message['error'] = 0;
                            $message['message'] = "a new child is add to you";
                        }else{
                            $message['error'] = 1;
                            $message['message'] = "error, please try again";
                        }
                    }else{
                        $message['error'] = 4;
                        $message['message'] = "you have requested this child before";
                    }
                     
                }else{
                    $message['error'] = 3;
                    $message['message'] = "this child have a parent try again";
                }
            }else{
                $message['error'] = 3;
                $message['message'] = "please enter child id";
            }
            
        }else{
            $message['error'] = 1;
            $message['message'] = " token, is not provided";
        }
            return response()->json($message);

    } 
     
     
     
    public function show_medicalreports(Request $request)
     {
      try{
          $token=$request->input('user_token');
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
              
        
              
               $show=Reports::select('reports.id','users.first_name','reports.report_name as report_name','reports.created_at')
               ->join('users','reports.examiner_id','=','users.id')
               ->where([['reports.user_id',$check_token['id']],['reports.type',2]])->get();
      
                 
          
                  if( count($show)>0){
                  $count=Reports::where([['reports.user_id',$check_token['id']],['reports.type',2]])->count();

                        $message['data']= $show;
                        $message['count']=$count;
                         $message['error']=0;
                        $message['message']='show data success';
                    }else{
                        $message['data']= $show;
                         $message['count']=0;
                         $message['error']=1;
                         $message['message']='error in show data ';
                    }
              
          

                 
          }else{
              
               $message['error']=3;
              $message['message']='this token is\'t exist ';
          }

            }catch(Exception $ex){
                 $message['error']=2;
                 $message['message']='error'.$ex->getMessage();
            }  
         

       
         return response()->json($message);
     }
     
    public function show_acadmyreports(Request $request)
     {
      try{
          $token=$request->input('user_token');
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
              
        
              
               $show=Reports::select('reports.id','users.first_name','reports.report_name as report_name','reports.created_at')
               ->join('users','reports.examiner_id','=','users.id')->where([['reports.user_id',$check_token['id']],['reports.type',3]])->get();
      
             
                 
          
                  if( count($show)>0){
                        $count=Reports::where([['reports.user_id',$check_token['id']],['reports.type',3]])->count();
                        $message['data']= $show;
                        $message['count']= $count;
                         $message['error']=0;
                        $message['message']='show data success';
                    }else{
                        $message['data']= $show;
                        $message['count']= 0;
                         $message['error']=1;
                         $message['message']='error in show data ';
                    }
              
          

                 
          }else{
              
               $message['error']=3;
              $message['message']='this token is\'t exist ';
          }

            }catch(Exception $ex){
                 $message['error']=2;
                 $message['message']='error'.$ex->getMessage();
            }  
         

       
         return response()->json($message);
     }
    public function show_slokyreports(Request $request)
     {
      try{
          $token=$request->input('user_token');
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
              
          
              
               $show=Reports::select('reports.id','users.first_name','reports.report_name as report_name','reports.created_at')
               ->join('users','reports.examiner_id','=','users.id')->where([['reports.user_id',$check_token['id']],['reports.type',1]])->get();
      
              
                 
          
                  if( count($show)>0){
                        $count=Reports::where([['reports.user_id',$check_token['id']],['reports.type',1]])->count();
                        $message['data']= $show;
                        $message['count']=$count;
                       
                         $message['error']=0;
                        $message['message']='show data success';
                    }else{
                        $message['data']= $show;
                        $message['count']=0;
                         $message['error']=1;
                         $message['message']='error in show data ';
                    }
              
          

                 
          }else{
              
               $message['error']=3;
              $message['message']='this token is\'t exist ';
          }

            }catch(Exception $ex){
                 $message['error']=2;
                 $message['message']='error'.$ex->getMessage();
            }  
         

       
         return response()->json($message);
     }
     
     
     
     
    public function show_child(Request $request)
     {
      try{
          $token=$request->input('user_token');
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
              
        
              
               $count=User::where('parent_id',$check_token['id'])->count();
      
               $show=User::select('id','first_name as name','last_name','user_name','image','code','password as passwords')->where('parent_id',$check_token['id'])->get();
                 
          
                  if( count($show)>0){
                        $message['data']= $show;
                        $message['count']=$count;
                        $message['error']=0;
                        $message['message']='show data success';
                    }else{
                        $message['data']= $show;
                         $message['error']=1;
                         $message['message']='error in show data ';
                    }
              
          

                 
          }else{
              
               $message['error']=3;
              $message['message']='this token is\'t exist ';
          }

            }catch(Exception $ex){
                 $message['error']=2;
                 $message['message']='error'.$ex->getMessage();
            }  
         

       
         return response()->json($message);
     }
    
    
    
    public function show_allchildern(Request $request)
     {
      try{
          $token=$request->input('user_token');
          
          $check_token=User::where('user_token',$token)->first();
          
          $data = array();
          if($request->has('user_token') && $check_token !=NULL){
              
                $show=User::select('id','first_name as name','last_name','user_name','image','code','password as passwords')
                        ->where([['state' , 2],['parent_id', NULL]])->get();
                
                foreach($show as $child ){
                    
                    $check_request =  \App\parentChild_state::where('child_id' , $child->id)->first();
                    
                    if($check_request == NULL){
                        array_push($data, (object)array(
                            
                            "id" => $child->id,
                            "name" => $child->name,
                            "last_name" => $child->last_name,
                            "user_name" => $child->user_name,
                            "image" => $child->image,
                            "code" => $child->code,
                            "passwords" => $child->passwords,
                            
                            ));
                            
                    }
                } 
          
                  if( count($data)>0){
                        $message['data']= $data;
                        $message['error']=0;
                        $message['message']='show data success';
                    }else{
                        $message['data']= $data;
                        $message['error']=1;
                        $message['message']='error in show data ';
                    }
              
          

                 
          }else{
              
               $message['error']=3;
              $message['message']='this token is\'t exist ';
          }

            }catch(Exception $ex){
                 $message['error']=2;
                 $message['message']='error'.$ex->getMessage();
            }  
         

       
         return response()->json($message);
     }
     
     
    public function  accept_parent_request(Request $request){
        
        $token=$request->input('user_token');
        $action = $request->input('action');
        $notify_id = $request->input('notify_id');
        
        $updated_at = carbon::now()->toDateTimeString();
        $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($updated_at)));
        
        $check_token=User::where('user_token',$token)->first();
          
          
        if($request->has('user_token') && $check_token !=NULL){
              $get_data = \App\parentChild_state::where('child_id' , $check_token->id)->first();

            if($action == "yes"){

                $update_action = \App\parentChild_state::where([['child_id' , $check_token->id] , ['state' , 'wait' ]])->update(['state'=> 'accept']);
                
                $update_student = \App\User::where('id' , $check_token->id)->update(['parent_id' => $get_data->parent_id]);
                
                $get_body = \App\Notification::where('id' , $notify_id)->first();
                
                $username = \App\User::where('id' , $get_body->sender_id)->first();
                                    
                $body = "you accept ".$username->first_name." ".$check_token->last_name." to be your parent ";

                $update_notification  = \App\Notification::where('id' , $notify_id)->update(['click_action' => '9' , "message" => $body]);

                
                if($update_student == true){
                    

                    $title = "Rafiiq notification";
                    $body = $check_token->first_name." ".$check_token->last_name." accept your request to be your child";
                    $body_ar =  "اقبل طلبك ليكون طفلك ".$check_token->first_name." ".$check_token->last_name;
                            
                    $message['error'] = 0;
                    $message['message'] = "Request Accepted";
                }else{
                    $message['error'] = 1;
                    $message['message'] = "error, please try again";
                }
            }else{
                $update_notification  = \App\Notification::where('id' , $notify_id)->delete();

                $title = "Rafiiq notification";
                $body = $check_token->first_name." ".$check_token->last_name." refuse your request to be your child";
                $body_ar =  "رفض طلبك بأن تكون طفلك ".  $check_token->first_name." ".$check_token->last_name;
                
                $message['error'] = 4;
                $message['message'] = "Request Removed";
                            
            }
 
               $get_user_token = \App\User::select('id','firebase_token')->where([['id', $get_data->parent_id] ])->first();

                if($get_user_token != NULL){
                     $msg = array(
                    		'body' 	=> $body,
                    		'title'	=> $title,
                            'click_action' => '8',     	
                              );
                    	$fields = array
                    			(
                    				'to'		=> $get_user_token->firebase_token,
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
                    $save->sender_id = $check_token->id;
                    $save->title   = $title;
                    $save->message = $body;
                    $save->title_ar ="أشعارات رفيق";
                    $save->message_ar = $body_ar;
                    $save->user_id = $get_data->parent_id;
                    $save->created_at = $dateTime;
                    $save->save();
                }
        }else{
              
               $message['error']=3;
              $message['message']='this token is\'t exist ';
          }
        return response()->json($message);

    }
    
    
      public function show_Trainerreports(Request $request)
     {
      try{
          $token=$request->input('user_token');
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
              
        
              
               $show=Reports::select('reports.id','reports.report_name as report_name','subcategory.name','reports.created_at')
               ->leftjoin('subcategory','reports.test_category','=','subcategory.id')
               ->where('reports.user_id',$check_token['id'])->get();
      
                 
          
                  if( count($show)>0){
                  $count=Reports::where('reports.user_id',$check_token['id'])->count();

                        $message['data']= $show;
                        $message['count']=$count;
                         $message['error']=0;
                        $message['message']='show data success';
                    }else{
                        $message['data']= $show;
                         $message['count']=0;
                         $message['error']=1;
                         $message['message']='error in show data ';
                    }
              
          

                 
          }else{
              
               $message['error']=3;
              $message['message']='this token is\'t exist ';
          }

            }catch(Exception $ex){
                 $message['error']=2;
                 $message['message']='error'.$ex->getMessage();
            }  
         

       
         return response()->json($message);
     }
     
     
     
     
     
      public function show_Trainerreportbyid(Request $request)
     {
      try{
          $token=$request->input('user_token');
                    $id=$request->input('id');

          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
              
            $check=Reports::where('id',$id)->value('test_category');
            
            
            
              
               $show=Reports::select('reports.id','reports.report_name as report_name','subcategory.name')
                               ->join('subcategory','reports.test_category','=','subcategory.id')
                               ->where('reports.user_id',$check_token['id'])->get();
      
                 
          
                  if( count($show)>0){
                  $count=Reports::where('reports.user_id',$check_token['id'])->count();

                        $message['data']= $show;
                        $message['count']=$count;
                         $message['error']=0;
                        $message['message']='show data success';
                    }else{
                        $message['data']= $show;
                         $message['count']=0;
                         $message['error']=1;
                         $message['message']='error in show data ';
                    }
              
          

                 
          }else{
              
               $message['error']=3;
              $message['message']='this token is\'t exist ';
          }

            }catch(Exception $ex){
                 $message['error']=2;
                 $message['message']='error'.$ex->getMessage();
            }  
         

       
         return response()->json($message);
     }
}
