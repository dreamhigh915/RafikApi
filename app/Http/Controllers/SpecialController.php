<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Http;

use Illuminate\Http\Request;
use App\User;
use carbon\carbon;
use App\Test;
use App\Test_questions;
use App\SubCategory;
use App\Languages;
use App\Institution;
use App\Reports;


class SpecialController extends Controller
{
    public $message=array();

                     
    public function make_atest(Request $request){

      try{
          $name=$request->input('name');
          $date=$request->input('birth_date'); 
          $ex_name=$request->input('examiner_name'); 
          $app=$request->input('app_date');
          $nationality=$request->input('nationality');
          $language=$request->input('language');
          $hand=$request->input('main_hand');
          $gender=$request->input('gender'); 
          $school=$request->input('school'); 
       //   $level=$request->input('educational_level'); 
          $user_data='';
           $sub=$request->input('test_category');
      	  $code=0;

          $updated_at = carbon::now()->toDateTimeString();
          $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($updated_at)));

          
                         try{
                            
                              
                                $code=  rand(1000,9999);
                                //rand(1000000,9999999).time();// give a unique name to file to be saved
                                  
                               }catch(Exception $ex){
                                    $message['error']=6;
                                   $message['message']='error in generate key';    
                              }
                              
                              
                              
       $test_data=SubCategory::where('id',$sub)->first();
                    
             $insert=new Reports;
             $insert->user_id=$code;
             $insert->examiner_id=NULL;
             $insert->name=$name;
             $insert->birth_date=$date;
             $insert->report_name=$test_data['name'];
             $insert->second_tool='مقياس الوظائف والمهن';
             $insert->examiner_name=$ex_name;
             $insert->test_type='2';
             $insert->test_category=$sub;
             $insert->type=4;
             $insert->nationality=$nationality;
             $insert->main_hand=$hand;
             $insert->gender=$gender;
             $insert->institution=$school;
             $insert->app_date=$app;
             ///$insert->educational_level=$level;
             $insert->created_at=$dateTime;
             $insert->save();
           

           if($insert == true){
            $message['report_id']=$insert->id;
            $message['error']=0;
            $message['message']='go to test';

           }else{
               $message['report_id']=null;
            $message['error']=1;
            $message['message']='error in save data';
           }



      }catch(Exception $ex){
        $message['error']=2;
        $message['message']='error '.$ex->getMessage();
      }
      return response()->json($message);
    }
    
       public function    el3amil_code(Request $request){

      try{
          $token=$request->input('user_token');
          $code=$request->input('code'); 
          
           $sub=$request->input('test_category');
      	

          $updated_at = carbon::now()->toDateTimeString();
          $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($updated_at)));

           
         $user_data=User::where('user_token',$token)->first();
         $el3amil=User::where('code',$code)->first();
         
          if($el3amil ==null){
              
              $message['error']=3;
              $message['message']='this code  is not exist write correct code';
          }else{
              
              
              
              
                    
             $insert=new Reports;
             $insert->user_id=$el3amil['id'];
             $insert->examiner_id=$user_data['id'];
             $insert->name=$el3amil['first_name'];
             $insert->birth_date=$el3amil['birth_date'];
             $insert->examiner_name=$user_data['first_name'];
             $insert->test_type='2';
             $insert->test_category=$sub;
             $insert->type=4;
             $insert->nationality=$el3amil['nationality'];
            // $insert->main_hand=$hand;
             $insert->gender=$el3amil['gender'];
             $insert->institution=$el3amil['institution_id'];
             $insert->app_date=$dateTime;
             $insert->educational_level=$el3amil['level'];
             $insert->created_at=$dateTime;
             $insert->save();
           

           if($insert == true){
             $message['report_id']=$insert->id;
            $message['error']=0;
            $message['message']='go to test';

           }else{
                 $message['report_id']=null;
            $message['error']=1;
            $message['message']='error in save data';
           }

        }

      }catch(Exception $ex){
        $message['error']=2;
        $message['message']='error '.$ex->getMessage();
      }
      return response()->json($message);
    }


     public function    create_El3mail_account(Request $request){

      try{
          $token=$request->input('user_token');
          $username=$request->input('user_name'); 
          $pass=$request->input('password'); 
          $sub=$request->input('test_category');
          $email = $request->input('email');          	

          $updated_at = carbon::now()->toDateTimeString();
          $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($updated_at)));
          
          $check = User::where('user_name',$username)->first();
          $check_email = User::where('email',$email)->first();

            if($check !=null){
              
                $message['error']=3;
                $message['message']='user name is exist';
            }elseif($check_email != NULL){
                $message['error'] = 7;
                $message['message'] = "this email is already exist";
            }else{
              
              
                try{
                    
                      
                        $code=  rand(1000,9999);
                        //rand(1000000,9999999).time();// give a unique name to file to be saved
                      
               }catch(Exception $ex){
                    $message['error']=6;
                   $message['message']='error in generate key';    
              }
                  $insert_user=new User;
                  $insert_user->user_name = $username;
                  $insert_user->email = $email;
                  $insert_user->password = $pass;
                  $insert_user->code = $code;
                  $insert_user->created_at = $dateTime;
                  $insert_user->save();
        
                  
                  
                  if($insert_user==true){
                    $user_data=User::where('user_token',$token)->first();
                    $el3amil=User::where('id',$insert_user['id'])->first();
                            
                     $insert=new Reports;
                     $insert->user_id=$el3amil['id'];
                     $insert->examiner_id=$user_data['id'];
                     $insert->name=$el3amil['first_name'];
                     $insert->birth_date=$el3amil['birth_date'];
                     $insert->examiner_name=$user_data['first_name'];
                     $insert->test_type='2';
                     $insert->test_category=$sub;
                     $insert->type=4;
                     $insert->nationality=$el3amil['nationality'];
                    // $insert->main_hand=$hand;
                     $insert->gender=$el3amil['gender'];
                     $insert->institution=$el3amil['institution_id'];
                     $insert->app_date=$dateTime;
                     $insert->educational_level=$el3amil['level'];
                     $insert->created_at=$dateTime;
                     $insert->save();
                   
        
                   if($insert == true){
                    $message['report_id']=$insert->id;
                    $message['error']=0;
                    $message['message']='go to test';
        
                   }else{
                    $message['report_id']=null;
                    $message['error']=1;
                    $message['message']='error in save data';
                
                  }  
                  }else{
                          $message['error']=4;
                    $message['message']='error in save data';
                       
                   }
          }


      }catch(Exception $ex){
        $message['error']=2;
        $message['message']='error '.$ex->getMessage();
      }
      return response()->json($message);
    }
    
// public function showUser(Request $request){
//         // $id = $request->input('id');
//         $get_user = \App\User::find(1);
        
//         return $get_user;
//     }
    
    
//     public function getdata(Request $request){
        
//         $response = Http::get('http://test.com');
//         return $response;
//     }
    
    

        
    
    
    
    
  
}
