<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use carbon\carbon;
use App\Classes;
use App\Institution_branch;
use App\Leveles;
use App\Teacher_class;


class ClassController extends Controller
{
    public $message=array();

     public function show_myclasses(Request $request)
     {
      try{
          $token=$request->input('user_token');
          $lang=$request->input('lang');
          
           
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
            
            
              
            if($lang=='ar' ||$lang=='AR'){  
                $branch=Institution_branch::select('institiution_branch.id','institiution_branch.name')
                                           ->join('class','institiution_branch.id','=','class.branch_id')
                                           ->join('teacher_class','class.id','=','teacher_class.class_id')
                                           ->where('teacher_class.teacher_id',$check_token['id'])
                                           ->get();
    
            }else{
                $branch=Institution_branch::select('institiution_branch.id','institiution_branch.E_name as name')
                                           ->join('class','institiution_branch.id','=','class.branch_id')
                                           ->join('teacher_class','class.id','=','teacher_class.class_id')
                                           ->where('teacher_class.teacher_id',$check_token['id'])
                                           ->get();
            }
    
                for($i=0;$i<count($branch);$i++) {
                  $show=Teacher_class::select('class.id','class.name','class.created_at')
                                   ->join('class','teacher_class.class_id','=','class.id')
                                   ->where([['class.branch_id',$branch[$i]['id']],['teacher_class.teacher_id',$check_token['id']]])
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

      public function show_oneClass(Request $request)
     {
      try{
          $token=$request->input('user_token');
          $id=$request->input('class_id');
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
              
               $teachers=User::select('users.id','users.first_name')
                 ->join('teacher_class','users.id','=','teacher_class.teacher_id')
                 ->where('teacher_class.class_id',$id)->orderBy('users.id')
                 ->get();

               $students=User::select('users.id','users.first_name')
                 ->where('class_id',$id)
                 ->get();
      
                 
          
                  if( count($teachers)>0){
                        $message['teachers']= $teachers;
                        $message['students']= $students;
                        $message['error']=0;
                        $message['message']='show data success';
                    }else{
                         $message['teachers']= $teachers;
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


      public function show_institutionClasses(Request $request)
     {
      try{
          $token=$request->input('user_token');
          $id=$request->input('school_id');
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
               

               $branch=Institution_branch::select('id','name')
               ->where('institution_id',$id)
               ->get();

            for($i=0;$i<count($branch);$i++) {

            	 $show=Classes::select('class.id','class.name')
               ->where('class.branch_id',$branch[$i]['id'])
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


    //fill the form to makea test
     public  function fill_form(Request $request){
      try{
          $token=$request->input('user_token');
          $name=$request->input('name');
          $date=$request->input('birth_date'); 
          $ex_name=$request->input('examiner_name'); 
          $app=$request->input('app_date');
          $report_date=$request->input('report_date');
          $nationality=$request->input('nationality');
          $language=$request->input('language');
          $type=$request->input('type'); 
          $hand=$request->input('main_hand');
          $gender=$request->input('gender'); 
          $test_type=$request->input('test_type');
          $school=$request->input('school'); 
          $level=$request->input('educational_level'); 




          $created_at = carbon::now()->toDateTimeString();
          $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($created_at)));
           
          $check_token=User::where('user_token',$token)->first();
           if($request->has('user_token') && $check_token !=NULL){
           }
          
          

         }catch(Exception $ex){
            $message['error']=2;
            $message['message']='error'.$ex->getMessage();
         }  
         
         return response()->json($message);


  }



/*
if($request->has('user_token') && $check_token !=NULL){

             

          }else{
              
               $message['error']=3;
              $message['message']='this token is\'t exist ';
          }


*/
}
