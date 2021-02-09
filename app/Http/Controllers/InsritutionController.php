<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Institution;
use carbon\carbon;
use Illuminate\Support\Facades\DB;
use App\Message;
use App\Institution_branch;
use App\Classes;
use App\User;

class InsritutionController extends Controller
{
        public $message=array();
        
        
        
        

        public function show_institutions(Request $request)
     {
      try{
            $token=$request->input('user_token');
           $lang=$request->input('lang');
         
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
              
              
           if($lang =='AR' || $lang=='ar'){
          $about=User::select('id','first_name  as name','email','phone','image','password as passwords','class_number','created_at')->where('state',7)->get();
           }else{
               
            $about=User::select('id','E_name as name','email','phone','image','password as passwords','class_number','created_at')->where('state',7)->get();

           }
                  if( count($about)>0){
                        $message['data']= $about;
                         $message['error']=0;
                        $message['message']='show success';
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
     
    public function show_myinstitution(Request $request)
    {
      try{
            $token=$request->input('user_token');
           $lang=$request->input('lang');
         
          
            $check_token=User::select('institution_id')->where('user_token',$token)->first();
          
          
           if($request->has('user_token') && $check_token !=NULL){
              
        
            $about=User::select('id','first_name','image')->where('id',$check_token['institution_id'])->get();
       
                  if( count($about)>0){
                        
                         $message['data']= $about;
                         $message['error']=0;
                        $message['message']='show success';
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
     
     
     
     
     
      public function show_allinstitutions(Request $request)
     {
      try{
           
           $lang=$request->input('lang');
         
          
       
        
                 $about=User::select('id','first_name')->where('state',7)->get();

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
//data  
    public function show_institutionByid(Request $request)
    {
       try{
       
           $id=$request->input('id');
             $token=$request->input('user_token');
           $lang=$request->input('lang');
           
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
              
            $select=User::select('id','first_name as name','E_name','image','password as passwords','phone','email','class_number')->where('id',$id)->first();

         /*     $branch=Institution_branch::select('id','name')
               ->where('institution_id',$id)
               ->get();

            for($i=0;$i<count($branch);$i++) {

               $show=Classes::select('class.id','class.name')
               ->where('class.branch_id',$branch[$i]['id'])
               ->get();

               $branch[$i]['classes']=$show;
      
            }*/
               

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
    
     public function delete_institutions(Request $request)
    {
       try{
       
           $id=$request->input('id');
             $token=$request->input('user_token');
           $lang=$request->input('lang');
            
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
              
            $select=User::where('id',$id)->delete();

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
    
     public function insert_institutions(Request $request)
    {
       try{
       
           $name=$request->input('name');
            $e_name=$request->input('E_name');
             $image=$request->file('image');
               $token=$request->input('user_token');
           $email=$request->input('email');
             $phone=$request->input('phone');
             $pass=$request->input('passwords');
           $class_number=$request->input('class_number');
         
          
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
		              $images =NULL;       
		          }
		          
		           $check=User::where('first_name',$name)->orWhere('E_name',$e_name)->get();
          
          if(count($check)>0){
              
              $message['error']=3;
              $message['message']='this name is exist';
              
          }else{

           
            $select=new User;
            $select->first_name=$name;
            $select->E_name=$e_name;
            $select->image=$images;
            $select->email=$email;
            $select->phone=$phone;
            $select->password=$pass;
            $select->class_number=$class_number;
            $select->state=7;
            $select->created_at=$dateTime;
            $select->save();

          if( $select ==true){
            //  $message['data']=$select;
              $message['error']=0;
              $message['message']='insert success';
          }else{
            //  $message['data']=$select;
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
    
    
     public function update_institution(Request $request)
    {
       try{
           $id=$request->input('id');
          $name=$request->input('name');
            $e_name=$request->input('E_name');
             $image=$request->file('image');
                  $token=$request->input('user_token');
            $pass=$request->input('passwords');
           $email=$request->input('email');
           $phone=$request->input('phone');
           $class_number=$request->input('class_number');
         
          
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
		              $images =User::where('id',$id)->value('image');       
		          }

           
            $update=User::where('id',$id)->update([
                'first_name'=>$name,
                  'E_name'=>$e_name,
                  'password'=>$pass,
                  'email'=>$email,
                  'phone'=>$phone,
                    'image'=>$images,
                    'class_number'=>$class_number,
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
    
    
    
    
    Public function showmessage(Request $request)
    {
      $user=$request->input('user_id');
      

      $all_mess=Message::select('messages.id','messages.message','sender.id as sender_id','sender.first_name as sender_name','sender.image as sender_image','receiver.id as receiver_id','receiver.first_name as receiver_name','receiver.image as receiver_image','messages.image','messages.created_at','messages.updated_at')
            ->join('users as sender','messages.sender_id','=','sender.id')
            ->join('users as receiver','messages.receiver_id','=','receiver.id')
          
          ->where([['sender_id',$user],['receiver_id' , 1]])
            ->orwhere([['sender_id',1],['receiver_id', $user]])->get();


      if(count($all_mess) >0)
      {
        $message['data']=$all_mess;

      $message['error']=0;
      $message['message']='show message body';
      }else{

        $message['data']=$all_mess;

      $message['error']=1;
      $message['message']='no message';
      }

      return response()->json($message);
    }
    
    
       //for admin
     Public function sentmessage(Request $request)
    {  //sende admin
      $receive=$request->input('user_id');
      
      $sender=1;
      $body=$request->input('message');
        $image=$request->file('image');
      

          $created_at = carbon::now()->toDateTimeString();
          $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($created_at)));
          
           if(isset($image)) {
                    $new_name = $image->getClientOriginalName();
                    $savedFileName = rand(100000,999999).time()."_".$new_name; // give a unique name to file to be saved
                    $destinationPath_id = 'uploads/messages';
                    $image->move($destinationPath_id, $savedFileName);
        
                    $images = $savedFileName;
                  
         }else{
              $images = NULL;       
          }



                  
                  

      $mess=new Message;

      $mess->sender_id=$sender;
      $mess->receiver_id=$receive;
      $mess->image=$images;
      
      $mess->message=$body;
   
        $mess->created_at=$dateTime;
      $mess->save();
 

      if($mess == true)
      {
        $all_mess=Message::select('messages.id','messages.message','sender.first_name as sender_name','sender.image as sender_image','receiver.first_name as receiver_name','receiver.image as receiver_image','messages.created_at','messages.updated_at')
            ->join('users as sender','messages.sender_id','=','sender.id')
            ->join('users as receiver','messages.receiver_id','=','receiver.id')
             
            ->where([
        ['messages.sender_id',$sender],
        ['messages.receiver_id',$receive]
          ])->get();

      
      $message['error']=0;
      $message['message']='sent message success';
      }else{

        
      $message['error']=1;
      $message['message']='error in send';
      }

      return response()->json($message);
    }
    
    
    //for user
      Public function sent_message(Request $request)
    {  //sende admin
      $receive=1;
      
      $sender=$request->input('user_id');
      $body=$request->input('message');
        $image=$request->file('image');
      

          $created_at = carbon::now()->toDateTimeString();
          $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($created_at)));
          
           if(isset($image)) {
                    $new_name = $image->getClientOriginalName();
                    $savedFileName = rand(100000,999999).time()."_".$new_name; // give a unique name to file to be saved
                    $destinationPath_id = 'uploads/messages';
                    $image->move($destinationPath_id, $savedFileName);
        
                    $images = $savedFileName;
                  
         }else{
              $images = NULL;       
          }



                  
                  

      $mess=new Message;

      $mess->sender_id=$sender;
      $mess->receiver_id=$receive;
      $mess->image=$images;
      
      $mess->message=$body;
   
        $mess->created_at=$dateTime;
      $mess->save();
 

      if($mess == true)
      {
        $all_mess=Message::select('messages.id','messages.message','sender.first_name as sender_name','sender.image as sender_image','receiver.first_name as receiver_name','receiver.image as receiver_image','messages.created_at','messages.updated_at')
            ->join('users as sender','messages.sender_id','=','sender.id')
            ->join('users as receiver','messages.receiver_id','=','receiver.id')
             
            ->where([
        ['messages.sender_id',$sender],
        ['messages.receiver_id',$receive]
          ])->get();

      
      $message['error']=0;
      $message['message']='sent message success';
      }else{

        
      $message['error']=1;
      $message['message']='error in send';
      }

      return response()->json($message);
    }

   
   
   
   
   
    Public function show_message(Request $request)
    {
           $user=$request->input('user_id');
            $token=$request->input('user_token');
          
         
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){

      $all_mess=Message::select('messages.id','messages.message','sender.id as sender_id','sender.first_name as sender_name','sender.image as sender_image','receiver.id as receiver_id','receiver.first_name as receiver_name','receiver.image as receiver_image','messages.image','messages.created_at','messages.updated_at')
            ->join('users as sender','messages.sender_id','=','sender.id')
            ->join('users as receiver','messages.receiver_id','=','receiver.id')
          
          ->where([['sender_id',$user],['receiver_id' , $check_token['id']]])
            ->orwhere([['sender_id',$check_token['id']],['receiver_id', $user]])->get();


              if(count($all_mess) >0)
              {
                $message['data']=$all_mess;
        
              $message['error']=0;
              $message['message']='show message body';
              }else{
        
                $message['data']=$all_mess;
        
              $message['error']=1;
              $message['message']='no message';
              }
          }else{
              $message['error']=3;
              $message['message']='this token is nt exist'; 
          }
      return response()->json($message);
    }
    
    
    
       //
     Public function sent_usermessage(Request $request)
    {  
    
      $token=$request->input('user_token');
          
         
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
      $receive=$request->input('user_id');
      
      
      $body=$request->input('message');
        $image=$request->file('image');
      

          $created_at = carbon::now()->toDateTimeString();
          $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($created_at)));
          
           if(isset($image)) {
                    $new_name = $image->getClientOriginalName();
                    $savedFileName = rand(100000,999999).time()."_".$new_name; // give a unique name to file to be saved
                    $destinationPath_id = 'uploads/messages';
                    $image->move($destinationPath_id, $savedFileName);
        
                    $images = $savedFileName;
                  
         }else{
              $images = NULL;       
          }



      $text = preg_replace('/\b[\+]?[(]?[0-9]{2,6}[)]?[-\s\.]?[-\s\/\.0-9]{3,15}\b/m', '[number_blocked]', $body);

      $mess=new Message;

      $mess->sender_id=$check_token['id'];
      $mess->receiver_id=$receive;
      $mess->image=$images;
      
      $mess->message=$text;
   
        $mess->created_at=$dateTime;
      $mess->save();
 

              if($mess == true)
              {
             
              
              $message['error']=0;
              $message['message']='sent message success';
              }else{
        
                
              $message['error']=1;
              $message['message']='error in send';
              }
       }else{
              $message['error']=3;
              $message['message']='this token is nt exist'; 
          } 

      return response()->json($message);
    }
    

  
}
