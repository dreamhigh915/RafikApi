<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Specialisties;
use App\User;
use carbon\carbon;

class SpecialistController extends Controller
{
    
          	 public $message=array();

     public function show_specialists(Request $request)
     {
      try{
          
             $token=$request->input('user_token');
           $lang=$request->input('lang');
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
              
              if($lang=='ar' ||$lang=='AR'){
                    $show=Specialisties::select('id','name','image','created_at','updated_at')->get();
              }else{
                     $show=Specialisties::select('id','E_name as name','image','created_at','updated_at')->get();
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
       public function show_allspecialists(Request $request)
     {
      try{
          
           
           $lang=$request->input('lang');
          
        
              
              if($lang=='ar' ||$lang=='AR'){
                    $show=Specialisties::select('id','name','image','created_at','updated_at')->get();
              }else{
                     $show=Specialisties::select('id','E_name as name','image','created_at','updated_at')->get();
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
            
          
        
            }catch(Exception $ex){
               $message['error']=2;
               $message['message']='error'.$ex->getMessage();
            }
      
            return response()->json($message);
     }
     
     
       public function show_doctorspecialists(Request $request)
     {
      try{
          
           
           $lang=$request->input('lang');
          
        
              
              if($lang=='ar' ||$lang=='AR'){
                    $show=DB::select('SELECT specialties.id,specialties.name,(SELECT COUNT(trainer_specialties.trainer_id) FROM trainer_specialties WHERE trainer_specialties.specialties_id=specialties.id )as count FROM specialties LIMIT 6');
              }else{
                     $show=DB::select('SELECT specialties.id,specialties.E_name as name,(SELECT COUNT(trainer_specialties.trainer_id) FROM trainer_specialties WHERE trainer_specialties.specialties_id=specialties.id )as count FROM specialties LIMIT 6');
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
            
          
        
            }catch(Exception $ex){
               $message['error']=2;
               $message['message']='error'.$ex->getMessage();
            }
      
            return response()->json($message);
     }
     
         public function show_doctorinspecialists(Request $request)
     {
      try{
          
           
           $id=$request->input('specialist_id');
          
        
              
            $show=DB::select('select DISTINCT users.id,users.first_name,users.last_name,users.image,users.surname,appreviation.name as appreviation,users.rate,(SELECT MIN(service.price)from service WHERE service.trainer_id=users.id) as price from users
            
        LEFT JOIN appreviation ON users.appreviation =appreviation.id
        JOIN trainer_specialties ON trainer_specialties.trainer_id=users.id
         where users.state=4 AND trainer_specialties.specialties_id='.$id);
        
          
              if(count($show)>0){
               
                   $message['data']=$show;
                   $message['error']=0;
                   $message['message']='show success';

             }else{
                    $message['data']=$show;
                    $message['error']=1;
                     $message['message']='no data';
             }
            
          
        
            }catch(Exception $ex){
               $message['error']=2;
               $message['message']='error'.$ex->getMessage();
            }
      
            return response()->json($message);
     }

    public function show_specialistsByid(Request $request)
     {
      try{
                  $token=$request->input('user_token');
           $lang=$request->input('lang');
             $id=$request->input('id');
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
              
             
                    $show=Specialisties::select('id','name','E_name','image','created_at','updated_at')->where('id',$id)->first();
             

       

              if($show != null){
               
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
     
     public function delete_specialist(Request $request)
     {
      try{
             $token=$request->input('user_token');
           $lang=$request->input('lang');
             $id=$request->input('id');
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
          

           $show=Specialisties::where('id',$id)->delete();

              if($show ==true){
               
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

       public function insert_specialists(Request $request)
     {
      try{
          $token=$request->input('user_token');

          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
           $name=$request->input('name');
           $e_name=$request->input('E_name');
          
            $image=$request->file('image');

            $updated_at = carbon::now()->toDateTimeString();
		       $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($updated_at)));


              if(isset($image)) {
                        $new_name = $image->getClientOriginalName();
                        $savedFileName = rand(100000,999999).time()."_".$new_name; // give a unique name to file to be saved
                        $destinationPath_id = 'uploads/specialist';
                        $image->move($destinationPath_id, $savedFileName);
            
                        $images = $savedFileName;
                      
               }else{
                  $images =NULL;       
              }

           $insert=new Specialisties;
           $insert->name=$name;
           $insert->E_name=$e_name;
          
           $insert->image=$images;
           $insert->created_at=$dateTime;
           $insert->save();


              if($insert ==true){
               
                   $message['data']=$insert;
                   $message['error']=0;
                   $message['message']='insert success';

             }else{
                    $message['data']=$insert;
                    $message['error']=1;
                     $message['message']='error in insert';
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

       public function update_specialists(Request $request)
     {
      try{
             $token=$request->input('user_token');

          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
      	   $id=$request->input('id');
           $name=$request->input('name');
           $e_name=$request->input('E_name');
            
              $image=$request->file('image');
            $updated_at = carbon::now()->toDateTimeString();
		         $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($updated_at)));
                  if(isset($image)) {
                        $new_name = $image->getClientOriginalName();
                        $savedFileName = rand(100000,999999).time()."_".$new_name; // give a unique name to file to be saved
                        $destinationPath_id = 'uploads/specialist';
                        $image->move($destinationPath_id, $savedFileName);
            
                        $images = $savedFileName;
                      
               }else{
                  $images =Specialisties::where('id',$id)->value('image');       
              }

           $update=Specialisties::where('id',$id)->update([
               'name'=>$name,
               'E_name'=>$e_name,
               'image'=>$images,
               'updated_at'=>$dateTime
           ]);
          
                     $select=Specialisties::where('id',$id)->first();


              if($update == true){
               
                   $message['data']=$select;
                   $message['error']=0;
                   $message['message']='update success';

             }else{
                    $message['data']=$select;
                    $message['error']=1;
                     $message['message']='error in update';
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
