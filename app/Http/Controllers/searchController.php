<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use carbon\carbon;
use App\Classes;
use App\Institution_branch;
use Illuminate\Support\Facades\DB;
use App\Leveles;
use App\Teacher_class;
use App\Rating;
use App\Reports;
use App\ reports_type;
use App\exam_result;

define( 'API_ACCESS_KEY12', 'AAAAz-GV50A:APA91bFXom0sxT5MNl_IQUbzEEsxJK55cgoI6k4ucPJctyN8wshU_ZOGZnADHyFfKOvcOxLsfuaAKrTJ3-2lGjmzdDAFH2IhiP0dtEtLMUxdyt5ZOPacOq5D9wHk0W0Wgxlncu3iMPl7');
   
class searchController extends Controller
{
    public $message=array();



    public function make_search(Request $request){

      try{
           $token=$request->input('user_token');
           $department=$request->input('department');
           $branch=$request->input('branch');

          
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){

 //فصول و معلمين ظظ طلاب  //  عربي  انجليزي 
    if($department=="فصول" && $branch=="عربي")
     {
        $branch=Institution_branch::select('institiution_branch.id','institiution_branch.name')
               
               ->where('institution_id',$check_token['id'])
               ->get();

            for($i=0;$i<count($branch);$i++) {
              $show=Classes::select('class.id','class.name')
               
               ->where([['class.branch_id',$branch[$i]['id']],['class.type','arabic'] ])
               ->get();
      
               $branch[$i]['classes']=$show;
      
            }
              
              
                 
          
                  if( count($branch)>0){
                        $message['data']= $branch;
                        $message['error']=0;
                        $message['message']='show data success';
                    }else{
                         $message['data']= $branch;
                         $message['error']=1;
                         $message['message']='error in show data ';
                    }


     }elseif($department=="فصول" && $branch=="انجليزي"){

    $branch=Institution_branch::select('institiution_branch.id','institiution_branch.name')
               
               ->where('institution_id',$check_token['id'])
               ->get();

            for($i=0;$i<count($branch);$i++) {
              $show=Classes::select('class.id','class.name')
               
               ->where([['class.branch_id',$branch[$i]['id']],['class.type','english'] ])
               ->get();
      
               $branch[$i]['classes']=$show;
      
            }
              
              
                 
          
                  if( count($branch)>0){
                        $message['data']= $branch;
                        $message['error']=0;
                        $message['message']='show data success';
                    }else{
                         $message['data']= $branch;
                         $message['error']=1;
                         $message['message']='error in show data ';
                    }
          		
     }elseif($department=="معلمين" && $branch=="انجليزي"){
      
       $teachers=User::select('users.id','users.first_name','users.last_name','users.image')->distinct()
       ->join('teacher_class','users.id','=','teacher_class.teacher_id')
       ->join('class','teacher_class.class_id','=','class.id')
      ->where([['users.state',4],['users.institution_id',$check_token['id']],['class.type','english']])
       ->get();


       if(count($teachers)>0){
          $message['data']=$teachers;
          $message['error']=0;
          $message['message']='show teachers successfullty';

       }else{
          $message['data']=$teachers;
          $message['error']=1;
          $message['message']='no data';

       }
          		
     }elseif($department=="معلمين" && $branch=="عربي"){

      $teachers=User::select('users.id','users.first_name','users.last_name','users.image')->distinct()
       ->join('teacher_class','users.id','=','teacher_class.teacher_id')
       ->join('class','teacher_class.class_id','=','class.id')
      ->where([['users.state',4],['users.institution_id',$check_token['id']],['class.type','arabic']])
       ->get();


       if(count($teachers)>0){
          $message['data']=$teachers;
          $message['error']=0;
          $message['message']='show teachers successfullty';

       }else{
          $message['data']=$teachers;
          $message['error']=1;
          $message['message']='no data';

       }
          		
     }elseif($department=="طلاب" && $branch=="عربي"){

       $select=User::select('users.id','users.first_name','users.last_name','users.image')->distinct()
        ->join('class','users.class_id','=','class.id')

       ->where([['users.state',2],['users.institution_id',$check_token['id']],['class.type','arabic']])->get();

          if(count($select)>0){

            $message['data']=$select;
            $message['error']=0;
            $message['message']='user data';

          }else{
            $message['data']=$select;
            $message['error']=1;
            $message['message']='no data for user ';
          }
       
          		
     }elseif($department=="طلاب" && $branch=="انجليزي"){

       $select=User::select('users.id','users.first_name','users.last_name','users.image')->distinct()
        ->join('class','users.class_id','=','class.id')

       ->where([['users.state',2],['users.institution_id',$check_token['id']],['class.type','english']])->get();

          if(count($select)>0){

            $message['data']=$select;
            $message['error']=0;
            $message['message']='show data successfully';

          }else{
            $message['data']=$select;
            $message['error']=1;
            $message['message']='no data for user ';
          }

          		
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

    ///reports

      public function show_specialreports(Request $request)
     {
      try{
          $token=$request->input('user_token');
          $id=$request->input('class_id');
          
          $check_token=User::where('user_token',$token)->first();
          
          
   if($request->has('user_token') && $check_token !=NULL){
              
        
              
      $show=Reports::select('reports.id','student.first_name as student_name','users.first_name','reports.report_name as report_name','reports.content','reports.created_at')
        ->join('users','reports.examiner_id','=','users.id')
        ->join('users as student','reports.user_id','=','student.id')
        ->where([['reports.type',1],['student.class_id',$id]])->get();
      
                 
          
                  if( count($show)>0){
                  $count=Reports::join('users as student','reports.user_id','=','student.id')
               ->where([['reports.type',1],['student.class_id',$id]])->count();

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
     
    public function show_generalreports(Request $request)
     {
      try{
          $token=$request->input('user_token');
           $id=$request->input('class_id');
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
              
        
              
         $show=Reports::select('reports.id','users.first_name','reports.report_name as report_name','reports.content','reports.created_at')
         ->join('users','reports.examiner_id','=','users.id')
         
         ->where([['reports.class_id',$id],['reports.type',2]])->get();
      
             
                 
          
                  if( count($show)>0){
                        $count=Reports::where([['reports.class_id',$id],['reports.type',2]])->count();
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
              
          
              
               $show=Reports::select('reports.id','users.first_name','reports.report_name as report_name','reports.content','reports.created_at')
               ->join('users','reports.examiner_id','=','users.id')
               ->where([['reports.class_id',$id],['reports.type',3]])->get();
      
              
                 
          
                  if( count($show)>0){
                        $count=Reports::where([['reports.class_id',$id],['reports.type',3]])->count();
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



    public function add_generalReport(Request $request){

      try{
          $token=$request->input('user_token');
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
          $id=$request->input('class_id');  
        $report_name=$request->input('report_name');
          $content=$request->input('content');
            $notes=$request->input('notes');
            $type	=$request->input('type');
         

          $updated_at = carbon::now()->toDateTimeString();
          $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($updated_at)));
          
   
          
              
            $insert=new Reports;
            $insert->examiner_id=$check_token['id'];
            $insert->examiner_name=$check_token['first_name'];
            $insert->report_name=$report_name;
            $insert->content=$content;
            $insert->notes=$notes;
            $insert->type	=$type	;
            $insert->class_id=$id;
            $insert->created_at=$dateTime;
            $insert->save();

                  if($insert == true){
                         $message['error']=0;
                        $message['message']='insert data success';
                    }else{
                         $message['error']=1;
                         $message['message']='error in insert data ';
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

    public function add_specialReport(Request $request){

      try{
          $token=$request->input('user_token');
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
          $id=$request->input('user_id');  
        $report_name=$request->input('report_name');
          $content=$request->input('content');
            $notes=$request->input('notes');
           // $type	=$request->input('type');
         

          $updated_at = carbon::now()->toDateTimeString();
          $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($updated_at)));
          
           $user_data=User::where('id',$id)->first();
          
              
            $insert=new Reports;
            $insert->examiner_id=$check_token['id'];
             $insert->examiner_name=$check_token['first_name'];
              $insert->user_id=$id;
            $insert->report_name=$report_name;
            $insert->content=$content;
            $insert->name=$user_data['first_name'];
            $insert->birth_date=$user_data['birth_date'];
            $insert->notes=$notes;
            $insert->type	=1;
            $insert->created_at=$dateTime;
            $insert->save();

                  if($insert == true){
                         $message['error']=0;
                        $message['message']='insert data success';
                    }else{
                         $message['error']=1;
                         $message['message']='error in insert data ';
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


   public function show_reportsType(Request $request){
      try{
          $token=$request->input('user_token');
           
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
              
        
              
         $show=reports_type::select('id','name')
         ->whereIn('id',[2,3])
         ->get();
      
             
                 
          
               if( count($show)>0){
          
                        $message['data']= $show;
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


  public function make_rating(Request $request)
    {
      try{

         $token=$request->input('user_token');
          $user=$request->input('user_id');
          
          
          $check_token=User::where('user_token',$token)->first();
          
          
        if($request->has('user_token') && $check_token !=NULL){
               
        $rate=$request->input('rate');
        $comment=$request->input('comment');
   
            if( !$request->has('rate') || !$request->has('comment')){
                  $message['error'] = 1;
                  $message['message'] = "please fill all the data";
                  return response()->json($message);
            }

      $created_at = carbon::now()->toDateTimeString();
      $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($created_at)));

      $user_data=User::where('id',$user)->first();


      if($user_data['state']==2){ //student


        $check=Rating::where([['user_id',$user],['trainer_id',$check_token['id']],['who_rate','teacher']])->get();



             if(count($check)>0){
                       
                        
              $insert=Rating::where([['user_id',$user],['trainer_id',$check_token['id']],['who_rate','teacher']])->update([
                          
                            
                     'rate'=>$rate,
                     'comment'=>$comment,
                     'updated_at'=>$dateTime
                          
                          ]);
      
                  }else{
                     
        $insert=new Rating;
        $insert->user_id=$user;
        $insert->trainer_id=$check_token['id'];
        $insert->rate=$rate;
        $insert->comment=$comment;
        $insert->who_rate='teacher';
        $insert->created_at=$dateTime;
        $insert->save();

      
                  }
                  
                    if($insert ==true){
                       $message['error']=0;
                       $message['message']='make rate successfuly';
                    }else{
                       $message['error']=1;
                       $message['message']='can not make rate';
                   } 
 
                        $get_user_token =User::select('firebase_token')->where('id', '=',$check_token['id'])->first();
                       
                       $title = "Rafiiq Ratting";
                       $body = "Someone is rating your profile";
                              $msg = array
                                      (
                            		'body' 	=> $body,
                            		'title'	=> $title,
                                    'click_action' => '3'     	
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
                            $save->sender_id=$user;
                            $save->title=$title;
                            $save->message=$body;
                            $save->title_ar = "أشعارات رفيق";
                            $save->message_ar = "يوجد شخص ما قيم صفحتك";
                            $save->user_id=$check_token['id'];
                            $save->created_at=$dateTime;
                            $save->save();

      }else{//teacher


         $state=$check_token['state'];
         
         
         
         if($state==2){
        
                $check=Rating::where([['user_id',$check_token['id']],['trainer_id',$user],['who_rate','student']])->get();


    
                 if(count($check)>0){
                           
                            
                          $insert=Rating::where([['user_id',$check_token['id']],['trainer_id',$user],['who_rate','student']])->update([
                                                                                                                                        'rate'=>$rate,
                                                                                                                                        'comment'=>$comment,
                                                                                                                                        'updated_at'=>$dateTime
                                                                                                                                              
                                                                                                                                              ]);
                                                              
                 }else{
                                 
                        $insert=new Rating;
                        $insert->user_id=$check_token['id'];
                        $insert->trainer_id=$user;
                        $insert->rate=$rate;
                        $insert->comment=$comment;
                        $insert->who_rate='student';
                        $insert->created_at=$dateTime;
                        $insert->save();

      
                  }
                  
                   if($insert ==true){
                         $get_user_token =User::select('firebase_token')->where('id', '=',$user)->first();
                       
                       $title = "Rafiiq Ratting";
                       $body = "Someone is rating your profile";
                              $msg = array
                                      (
                            		'body' 	=> $body,
                            		'title'	=> $title,
                                    'click_action' => '3',	
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
                            $save->title_ar = "أشعارات رفيق";
                            $save->message_ar = "يوجد شخص ما قيم صفحتك";
                            $save->user_id=$user;
                            $save->created_at=$dateTime;
                            $save->save();
                       $message['error']=0;
                       $message['message']='make rate successfuly';

                }else{
                    $message['error']=1;
                    $message['message']='can not make rate';
                } 
         }else{
             
                $check=Rating::where([['user_id',1],['trainer_id',$user],['who_rate','management']])->get();



             if(count($check)>0){
                       
                        
              $insert=Rating::where([['user_id',1],['trainer_id',$user],['who_rate','management']])->update([
                                                                                                     'rate'=>$rate,
                                                                                                     'comment'=>$comment,
                                                                                                     'updated_at'=>$dateTime
                                                                                                          
                                                                                                          ]);
      
                  }else{
                     
        $insert=new Rating;
        $insert->user_id=1;
        $insert->trainer_id=$user;
        $insert->rate=$rate;
        $insert->comment=$comment;
        $insert->who_rate='management';
        $insert->created_at=$dateTime;
        $insert->save();

      
        }
                  
                if($insert ==true){
                    
                     $get_user_token =User::select('firebase_token')->where('id', '=',$user)->first();
                       
                       $title = "Rafiiq Notification";
                       $body = "Rafiiq Management is rating your profile";
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
                            $save->sender_id=1;
                            $save->title=$title;
                            $save->message=$body;
                            $save->title_ar = "أشعارات رفيق";
                            $save->message_ar = "تم تقيمك من خلال إدارة رفيق";
                            $save->user_id=$user;
                            $save->created_at=$dateTime;
                            $save->save();
                    $message['error']=0;
                    $message['message']='make rate successfuly';
                }else{
                    $message['error']=1;
                    $message['message']='can not make rate';
                } 
    
             
         }
      }
      
      
   
    
      }else{
           $message['error']=3;
           $message['message']='this token is not exist';

      }


      }catch(Exception  $ex){
         $message['error']=2;
             $message['message']='error'.$ex->getMessage();

      }
      return response()->json($message);
    }
     
   


public function rate_myself(Request $request)
    {
      try{

         $token=$request->input('user_token');
          
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
               
        $rate=$request->input('rate');
        
         if( !$request->has('rate') ){
          $message['error'] = 1;
          $message['message'] = "please fill all the data";
          return response()->json($message);
      }
      

      $created_at = carbon::now()->toDateTimeString();
      $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($created_at)));
      
      
             
                       
                        
     $insert=User::where('id',$check_token['id'])->update([
                          
                            
                    'rate_myself'=>$rate,
                   
      
                    'updated_at'=>$dateTime
                          
                          ]);
      
                
                  
              if($insert ==true){

          
               
                 $message['error']=0;
                 $message['message']='make rate successfuly';

                }else{
                        
                         $message['error']=1;
                         $message['message']='can not make rate';
        
                } 

    
      }else{
           $message['error']=3;
           $message['message']='this token is not exist';

      }


      }catch(Exception  $ex){
         $message['error']=2;
             $message['message']='error'.$ex->getMessage();

      }
      return response()->json($message);
    }
     
 

  public function rate_management(Request $request)
    {
      try{

         $token=$request->input('user_token');
         
          
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
               
        $rate=$request->input('rate');
        $comment=$request->input('comment');
   
        if( !$request->has('rate') || !$request->has('comment')){
          $message['error'] = 1;
          $message['message'] = "please fill all the data";
          return response()->json($message);
        }

      $created_at = carbon::now()->toDateTimeString();
      $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($created_at)));



     


       $check=Rating::where([['user_id',$check_token['id']],['management','!=',NULL],['who_rate','user']])->get();



             if(count($check)>0){
                       
                        
              $insert=Rating::where([['user_id',$check_token['id']],['management','!=',NULL],['who_rate','user']])->update([
                                                                                                                            'rate'=>$rate,
                                                                                                                            'comment'=>$comment,
                                                                                                                            'updated_at'=>$dateTime
                                                                                                                                  
                                                                                                                                  ]);
      
                  }else{
                     
        $insert=new Rating;
        $insert->user_id=$check_token['id'];
        $insert->trainer_id=NULL;
        $insert->management='management';
        $insert->rate=$rate;
        $insert->comment=$comment;
        $insert->who_rate='user';
        $insert->created_at=$dateTime;
        $insert->save();

      
                  }
                  
                   if($insert ==true){

          
               
                 $message['error']=0;
                 $message['message']='make rate successfuly';

                }else{
                        
                  $message['error']=1;
                  $message['message']='can not make rate';
        
                } 

      }else{
           $message['error']=3;
           $message['message']='this token is not exist';

      }


      }catch(Exception  $ex){
         $message['error']=2;
             $message['message']='error'.$ex->getMessage();

      }
      return response()->json($message);
    }
     
   public function rate_school(Request $request)
    {
      try{

         $token=$request->input('user_token');
         
          
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
               
        $rate=$request->input('rate');
        $comment=$request->input('comment');
   
        if( !$request->has('rate') || !$request->has('comment')){
          $message['error'] = 1;
          $message['message'] = "please fill all the data";
          return response()->json($message);
        }

      $created_at = carbon::now()->toDateTimeString();
      $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($created_at)));

     


     


       $check=Rating::where([['user_id',$check_token['id']],['school_id','!=',NULL],['who_rate','student']])->get();



             if(count($check)>0){
                       
                        
              $insert=Rating::where([['user_id',$check_token['id']],['school_id','!=',NULL],['who_rate','student']])->update([
                          
                            
                    'rate'=>$rate,
                     'comment'=>$comment,

      
                    'updated_at'=>$dateTime
                          
                          ]);
      
                  }else{
                     
       $insert=new Rating;
       $insert->user_id=$check_token['id'];
       $insert->trainer_id=NULL;
       $insert->management=NULL;
       $insert->school_id=$check_token['institution_id'];
       $insert->rate=$rate;
        $insert->comment=$comment;
        $insert->who_rate='student';
        
      
         $insert->created_at=$dateTime;
          $insert->save();

      
                  }
                  
                   if($insert ==true){

          
               
                 $message['error']=0;
                 $message['message']='make rate successfuly';

                }else{
                        
                  $message['error']=1;
                  $message['message']='can not make rate';
        
                } 

      }else{
           $message['error']=3;
           $message['message']='this token is not exist';

      }


      }catch(Exception  $ex){
         $message['error']=2;
             $message['message']='error'.$ex->getMessage();

      }
      return response()->json($message);
    }  
    

 public function show_onestudent(Request $request)
     {
      try{
          $token=$request->input('user_token');
          $id=$request->input('user_id');
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
              
               

               $students=User::select('users.id','users.first_name','users.last_name','users.image','rate_myself','created_at')
                 ->where('id',$id)
                 ->get();
      
                 
          
                  if( count($students)>0){
                        $message['data']= $students;
                        $message['error']=0;
                        $message['message']='show data success';
                    }else{
                         $message['data']= $students;
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


  public function show_studentrating(Request $request)
  {
      try{
         
         $id=$request->input('student_id');
 
            $token=$request->input('user_token');
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){ 

        $check=Rating::select('users.first_name','users.last_name','users.image','rating.rate','rating.comment','rating.created_at')
             ->join('users','rating.trainer_id','=','users.id')

             ->where([['rating.user_id',$id],['who_rate','teacher']])->get();
     
      
	      
	        if( count($check)>0){
	          
	                $message['data']=$check;
	                 $message['error']=0;
	                 $message['message']='show rate for this student';

	        }else{
	                 $message['data']=$check;
	                 $message['error']=1;
	                 $message['message']='no  rate';
	        }
	    }else{
              
               $message['error']=3;
              $message['message']='this token is\'t exist ';
         }
	      
      }catch(Exception  $ex){
         $message['error']=2;
          $message['message']='error'.$ex->getMessage();

      }
      return response()->json($message);
   }
   
   

   public function insert_degree(Request $request){

      try{
		   
          $token=$request->input('user_token');
          $degree=$request->input('degree');
          $class=$request->input('class_id');
          $month=$request->input('month');
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
         
         
$arr=array();
          $updated_at = carbon::now()->toDateTimeString();
          $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($updated_at)));
          
          $degree_arr=explode(',',$degree);
         
          for($i=0;$i<count($degree_arr);$i++){
              $students=User::where('class_id',$class)->orderBy('id')->pluck('id')->toArray();
       
          $arr[]=array('teacher_id'=>$check_token['id'],"user_id"=>$students[$i],"subject"=>$check_token['subject'],"class_id"=>$class,"degree"=>$degree_arr[$i],"month"=>$month,"created_at"=>$dateTime);
           
                 
          }
          
              
            $insert=exam_result::insert($arr);
          /*  $insert->teacher_id=$check_token['id'];
            $insert->user_id=;
            $insert->subject=$check_token['subject'];
            $insert->degree=$notes;
            $insert->class_id=$class;
            $insert->month=$month;
            $insert->created_at=$dateTime;
            $insert->save();*/

                  if($insert == true){
                         $message['error']=0;
                        $message['message']='insert data success';
                    }else{
                         $message['error']=1;
                         $message['message']='error in insert data ';
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
    
    
  public function show_studentresult(Request $request)
  {
      try{
         
         $id=$request->input('student_id');
 
            $token=$request->input('user_token');
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){ 

        $check=Rating::select('users.first_name','users.last_name','users.image','rating.rate','rating.comment','rating.created_at')
             ->join('users','rating.trainer_id','=','users.id')

             ->where([['rating.user_id',$id],['who_rate','teacher']])->get();
     
      
	      
	        if( count($check)>0){
	          
	                $message['data']=$check;
	                 $message['error']=0;
	                 $message['message']='show rate for this student';

	        }else{
	                 $message['data']=$check;
	                 $message['error']=1;
	                 $message['message']='no  rate';
	        }
	    }else{
              
               $message['error']=3;
              $message['message']='this token is\'t exist ';
         }
	      
      }catch(Exception  $ex){
         $message['error']=2;
          $message['message']='error'.$ex->getMessage();

      }
      return response()->json($message);
   }
   
   
     public function countsubject_Monthchart(Request $request){
      try{
             $id=$request->input('student_id');
           $month=$request->input('month');
            $token=$request->input('user_token');
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){ 
              
                  $all_price = array();
                  $price = array();
                  $date = array();
                  
                  $get_chart = DB::select("SELECT Distinct  subject FROM exam_result WHERE month='".$month."' AND  user_id=".$id);
        
                  foreach($get_chart as $money){
                        array_push($date, $money->subject);
                   }
                  
                            
                    $message['subject'] = $date;
            
          }else{
              
               $message['error']=3;
              $message['message']='this token is\'t exist ';
          }
          
      }catch(Exception $ex){
              $message['error']=3;
             $message['message']='error'.$ex->getMessage();

    }

    return response()->json($message);
  }
  

    
     public function countdegree_Monthchart(Request $request){
      try{
             $id=$request->input('student_id');
           $month=$request->input('month');
            $token=$request->input('user_token');
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){ 
              
                  $all_price = array();
                  $price = array();
                  $date = array();
                  
                  $get_chart = DB::select("SELECT  degree FROM exam_result WHERE month='".$month."' AND user_id=".$id);
        
                  foreach($get_chart as $money){
                        array_push($date, $money->degree);
                   }
                  
                            
                    $message['degree'] = $date;
            
          }else{
              
               $message['error']=3;
              $message['message']='this token is\'t exist ';
          }
          
      }catch(Exception $ex){
              $message['error']=3;
             $message['message']='error'.$ex->getMessage();

    }

    return response()->json($message);
  }
  
  
    public function  Takder(Request $request){
      try{
           
            $token=$request->input('user_token');
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){ 
              
                  $all_price = array();
                  $price = array();
                  $date = array();
                  
                  
            $date=array('ضعيف','مقبول','جيد','جيد جدا','ممتاز');
                   
                  
                            
                    $message['data'] = $date;
            
          }else{
              
               $message['error']=3;
              $message['message']='this token is\'t exist ';
          }
          
      }catch(Exception $ex){
              $message['error']=3;
             $message['message']='error'.$ex->getMessage();

    }

    return response()->json($message);
  }
  
    public function classdegree_Monthchart(Request $request){
      try{
             $id=$request->input('class_id');
           $month=$request->input('month');
            $token=$request->input('user_token');
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){ 
              
                  $all = array();
                  $price = array();
                  $date = array();
                  
                  
                  
                  $subject=exam_result::distinct()->where('class_id',$id)->pluck('subject')->toArray();
                  
                  
                  foreach($subject as $k){
                      
                  
                  
                  $d=exam_result::where([['month',$month],['class_id',$id],['degree','<',20],['subject',$k]])->count();
                  
                  $m=exam_result::where([['month',$month],['class_id',$id],['subject',$k],['degree','>',20],['degree','<=',40]])
                   //  ->whereBetween('degree',[20,40])
                  
                     ->count();
                     
                        $g=exam_result::where([['month',$month],['class_id',$id],['subject',$k],['degree','>',40],['degree','<=',60]])
                    // ->whereBetween('degree',[40,60])
                  
                     ->count();
                     
               $gg=exam_result::where([['month',$month],['class_id',$id],['subject',$k],['degree','>',60],['degree','<=',80]])
                //->whereBetween('degree',[60,80])
                  
                ->count();
                
                   $mm=exam_result::where([['month',$month],['class_id',$id],['subject',$k],['degree','>',80],['degree','<=',100]])
                     //->whereBetween('degree',[80,100])
                  
                     ->count();
                     
                     $all[$k][]=array($d,$m,$g,$gg,$mm);
                
        
                  }
                  
                            
                 return $all;
            
          }else{
              
               $message['error']=3;
              $message['message']='this token is\'t exist ';
          }
          
      }catch(Exception $ex){
              $message['error']=3;
             $message['message']='error'.$ex->getMessage();

    }

    return response()->json($message);
  }

}
