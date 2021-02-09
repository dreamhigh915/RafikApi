<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Category;
use App\Test;
use App\Answer;
use App\Test_questions;
use carbon\carbon;
use App\User;
use App\SubCategory;
use App\Specialisties;
use App\Languages;
use App\user_tests;
use App\Country;
use App\Institution;
use App\Payment;
use App\Time;
use App\Reservation;

class TestController extends Controller
{
      public $message=array();
      
      public function count_data(Request $request)
     {
      try{ 
             $from=$request->input('date_from');
             $to=$request->input('date_to');
             
              if($request->has('date_from') &&$request->has('date_to')){
                  
                      $Specialisties=Specialisties::whereBetween('created_at',[$request->date_from,$request->date_to])->count();
                      $test=SubCategory::whereBetween('created_at',[$request->date_from,$request->date_to])->count();
                      $trainer=User::where('state',4)->whereBetween('created_at',[$request->date_from,$request->date_to])->count();
                      $student=User::where('state',2)->whereBetween('created_at',[$request->date_from,$request->date_to])->count();
                      $wlyAlamer=User::where('state',3)->whereBetween('created_at',[$request->date_from,$request->date_to])->count();
                      $country=Country::whereBetween('created_at',[$request->date_from,$request->date_to])->count();
                      $lang=Languages::whereBetween('created_at',[$request->date_from,$request->date_to])->count();
                      $inst=User::where('state',7)->whereBetween('created_at',[$request->date_from,$request->date_to])->count();
                      $reservation=Reservation::whereBetween('created_at',[$request->date_from,$request->date_to])->count();
                      $payment=Payment::whereBetween('created_at',[$request->date_from,$request->date_to])->count();
                  
              }else{
                      $Specialisties=Specialisties::count();
                      $test=SubCategory::count();
                      $trainer=User::where('state',4)->count();
                      $student=User::where('state',2)->count();
                      $wlyAlamer=User::where('state',3)->count();
                      $country=Country::count();
                      $lang=Languages::count();
                      $inst=User::where('state',7)->count();
                      $reservation=Reservation::count();
                      $payment=Payment::count();
              }
     
                    $message['Specialisties_count']=$Specialisties;
                    $message['test_count']=$test;
                    $message['trainer']=$trainer;
                    $message['student']=$student;
                    $message['wlyAlamer']=$wlyAlamer;
                    $message['country']=$country;
                    $message['languages']=$lang;
                    $message['institutions']=$inst;
                    $message['reservation']=$reservation;
                    $message['payment']=$payment;
                    
             
            
            }catch(Exception $ex){
               $message['error']=2;
               $message['message']='error'.$ex->getMessage();
            }
      
            return response()->json($message);
     }
      
      
 

