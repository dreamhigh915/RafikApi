<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Test_questions;
use carbon\carbon;
use App\Reports;
use App\SubCategory;
use App\Test_answer;
use App\test_code;
use App\User;
use App\Answer;

class newTestController extends Controller
{
     public $message=array();


    public function test_atest(Request $request){

          
              
            $token=$request->input('user_token');
          $report=$request->input('report_id');
          $test=$request->input('sub_id');
          $group=$request->input('group_number');
          $id=$request->input('id');
          $type=$request->input('test_type');          $question=[];
           //  if($lang=='en' || $lang=='EN'){
                 
   
        if($type ==1){
            
              if($request->has('user_token')){
                          
                         $data=User::where('user_token',$token)->first();
                         
                         
                         $check=Test_answer::where([['user_id',$data['id']],['test_type',1],['test_id',$test],['group_num',$group]])->orderBy('id','DESC')->first();
                         
                         
                         if($check !=null){
                             
                             $count_data=Test_answer::where([['user_id',$data['id']],['test_type',1],['test_id',$test],['group_num',$group]])->count();
                         
                              if($check['question_id'] >=223 && $check['question_id'] < 239){
                                  
                                  return ' move to next';
                                   $message['error']=3;
                                   $message['message']='you finished this exam move to next exam';
                              
                              }elseif($check['question_id'] >= 239){
                                  
                                      $question=Test_questions::select('test_questions.id','test_questions.question')
                               
                                     ->where([['test_questions.test_id','=',$test],['group_num',$group]])->limit(20)->get();
                                               
                                    for($i=0;$i<count($question);$i++){
                        
                                    $question[$i]['answers']=Answer::select('id as value','answer')
                                         ->where('question_id',0)->get();
                                         
                                         
                                       } 
                                  
                              
                              }else{
    
                             
                                      $question=Test_questions::select('test_questions.id','test_questions.question')
                                   
                                         ->where([['test_questions.id','>',$check['question_id']],['test_id',$test],['group_num',$group]])->limit(20)->get();
                                                   
                                        for($i=0;$i<count($question);$i++){
                            
                                        $question[$i]['answers']=Answer::select('id as value','answer')
                                             ->where('question_id',0)->get();
                                             
                                             
                                       }
                              }
                         }else{
                             
                                      $question=Test_questions::select('test_questions.id','test_questions.question')
                               
                                     ->where([['test_questions.test_id','=',$test],['group_num',$group]])->limit(20)->get();
                                               
                                    for($i=0;$i<count($question);$i++){
                        
                                    $question[$i]['answers']=Answer::select('id as value','answer')
                                         ->where('question_id',0)->get();
                                         
                                         
                                       } 
                                     
                             
                         }
                         
                           
                           if(count($question)>0){
                               $message['data']=$question;
                               $message['error']=0;
                               $message['message']='show data success';
                           }else{
                               $message['data']=$question;
                               $message['error']=1;
                               $message['message']='error in show data';
                               
                           }
              }else{
                     $data=Reports::where('id',$report)->first();
                  
                     $check=Test_answer::where([['user_id',$data['user_id']],['test_type',1],['test_id',$test],['group_num',$group]])->orderBy('id','DESC')->first();
                      
                      if($check !=null){
                          
                          $count_data=Test_answer::where([['user_id',$data['user_id']],['test_type',1],['test_id',$test],['group_num',$group]])->count();
                         
                              if($check['question_id'] >=223 && $check['question_id'] < 239){
                                  
                                  return ' move to next';
                                   $message['error']=3;
                                   $message['message']='you finished this exam move to next exam';
                              
                              }elseif($check['question_id'] >= 239){
                                  
                                      $question=Test_questions::select('test_questions.id','test_questions.question')
                               
                                     ->where([['test_questions.test_id','=',$test],['group_num',$group]])->limit(20)->get();
                                               
                                    for($i=0;$i<count($question);$i++){
                        
                                    $question[$i]['answers']=Answer::select('id as value','answer')
                                         ->where('question_id',0)->get();
                                         
                                         
                                       } 
                                  
                              
                              }else{
                         
                         
                                  $question=Test_questions::select('test_questions.id','test_questions.question')
                               
                                     ->where([['test_questions.id','>',$check['question_id']],['test_id',$test],['group_num',$group]])->limit(20)->get();
                                               
                                    for($i=0;$i<count($question);$i++){
                        
                                    $question[$i]['answers']=Answer::select('id as value','answer')
                                         ->where('question_id',0)->get();
                                         
                                         
                                   }
                              }
                         }else{
                             
                                      $question=Test_questions::select('test_questions.id','test_questions.question')
                               
                                     ->where([['test_questions.test_id','=',$test],['group_num',$group]])->limit(20)->get();
                                               
                                    for($i=0;$i<count($question);$i++){
                        
                                    $question[$i]['answers']=Answer::select('id as value','answer')
                                         ->where('question_id',0)->get();
                                         
                                         
                                       } 
                                     
                             
                         }
                           
                              if(count($question)>0){
                               $message['data']=$question;
                               $message['error']=0;
                               $message['message']='show data success';
                           }else{
                               $message['data']=$question;
                               $message['error']=1;
                               $message['message']='error in show data';
                               
                           }
              }
            
        }else{
            
            
             if($request->has('user_token')&& $request->has('id')){
                    
                         $data=User::where('id',$id)->first();
                         
                         $examiner=User::where('user_token',$token)->first();
                         
                      $check=Test_answer::where([['user_id',$id],['examiner_id',$examiner['id']],['test_type',2],['test_id',$test],['group_num',$group]])->orderBy('id','DESC')->first();
             
            
                        if($check !=null){
                            
                               $count_data=Test_answer::where([['user_id',$id],['examiner_id',$examiner['id']],['test_type',2],['test_id',$test],['group_num',$group]])->count();
                         
                              if($check['question_id'] >=223 && $check['question_id'] < 239){
                                  
                                  return ' move to next';
                                   $message['error']=3;
                                   $message['message']='you finished this exam move to next exam';
                              
                              }elseif($check['question_id'] >= 239){
                                  
                                      $question=Test_questions::select('test_questions.id','test_questions.question')
                               
                                     ->where([['test_questions.test_id','=',$test],['group_num',$group]])->limit(20)->get();
                                               
                                    for($i=0;$i<count($question);$i++){
                        
                                    $question[$i]['answers']=Answer::select('id as value','answer')
                                         ->where('question_id',0)->get();
                                         
                                         
                                       } 
                              
                              }else{
                         
                         
                                  $question=Test_questions::select('test_questions.id','test_questions.question')
                               
                                     ->where([['test_questions.id','>',$check['question_id']],['test_id',$test],['group_num',$group]])->limit(20)->get();
                                               
                                    for($i=0;$i<count($question);$i++){
                        
                                    $question[$i]['answers']=Answer::select('id as value','answer')
                                         ->where('question_id',0)->get();
                                         
                                         
                                   }
                              }
                         }else{
                             
                                      $question=Test_questions::select('test_questions.id','test_questions.question')
                               
                                     ->where([['test_questions.test_id','=',$test],['group_num',$group]])->limit(20)->get();
                                               
                                    for($i=0;$i<count($question);$i++){
                        
                                    $question[$i]['answers']=Answer::select('id as value','answer')
                                         ->where('question_id',0)->get();
                                         
                                         
                                       } 
                                     
                             
                         }
                         
            
                          if(count($question)>0){
                               $message['data']=$question;
                               $message['error']=0;
                               $message['message']='show data success';
                           }else{
                               $message['data']=$question;
                               $message['error']=1;
                               $message['message']='error in show data';
                               
                           }
            
            
                        
                }elseif($request->has('user_token') && $request->has('report_id')){
                    
                      $data=Reports::where('id',$report)->first();
                      $examiner=User::where('user_token',$token)->first();
                      
                      
                      $check=Test_answer::where([['user_id',$data['user_id']],['examiner_id',$examiner['id']],['test_type',2],['test_id',$test],['group_num',$group]])->orderBy('id','DESC')->first();
                      
                      
                        if($check !=null){
                            
                               $count_data=Test_answer::where([['user_id',$data['user_id']],['examiner_id',$examiner['id']],['test_type',2],['test_id',$test],['group_num',$group]])->count();
                         
                              if($check['question_id'] >=223 && $check['question_id'] < 239){
                                  
                                  return ' move to next';
                                   $message['error']=3;
                                   $message['message']='you finished this exam move to next exam';
                              
                              }elseif($check['question_id'] >= 239){
                                  
                                      $question=Test_questions::select('test_questions.id','test_questions.question')
                               
                                     ->where([['test_questions.test_id','=',$test],['group_num',$group]])->limit(20)->get();
                                               
                                    for($i=0;$i<count($question);$i++){
                        
                                    $question[$i]['answers']=Answer::select('id as value','answer')
                                         ->where('question_id',0)->get();
                                         
                                         
                                       } 
                              }else{
                         
                         
                                  $question=Test_questions::select('test_questions.id','test_questions.question')
                               
                                     ->where([['test_questions.id','>',$check['question_id']],['test_id',$test],['group_num',$group]])->limit(20)->get();
                                               
                                    for($i=0;$i<count($question);$i++){
                        
                                    $question[$i]['answers']=Answer::select('id as value','answer')
                                         ->where('question_id',0)->get();
                                         
                                         
                                   }
                              }
                         }else{
                             
                                      $question=Test_questions::select('test_questions.id','test_questions.question')
                               
                                     ->where([['test_questions.test_id','=',$test],['group_num',$group]])->limit(20)->get();
                                               
                                    for($i=0;$i<count($question);$i++){
                        
                                    $question[$i]['answers']=Answer::select('id as value','answer')
                                         ->where('question_id',0)->get();
                                         
                                         
                                       } 
                                     
                             
                         }
                    
                         if(count($question)>0){
                               $message['data']=$question;
                               $message['error']=0;
                               $message['message']='show data success';
                           }else{
                               $message['data']=$question;
                               $message['error']=1;
                               $message['message']='error in show data';
                               
                           }

                }elseif(!$request->has('user_token') && $request->has('report_id')){
                    
                    
                    
                      $data=Reports::where('id',$report)->first();
                      
                      
                     $check=Test_answer::where([['user_id',$data['user_id']],['test_type',2],['test_id',$test],['group_num',$group]])->orderBy('id','DESC')->first();

                       if($check !=null){
                           
                           
                         
                               $count_data=Test_answer::where([['user_id',$data['user_id']],['test_type',2],['test_id',$test],['group_num',$group]])->count();
                         
                               if($check['question_id'] >=223 && $check['question_id'] < 239){
                                  
                                  return ' move to next';
                                   $message['error']=3;
                                   $message['message']='you finished this exam move to next exam';
                              
                              }elseif($check['question_id'] >= 239){
                                  
                                      $question=Test_questions::select('test_questions.id','test_questions.question')
                               
                                     ->where([['test_questions.test_id','=',$test],['group_num',$group]])->limit(20)->get();
                                               
                                    for($i=0;$i<count($question);$i++){
                        
                                    $question[$i]['answers']=Answer::select('id as value','answer')
                                         ->where('question_id',0)->get();
                                         
                                         
                                       } 
                              
                              }else{
                                  $question=Test_questions::select('test_questions.id','test_questions.question')
                               
                                     ->where([['test_questions.id','>',$check['question_id']],['test_id',$test],['group_num',$group]])->limit(20)->get();
                                               
                                    for($i=0;$i<count($question);$i++){
                        
                                    $question[$i]['answers']=Answer::select('id as value','answer')
                                         ->where('question_id',0)->get();
                                         
                                         
                                   }
                              }
                         }else{
                             
                                      $question=Test_questions::select('test_questions.id','test_questions.question')
                               
                                     ->where([['test_questions.test_id','=',$test],['group_num',$group]])->limit(20)->get();
                                               
                                    for($i=0;$i<count($question);$i++){
                        
                                    $question[$i]['answers']=Answer::select('id as value','answer')
                                         ->where('question_id',0)->get();
                                         
                                         
                                       } 
                                     
                             
                         }
                             if(count($question)>0){
                               $message['data']=$question;
                               $message['error']=0;
                               $message['message']='show data success';
                           }else{
                               $message['data']=$question;
                               $message['error']=1;
                               $message['message']='error in show data';
                               
                           }
                      
                      
                }
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
        }

         return response()->json($message);

     }


