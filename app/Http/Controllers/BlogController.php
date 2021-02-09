<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\posts;
use carbon\carbon;
use App\Category;
use App\User;
class BlogController extends Controller
{
     public $message=array();
     
    public function show_category(Request $request)
     {
       try{
           
            $token=$request->input('user_token');
              $lang=$request->input('lang');
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
          
                     

              if($lang=='ar' ||$lang=='AR'){
                  $select=Category::select('category.id','category.name','category.created_at','category.updated_at')
            
                  ->get();
              }else{
                  
                    $select=Category::select('category.id','category.E_name as name','category.created_at','category.updated_at')
            
                  ->get();
              }
          
                    if(count($select)>0){
                           $message['data']=$select;
                           $message['error']=0;
                           $message['message']='show category success ';
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
     
     
    public function show_categorybyid(Request $request)
     {
       try{
           
            $token=$request->input('user_token');
              $id=$request->input('id');
              
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
          
                     

            
                  $select=Category::select('category.id','category.name','category.E_name','category.created_at','category.updated_at')
            
                  ->where('id',$id)->first();
             
          
                    if($select !=null){
                           $message['data']=$select;
                           $message['error']=0;
                           $message['message']='show category success ';
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
     
     
     public function delete_category(Request $request)
     {
       try{
           
            $token=$request->input('user_token');
              $id=$request->input('id');
              
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
          
                     

            
                  $del=Category::where('id',$id)->delete();
             
          
                    if($del ==true){
                           $message['error']=0;
                           $message['message']='delete category success ';
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
     
     
     public function add_category(Request $request)
     {
       try{
           
            $token=$request->input('user_token');
          

            
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
           
              $name=$request->input('name');
              $ename=$request->input('E_name');
          
              
            
              $updated_at = carbon::now()->toDateTimeString();
              $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($updated_at)));
    
             
         
    
                    $check=Category::where('name',$name)->orwhere('E_name',$ename)->first();
               
               if($check !=null){
                    $message['error']=2;
                    $message['message']='this name  exist';
               
               }else{
        
                    $insert=Category::insertGetId([
                    'name'=>$name,
                    'E_name'=>$ename,
                    'created_at'=>$dateTime]);
    
         
          
                    if($insert >0){
     
                          $message['error']=0;
                           $message['message']='add category success ';
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
     
     
     public function update_category(Request $request)
     {
       try{
           
            $token=$request->input('user_token');
          

            
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
               $id=$request->input('id');
               $name=$request->input('name');
              $ename=$request->input('E_name');
        
              
            
              $updated_at = carbon::now()->toDateTimeString();
              $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($updated_at)));
    
               
    
            
        
                    $update=Category::where('id',$id)->update([
                    'name'=>$name,
                    'E_name'=>$ename,
                  
                    'created_at'=>$dateTime]);
    
         
          
                    if($update ==true){
     
                          $message['error']=0;
                           $message['message']='update category success ';
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
     
     
     
     
     ////////////////////posts
     
      public function show_posts(Request $request)
     {
       try{
           
            $token=$request->input('user_token');
              $lang=$request->input('lang');
              $id=$request->input('category_id');
              
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
          
                     

            
              if($lang=='ar' ||$lang=='AR'){
                  $select=posts::select('id','title','image','description','details','arrange','created_at','updated_at')
            
                         ->where('cat_id',$id)->orderBy('id','ASC')->orderBy('arrange','ASC')->get();
              }else{
                  
                   $select=posts::select('id','E_title as title','image','E_description as description','E_details as details','arrange','created_at','updated_at')
            
                         ->where('cat_id',$id)->orderBy('id','ASC')->orderBy('arrange','ASC')->get();
              }
          
                    if(count($select)>0){
                           $message['data']=$select;
                           $message['error']=0;
                           $message['message']='show posts success ';
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
     
     
      public function show_postbyid(Request $request)
     {
       try{
           
            $token=$request->input('user_token');
             // $lang=$request->input('lang');
              $id=$request->input('id');
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
          
                     

            
                  $select=posts::select('id','title','E_title','image','description','E_description','details','E_details','arrange','cat_id as category_id','created_at','updated_at')
            
                  ->where('id',$id)->first();
            
          
                    if($select !=null){
                           $message['data']=$select;
                           $message['error']=0;
                           $message['message']='show posts success ';
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
     
     
      public function delete_post(Request $request)
     {
       try{
           
            $token=$request->input('user_token');
             // $lang=$request->input('lang');
              $id=$request->input('id');
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
          
                     

            
                  $select=posts::where('id',$id)->delete();
            
          
                    if($select ==true){
                           $message['error']=0;
                           $message['message']='delete posts success ';
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
     
     
       public function add_post(Request $request)
     {
       try{
           
            $token=$request->input('user_token');
          

            
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
           
              $title=$request->input('title');
                   $etitle=$request->input('E_title');
              $image=$request->file('image');
              $desc=$request->input('description');
                  $edesc=$request->input('E_description');
              $details=$request->input('details');
                     $edetails=$request->input('E_details');
              $arr=$request->input('arrange');
          $cat=$request->input('category_id');
              
            
              $updated_at = carbon::now()->toDateTimeString();
              $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($updated_at)));
    
             
            if(isset($image)) {
                            $new_name = $image->getClientOriginalName();
                            $savedFileName = rand(100000,999999).time()."_".$new_name; // give a unique name to file to be saved
                            $destinationPath_id = 'uploads/posts';
                            $image->move($destinationPath_id, $savedFileName);
                
                            $images = $savedFileName;
                          
                   }else{
                      $images = NULL;       
                  }
    
                  
                    $insert=posts::insertGetId([
                    'title'=>$title,
                     'E_title'=>$etitle,
                    'image'=>$images,
                    'cat_id'=>$cat,
                    'description'=>$desc,
                       'E_description'=>$edesc,
                    'details'=>$details,
                     'E_details'=>$edetails,
                    'arrange'=>$arr,
                    'created_at'=>$dateTime]);
    
         
          
                    if($insert >0){
     
                          $message['error']=0;
                           $message['message']='add post success ';
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
     
     
      public function update_post(Request $request)
     {
       try{
           
            $token=$request->input('user_token');
          

            
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
              $id=$request->input('id');
                $title=$request->input('title');
                   $etitle=$request->input('E_title');
              $image=$request->file('image');
              $desc=$request->input('description');
                  $edesc=$request->input('E_description');
              $details=$request->input('details');
                     $edetails=$request->input('E_details');
              $arr=$request->input('arrange');
          $cat=$request->input('category_id');
            
              $updated_at = carbon::now()->toDateTimeString();
              $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($updated_at)));
    
             
            if(isset($image)) {
                            $new_name = $image->getClientOriginalName();
                            $savedFileName = rand(100000,999999).time()."_".$new_name; // give a unique name to file to be saved
                            $destinationPath_id = 'uploads/posts';
                            $image->move($destinationPath_id, $savedFileName);
                
                            $images = $savedFileName;
                          
                   }else{
                      $images =posts::where('id',$id)->value('image');       
                  }
    
                  
                    $update=posts::where('id',$id)->update([
                    'title'=>$title,
                     'E_title'=>$etitle,
                    'image'=>$images,
                    'cat_id'=>$cat,
                    'description'=>$desc,
                       'E_description'=>$edesc,
                    'details'=>$details,
                     'E_details'=>$edetails,
                    'arrange'=>$arr,
                    'updated_at'=>$dateTime]);
    
         
          
                    if($update >0){
     
                          $message['error']=0;
                           $message['message']='update post success ';
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
     
     
      public function show_posts_incategory(Request $request)
     {
       try{
           
           
              $lang=$request->input('lang');
              $id=$request->input('category_id');
              
        
          
                     

            
              if($lang=='ar' ||$lang=='AR'){
                  $select=posts::select('id','title','image','description','details','arrange','created_at','updated_at')
            
                         ->where('cat_id',$id)->orderBy('id','ASC')->orderBy('arrange','ASC')->get();
              }else{
                  
                   $select=posts::select('id','E_title as title','image','E_description as description','E_details as details','arrange','created_at','updated_at')
            
                         ->where('cat_id',$id)->orderBy('id','ASC')->orderBy('arrange','ASC')->get();
              }
          
                    if(count($select)>0){
                           $message['data']=$select;
                           $message['error']=0;
                           $message['message']='show posts success ';
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
     
       public function show_blog(Request $request)
     {
       try{
           $lang=$request->input('lang');
            
               
                         
                 if($lang=='ar' ||$lang=='AR'){
                     
                        $blog['posts']=posts::select('id','title','image','description','created_at')
                                            ->orderBy('id','ASC')->orderBy('arrange','ASC')->get();
                         
                        $blog['recent_posts']=posts::select('id','title','image','created_at')
                                                    ->orderBy('id','DESC')->limit(3)->get();
                         
                         
                  $blog['category']=Category::select('category.id','category.name','category.created_at','category.updated_at')
            
                         ->get();
                  
                  for($i=0;$i<count($blog['category']);$i++){
                      $blog['category'][$i]['count']=posts::where('cat_id',$blog['category'][$i]['id'])->count();
                  }
                  
                  
                  
                  
              }else{
                  
                    $blog['posts']=posts::select('id','E_title as title','image','E_description as description','created_at')
                                        ->orderBy('id','ASC')->orderBy('arrange','ASC')->get();
                         
                    $blog['recent_posts']=posts::select('id','E_title as title','image','created_at')
                                                ->orderBy('click_count','DESC')->limit(3)->get();
                         
                         
                  $blog['category']=Category::select('category.id','category.E_name as name','category.created_at','category.updated_at')->get();
                  
                  for($i=0;$i<count($blog['category']);$i++){
                      $blog['category'][$i]['count']=posts::where('cat_id',$blog['category'][$i]['id'])->count();
                  }
              }        
                         
            
                    if(count($blog)>0){
                           $message['posts']=$blog['posts'];
                           $message['recent_posts']= $blog['recent_posts'];
                           $message['category']= $blog['category'];
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
     
     
       public function show_onepost(Request $request)
     {
       try{
           
    
             $lang=$request->input('lang');
              $id=$request->input('id');
        
                     
             if($lang=='ar'||$lang=='AR'){
            
                  $blog['posts']=posts::select('id','title','E_title','image','description','E_description','details','E_details','created_at','updated_at')
            
                                 ->where('id',$id)->first();
                  
                  
                    $blog['recent_posts']=posts::select('id','title','image','created_at')
            
                                   ->orderBy('click_count','DESC')->limit(3)->get();
                         
                         
                  $blog['category']=Category::select('category.id','category.name','category.created_at','category.updated_at')
            
                         ->get();
                  
                  for($i=0;$i<count($blog['category']);$i++){
                      $blog['category'][$i]['count']=posts::where('cat_id',$blog['category'][$i]['id'])->count();
                  }
                  
                  
             }else{
                     $blog['posts']=posts::select('id','E_title as title','image','E_description as description','E_details as details','created_at','updated_at')
            
                                   ->where('id',$id)->first();
                  
                  
                    $blog['recent_posts']=posts::select('id','title','image','created_at')
            
                                   ->orderBy('click_count','DESC')->limit(3)->get();
                         
                         
                  $blog['category']=Category::select('category.id','category.name','category.created_at','category.updated_at')
            
                         ->get();
                  
                  for($i=0;$i<count($blog['category']);$i++){
                      $blog['category'][$i]['count']=posts::where('cat_id',$blog['category'][$i]['id'])->count();
                  }
             }
            
          
                    if(count( $blog ) > 0){
                        
                        $update=posts::where('id',$id)->increment('click_count',1);
                           $message['posts']=$blog['posts'];
                           $message['recent_posts']=$blog['recent_posts'];
                           $message['category']=$blog['category'];
                           
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
}
