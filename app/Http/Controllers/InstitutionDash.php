<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Institution;
use carbon\carbon;
use Illuminate\Support\Facades\DB;
use App\Message;
use App\Institution_branch;
use App\Classes;
use App\Leveles;
use App\Wallet;
use App\User;
use App\setting;
use Illuminate\Support\Str;
use App\Specialisties;
use App\Trainer_specialist;
use App\Teacher_class;


class InstitutionDash extends Controller
{
     public $message=array();
     
     
     
      public function data_count(Request $request)
     {
      try{ 
                   $token=$request->input('user_token');
                   
                   
                   $check_token=User::where('user_token',$token)->first();

            if($request->has('user_token') && $check_token !=NULL){
                  
                      $branch=Institution_branch::where('institution_id',$check_token['id'])->count();
                      
                      $class=Classes::where('institution_id',$check_token['id'])->count();
                      
                      $trainer=User::where([['state',4],['institution_id',$check_token['id']]])->count();
                      
                      $student=User::where([['state',2],['institution_id',$check_token['id']]])->count();
                      
                      $level=Leveles::where('institution_id',$check_token['id'])->count();
                     
                  
            
     
                    $message['branches']=$branch;
                    $message['levels']=$level;
                    $message['trainer']=$trainer;
                    $message['student']=$student;
                    $message['class']=$class;
                 
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
     
     public function show_Trainerspecialists(Request $request)
     {
      try{
          
             $token=$request->input('user_token');
           $lang=$request->input('lang');
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
              
              
             
              if($lang=='ar' ||$lang=='AR'){
                $show=Trainer_specialist::select('trainer_specialties.id','specialties.name','trainer_specialties.created_at','trainer_specialties.updated_at')
            ->join('specialties','trainer_specialties.specialties_id','=','specialties.id')
            ->where('trainer_specialties.trainer_id',$check_token['id'])->get();              
            
              }else{
                  
             $show=Trainer_specialist::select('trainer_specialties.id','specialties.E_name as name','trainer_specialties.created_at','trainer_specialties.updated_at')
            ->join('specialties','trainer_specialties.specialties_id','=','specialties.id')
            ->where('trainer_specialties.trainer_id',$check_token['id'])->get();
              }
        
          
              if(count($show)>0){
               
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
     
     
     
     
    public function institution_login(Request $request)
     {
     try{
         $email=$request->input('email');
         $pass=$request->input('passwords');
              $updated_at = carbon::now()->toDateTimeString();
              $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($updated_at)));
         
        
              $userexist=User::select('users.id','users.first_name as name','users.email','users.password as passwods','users.phone','roles.name as state','users.image','users.created_at','users.updated_at')
              ->join('roles','users.state','=','roles.id')
              ->where([['users.email',$email],['users.password',$pass],['users.state','=',7]])->first();
              
              
              
                try{
                    
		                  
		                    $user_token=hash('sha256', Str::random(60)).rand(10000,999999).time();
		                    //rand(1000000,9999999).time();// give a unique name to file to be saved
		                    
		                    $insert=User::where('id',$userexist['id'])->update([
		                        'user_token'=>$user_token,
		                        'updated_at'=>$dateTime
		                    ]);
		                    
		           }catch(Exception $ex){
		                $message['error']=4;
                   $message['message']='error in generate key';    
		          }
              
              
              
               $userexist=User::select('users.id','users.first_name as name','users.email','users.password as passwods','users.phone','roles.name as state','users.image','users.user_token','users.created_at','users.updated_at')->join('roles','users.state','=','roles.id')
              ->where([['users.email',$email],['users.password',$pass],['users.state','=',7]])->first();
              

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



   public function show_institution_Byid(Request $request)
    {
       try{
       
      
             $token=$request->input('user_token');
           $lang=$request->input('lang');
           
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
              
            $select=User::select('id','first_name as name','E_name','image','password as passwords','phone','email')->where('id',$check_token['id'])->first();

               

          if( $select !=null){
              $message['data']=$select;
             // $message['branches']=$branch;
              $message['error']=0;
              $message['message']='show success';
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
    
    
       public function edit_institutionProfle(Request $request)
    {
       try{
          $name=$request->input('name');
            $e_name=$request->input('E_name');
             $image=$request->file('image');
                  $token=$request->input('user_token');
           $email=$request->input('email');
           $phone=$request->input('phone');
             $pass=$request->input('passwords');

          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){

                $created_at = carbon::now()->toDateTimeString();
          $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($created_at)));

             if(isset($image)) {
		                    $new_name = $image->getClientOriginalName();
		                    $savedFileName = rand(100000,999999).time()."_".$new_name; // give a unique name to file to be saved
		                    $destinationPath_id = 'uploads/institution';
		                    $image->move($destinationPath_id, $savedFileName);
		        
		                    $images = $savedFileName;
		                  
		           }else{
		              $images =User::where('id',$check_token['id'])->value('image');       
		          }

           
            $update=User::where('id',$check_token['id'])->update([
                'first_name'=>$name,
                  'E_name'=>$e_name,
                  'email'=>$email,
                  'phone'=>$phone,
                    'image'=>$images,
                    'password'=>$pass,
                'updated_at'=>$dateTime
                
                ]);
            
            // $select=User::where('id',$id)->first();
         
          if( $update ==true){
             // $message['data']=$select;
              $message['error']=0;
              $message['message']='update success';
          }else{
             // $message['data']=$select;
              $message['error']=1;
              $message['message']='error in update data';
          }
          }else{
              $message['error']=3;
              $message['message']='this token is nt exist'; 
          }
        }catch(Exception $ex){
             
              $message['error']=2;
              $message['message']='error'.$ex->getMessage();

        }
       return response()->json($message);
    }
    
         public function show_trainerbyid(Request $request)
    {
       try{
           
           $token=$request->input('user_token');
          $id=$request->input('id');

          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
       
          $userexist=User::select('users.id','users.first_name','users.last_name','users.email','users.password as passwords','users.phone','roles.name as state','users.image','users.created_at','users.updated_at')
            ->join('roles','users.state','=','roles.id')
                      
            ->where('users.id',$id)->get();

          if(count($userexist)>0){

            $message['data']=$userexist;
            $message['error']=0;
            $message['message']='user data';

          }else{
            $message['data']=$userexist;
            $message['error']=1;
            $message['message']='no data for user ';
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
    public function show_schooltrainer(Request $request)
    {
       try{
           
           $token=$request->input('user_token');
         
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
       
          $userexist=User::select('users.id','users.first_name','users.last_name','users.user_name','users.email','users.password as passwords','users.subject','roles.name as state','users.created_at','users.updated_at')
            ->join('roles','users.state','=','roles.id')
                      
            ->where([['users.state',4],['institution_id',$check_token['id']]])->get();

          if(count($userexist)>0){

            $message['data']=$userexist;
            $message['error']=0;
            $message['message']='user data';

          }else{
            $message['data']=$userexist;
            $message['error']=1;
            $message['message']='no data for user ';
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
    
    
    public function show_trainer_byid(Request $request)
    {
       try{
           
           $token=$request->input('user_token');
            $id=$request->input('id');
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
       
          $userexist=User::select('users.id','users.first_name','users.last_name','users.user_name','users.email','users.password as passwords','users.subject','users.created_at','users.updated_at')
            ->join('roles','users.state','=','roles.id')
                      
            ->where('users.id',$id)->get();

          if(count($userexist)>0){

            $message['data']=$userexist;
            $message['error']=0;
            $message['message']='user data';

          }else{
            $message['data']=$userexist;
            $message['error']=1;
            $message['message']='no data for user ';
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
    public function show_schoolstudents(Request $request)
    {
       try{
             $token=$request->input('user_token');
           
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
       
          $userexist=User::select('users.id','users.first_name','users.last_name','users.user_name','users.password as passwords','roles.name as state','users.code','levels.name as level','class.name as class','institiution_branch.name as branch','users.created_at','users.updated_at')
              ->join('roles','users.state','=','roles.id')
              ->join('class','users.class_id','=','class.id')
              ->join('levels','users.level','=','levels.id')
              ->join('institiution_branch','users.branch_id','=','institiution_branch.id')
            ->where([['users.state',2],['users.institution_id',$check_token['id']]])->get();

                  if(count($userexist)>0){
        
                    $message['data']=$userexist;
                    $message['error']=0;
                    $message['message']='user data';
        
                  }else{
                    $message['data']=$userexist;
                    $message['error']=1;
                    $message['message']='no data for user ';
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
    
    
    public function show_studentbyid(Request $request)
    {
       try{
             $token=$request->input('user_token');
             $id=$request->input('id');

          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
       
          $userexist=User::select('users.id','users.first_name','users.last_name','users.user_name','users.password as passwords','users.class_id','users.level as level_id','users.branch_id')
           // ->join('roles','users.state','=','roles.id')
                      
            ->where('users.id',$id)->get();

                  if(count($userexist)>0){
        
                    $message['data']=$userexist;
                    $message['error']=0;
                    $message['message']='user data';
        
                  }else{
                    $message['data']=$userexist;
                    $message['error']=1;
                    $message['message']='no data for user ';
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
    
    
    
     public function insert_branch(Request $request)
    {
       try{
       
            
            
            $token=$request->input('user_token');
            $name=$request->input('name');
            $e_name=$request->input('E_name');
      
           
         
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){

                $created_at = carbon::now()->toDateTimeString();
          $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($created_at)));


          
		          
		           $check=Institution_branch::where([['name',$name],['institution_id',$check_token['id']]])->orwhere([['E_name',$e_name],['institution_id',$check_token['id']]])->first();
          
          if($check !=null){
              
              $message['error']=3;
              $message['message']='this name is exist';
              
          }else{

           
            $insert=new Institution_branch;
            $insert->name=$name;
            $insert->E_name=$e_name;
            $insert->institution_id=$check_token['id'];
            $insert->created_at=$dateTime;
            $insert->save();

          if( $insert ==true){

              
              $message['error']=0;
              $message['message']='insert success';
          }else{
            
              $message['error']=1;
              $message['message']='error in insert data';
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
    
    
     public function update_branch(Request $request)
    {
       try{
       
            
            
            $token=$request->input('user_token');
             $id=$request->input('id');
            $name=$request->input('name');
            $e_name=$request->input('E_name');
      
           
         
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){

                $created_at = carbon::now()->toDateTimeString();
          $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($created_at)));



           
            $update=Institution_branch::where('id',$id)->update([
                
                   'name'=>$name,
                   'E_name'=>$e_name,
                   'updated_at'=>$dateTime
                
                ]);
          

          if( $update ==true){

              
              $message['error']=0;
              $message['message']='update success';
          }else{
            
              $message['error']=1;
              $message['message']='error in update data';
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

  public function show_branches(Request $request)
   {
       try{
       
           
             $token=$request->input('user_token');
            $lang=$request->input('lang');

          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
    
    
              if($lang =='ar' ||$lang=='AR'){
              $branch=Institution_branch::select('id','name', 'created_at', 'updated_at')
               ->where('institution_id',$check_token['id'])
               ->get();
              }else{
                  
                   $branch=Institution_branch::select('id','E_name as name',  'created_at', 'updated_at')
               ->where('institution_id',$check_token['id'])
               ->get();
                  
              }
            
               

          if( count($branch) >0){
           
              $message['data']=$branch;
              $message['error']=0;
              $message['message']='show success';
          }else{
              $message['data']=$branch;
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

   public function show_branchebyid(Request $request)
   {
       try{
       
           
             $token=$request->input('user_token');
             $id=$request->input('id');

          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
    

            $data=Institution_branch::select('id','name','E_name','created_at')
               ->where('id',$id)
               ->first();
   

          if( $data !=null){
           
              $message['data']=$data;
         
              $message['error']=0;
              $message['message']='show success';
          }else{
              $message['data']=$data;
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
  public function delete_branche(Request $request)
   {
       try{
       
           
             $token=$request->input('user_token');
             $id=$request->input('id');

          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
    

            $delete=Institution_branch::where('id',$id)->delete();
   

          if( $delete ==true){
           
          
         
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

    public function insert_level(Request $request)
    {
       try{
       
            
            
            $token=$request->input('user_token');
            $name=$request->input('name');
            $e_name=$request->input('E_name');
            $id=$request->input('branch_id');
           
         
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){

                $created_at = carbon::now()->toDateTimeString();
          $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($created_at)));


          
		          
		           $check=Leveles::where([['name',$name],['branch_id',$id]])->get();
          
          if(count($check)>0){
              
              $message['error']=3;
              $message['message']='this name is exist';
              
          }else{

           
            $insert=new Leveles;
            $insert->name=$name;
            $insert->E_name=$e_name;
            $insert->branch_id=$id;
            $insert->created_at=$dateTime;
            $insert->save();

          if( $insert ==true){

              
              $message['error']=0;
              $message['message']='insert success';
          }else{
            
              $message['error']=1;
              $message['message']='error in insert data';
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

    public function update_level(Request $request)
    {
       try{
      
            $token=$request->input('user_token');
           
            $id=$request->input('level_id');
            $name=$request->input('name');
            $e_name=$request->input('E_name');
          
          $check_token=User::where('user_token',$token)->first();
                  
          if($request->has('user_token') && $check_token !=NULL){

                $created_at = carbon::now()->toDateTimeString();
          $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($created_at)));


          
            $update=Leveles::where('id',$id)->update([
             'name'=>$name,
             'E_name'=>$e_name,
             'updated_at'=>$dateTime

            ]);
         

          if( $update ==true){

              $message['error']=0;
              $message['message']='update success';
          }else{
              $message['error']=1;
              $message['message']='error in update data';
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


    public function delete_level(Request $request)
    {
       try{
      
            $token=$request->input('user_token');
           
            $id=$request->input('level_id');
            
          
          $check_token=User::where('user_token',$token)->first();
                  
          if($request->has('user_token') && $check_token !=NULL){

               

          
            $delete=Leveles::where('id',$id)->delete();
         

          if( $delete ==true){

          	$delete=Classes::where('level_id',$id)->delete();
          	
          	$student=User::where('level',$id)->delete();

              $message['error']=0;
              $message['message']='delete success';
          }else{
              $message['error']=1;
              $message['message']='error in delete data';
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

    public function show_levelbyid(Request $request)
    {
       try{
      
            $token=$request->input('user_token');
           
            $id=$request->input('level_id');
            
          
          $check_token=User::where('user_token',$token)->first();
                  
          if($request->has('user_token') && $check_token !=NULL){

               

          
            $select=Leveles::select('id','name','E_name')->where('id',$id)->get();
            
            


          if( count($select) >0){

              $message['data']=$select;
              $message['error']=0;
              $message['message']='show success';
          }else{
              $message['data']=$select;
              $message['error']=1;
              $message['message']='error in show data';
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
    
    
    
      public function show_levels(Request $request)
    {
       try{
      
            $token=$request->input('user_token');
           
            $id=$request->input('branch_id');
              $lang=$request->input('lang');
            
          
          $check_token=User::where('user_token',$token)->first();
                  
          if($request->has('user_token') && $check_token !=NULL){

               

           if($lang=='ar' || $lang=='AR'){
            $select=Leveles::select('id','name','created_at','updated_at')->where('branch_id',$id)->get();
           }else{
               
              $select=Leveles::select('id','E_name as name','created_at','updated_at')->where('branch_id',$id)->get();
  
           }
            
            


          if( count($select) >0){

              $message['data']=$select;
              $message['error']=0;
              $message['message']='show success';
          }else{
              $message['data']=$select;
              $message['error']=1;
              $message['message']='error in show data';
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



    public function show_classbyid(Request $request)
    {
       try{
      
            $token=$request->input('user_token');
           
            $id=$request->input('class_id');
            
          
          $check_token=User::where('user_token',$token)->first();
                  
          if($request->has('user_token') && $check_token !=NULL){

               

          
            $select=Classes::select('id','name','type')->where('id',$id)->get();


         

          if( count($select) > 0){

              $message['data']=$select;
              $message['error']=0;
              $message['message']='show success';
          }else{
              $message['data']=$select;
              $message['error']=1;
              $message['message']='error in show data';
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


     public function show_classes(Request $request)
    {
       try{
      
            $token=$request->input('user_token');
           
            $id=$request->input('level_id');
             $lang=$request->input('lang');
            
          
          $check_token=User::where('user_token',$token)->first();
                  
          if($request->has('user_token') && $check_token !=NULL){

               
               
          
                   $select=Classes::select('id','name','type','created_at','updated_at')->where('level_id',$id)->get();
               


         

          if( count($select) > 0){

              $message['data']=$select;
              $message['error']=0;
              $message['message']='show success';
          }else{
              $message['data']=$select;
              $message['error']=1;
              $message['message']='error in show data';
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
//classes

    public function insert_class(Request $request)
    {
       try{
       
            
            
            $token=$request->input('user_token');
            $name=$request->input('name');
            $type=$request->input('type');
            $id=$request->input('level_id');
           
         
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){

                $created_at = carbon::now()->toDateTimeString();
          $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($created_at)));

     $check_number=Classes::where('institution_id',$check_token['id'])->count();
            if($check_token['class_number'] >$check_number){
		          
		           $check=Classes::where([['name',$name],['level_id',$id]])->get();
          
          if(count($check)>0){
              
              $message['error']=3;
              $message['message']='this name is exist';
              
          }else{

           $branch=Leveles::where('id',$id)->value('branch_id');
           
            $insert=new Classes;
            $insert->name=$name;
            $insert->type=$type;
            $insert->level_id=$id;
            $insert->institution_id=$check_token['id'];
            $insert->branch_id=$branch;
            $insert->created_at=$dateTime;
            $insert->save();

                  if( $insert ==true){
        
                      
                      $message['error']=0;
                      $message['message']='insert success';
                  }else{
                    
                      $message['error']=1;
                      $message['message']='error in insert data';
                  }
          }
            }else{
                    $message['error']=5;
                    $message['message']='sorry you exceed the limited number of classes '.$check_number;
                
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

    public function update_class(Request $request)
    {
       try{
      
            $token=$request->input('user_token');
           
            $id=$request->input('id');
            $name=$request->input('name');
            $type=$request->input('type');
          
          $check_token=User::where('user_token',$token)->first();
                  
          if($request->has('user_token') && $check_token !=NULL){

                $created_at = carbon::now()->toDateTimeString();
          $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($created_at)));


          
            $update=Classes::where('id',$id)->update([
             'name'=>$name,
             'type'=>$type,
             'updated_at'=>$dateTime

            ]);
         

          if( $update ==true){

              $message['error']=0;
              $message['message']='update success';
          }else{
              $message['error']=1;
              $message['message']='error in update data';
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


    public function delete_class(Request $request)
    {
       try{
      
            $token=$request->input('user_token');
           
            $id=$request->input('class_id');
            
          
          $check_token=User::where('user_token',$token)->first();
                  
          if($request->has('user_token') && $check_token !=NULL){

               

          
            $delete=Classes::where('id',$id)->delete();
         

          if( $delete ==true){

          
          	
          	$student=User::where('level',$id)->delete();

              $message['error']=0;
              $message['message']='delete success';
          }else{
              $message['error']=1;
              $message['message']='error in delete data';
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
    
    
    
    public function insert_student(Request $request)
    {
       try{
       
            
            
            $token=$request->input('user_token');
            $fname=$request->input('first_name');
            $lname=$request->input('last_name');
            $username=$request->input('user_name');
            $pass=$request->input('passwords');
            $class=$request->input('class_id');
            $level=$request->input('level_id');
            $branch=$request->input('branch_id');
            
            
            $code=$request->input('code');
           
         
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){

                $created_at = carbon::now()->toDateTimeString();
          $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($created_at)));
          
          
           if(!$request->has('level_id')){
               
               $level=Classes::where('id',$class)->value('level_id');
               
           }

             
           if(!$request->has('branch_id')){
               
               $branch=Classes::where('id',$class)->value('branch_id');
               
           }


              if($request->has('code')){
                  
                  
                    $action=User::where('code',$code)->update([
                        'institution_id'=>$check_token['id'],
                        'class_id'=>$class,
                        'level'=>$level,
                        'branch_id'=>$branch,
                        'updated_at'=>$dateTime
                        ]);
                        
                        
                        
                          if( $action ==true){
    
                  
                          $message['error']=0;
                          $message['message']='action success';
                      }else{
                        
                          $message['error']=1;
                          $message['message']='error in action data';
                      }
              }else{
		          
        		   $check=User::where('user_name',$username)->get();
                  
                  if(count($check)>0){
                      
                      $message['error']=3;
                      $message['message']='this user_name is exist';
                      
                      
                  }else{
        
        
                            try{
                            
                              
                                $code=  rand(1000,9999);
                                //rand(1000000,9999999).time();// give a unique name to file to be saved
                                  
                               }catch(Exception $ex){
                                    $message['error']=6;
                                   $message['message']='error in generate key';    
                              }

                   
                    $action=new User;
                    $action->first_name=$fname;
                    $action->last_name=$lname;
                    $action->user_name=$username;
                    $action->password=$pass;
                    $action->class_id=$class;
                    $action->level=$level;
                    $action->code=$code;
                    $action->branch_id=$branch;
                    $action->state=2;
                    $action->institution_id=$check_token['id'];
                    $action->created_at=$dateTime;
                    $action->save();
                    
                      if( $action ==true){
                          
                            $makeWallet=new Wallet;
                            $makeWallet->user_id=$action->id;
                            $makeWallet->credit=0;
                            $makeWallet->created_at=$dateTime;
                            $makeWallet->save();
                    
                    
                          $set=new setting;
                          $set->user_id=$action->id;
                          $set->notification='ON';
                          $set->credit=0;
                          
                          $set->created_at=$dateTime;
                          $set->save();
                    
    
                  
                  $message['error']=0;
                  $message['message']='action success';
              }else{
                
                  $message['error']=1;
                  $message['message']='error in action data';
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
    
     public function update_student(Request $request)
    {
       try{
       
            
            
            $token=$request->input('user_token');
          $id=$request->input('id');
 
            $fname=$request->input('first_name');
            $lname=$request->input('last_name');
            $username=$request->input('user_name');
            $pass=$request->input('passwords');
            $class=$request->input('class_id');
            $level=$request->input('level_id');
            $branch=$request->input('branch_id');
            
            

         
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){

                $created_at = carbon::now()->toDateTimeString();
          $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($created_at)));
          
          
           if(!$request->has('level_id')){
               
               $level=User::where('id',$id)->value('level_id');
               
           }

             
           if(!$request->has('branch_id')){
               
               $branch=User::where('id',$id)->value('branch_id');
               
           }


               if(!$request->has('class_id')){
               
               $class=User::where('id',$id)->value('class_id');
               
           }
        		   $check=User::where([['user_name',$username],['id','!=',$id]])->get();
                  
                  if(count($check)>0){
                      
                      $message['error']=3;
                      $message['message']='this user_name is exist';
                      
                  }else{
        
        
                           

                   
                    $action=User::where('id',$id)->update([
                        
                   'first_name'=>$fname,
                    'last_name'=>$lname,
                    'user_name'=>$username,
                    'password'=>$pass,
                    'class_id'=>$class,
                    'level'=>$level,
                    
                    'branch_id'=>$branch,
                    
                   
                    'updated_at'=>$dateTime
                        
                        
                        
                        
                        ]);
                        if( $action ==true){
    
                  
                          $message['error']=0;
                          $message['message']='action success';
                      }else{
                        
                          $message['error']=1;
                          $message['message']='error in action data';
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

    public function insert_teacher(Request $request)
    {
       try{
       
            
            
            $token=$request->input('user_token');
            $fname=$request->input('first_name');
            $lname=$request->input('last_name');
            $username=$request->input('user_name');
            $pass=$request->input('passwords');
             $email=$request->input('email');
             $subject=$request->input('subject');
            
            


         
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){

                $created_at = carbon::now()->toDateTimeString();
          $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($created_at)));
          
          

		          
        		   $check=User::where('user_name',$username)->first();
                  
                  if($check !=null){
                      
                      $message['error']=3;
                      $message['message']='this user_name is exist';
                      
                  }else{
        
                   
                    $action=new User;
                    $action->first_name=$fname;
                    $action->last_name=$lname;
                    $action->user_name=$username;
                    $action->password=$pass;
                    $action->email=$email;
                    $action->state=4;
                    $action->subject=$subject;
                    $action->institution_id=$check_token['id'];
                    $action->created_at=$dateTime;
                    $action->save();
                    
                    
                    if( $action ==true){
                        
                        
                            $makeWallet=new Wallet;
                            $makeWallet->user_id=$action->id;
                            $makeWallet->credit=0;
                            $makeWallet->created_at=$dateTime;
                            $makeWallet->save();
                    
                    
                          $set=new setting;
                          $set->user_id=$action->id;
                          $set->notification='ON';
                          $set->credit=0;

                      
                      $message['error']=0;
                      $message['message']='action success';
                  }else{
                    
                      $message['error']=1;
                      $message['message']='error in action data';
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
    
      public function update_teacher(Request $request)
    {
       try{
       
            
            
            $token=$request->input('user_token');
                $id=$request->input('id');
            $fname=$request->input('first_name');
            $lname=$request->input('last_name');
            $username=$request->input('user_name');
            $pass=$request->input('passwords');
             $email=$request->input('email');
             $subject=$request->input('subject');
            
            

         
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){

                $created_at = carbon::now()->toDateTimeString();
          $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($created_at)));
          
          

		        
        
                   
                    $action=User::where('id',$id)->update([
                        
                        'first_name'=>$fname,
                       'last_name'=>$lname,
                       'user_name'=>$username,
                            'password'=>$pass,
                             'email'=>$email,
                             'subject'=>$subject,
                             'updated_at'=>$dateTime
                        
                        
                        
                        ]);
                
                    
                    
                    if( $action ==true){

                      
                      $message['error']=0;
                      $message['message']='action success';
                  }else{
                    
                      $message['error']=1;
                      $message['message']='error in action data';
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
    
    
    
   
    
    
    
       public function delete_teacher(Request $request)
    {
       try{
       
            
            
            $token=$request->input('user_token');
            $id=$request->input('id');

          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){

                $created_at = carbon::now()->toDateTimeString();
          $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($created_at)));
          
          

		          
        		   $delete=User::where('id',$id)->delete();
                  
                 
          if( $delete ==true){

              
              $message['error']=0;
              $message['message']='action success';
          }else{
            
              $message['error']=1;
              $message['message']='error in action data';
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
    
     public function add_teacher_class(Request $request)
    {
       try{
       
            
            
            $token=$request->input('user_token');
            $teacher=$request->input('teacher_id');
            $branch=$request->input('branch_id');
            $level=$request->input('level_id');
            $class=$request->input('class_id');
          
  
            
            

         
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){

                $created_at = carbon::now()->toDateTimeString();
          $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($created_at)));
          
          

		           $arr=array();
		           $myarr=array();
		           
		           $arr=explode(',',$class);
		           
		           foreach($arr as $key){
		               
		               $myarr[]=array('teacher_id'=>$teacher,'class_id'=>$key,'level_id'=>$level,'branch_id'=>$branch,'created_at'=>$dateTime);
		           }
                   
                    $action=Teacher_class::insert($myarr);
             
                  if( $action ==true){
        
                      
                      $message['error']=0;
                      $message['message']='action success';
                  }else{
                    
                      $message['error']=1;
                      $message['message']='error in action data';
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
    
    //delete teacher class
    //delete student from class
    // 
    
    
    
    
     public function delete_student_class(Request $request)
    {
       try{
      
            $token=$request->input('user_token');
           
            $id=$request->input('class_id');
            $student=$request->input('student_id');
            
          
          $check_token=User::where('user_token',$token)->first();
                  
          if($request->has('user_token') && $check_token !=NULL){

               

          
            $delete=User::where([['id',$student],['class_id',$id]])->delete();
         

          if( $delete ==true){

          
          	

              $message['error']=0;
              $message['message']='delete success';
          }else{
              $message['error']=1;
              $message['message']='error in delete data';
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
    
    
        public function delete_teacher_class(Request $request)
    {
       try{
      
            $token=$request->input('user_token');
           
            $id=$request->input('class_id');
            $student=$request->input('student_id');
            
          
          $check_token=User::where('user_token',$token)->first();
                  
          if($request->has('user_token') && $check_token !=NULL){

               

          
            $delete=User::where([['id',$student],['class_id',$id]])->delete();
         

          if( $delete ==true){

          
          	

              $message['error']=0;
              $message['message']='delete success';
          }else{
              $message['error']=1;
              $message['message']='error in delete data';
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
    
    
    
     public function show_classstudents(Request $request)
    {
       try{
      
            $token=$request->input('user_token');
           
            $id=$request->input('id');
            
          
          $check_token=User::where('user_token',$token)->first();
                  
          if($request->has('user_token') && $check_token !=NULL){

               

          
        $select=User::select('users.id','users.first_name','users.last_name','users.user_name','users.password as passwords','users.code')
                  ->where('class_id',$id)->get();


         

          if( count($select) > 0){

              $message['data']=$select;
              $message['error']=0;
              $message['message']='show success';
          }else{
              $message['data']=$select;
              $message['error']=1;
              $message['message']='error in show data';
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
    
    public function show_classteachers(Request $request)
    {
       try{
      
            $token=$request->input('user_token');
           
            $id=$request->input('id');
            
          
          $check_token=User::where('user_token',$token)->first();
                  
          if($request->has('user_token') && $check_token !=NULL){

               

          
        $select=User::select('users.id','users.first_name','users.last_name','users.user_name','users.password as passwords','users.code')
                  ->join('teacher_class','users.id','=','teacher_class.teacher_id')->where('teacher_class.class_id',$id)->distinct()->get();


         

          if( count($select) > 0){

              $message['data']=$select;
              $message['error']=0;
              $message['message']='show success';
          }else{
              $message['data']=$select;
              $message['error']=1;
              $message['message']='error in show data';
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
     public function delete_student(Request $request)
    {
       try{
      
            $token=$request->input('user_token');
           
            $id=$request->input('id');
            
          
          $check_token=User::where('user_token',$token)->first();
                  
          if($request->has('user_token') && $check_token !=NULL){

               

          
            $delete=User::where('id',$id)->delete();
         

          if( $delete ==true){

          
          	

              $message['error']=0;
              $message['message']='delete success';
          }else{
              $message['error']=1;
              $message['message']='error in delete data';
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