   public function show_digram(Request $request){
       
       

        $id=$request->input('report_id');
      $table_p=array();
      $table_p=[];
        $test_id=Reports::where('id',$id)->value('test_category');
        $table_name=test_code::select('name')->whereIn('id',[1,2,3,4,5,6])->get();

         $test=SubCategory::where('id',$test_id)->first();
          $user_data=Reports::select('name','birth_date','examiner_name','created_at','report_name','second_tool')->where('id',$id)->get();
          $description=$test['instructions'];

     // $select=Test_answer::join('test_code as cat','test_answer.cat_id','=','cat.id')
    // -> join('test_code as sub','test_answer.sub_id','=','sub.id')
    // ->where([['cat.main',0],['sub.main','!=',0],['user_id',$user]])
    
    $user=Reports::where('id',$id)->value('user_id');
      $dd_arr=array();

         for($i=1;$i<=6;$i++){
      ///A
      $A=Test_answer::where([['cat_id',$i],['user_id', $user]])->count();//a count
        //    echo 'A ='.$A .'<br>';

      $a_answered=Test_answer::where([['cat_id',$i],['user_id', $user],['answer','!=',NULL]])->count();

      //  echo 'a answerd ='.$a_answered .'<br>';

        $a_accept=Test_answer::where([['cat_id',$i],['user_id', $user],['answer','=',1]])->count();

       //   echo 'a_accept ='.$a_accept .'<br>';

         $a_refused=Test_answer::where([['cat_id',$i],['user_id', $user],['answer','=',2]])->count();

       //    echo 'a_refused ='.$a_refused .'<br>';

          $a_dont=Test_answer::where([['cat_id',$i],['user_id', $user],['answer','=',3]])->count();

       //     echo 'a_dont ='.$a_dont .'<br>';


$total_dont=$a_answered - $a_dont;

 //echo 'total - dont ='.$total_dont .'<br>';

 if($total_dont !=0){
          $accept_precentage=($a_accept/$total_dont)*100;
 }else{
 	$accept_precentage=0;
 }


      //    echo $accept_precentage .'<br>';

$select1=test_code::where('id',$i)->value('code');
  $dd_arr[$select1]=$accept_precentage;


  //for($m=0;$m<count($table_name);$m++){
      
       $table_name[$i-1]['degree']=$accept_precentage;
  //}

 

       for($j=7;$j<=43;$j++){
      ///A
      $A=Test_answer::where([['cat_id',$i],['sub_id',$j],['user_id', $user]])->count();//a count
       //     echo 'A ='.$A .'<br>';

      $a_answered=Test_answer::where([['cat_id',$i],['sub_id',$j],['user_id', $user],['answer','!=',NULL]])->count();

      //  echo 'a answerd ='.$a_answered .'<br>';

        $a_accept=Test_answer::where([['cat_id',$i],['sub_id',$j],['user_id', $user],['answer','=',1]])->count();

        //  echo 'a_accept ='.$a_accept .'<br>';

         $a_refused=Test_answer::where([['cat_id',$i],['sub_id',$j],['user_id', $user],['answer','=',2]])->count();

         //  echo 'a_refused ='.$a_refused .'<br>';

          $a_dont=Test_answer::where([['cat_id',$i],['sub_id',$j],['user_id', $user],['answer','=',3]])->count();

        //    echo 'a_dont ='.$a_dont .'<br>';


$total_dont=$a_answered - $a_dont;

// echo 'total - dont ='.$total_dont .'<br>';

    if($total_dont !=0){
          $accept_precentage=($a_accept/$total_dont)*100;

      }else{
      	  $accept_precentage=0;
      }

    //      echo $accept_precentage .'<br>';

        $select2=test_code::where([['main',$i],['id',$j]])->value('code');

                    if($select2 !=null){

          $my_arr[$select1][$select2]=$accept_precentage;

}

        }
        }
            $new_ayy=array();
                           arsort($dd_arr);
                        $new_ayy=array_filter($dd_arr, function($v, $k) {
                            return $v >= 75;
                        }, ARRAY_FILTER_USE_BOTH);
                        
                        if(count($new_ayy) >2){
                        
                           $final=$new_ayy;
                           
                        }elseif(count($new_ayy)<2){
                            
                           
                            
                            $new_ayy=array_slice($dd_arr,0,2);
                            
                             $final=$new_ayy;
                            
                        }else{
                           
                            
                            $final=array_slice($dd_arr,0,2);
                            
                             
                        }
                        $code='';
                        
                       // foreach($final as $key =>$val){
                            
                           // $code .="-".$key;
                            $code_description=test_code::select('name','mission')->whereIn('code',array_keys($final))->get();
                           // $code_description[$key]=test_code::where('code',$key)->value('mission');
                           //  $characteristic[$key]=test_code::where('code',$key)->value('characteristic');
                            
                       // }
                        
                        
                        
                        
                               
                             $job_description=array();
                             $characteristic=array();
                        
                        foreach($my_arr as $key=>$val){
                            
                           

                            foreach($val as $ky=>$vl){
                                
                               
                            if($vl >=60){    
                                
                               // $job=test_code::where('code',$ky)->value('mission');
                                
                            $job_description[]=test_code::select('mission')->where('code',$ky)->first();

                             // array_push($job_description,$job);
                              
                        //      $car=test_code::where('code',$ky)->value('characteristic');
                               $characteristic[]=test_code::select('characteristic')->where('code',$ky)->first();
                               //array_push($characteristic,$car);
                            }
                             
                            }
                            
                        }
                        
                        

      
        $mm=array();
        $my_jobs=array();
        
        $suitable_jobs=Test_answer::where([['test_answer.user_id',$user],['test_answer.test_id',$test_id],['test_answer.group_num',2]])
        
                      ->get();
                      
                      for($i=0;$i<16;$i++){
                          
                          $answers=Answer::where('answers.id',$suitable_jobs[$i]->answer)->pluck('code')->toArray();
                          
                          $answers=implode($answers);
                         
                            $mm=explode(',',$answers);
                          
                          
                        //  $my_jobs=Answer::where('answers.id',$suitable_jobs[$i]->answer)->value('answer');

                          
                           foreach($final as $key=>$val){
                               
                         

                                if(in_array($key,$mm)){
                                    

                                   $my_jobs[]=Answer::select('answer')->where('answers.id',$suitable_jobs[$i]->answer)->first();

                                
                                   
                                  }
                               

                           }
                      }
                      
                      
                       //  if(empty($my_jobs)){     $my_jobs=array();  }
                         
                         
                         
       $suggestions=$test['suggestions'];
                                
//$job_description=["gjhf jhgds fjgjhgdsfh dsg hghdsfjsdfdsfjsdfs","dsfdsfsdfdsfsfdsfsfsdfdsfdsfsdfsdfsdf","dsfdsfdsfsdfsdsd fdg ggggsgdgsdgsgsg"];
//$characteristic=["gjhf jhgds fjgjhgdsfh dsg hghdsfjsdfdsfjsdfs","dsfdsfsdfdsfsfdsfsfsdfdsfdsfsdfsdfsdf","dsfdsfdsfsdfsdsd fdg ggggsgdgsdgsgsg"];

//$price = array_column($inventory, 'price');

//array_multisort($price, SORT_DESC, $inventory);

  $message['user_data']=$user_data;
  $message['description']=$description;
  $message['table_names']=$table_name;
  //$message['table_degree']=$table_p;
 //  $message['data']=$dd_arr;
   $message['code_description']=$code_description;
   // $message['mydata']=$my_arr;
    $message['missions_jobs']=$job_description;
    $message['characterictic']=$characteristic;
    $message['suggestions']=$suggestions;
    $message['suitable_job']=$my_jobs;


   return response()->json($message);
   }



