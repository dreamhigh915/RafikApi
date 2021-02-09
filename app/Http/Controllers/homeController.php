<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Opinion;
use App\Work;
use App\Category;
use App\posts;
use App\home;
use carbon\carbon;
use App\About;

class homeController extends Controller
{
     public $message=array();
     
     
     
     public function home_data(Request $request)
     {
       try{
           $lang=$request->input('lang');
            
               
                         
                 if($lang=='ar' ||$lang=='AR'){
                     
                        $select=home::select('title','description')
            
                            ->where('id',1)->first();
                         
                        
              }else{
                  
                     $select=home::select('E_title as title','E_description as description')
            
                           ->where('id',1)->first();
                         
                        
                         
                         
                  
              }        
                         
            
                    if($select !=null){
                                   $message['data']=$select;
                                 
                                   $message['error']=0;
                                   $message['message']='show data success ';
                            }else{
                                   $message['data']=$select;
                                   $message['error']=1;
                                   $message['message']='error in show';
                            }
                  
              

              
        
            }catch(Exception $ex){
                   $message['error']=4;
                   $message['message']='error'.$ex->getMessage();
            }
            return response()->json($message);
     }
     
     
      public function social()
     {
       try{
           
          
                  
             $select=About::select('face_link','insta_link','linked_link','twitter_link')->where('id',1)->first();
    
            if($select !=null){
                   $message['data']=$select;
                 
                   $message['error']=0;
                   $message['message']='show data success ';
            }else{
                   $message['data']=$select;
                   $message['error']=1;
                   $message['message']='error in show';
            }
                  
            }catch(Exception $ex){
                   $message['error']=4;
                   $message['message']='error'.$ex->getMessage();
            }
            return response()->json($message);
     }
     
     
     
     
       public function show_homeData(Request $request)
     {
       try{
           $lang=$request->input('lang');
            $token=$request->input('user_token');
            
              
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
            
               
                         

                        $select=home::select('title','description','E_title','E_description','created_at','updated_at')
            
                            ->where('id',1)->first();
                         
                        
                     
                         
            
                    if($select !=null){
                                   $message['data']=$select;
                                 
                                   $message['error']=0;
                                   $message['message']='show data success ';
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
     
       public function update_homeData(Request $request)
     {
       try{
          // $lang=$request->input('lang');
            $token=$request->input('user_token');
            $title=$request->input('title');
              $e_title=$request->input('E_title');
                $description=$request->input('description');
                  $e_description=$request->input('E_description');
            
            
              
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
            
                        
                   $updated_at = carbon::now()->toDateTimeString();
              $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($updated_at)));
                         

                        $update=home::where('id',1)->update([
                            'title'=>$title,'description'=>$description,'E_title'=>$e_title,'E_description'=>$e_description,
                            'updated_at'=>$dateTime
                            ]);
            
                           
                         
                        
                     
                         
            
                    if($update ==true){
                                  
                                 
                                   $message['error']=0;
                                   $message['message']='update data success ';
                            }else{
                                  
                                   $message['error']=1;
                                   $message['message']='error in update';
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
     
     
     
     
     //make_opinion
     
      public function make_opinion(Request $request)
     {
       try{
           
            $token=$request->input('user_token');
              $content=$request->input('content');
              
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
          
            if($content == "" || $content == NULL){
                $message['error'] = 1;
                $message['message'] = "please fill all the data";
                return response()->json($message);

            }         
                          
             $updated_at = carbon::now()->toDateTimeString();
             $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($updated_at)));

             $insert=new Opinion;
             $insert->user_id=$check_token['id'];
             $insert->content=$content;
             $insert->created_at=$dateTime;
             $insert->save();
             
          
                    if($insert ==true){
                           
                           $message['error']=0;
                           $message['message']='insert opinion success ';
                    }else{
                          
                           $message['error']=1;
                           $message['message']='error in save';
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
     
     
     //admin
       public function choose_main_post(Request $request)
     {
       try{
           
            $token=$request->input('user_token');
              $id=$request->input('id');
              
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
          
                     
                          
                   $updated_at = carbon::now()->toDateTimeString();
              $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($updated_at)));

             $update=posts::where('id',$id)->update([
                 
                  'main'=>1,
                  'updated_at'=>$dateTime
                 ]);
           
            
            
             
          
                    if($update ==true){
                          $update=posts::where('id','!=',$id)->update([
                 
                              'main'=>0,
                              'updated_at'=>$dateTime
                             ]);
                           
                           $message['error']=0;
                           $message['message']='choose success ';
                    }else{
                          
                           $message['error']=1;
                           $message['message']='error in choose';
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
     
     
     //home 
     public function home_blog(Request $request)
     {
       try{
           $lang=$request->input('lang');
            
               
                         
                 if($lang=='ar' ||$lang=='AR'){
                     
                        $blog['posts']=posts::select('id','title','image','description','created_at')
                                            ->limit(2)->orderby('id' , 'DESC')->get();
                         
                        $blog['recent_posts']=posts::select('id','title','image','created_at')
                                                    ->orderBy('id','DESC')->limit(3)->get();
                         
                  
                  
              }else{
                  
                     $blog['posts']=posts::select('id','E_title as title','image','E_description as description','created_at')
                                          ->limit(2)->orderBy('id', 'DESC')->get();
                         
                        $blog['recent_posts']=posts::select('id','E_title as title','image','created_at')
                                                  ->orderBy('id','DESC')->limit(3)->get();
                         
                         
                  
              }        
                         
            
                    if(count($blog)>0){
                                   $message['posts']=$blog['posts'];
                                   $message['recent_posts']= $blog['recent_posts'];
                                   $message['error']=0;
                                   $message['message']='show posts success ';
                            }else{
                                   $message['data']=$blog;
                                   $message['error']=1;
                                   $message['message']='error in show';
                            }
                  
              

              
        
            }catch(Exception $ex){
                   $message['error']=4;
                   $message['message']='error'.$ex->getMessage();
            }
            return response()->json($message);
     }
     
     
     
     
     //admin  rafik work  steps
     
       public function show_steps(Request $request)
     {
       try{
           
            $token=$request->input('user_token');
              $lang=$request->input('lang');
              
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
          
                     

              if($lang=='ar' ||$lang=='AR'){
                  $select=Work::select('id','title','description','image','video','link','created_at','updated_at')
            
                  ->get();
              }else{
                  
                    $select=Work::select('id','E_title as title','E_description as description' , 'image','video','link','created_at','updated_at')
            
                  ->get();
              }
          
                    if(count($select)>0){
                           $message['data']=$select;
                           $message['error']=0;
                           $message['message']='show steps success ';
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
     
       public function show_stepbyid(Request $request)
     {
       try{
           
            $token=$request->input('user_token');
              $id=$request->input('id');
              
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
          
                     


                  $select=Work::select('id','title','description','E_title','E_description','video','image','created_at','updated_at')
            
                  ->where('id',$id)->first();
              
          
                    if($select !=null){
                           $message['data']=$select;
                           $message['error']=0;
                           $message['message']='show steps success ';
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
     
     
     public function update_steps(Request $request)
     {
       try{
           
            $token=$request->input('user_token');
            
              $id=$request->input('id');
              $title=$request->input('title');
              $e_title=$request->input('E_title');
              $description=$request->input('description');
              $e_description=$request->input('E_description');
             $video=$request->file('video');
             $image=$request->file('image');
          
              
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
          
                     
           if(isset($image)) {
                $new_name = $image->getClientOriginalName();
                $savedFileName = rand(100000,999999).time()."_".$new_name; // give a unique name to file to be saved
                $destinationPath_id = 'uploads/how_work';
                $image->move($destinationPath_id, $savedFileName);
    
                $images = $savedFileName;
              
            }else{
              $images = NULL;       
            }
                  
                  
                   $updated_at = carbon::now()->toDateTimeString();
              $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($updated_at)));
                  
                  
                    if(isset($video)) {
                            $new_name = $video->getClientOriginalName();
                            $savedFileName = rand(100000,999999).time()."_".$new_name; // give a unique name to file to be saved
                            $destinationPath_id = 'uploads/how_work';
                            $video->move($destinationPath_id, $savedFileName);
                
                            $videos = $savedFileName;
                          
                   }else{
                      $videos = NULL;       
                  }

                  $update=Work::where('id',$id)->update([
                  'title'=>$title,
                  'description'=>$description,
                  'E_title'=>$e_title,
                  'E_description'=>$e_description,
                  'video'=>$videos,
                  'image'=>$images,
                  'updated_at'=>$dateTime
                  ]) ;
              
          
                    if($update ==true){
                   
                           $message['error']=0;
                           $message['message']='update steps success ';
                    }else{
                          
                           $message['error']=1;
                           $message['message']='error in update';
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
     
     
     
     public function rafik_work1(Request $request)
     {
       try{
           
           
              $lang=$request->input('lang');
              
        
          
                     

              if($lang=='ar' ||$lang=='AR'){
                  $select=Work::select('id','title','description','video','link','image')
            
                  ->where('id',1)->first();
              }else{
                  
                    $select=Work::select('id','E_title as title','E_description as description','video','link','image')
            
                  ->where('id',1)->first();
              }
          
                    if($select !=null){
                           $message['data']=$select;
                           $message['error']=0;
                           $message['message']='show steps success ';
                    }else{
                           $message['data']=$select;
                           $message['error']=1;
                           $message['message']='error in show';
                    }
              

              
         
            }catch(Exception $ex){
                   $message['error']=4;
                   $message['message']='error'.$ex->getMessage();
            }
            return response()->json($message);
     }
     
     
     
       public function rafik_work2(Request $request)
     {
       try{
           
           
              $lang=$request->input('lang');
              
        
          
                     
                     

              if($lang=='ar' ||$lang=='AR'){
                  $select=Work::select('id','title','description','link','image','video')
            
                  ->where('id',2)->first();
              }else{
                  
                    $select=Work::select('id','E_title as title','E_description as description','link','image','video')
            
                  ->where('id',2)->first();
              }
          
                    if($select !=null){
                           $message['data']=$select;
                           $message['error']=0;
                           $message['message']='show steps success ';
                    }else{
                           $message['data']=$select;
                           $message['error']=1;
                           $message['message']='error in show';
                    }
              

              
         
            }catch(Exception $ex){
                   $message['error']=4;
                   $message['message']='error'.$ex->getMessage();
            }
            return response()->json($message);
     }
     
     
}