     public function show_testCategory(Request $request)
     {
      try{
          $token=$request->input('user_token');
           $lang=$request->input('lang');
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
              
              if($lang=='ar' || $lang=='AR'){
                    $about=Category::select('id','name','created_at','updated_at')->get();
              }else{
                     $about=Category::select('id','E_name as anme','created_at','updated_at')->get();
              }

              if(count($about)>0){
               
                   $message['data']=$about;
                   $message['error']=0;
                   $message['message']='show success';

             }else{
                    $message['data']=$about;
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
//////////////////    

    public function show_Categoryoftest(Request $request)
     {
      try{
       
           $lang=$request->input('lang');
          
         
              
              if($lang=='ar' || $lang=='AR'){
                    $about=Category::select('id','name')->limit(2)->get();
              }else{
                     $about=Category::select('id','E_name as anme')->limit(2)->get();
              }
        

              if(count($about)>0){
               
                   $message['data']=$about;
                   $message['error']=0;
                   $message['message']='show success';

             }else{
                    $message['data']=$about;
                    $message['error']=1;
                     $message['message']='no types';
             }
        
            
            }catch(Exception $ex){
               $message['error']=2;
               $message['message']='error'.$ex->getMessage();
            }
      
            return response()->json($message);
     }
     
     
      public function show_subofcategory(Request $request)
     {
      try{
           
           $lang=$request->input('lang');
             $id=$request->input('category_id');
          
         
              
              if($lang=='ar' || $lang=='AR'){
                
                   
                    $sub=SubCategory::select('subcategory.id','subcategory.name','subcategory.image')
                         //->where('subcategory.cat_id',1)
                         ->get();
              }else{
                     
                     
                     $sub=SubCategory::select('subcategory.id','subcategory.E_name as name','subcategory.image')
                //  ->where('subcategory.cat_id',1)
                  ->get();
              }
          
          


              if(count($sub)>0){
               
                
                   $message['data']=$sub;

                   $message['error']=0;
                   $message['message']='show success';

          }else{
                   $message['data']=$sub;

                   $message['error']=1;
                   $message['message']='show fail ';
          }
            
            }catch(Exception $ex){
               $message['error']=2;
               $message['message']='error'.$ex->getMessage();
            }
      
            return response()->json($message);
     }
     
       public function show_secondcategory(Request $request)
     {
      try{
           
           $lang=$request->input('lang');
             $id=$request->input('category_id');
          
         
              
              if($lang=='ar' || $lang=='AR'){
                
                   
                    $sub=SubCategory::select('subcategory.id','subcategory.name','subcategory.image')
                                   ->where('subcategory.cat_id',2)->get();
              }else{
                     
                     
                     $sub=SubCategory::select('subcategory.id','subcategory.E_name as name','subcategory.image')
                                     ->where('subcategory.cat_id',2)->get();
              }
          
          


              if(count($sub)>0){
               
                
                   $message['data']=$sub;

                   $message['error']=0;
                   $message['message']='show success';

          }else{
                   $message['data']=$sub;

                   $message['error']=1;
                   $message['message']='show fail ';
          }
            
            }catch(Exception $ex){
               $message['error']=2;
               $message['message']='error'.$ex->getMessage();
            }
      
            return response()->json($message);
     }
     
     
     
    
     
        public function show_testinsubcategory(Request $request)
     {
      try{
        
           $lang=$request->input('lang');
          
              
            $id=$request->input('sub_id');
            
               if($lang=='ar' || $lang=='AR'){
                   


           $test=Test::select('tests.id as test_id','tests.name')
           ->where('tests.subcat_id',$id)
           ->get();

              }else{
           


           $test=Test::select('tests.id as test_id','tests.E_name as name')
           ->where('tests.subcat_id',$id)
           ->get();

              }
            
           
              if(count($test)>0){
               
                  
                    $message['data']=$test;
                   $message['error']=0;
                   $message['message']='show success';

             }else{
                    $message['data']=$test;
                    $message['error']=1;
                     $message['message']='no types';
             }
       
            }catch(Exception $ex){
               $message['error']=2;
               $message['message']='error'.$ex->getMessage();
            }
      
            return response()->json($message);
     }
     
     
       public function show_alltestquestion(Request $request)
     {
      try{
          
          $lang=$request->input('lang');
        
          
      
              
           $id=$request->input('sub_id');

         
             if($lang=='ar' || $lang=='AR'){
                 

                    $question=Test_questions::select('test_questions.id','test_questions.question')
                       
                       ->where('test_questions.sub_id',$id)
                       ->limit(10)->get();
              }else{
               

                   $question=Test_questions::select('test_questions.id','test_questions.E_question as question')
         
                   ->where('test_questions.sub_id',$id)
                  ->limit(10)->get();
              }

              if(count($question)>0){
               
                   $message['data']=$question;
                   $message['error']=0;
                   $message['message']='show success';

             }else{
                    $message['data']=$question;
                    $message['error']=1;
                     $message['message']='no types';
             }
          
          
            
            }catch(Exception $ex){
               $message['error']=2;
               $message['message']='error'.$ex->getMessage();
            }
      
            return response()->json($message);
     }
     
      public function show_question_answers(Request $request)
     {
      try{
          
          $lang=$request->input('lang');
        
          
      
              
           $id=$request->input('sub_id');

         
             if($lang=='ar' || $lang=='AR'){
                 

                    $question=Test_questions::select('test_questions.id','test_questions.question')
                       
                       ->where('test_questions.sub_id',$id)
                       ->limit(10)->get();
                       
                        for($i=0;$i<count($question);$i++){
                              $question[$i]['answers']=Answer::select('answers.id as answer_id','answers.answer')->where('answers.question_id',$question[$i]->id)->limit(5)->get();
                            
                        }
                       
                       
              }else{
               

                   $question=Test_questions::select('test_questions.id','test_questions.E_question as question')
         
                   ->where('test_questions.sub_id',$id)
                  ->limit(10)->get();
                  
                   for($i=0;$i<count($question);$i++){
                     $question[$i]['answers']=Answer::select('answers.id as answer_id','answers.E_answer as answer')->where('answers.question_id',$question[$i]->id)->limit(5)->get();

                            
                        }
              }
               $count=Test_questions::where('test_questions.sub_id',$id)->count();

              if(count($question)>0){
               
                   $message['data']=$question;
                   $message['count']=$count;
                   $message['error']=0;
                   $message['message']='show success';

             }else{
                    $message['data']=$question;
                    
                    $message['error']=1;
                     $message['message']='no types';
             }
          
          
            
            }catch(Exception $ex){
               $message['error']=2;
               $message['message']='error'.$ex->getMessage();
            }
      
            return response()->json($message);
     }
     
     
        public function show_questionanswers(Request $request)
     {
      try{
           
           $lang=$request->input('lang');
            $id=$request->input('question_id');
            
            
            
         
              
              if($lang=='ar' || $lang=='AR'){
               

             $answers=Answer::select('answers.id','answers.answer')
           ->where('question_id',$id)
          ->limit(5)->get();

              }else{
           


             $answers=Answer::select('answers.id','answers.E_answer as answer')
           ->where('question_id',$id)
           ->limit(5)->get();
           
              }
           

            
          




              if(count($answers)>0){
               
                 
                   $message['data']=$answers;
                   $message['error']=0;
                   $message['message']='show success';

             }else{
                    $message['data']=$answers;
                    $message['error']=1;
                     $message['message']='no types';
             }
        
            
            }catch(Exception $ex){
               $message['error']=2;
               $message['message']='error'.$ex->getMessage();
            }
            
      
            return response()->json($message);
     }
     
     
     
     
     ///////////////////







      public function show_categoryByid(Request $request)
     {
      try{
           $token=$request->input('user_token');
           $lang=$request->input('lang');
             $id=$request->input('category_id');
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
              
              if($lang=='ar' || $lang=='AR'){
                    $specialtype=Category::select('id','name','created_at','updated_at')->where('id',$id)->first();
                   
                    $sub=SubCategory::select('subcategory.id as sub_id','subcategory.name as sub_name','subcategory.image','subcategory.created_at','subcategory.updated_at')
                         ->where('subcategory.cat_id',$id)
                         ->get();
              }else{
                     $specialtype=Category::select('id','E_name as name','created_at','updated_at')->where('id',$id)->first();
                     
                     $sub=SubCategory::select('subcategory.id as sub_id','subcategory.E_name as sub_name','subcategory.image','subcategory.created_at','subcategory.updated_at')
                  ->where('subcategory.cat_id',$id)
                  ->get();
              }
          
          


              if($specialtype !=null){
               
                   $message['data']=$specialtype;
                   $message['subcategories']=$sub;

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
       public function insert_category(Request $request)
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
		        
		        
		        $check=Category::where('name',$name)->get();
		        
		        if(count($check)>0){
		             $message['error']=4;
                   $message['message']='this name is exist';
		            
		        }else{
		            
		             $specialtype=new Category;
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
    public function update_category(Request $request)
    {
      try{
          
          $token=$request->input('user_token');
           $lang=$request->input('lang');
           
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
      	     $id=$request->input('id');
            $name=$request->input('name');
               $e_name=$request->input('E_name');
          

            $updated_at = carbon::now()->toDateTimeString();
		    $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($updated_at)));

	        $specialtype=Category::where('id',$id)->update([
             'name'=>$name,
             'E_name'=>$e_name,
	        
	        'updated_at'=>$dateTime
	        ]);
	     
	        $select=Category::where('id',$id)->first();

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

     public function delete_category(Request $request)
    {
      try{
             $token=$request->input('user_token');
           $lang=$request->input('lang');
                 	     $id=$request->input('category_id');
           
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){

        
	        $specialtype=Category::where('id',$id)->delete();

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

//////////subcategory

      public function show_testSubCategory(Request $request)
     {
      try{
           $id=$request->input('category_id');
           $specialtype=SubCategory::select('subcategory.id','subcategory.name','subcategory.E_name','category.name as category_name','subcategory.created_at','subcategory.updated_at')
           ->join('category','subcategory.cat_id','=','category.id')
           ->where('subcategory.cat_id',$id)
           ->get();

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

      public function show_tests(Request $request)
     {
      try{
           $token=$request->input('user_token');
           $lang=$request->input('lang');
          
           $check_token=User::where('user_token',$token)->first();
          
          
            if($request->has('user_token') && $check_token !=NULL) {
              
         
            
               if($lang=='ar' || $lang=='AR'){
                    $specialtype=DB::select('select subcategory.id as sub_id,subcategory.name,subcategory.image,price,subcategory.created_at,
                    subcategory.updated_at,(select count(id) from user_tests where user_tests.test_id=subcategory.id)as buy_count from subcategory');
            
                    
                   


             

              }else{
             $specialtype=DB::select('select subcategory.id as sub_id,subcategory.E_name as name,subcategory.image,price,subcategory.created_at,
                    subcategory.updated_at,(select count(id) from user_tests where user_tests.test_id=subcategory.id)as buy_count from subcategory');


         

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


       public function insert_subcategory(Request $request)
     {
      try{
           $token=$request->input('user_token');
           $lang=$request->input('lang');
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
            $name=$request->input('name');
            $image=$request->file('image');
             $e_name=$request->input('E_name');
           $cat=$request->input('category_id');
          

            $updated_at = carbon::now()->toDateTimeString();
            $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($updated_at)));
            
            
            
            
             if(isset($image)) {
                    $new_name = $image->getClientOriginalName();
                    $savedFileName = rand(100000,999999).time()."_".$new_name; // give a unique name to file to be saved
                    $destinationPath_id = 'uploads/tests';
                    $image->move($destinationPath_id, $savedFileName);
        
                    $images = $savedFileName;
                  
           }else{
              $images =NULL;       
          }
            
              $check=SubCategory::where('name',$name)->get();
		        
		        if(count($check)>0){
		             $message['error']=4;
                   $message['message']='this name is exist';
		            
		        }else{

                      $specialtype=new SubCategory;
                      $specialtype->name=$name;
                      $specialtype->E_name=$e_name;
                      $specialtype->cat_id=$cat;
                      $specialtype->image=$images;
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
    public function update_subcategory(Request $request)
    {
      try{
           $token=$request->input('user_token');
          
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
              
             $id=$request->input('sub_id');
            $price=$request->input('price');
            

            $updated_at = carbon::now()->toDateTimeString();
        $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($updated_at)));
        
          
          $specialtype=SubCategory::where('id',$id)->update([
             'price'=>$price,
          'updated_at'=>$dateTime
          ]);
       
        

              if($specialtype ==true){
               
                   
                   $message['error']=0;
                   $message['message']='update success';

             }else{
                    
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

     public function delete_subcategory(Request $request)
    {
      try{
          
          
           $token=$request->input('user_token');
          
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
             $id=$request->input('id');
        
          $specialtype=SubCategory::where('id',$id)->delete();

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
    
    
    

    //    tests
  public function check_testbuy(Request $request)
     {
      try{
           $token=$request->input('user_token');
           
            $id=$request->input('test_id');
            
            
            
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if( $request->has('user_token') && $check_token !=NULL){
              
             
                   
            $check=user_tests::where([['test_id',$id],['user_id',$check_token['id']]])->first();
            
            if($check !=null){
                
                $message['data']=1;
              
                 
            }else{
                $message['data']=0;
            
            }
           
         

          
          }else{
               $message['data']=2;
               $message['error']=3;
               $message['message']='this token is not eexist';   
          }
            
            }catch(Exception $ex){
               $message['error']=2;
               $message['message']='error'.$ex->getMessage();
            }
      
            return response()->json($message);
     }

     
      public function show_testByid(Request $request)
     {
      try{
           $token=$request->input('user_token');
           $lang=$request->input('lang');
            $id=$request->input('test_id');
            
            
            
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if( $request->has('user_token') && $check_token !=NULL){
              
             
                    $specialtype=SubCategory::select('id as test_id','name','E_name','image','price','created_at','updated_at')
                       ->where('id',$id)
                       ->get();
            
                    $question=Test_questions::select('test_questions.id as question_id','test_questions.question','test_questions.created_at','test_questions.updated_at')
         
                       ->where('test_questions.test_id',$id)
                       ->get();
         
           
         

              if(count($specialtype) >0){
               
                   $message['data']=$specialtype;
                   $message['questions']=$question;
                   $message['error']=0;
                   $message['message']='show success';

             }else{
                    $message['data']=$specialtype;
                    $message['error']=1;
                     $message['message']='no types';
             }
          }else{
               $message['error']=3;
               $message['message']='this token is not eexist';   
          }
            
            }catch(Exception $ex){
               $message['error']=2;
               $message['message']='error'.$ex->getMessage();
            }
      
            return response()->json($message);
     }
     
       public function show_onetest(Request $request)
     {
      try{
           $token=$request->input('user_token');
           $lang=$request->input('lang');
            $id=$request->input('test_id');
            
            
            
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if( $request->has('user_token') && $check_token !=NULL){
              
          
                    $specialtype=SubCategory::select('id as test_id','name','E_name','price','image','created_at','updated_at')
                       ->where('id',$id)
                       ->get();

                    
       
           
         

              if(count($specialtype) >0){
               
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
               $message['message']='this token is not eexist';   
          }
            
            }catch(Exception $ex){
               $message['error']=2;
               $message['message']='error'.$ex->getMessage();
            }
      
            return response()->json($message);
     }
  public function insert_test(Request $request)
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
                        $destinationPath_id = 'uploads/tests';
                        $image->move($destinationPath_id, $savedFileName);
            
                        $images = $savedFileName;
                      
               }else{
                  $images =NULL;      
              }
            
              $check=SubCategory::where('name',$name)
              ->orWhere('E_name',$e_name)->get();
		        
		        if(count($check)>0){
		             $message['error']=4;
                   $message['message']='this name is exist';
		            
		        }else{
            
            

                      $specialtype=new SubCategory;
                      $specialtype->name=$name;
                      $specialtype->E_name=$e_name;
                      $specialtype->image=$images;
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


    public function update_test(Request $request)
    {
      try{
            
            $token=$request->input('user_token');
            $id=$request->input('id');
        
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
            $price=$request->input('price');

            $updated_at = carbon::now()->toDateTimeString();
            
            $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($updated_at)));

              

          $specialtype=SubCategory::where('id',$id)->update([
            'price'=>$price,
          'updated_at'=>$dateTime
          ]);
       

              if($specialtype ==true){
               
                   $message['error']=0;
                   $message['message']='update success';

             }else{
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
    
    

     public function delete_test(Request $request)
    {
      try{
           
            $token=$request->input('user_token');
            $id=$request->input('id');
        
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
          
        
          $specialtype=SubCategory::where('id',$id)->delete();

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






//test questions
   public function show_testquestion(Request $request)
     {
      try{
           $token=$request->input('user_token');
          $lang=$request->input('lang');
        
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
              
           $id=$request->input('sub_id');

           $specialtype=Test_questions::select('test_questions.id','test_questions.question','test_questions.created_at','test_questions.updated_at')
           ->join('tests','test_questions.test_id','=','tests.id')
           ->where('test_questions.test_id',$id)
           ->get();

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
     
     
     

      public function show_testquestionsByid(Request $request)
     {
      try{
             $token=$request->input('user_token');
           $lang=$request->input('lang');
            $id=$request->input('question_id');
            
            
            
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if( $request->has('user_token') && $check_token !=NULL){
              
                  
        $specialtype=Test_questions::select('test_questions.id as question_id','test_questions.question','test_questions.E_question','test_questions.created_at','test_questions.updated_at')
         
           ->where('test_questions.id',$id)
           ->get();
           
           $m=array();
           
           $m=range(1,223);

             if(in_array($id,$m)){

             $answers=Answer::select('answers.id as answer_id','answers.answer','answers.degree','answers.created_at','answers.updated_at')
                    ->where('question_id',0)
                    ->get();
              }else{
                 $answers=Answer::select('answers.id as answer_id','answers.answer','answers.degree','answers.created_at','answers.updated_at')
                    ->where('question_id',$id)
                    ->get();  
                  
              }
            
           

            
          




              if(count($specialtype)>0){
               
                   $message['data']=$specialtype;
                   $message['answers']=$answers;
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
     
          public function show_questionsByid(Request $request)
     {
      try{
             $token=$request->input('user_token');
           $lang=$request->input('lang');
            $id=$request->input('question_id');
            
            
            
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if( $request->has('user_token') && $check_token !=NULL){
              
          
                  
        $specialtype=Test_questions::select('test_questions.id as question_id','test_questions.question','test_questions.E_question','test_questions.created_at','test_questions.updated_at')
         
           ->where('test_questions.id',$id)
           ->get();


           

           

            
          




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
       public function insert_testquestion(Request $request)
     {
      try{
           $token=$request->input('user_token');
           $lang=$request->input('lang');
          
            
            
            
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if( $request->has('user_token') && $check_token !=NULL){
            $name=$request->input('question');
             $e_name=$request->input('E_question');
             $test=$request->input('sub_id');
           

            $updated_at = carbon::now()->toDateTimeString();
            $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($updated_at)));
            
              $check=Test_questions::where('question',$name)->orwhere('E_question',$e_name)->get();
              $count=Test_questions::where('sub_id',$test)->count();
              
              if($count >=10){
                  
                   $message['error']=5;
                   $message['message']='this test has 10 questions';
              }else{
                   if(count($check)>0){
		             $message['error']=4;
                   $message['message']='this question is exist';
		            
		        }else{

              $specialtype=new Test_questions;
              $specialtype->question=$name;
              $specialtype->E_question=$e_name;
              $specialtype->sub_id=$test;
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
    public function update_testquestions(Request $request)
    {
      try{
            $token=$request->input('user_token');
           $lang=$request->input('lang');
          
            
            
            
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if( $request->has('user_token') && $check_token !=NULL){
              
             $id=$request->input('id');
          $name=$request->input('question');
              $e_name=$request->input('E_question');

            $updated_at = carbon::now()->toDateTimeString();
        $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($updated_at)));

          $specialtype=Test_questions::where('id',$id)->update([
             'question'=>$name,
              'E_question'=>$e_name,
          'updated_at'=>$dateTime
          ]);
       
          $select=Test_questions::where('id',$id)->first();

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

     public function delete_testquestion(Request $request)
    {
      try{
           $token=$request->input('user_token');
           $lang=$request->input('lang');
          
            
            
            
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if( $request->has('user_token') && $check_token !=NULL){
             $id=$request->input('id');
        
          $specialtype=Test_questions::where('id',$id)->delete();

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


//answers
          public function show_answerByid(Request $request)
     {
      try{
             $token=$request->input('user_token');
           $lang=$request->input('lang');
                $id=$request->input('answer_id');
            
            
            
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if( $request->has('user_token') && $check_token !=NULL){
              

                  $specialtype=Answer::select('answers.id as answer_id','answers.answer','answers.E_answer','answers.degree','answers.created_at','answers.updated_at')
           
           ->where('answers.id',$id)
           ->first();

    
        

            
         


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
       public function insert_answer(Request $request)
     {
      try{
          $token=$request->input('user_token');
            $ans=$request->input('answer');
            $e_ans=$request->input('E_answer');
             $question=$request->input('question_id');
           $degree=$request->input('degree');
            
            
            
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if( $request->has('user_token') && $check_token !=NULL){
          

            $updated_at = carbon::now()->toDateTimeString();
            $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($updated_at)));
            
              $check=Answer::where('answer',$ans)->orWhere('E_answer',$e_ans)->get();
               $count=Answer::where('question_id',$question)->count();
              
              if($count >=5){
                  
                   $message['error']=5;
                   $message['message']='this questions has 5 answers';
              }else{
		        
		        if(count($check)>0){
		             $message['error']=4;
                   $message['message']='this answer is exist';
		            
		        }else{

              $specialtype=new Answer;
              $specialtype->answer=$ans;
              $specialtype->E_answer=$e_ans;
              $specialtype->question_id=$question;
              $specialtype->degree=$degree;

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
    public function update_Answer(Request $request)
    {
      try{
            $token=$request->input('user_token');
            $id=$request->input('id');
            $ans=$request->input('answer');
            $e_ans=$request->input('E_answer');
             $question=$request->input('question_id');
           $degree=$request->input('degree');
            
            
            
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if( $request->has('user_token') && $check_token !=NULL){
             
         

            $updated_at = carbon::now()->toDateTimeString();
        $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($updated_at)));

          $specialtype=Answer::where('id',$id)->update([
             'answer'=>$ans,
             'E_answer'=>$e_ans,
           'degree'=>$degree,
          'updated_at'=>$dateTime
          ]);
       
          $select=Answer::where('id',$id)->first();

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

     public function delete_Answer(Request $request)
    {
      try{
           $token=$request->input('user_token');
            $id=$request->input('id');

            
            
            
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if( $request->has('user_token') && $check_token !=NULL){
             
          
    
        
          $specialtype=Answer::where('id',$id)->delete();

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


}
