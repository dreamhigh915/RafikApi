<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;
use App\User;
use carbon\carbon;
use App\admin_roles;
use App\E3lan;
use App\user_tests;
use App\shared_reports;
use App\Wallet;
use App\SubCategory;

use App\Roles;
define( 'API_ACCESS_KEY12', 'AAAAz-GV50A:APA91bFXom0sxT5MNl_IQUbzEEsxJK55cgoI6k4ucPJctyN8wshU_ZOGZnADHyFfKOvcOxLsfuaAKrTJ3-2lGjmzdDAFH2IhiP0dtEtLMUxdyt5ZOPacOq5D9wHk0W0Wgxlncu3iMPl7');

class AdminController extends Controller
{
     public $message=array();
     
     
     
     
   public function my_tests(Request $request)
     {
      try{
            $token=$request->input('user_token');
            
          
            $check_token=User::where('user_token',$token)->first();
          

          if($request->has('user_token') && $check_token != NULL){
              
            //  $mytests = Array();
            
                $mytests = \App\user_tests::select('subcategory.id','subcategory.name','subcategory.image' )
                                        ->JOIN('subcategory' , 'user_tests.test_id' ,'=' ,'subcategory.id')
                                        ->where('user_tests.user_id', $check_token->id)->get();
                
                //DB::RAW('(select reports.id from reports where reports.test_category = subcategory.id and reports.test_type = 1 and reports.user_id= '.$check_token->id.') as report_id')
            //   $mytests=DB::select('select subcategory.id,subcategory.name,subcategory.image,(select reports.id from reports where reports.test_category=subcategory.id 
            //              AND reports.test_type=1 AND reports.user_id='.$check_token['id'].') as report_id  from user_tests join subcategory ON user_tests.test_id= subcategory.id where user_tests.user_id='.$check_token['id']);
            
                
                
                if( count($mytests)>0 ){
                    $message['data']=$mytests;
                    $message['error']=0;
                    $message['message']='show data success';
                }else{
                    $message['data']=$mytests;
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
    
    public function my_shared_reports(Request $request)
     {
      try{
              $token=$request->input('user_token');
             
             

            
          
          $check_token=User::where('user_token',$token)->first();
          
          
       
          if($request->has('user_token') && $check_token !=NULL){
              
              
            $mytests=shared_reports::select('reports.id as report_id','reports.report_name','users.first_name','reports.app_date','shared_reports.created_at')
            ->join('reports','shared_reports.report_id','=','reports.id')
            ->join('users','reports.user_id','=','users.id')
            ->where([['shared_reports.type','report'],['shared_reports.trainer_id',$check_token['id']]])
            ->get();
                
                
                                  if( count($mytests)>0 ){
                                      $message['data']=$mytests;
                                         $message['error']=0;
                                        $message['message']='show data success';
                                    }else{
                                          $message['data']=$mytests;
                                    
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
     
     
      public function shared_class_result(Request $request)
     {
      try{
              $token=$request->input('user_token');
             
             

            
          
          $check_token=User::where('user_token',$token)->first();
          
          
       
          if($request->has('user_token') && $check_token !=NULL){
              
              
            $mytests=shared_reports::select('shared_reports.result_id  as class_id','class.name as class_name','shared_reports.month','shared_reports.created_at')
            ->join('class','shared_reports.result_id','=','class.id')
            
            ->where([['shared_reports.type','class'],['shared_reports.trainer_id',$check_token['id']]])
            ->get();
                
                
                                  if( count($mytests)>0 ){
                                      $message['data']=$mytests;
                                         $message['error']=0;
                                        $message['message']='show data success';
                                    }else{
                                          $message['data']=$mytests;
                                    
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
     
       public function shared_student_result(Request $request)
     {
      try{
              $token=$request->input('user_token');
             
             

            
          
          $check_token=User::where('user_token',$token)->first();
          
          
       
          if($request->has('user_token') && $check_token !=NULL){
              
              
            $mytests=shared_reports::select('shared_reports.result_id  as student_id','users.first_name as student_name','shared_reports.month','shared_reports.created_at')
            ->join('users','shared_reports.result_id','=','users.id')
            
            ->where([['shared_reports.type','student'],['shared_reports.trainer_id',$check_token['id']]])
            ->get();
                
                
                                  if( count($mytests)>0 ){
                                      $message['data']=$mytests;
                                         $message['error']=0;
                                        $message['message']='show data success';
                                    }else{
                                          $message['data']=$mytests;
                                    
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
     
    public function buy_atest(Request $request)
     {
      try{
              $token=$request->input('user_token');
              $test=$request->input('test_id');
              $pay=$request->input('payment_id'); //1=>wallet  2//master card
             $paid=$request->input('paid');
             

            
          
          $check_token=User::where('user_token',$token)->first();
          
          
       
          if($request->has('user_token') && $check_token !=NULL){
              
              $updated_at = carbon::now()->toDateTimeString();
              $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($updated_at)));
              
              $wa=Wallet::where('user_id',$check_token['id'])->first();
              
              
              $check=user_tests::where([['test_id',$test],['user_id',$check_token['id']]])->first();
              $price=SubCategory::where('id',$test)->value('price');
              
               if($check !=null){
                        $message['error']=4;
                        $message['message']='you buy  this before';
               }else{
                   
                   
                   
                   
                   if($pay ==1){
                       
                       
                       if($wa['credit'] >= $price){
                           
                           
                           $wa=Wallet::where('user_id',$check_token['id'])->decrement('credit',$price);
 
                               $insert=new user_tests;
                                $insert->test_id=$test;
                                $insert->user_id=$check_token['id'];
                                $insert->payment=$pay;
                                $insert->created_at=$dateTime;
                                $insert->save();
                    
            
            
                              if( $insert ==true ){
                                     $message['error']=0;
                                    $message['message']='buy success';
                                }else{
                                     $message['error']=1;
                                     $message['message']='error in save data';
                                }
                       }else{
                           
                         $message['error']=5;
                         $message['message']=' your credit not enough';
                       }
                       
                   }else{
                       
                     if( $paid ==1){
                         
                                    $insert=new user_tests;
                                    $insert->test_id=$test;
                                    $insert->user_id=$check_token['id'];
                                    $insert->payment=$pay;
                                    $insert->created_at=$dateTime;
                                    $insert->save();
                        
                
                
                                  if( $insert ==true ){
                                         $message['error']=0;
                                        $message['message']='buy success';
                                    }else{
                                         $message['error']=1;
                                         $message['message']='error in share data';
                                    }
                     }else{
                         
                           $message['error']=5;
                         $message['message']=' your credit not enough';
                     }
                       
                       
                       
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
     
     
     
      public function share_reports(Request $request)
     {
      try{
              $token=$request->input('user_token');
              $report=$request->input('report_id');
              $user=$request->input('trainer_id');
             

            
          
          $check_token=User::where('user_token',$token)->first();
          
          
       
          if($request->has('user_token') && $check_token !=NULL){
              
                $updated_at = carbon::now()->toDateTimeString();
              $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($updated_at)));
              
              $check=shared_reports::where([['report_id',$report],['trainer_id',$user],['type','report']])->first();
              
              
               if($check !=null){
                        $message['error']=4;
                        $message['message']='you shared this before';
               }else{
             
                    $insert=new shared_reports;
                    $insert->report_id=$report;
                    $insert->trainer_id=$user;
                    $insert->type='report';
                    $insert->created_at=$dateTime;
                    $insert->save();
        


                  if( $insert ==true ){
                         $message['error']=0;
                        $message['message']='share success';
                    }else{
                         $message['error']=1;
                         $message['message']='error in share data';
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
     
     
    public function share_class_result(Request $request)
     {
      try{
              $token=$request->input('user_token');
              $class=$request->input('class_id');
              $mon=$request->input('month');
              $user=$request->input('trainer_id');


            
          
          $check_token=User::where('user_token',$token)->first();
            $updated_at = carbon::now()->toDateTimeString();
              $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($updated_at)));
          
       
          if($request->has('user_token') && $check_token !=NULL){
              
              
              $check=shared_reports::where([['result_id',$class],['trainer_id',$user],['month',$mon],['type','class']])->first();
              
              
               if($check !=null){
                        $message['error']=4;
                        $message['message']='you shared this before';
               }else{
             
                    $insert=new shared_reports;
                    $insert->result_id=$class;
                    $insert->trainer_id=$user;
                    $insert->type='class';
                    $insert->month=$mon;
                    $insert->created_at=$dateTime;
                    $insert->save();
        


                  if( $insert ==true ){
                         $message['error']=0;
                        $message['message']='share success';
                    }else{
                         $message['error']=1;
                         $message['message']='error in share data';
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
     
       public function share_student_result(Request $request)
     {
      try{
              $token=$request->input('user_token');
              $student=$request->input('student_id');
              $mon=$request->input('month');
              $user=$request->input('trainer_id');


            
          
          $check_token=User::where('user_token',$token)->first();
            $updated_at = carbon::now()->toDateTimeString();
              $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($updated_at)));
          
       
          if($request->has('user_token') && $check_token !=NULL){
              
              
              $check=shared_reports::where([['result_id',$student],['trainer_id',$user],['month',$mon],['type','student']])->first();
              
              
               if($check !=null){
                        $message['error']=4;
                        $message['message']='you shared this before';
               }else{
             
                    $insert=new shared_reports;
                    $insert->result_id=$student;
                    $insert->trainer_id=$user;
                    $insert->type='student';
                    $insert->month=$mon;
                    $insert->created_at=$dateTime;
                    $insert->save();
        


                  if( $insert ==true ){
                         $message['error']=0;
                        $message['message']='share success';
                    }else{
                         $message['error']=1;
                         $message['message']='error in share data';
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
     //for dash
     
     
      public function show_E3lan(Request $request)
     {
      try{
           
          

             
                    $select=E3lan::select('id','text','image','link','type')->first();
             
        


                    if( $select !=null ){
                        $message['data']= $select;
                         $message['error']=0;
                        $message['message']='show success';
                    }else{
                         $message['data']=NULL;
                         $message['error']=1;
                         $message['message']='no data ';
                   }
         

        
            }catch(Exception $ex){
                   $message['error']=4;
                   $message['message']='error'.$ex->getMessage();
            }
            return response()->json($message);
     }
     
     
      public function show_dashE3lan(Request $request)
     {
      try{
              $token=$request->input('user_token');
          

            
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
          

             
                    $select=E3lan::select('id','text','image','link','type')->first();
             
        


                  if( $select !=null ){
                        $message['data']= $select;
                         $message['error']=0;
                        $message['message']='show success';
                    }else{
                         $message['data']= $select;
                         $message['error']=1;
                         $message['message']='no data ';
                    }
         

          }else{
                $message['error']=3;
              $message['message']='this token is not exist'; 
          }
            }catch(Exception $ex){
                   $message['error']=4;
                   $message['message']='error'.$ex->getMessage();
            }
            return response()->json($message);
     }
     
     
      public function update_E3lan(Request $request)
     {
      try{
              $token=$request->input('user_token');
              $text=$request->input('text');
              $image=$request->file('image');
              $link=$request->input('link');
              $type=$request->input('type');

            
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
              
              $updated_at = carbon::now()->toDateTimeString();
              $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($updated_at)));
              
          
                        if(isset($image)) {
                            $new_name = $image->getClientOriginalName();
                            $savedFileName = rand(100000,999999).time()."_".$new_name; // give a unique name to file to be saved
                            $destinationPath_id = 'uploads/how_work';
                            $image->move($destinationPath_id, $savedFileName);
                
                            $images = $savedFileName;
                          
                   }else{
                      $images = NULL;       
                  }
             
                    $select=E3lan::where('id',1)->update([
                         'text'=>$text,
                         'image'=>$images,
                         'link'=>$link,
                         'type'=>$type,
                         'updated_at'=>$dateTime
                        
                        ]);
             
        


                  if( $select !=null ){
                        $message['data']= $select;
                         $message['error']=0;
                        $message['message']='show success';
                    }else{
                         $message['data']= $select;
                         $message['error']=1;
                         $message['message']='no data ';
                    }
         

          }else{
                $message['error']=3;
              $message['message']='this token is not exist'; 
          }
            }catch(Exception $ex){
                   $message['error']=4;
                   $message['message']='error'.$ex->getMessage();
            }
            return response()->json($message);
     }
     
     
     
     
     public function show_Adminroles(Request $request)
     {
      try{
          
        
          
            $arr=[2,3,4,5,6,7,8,9];
              
             
                    $about=Roles::select('id','name')->whereNotIn('id',$arr)->get();
             
        


                  if( count($about)>0){
                        $message['data']= $about;
                         $message['error']=0;
                        $message['message']='show success';
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
     
     
     
     
     public function add_admin(Request $request)
     {
       try{
           
            $token=$request->input('user_token');
          

            
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
           
              $fname=$request->input('first_name');
              $lname=$request->input('last_name');
              $email=$request->input('email');
              $phone=$request->input('phone');
              $image=$request->file('image');
              $pass=$request->input('passwords');
         
              
            
              $updated_at = carbon::now()->toDateTimeString();
              $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($updated_at)));
    
               if(isset($image)) {
                            $new_name = $image->getClientOriginalName();
                            $savedFileName = rand(100000,999999).time()."_".$new_name; // give a unique name to file to be saved
                            $destinationPath_id = 'uploads/users';
                            $image->move($destinationPath_id, $savedFileName);
                
                            $images = $savedFileName;
                          
                   }else{
                      $images = NULL;       
                  }
         
    
                    //verification
             if($request->has('email')){
                 $emailexist=User::select('id','email')->where('email',$email)->get();
               }
               
               if(count($emailexist) >0){
                    $message['error']=2;
                    $message['message']='email  exist';
               
               }else{
        
                    $user=User::insertGetId([
                    'first_name'=>$fname,
                    'last_name'=>$lname,
                    'email'=>$email,
                    'phone'=>$phone,
                    'password'=>$pass,
                    'state'=>1,
                    'image'=>$images,
                    'created_at'=>$dateTime]);
    
                 
          
                    if($user >0){
                        
             
                          $message['error']=0;
                           $message['message']='add admin success ';
                    }else{
                      
                          $message['error']=1;
                           $message['message']='error in save';
                    }
              

              }
          }else{
                $message['error']=3;
              $message['message']='this token is not exist'; 
          }
            }catch(Exception $ex){
                   $message['error']=4;
                   $message['message']='error'.$ex->getMessage();
            }
            return response()->json($message);
     }
     
     
     public function update_admin(Request $request)
     {
       try{
           
            $token=$request->input('user_token');
          

            
          
          $check_token=User::select('id')->where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
               $id=$request->input('id');
              $fname=$request->input('first_name');
              $lname=$request->input('last_name');
              $email=$request->input('email');
              $phone=$request->input('phone');
              $image=$request->file('image');
              $pass=$request->input('passwords');
              
              
            
              $updated_at = carbon::now()->toDateTimeString();
              $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($updated_at)));
    
               if(isset($image)) {
                            $new_name = $image->getClientOriginalName();
                            $savedFileName = rand(100000,999999).time()."_".$new_name; // give a unique name to file to be saved
                            $destinationPath_id = 'uploads/users';
                            $image->move($destinationPath_id, $savedFileName);
                
                            $images = $savedFileName;
                          
                   }else{
                      $images =User::where('id',$id)->value('image');       
                  }
         
    
                    //verification
             if($request->has('email')){
                 $emailexist=User::select('id','email')->where([['email',$email],['id','!=',$id]])->get();
               }
               
               if(count($emailexist) >0){
                    $message['error']=2;
                    $message['message']='email  exist';
               
               }else{
        
                    $update=User::where('id',$id)->update([
                    'first_name'=>$fname,
                    'last_name'=>$lname,
                    'email'=>$email,
                    'phone'=>$phone,
                    'password'=>$pass,
                    'image'=>$images,
                    'created_at'=>$dateTime]);
    
         
         
                    if($update ==true){
                        
                        
                       
     
                          $message['error']=0;
                           $message['message']='update admin success ';
                    }else{
                      
                          $message['error']=1;
                           $message['message']='error in update';
                    }
              

              }
          }else{
                $message['error']=3;
              $message['message']='this token is not exist'; 
          }
            }catch(Exception $ex){
                   $message['error']=4;
                   $message['message']='error'.$ex->getMessage();
            }
            return response()->json($message);
     }
     
     
     
     
      public function delete_admin(Request $request)
     {
       try{
           
            $token=$request->input('user_token');
          

            
          
          $check_token=User::select('id')->where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
               $id=$request->input('id');
          
        
                    $delete=User::where('id',$id)->delete();
         
          
                    if($delete ==true){
     
                          $message['error']=0;
                           $message['message']='delete admin success ';
                    }else{
                      
                          $message['error']=1;
                           $message['message']='error in delete';
                    }
              

              
          }else{
                $message['error']=3;
              $message['message']='this token is not exist'; 
          }
            }catch(Exception $ex){
                   $message['error']=4;
                   $message['message']='error'.$ex->getMessage();
            }
            return response()->json($message);
     }
     
     public function show_admins(Request $request)
     {
       try{
           
            $token=$request->input('user_token');
          

            
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
          
                           $arr=[2,3,4,5,6,7,8,9];

        
                  $select=User::select('users.id','users.first_name','users.last_name','users.email','users.phone','users.password as passwords','roles.name as state','users.created_at','users.updated_at')
                 
                   ->join('roles','users.state','=','roles.id')
                   ->where('state',1)
                  ->get();
         
                 /* for($i=0;$i<count($select);$i++){
                      
                      $select[$i]['state']=admin_roles::select('roles.name')->join('roles','admin_roles.role','=','roles.id')->where('admin_roles.admin_id',$select[$i]['id'])->get();
                  }*/
          
                    if(count($select)>0){
                           $message['data']=$select;
                           $message['error']=0;
                           $message['message']='show admin success ';
                    }else{
                           $message['data']=$select;
                           $message['error']=1;
                           $message['message']='error in show';
                    }
              

              
          }else{
                $message['error']=3;
              $message['message']='this token is not exist'; 
          }
            }catch(Exception $ex){
                   $message['error']=4;
                   $message['message']='error'.$ex->getMessage();
            }
            return response()->json($message);
     }
     
      public function show_adminbyid(Request $request)
     {
       try{
           
            $token=$request->input('user_token');
          

            
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
          
                        $id=$request->input('id');

        
                  $select=User::select('users.id','users.first_name','users.last_name','users.email','users.phone','users.password as passwords','users.created_at','users.updated_at')
                  //->join('roles','users.state','=','roles.id')
                  ->where('users.id',$id)->first();
         
          

                    if($select !=null){
                           $message['data']=$select;
                           $message['error']=0;
                           $message['message']='show admin success ';
                    }else{
                           $message['data']=$select;
                           $message['error']=1;
                           $message['message']='error in show';
                    }
              

              
          }else{
                $message['error']=3;
              $message['message']='this token is not exist'; 
          }
            }catch(Exception $ex){
                   $message['error']=4;
                   $message['message']='error'.$ex->getMessage();
            }
            return response()->json($message);
     }
     
     
     
     
     
     
     
 public function show_alladminRoles(Request $request){
 	try{
 	    
 	          $token=$request->input('user_token');
          

            
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
              
        $id=$request->input('user_id');

        $user_data =admin_roles::select('admin_roles.id', 'roles.name','admin_roles.created_at')
        ->join('roles','admin_roles.role','=','roles.id')->where('admin_roles.admin_id',$id)->get();
            
        
                if(count($user_data) >0){
        
                  $message['data']=$user_data;
                  $message['error']=0;
                  $message['message']='show data success';
                  }else{
                  $message['data']=$user_data;
                  $message['error']=1;
                  $message['message']='no data';
        
                  }




        
          }else{
                $message['error']=3;
              $message['message']='this token is not exist'; 
          }
            }catch(Exception $ex){
                   $message['error']=4;
                   $message['message']='error'.$ex->getMessage();
            }
            return response()->json($message);
 }  
 
 

 public function add_adminrole(Request $request){
 	try{
 	          $token=$request->input('user_token');
          

            
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
          
           $user=$request->input('user_id');
           $role=$request->input('role_id'); //4,4,56
         

        

		$created_at = carbon::now()->toDateTimeString();

        $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($created_at)));

  
        $my_arr=array();
       // $ids=rtrim($role,',');
        $my_arr=explode(',',$role);
        
        
          foreach($my_arr as $id){
                                      
               $roles_arr[]=array('admin_id'=>$user,'role'=>$id,'created_at'=>$dateTime);
          }
          

             $insert=admin_roles::insert($roles_arr);

             
          

                if($insert ==true){
        
                  $message['error']=0;
                  $message['message']='insert data success';
                  }else{
                  $message['error']=1;
                  $message['message']='error in insert data';
        
                  }



 
          }else{
                $message['error']=3;
              $message['message']='this token is not exist'; 
          }
            }catch(Exception $ex){
                   $message['error']=4;
                   $message['message']='error'.$ex->getMessage();
            }
            return response()->json($message);
 } 

 

public function delete_adminRole(Request $request){
 	try{
 	    
 	    $token=$request->input('user_token');
        $check_token=User::where('user_token',$token)->first();
        
        if($request->has('user_token') && $check_token !=NULL){
              
            $id=$request->input('id');
    
            $delete=admin_roles::where('id',$id)->delete();

           if($delete ==true ){
              $message['error']=0;
              $message['message']='delete data success';
          }else{
              $message['error']=1;
              $message['message']='no data';
          }

 	      }else{
                $message['error']=3;
              $message['message']='this token is not exist'; 
          }
    }catch(Exception $ex){
           $message['error']=4;
           $message['message']='error'.$ex->getMessage();
    }
    return response()->json($message);
 } 
 
 
 public function send_studentNotification(Request $request){
    $updated_at = carbon::now()->toDateTimeString();
              $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($updated_at)));
              $token=$request->input('user_token');
    $updated_at = carbon::now()->toDateTimeString();
    $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($updated_at)));
              
    $title = $request->input('title');
    $body = $request->input('body');
    $title_ar = $request->input('title_ar');
    $body_ar = $request->input('body_ar');
    
    $check_token=User::where('user_token',$token)->first();
        
    $get_user_token = \App\User::select('id','firebase_token')->where([['state', '2'] ])->get();

    if($request->has('user_token') && $check_token !=NULL){
        
        foreach($get_user_token as $firebase){
            $msg = array(
            		'body' 	=> $body,
            		'title'	=> $title,
                    'click_action' => '6',     	
                      );
            	$fields = array
            			(
            				'to'		=> $firebase->firebase_token,
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
            $save->sender_id = 1;
            $save->title   = $title;
            $save->message = $body;
            $save->title_ar = $title_ar;
            $save->message_ar = $body_ar;
            $save->user_id = $firebase->id;
            $save->created_at = $dateTime;
            $save->save();
        }
        $message['error'] = 0;
        $message['message'] = "message is send successfully";
        
    }else{
        $message['error']=3;
        $message['message']='this token is not exist'; 
    }
    return response()->json($message);
     
 }
 
 
 public function send_parentNotification(Request $request){
    $token=$request->input('user_token');
    $updated_at = carbon::now()->toDateTimeString();
    $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($updated_at)));
              
    $title = $request->input('title');
    $body = $request->input('body');
    $title_ar = $request->input('title_ar');
    $body_ar = $request->input('body_ar');
   
    $check_token=User::where('user_token',$token)->first();
        
    $get_user_token = \App\User::select('id','firebase_token')->where([['state', '3'], ['firebase_token' ,'!=' , NULL]])->get();

    if($request->has('user_token') && $check_token !=NULL){
        
        foreach($get_user_token as $firebase){
            $msg = array(
            		'body' 	=> $body,
            		'title'	=> $title,
                    'click_action' => '6',     	
                      );
            	$fields = array
            			(
            				'to'		=> $firebase->firebase_token,
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
            $save->sender_id = 1;
            $save->title   = $title;
            $save->message = $body;
            $save->title_ar = $title_ar;
            $save->message_ar = $body_ar;
            $save->user_id = $firebase->id;
            $save->created_at = $dateTime;
            $save->save();
        }
        $message['error'] = 0;
        $message['message'] = "message is send successfully";
        
    }else{
        $message['error']=3;
        $message['message']='this token is not exist'; 
    }
    return response()->json($message);
     
 }
 
  public function send_trainerNotification(Request $request){
    $token=$request->input('user_token');
    $updated_at = carbon::now()->toDateTimeString();
    $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($updated_at)));
              
    $title = $request->input('title');
    $body = $request->input('body');
    $title_ar = $request->input('title_ar');
    $body_ar = $request->input('body_ar');
   
    $check_token=User::where('user_token',$token)->first();
        
    $get_user_token = \App\User::select('id','firebase_token')->where([['state', '4'], ['firebase_token' ,'!=' , NULL]])->get();

    if($request->has('user_token') && $check_token !=NULL){
        
        foreach($get_user_token as $firebase){
            $msg = array(
            		'body' 	=> $body,
            		'title'	=> $title,
                    'click_action' => '6',     	
                      );
            	$fields = array
            			(
            				'to'		=> $firebase->firebase_token,
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
            $save->sender_id = 1;
            $save->title   = $title;
            $save->message = $body;
            $save->title_ar = $title_ar;
            $save->message_ar = $body_ar;
            $save->user_id = $firebase->id;
            $save->created_at = $dateTime;
            $save->save();
        }
        $message['error'] = 0;
        $message['message'] = "message is send successfully";
        
    }else{
        $message['error']=3;
        $message['message']='this token is not exist'; 
    }
    return response()->json($message);
     
 }
 
 
 public function send_specialNotification(Request $request){
   
    $token=$request->input('user_token');
    $updated_at = carbon::now()->toDateTimeString();
    $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($updated_at)));
    
    $user_id = $request->input('user_id');          
    $title = $request->input('title');
    $body = $request->input('body');
    $title_ar = $request->input('title_ar');
    $body_ar = $request->input('body_ar');
   
    $check_token=User::where('user_token',$token)->first();
        
    $get_user_token = \App\User::select('id','firebase_token')->where([['id', $user_id]])->first();

    if($request->has('user_token') && $check_token !=NULL){
        
            $msg = array(
            		'body' 	=> $body,
            		'title'	=> $title,
                    'click_action' => '6',     	
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
            $save->sender_id = 1;
            $save->title   = $title;
            $save->message = $body;
            $save->title_ar = $title_ar;
            $save->message_ar = $body_ar;
            $save->user_id = $get_user_token->id;
            $save->created_at = $dateTime;
            $save->save();
        
        $message['error'] = 0;
        $message['message'] = "message is send successfully";
        
    }else{
        $message['error']=3;
        $message['message']='this token is not exist'; 
    }
    return response()->json($message);
     
 }
 
 
 public function show_meeting(Request $request){

    $meeting_number = $request->input('meetingNumber');
    $role = $request->input('role');
    $api_key = "Y3UpCvRpRM--m6HEA_0SpQ";           
    $api_secret =  "2RAbm1uKmGazFE0qmpTRlYKLO3xkXZ9uPpXL";
   
      
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
 
 
 
 public function getMeeting(Request $request){

    $meeting_number = $request->input('meetingNumber');
    $role = $request->input('role');
    $api_key = $request->input('api_key');           
    $api_secret =  $request->input('api_secret');
   
      
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
 
 

}
