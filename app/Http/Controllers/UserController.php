<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Languages;
use App\Trainer_Language;
use App\Certificate;
use Redirect;

use App\Wallet;
use App\About;
use App\setting;
use carbon\carbon;
use Illuminate\Support\Str;
use App\admin_roles;
use App\Trainer_specialist;

            define( 'API_ACCESS_KEY12', 'AAAAz-GV50A:APA91bFXom0sxT5MNl_IQUbzEEsxJK55cgoI6k4ucPJctyN8wshU_ZOGZnADHyFfKOvcOxLsfuaAKrTJ3-2lGjmzdDAFH2IhiP0dtEtLMUxdyt5ZOPacOq5D9wHk0W0Wgxlncu3iMPl7');

class UserController extends Controller
{
    

    	 public $message=array();


     public function admin_login(Request $request)
     {
     try{
         $email=$request->input('email');
         $pass=$request->input('passwords');
         $firebase_token = $request->input('firebase_token');
         
              $updated_at = carbon::now()->toDateTimeString();
              $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($updated_at)));
         
        
              $userexist=User::select('users.id','users.first_name','users.last_name','users.email','users.password as passwods','users.phone','roles.name as state','users.image','users.user_token','users.created_at','users.updated_at')->join('roles','users.state','=','roles.id')
              ->where([['users.email',$email],['users.password','like' ,'%'.$pass.'%'],['users.state','=',1]])->first();
              
              
              
                try{
                    
		                  
		                    $user_token=hash('sha256', Str::random(60)).time().rand(10000,999999);
		                    //rand(1000000,9999999).time();// give a unique name to file to be saved
		                    
		                    $insert=User::where([['users.email',$email],['users.password','like' ,'%'.$pass.'%'],['users.state','=',1]])->update([
		                        'user_token'=>$user_token,
		                        "firebase_token" => $firebase_token,
		                        'updated_at'=>$dateTime,
		                        
		                    ]);
		                    
		           }catch(Exception $ex){
		                $message['error']=4;
                   $message['message']='error in generate key';    
		          }
              
              
              
               $userexist=User::select('users.id','users.first_name','users.last_name','users.email','users.password as passwods','users.phone','roles.name as state','users.image','users.user_token','users.created_at','users.updated_at')->join('roles','users.state','=','roles.id')
              ->where([['users.email',$email],['users.password','like' ,'%'.$pass.'%'],['users.state','=',1]])->first();
              
              
              
                 
            