   public function answer_atest22(Request $request){
       
       
       $message=array();
       
            //  $list_arr_data= $request->json()->all( ); 

              $token=$request->input('user_token');
              $test=$request->input('test_id');
              $report=$request->input('report_id');
              $group=$request->input('group_number');
              $id=$request->input('id');
              $type=$request->input('test_type'); //1//2

                $list=$request->input('list'); 
                
                
                $list_arr=json_decode($request->input('list'),true);
                
                
               $updated_at = carbon::now()->toDateTimeString();
               $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($updated_at)));


      
          if($type ==1){
                     
                     
                       $my_arr=array();
                     
                      if($request->has('user_token')){
                          
                         $data=User::where('user_token',$token)->first();
                         
                         
                         $check=Test_answer::where([['user_id',$data['id']],['test_type',1],['test_id',$test],['group_num',$group]])->get();
                         
                         
                         if(count($check) >0 &&  count($check) >=223){
                             
                               $delete=Test_answer::where([['user_id',$data['id']],['test_type',1],['test_id',$test],['group_num',$group]])->delete();
                               
                               $report=Reports::where([['user_id',$data['id']],['test_category',$test]])->value('id');
                               
                               	 for($i=0;$i<count($list_arr);$i++){
                	      	 	$quest=Test_questions::where([['test_id','=',$test],['group_num',$group]])->get();
                	      	 	
                	      	  
                        		 	
                        		    $my_arr[]=array('user_id'=>$data['id'],'question_id'=>$list_arr[$i]['id'],'answer'=>$list_arr[$i]['answer'],'cat_id'=>$quest[$i]['cat_id'],'sub_id'=>$quest[$i]['sub_id'],'test_id'=>$test,'group_num'=>$group,'test_type'=>$type,'created_at'=>$dateTime);
                        
                        		 }
                             
                                 $insert=Test_answer::insert($my_arr);
                                 
                                  if($insert ==true){
                                  
                                 $message['data']=$report;
                                 $message['error']=0;
                                 $message['message']='insert success';
                         
                              }else{
                               
                                  $message['error']=1;
                                  $message['message']='error in insert';
                    
                              }
                                 
                         }else{
                             
                             	 for($i=0;$i<count($list_arr);$i++){
                	      	 	$quest=Test_questions::where([['test_id','=',$test],['group_num',$group]])->get();
                        		    $my_arr[]=array('user_id'=>$data['id'],'question_id'=>$list_arr[$i]['id'],'answer'=>$list_arr[$i]['answer'],'cat_id'=>$quest[$i]['cat_id'],'sub_id'=>$quest[$i]['sub_id'],'test_id'=>$test,'group_num'=>$group,'test_type'=>$type,'created_at'=>$dateTime);
                        
                        		 }
                     
                                    $insert=Test_answer::insert($my_arr);
                         
                         
                                     $test_data=SubCategory::where('id',$test)->first();
                                  if($insert ==true){
                                  
                                  
                                    if(count($check) >=223  || count($check) ==0){
                                        
                                     $insert=new Reports;
                                     $insert->user_id=$data['id'];
                                     $insert->report_name=$test_data['name'];
                                     $insert->name=$data['first_name'];
                                     $insert->birth_date=$data['birth_date'];
                                     $insert->second_tool='مقياس الوظائف والمهن';
                                     $insert->test_type='1';
                                     $insert->test_category=$test;
                                     $insert->type=4;
                                     $insert->nationality=$data['nationality'];
                                    // $insert->main_hand=$hand;
                                     $insert->gender=$data['gender'];
                                     $insert->institution=$data['institution_id'];
                                     $insert->app_date=$dateTime;
                                     $insert->educational_level=$data['level'];
                                     $insert->created_at=$dateTime;
                                     $insert->save();
                                     
                                     $report_id=$insert->id;
                                    }else{
                                         $report=Reports::where([['user_id',$data['id']],['test_category',$test]])->value('id');
                                         
                                          $report_id=$report;
                                        
                                    }
                                     
                                     
                                 $message['data']=$report_id;
                                 $message['error']=0;
                                 $message['message']='insert success';
                         
                              }else{
                               
                                  $message['error']=1;
                                  $message['message']='error in insert';
                    
                              }
                             
                         }
                               
                	
                         
                        
                          
                          
                          
                     
                        
                         
                        
               
                 }else{
                     
                     // insert  test without check if exist
                     
                      if($request->has('report_id')){
                     
                        $data=Reports::where('id',$report)->first();
                               
                		 for($i=0;$i<count($list_arr);$i++){
                	      	 	$quest=Test_questions::where([['test_id','=',$test],['group_num',$group]])->get();
                		    $my_arr[]=array('user_id'=>$data['user_id'],'question_id'=>$list_arr[$i]['id'],'test_type'=>$type,'answer'=>$list_arr[$i]['answer'],'cat_id'=>$quest[$i]['cat_id'],'sub_id'=>$quest[$i]['sub_id'],'test_id'=>$test,'group_num'=>$group,'created_at'=>$dateTime);
                
                		 }
                     
                         $insert=Test_answer::insert($my_arr);
                         
                       //   $test_data=SubCategory::where('id',$test)->first();
                          
                     
                                if($insert ==true ){
                                 $message['data']=$report;
                                 $message['error']=0;
                                 $message['message']='insert success';
                         
                              }else{
                               
                                  $message['error']=1;
                                  $message['message']='error in insert';
                    
                              }
                       
                          
                         }
                                 
            
                     
                     
                 }
                
                
                
                
            }else{
                //type =2 
                
                
                if($request->has('user_token')&& $request->has('id')){
                    
                         $data=User::where('id',$id)->first();
                         
                         $examiner=User::where('user_token',$token)->first();
                         
                      $check=Test_answer::where([['user_id',$id],['examiner_id',$examiner['id']],['test_type',2],['test_id',$test],['group_num',$group]])->get();
                      
                      
                     if(count($check)>0 && count($check)>=223){
                         
                           $delete=Test_answer::where([['user_id',$id],['test_id',$test],['test_type',2],['group_num',$group],['examiner_id',$examiner['id']]])->delete();
                               
                               $report=Reports::where([['user_id',$id],['examiner_id',$examiner['id']],['test_category',$test]])->value('id');
                               
                               	 for($i=0;$i<count($list_arr);$i++){
                	      	 	$quest=Test_questions::where([['test_id','=',$test],['group_num',$group]])->get();
                        		    $my_arr[]=array('user_id'=>$id,'question_id'=>$list_arr[$i]['id'],'test_type'=>2,'examiner_id'=>$examiner['id'],'answer'=>$list_arr[$i]['answer'],'cat_id'=>$quest[$i]['cat_id'],'sub_id'=>$quest[$i]['sub_id'],'test_id'=>$test,'group_num'=>$group,'created_at'=>$dateTime);
                        
                        		 }
                             
                                 $insert=Test_answer::insert($my_arr);
                                 
                                  if($insert ==true){
                                  
                                 $message['data']=$report;
                                 $message['error']=0;
                                 $message['message']='insert success';
                         
                              }else{
                               
                                  $message['error']=1;
                                  $message['message']='error in insert';
                    
                              }
                     }else{
                         
                         
                        	 for($i=0;$i<count($list_arr);$i++){
                	      	 	$quest=Test_questions::where([['test_id','=',$test],['group_num',$group]])->first();
                		    $my_arr[]=array('user_id'=>$data['id'],'question_id'=>$list_arr[$i]['id'],'answer'=>$list_arr[$i]['answer'],'examiner_id'=>$examiner['id'],'test_type'=>2,'cat_id'=>$quest[$i]['cat_id'],'sub_id'=>$quest[$i]['sub_id'],'test_id'=>$test,'group_num'=>$group,'created_at'=>$dateTime);
                
                	      	 }
                     
                         $insert=Test_answer::insert($my_arr);
                         
                          $test_data=SubCategory::where('id',$id)->first();
                          
                          

                         
                         
                             if($insert ==true ){
                                 
                                   if(count($check) >=223  || count($check) ==0){
                                         $insert=new Reports;
                                         $insert->user_id=$data['id'];
                                         $insert->report_name=$test_data['name'];
                                         $insert->name=$data['first_name'];
                                         $insert->birth_date=$data['birth_date'];
                                         $insert->examiner_id=$examiner['id'];
                                         $insert->examiner_name=$examiner['first_name'];
                                         $insert->test_type=2;
                                         $insert->test_category=$test;
                                         $insert->type=4;
                                         $insert->nationality=$data['nationality'];
                                       
                                         $insert->gender=$data['gender'];
                                         $insert->institution=$data['institution_id'];
                                         $insert->app_date=$dateTime;
                                         $insert->educational_level=$data['level'];
                                         $insert->created_at=$dateTime;
                                         $insert->save();
                                         
                                         $report_id=$insert->id;
                                   }else{
                                       
                                       $report=Reports::where([['user_id',$data['id']],['examiner_id',$examiner['id']],['test_category',$test]])->value('id');
                                         
                                          $report_id=$report;
                                        
                                   }
                                 
                                 
                                 $message['data']=$report_id;
                                 $message['error']=0;
                                 $message['message']='insert success';
                         
                              }else{
                               
                                  $message['error']=1;
                                  $message['message']='error in insert';
                    
                              }
                     
                     }
                               
                	   
                    
                       
                    
                    
                }elseif($request->has('user_token') && $request->has('report_id')){
                    
                 
                      $data=Reports::where('id',$report)->first();
                      $examiner=User::where('user_token',$token)->first();
                      
                      
                      $check=Test_answer::where([['user_id',$data['user_id']],['examiner_id',$examiner['id']],['test_type',2],['test_id',$test],['group_num',$group]])->get();
                      
                      
                      
                      
                      if(count($check )>0 && count($check)>=223){
                          
                      $delete=Test_answer::where([['user_id',$data['user_id']],['examiner_id',$examiner['id']],['test_type',2],['test_id',$test],['group_num',$group]])->delete();
 
                         $report=Reports::where([['user_id',$data['user_id']],['examiner_id',$examiner['id']],['test_category',$test]])->value('id');
                               
                               	 for($i=0;$i<count($list_arr);$i++){
                	      	 	$quest=Test_questions::where([['test_id','=',$test],['group_num',$group]])->first();
                        		    $my_arr[]=array('user_id'=>$data['id'],'question_id'=>$list_arr[$i]['id'],'test_type'=>2,'examiner_id'=>$examiner['id'],'answer'=>$list_arr[$i]['answer'],'cat_id'=>$quest[$i]['cat_id'],'sub_id'=>$quest[$i]['sub_id'],'test_id'=>$test,'group_num'=>$group,'created_at'=>$dateTime);
                        
                        		 }
                             
                                 $insert=Test_answer::insert($my_arr);
                                 
                                  if($insert ==true){
                                  
                                 $message['data']=$report;
                                 $message['error']=0;
                                 $message['message']='insert success';
                         
                              }else{
                               
                                  $message['error']=1;
                                  $message['message']='error in insert';
                    
                              }
                          
                          
                          
                      }else{
                          
                          
                          	 for($i=0;$i<count($list_arr);$i++){
                	      	 	$quest=Test_questions::where([['test_id','=',$test],['group_num',$group]])->first();
                		    $my_arr[]=array('user_id'=>$data['user_id'],'question_id'=>$list_arr[$i]['id'],'answer'=>$list_arr[$i]['answer'],'examiner_id'=>$examiner['id'],'test_type'=>2,'cat_id'=>$quest[$i]['cat_id'],'sub_id'=>$quest[$i]['sub_id'],'test_id'=>$test,'group_num'=>$group,'created_at'=>$dateTime);
                
                		 }
                     
                         $insert=Test_answer::insert($my_arr);
                         
                              if($insert ==true){
                                 $message['data']=$report;
                                 $message['error']=0;
                                 $message['message']='insert success';
                         
                              }else{
                               
                                  $message['error']=1;
                                  $message['message']='error in insert';
                    
                              }

                          
                          
                          
                      }

                               
                	
                    
                    
                    
                }elseif(!$request->has('user_token') && $request->has('report_id')){
                    
                 
                      $data=Reports::where('id',$report)->first();

                               
                		 for($i=0;$i<count($list_arr);$i++){
                		 	$quest=Test_questions::where([['test_id','=',$test],['group_num',$group]])->first();
                		    $my_arr[]=array('user_id'=>$data['user_id'],'question_id'=>$list_arr[$i]['id'],'answer'=>$list_arr[$i]['answer'],'test_type'=>2,'cat_id'=>$quest[$i]['cat_id'],'sub_id'=>$quest[$i]['sub_id'],'test_id'=>$test,'group_num'=>$group,'created_at'=>$dateTime);
                
                		 }
                     
                         $insert=Test_answer::insert($my_arr);
                         
                              if($insert ==true){
                                 $message['data']=$report;
                                 $message['error']=0;
                                 $message['message']='insert success';
                         
                              }else{
                               
                                  $message['error']=1;
                                  $message['message']='error in insert';
                    
                              }

                    
                    
                    
                }
                
                
                
                
                
            }
      

    return response()->json($message);


   }



