<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Special_question;
use App\special_questionTye;
use Illuminate\Support\Facades\DB;

use carbon\carbon;
use App\About;
use App\conditions;
use App\Policy;
use App\Opinion;
use App\News;
use App\User;

class Special_questionController extends Controller
{
       	 public $message=array();
     public function show_questiontype(Request $request)
     {
      try{
            $token=$request->input('user_token');
           $lang=$request->input('lang');
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
              
              if($lang=='ar' || $lang=='AR'){
                    $specialtype=special_questionTye::select('id','name','question_num','created_at','updated_at')->get();
              }else{
                     $specialtype=special_questionTye::select('id','E_name as name','question_num','created_at','updated_at')->get();
              }
        
        

   

              if(count($specialtype)>0){
               
                   $message['data']=$specialtype;
                   $message['error']=0;
                   $message['message']='show success';

             }else{
                    $message['data']=$specialtype;
                    $message['error']=1;
                     $message['message']='no types';
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
     
     public function show_opinions()
     {
      try{
        
              
              
                    $specialtype=Opinion::select('users.image','opinions.content')->join('users','opinions.user_id','=','users.id')->distinct()
                    
                     ->orderBy('opinions.id','DESC')->limit(10)->get();
             
        
        

   

              if(count($specialtype)>0){
               
                   $message['data']=$specialtype;
                   $message['error']=0;
                   $message['message']='show success';

             }else{
                    $message['data']=$specialtype;
                    $message['error']=1;
                     $message['message']='no types';
             }
            
        
             }catch(Exception $ex){
               $message['error']=2;
               $message['message']='error'.$ex->getMessage();
            }
      
            return response()->json($message);
     }
     
     
     
        public function show_allopinions()
     {
      try{
        
              
              
                    $specialtype=Opinion::select('users.first_name','users.last_name','users.image','opinions.content','opinions.rate','roles.name as state')
                    ->join('users','opinions.user_id','=','users.id')
                    ->join('roles','users.state','=','roles.id')->where('users.state','!=',1)->distinct()->get();
             
        
        

   

              if(count($specialtype)>0){
               
                   $message['data']=$specialtype;
                   $message['error']=0;
                   $message['message']='show success';

             }else{
                    $message['data']=$specialtype;
                    $message['error']=1;
                     $message['message']='no types';
             }
            
        
             }catch(Exception $ex){
               $message['error']=2;
               $message['message']='error'.$ex->getMessage();
            }
      
            return response()->json($message);
     }
     
     
    public function show_allNews(Request $request){
      try{
        
            // $lang = $request->input('lang');
            
            // if($lang=='ar' ||$lang=='AR'){              
                
                $specialtype = News::select('name','image','link')->get();
            
            
              if(count($specialtype)>0){
               
                   $message['data']=$specialtype;
                   $message['error']=0;
                   $message['message']='show success';

             }else{
                    $message['data']=$specialtype;
                    $message['error']=1;
                     $message['message']='no types';
             }
            
        
             }catch(Exception $ex){
               $message['error']=2;
               $message['message']='error'.$ex->getMessage();
            }
      
            return response()->json($message);
     }
     
     
     
     
     
    public function show_someNews(){
        try{
        
             $specialtype=News::select('name','image','link')->limit(6)->get();
             

              if(count($specialtype)>0){
               
                   $message['data']=$specialtype;
                   $message['error']=0;
                   $message['message']='show success';

             }else{
                    $message['data']=$specialtype;
                    $message['error']=1;
                     $message['message']='no types';
             }
            
        
             }catch(Exception $ex){
               $message['error']=2;
               $message['message']='error'.$ex->getMessage();
            }
      
            return response()->json($message);
     }
       public function show_News(Request $request)
     {
      try{
        
               $token=$request->input('user_token');
        
           
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){ 
              
            
             $specialtype=News::select('id','name','image','link','created_at','updated_at')->get();
             
        
        

   

              if(count($specialtype)>0){
               
                   $message['data']=$specialtype;
                   $message['error']=0;
                   $message['message']='show success';

             }else{
                    $message['data']=$specialtype;
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
     
        public function show_Newbyid(Request $request)
     {
      try{
        
               $token=$request->input('user_token');
         $id=$request->input('id');
           
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){ 
              
            
             $specialtype=News::select('id','name','image','link','created_at','updated_at')->where('id',$id)->first();
             
        
        

   

              if($specialtype !=null){
               
                   $message['data']=$specialtype;
                   $message['error']=0;
                   $message['message']='show success';

             }else{
                    $message['data']=$specialtype;
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
       public function delete_New(Request $request)
     {
      try{
        
               $token=$request->input('user_token');
         $id=$request->input('id');
           
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){ 
              
            
             $specialtype=News::where('id',$id)->delete();
             
        
        

   

              if($specialtype ==true){
               
                   $message['data']=$specialtype;
                   $message['error']=0;
                   $message['message']='show success';

             }else{
                    $message['data']=$specialtype;
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
     
       public function insert_New(Request $request)
     {
      try{
        
               $token=$request->input('user_token');
                 $name=$request->input('name');
                 $image=$request->file('image');
                 $link=$request->input('link');
                 
                 
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){ 
              
              
		          $updated_at = carbon::now()->toDateTimeString();
		          $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($updated_at)));
		             if(isset($image)) {
		                    $new_name = $image->getClientOriginalName();
		                    $savedFileName = rand(100000,999999).time()."_".$new_name; // give a unique name to file to be saved
		                    $destinationPath_id = 'uploads/news';
		                    $image->move($destinationPath_id, $savedFileName);
		        
		                    $images = $savedFileName;
		                  
		           }else{
		              $images = NULL;       
		          }
              
            
             $specialtype=new News;
           $specialtype->name=$name;
           $specialtype->image=$images;
           $specialtype->link=$link;
           $specialtype->created_at=$dateTime;
           $specialtype->save();
          
             
        
        

   

              if($specialtype ==true){
               
                   $message['data']=$specialtype;
                   $message['error']=0;
                   $message['message']='insert success';

             }else{
                    $message['data']=$specialtype;
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
     
       public function update_New(Request $request)
     {
      try{
        
               $token=$request->input('user_token');
               $id=$request->input('id');
                 $name=$request->input('name');
                 $image=$request->file('image');
                 $link=$request->input('link');
                 
                 
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){ 
              
              
		          $updated_at = carbon::now()->toDateTimeString();
		          $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($updated_at)));
		             if(isset($image)) {
		                    $new_name = $image->getClientOriginalName();
		                    $savedFileName = rand(100000,999999).time()."_".$new_name; // give a unique name to file to be saved
		                    $destinationPath_id = 'uploads/news';
		                    $image->move($destinationPath_id, $savedFileName);
		        
		                    $images = $savedFileName;
		                  
		           }else{
		              $images =News::where('id',$id)->value('image');       
		          }
              
            
             $update=News::where('id',$id)->update([
                    'name'=>$name,
                    'image'=>$images,
                     'link'=>$link,
                     'updated_at'=>$dateTime
         
                 ]);
                 
                 
           
          
             $specialtype=News::where('id',$id)->first();
        
        

   

              if($update ==true){
               
                   $message['data']=$specialtype;
                   $message['error']=0;
                   $message['message']='update success';

             }else{
                    $message['data']=$specialtype;
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
     
     
     
     
     
     
       public function show_allquestiontype(Request $request)
     {
      try{
            
           $lang=$request->input('lang');
          
         
              
              if($lang=='ar' || $lang=='AR'){
                    $specialtype=special_questionTye::select('special_questiontype.id','special_questiontype.name',DB::RAW('(select count(id) from special_questions WHERE special_questions.type_id = special_questiontype.id ) as question_num'),'users.first_name as writer')->crossJoin('users')->where('users.id',1)->get();
              }else{
                     $specialtype=special_questionTye::select('special_questiontype.id','special_questiontype.E_name as name',DB::RAW('(select count(id) from special_questions WHERE special_questions.type_id = special_questiontype.id ) as question_num'),'users.first_name as writer')->crossJoin('users')->where('users.id',1)->get();
              }
        
        

   

              if(count($specialtype)>0){
               
                   $message['data']=$specialtype;
                   $message['error']=0;
                   $message['message']='show success';

             }else{
                    $message['data']=$specialtype;
                    $message['error']=1;
                     $message['message']='no types';
             }
            
        
         
             }catch(Exception $ex){
               $message['error']=2;
               $message['message']='error'.$ex->getMessage();
            }
      
            return response()->json($message);
     }
     
        public function show_typequestions(Request $request)
     {
      try{
          
           $lang=$request->input('lang');
            $id=$request->input('id');
          
        
              
              if($lang=='ar' || $lang=='AR'){
                    
                    $qspecialtype=Special_question::select('id','question','answer')->where('type_id',$id)->get();

              }else{
                   
                      $qspecialtype=Special_question::select('id','E_question as question','E_answer as answer')->where('type_id',$id)->get();
              }
        
        

              if(count($qspecialtype)>0 ){
               
                  
                   $message['data']=$qspecialtype;
                   $message['error']=0;
                   $message['message']='show success';

             }else{
                    $message['data']=$qspecialtype;
                 
                    $message['error']=1;
                     $message['message']='no types';
             }
         
            
            }catch(Exception $ex){
               $message['error']=2;
               $message['message']='error'.$ex->getMessage();
            }
      
            return response()->json($message);
     }
     
     
     
     
     
     

      public function show_typeByid(Request $request)
     {
      try{
            $token=$request->input('user_token');
           $lang=$request->input('lang');
            $id=$request->input('id');
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
              
              if($lang=='ar' || $lang=='AR'){
                    $specialtype=special_questionTye::select('id','name','question_num','created_at','updated_at')->where('id',$id)->first();
                    $qspecialtype=Special_question::select('id','question','answer','created_at','updated_at')->where('type_id',$id)->get();

              }else{
                     $specialtype=special_questionTye::select('id','E_name as name','question_num','created_at','updated_at')->where('id',$id)->first();
                      $qspecialtype=Special_question::select('id','E_question as question','E_answer as answer','created_at','updated_at')->where('type_id',$id)->get();
              }
        
        
        

              if($specialtype !=null){
               
                   $message['data']=$specialtype;
                   $message['questions']=$qspecialtype;
                   $message['error']=0;
                   $message['message']='show success';

             }else{
                    $message['data']=$specialtype;
                   $message['questions']=$qspecialtype;
                    $message['error']=1;
                     $message['message']='no types';
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
     
       public function show_onetype(Request $request)
     {
      try{
            $token=$request->input('user_token');
           $lang=$request->input('lang');
            $id=$request->input('id');
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
              
                    $specialtype=special_questionTye::select('id','name','E_name','question_num','created_at','updated_at')->where('id',$id)->first();

           
        
        

              if($specialtype !=null){
               
                   $message['data']=$specialtype;
                  
                   $message['error']=0;
                   $message['message']='show success';

             }else{
                    $message['data']=$specialtype;
                  
                    $message['error']=1;
                     $message['message']='no types';
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
     
     
     
       public function insert_type(Request $request)
     {
      try{
          
           $token=$request->input('user_token');
           $lang=$request->input('lang');
           
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
            $name=$request->input('name');
            $e_name=$request->input('E_name');
           

            $updated_at = carbon::now()->toDateTimeString();
		        $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($updated_at)));
		        
		        $check=special_questionTye::where('name',$name)->get();
		        
		        if(count($check)>0){
		            
		            $message['error']=3;
                   $message['message']='this name exist';
		        }else{
		           $specialtype=new special_questionTye;
    	           $specialtype->name=$name;
                   $specialtype->E_name=$e_name;
    	           

    	        $specialtype->created_at=$dateTime;
    	        $specialtype->save();

              if($specialtype ==true){
               
                   $message['data']=$specialtype;
                   $message['error']=0;
                   $message['message']='insert success';

             }else{
                    $message['data']=$specialtype;
                    $message['error']=1;
                     $message['message']='error in insert';
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
    public function update_type(Request $request)
    {
      try{
          
          
           $token=$request->input('user_token');
           $lang=$request->input('lang');
            $id=$request->input('id');
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
      	     
            $name=$request->input('name');
               $e_name=$request->input('E_name');
           

            $updated_at = carbon::now()->toDateTimeString();
		    $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($updated_at)));

	        $specialtype=special_questionTye::where('id',$id)->update([
             'name'=>$name,
             'E_name'=>$e_name,
	       
	        'updated_at'=>$dateTime
	        ]);
	     
	        $select=special_questionTye::where('id',$id)->first();

              if($specialtype ==true){
               
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

     public function delete_type(Request $request)
    {
      try{
           
           $token=$request->input('user_token');
           $lang=$request->input('lang');
            $id=$request->input('id');
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
      	     $id=$request->input('id');
        
	        $specialtype=special_questionTye::where('id',$id)->delete();

              if($specialtype ==true){
               
                   
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


//questions
     public function show_questionBytype(Request $request)
     {
      try{
            $id=$request->input('type_id');
           $specialtype=Special_question::where('type_id',$id)->get();

              if(count($specialtype)>0){
               
                   $message['data']=$specialtype;
                   $message['error']=0;
                   $message['message']='show success';

             }else{
                    $message['data']=$specialtype;
                    $message['error']=1;
                     $message['message']='no question for this type';
             }
            
            }catch(Exception $ex){
               $message['error']=2;
               $message['message']='error'.$ex->getMessage();
            }
      
            return response()->json($message);
     }

      public function show_questionByid(Request $request)
     {
      try{
             $token=$request->input('user_token');
           $lang=$request->input('lang');
           
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
            $id=$request->input('id');
            
           
                    $specialtype=Special_question::select('id','question','answer','E_question','E_answer','created_at','updated_at')->where('id',$id)->first();
                  

             
          

              if($specialtype !=null){
               
                   $message['data']=$specialtype;
                   $message['error']=0;
                   $message['message']='show success';

             }else{
                    $message['data']=$specialtype;
                    $message['error']=1;
                     $message['message']='no data for question';
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

       public function show_allquestion(Request $request)
     {
      try{
            
           $specialtype=Special_question::all();

              if(count($specialtype)>0){
               
                   $message['data']=$specialtype;
                   $message['error']=0;
                   $message['message']='show success';

             }else{
                    $message['data']=$specialtype;
                    $message['error']=1;
                     $message['message']='no questions';
             }
            
            }catch(Exception $ex){
               $message['error']=2;
               $message['message']='error'.$ex->getMessage();
            }
      
            return response()->json($message);
     }
       public function delete_question(Request $request)
     {
      try{
           $token=$request->input('user_token');
           $lang=$request->input('lang');
           
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
            $id=$request->input('id');
           $specialtype=Special_question::where('id',$id)->delete();

              if($specialtype == true){
                  $update=special_questionTye::where('id',$id)->decrement('question_num',1);
                 
                   $message['error']=0;
                   $message['message']='delete success';

             }else{
              
                    $message['error']=1;
                     $message['message']='error in delete types';
             }
          }else{
                           $message['error']=3;
                     $message['message']='this token i not exist';
          }
            
            }catch(Exception $ex){
               $message['error']=2;
               $message['message']='error'.$ex->getMessage();
            }
      
            return response()->json($message);
     }

       public function insert_question(Request $request)
     {
      try{
           $token=$request->input('user_token');
           $lang=$request->input('lang');
           
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
            $quest=$request->input('question');
            $ans=$request->input('answer');
            $e_quest=$request->input('E_question');
            $e_ans=$request->input('E_answer');
            $type=$request->input('type_id');

             $updated_at = carbon::now()->toDateTimeString();
            $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($updated_at)));
            
            
        
            $check=Special_question::where('question',$quest)->orWhere('E_question',$e_quest)->get();
            
            if(count($check)>0){
                  $message['error']=4;
                   $message['message']='this question exist';
            }else{
           $specialtype=new Special_question;
           $specialtype->question=$quest;
           $specialtype->answer=$ans;
             $specialtype->E_question=$e_quest;
           $specialtype->E_answer=$e_ans;
           $specialtype->type_id=$type;
           $specialtype->created_at=$dateTime;
           $specialtype->save();

              if($specialtype ==true){
                      $update=special_questionTye::where('id',$type)->increment('question_num',1);
               
                   $message['data']=$specialtype;
                   $message['error']=0;
                   $message['message']='insert success';

             }else{
                    $message['data']=$specialtype;
                    $message['error']=1;
                     $message['message']='error in insert  question';
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
       public function update_question(Request $request)
     {
      try{ 
           $token=$request->input('user_token');
           $lang=$request->input('lang');
           
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
      	    $id=$request->input('id');
            $quest=$request->input('question');
            $ans=$request->input('answer');
            $e_quest=$request->input('E_question');
            $e_ans=$request->input('E_answer');
            $type=$request->input('type_id');

              $updated_at = carbon::now()->toDateTimeString();
            $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($updated_at)));



            if(empty($type))
            {
             Special_question::where('id',$id)->value('type_id') ;
            }


           $update=Special_question::where('id',$id)->update([
             'question'=>$quest,
             'answer'=>$ans,
              'E_question'=>$e_quest,
             'E_answer'=>$e_ans,
             'type_id'=>$type,
             'updated_at'=>$dateTime
           ]);
           
             $specialtype=Special_question::where('id',$id)->first();

              if($update ==true){
               
                   $message['data']=$specialtype;
                   $message['error']=0;
                   $message['message']='update success';

             }else{
                    $message['data']=$specialtype;
                    $message['error']=1;
                     $message['message']='error in update for question';
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
     
       public function show_roles_conditions(Request $request)
     {
       try{
           
          
           $lang=$request->input('lang');
          
         
               if($lang=='ar' || $lang=='AR'){
                 $show=About::select('conditions as text')->get(); 
                 $subject=conditions::select('id','title','details','created_at','updated_at')->get();
              }else{
                   $show=About::select('E_conditions as text')->get(); 
                    
                     $subject=conditions::select('id','E_title as title','E_details as details','created_at','updated_at')->get();
              }

                   $date=DB::select('select date(updated_at)as date from aboutus where id=1 limit 1');
              if(count($show)>0 ){
               
                   $message['data']=$show;
                  $message['subjects']=$subject;
                 $message['updated_at']= $date[0]->date; 
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
     
      public function show_rolesubject(Request $request)
     {
       try{
           
          
           $lang=$request->input('lang');
          
         
               if($lang=='ar' || $lang=='AR'){
                
                 $subject=conditions::select('title','details')->get();
              }else{
                
                    
                     $subject=conditions::select('E_title as title','E_details as details')->get();
              }


              if(count($subject)>0 ){
               
                   $message['data']=$subject;
                    
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
     
     
       public function show_conditions(Request $request)
     {
       try{
           
            $token=$request->input('user_token');
           $lang=$request->input('lang');
          
          $check_token=User::where('user_token',$token)->first();
          
          //356a192b7913b04c54574d18c28d46e6395428ab
          if($request->has('user_token') && $check_token !=NULL){
              
               if($lang=='ar' || $lang=='AR'){
                 $show=About::select('conditions as text')->get(); 
                 $subject=conditions::select('id','title','created_at','updated_at')->get();
              }else{
                   $show=About::select('E_conditions as text')->get(); 
                    
                     $subject=conditions::select('id','E_title as title','created_at','updated_at')->get();
              }


              if(count($show)>0 ){
               
                   $message['data']=$show;
                     $message['subjects']=$subject;
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
     
      public function show_conditionByid(Request $request)
     {
       try{
           
            $token=$request->input('user_token');
           $lang=$request->input('lang');
           
          
           
          $check_token=User::where('user_token',$token)->first();
          
          //356a192b7913b04c54574d18c28d46e6395428ab
          if($request->has('user_token') && $check_token !=NULL){
              
             
                   
                 $show=About::select('conditions as text','E_conditions as E_text')->first(); 
                
            


              if($show != null ){
               
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
     
    /*   public function show_conditionByid(Request $request)
     {
       try{
           
            $token=$request->input('user_token');
           $lang=$request->input('lang');
           
           $id=$request->input('subject_id');
           
          $check_token=User::where('user_token',$token)->first();
          
          //356a192b7913b04c54574d18c28d46e6395428ab
          if($request->has('user_token') && $check_token !=NULL){
              
               if($lang=='ar' || $lang=='AR'){
                   
                
                 $subject=conditions::select('id','title','details','created_at','updated_at')->where('id',$id)->first();
              }else{
                   
                    
                     $subject=conditions::select('id','E_title as title','E_details as details','created_at','updated_at')->where('id',$id)->first();
              }


              if($subject != null ){
               
                  
                     $message['data']=$subject;
                   $message['error']=0;
                   $message['message']='show success';

             }else{
                    $message['data']=$subject;
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
     }*/
     
       public function update_conditions(Request $request)
     {
       try{
             $token=$request->input('user_token');
           $lang=$request->input('lang');
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
           $txt=$request->input('text');
            $e_txt=$request->input('E_text');
            
              $updated_at = carbon::now()->toDateTimeString();
            $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($updated_at)));




             $update=About::where('id',1)->update([
                    'conditions'=>$txt,
                    'E_conditions'=>$e_txt,
                    'updated_at'=>$dateTime

             ]);
             $select=About::select('conditions','E_conditions')->where('id',1)->first();

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
     
         public function update_conditionsubject(Request $request)
     {
       try{
             $token=$request->input('user_token');
           $lang=$request->input('lang');
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
            $id=$request->input('id');
         
            $title=$request->input('title');
            $e_title=$request->input('E_title');
            $detalis=$request->input('details');
            $e_detalis=$request->input('E_details');
            
              $updated_at = carbon::now()->toDateTimeString();
            $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($updated_at)));



             
             
             $updatesubject=conditions::where('id',$id)->update([
                 
                     'title'=>$title,
                     'E_title'=>$e_title,
                     'details'=>$detalis,
                     'E_details'=>$e_detalis,
                     'updated_at'=>$dateTime
                 
                 ]);
                 
                 $select=conditions::where('id',$id)->first();

              if($updatesubject == true){
               
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
     
      public function insert_conditionsubject(Request $request)
     {
       try{
             $token=$request->input('user_token');
           $lang=$request->input('lang');
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
          
            $title=$request->input('title');
            $e_title=$request->input('E_title');
            $detalis=$request->input('details');
            $e_detalis=$request->input('E_details');
            
              $updated_at = carbon::now()->toDateTimeString();
            $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($updated_at)));



            
             
                     $updatesubject=new conditions;
                 
                     $updatesubject->title=$title;
                     $updatesubject->E_title=$e_title;
                     $updatesubject->details=$detalis;
                     $updatesubject->E_details=$e_detalis;
                     $updatesubject->created_at=$dateTime;
                     $updatesubject->save();
                 
                
                 

              if($updatesubject == true){
               
                   $message['data']=$updatesubject;
                   $message['error']=0;
                   $message['message']='insert success';

             }else{
                    $message['data']=$updatesubject;
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
     
       public function delete_subjectconditions(Request $request)
     {
       try{
             $token=$request->input('user_token');
           $lang=$request->input('lang');
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
          
            $id=$request->input('id');
          
        



            
             
                     $updatesubject=conditions::where('id',$id)->delete();
                 
                 
                
                 

              if($updatesubject == true){
               
            
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
     
     
     
     
     public function show_PrivacePolicy(Request $request)
     {
       try{
           
           
           $lang=$request->input('lang');
          
       
               if($lang=='ar' || $lang=='AR'){
                 $show=About::select('privacy_policy as text')->get(); 
                 $subject=Policy::select('id','title','details','created_at','updated_at')->get();
              }else{
                   $show=About::select('E_privacy_policy as text')->get(); 
                   $subject=Policy::select('id','E_title as title','E_details as details','created_at','updated_at')->get();
              }

           $date=DB::select('select date(updated_at)as date from aboutus where id=1 limit 1');
           
              if(count($show)>0 ){
                   $message['data']=$show;
                   $message['subjects']=$subject;
                   $message['updated_at']= $date[0]->date; 
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
     
     
     
     public function show_subjectPolicy(Request $request)
     {
       try{
           
           
           $lang=$request->input('lang');
          
       
               if($lang=='ar' || $lang=='AR'){
                 
                 $subject=Policy::select('title','details')->get();
              }else{
                 
                    
                     $subject=Policy::select('E_title as title','E_details as details')->get();
              }


              if(count($subject)>0 ){
               
                   $message['data']=$subject;
                   
                   $message['error']=0;
                   $message['message']='show success';

             }else{
                    $message['data']=$subject;
                    $message['error']=1;
                     $message['message']='no data';
             }
            
           
           
        
            }catch(Exception $ex){
               $message['error']=2;
               $message['message']='error'.$ex->getMessage();
            }
      
             return response()->json($message);
     }
     

     public function show_policy(Request $request)
     {
       try{
           
            $token=$request->input('user_token');
           $lang=$request->input('lang');
          
          $check_token=User::where('user_token',$token)->first();
          
          //356a192b7913b04c54574d18c28d46e6395428ab
          if($request->has('user_token') && $check_token !=NULL){
              
               if($lang=='ar' || $lang=='AR'){
                 $show=About::select('privacy_policy as text')->get(); 
                 $subject=Policy::select('id','title','created_at','updated_at')->get();
              }else{
                   $show=About::select('E_privacy_policy as text')->get(); 
                    
                     $subject=Policy::select('id','E_title as title','created_at','updated_at')->get();
              }


              if(count($show)>0 ){
               
                   $message['data']=$show;
                     $message['subjects']=$subject;
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
     
     
       public function show_policyByid(Request $request)
     {
       try{
           
            $token=$request->input('user_token');
           $lang=$request->input('lang');
           
          
           
          $check_token=User::where('user_token',$token)->first();
          
          //356a192b7913b04c54574d18c28d46e6395428ab
          if($request->has('user_token') && $check_token !=NULL){
              
               
                   
                 $show=About::select('privacy_policy as text','E_privacy_policy as E_text','created_at')->first(); 
                
             


              if($show != null ){
               
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
   /*  public function show_policyByid(Request $request)
     {
       try{
           
            $token=$request->input('user_token');
           $lang=$request->input('lang');
           
           $id=$request->input('id');
           
          $check_token=User::where('user_token',$token)->first();
          
          //356a192b7913b04c54574d18c28d46e6395428ab
          if($request->has('user_token') && $check_token !=NULL){
              
               if($lang=='ar' || $lang=='AR'){
                   
                 $show=About::select('privacy_policy as text')->first(); 
                 $subject=Policy::select('id','title','details','created_at','updated_at')->where('id',$id)->first();
              }else{
                   $show=About::select('E_privacy_policy as text')->first(); 
                    
                     $subject=Policy::select('id','E_title as title','E_details as details','created_at','updated_at')->where('id',$id)->first();
              }


              if($show != null ){
               
                   $message['data']=$show;
                     $message['subjects']=$subject;
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
     }*/
      public function show_subjectByid(Request $request)
     {
       try{
           
            $token=$request->input('user_token');
           $lang=$request->input('lang');
           
           $id=$request->input('id');
           
          $check_token=User::where('user_token',$token)->first();
          
          //356a192b7913b04c54574d18c28d46e6395428ab
          if($request->has('user_token') && $check_token !=NULL){
              
             
                
                 $subject=Policy::select('id','title','E_title','details','E_details','created_at','updated_at')->where('id',$id)->first();
          


              if($subject != null ){
               
                  
                     $message['data']=$subject;
                   $message['error']=0;
                   $message['message']='show success';

             }else{
                    $message['data']=$subject;
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


      public function show_onesubject(Request $request)
     {
       try{
           
            $token=$request->input('user_token');
           $lang=$request->input('lang');
           
           $id=$request->input('id');
           
          $check_token=User::where('user_token',$token)->first();
          
          //356a192b7913b04c54574d18c28d46e6395428ab
          if($request->has('user_token') && $check_token !=NULL){
              
             
                
                 $subject=conditions::select('id','title','E_title','details','E_details','created_at','updated_at')->where('id',$id)->first();
          


              if($subject != null ){
               
                  
                     $message['data']=$subject;
                   $message['error']=0;
                   $message['message']='show success';

             }else{
                    $message['data']=$subject;
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


     public function update_policy(Request $request)
     {
       try{
             $token=$request->input('user_token');
           $lang=$request->input('lang');
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
           $txt=$request->input('text');
            $e_txt=$request->input('E_text');
            
              $updated_at = carbon::now()->toDateTimeString();
            $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($updated_at)));




             $update=About::where('id',1)->update([
                    'privacy_policy'=>$txt,
                    'E_privacy_policy'=>$e_txt,
                    'updated_at'=>$dateTime

             ]);
             $select=About::select('privacy_policy','E_privacy_policy')->where('id',1)->first();

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
     
       public function update_subject(Request $request)
     {
       try{
             $token=$request->input('user_token');
           $lang=$request->input('lang');
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
            $id=$request->input('id');
         
            $title=$request->input('title');
            $e_title=$request->input('E_title');
            $detalis=$request->input('details');
            $e_detalis=$request->input('E_details');
            
              $updated_at = carbon::now()->toDateTimeString();
            $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($updated_at)));



             
             
             $updatesubject=Policy::where('id',$id)->update([
                 
                     'title'=>$title,
                     'E_title'=>$e_title,
                     'details'=>$detalis,
                     'E_details'=>$e_detalis,
                     'updated_at'=>$dateTime
                 
                 ]);
                 
                 $select-Policy::where('id',$id)->first();

              if($updatesubject == true){
               
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
     
      public function insert_subject(Request $request)
     {
       try{
             $token=$request->input('user_token');
           $lang=$request->input('lang');
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
          
            $title=$request->input('title');
            $e_title=$request->input('E_title');
            $detalis=$request->input('details');
            $e_detalis=$request->input('E_details');
            
              $updated_at = carbon::now()->toDateTimeString();
            $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($updated_at)));



            
             
                     $updatesubject=new Policy;
                 
                     $updatesubject->title=$title;
                     $updatesubject->E_title=$e_title;
                     $updatesubject->details=$detalis;
                     $updatesubject->E_details=$e_detalis;
                     $updatesubject->created_at=$dateTime;
                     $updatesubject->save();
                 
                
                 

              if($updatesubject == true){
               
                   $message['data']=$updatesubject;
                   $message['error']=0;
                   $message['message']='insert success';

             }else{
                    $message['data']=$updatesubject;
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
     
       public function delete_subject(Request $request)
     {
       try{
             $token=$request->input('user_token');
           $lang=$request->input('lang');
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
          
            $id=$request->input('id');
          
        



            
             
                     $updatesubject=Policy::where('id',$id)->delete();
                 
                 
                
                 

              if($updatesubject == true){
               
            
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
     
}