              if($userexist !=null){
                  
                  $roles=admin_roles::select('admin_roles.id', 'roles.name','admin_roles.created_at')
                             ->join('roles','admin_roles.role','=','roles.id')->where('admin_roles.admin_id',$userexist['id'])->get();
               
                   $message['data']=$userexist;
                   $message['roles']=$roles;
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

       public function edit_adminprofile(Request $request)
     {
        try{
            
             $token=$request->input('user_token');
          
          $check_token=User::select('id')->where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
		       
		        $fname=$request->input('first_name');
		         $lname=$request->input('last_name');
		        $phone=$request->input('phone');
		         $image=$request->file('image');
		        $email=$request->input('email');
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
		              $images = User::where('id',$check_token['id'])->value('image');       
		          }
		        
		 
		            $userupdate=User::where('id',$check_token['id'])
		              ->update(
		               ['first_name'=>$fname,
		               'last_name'=>$lname,
		                'email'=>$email,
		                 'image'=>$images,
		                'phone'=>$phone,
		                'password'=>$pass,
		                'updated_at'=>$dateTime]);

		              

		        $user_data=User::select('users.id','users.first_name','users.last_name','users.email','users.password as passwods','users.phone','roles.name as state','users.image','users.user_token','users.created_at','users.updated_at')->join('roles','users.state','=','roles.id')
            ->where('users.id','=',$check_token['id'])->first();

		                  if($userupdate==true){
		                        $message['data']=$user_data;
		                         $message['error']=0;
		                        $message['message']='update data successfully';
		                    }else{
		                         $message['data']=$user_data;
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
     
     
 public function verify_code(Request $request)
     {
        try{
            
             
		       
		        $email=$request->input('email');
               // echo $email;
		      // return $email;
		      

		          $updated_at = carbon::now()->toDateTimeString();
		          $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($updated_at)));
		            
		        
		 
		            $userupdate=User::where('email',$email)
		              ->update(
		               [
		                   'verify'=>1,
		                'updated_at'=>$dateTime]);

		              

		      

		                 
       

		            }catch(Exception $ex){
		                 $message['error']=2;
		                 $message['message']='error'.$ex->getMessage();
		            }  

		       return Redirect::to('http://site.rafiiq.net/login');
     }

        public function update_about(Request $request)
     {
      try{
          $token=$request->input('user_token');
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
              
        
          $title=$request->input('title');
          $address=$request->input('address');
          $e_title=$request->input('e_title');
          $e_address=$request->input('e_address');
          $phone=$request->input('phone');
          $email=$request->input('email');
          $image=$request->file('image');
          $details=$request->input('details');
          $e_details=$request->input('e_details');
          $face=$request->input('face_link');
          $linked=$request->input('linked_link');
          $twitter=$request->input('twitter_link');
          $insta=$request->input('insta_link');

          $updated_at = carbon::now()->toDateTimeString();
          $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($updated_at)));
           

           if(isset($image)) {
                    $new_name = $image->getClientOriginalName();
                    $savedFileName = rand(100000,999999).time()."_".$new_name; // give a unique name to file to be saved
                    $destinationPath_id = 'uploads/about';
                    $image->move($destinationPath_id, $savedFileName);
        
                    $images = $savedFileName;
                  
         }else{
              $images =About::where('id',1)->value('image');       
          }

          $aboutus=About::where('id',1)->update([
              'title'=>$title,
           'address'=>$address, 
           'E_title'=>$e_title,
           'E_address'=>$e_address, 
           'phone'=>$phone, 'email'=>$email, 
           'image'=>$images, 'details'=>$details,
           'E_details'=>$e_details,
           'twitter_link'=>$twitter,
           'face_link'=>$face,
           'linked_link'=>$linked,
           'insta_link'=>$insta,
           'updated_at'=>$dateTime]);

         //    $about=About::select('id','title', 'address', 'phone', 'email', 'image', 'details')->first();

                  if($aboutus == true){
                     //   $message['about_data']= $about;
                         $message['error']=0;
                        $message['message']='update about us data';
                    }else{
                       //  $message['about_data']= $about;
                         $message['error']=1;
                         $message['message']='error in update data ';
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

      public function show_about(Request $request)
     {
      try{
           $token=$request->input('user_token');
           $lang=$request->input('lang');
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
              
           
        
                $about=About::select('id', 'title','E_title', 'address','E_address', 'phone', 'email', 'image', 'details','E_details','face_link','insta_link','linked_link','twitter_link')->first();
               

                  if( $about !=null){
                        $message['about_data']= $about;
                         $message['error']=0;
                        $message['message']='show about us data';
                    }else{
                         $message['about_data']= $about;
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
     
     
     
     
      public function show_aboutus(Request $request)
     {
      try{
         
           $lang=$request->input('lang');
       
               if($lang=='ar' || $lang=='AR'){
              
        
                $about=About::select('id','phone', 'email', 'details','face_link as facebook', 'insta_link as instagram', 'linked_link as linkedIn', 'twitter_link as twitter')->first();
               }
               else{
                $about=About::select('id','phone', 'email','E_details as details','face_link as facebook', 'insta_link as instagram', 'linked_link as linkedIn', 'twitter_link as twitter')->first();
  
               }

                  if( $about !=null){
                        $message['data']= $about;
                         $message['error']=0;
                        $message['message']='show about us data';
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

      public function show_adminById(Request $request)
    {
       try{
           $token=$request->input('user_token');
           $lang=$request->input('lang');
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
        
        

         $user_data=User::select('users.id','users.first_name','users.last_name','users.email','users.password as passwords','users.phone','roles.name as state','users.image','users.created_at','users.updated_at')
            ->join('roles','users.state','=','roles.id')
            ->where([['users.user_token','=',$token],['users.state',1]])->first();
          

                  if($user_data !=null){
        
                    $message['data']=$user_data;
                    $message['error']=0;
                    $message['message']='user data';
        
                  }else{
                    $message['data']=$user_data;
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
    
    
    
    
     
        public function forget_password(Request $request){
         try{
             $message = array();
             
             $user_email = $request->input('email');
             
             
             $check=User::where('email',$user_email)->value('email');
             
             
             if($check !=null){
             
             $uniqid = uniqid();

             $rand_start = rand(1,5);
            
             $generate_code = substr($uniqid,$rand_start,6);
             
             $update_code_pass=User::where('email', $user_email)->update(['confirm_password' => $generate_code]);
             
             
             
                $to = $user_email;
                $subject = " Raffiq ";
                
                $msg = '
                <html>
                <head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
                <title>Rafiq Password</title>
                </head>
                <body>
                 <h2>Rafiq Reset Password</h2>
                
                 <p style="display:rlt;">Rafik  App has recieved a request to reset the password for your account. If you did not request to reset your password, please ignore this email. </p>
                 <h4 style="text-align:center;"> Confirmation code : <br>'.$generate_code.'</h4>
                </body>
                </html>
                ';
                
                // Always set content-type when sending HTML email
                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                
                // More headers
                $headers .= 'From: <Rafiq@APP.com>' . "\r\n";  

                mail($to,$subject,$msg,$headers);
              
              $message['data']=$user_email;
              $message['error'] = 0;
              $message['message'] = "the code is send to ur email successfully";
               
             }else{
                 $message['data']=NULL;
                 $message['error']=1;
                 $message['message']=" please enter valid email";   
                 
             }
              
         }catch( Exception $ex)  {
                $message['error']=2;
                $message['message']=" error('DataBase Error :{$ex->getMessage()}')";
            }
                return response()->json($message);
     }
     
     public function Reset_password(Request $request){
         try{
             $message = array();
             
             
             $email = $request->input('email');
             
             $code = $request->input('code');
             
             $password = $request->input('password');
             
             if($request->has('email') && $request->has('code') && $request->has('password')){
             
                 $check_code = User::where([['email', $email],['confirm_password', $code]])->first();
                 
                 if($check_code != NULL) {
                     $update_pass = User::where([['email', $email], ['confirm_password', $code]])->update(['password' =>$password]);
                     
                     $message['error'] = 0;
                     $message['message'] = "the password is updated successfully";
                 }else{
                     $message['error'] = 3;
                     $message['message'] = "there is an error, please try again";
                 }
             }else{
                 $message['error'] = 1;
                 $message['message'] = "please fill all the data"; 
             }
         }catch( Exception $ex)  {
                $message['error']=2;
                $message['message']=" error('DataBase Error :{$ex->getMessage()}')";
            }
                return response()->json($message);
     }
     
     
    
    
    
    
    
    
    
    

     public function user_login(Request $request)
     {
      try{
         $email=$request->input('email');
         $pass=$request->input('passwords');
         $firebase_token = $request->input('firebase_token');
         
         if($request->has('email')&&$request->has('passwords')){
        
           $userexist = User::where([['users.email',$email],[DB::raw('BINARY users.password'),$pass], ['users.state' , '!=' , 5], ['users.state' , '!=' , 6]])
                         ->orWhere([['users.email',$email],['users.code',$pass] , ['users.state' , '!=' , 5], ['users.state' , '!=' , 6]])
                         ->orWhere([['users.user_name',$email],[DB::raw('BINARY users.password'),$pass], ['users.state' , '!=' , 5], ['users.state' , '!=' , 6]])
                         ->orWhere([['users.user_name',$email],['users.code',$pass], ['users.state' , '!=' , 5], ['users.state' , '!=' , 6]])->first();

            $check = \App\User::where([['users.email',$email],[DB::raw('BINARY users.password'),$pass]])
                                 ->orWhere([['users.email',$email],['users.code',$pass]])
                                 ->orWhere([['users.user_name',$email],[DB::raw('BINARY users.password'),$pass]])
                                 ->orWhere([['users.user_name',$email],['users.code',$pass] ])->first();
            
                
                try{
                    
		                  
                    $user_token=hash('sha256', Str::random(60)).time().rand(10000,999999);
                    //rand(1000000,9999999).time();// give a unique name to file to be saved
                    
                    $insert=User::where([['users.email',$email],[DB::raw('BINARY users.password'),$pass]])
                                 ->orWhere([['users.email',$email],['users.code',$pass]])
                                 ->orWhere([['users.user_name',$email],[DB::raw('BINARY users.password'),$pass]])
                                 ->orWhere([['users.user_name',$email],['users.code',$pass]])->update([
                                    'user_token'=>$user_token,
            
                                ]);
		                    
		           }catch(Exception $ex){
		                $message['error']=4;
                        $message['message']='error in generate key';    
		          }
              
              $select=User::select('users.id','users.first_name','users.last_name','users.user_name','users.email','users.password as passwods','users.phone','roles.name as state','users.image','country.a_name as country_name','users.user_token','users.created_at','users.updated_at')
                        ->join('roles','users.state','=','roles.id')
                        ->leftJoin('country','users.country_id','=','country.id')
                        ->where([['users.id',$userexist['id']]])->first();

              if($userexist !=null){
                  
                  $check_verify=$userexist['verify'];
                  
                  
                  if($check_verify==0){
                       $message['error']=3;
                       $message['message']='please verify your email first';
                      
                  }else{
                    
                   $update_firebase = \App\User::where('id' , $userexist['id'])->update(['firebase_token' => $firebase_token]);
            
                   $message['data']=$select;
                   $message['error']=0;
                   $message['message']='login success';
                  }

             }else{
                     if($check != ""|| $check !=NULL ){
                        if($check->state == 5 ){
                                 $message['data'] = $select;
                                 $message['error'] = 8;
                                 $message['message'] = "you are still pending ";
                                 return response()->json($message);
        
                             }
                        }
                    $message['data']=$select;
                    $message['error']=1;
                    $message['message']='data is wrong';
                 
             }
         }else{
             $message['data']=null;
             $message['error']=6;
             $message['message']='enter data';
         }
            
            }catch(Exception $ex){
               $message['error']=2;
               $message['message']='error'.$ex->getMessage();
            }
      
            return response()->json($message);
     }

      public function regesteration(Request $request)
     {
       try{
          $fname=$request->input('first_name');
          $lname=$request->input('last_name');
           $username=$request->input('user_name');
          $email=$request->input('email');
          $phone=$request->input('phone');
          $gender=$request->input('gender');
          $image=$request->file('image');
          $pass=$request->input('passwords');
          $state=$request->input('state_id'); // 2 s/3 w/4 t
          
        
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
        
             $emailexist=User::where('email',$email)->value('email');
           
         $usernameexist=User::where('user_name',$username)->value('user_name');

           
           if($emailexist !=null ){
                $message['error']=2;
                $message['message']='email  exist';
           
          }elseif($usernameexist !=null){
            $message['error']=5;
                          $message['message']='user_name  exist';
           }else{

              try{
                       $uniqid = uniqid();

                       $rand_start = rand(1,5);
                    
                     $generate_code = substr($uniqid,$rand_start,6);

                        $code=  rand(1000,9999);
                        //rand(1000000,9999999).time();// give a unique name to file to be saved
                      
               }catch(Exception $ex){
                    $message['error']=6;
                   $message['message']='error in generate key';    
              }

       
          
                 
    
                $user=User::insertGetId(['first_name'=>$fname,'last_name'=>$lname,'user_name'=>$username,'code'=>$code,'email'=>$email,'phone'=>$phone,'password'=>$pass,'state'=>$state,'image'=>$images,'verify'=>0,'created_at'=>$dateTime]);

             

              
                       $select=User::select('users.id','users.first_name','users.last_name','users.email','users.password as passwods','users.phone','roles.name as state','users.image','users.code','users.created_at','users.updated_at')
                        ->join('roles','users.state','=','roles.id')
                         ->leftJoin('country','users.country_id','=','country.id')
                        ->where('users.id',$user)->first();
                 
                 
               
      
                if($user >0){
                    
                            $makeWallet=new Wallet;
                            $makeWallet->user_id=$user;
                            $makeWallet->credit=0;
                            $makeWallet->created_at=$dateTime;
                            $makeWallet->save();
                    
                    
                          $set=new setting;
                          $set->user_id=$user;
                          $set->notification='ON';
                          $set->credit=0;
                          $set->commission = '0%';
                          $set->created_at=$dateTime;
                          $set->save();
                          
                          
                                    $to = $email;
                                    $subject = " RafiQ email ";
                                    
      
                                    
                            
                                    $msg = '
                                    <html>
                                    <head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
                                    <title>Rafik verification code</title>
                                    
                                   
                                    </head>
                                    <body>
                                     <h2>Rafik verification code</h2>
                                    
                                     <p style="display:rlt;">Rafik sent a  verification email for your account.</p>
                                     <h4 style="text-align:center;"> We just need you to verify your email address by clicking the button below</h4>
                                     
                                   <form action="https://rafikapi.codecaique.com/api/verify_code" method="post" style="margin:0% 50px 0%px 50px;">
                                     <input type="hidden" value="'.$to.'" name="email" />
                                     <button type="submit"  value="verify" class="text-cenetr" style="width:100px;height:50px;color:white;background-color:#46a049;cursor:pointer;">
                                     
                                     
                                     verfiy now
                                     </button>
                                     </form>
                                     
                                     

                                    </body>
                                    </html>
                                    ';
                                    
                                    // Always set content-type when sending HTML email
                                    $headers = "MIME-Version: 1.0" . "\r\n";
                                    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                                    
                                    // More headers
                                    $headers .= 'From: <Rafiq@APP.com>' . "\r\n";  
                    
                                    mail($to,$subject,$msg,$headers);
                    
                  
                      $message['data']=$select;
                      $message['error']=0;
                       $message['message']='regesteration success';
                }else{
                      $message['data']=$select;
                      $message['error']=1;
                       $message['message']='error in save';
                }
              

            }
            }catch(Exception $ex){
                   $message['error']=4;
                   $message['message']='error'.$ex->getMessage();
            }
            return response()->json($message);
     }
     
     
       public function train_regesteration(Request $request)
     {
       try{
          $fname=$request->input('first_name');
          $lname=$request->input('last_name');
          $username=$request->input('user_name');
          $email=$request->input('email');
          $phone=$request->input('phone');
          $country=$request->input('country');
          $image=$request->file('image');
          $pass=$request->input('passwords');
          $state=5;
          
        
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
          $emailexist=User::where('email',$email)->value('email');
           
         $usernameexist=User::where('user_name',$username)->value('user_name');
         $phone_check =User::where('phone',$phone)->value('phone');

           
           if($emailexist !=null ){
                $message['error']=2;
                $message['message']='email  exist';
           
          }elseif($phone_check != NULL){
              $message['error'] = 6;
              $message['message'] = "phone is already exists";
              
          }elseif($usernameexist !=null){
            $message['error']=5;
            $message['message']='user_name  exist';
           }else{

              try{
                       $uniqid = uniqid();

                       $rand_start = rand(1,6);
                    
                      $generate_code = substr($uniqid,$rand_start,6);

                        $code=  rand(1000,99999);
                        //rand(1000000,9999999).time();// give a unique name to file to be saved
                      
               }catch(Exception $ex){
                    $message['error']=6;
                   $message['message']='error in generate key';    
              }

       
          
                 
    
                $user=User::insertGetId(['first_name'=>$fname,'last_name'=>$lname,'user_name'=>$username,'code'=>$code,'email'=>$email,'phone'=>$phone,'verify'=>0,'password'=>$pass,'state'=>$state,'image'=>$images,'country_id'=>$country,'created_at'=>$dateTime]);

             

              
                       $select=User::select('users.id','users.first_name','users.last_name','users.email','users.password as passwods','users.phone','roles.name as state','users.image','users.code','users.created_at','users.updated_at')
                        ->join('roles','users.state','=','roles.id')
                         ->leftJoin('country','users.country_id','=','country.id')
                        ->where('users.id',$user)->first();
                 
               
      
                if($user >0){
                    
                            $makeWallet=new Wallet;
                            $makeWallet->user_id=$user;
                            $makeWallet->credit=0;
                            $makeWallet->created_at=$dateTime;
                            $makeWallet->save();
                    
                    
                          $set=new setting;
                          $set->user_id=$user;
                          $set->notification='ON';
                          $set->credit=0;
                          $set->commission = '0%';
                          $set->created_at=$dateTime;
                          $set->save();
                          
                          
                                      $to = $email;
                                    $subject = " RafiQ email ";
                                    
                                    $msg = '
                                    <html>
                                    <head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
                                    <title>Rafik verification code</title>
                                     </head>
                                    <body>
                                     <h2>Rafik verification code</h2>
                                    
                                     <p style="display:rlt;">Rafik sent a  verification email for your account.</p>
                                     <h4 style="text-align:center;"> We just need you to verify your email address by clicking the button below</h4>
                                     
                                   <form action="http://rafikapi.codecaique.com/api/verify_code" method="post" style="margin:0% 50px 0%px 50px;">
                                                                             

                                     <input type="hidden" value="'.$to.'" name="email" />
                                     <button type="submit"  value="verify" class="text-cenetr" style="width:100px;height:50px;color:white;background-color:#46a049;cursor:pointer;">
                                     
                                     
                                     verfiy now
                                     </button>
                                     </form>
                                    </body>
                                    </html>
                                    ';
                                    
                                    // Always set content-type when sending HTML email
                                    $headers = "MIME-Version: 1.0" . "\r\n";
                                    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                                    
                                    // More headers
                                    $headers .= 'From: <Rafiq@RafiqAPP.com>' . "\r\n";  
                    
                                    mail($to,$subject,$msg,$headers);
                  
                      $message['data']=$select;
                      $message['error']=0;
                       $message['message']='regesteration success';
                }else{
                      $message['data']=$select;
                      $message['error']=1;
                       $message['message']='error in save';
                }
              

            }
            }catch(Exception $ex){
                   $message['error']=4;
                   $message['message']='error'.$ex->getMessage();
            }
            return response()->json($message);
     }
     
     


   public function show_pendingtrainer(Request $request)
    {
       try{
           $token=$request->input('user_token');
           $lang=$request->input('lang');
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
       
          $userexist=User::select('users.id','users.first_name','users.last_name','users.email','users.password as passwods','users.phone','roles.name as state','users.image','users.created_at','users.updated_at')
                            ->join('roles','users.state','=','roles.id')
                            ->where('users.state',5)->get();

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
    
       public function show_blockedtrainer(Request $request)
    {
       try{
             $token=$request->input('user_token');
           $lang=$request->input('lang');
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
           
       
          $userexist=User::select('users.id','users.first_name','users.last_name','users.email','users.password as passwods','users.phone','roles.name as state','users.image','users.cv','users.surname','users.appreviation','users.created_at','users.updated_at')
            ->join('roles','users.state','=','roles.id')
                      
            ->where('users.state',6)->get();

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
    
    
      public function show_blockedstudent(Request $request)
    {
       try{
             $token=$request->input('user_token');
           $lang=$request->input('lang');
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
           
       
          $userexist=User::select('users.id','users.first_name','users.last_name','users.user_name','users.email','users.password as passwods','users.phone','roles.name as state','users.image','users.code','users.created_at','users.updated_at')
            ->join('roles','users.state','=','roles.id')
                      
            ->where('users.state',9)->get();

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
     public function show_blockedwlyamer(Request $request)
    {
       try{
             $token=$request->input('user_token');
           $lang=$request->input('lang');
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
           
       
          $userexist=User::select('users.id','users.first_name','users.last_name','users.user_name','users.email','users.password as passwods','users.phone','roles.name as state','users.image','users.job','users.nationality','users.created_at','users.updated_at')
            ->join('roles','users.state','=','roles.id')
                      
            ->where('users.state',8)->get();

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
    public function show_trainer(Request $request)
    {
       try{
           
           $token=$request->input('user_token');
           $lang=$request->input('lang');
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
       
          $userexist=User::select('users.id','users.first_name','users.last_name','users.email','users.password as passwods','users.phone','roles.name as state','users.image','users.cv','users.surname','users.appreviation','users.created_at','users.updated_at')
            ->join('roles','users.state','=','roles.id')
                      
            ->where('users.state',4)->get();

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
    
       public function show_allusers(Request $request)
    {
       try{
           
           $token=$request->input('user_token');
     
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
              
              
              if($check_token['state']==4 ){
                  
                  
                    $userexist=DB::select('SELECT DISTINCT users.id,users.first_name as first_name,users.last_name,users.email,users.phone,users.image
                    from reservations
                   join timeline ON reservations.timeline_id =timeline.id
                   join users ON reservations.user_id=users.id
                   where timeline.trainer_id='.$check_token['id']);
                      
            
              }else{
                  
                   $userexist=DB::select(' SELECT DISTINCT users.id,users.first_name as first_name,users.last_name,users.email,users.phone,users.image
                             from reservations
                   join timeline ON reservations.timeline_id =timeline.id
                   join users ON timeline.trainer_id=users.id
                   where reservations.user_id='.$check_token['id']);
              }
       
        

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
    
    public function show_students(Request $request)
    {
       try{
             $token=$request->input('user_token');
           $lang=$request->input('lang');
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
       
          $userexist=User::select('users.id','users.first_name','users.last_name','users.email','users.password as passwods','users.phone','roles.name as state','users.image','users.created_at','users.updated_at')
            ->join('roles','users.state','=','roles.id')
                      
            ->where('users.state',2)->get();

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
    public function show_wlyAlamer(Request $request)
    {
       try{
            $token=$request->input('user_token');
           $lang=$request->input('lang');
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
       
          $userexist=User::select('users.id','users.first_name','users.last_name','users.email','users.password as passwods','users.phone','roles.name as state','users.image','users.created_at','users.updated_at')
            ->join('roles','users.state','=','roles.id')
                      
            ->where('users.state',3)->get();

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
//////////
    



public function convert_to_trainer(Request $request)
     {
      try{
          $token=$request->input('user_token');
           $lang=$request->input('lang');
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){

          $id=$request->input('user_id');
        

          $updated_at = carbon::now()->toDateTimeString();
          $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($updated_at)));


           
                        $action=User::where('id',$id)->update(['state'=>4,'updated_at'=>$dateTime]);
                       
                   
        


              if($action ==true){
                  
                   $message['error']=0;
                   $message['message']='convert to  trainer success';

             }else{
                    $message['error']=1;
                     $message['message']='error in convert this user to trainer';
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
   

    
     public function block_user(Request $request)
     {
      try{
          $token=$request->input('user_token');
           $lang=$request->input('lang');
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){

          $id=$request->input('user_id');
        

          $updated_at = carbon::now()->toDateTimeString();
          $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($updated_at)));

           $check=User::where('id',$id)->value('state');
           
           if($check_token['state']==1){
           
                   if($check ==2){
                    $userexist=User::where('id',$id)->update(['state'=>9,'updated_at'=>$dateTime]);
                   }elseif($check ==3){
                       
                        $userexist=User::where('id',$id)->update(['state'=>8,'updated_at'=>$dateTime]);
                   }else{
                        $userexist=User::where('id',$id)->update(['state'=>6,'updated_at'=>$dateTime]);
                       
                   }
           }else{
               
                if($check ==2){
                    $userexist=User::where('id',$id)->update(['state'=>10,'updated_at'=>$dateTime]);
                   }elseif($check ==3){
                       
                        $userexist=User::where('id',$id)->update(['state'=>11,'updated_at'=>$dateTime]);
                   }else{
                        $userexist=User::where('id',$id)->update(['state'=>12,'updated_at'=>$dateTime]);
                       
                   }
               
               
           }
             $user_data=User::select('users.id','users.first_name','users.last_name','users.phone','users.email','users.image','roles.name as state','users.password as passwords','users.created_at','users.updated_at')
                    ->join('roles','users.state','=','roles.id')
                    ->where('users.id',$id)->first();


              if($userexist ==true){
                  
                   $message['data']=$user_data;
                   $message['error']=0;
                   $message['message']='block trainer success';

             }else{
                    $message['data']=$user_data;
                    $message['error']=1;
                     $message['message']='error in block this trainer';
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
   
      public function unblock_user(Request $request)
     {
      try{
          
          $token=$request->input('user_token');
            $id=$request->input('user_id');
           $lang=$request->input('lang');
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){

        
        

          $updated_at = carbon::now()->toDateTimeString();
          $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($updated_at)));

                $check=User::where('id',$id)->value('state');
                
                
           
           if($check ==9 || $check==10){
            $userexist=User::where('id',$id)->update(['state'=>2,'updated_at'=>$dateTime]);
           }elseif($check ==8 || $check==11){
               
                $userexist=User::where('id',$id)->update(['state'=>3,'updated_at'=>$dateTime]);
           }elseif($check ==5 || $check==6 ||$check==12){
                $userexist=User::where('id',$id)->update(['state'=>4,'updated_at'=>$dateTime]);
               
           }
  

     

             $user_data=User::select('users.id','users.first_name','users.last_name','users.phone','users.email','users.image','roles.name as state','users.password as passwords','users.created_at','users.updated_at')
                    ->join('roles','users.state','=','roles.id')
                    ->where('users.id',$id)->first();
                 


              if($userexist ==true){
                  
                   $message['data']=$user_data;
                   $message['error']=0;
                   $message['message']='edit trainer state';

             }else{
                    $message['data']=$user_data;
                    $message['error']=1;
                     $message['message']='error in edit user state';
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

       public function show_user(Request $request)
    {
       try{
        
          $token=$request->input('user_token');
           $lang=$request->input('lang');
           $specialistis=array();
           $language=array();
           $cert=array();
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){

          
         // $state=User::where('user_token',$token)->first();


            if($check_token['state'] ==4)
                {
                    
                  if($lang=='ar' || $lang=='AR'){
                  $select=User::select('users.id','users.first_name','users.last_name','users.user_name','users.nationality','users.phone','users.email','users.image','roles.name as state','users.password as passwords','users.job','users.gender','users.country_id','country.a_name as country_name','users.code','users.profile','users.birth_date')
                      ->leftJoin('appreviation','users.appreviation','=','appreviation.id')
                      ->join('roles','users.state','=','roles.id')
                      ->leftJoin('country','users.country_id','=','country.id')
                                    
                      ->where('users.id',$check_token['id'])->first();
                      
                
                
                         $specialistis=Trainer_specialist::select('trainer_specialties.id','specialties.name','trainer_specialties.created_at','trainer_specialties.updated_at')
                            ->join('specialties','trainer_specialties.specialties_id','=','specialties.id')
                            ->where('trainer_specialties.trainer_id',$check_token['id'])->get();
                            
                            $language=Trainer_Language::select('trainer_language.id','languages.name','trainer_language.created_at','trainer_language.updated_at')
                             ->join('languages','trainer_language.language_id','=','languages.id')
                            ->where('trainer_language.trainer_id',$check_token['id'])->get();
                            
                              $cert=Certificate::select('name')->where('trainer_id',$check_token['id'])->get();
        
                                
                     }else{
                      $select=User::select('users.id','users.first_name','users.last_name','users.user_name','users.nationality','users.phone','users.email','users.image','roles.name as state','users.password as passwords','users.job','users.gender','users.country_id','country.E_name as country_name','users.code','users.profile','users.birth_date')
                      ->leftJoin('appreviation','users.appreviation','=','appreviation.id')
                      ->join('roles','users.state','=','roles.id')
                      ->leftJoin('country','users.country_id','=','country.id')
                                    
                      ->where('users.id',$check_token['id'])->first();
                        
                
                              $specialistis=Trainer_specialist::select('trainer_specialties.id','specialties.E_name as name','trainer_specialties.created_at','trainer_specialties.updated_at')
                            ->join('specialties','trainer_specialties.specialties_id','=','specialties.id')
                            ->where('trainer_specialties.trainer_id',$check_token['id'])->get();
                            
                            $language=Trainer_Language::select('trainer_language.id','languages.E_name as name','trainer_language.created_at','trainer_language.updated_at')
                             ->join('languages','trainer_language.language_id','=','languages.id')
                            ->where('trainer_language.trainer_id',$check_token['id'])->get();
                            
                            
                              $cert=Certificate::select('name')->where('trainer_id',$check_token['id'])->get();
        
                            }
                 
                  
                 }elseif($check_token['state'] ==2){
                     
                         if($lang=='ar' || $lang=='AR'){
                               $select=User::select('users.id','users.first_name','users.last_name','users.user_name','users.nationality','users.phone','users.email','users.image','roles.name as state','users.password as passwords','users.country_id','country.a_name as country_name','users.gender','users.code','users.birth_date')
                        ->join('roles','users.state','=','roles.id')
                         ->leftJoin('country','users.country_id','=','country.id')
                        ->where('users.id',$check_token['id'])->first();
                         }else{
                              $select=User::select('users.id','users.first_name','users.last_name','users.user_name','users.nationality','users.phone','users.email','users.image','roles.name as state','users.password as passwords','users.country_id','country.E_name as country_name','users.gender','users.code','users.birth_date')
                        ->join('roles','users.state','=','roles.id')
                         ->leftJoin('country','users.country_id','=','country.id')
                        ->where('users.id',$check_token['id'])->first(); 
                         }
                 
                 }elseif($check_token['state'] ==3 ){
                     
                         if($lang=='ar' || $lang=='AR'){
                               $select=User::select('users.id','users.first_name','users.last_name','users.user_name','users.nationality','users.phone','users.email','users.image','roles.name as state','users.password as passwords','users.country_id','users.gender','country.a_name as country_name','users.job','users.code','birth_date')
                        ->join('roles','users.state','=','roles.id')
                         ->leftJoin('country','users.country_id','=','country.id')
                        ->where('users.id',$check_token['id'])->first();
                         }else{
                              $select=User::select('users.id','users.first_name','users.last_name','users.user_name','users.phone','users.nationality','users.phone','users.email','users.image','roles.name as state','users.password as passwords','users.country_id','users.gender','country.E_name as country_name','users.job','users.code','birth_date')
                        ->join('roles','users.state','=','roles.id')
                         ->leftJoin('country','users.country_id','=','country.id')
                        ->where('users.id',$check_token['id'])->first(); 
                         }
                 
                 }

          if($select !=null){
              
                  if($check_token['state']==4){
                   $message['data']=$select;
                   $message['languages']=$language;
                   $message['certifications']=$cert;
                   $message['specialist']=$specialistis;
                  
                  }else{
                      $message['data']=$select; 
                  }
            $message['error']=0;
            $message['message']='user data';

          }else{
            $message['data']=$select;
            $message['error']=1;
            $message['message']='no data for user ';
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
    
    
    
    
    
    
        public function edit_userProfile(Request $request)
     {
       try{
                  $token=$request->input('user_token');
          
               
                   $fname=$request->input('first_name');
                   $lname=$request->input('last_name');
                   $username=$request->input('user_name');
                   $email=$request->input('email');
                   $phone=$request->input('phone'); 
                   $image=$request->file('image');
                   $gender=$request->input('gender');///
                   $birth_date=$request->input('birth_date');///
                   $country=$request->input('country_id');
                   $jobs=$request->input('job'); ///
                   $pass=$request->input('passwords');
                   $nationality=$request->input('nationality');
                   $profile=$request->input('profile');

          
          if( $request->has('job')){
              $job = $jobs;
          }else{
              $job = NULL;
          }
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
     
          
          
          $updated_at = carbon::now()->toDateTimeString();
          $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($updated_at)));


          

           if(isset($image)) {
                        $new_name = $image->getClientOriginalName();
                        $savedFileName = rand(100000,999999).time()."_".$new_name; // give a unique name to file to be saved
                        $destinationPath_id = 'uploads/users';
                        $image->move($destinationPath_id, $savedFileName);
            
                        $images = $savedFileName;
                      
               }else{
                  $images =User::where('id',$check_token['id'])->value('image');      
                 }
     

                 if(empty($country)){
                  $country=User::where('user_token',$token)->value('country_id');

                 }

                   if(empty($pass)){
                  $pass=User::where('user_token',$token)->value('password');

                 }

              

             $userexist=User::select('id','email')->where([['user_token','!=',$token],['email',$email]])->get();
             $phoneexist=User::select('id','phone')->where([['user_token','!=',$token],['phone',$phone]])->get();
              $nameexist=User::select('id','phone')->where([['user_token','!=',$token],['user_name',$username]])->get();
           if(count($userexist) >0){
                  $message['error']=5;
                   $message['message']='email  exist';
           }elseif(count($phoneexist) >0){
                  $message['error']=4;
                   $message['message']='phone  exist';
           }elseif(count($nameexist) >0){
                  $message['error']=6;
                   $message['message']='user_name  exist';
           }else{
        

          $update=User::where('id',$check_token['id'])->update([
              'first_name'=>$fname,
              'last_name'=>$lname, 
              'user_name'=>$username, 
              'email'=>$email,
              'phone'=>$phone,
              'password'=>$pass,
              'gender'=>$gender,
              'job'=>$job,
              'birth_date'=>$birth_date,
              'country_id'=>$country,              
              'image'=>$images,
              'nationality'=>$nationality,
              'profile'=>$profile,
              'updated_at'=>$dateTime]);

               
                  
                       $select=User::select('users.id','users.first_name','users.last_name','users.user_name','users.phone','users.email','users.image','roles.name as state','users.password as passwords','users.gender','users.job','country.a_name as country_name','users.birth_date','users.nationality')
                       
                        ->join('roles','users.state','=','roles.id')
                          ->leftJoin('country','users.country_id','=','country.id')
                        ->where('users.id',$check_token['id'])->first();
                     
                if($update == true){
                  
                      $message['data']=$select;
                      $message['error']=0;
                       $message['message']='update success';
                }else{
                      $message['data']=$select;
                      $message['error']=1;
                       $message['message']='error in save';
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
     
        public function change_password(Request $request)
     {
       try{
                  $token=$request->input('user_token');
                  
                    $pass=$request->input('passwords');  
                    
              //     $cpass=$request->input('current_password');
               
                 
            
          
          $check_token=User::select('password')->where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
     
          
          
          $updated_at = carbon::now()->toDateTimeString();
          $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($updated_at)));

               $update=User::where('user_token',$token)->update([
             
              'password'=>$pass,
              'updated_at'=>$dateTime]);
              
              
              if($update == true){
                  
                    //  $message['data']=$select;
                      $message['error']=0;
                       $message['message']='update success';
                }else{
                     // $message['data']=$select;
                      $message['error']=1;
                       $message['message']='error in save';
                }
              
         
        

         

          /*     $state=User::where('user_token',$token)->value('state');
                      if($state==4)
                    {
                       $select=User::select('users.id','users.first_name','users.last_name','users.phone','users.email','users.image','roles.name as state','users.password as passwords','users.gender','users.cv','users.surname as job','appreviation.name as appriviation','country.a_name as country_name','institution.name as institution_name','users.social_status')
                        ->join('appreviation','users.appreviation','=','appreviation.id')
                        ->join('roles','users.state','=','roles.id')
                         ->leftJoin('institution','users.institution_id','=','institution.id')
                          ->leftJoin('country','users.country_id','=','country.id')
                        ->where('users.user_token',$token)->first();
                     }elseif($state==2){
                       $select=User::select('users.id','users.first_name','users.last_name','users.phone','users.email','users.image','roles.name as state','users.password as passwords','users.gender','country.a_name as country_name','users.brother_num', 'users.arrange_brother','users.code')
                    ->join('roles','users.state','=','roles.id')
                    ->leftJoin('country','users.country_id','=','country.id')
                    ->where('users.user_token',$token)->first();
                     }elseif($state==3){
                       $select=User::select('users.id','users.first_name','users.last_name','users.phone','users.email','users.image','roles.name as state','users.password as passwords','users.gender','country.a_name as country_name', 'users.job','users.social_status','users.code')
                    ->join('roles','users.state','=','roles.id')
                    ->leftJoin('country','users.country_id','=','country.id')
                    ->where('users.user_token',$token)->first();
                     }*/
                

                

            
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



    public function sent_user_notification(Request $request){
        
        
        $token=$request->input('user_token');
       $updated_at = carbon::now()->toDateTimeString();
          $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($updated_at)));

          
        $check_token=User::where('user_token',$token)->first();
      
        if($request->has('user_token') && $check_token !=NULL){
            
            $get_firebase_token = \App\User::select('id','firebase_token')->where('id' , $check_token->id)->first();
            
                  
                         
                        #prep the bundle
                             
            
            		
        	if( $get_firebase_token != NULL){
        	    
        	    	$msg = array(
                		'body' 	=> "Weeeew",
                		'title'	=>  "Welcome in Rafiiq Website ",
                          );
                	$fields = array
                			(
                				'to'		=> $get_firebase_token->firebase_token,
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
                $save->title   = "Weeeew";
                $save->message = "Welcome in Rafiiq Website ";
                $save->user_id = $get_firebase_token->id;
                $save->created_at = $dateTime;
                $save->save();
                
        	    $message['error'] = 0;
        	    $message['message'] = "notification is send successfully";
        	}else{
        	    $message['error'] = 1;
        	    $message['message'] = "there is no firebase token, please try again";
        	}	
            		  
        }else{
          $message['error']=3;
          $message['message']='this token is not exist';
       }
            return response()->json($message);
    }
    
    
    public function user_logout(Request $request){
        $token=$request->input('user_token');
      
        $check_token=User::where('user_token',$token)->first();
      
        if($request->has('user_token') && $check_token !=NULL){
            
            $deleteToken = \App\User::where('user_token' , $token)->update(['firebase_token' => NULL , 'user_token' => NULL]); 
           
            $userToken = \App\User::where('id' , $check_token->id)->value('user_token'); 

            if($deleteToken == true){
                $message['data'] = $userToken;
                $message['error'] = 0;
                $message['message'] = "logout successfuly";
            }else{
                $message['data'] = NULL;
                $message['error'] = 1;
                $message['message'] = "error, please try again";
            }
         
         
        }else{
          $message['error']=3;
          $message['message']='this token is not exist';
        }
            return response()->json($message);
    }
    
    
    public function show_userNotification(Request $request){
        
        $token=$request->input('user_token');
        $lang = $request->input('lang');
        
        $check_token = User::where('user_token',$token)->first();

        if($request->has('user_token') && $check_token !=NULL){
            
            if($lang == 'en' || $lang == 'En'){
                $get_data = \App\Notification::select('id', 'user_id','title', 'message', 'image','sender_id','state','click_action','created_at','updated_at')
                                            ->where('user_id' , $check_token->id )->orderby('created_at' , 'DESC')->get();
            }else{
                $get_data = \App\Notification::select('id', 'user_id','title_ar as title', 'message_ar as message', 'image','sender_id','state','click_action','created_at','updated_at')
                                            ->where('user_id' , $check_token->id )->orderby('created_at' , 'DESC')->get();
            }
            
            if( count($get_data)>0){
                $message['data'] = $get_data;
                $message['count'] = count($get_data);
                $message['error'] = 0;
                $message['message'] = "all notification";
            }else{
                $message['data'] = $get_data;
                $message['count'] = count($get_data);
                $message['error'] = 1;
                $message['message'] = "No user notification ";
            }
            
            
        }else{
          $message['error']=3;
          $message['message']='this token is not exist';
        }
        return response()->json($message);
            
    } 
    
    public function show_all_users(Request  $request){
        $token=$request->input('user_token');
      
        $check_token = User::where('user_token',$token)->first();

        if($request->has('user_token') && $check_token !=NULL){
            
            $get_data = \App\User::select('id', DB::raw("CONCAT(users.first_name,' ',users.last_name) as full_name"))
                                ->where('state' , 2)
                                ->orwhere('state' , 3)
                                ->orwhere('state' , 4)->get();
                                
            if(count($get_data) >0 ){
                $message['data'] = $get_data;
                $message['error'] = 0;
                $message['message'] = "all users of rafiiq";
            }else{
                $message['data'] = $get_data;
                $message['error'] = 1;
                $message['message'] = "No users data";
            }
        }else{
          $message['error']=3;
          $message['message']='this token is not exist';
        }
        return response()->json($message);
    }
    
}