public function answer_atest(Request $request){
       
       
       $message=array();
       
              $list_data= $request->json()->all( ); 
            

              $token=$list_data['user_token'];
              $test=$list_data['test_id'];
              $report=$list_data['report_id'];
              $group=$list_data['group_number'];
              $id=$list_data['id'];
              $type=$list_data['test_type']; //1//2

               // $list=$list_arr['list']; [{'id':1,"answer":},{"id":,"answer":}]
                
               $list_arr=$list_data['list'];
                
                
               $updated_at = carbon::now()->toDateTimeString();
               $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($updated_at)));


      
          if($type ==1){
                     
                     
                       $my_arr=array();
                     
                      if(!empty($list_data['user_token'])){
                          
                         $data=User::where('user_token',$token)->first();
                         
                         
                         $check=Test_answer::where([['user_id',$data['id']],['test_type',1],['test_id',$test],['group_num',$group]])->first();
                         
                         
                         if($check !=null){
                             
                               $delete=Test_answer::where([['user_id',$data['id']],['test_type',1],['test_id',$test],['group_num',$group]])->delete();
                               
                               $report=Reports::where([['user_id',$data['id']],['test_category',$test]])->value('id');
                               
                               	 for($i=0;$i<count($list_arr);$i++){
                	      	 	$quest=Test_questions::where([['test_id','=',$test],['group_num',$group]])->get();
                	      	 	
                	      	  
                        		 	
                        		    $my_arr[]=array('user_id'=>$data['id'],'question_id'=>$list_arr[$i]['id'],'answer'=>$list_arr[$i]['answer'],'cat_id'=>$quest[$i]['cat_id'],'sub_id'=>$quest[$i]['sub_id'],'test_id'=>$test,'group_num'=>$group,'test_type'=>$type,'created_at'=>$dateTime);
                        
                        		 }
                             
                                 $insert=Test_answer::insert($my_arr);
                                 
                                  if($insert ==true){
                                  
                                 $message['data']=$report;
                                 $message['error']=0;
                                 $message['message']='insert success';
                         
                              }else{
                               
                                  $message['error']=1;
                                  $message['message']='error in insert';
                    
                              }
                                 
                         }else{
                             
                             	 for($i=0;$i<count($list_arr);$i++){
                	      	 	$quest=Test_questions::where([['test_id','=',$test],['group_num',$group]])->get();
                        		    $my_arr[]=array('user_id'=>$data['id'],'question_id'=>$list_arr[$i]['id'],'answer'=>$list_arr[$i]['answer'],'cat_id'=>$quest[$i]['cat_id'],'sub_id'=>$quest[$i]['sub_id'],'test_id'=>$test,'group_num'=>$group,'test_type'=>$type,'created_at'=>$dateTime);
                        
                        		 }
                     
                         $insert=Test_answer::insert($my_arr);
                         
                         
                                     $test_data=SubCategory::where('id',$test)->first();
                                  if($insert ==true){
                                  
                                  
                                    if($group ==1){
                                     $insert=new Reports;
                                     $insert->user_id=$data['id'];
                                     $insert->report_name=$test_data['name'];
                                     $insert->name=$data['first_name'];
                                     $insert->birth_date=$data['birth_date'];
                                     $insert->second_tool='مقياس الوظائف والمهن';
                                     $insert->test_type='1';
                                     $insert->test_category=$test;
                                     $insert->type=4;
                                     $insert->nationality=$data['nationality'];
                                    // $insert->main_hand=$hand;
                                     $insert->gender=$data['gender'];
                                     $insert->institution=$data['institution_id'];
                                     $insert->app_date=$dateTime;
                                     $insert->educational_level=$data['level'];
                                     $insert->created_at=$dateTime;
                                     $insert->save();
                                     
                                     $report_id=$insert->id;
                                    }else{
                                         $report=Reports::where([['user_id',$data['id']],['test_category',$test]])->value('id');
                                         
                                          $report_id=$report;
                                        
                                    }
                                     
                                     
                                 $message['data']=$report_id;
                                 $message['error']=0;
                                 $message['message']='insert success';
                         
                              }else{
                               
                                  $message['error']=1;
                                  $message['message']='error in insert';
                    
                              }
                             
                         }
                               
                	
                         
                        
                          
                          
                          
                     
                        
                         
                        
               
                 }else{
                     
                     // insert  test without check if exist
                     
                      if(!empty($list_data['report_id'])){
                     
                        $data=Reports::where('id',$report)->first();
                               
                		 for($i=0;$i<count($list_arr);$i++){
                	      	 	$quest=Test_questions::where([['test_id','=',$test],['group_num',$group]])->get();
                		    $my_arr[]=array('user_id'=>$data['user_id'],'question_id'=>$list_arr[$i]['id'],'test_type'=>$type,'answer'=>$list_arr[$i]['answer']['answer'],'cat_id'=>$quest[$i]['cat_id'],'sub_id'=>$quest[$i]['sub_id'],'test_id'=>$test,'group_num'=>$group,'created_at'=>$dateTime);
                
                		 }
                     
                         $insert=Test_answer::insert($my_arr);
                         
                       //   $test_data=SubCategory::where('id',$test)->first();
                          
                     
                                if($insert ==true ){
                                 $message['data']=$report;
                                 $message['error']=0;
                                 $message['message']='insert success';
                         
                              }else{
                               
                                  $message['error']=1;
                                  $message['message']='error in insert';
                    
                              }
                       
                          
                         }
                                 
            
                     
                     
                 }
                
                
                
                
            }else{
                //type =2 
                
                
                if(!empty($list_data['user_token']) && !empty($list_data['id'])){
                    
                         $data=User::where('id',$id)->first();
                         
                         $examiner=User::where('user_token',$token)->first();
                         
                      $check=Test_answer::where([['user_id',$id],['examiner_id',$examiner['id']],['test_type',2],['test_id',$test],['group_num',$group]])->first();
                      
                      
                     if($check !=null){
                         
                           $delete=Test_answer::where([['user_id',$id],['test_id',$test],['test_type',2],['group_num',$group],['examiner_id',$examiner['id']]])->delete();
                               
                               $report=Reports::where([['user_id',$id],['examiner_id',$examiner['id']],['test_category',$test]])->value('id');
                               
                               	 for($i=0;$i<count($list_arr);$i++){
                	      	 	$quest=Test_questions::where([['test_id','=',$test],['group_num',$group]])->get();
                        		    $my_arr[]=array('user_id'=>$id,'question_id'=>$list_arr[$i]['id'],'test_type'=>2,'examiner_id'=>$examiner['id'],'answer'=>$list_arr[$i]['answer']['answer'],'cat_id'=>$quest[$i]['cat_id'],'sub_id'=>$quest[$i]['sub_id'],'test_id'=>$test,'group_num'=>$group,'created_at'=>$dateTime);
                        
                        		 }
                             
                                 $insert=Test_answer::insert($my_arr);
                                 
                                  if($insert ==true){
                                  
                                 $message['data']=$report;
                                 $message['error']=0;
                                 $message['message']='insert success';
                         
                              }else{
                               
                                  $message['error']=1;
                                  $message['message']='error in insert';
                    
                              }
                     }else{
                         
                         
                        	 for($i=0;$i<count($list_arr);$i++){
                	      	 	$quest=Test_questions::where([['test_id','=',$test],['group_num',$group]])->first();
                		    $my_arr[]=array('user_id'=>$data['id'],'question_id'=>$list_arr[$i]['id'],'answer'=>$list_arr[$i]['answer']['answer'],'examiner_id'=>$examiner['id'],'test_type'=>2,'cat_id'=>$quest[$i]['cat_id'],'sub_id'=>$quest[$i]['sub_id'],'test_id'=>$test,'group_num'=>$group,'created_at'=>$dateTime);
                
                	      	 }
                     
                         $insert=Test_answer::insert($my_arr);
                         
                          $test_data=SubCategory::where('id',$id)->first();
                          
                          

                         
                         
                             if($insert ==true ){
                                 
                                   if($group ==1){
                                         $insert=new Reports;
                                         $insert->user_id=$data['id'];
                                         $insert->report_name=$test_data['name'];
                                         $insert->name=$data['first_name'];
                                         $insert->birth_date=$data['birth_date'];
                                         $insert->examiner_id=$examiner['id'];
                                         $insert->examiner_name=$examiner['first_name'];
                                         $insert->test_type=2;
                                         $insert->test_category=$test;
                                         $insert->type=4;
                                         $insert->nationality=$data['nationality'];
                                       
                                         $insert->gender=$data['gender'];
                                         $insert->institution=$data['institution_id'];
                                         $insert->app_date=$dateTime;
                                         $insert->educational_level=$data['level'];
                                         $insert->created_at=$dateTime;
                                         $insert->save();
                                         
                                         $report_id=$insert->id;
                                   }else{
                                       
                                       $report=Reports::where([['user_id',$data['id']],['examiner_id',$examiner['id']],['test_category',$test]])->value('id');
                                         
                                          $report_id=$report;
                                        
                                   }
                                 
                                 
                                 $message['data']=$report_id;
                                 $message['error']=0;
                                 $message['message']='insert success';
                         
                              }else{
                               
                                  $message['error']=1;
                                  $message['message']='error in insert';
                    
                              }
                     
                     }
                               
                	   
                    
                       
                    
                    
                }elseif(!empty($list_data['user_token']) &&  !empty($list_data['report_id'])){
                    
                 
                      $data=Reports::where('id',$report)->first();
                      $examiner=User::where('user_token',$token)->first();
                      
                      
                      $check=Test_answer::where([['user_id',$data['user_id']],['examiner_id',$examiner['id']],['test_type',2],['test_id',$test],['group_num',$group]])->first();
                      
                      
                      
                      
                      if($check !=null){
                          
                      $delete=Test_answer::where([['user_id',$data['user_id']],['examiner_id',$examiner['id']],['test_type',2],['test_id',$test],['group_num',$group]])->delete();
 
                         $report=Reports::where([['user_id',$data['user_id']],['examiner_id',$examiner['id']],['test_category',$test]])->value('id');
                               
                               	 for($i=0;$i<count($list_arr);$i++){
                	      	 	$quest=Test_questions::where([['test_id','=',$test],['group_num',$group]])->first();
                        		    $my_arr[]=array('user_id'=>$data['id'],'question_id'=>$list_arr[$i]['id'],'test_type'=>2,'examiner_id'=>$examiner['id'],'answer'=>$list_arr[$i]['answer']['answer'],'cat_id'=>$quest[$i]['cat_id'],'sub_id'=>$quest[$i]['sub_id'],'test_id'=>$test,'group_num'=>$group,'created_at'=>$dateTime);
                        
                        		 }
                             
                                 $insert=Test_answer::insert($my_arr);
                                 
                                  if($insert ==true){
                                  
                                 $message['data']=$report;
                                 $message['error']=0;
                                 $message['message']='insert success';
                         
                              }else{
                               
                                  $message['error']=1;
                                  $message['message']='error in insert';
                    
                              }
                          
                          
                          
                      }else{
                          
                          
                          	 for($i=0;$i<count($list_arr);$i++){
                	      	 	$quest=Test_questions::where([['test_id','=',$test],['group_num',$group]])->first();
                		    $my_arr[]=array('user_id'=>$data['user_id'],'question_id'=>$list_arr[$i]['id'],'answer'=>$list_arr[$i]['answer']['answer'],'examiner_id'=>$examiner['id'],'test_type'=>2,'cat_id'=>$quest[$i]['cat_id'],'sub_id'=>$quest[$i]['sub_id'],'test_id'=>$test,'group_num'=>$group,'created_at'=>$dateTime);
                
                		 }
                     
                         $insert=Test_answer::insert($my_arr);
                         
                              if($insert ==true){
                                 $message['data']=$report;
                                 $message['error']=0;
                                 $message['message']='insert success';
                         
                              }else{
                               
                                  $message['error']=1;
                                  $message['message']='error in insert';
                    
                              }

                          
                          
                          
                      }

                               
                	
                    
                    
                    
                }elseif(empty($list_data['user_token']) && empty($list_data['report_id'])){
                    
                 
                      $data=Reports::where('id',$report)->first();

                               
                		 for($i=0;$i<count($list_arr);$i++){
                		 	$quest=Test_questions::where([['test_id','=',$test],['group_num',$group]])->first();
                		    $my_arr[]=array('user_id'=>$data['user_id'],'question_id'=>$list_arr[$i]['id'],'answer'=>$list_arr[$i]['answer']['answer'],'test_type'=>2,'cat_id'=>$quest[$i]['cat_id'],'sub_id'=>$quest[$i]['sub_id'],'test_id'=>$type,'group_num'=>$group,'created_at'=>$dateTime);
                
                		 }
                     
                         $insert=Test_answer::insert($my_arr);
                         
                              if($insert ==true){
                                 $message['data']=$report;
                                 $message['error']=0;
                                 $message['message']='insert success';
                         
                              }else{
                               
                                  $message['error']=1;
                                  $message['message']='error in insert';
                    
                              }

                    
                    
                    
                }
                
                
                
                
                
            }
      

    return response()->json($message);


   }
   
   
   public function answer_Btest(Request $request){
       
       
       $message=array();
       
            //  $list_arr_data= $request->json()->all( ); 

              
             
              $report=$request->input('report_id');
              $group=2;
              
              

            $list=$request->input('list'); 
                
                
          $list_arr=json_decode($request->input('list'),true);
                
                

                
               $updated_at = carbon::now()->toDateTimeString();
               $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($updated_at)));


                  
                        $data=Reports::where('id',$report)->first();
                        
                $check=Test_answer::where([['user_id',$data['user_id']],['test_type',$data['test_type']],['test_id',$data['test_category']],['group_num',$group]])->get();

                              if(count($check)>0){
                                  
                              $delete=Test_answer::where([['user_id',$data['user_id']],['test_type',$data['test_type']],['test_id',$data['test_category']],['group_num',$group]])->delete();
                              
                              
                    for($i=0;$i<count($list_arr);$i++){
            $quest=Test_questions::where([['test_id','=',$data['test_category']],['group_num',$group]])->get();
            
            
          $my_arr[]=array('user_id'=>$data['user_id'],'question_id'=>$list_arr[$i]['id'],'test_type'=>$data['test_type'],'answer'=>$list_arr[$i]['answer'],'test_id'=>$data['test_category'],'group_num'=>$group,'created_at'=>$dateTime);
                        
                        		 }
                             
                                 $insert=Test_answer::insert($my_arr);
                                 
        
                             
                                        if($insert ==true ){
                                         $message['data']=$report;
                                         $message['error']=0;
                                         $message['message']='insert success';
                                 
                                      }else{
                                       
                                          $message['error']=1;
                                          $message['message']='error in insert';
                            
                                      }
                       

                                  
                              }else{
                                 for($i=0;$i<count($list_arr);$i++){
                	 $quest=Test_questions::where([['test_id','=',$data['test_category']],['group_num',$group]])->get();
            $my_arr[]=array('user_id'=>$data['user_id'],'question_id'=>$list_arr[$i]['id'],'test_type'=>$data['test_type'],'answer'=>$list_arr[$i]['answer'],'test_id'=>$data['test_category'],'group_num'=>$group,'created_at'=>$dateTime);
                
                		 }
                     
                         $insert=Test_answer::insert($my_arr);
                         

                     
                                if($insert ==true ){
                                 $message['data']=$report;
                                 $message['error']=0;
                                 $message['message']='insert success';
                         
                              }else{
                               
                                  $message['error']=1;
                                  $message['message']='error in insert';
                    
                              }
                       
   
                                  
                              }
                		                          
                         
                                 
            
                     
                     
                 
                
                
                
                
            
      

    return response()->json($message);


   }

/*

   public function answer_atest3(Request $request){
       
       
       $message=array();
       
            //  $list_arr_data= $request->json()->all( ); 

              $token=$request->input('user_token');
              $test=$request->input('test_id');
              $report=$request->input('report_id');
              $group=$request->input('group_number');
              $id=$request->input('id');
              $type=$request->input('test_type'); //1//2

                $list=$request->input('list'); 
                
                
                $list_arr=json_decode($request->input('list'));
                
                
               $updated_at = carbon::now()->toDateTimeString();
               $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($updated_at)));


      
          if($type ==1){
                     
                     
                       $my_arr=array();
                     
                      if($request->has('user_token')){
                          
                         $data=User::where('user_token',$token)->first();
                         
                         
                         $check=Test_answer::where([['user_id',$data['id']],['test_type',1],['test_id',$test],['group_num',$group]])->get();
                         
                         
                         if(count($check) >0 &&  count($check) >=223){
                             
                               $delete=Test_answer::where([['user_id',$data['id']],['test_type',1],['test_id',$test],['group_num',$group]])->delete();
                               
                               $report=Reports::where([['user_id',$data['id']],['test_category',$test]])->value('id');
                               
                               	 for($i=0;$i<count($list_arr);$i++){
                	      	 	$quest=Test_questions::where([['test_id','=',$test],['group_num',$group]])->get();
                	      	 	
                	      	  
                        		 	
                        		    $my_arr[]=array('user_id'=>$data['id'],'question_id'=>$list_arr[$i]['id'],'answer'=>$list_arr[$i]['answer'],'cat_id'=>$quest[$i]['cat_id'],'sub_id'=>$quest[$i]['sub_id'],'test_id'=>$test,'group_num'=>$group,'test_type'=>$type,'created_at'=>$dateTime);
                        
                        		 }
                             
                                 $insert=Test_answer::insert($my_arr);
                                 
                                  if($insert ==true){
                                  
                                 $message['data']=$report;
                                 $message['error']=0;
                                 $message['message']='insert success';
                         
                              }else{
                               
                                  $message['error']=1;
                                  $message['message']='error in insert';
                    
                              }
                                 
                         }else{
                             
                             	 for($i=0;$i<count($list_arr);$i++){
                	      	 	$quest=Test_questions::where([['test_id','=',$test],['group_num',$group]])->get();
                        		    $my_arr[]=array('user_id'=>$data['id'],'question_id'=>$list_arr[$i]['id'],'answer'=>$list_arr[$i]['answer'],'cat_id'=>$quest[$i]['cat_id'],'sub_id'=>$quest[$i]['sub_id'],'test_id'=>$test,'group_num'=>$group,'test_type'=>$type,'created_at'=>$dateTime);
                        
                        		 }
                     
                                    $insert=Test_answer::insert($my_arr);
                         
                         
                                     $test_data=SubCategory::where('id',$test)->first();
                                  if($insert ==true){
                                  
                                  
                                    if(count($check) >=223  || count($check) ==0){
                                        
                                     $insert=new Reports;
                                     $insert->user_id=$data['id'];
                                     $insert->report_name=$test_data['name'];
                                     $insert->name=$data['first_name'];
                                     $insert->birth_date=$data['birth_date'];
                                     $insert->second_tool='مقياس الوظائف والمهن';
                                     $insert->test_type='1';
                                     $insert->test_category=$test;
                                     $insert->type=4;
                                     $insert->nationality=$data['nationality'];
                                    // $insert->main_hand=$hand;
                                     $insert->gender=$data['gender'];
                                     $insert->institution=$data['institution_id'];
                                     $insert->app_date=$dateTime;
                                     $insert->educational_level=$data['level'];
                                     $insert->created_at=$dateTime;
                                     $insert->save();
                                     
                                     $report_id=$insert->id;
                                    }else{
                                         $report=Reports::where([['user_id',$data['id']],['test_category',$test]])->value('id');
                                         
                                          $report_id=$report;
                                        
                                    }
                                     
                                     
                                 $message['data']=$report_id;
                                 $message['error']=0;
                                 $message['message']='insert success';
                         
                              }else{
                               
                                  $message['error']=1;
                                  $message['message']='error in insert';
                    
                              }
                             
                         }
                               
                	
                         
                        
                          
                          
                          
                     
                        
                         
                        
               
                 }else{
                     
                     // insert  test without check if exist
                     
                      if($request->has('report_id')){
                     
                        $data=Reports::where('id',$report)->first();
                               
                		 for($i=0;$i<count($list_arr);$i++){
                	      	 	$quest=Test_questions::where([['test_id','=',$test],['group_num',$group]])->get();
                		    $my_arr[]=array('user_id'=>$data['user_id'],'question_id'=>$list_arr[$i]['id'],'test_type'=>$type,'answer'=>$list_arr[$i]['answer'],'cat_id'=>$quest[$i]['cat_id'],'sub_id'=>$quest[$i]['sub_id'],'test_id'=>$test,'group_num'=>$group,'created_at'=>$dateTime);
                
                		 }
                     
                         $insert=Test_answer::insert($my_arr);
                         
                       //   $test_data=SubCategory::where('id',$test)->first();
                          
                     
                                if($insert ==true ){
                                 $message['data']=$report;
                                 $message['error']=0;
                                 $message['message']='insert success';
                         
                              }else{
                               
                                  $message['error']=1;
                                  $message['message']='error in insert';
                    
                              }
                       
                          
                         }
                                 
            
                     
                     
                 }
                
                
                
                
            }else{
                //type =2 
                
                
                if($request->has('user_token')&& $request->has('id')){
                    
                         $data=User::where('id',$id)->first();
                         
                         $examiner=User::where('user_token',$token)->first();
                         
                      $check=Test_answer::where([['user_id',$id],['examiner_id',$examiner['id']],['test_type',2],['test_id',$test],['group_num',$group]])->get();
                      
                      
                     if(count($check)>0 && count($check)>=223){
                         
                           $delete=Test_answer::where([['user_id',$id],['test_id',$test],['test_type',2],['group_num',$group],['examiner_id',$examiner['id']]])->delete();
                               
                               $report=Reports::where([['user_id',$id],['examiner_id',$examiner['id']],['test_category',$test]])->value('id');
                               
                               	 for($i=0;$i<count($list_arr);$i++){
                	      	 	$quest=Test_questions::where([['test_id','=',$test],['group_num',$group]])->get();
                        		    $my_arr[]=array('user_id'=>$id,'question_id'=>$list_arr[$i]['id'],'test_type'=>2,'examiner_id'=>$examiner['id'],'answer'=>$list_arr[$i]['answer'],'cat_id'=>$quest[$i]['cat_id'],'sub_id'=>$quest[$i]['sub_id'],'test_id'=>$test,'group_num'=>$group,'created_at'=>$dateTime);
                        
                        		 }
                             
                                 $insert=Test_answer::insert($my_arr);
                                 
                                  if($insert ==true){
                                  
                                 $message['data']=$report;
                                 $message['error']=0;
                                 $message['message']='insert success';
                         
                              }else{
                               
                                  $message['error']=1;
                                  $message['message']='error in insert';
                    
                              }
                     }else{
                         
                         
                        	 for($i=0;$i<count($list_arr);$i++){
                	      	 	$quest=Test_questions::where([['test_id','=',$test],['group_num',$group]])->first();
                		    $my_arr[]=array('user_id'=>$data['id'],'question_id'=>$list_arr[$i]['id'],'answer'=>$list_arr[$i]['answer'],'examiner_id'=>$examiner['id'],'test_type'=>2,'cat_id'=>$quest[$i]['cat_id'],'sub_id'=>$quest[$i]['sub_id'],'test_id'=>$test,'group_num'=>$group,'created_at'=>$dateTime);
                
                	      	 }
                     
                         $insert=Test_answer::insert($my_arr);
                         
                          $test_data=SubCategory::where('id',$id)->first();
                          
                          

                         
                         
                             if($insert ==true ){
                                 
                                   if(count($check) >=223  || count($check) ==0){
                                         $insert=new Reports;
                                         $insert->user_id=$data['id'];
                                         $insert->report_name=$test_data['name'];
                                         $insert->name=$data['first_name'];
                                         $insert->birth_date=$data['birth_date'];
                                         $insert->examiner_id=$examiner['id'];
                                         $insert->examiner_name=$examiner['first_name'];
                                         $insert->test_type=2;
                                         $insert->test_category=$test;
                                         $insert->type=4;
                                         $insert->nationality=$data['nationality'];
                                       
                                         $insert->gender=$data['gender'];
                                         $insert->institution=$data['institution_id'];
                                         $insert->app_date=$dateTime;
                                         $insert->educational_level=$data['level'];
                                         $insert->created_at=$dateTime;
                                         $insert->save();
                                         
                                         $report_id=$insert->id;
                                   }else{
                                       
                                       $report=Reports::where([['user_id',$data['id']],['examiner_id',$examiner['id']],['test_category',$test]])->value('id');
                                         
                                          $report_id=$report;
                                        
                                   }
                                 
                                 
                                 $message['data']=$report_id;
                                 $message['error']=0;
                                 $message['message']='insert success';
                         
                              }else{
                               
                                  $message['error']=1;
                                  $message['message']='error in insert';
                    
                              }
                     
                     }
                               
                	   
                    
                       
                    
                    
                }elseif($request->has('user_token') && $request->has('report_id')){
                    
                 
                      $data=Reports::where('id',$report)->first();
                      $examiner=User::where('user_token',$token)->first();
                      
                      
                      $check=Test_answer::where([['user_id',$data['user_id']],['examiner_id',$examiner['id']],['test_type',2],['test_id',$test],['group_num',$group]])->get();
                      
                      
                      
                      
                      if(count($check )>0 && count($check)>=223){
                          
                      $delete=Test_answer::where([['user_id',$data['user_id']],['examiner_id',$examiner['id']],['test_type',2],['test_id',$test],['group_num',$group]])->delete();
 
                         $report=Reports::where([['user_id',$data['user_id']],['examiner_id',$examiner['id']],['test_category',$test]])->value('id');
                               
                               	 for($i=0;$i<count($list_arr);$i++){
                	      	 	$quest=Test_questions::where([['test_id','=',$test],['group_num',$group]])->first();
                        		    $my_arr[]=array('user_id'=>$data['id'],'question_id'=>$list_arr[$i]['id'],'test_type'=>2,'examiner_id'=>$examiner['id'],'answer'=>$list_arr[$i]['answer'],'cat_id'=>$quest[$i]['cat_id'],'sub_id'=>$quest[$i]['sub_id'],'test_id'=>$test,'group_num'=>$group,'created_at'=>$dateTime);
                        
                        		 }
                             
                                 $insert=Test_answer::insert($my_arr);
                                 
                                  if($insert ==true){
                                  
                                 $message['data']=$report;
                                 $message['error']=0;
                                 $message['message']='insert success';
                         
                              }else{
                               
                                  $message['error']=1;
                                  $message['message']='error in insert';
                    
                              }
                          
                          
                          
                      }else{
                          
                          
                          	 for($i=0;$i<count($list_arr);$i++){
                	      	 	$quest=Test_questions::where([['test_id','=',$test],['group_num',$group]])->first();
                		    $my_arr[]=array('user_id'=>$data['user_id'],'question_id'=>$list_arr[$i]['id'],'answer'=>$list_arr[$i]['answer'],'examiner_id'=>$examiner['id'],'test_type'=>2,'cat_id'=>$quest[$i]['cat_id'],'sub_id'=>$quest[$i]['sub_id'],'test_id'=>$test,'group_num'=>$group,'created_at'=>$dateTime);
                
                		 }
                     
                         $insert=Test_answer::insert($my_arr);
                         
                              if($insert ==true){
                                 $message['data']=$report;
                                 $message['error']=0;
                                 $message['message']='insert success';
                         
                              }else{
                               
                                  $message['error']=1;
                                  $message['message']='error in insert';
                    
                              }

                          
                          
                          
                      }

                               
                	
                    
                    
                    
                }elseif(!$request->has('user_token') && $request->has('report_id')){
                    
                 
                      $data=Reports::where('id',$report)->first();

                               
                		 for($i=0;$i<count($list_arr);$i++){
                		 	$quest=Test_questions::where([['test_id','=',$test],['group_num',$group]])->first();
                		    $my_arr[]=array('user_id'=>$data['user_id'],'question_id'=>$list_arr[$i]['id'],'answer'=>$list_arr[$i]['answer'],'test_type'=>2,'cat_id'=>$quest[$i]['cat_id'],'sub_id'=>$quest[$i]['sub_id'],'test_id'=>$test,'group_num'=>$group,'created_at'=>$dateTime);
                
                		 }
                     
                         $insert=Test_answer::insert($my_arr);
                         
                              if($insert ==true){
                                 $message['data']=$report;
                                 $message['error']=0;
                                 $message['message']='insert success';
                         
                              }else{
                               
                                  $message['error']=1;
                                  $message['message']='error in insert';
                    
                              }

                    
                    
                    
                }
                
                
                
                
                
            }
      

    return response()->json($message);


   }



*/

}