<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\final_result;
use App\test_code;
use App\Test_questions;
use App\Test_result;
use carbon\carbon;
use App\Reports;
use App\Test_answer;
use App\SubCategory;
use App\Answer;
use App\user_tests;
use App\inteligent_testresult;
use Illuminate\Support\Facades\DB;

class NewController extends Controller
{
    public $message=array();

      public function show_result(Request $request)
     {
      try{
          $result=$request->input('report_id');
           $lang=$request->input('lang');



           $report=Reports::where('id',$result)->value('test_category');



          
           if($report ==4){
            
            $join_select=Reports::select('reports.id as report_id','final_result.user_id as test_user','reports.name', 'reports.birth_date', 'reports.examiner_name', 'reports.app_date', 'test_code.code','test_code.surname','test_code.details','test_code.weakness','test_code.straighten','reports.created_at')
            ->join('final_result','reports.result_code','=','final_result.code')
           ->leftjoin('test_code','final_result.code','=','test_code.code')
            ->where('reports.id','=',$result)
               ->distinct()->get()->toArray();
             }elseif ($report ==1) {

                $join_select=Reports::select('reports.id as report_id','inteligent_testresult.user_id as test_user',
                  'reports.name', 'reports.birth_date', 'reports.examiner_name', 'reports.app_date','inteligent_testresult.logical', 'inteligent_testresult.musical', 'inteligent_testresult.Linguist', 'inteligent_testresult.basary', 'inteligent_testresult.haraky', 'inteligent_testresult.social','inteligent_testresult.zaty','inteligent_testresult.naturally','reports.created_at')
            ->join('inteligent_testresult','reports.result_id','=','inteligent_testresult.id')
          
            ->where('reports.id','=',$result)
               ->distinct()->get()->toArray();
             }
       
 
   
          

                 if(count($join_select)>0 ){
                        $message['data']= $join_select;
                         $message['error']=0;
                        $message['message']='show result data';
                      }else{
                        $message['data']= $join_select;
                        $message['error']=1;
                        $message['message']='no data';
                      }
                   

          
            
            }catch(Exception $ex){
                 $message['error']=2;
                 $message['message']='error'.$ex->getMessage();
            }  
         

       
         return response()->json($message);
     }


     // 
      public function answer_atest(Request $request){
          try{
              $token=$request->input('user_token');
              $sub=$request->input('test_id');
              $report=$request->input('report_id');
              $group=$request->input('group_number');
              $id=$request->input('id');
              $type=$request->input('test_type');

                $list= json_decode($request->input('list'),true); 
               $updated_at = carbon::now()->toDateTimeString();
               $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($updated_at)));


  if($sub ==4){
                $E=0;
                $I=0;
                $N=0;//mshaar
                $S=0;//mofaker
                $F=0;//hadsy
                $T=0;//hsy
                $P=0;//judge
                $J=0;//observe
                 $Fruits = array(); 
         if($group==1){
          foreach ( $list AS $key => $line ) { 
            if (in_array($line['id'],[1,9,17,25,33]) ) { 
                 if( $line['answer']==1)
                   {
                    $E++;
                   }else{
                    $I++;
                   } 

            } 
            if (in_array($line['id'],[5,13,21,29,37]) ) { 
                 if( $line['answer']==1){
                     $I++;
                   }else{
                    $E++;
                   } 
                  
            } 

             if (in_array($line['id'],[2,10,18,26,34]) ) { 
                 if( $line['answer']==1){
                     $N++;
                   }else{
                    $S++;
                   }      
            }

              if (in_array($line['id'],[6,14,22,30,38]) ) { 
                 if( $line['answer']==1){  
                    $S++;
                   }else{ 
                    $N++;
                   } 
            } 
              if (in_array($line['id'],[3,11,19,27,35]) ) { 
                 if( $line['answer']==1){  
                    $F++;
                   }else{
                    $T++;
                   } 
            } 

             if (in_array($line['id'],[7,15,23,31,39]) ) { 
                 if( $line['answer']==1)
                   {
                      $T++;
                    
                   }else{
                   $F++;
                   
                   } 
            } 


              if (in_array($line['id'],[4,12,20,28,36]) ) { 
                 if( $line['answer']==1) {
                      $P++;
                   }else{
                     $J++;
                   
                   }     
            } 
              if (in_array($line['id'],[8,16,24,32,40]) ) { 
                 if( $line['answer']==1){  
                    $J++;
                   }else{
                   
                    $P++;
                   } 

                   
            } 
          }

        }else{
           //1 =>أ و 2=> ب
          foreach ( $list AS $key => $line ) { 

                if (in_array($line['id'],[41,42,43,44,45,46]) ) { 
                     if( $line['answer']==1)
                       {
                        $E++;
                       }else{
                        $I++;
                       } 

                }

                 if (in_array($line['id'],[47,48,49,50,51,52]) ) { 
                     if( $line['answer']==1)
                       {
                        $S++;
                       }else{
                        $N++;
                       } 

                }

                if (in_array($line['id'],[53,54,55,56,57,58]) ) { 
                     if( $line['answer']==1)
                       {
                        $T++;
                       }else{
                        $F++;
                       } 

                }

                   if (in_array($line['id'],[59,60,61,62,63,64]) ) { 
                     if( $line['answer']==1)
                       {
                        $P++;
                       }else{
                        $J++;
                       } 

                }
      }

 }

  $check=Test_result::where([['user_id',$user],['sub_id',$sub],['group_num',$group]])->first();

  if($check !=null){
   $action=Test_result::where([['user_id',$user],['sub_id',$sub],['group_num',$group]])->update([

        'E'=>$E,
        'I'=>$I,
        'S'=>$S,
        'N'=>$N,
        'F'=>$F,
        'T'=>$T,
        'P'=>$P,
        'J'=>$J,
        'updated_at'=>$dateTime 
 
   ]);
  }else{

  
            $action=new Test_result;
            $action->user_id=$user;
            $action->sub_id=$sub;
            $action->group_num=$group;
            $action->E=$E;
            $action->I=$I;
            $action->S=$S;
            $action->N=$N;
            $action->F=$F;
            $action->T=$T;
            $action->P=$P;
            $action->J=$J;
            $action->created_at=$dateTime;
            $action->save();
         
    }
          if($action ==true){


             //for update code of final result
 
               $select=final_result::where('user_id',3)->first(); 
           
            $a='';
            $m='';
            $h='';
            $n='';

            if($select['E']>$select['I']){
              $a='E';
            }else{
              $a='I';
            }

           if($select['N']>$select['S']){
              $h='N';
            }else{
              $h='S';
            }
      

        if($select['F']>$select['T']){
              $m='F';
            }else{
              $m='T';
            }

              if($select['J']>$select['P']){
              $n='J';
            }else{
              $n='P';
            }

            $code=$a.$h.$m.$n;
         
        
            $result_id=final_result::where([['user_id',$user],['sub_id',$sub]])->value('id');
            $update=final_result::where([['user_id',$user],['sub_id',$sub]])->update([
               'code'=>$code,
               'updated_at'=>$dateTime

            ]);

            if($request->has('report_id')){
                $update_report=Reports::where('id',$report)->update([
                   'result_code'=>$code,
                   'result_id'=>$result_id,
                   'updated_at'=>$dateTime
    
    
                ]);
            }
            
            
           
                
                 if($type ==1){
                     
                     
                     
                     
                      if($request->has('user_token')){
                          
                          
                          
                          
                          
                          
                          
                          
                     
                         $data=User::where('user_token',$token)->first();
                         
                         $insert=new Reports;
                         $insert->user_id=$data['id'];
                       //  $insert->examiner_id=NULL;
                         $insert->name=$data['first_name'];
                         $insert->birth_date=$data['birth_date'];
                      //   $insert->examiner_name='';
                         $insert->test_type='1';
                         $insert->test_category=$sub;
                         $insert->type=4;
                         $insert->nationality=$data['nationality'];
                        // $insert->main_hand=$hand;
                         $insert->gender=$data['gender'];
                         $insert->institution=$data['institution_id'];
                         $insert->app_date=$dateTime;
                         $insert->result_code=$code;
                         $insert->result_id=$result_id;
                         $insert->educational_level=$data['level'];
                         $insert->created_at=$dateTime;
                         $insert->save();
                 }else{
                     
                     // insert  test without check if exist
                     
                     
                     
                     
                     
                     
                        if($request->has('report_id')){
                          $update_report=Reports::where('id',$report)->update([
                           'result_code'=>$code,
                           'result_id'=>$result_id,
                           'updated_at'=>$dateTime
            
            
                             ]);
                         }
            
                     
                     
                 }
                
                
                
                
            }else{
                //type =2 
                
                
                if($request->has('user_token')&&$request->has('id')){
                    
                    
                    
                    
                }elseif($request->has('user_token')&&$request->has('report_id')){
                    
                    
                    
                    
                }elseif(!$request->has('user_token')&&$request->has('report_id')){
                    
                    
                    
                    
                    
                }
                
                
                
                
                
            }

             $message['data']=$action;
             $message['error']=0;
             $message['message']='insert success';
     
          }else{
              $message['data']=$action;
              $message['error']=1;
              $message['message']='error in insert';

          }
        }elseif($sub==1){
             $man_count=Test_questions::where('type','mantky')->pluck('id')->toArray();

             $m_count=Test_questions::where('type','musical')->pluck('id')->toArray();
             $l_count=Test_questions::where('type','logical')->pluck('id')->toArray();
             $b_count=Test_questions::where('type','basary')->pluck('id')->toArray();
           $h_count=Test_questions::where('type','haraky')->pluck('id')->toArray();
            
          $s_count=Test_questions::where('type','social')->pluck('id')->toArray();
            $z_count=Test_questions::where('type','zaty')->pluck('id')->toArray();
             $n_count=Test_questions::where('type','natural')->pluck('id')->toArray();
             $mantky=0;  $mantky_null=0;
            $music=0;    $music_null=0;
            $logistic=0; $logistic_null=0;
            $basary=0;   $basary_null=0;
            $harky=0;    $harky_null=0;
            $social=0;   $social_null=0;
            $zaty=0;     $zaty_null=0;
            $natural=0;  $natural_null=0;
          
          
    //    $slice_mantky=array_slice($list,0,12);
         foreach ($list as $key => $value) {
           

             if(in_array($value['id'],$man_count) ){
                 if($value['answer']==0){
                  $mantky_null=$mantky_null+1;
                 }
              $mantky=$mantky+$value['answer'];


           }elseif(in_array($value['id'],$m_count)){
               if($value['answer']==0){
                  $music_null=$music_null+1;
                 }
              $music=$music+$value['answer'];

           }
           elseif(in_array($value['id'],$l_count)){
                if($value['answer']==0){
                  $logistic_null=$logistic_null+1;
                 }
              $logistic=$logistic+$value['answer'];
           }elseif(in_array($value['id'],$b_count)){
                if($value['answer']==0){
                  $basary_null=$basary_null+1;
                 }
              $basary=$basary+$value['answer'];
           }elseif(in_array($value['id'],$h_count)){
               if($value['answer']==0){
                  $haraky_null=$haraky_null+1;
                 }
              $haraky=$haraky+$value['answer'];
           }elseif(in_array($value['id'],$s_count)){
                if($value['answer']==0){
                  $social_null=$social_null+1;
                 }
              $social=$social+$value['answer'];
           }
           elseif(in_array($value['id'],$z_count)){
               if($value['answer']==0){
                  $zaty_null=$zaty_null+1;
                 }
              $zaty=$zaty+$value['answer'];
           }elseif(in_array($value['id'],$n_count)){
                  if($value['answer']==0){
                  $natural_null=$natural_null+1;
                 }
              $natural=$natural+$value['answer'];
           }
           
           } 

         $total_mantky=($mantky/((count($man_count)-$mantky_null)*5))*100;
         $total_music=($music/((count($m_count)-$music_null)*5))*100;
         $total_logistic=($logistic/((count($l_count)-$logistic_null)*5))*100;
         $total_basary=($basary/((count($b_count)-$basary_null)*5))*100;
         $total_harky=($harky/((count($h_count)-$haraky_null)*5))*100;
         $total_social=($social/((count($s_count)-$social_null)*5))*100;
          $total_zaty=($zaty/((count($z_count)-$zaty_null)*5))*100;

         $total_natural=($natural/((count($n_count)-$natural_null)*5))*100;

       $insert=new inteligent_testresult;
       $insert->user_id=$user;
       $insert->logical=round($total_mantky,1);
       $insert->musical=round($total_music,1);
       $insert->Linguist=round($total_logistic,1);
       $insert->basary=round($total_basary,1);
       $insert->haraky=round($total_harky,1);
       $insert->social=round($total_social,1);
       $insert->zaty=round($total_zaty,1);
       $insert->naturally=round($total_natural,1);
       $insert->created_at=$dateTime;
       $insert->save();

          if($insert == true){


             $update_report=reports::where('id',$report)->update([
               
               'result_id'=>$insert->id,
               'created_at'=>$dateTime


            ]);
             $message['data']=$insert;
             $message['error']=0;
             $message['message']='insert success';
     
          }else{
              $message['data']=$insert;
              $message['error']=1;
              $message['message']='error in insert';

          }


        }
          }catch(Exception $ex){
               $message['error']=2;
               $message['message']='error'.$ex->getMessage();
          }
          
           return response()->json($message);
          
      }
      
      
          public function answer_aquestion(Request $request){
          try{
              $token=$request->input('user_token');
              $sub=$request->input('test_id');
              $report=$request->input('report_id');
              $group=$request->input('group_number');
              $id=$request->input('id');
              $type=$request->input('test_type');

                $list= json_decode($request->input('list'),true); 
               $updated_at = carbon::now()->toDateTimeString();
               $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($updated_at)));


  if($sub ==4){
                $E=0;
                $I=0;
                $N=0;//mshaar
                $S=0;//mofaker
                $F=0;//hadsy
                $T=0;//hsy
                $P=0;//judge
                $J=0;//observe
                 $Fruits = array(); 
         if($group==1){
          foreach ( $list AS $key => $line ) { 
            if (in_array($line['id'],[1,9,17,25,33]) ) { 
                 if( $line['answer']==1)
                   {
                    $E++;
                   }else{
                    $I++;
                   } 

            } 
            if (in_array($line['id'],[5,13,21,29,37]) ) { 
                 if( $line['answer']==1){
                     $I++;
                   }else{
                    $E++;
                   } 
                  
            } 

             if (in_array($line['id'],[2,10,18,26,34]) ) { 
                 if( $line['answer']==1){
                     $N++;
                   }else{
                    $S++;
                   }      
            }

              if (in_array($line['id'],[6,14,22,30,38]) ) { 
                 if( $line['answer']==1){  
                    $S++;
                   }else{ 
                    $N++;
                   } 
            } 
              if (in_array($line['id'],[3,11,19,27,35]) ) { 
                 if( $line['answer']==1){  
                    $F++;
                   }else{
                    $T++;
                   } 
            } 

             if (in_array($line['id'],[7,15,23,31,39]) ) { 
                 if( $line['answer']==1)
                   {
                      $T++;
                    
                   }else{
                   $F++;
                   
                   } 
            } 


              if (in_array($line['id'],[4,12,20,28,36]) ) { 
                 if( $line['answer']==1) {
                      $P++;
                   }else{
                     $J++;
                   
                   }     
            } 
              if (in_array($line['id'],[8,16,24,32,40]) ) { 
                 if( $line['answer']==1){  
                    $J++;
                   }else{
                   
                    $P++;
                   } 

                   
            } 
          }

        }else{
           //1 =>أ و 2=> ب
          foreach ( $list AS $key => $line ) { 

                if (in_array($line['id'],[41,42,43,44,45,46]) ) { 
                     if( $line['answer']==1)
                       {
                        $E++;
                       }else{
                        $I++;
                       } 

                }

                 if (in_array($line['id'],[47,48,49,50,51,52]) ) { 
                     if( $line['answer']==1)
                       {
                        $S++;
                       }else{
                        $N++;
                       } 

                }

                if (in_array($line['id'],[53,54,55,56,57,58]) ) { 
                     if( $line['answer']==1)
                       {
                        $T++;
                       }else{
                        $F++;
                       } 

                }

                   if (in_array($line['id'],[59,60,61,62,63,64]) ) { 
                     if( $line['answer']==1)
                       {
                        $P++;
                       }else{
                        $J++;
                       } 

                }
      }

 }

  $check=Test_result::where([['user_id',$user],['sub_id',$sub],['group_num',$group]])->first();

  if($check !=null){
   $action=Test_result::where([['user_id',$user],['sub_id',$sub],['group_num',$group]])->update([

        'E'=>$E,
        'I'=>$I,
        'S'=>$S,
        'N'=>$N,
        'F'=>$F,
        'T'=>$T,
        'P'=>$P,
        'J'=>$J,
        'updated_at'=>$dateTime 
 
   ]);
  }else{

  
            $action=new Test_result;
            $action->user_id=$user;
            $action->sub_id=$sub;
            $action->group_num=$group;
            $action->E=$E;
            $action->I=$I;
            $action->S=$S;
            $action->N=$N;
            $action->F=$F;
            $action->T=$T;
            $action->P=$P;
            $action->J=$J;
            $action->created_at=$dateTime;
            $action->save();
         
    }
          if($action ==true){


             //for update code of final result
 
               $select=final_result::where('user_id',3)->first(); 
           
            $a='';
            $m='';
            $h='';
            $n='';

            if($select['E']>$select['I']){
              $a='E';
            }else{
              $a='I';
            }

           if($select['N']>$select['S']){
              $h='N';
            }else{
              $h='S';
            }
      

        if($select['F']>$select['T']){
              $m='F';
            }else{
              $m='T';
            }

              if($select['J']>$select['P']){
              $n='J';
            }else{
              $n='P';
            }

            $code=$a.$h.$m.$n;
         
        
            $result_id=final_result::where([['user_id',$user],['sub_id',$sub]])->value('id');
            $update=final_result::where([['user_id',$user],['sub_id',$sub]])->update([
               'code'=>$code,
               'updated_at'=>$dateTime

            ]);

            if($request->has('report_id')){
                $update_report=Reports::where('id',$report)->update([
                   'result_code'=>$code,
                   'result_id'=>$result_id,
                   'updated_at'=>$dateTime
    
    
                ]);
            }
            
            
           
                
                 if($type ==1){
                     
                     
                     
                     
                      if($request->has('user_token')){
                          
                          
                          
                          
                          
                          
                          
                          
                     
                         $data=User::where('user_token',$token)->first();
                         
                         $insert=new Reports;
                         $insert->user_id=$data['id'];
                       //  $insert->examiner_id=NULL;
                         $insert->name=$data['first_name'];
                         $insert->birth_date=$data['birth_date'];
                      //   $insert->examiner_name='';
                         $insert->test_type='1';
                         $insert->test_category=$sub;
                         $insert->type=4;
                         $insert->nationality=$data['nationality'];
                        // $insert->main_hand=$hand;
                         $insert->gender=$data['gender'];
                         $insert->institution=$data['institution_id'];
                         $insert->app_date=$dateTime;
                         $insert->result_code=$code;
                         $insert->result_id=$result_id;
                         $insert->educational_level=$data['level'];
                         $insert->created_at=$dateTime;
                         $insert->save();
                 }else{
                     
                     // insert  test without check if exist
                     
                     
                     
                     
                     
                     
                        if($request->has('report_id')){
                          $update_report=Reports::where('id',$report)->update([
                           'result_code'=>$code,
                           'result_id'=>$result_id,
                           'updated_at'=>$dateTime
            
            
                             ]);
                         }
            
                     
                     
                 }
                
                
                
                
            }else{
                //type =2 
                
                
                if($request->has('user_token')&&$request->has('id')){
                    
                    
                    
                    
                }elseif($request->has('user_token')&&$request->has('report_id')){
                    
                    
                    
                    
                }elseif(!$request->has('user_token')&&$request->has('report_id')){
                    
                    
                    
                    
                    
                }
                
                
                
                
                
            }

             $message['data']=$action;
             $message['error']=0;
             $message['message']='insert success';
     
          }else{
              $message['data']=$action;
              $message['error']=1;
              $message['message']='error in insert';

          }
        }elseif($sub==1){
             $man_count=Test_questions::where('type','mantky')->pluck('id')->toArray();

             $m_count=Test_questions::where('type','musical')->pluck('id')->toArray();
             $l_count=Test_questions::where('type','logical')->pluck('id')->toArray();
             $b_count=Test_questions::where('type','basary')->pluck('id')->toArray();
           $h_count=Test_questions::where('type','haraky')->pluck('id')->toArray();
            
          $s_count=Test_questions::where('type','social')->pluck('id')->toArray();
            $z_count=Test_questions::where('type','zaty')->pluck('id')->toArray();
             $n_count=Test_questions::where('type','natural')->pluck('id')->toArray();
             $mantky=0;  $mantky_null=0;
            $music=0;    $music_null=0;
            $logistic=0; $logistic_null=0;
            $basary=0;   $basary_null=0;
            $harky=0;    $harky_null=0;
            $social=0;   $social_null=0;
            $zaty=0;     $zaty_null=0;
            $natural=0;  $natural_null=0;
          
          
    //    $slice_mantky=array_slice($list,0,12);
         foreach ($list as $key => $value) {
           

             if(in_array($value['id'],$man_count) ){
                 if($value['answer']==0){
                  $mantky_null=$mantky_null+1;
                 }
              $mantky=$mantky+$value['answer'];


           }elseif(in_array($value['id'],$m_count)){
               if($value['answer']==0){
                  $music_null=$music_null+1;
                 }
              $music=$music+$value['answer'];

           }
           elseif(in_array($value['id'],$l_count)){
                if($value['answer']==0){
                  $logistic_null=$logistic_null+1;
                 }
              $logistic=$logistic+$value['answer'];
           }elseif(in_array($value['id'],$b_count)){
                if($value['answer']==0){
                  $basary_null=$basary_null+1;
                 }
              $basary=$basary+$value['answer'];
           }elseif(in_array($value['id'],$h_count)){
               if($value['answer']==0){
                  $haraky_null=$haraky_null+1;
                 }
              $haraky=$haraky+$value['answer'];
           }elseif(in_array($value['id'],$s_count)){
                if($value['answer']==0){
                  $social_null=$social_null+1;
                 }
              $social=$social+$value['answer'];
           }
           elseif(in_array($value['id'],$z_count)){
               if($value['answer']==0){
                  $zaty_null=$zaty_null+1;
                 }
              $zaty=$zaty+$value['answer'];
           }elseif(in_array($value['id'],$n_count)){
                  if($value['answer']==0){
                  $natural_null=$natural_null+1;
                 }
              $natural=$natural+$value['answer'];
           }
           
           } 

         $total_mantky=($mantky/((count($man_count)-$mantky_null)*5))*100;
         $total_music=($music/((count($m_count)-$music_null)*5))*100;
         $total_logistic=($logistic/((count($l_count)-$logistic_null)*5))*100;
         $total_basary=($basary/((count($b_count)-$basary_null)*5))*100;
         $total_harky=($harky/((count($h_count)-$haraky_null)*5))*100;
         $total_social=($social/((count($s_count)-$social_null)*5))*100;
          $total_zaty=($zaty/((count($z_count)-$zaty_null)*5))*100;

         $total_natural=($natural/((count($n_count)-$natural_null)*5))*100;

       $insert=new inteligent_testresult;
       $insert->user_id=$user;
       $insert->logical=round($total_mantky,1);
       $insert->musical=round($total_music,1);
       $insert->Linguist=round($total_logistic,1);
       $insert->basary=round($total_basary,1);
       $insert->haraky=round($total_harky,1);
       $insert->social=round($total_social,1);
       $insert->zaty=round($total_zaty,1);
       $insert->naturally=round($total_natural,1);
       $insert->created_at=$dateTime;
       $insert->save();

          if($insert == true){


             $update_report=reports::where('id',$report)->update([
               
               'result_id'=>$insert->id,
               'created_at'=>$dateTime


            ]);
             $message['data']=$insert;
             $message['error']=0;
             $message['message']='insert success';
     
          }else{
              $message['data']=$insert;
              $message['error']=1;
              $message['message']='error in insert';

          }


        }
          }catch(Exception $ex){
               $message['error']=2;
               $message['message']='error'.$ex->getMessage();
          }
          
           return response()->json($message);
          
      }
      
   
      
      
      

 /* public function answer_aquestion(Request $request)
  {
    
          try{
              $token=$request->input('user_token');
              $sub=$request->input('test_id');
              $report=$request->input('report_id');
              $group=$request->input('group_number');
              $id=$request->input('id');
              $type=$request->input('test_type');

                $list= json_decode($request->input('list'),true); 
               $updated_at = carbon::now()->toDateTimeString();
               $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($updated_at)));
               
               
               
               
                    if($type ==1){
                     
                     
                     
                     
                      if($request->has('user_token')){
                             $data=User::where('user_token',$token)->first();

                          
                         if($sub ==4){
                                $E=0;
                                $I=0;
                                $N=0;//mshaar
                                $S=0;//mofaker
                                $F=0;//hadsy
                                $T=0;//hsy
                                $P=0;//judge
                                $J=0;//observe
                                 $Fruits = array(); 
                         if($group==1){
                          foreach ( $list AS $key => $line ) { 
                            if (in_array($line['id'],[1,9,17,25,33]) ) { 
                                 if( $line['answer']==1)
                                   {
                                    $E++;
                                   }else{
                                    $I++;
                                   } 
                
                            } 
                            if (in_array($line['id'],[5,13,21,29,37]) ) { 
                                 if( $line['answer']==1){
                                     $I++;
                                   }else{
                                    $E++;
                                   } 
                                  
                            } 
                
                             if (in_array($line['id'],[2,10,18,26,34]) ) { 
                                 if( $line['answer']==1){
                                     $N++;
                                   }else{
                                    $S++;
                                   }      
                            }
                
                              if (in_array($line['id'],[6,14,22,30,38]) ) { 
                                 if( $line['answer']==1){  
                                    $S++;
                                   }else{ 
                                    $N++;
                                   } 
                            } 
                              if (in_array($line['id'],[3,11,19,27,35]) ) { 
                                 if( $line['answer']==1){  
                                    $F++;
                                   }else{
                                    $T++;
                                   } 
                            } 
                
                             if (in_array($line['id'],[7,15,23,31,39]) ) { 
                                 if( $line['answer']==1)
                                   {
                                      $T++;
                                    
                                   }else{
                                   $F++;
                                   
                                   } 
                            } 
                
                
                              if (in_array($line['id'],[4,12,20,28,36]) ) { 
                                 if( $line['answer']==1) {
                                      $P++;
                                   }else{
                                     $J++;
                                   
                                   }     
                            } 
                              if (in_array($line['id'],[8,16,24,32,40]) ) { 
                                 if( $line['answer']==1){  
                                    $J++;
                                   }else{
                                   
                                    $P++;
                                   } 
                
                                   
                            } 
                          }
                
                        }else{
                           //1 =>أ و 2=> ب
                          foreach ( $list AS $key => $line ) { 
                
                                if (in_array($line['id'],[41,42,43,44,45,46]) ) { 
                                     if( $line['answer']==1)
                                       {
                                        $E++;
                                       }else{
                                        $I++;
                                       } 
                
                                }
                
                                 if (in_array($line['id'],[47,48,49,50,51,52]) ) { 
                                     if( $line['answer']==1)
                                       {
                                        $S++;
                                       }else{
                                        $N++;
                                       } 
                
                                }
                
                                if (in_array($line['id'],[53,54,55,56,57,58]) ) { 
                                     if( $line['answer']==1)
                                       {
                                        $T++;
                                       }else{
                                        $F++;
                                       } 
                
                                }
                
                                   if (in_array($line['id'],[59,60,61,62,63,64]) ) { 
                                     if( $line['answer']==1)
                                       {
                                        $P++;
                                       }else{
                                        $J++;
                                       } 
                
                                }
                      }
                
                 }
                
                  $check=Test_result::where([['user_id',$data['id']],['sub_id',$sub],['group_num',$group]])->first();
                
                  if($check !=null){
                   $action=Test_result::where([['user_id',$data['id']],['sub_id',$sub],['group_num',$group]])->update([
                
                        'E'=>$E,
                        'I'=>$I,
                        'S'=>$S,
                        'N'=>$N,
                        'F'=>$F,
                        'T'=>$T,
                        'P'=>$P,
                        'J'=>$J,
                        'updated_at'=>$dateTime 
                 
                   ]);
                  }else{
                
                  
                            $action=new Test_result;
                            $action->user_id=$data['id'];
                            $action->sub_id=$sub;
                            $action->group_num=$group;
                            $action->E=$E;
                            $action->I=$I;
                            $action->S=$S;
                            $action->N=$N;
                            $action->F=$F;
                            $action->T=$T;
                            $action->P=$P;
                            $action->J=$J;
                            $action->created_at=$dateTime;
                            $action->save();
                         
                    }
                          if($action ==true){
                
                
                             //for update code of final result
                 
                               $select=final_result::where('user_id',$data['id'])->first(); 
                           
                            $a='';
                            $m='';
                            $h='';
                            $n='';
                
                            if($select['E']>$select['I']){
                              $a='E';
                            }else{
                              $a='I';
                            }
                
                           if($select['N']>$select['S']){
                              $h='N';
                            }else{
                              $h='S';
                            }
                      
                
                        if($select['F']>$select['T']){
                              $m='F';
                            }else{
                              $m='T';
                            }
                
                              if($select['J']>$select['P']){
                              $n='J';
                            }else{
                              $n='P';
                            }
                
                            $code=$a.$h.$m.$n;
                         
                        
                            $result_id=final_result::where([['user_id',$data['id']],['sub_id',$sub]])->value('id');
                            $update=final_result::where([['user_id',$data['id']],['sub_id',$sub]])->update([
                               'code'=>$code,
                               'updated_at'=>$dateTime
                
                            ]);
                
                         /*   if($request->has('report_id')){
                                $update_report=Reports::where('id',$report)->update([
                                   'result_code'=>$code,
                                   'result_id'=>$result_id,
                                   'updated_at'=>$dateTime
                    
                    
                                ]);
                          //  }
                            
                                    $insert=new Reports;
                                     $insert->user_id=$data['id'];
                                   //  $insert->examiner_id=NULL;
                                     $insert->name=$data['first_name'];
                                     $insert->birth_date=$data['birth_date'];
                                  //   $insert->examiner_name='';
                                     $insert->test_type='1';
                                     $insert->test_category=$sub;
                                     $insert->type=4;
                                     $insert->nationality=$data['nationality'];
                                    // $insert->main_hand=$hand;
                                     $insert->gender=$data['gender'];
                                     $insert->institution=$data['institution_id'];
                                     $insert->app_date=$dateTime;
                                     $insert->result_code=$code;
                                     $insert->result_id=$result_id;
                                     $insert->educational_level=$data['level'];
                                     $insert->created_at=$dateTime;
                                     $insert->save();
                            
                            
                           
                                
                            
                             $message['data']=$action;
                             $message['error']=0;
                             $message['message']='insert success';
                     
                          }else{
                              $message['data']=$action;
                              $message['error']=1;
                              $message['message']='error in insert';
                
                          }
                        }elseif($sub==1){
                             $man_count=Test_questions::where('type','mantky')->pluck('id')->toArray();
                
                             $m_count=Test_questions::where('type','musical')->pluck('id')->toArray();
                             $l_count=Test_questions::where('type','logical')->pluck('id')->toArray();
                             $b_count=Test_questions::where('type','basary')->pluck('id')->toArray();
                           $h_count=Test_questions::where('type','haraky')->pluck('id')->toArray();
                            
                          $s_count=Test_questions::where('type','social')->pluck('id')->toArray();
                            $z_count=Test_questions::where('type','zaty')->pluck('id')->toArray();
                             $n_count=Test_questions::where('type','natural')->pluck('id')->toArray();
                             $mantky=0;  $mantky_null=0;
                            $music=0;    $music_null=0;
                            $logistic=0; $logistic_null=0;
                            $basary=0;   $basary_null=0;
                            $harky=0;    $harky_null=0;
                            $social=0;   $social_null=0;
                            $zaty=0;     $zaty_null=0;
                            $natural=0;  $natural_null=0;
                          
                          
                    //    $slice_mantky=array_slice($list,0,12);
                         foreach ($list as $key => $value) {
                           
                
                             if(in_array($value['id'],$man_count) ){
                                 if($value['answer']==0){
                                  $mantky_null=$mantky_null+1;
                                 }
                              $mantky=$mantky+$value['answer'];
                
                
                           }elseif(in_array($value['id'],$m_count)){
                               if($value['answer']==0){
                                  $music_null=$music_null+1;
                                 }
                              $music=$music+$value['answer'];
                
                           }
                           elseif(in_array($value['id'],$l_count)){
                                if($value['answer']==0){
                                  $logistic_null=$logistic_null+1;
                                 }
                              $logistic=$logistic+$value['answer'];
                           }elseif(in_array($value['id'],$b_count)){
                                if($value['answer']==0){
                                  $basary_null=$basary_null+1;
                                 }
                              $basary=$basary+$value['answer'];
                           }elseif(in_array($value['id'],$h_count)){
                               if($value['answer']==0){
                                  $haraky_null=$haraky_null+1;
                                 }
                              $haraky=$haraky+$value['answer'];
                           }elseif(in_array($value['id'],$s_count)){
                                if($value['answer']==0){
                                  $social_null=$social_null+1;
                                 }
                              $social=$social+$value['answer'];
                           }
                           elseif(in_array($value['id'],$z_count)){
                               if($value['answer']==0){
                                  $zaty_null=$zaty_null+1;
                                 }
                              $zaty=$zaty+$value['answer'];
                           }elseif(in_array($value['id'],$n_count)){
                                  if($value['answer']==0){
                                  $natural_null=$natural_null+1;
                                 }
                              $natural=$natural+$value['answer'];
                           }
                           
                           } 
                
                         $total_mantky=($mantky/((count($man_count)-$mantky_null)*5))*100;
                         $total_music=($music/((count($m_count)-$music_null)*5))*100;
                         $total_logistic=($logistic/((count($l_count)-$logistic_null)*5))*100;
                         $total_basary=($basary/((count($b_count)-$basary_null)*5))*100;
                         $total_harky=($harky/((count($h_count)-$haraky_null)*5))*100;
                         $total_social=($social/((count($s_count)-$social_null)*5))*100;
                          $total_zaty=($zaty/((count($z_count)-$zaty_null)*5))*100;
                
                         $total_natural=($natural/((count($n_count)-$natural_null)*5))*100;
                         
                         $check=inteligent_testresult::where('user_id',$data['id'])->first();
                         
                         
                         if($check !=null){
                               $insert=inteligent_testresult::where('user_id',$data['id'])->update([
                                   
                                         
                                       'logical'=>round($total_mantky,1),
                                       'musical'=>round($total_music,1),
                                       'Linguist'=>round($total_logistic,1),
                                       'basary'=>round($total_basary,1),
                                       'haraky'=>round($total_harky,1),
                                       'social'=>round($total_social,1),
                                       'zaty'=>round($total_zaty,1),
                                       'naturally'=>round($total_natural,1),
                                       'updated_at'=>$dateTime
         
                                   ]);
                           

                         }else{
                
                               $insert=new inteligent_testresult;
                               $insert->user_id=$data['id'];
                               $insert->logical=round($total_mantky,1);
                               $insert->musical=round($total_music,1);
                               $insert->Linguist=round($total_logistic,1);
                               $insert->basary=round($total_basary,1);
                               $insert->haraky=round($total_harky,1);
                               $insert->social=round($total_social,1);
                               $insert->zaty=round($total_zaty,1);
                               $insert->naturally=round($total_natural,1);
                               $insert->created_at=$dateTime;
                               $insert->save();
                        
                          if($insert == true){
                
                
                                  $insert=new Reports;
                                 $insert->user_id=$data['id'];
                               //  $insert->examiner_id=NULL;
                                 $insert->name=$data['first_name'];
                                 $insert->birth_date=$data['birth_date'];
                              //   $insert->examiner_name='';
                                 $insert->test_type='1';
                                 $insert->test_category=$sub;
                                 $insert->type=4;
                                 $insert->nationality=$data['nationality'];
                                // $insert->main_hand=$hand;
                                 $insert->gender=$data['gender'];
                                 $insert->institution=$data['institution_id'];
                                 $insert->app_date=$dateTime;
                                 //$insert->result_code=$code;
                                 $insert->result_id=$insert->id;
                                 $insert->educational_level=$data['level'];
                                 $insert->created_at=$dateTime;
                                 $insert->save();
                             $message['data']=$insert;
                             $message['error']=0;
                             $message['message']='insert success';
                     
                          }else{
                              $message['data']=$insert;
                              $message['error']=1;
                              $message['message']='error in insert';
                
                          }
                         }
                          
                          
                          
                          
                          
                     
                         
                        
                 }else{
                     
                     // insert  test without check if exist
                     
                        if($request->has('report_id')){
                            
                            
                            
                                                       
                         if($sub ==4){
                                $E=0;
                                $I=0;
                                $N=0;//mshaar
                                $S=0;//mofaker
                                $F=0;//hadsy
                                $T=0;//hsy
                                $P=0;//judge
                                $J=0;//observe
                                 $Fruits = array(); 
                         if($group==1){
                          foreach ( $list AS $key => $line ) { 
                            if (in_array($line['id'],[1,9,17,25,33]) ) { 
                                 if( $line['answer']==1)
                                   {
                                    $E++;
                                   }else{
                                    $I++;
                                   } 
                
                            } 
                            if (in_array($line['id'],[5,13,21,29,37]) ) { 
                                 if( $line['answer']==1){
                                     $I++;
                                   }else{
                                    $E++;
                                   } 
                                  
                            } 
                
                             if (in_array($line['id'],[2,10,18,26,34]) ) { 
                                 if( $line['answer']==1){
                                     $N++;
                                   }else{
                                    $S++;
                                   }      
                            }
                
                              if (in_array($line['id'],[6,14,22,30,38]) ) { 
                                 if( $line['answer']==1){  
                                    $S++;
                                   }else{ 
                                    $N++;
                                   } 
                            } 
                              if (in_array($line['id'],[3,11,19,27,35]) ) { 
                                 if( $line['answer']==1){  
                                    $F++;
                                   }else{
                                    $T++;
                                   } 
                            } 
                
                             if (in_array($line['id'],[7,15,23,31,39]) ) { 
                                 if( $line['answer']==1)
                                   {
                                      $T++;
                                    
                                   }else{
                                   $F++;
                                   
                                   } 
                            } 
                
                
                              if (in_array($line['id'],[4,12,20,28,36]) ) { 
                                 if( $line['answer']==1) {
                                      $P++;
                                   }else{
                                     $J++;
                                   
                                   }     
                            } 
                              if (in_array($line['id'],[8,16,24,32,40]) ) { 
                                 if( $line['answer']==1){  
                                    $J++;
                                   }else{
                                   
                                    $P++;
                                   } 
                
                                   
                            } 
                          }
                
                        }else{
                           //1 =>أ و 2=> ب
                          foreach ( $list AS $key => $line ) { 
                
                                if (in_array($line['id'],[41,42,43,44,45,46]) ) { 
                                     if( $line['answer']==1)
                                       {
                                        $E++;
                                       }else{
                                        $I++;
                                       } 
                
                                }
                
                                 if (in_array($line['id'],[47,48,49,50,51,52]) ) { 
                                     if( $line['answer']==1)
                                       {
                                        $S++;
                                       }else{
                                        $N++;
                                       } 
                
                                }
                
                                if (in_array($line['id'],[53,54,55,56,57,58]) ) { 
                                     if( $line['answer']==1)
                                       {
                                        $T++;
                                       }else{
                                        $F++;
                                       } 
                
                                }
                
                                   if (in_array($line['id'],[59,60,61,62,63,64]) ) { 
                                     if( $line['answer']==1)
                                       {
                                        $P++;
                                       }else{
                                        $J++;
                                       } 
                
                                }
                      }
                
                 }
                
                  
                            $action=new Test_result;
                            $action->user_id=$data['id'];
                            $action->sub_id=$sub;
                            $action->group_num=$group;
                            $action->E=$E;
                            $action->I=$I;
                            $action->S=$S;
                            $action->N=$N;
                            $action->F=$F;
                            $action->T=$T;
                            $action->P=$P;
                            $action->J=$J;
                            $action->created_at=$dateTime;
                            $action->save();
                         
                    
                          if($action ==true){
                
                
                             //for update code of final result
                 
                               $select=final_result::where('user_id',$data['id'])->first(); 
                           
                            $a='';
                            $m='';
                            $h='';
                            $n='';
                
                            if($select['E']>$select['I']){
                              $a='E';
                            }else{
                              $a='I';
                            }
                
                           if($select['N']>$select['S']){
                              $h='N';
                            }else{
                              $h='S';
                            }
                      
                
                        if($select['F']>$select['T']){
                              $m='F';
                            }else{
                              $m='T';
                            }
                
                              if($select['J']>$select['P']){
                              $n='J';
                            }else{
                              $n='P';
                            }
                
                            $code=$a.$h.$m.$n;
                         
                        
                            $result_id=final_result::where([['user_id',$data['id']],['sub_id',$sub]])->value('id');
                            $update=final_result::where([['user_id',$data['id']],['sub_id',$sub]])->update([
                               'code'=>$code,
                               'updated_at'=>$dateTime
                
                            ]);
                
                         /*   if($request->has('report_id')){
                                $update_report=Reports::where('id',$report)->update([
                                   'result_code'=>$code,
                                   'result_id'=>$result_id,
                                   'updated_at'=>$dateTime
                    
                    
                                ]);
                           // }
                            
                                    $insert=new Reports;
                                     $insert->user_id=$data['id'];
                                   //  $insert->examiner_id=NULL;
                                     $insert->name=$data['first_name'];
                                     $insert->birth_date=$data['birth_date'];
                                  //   $insert->examiner_name='';
                                     $insert->test_type='1';
                                     $insert->test_category=$sub;
                                     $insert->type=4;
                                     $insert->nationality=$data['nationality'];
                                    // $insert->main_hand=$hand;
                                     $insert->gender=$data['gender'];
                                     $insert->institution=$data['institution_id'];
                                     $insert->app_date=$dateTime;
                                     $insert->result_code=$code;
                                     $insert->result_id=$result_id;
                                     $insert->educational_level=$data['level'];
                                     $insert->created_at=$dateTime;
                                     $insert->save();
                            
                            
                           
                                
                            
                             $message['data']=$action;
                             $message['error']=0;
                             $message['message']='insert success';
                     
                          }else{
                              $message['data']=$action;
                              $message['error']=1;
                              $message['message']='error in insert';
                
                          }
                        }elseif($sub==1){
                             $man_count=Test_questions::where('type','mantky')->pluck('id')->toArray();
                
                             $m_count=Test_questions::where('type','musical')->pluck('id')->toArray();
                             $l_count=Test_questions::where('type','logical')->pluck('id')->toArray();
                             $b_count=Test_questions::where('type','basary')->pluck('id')->toArray();
                           $h_count=Test_questions::where('type','haraky')->pluck('id')->toArray();
                            
                          $s_count=Test_questions::where('type','social')->pluck('id')->toArray();
                            $z_count=Test_questions::where('type','zaty')->pluck('id')->toArray();
                             $n_count=Test_questions::where('type','natural')->pluck('id')->toArray();
                             $mantky=0;  $mantky_null=0;
                            $music=0;    $music_null=0;
                            $logistic=0; $logistic_null=0;
                            $basary=0;   $basary_null=0;
                            $harky=0;    $harky_null=0;
                            $social=0;   $social_null=0;
                            $zaty=0;     $zaty_null=0;
                            $natural=0;  $natural_null=0;
                          
                          
                    //    $slice_mantky=array_slice($list,0,12);
                         foreach ($list as $key => $value) {
                           
                
                             if(in_array($value['id'],$man_count) ){
                                 if($value['answer']==0){
                                  $mantky_null=$mantky_null+1;
                                 }
                              $mantky=$mantky+$value['answer'];
                
                
                           }elseif(in_array($value['id'],$m_count)){
                               if($value['answer']==0){
                                  $music_null=$music_null+1;
                                 }
                              $music=$music+$value['answer'];
                
                           }
                           elseif(in_array($value['id'],$l_count)){
                                if($value['answer']==0){
                                  $logistic_null=$logistic_null+1;
                                 }
                              $logistic=$logistic+$value['answer'];
                           }elseif(in_array($value['id'],$b_count)){
                                if($value['answer']==0){
                                  $basary_null=$basary_null+1;
                                 }
                              $basary=$basary+$value['answer'];
                           }elseif(in_array($value['id'],$h_count)){
                               if($value['answer']==0){
                                  $haraky_null=$haraky_null+1;
                                 }
                              $haraky=$haraky+$value['answer'];
                           }elseif(in_array($value['id'],$s_count)){
                                if($value['answer']==0){
                                  $social_null=$social_null+1;
                                 }
                              $social=$social+$value['answer'];
                           }
                           elseif(in_array($value['id'],$z_count)){
                               if($value['answer']==0){
                                  $zaty_null=$zaty_null+1;
                                 }
                              $zaty=$zaty+$value['answer'];
                           }elseif(in_array($value['id'],$n_count)){
                                  if($value['answer']==0){
                                  $natural_null=$natural_null+1;
                                 }
                              $natural=$natural+$value['answer'];
                           }
                           
                           } 
                
                         $total_mantky=($mantky/((count($man_count)-$mantky_null)*5))*100;
                         $total_music=($music/((count($m_count)-$music_null)*5))*100;
                         $total_logistic=($logistic/((count($l_count)-$logistic_null)*5))*100;
                         $total_basary=($basary/((count($b_count)-$basary_null)*5))*100;
                         $total_harky=($harky/((count($h_count)-$haraky_null)*5))*100;
                         $total_social=($social/((count($s_count)-$social_null)*5))*100;
                          $total_zaty=($zaty/((count($z_count)-$zaty_null)*5))*100;
                
                         $total_natural=($natural/((count($n_count)-$natural_null)*5))*100;
                         
                         $check=inteligent_testresult::where('user_id',$data['id'])->first();
                         
                         
                         if($check !=null){
                               $insert=inteligent_testresult::where('user_id',$data['id'])->update([
                                   
                                         
                                       'logical'=>round($total_mantky,1),
                                       'musical'=>round($total_music,1),
                                       'Linguist'=>round($total_logistic,1),
                                       'basary'=>round($total_basary,1),
                                       'haraky'=>round($total_harky,1),
                                       'social'=>round($total_social,1),
                                       'zaty'=>round($total_zaty,1),
                                       'naturally'=>round($total_natural,1),
                                       'updated_at'=>$dateTime
         
                                   ]);
                           

                         }else{
                
                               $insert=new inteligent_testresult;
                               $insert->user_id=$data['id'];
                               $insert->logical=round($total_mantky,1);
                               $insert->musical=round($total_music,1);
                               $insert->Linguist=round($total_logistic,1);
                               $insert->basary=round($total_basary,1);
                               $insert->haraky=round($total_harky,1);
                               $insert->social=round($total_social,1);
                               $insert->zaty=round($total_zaty,1);
                               $insert->naturally=round($total_natural,1);
                               $insert->created_at=$dateTime;
                               $insert->save();
                        
                          if($insert == true){
                
                
                                  $insert=new Reports;
                                 $insert->user_id=$data['id'];
                               //  $insert->examiner_id=NULL;
                                 $insert->name=$data['first_name'];
                                 $insert->birth_date=$data['birth_date'];
                              //   $insert->examiner_name='';
                                 $insert->test_type='1';
                                 $insert->test_category=$sub;
                                 $insert->type=4;
                                 $insert->nationality=$data['nationality'];
                                // $insert->main_hand=$hand;
                                 $insert->gender=$data['gender'];
                                 $insert->institution=$data['institution_id'];
                                 $insert->app_date=$dateTime;
                                 //$insert->result_code=$code;
                                 $insert->result_id=$insert->id;
                                 $insert->educational_level=$data['level'];
                                 $insert->created_at=$dateTime;
                                 $insert->save();
                             $message['data']=$insert;
                             $message['error']=0;
                             $message['message']='insert success';
                     
                          }else{
                              $message['data']=$insert;
                              $message['error']=1;
                              $message['message']='error in insert';
                
                          }
                         }
                            
                            
                            
                            
                            
                      
                     
                     
                 }
                
                
                
                
            }else{
                //type =2 
                
                
                    if($request->has('user_token')&&$request->has('id')){
                        
                        
                        
                        
                    }elseif($request->has('user_token')&&$request->has('report_id')){
                        
                        
                        
                        
                    }elseif(!$request->has('user_token')&&$request->has('report_id')){
                        
                        
                        
                        
                        
                    }
                
                
                
                
                
            }

               
               
               
               
               
               
               
               
               
               
               
               
               
               
               
               
               
               
               
               
               
               
               


  if($sub ==4){
                $E=0;
                $I=0;
                $N=0;//mshaar
                $S=0;//mofaker
                $F=0;//hadsy
                $T=0;//hsy
                $P=0;//judge
                $J=0;//observe
                 $Fruits = array(); 
         if($group==1){
          foreach ( $list AS $key => $line ) { 
            if (in_array($line['id'],[1,9,17,25,33]) ) { 
                 if( $line['answer']==1)
                   {
                    $E++;
                   }else{
                    $I++;
                   } 

            } 
            if (in_array($line['id'],[5,13,21,29,37]) ) { 
                 if( $line['answer']==1){
                     $I++;
                   }else{
                    $E++;
                   } 
                  
            } 

             if (in_array($line['id'],[2,10,18,26,34]) ) { 
                 if( $line['answer']==1){
                     $N++;
                   }else{
                    $S++;
                   }      
            }

              if (in_array($line['id'],[6,14,22,30,38]) ) { 
                 if( $line['answer']==1){  
                    $S++;
                   }else{ 
                    $N++;
                   } 
            } 
              if (in_array($line['id'],[3,11,19,27,35]) ) { 
                 if( $line['answer']==1){  
                    $F++;
                   }else{
                    $T++;
                   } 
            } 

             if (in_array($line['id'],[7,15,23,31,39]) ) { 
                 if( $line['answer']==1)
                   {
                      $T++;
                    
                   }else{
                   $F++;
                   
                   } 
            } 


              if (in_array($line['id'],[4,12,20,28,36]) ) { 
                 if( $line['answer']==1) {
                      $P++;
                   }else{
                     $J++;
                   
                   }     
            } 
              if (in_array($line['id'],[8,16,24,32,40]) ) { 
                 if( $line['answer']==1){  
                    $J++;
                   }else{
                   
                    $P++;
                   } 

                   
            } 
          }

        }else{
           //1 =>أ و 2=> ب
          foreach ( $list AS $key => $line ) { 

                if (in_array($line['id'],[41,42,43,44,45,46]) ) { 
                     if( $line['answer']==1)
                       {
                        $E++;
                       }else{
                        $I++;
                       } 

                }

                 if (in_array($line['id'],[47,48,49,50,51,52]) ) { 
                     if( $line['answer']==1)
                       {
                        $S++;
                       }else{
                        $N++;
                       } 

                }

                if (in_array($line['id'],[53,54,55,56,57,58]) ) { 
                     if( $line['answer']==1)
                       {
                        $T++;
                       }else{
                        $F++;
                       } 

                }

                   if (in_array($line['id'],[59,60,61,62,63,64]) ) { 
                     if( $line['answer']==1)
                       {
                        $P++;
                       }else{
                        $J++;
                       } 

                }
      }

 }

  $check=Test_result::where([['user_id',$user],['sub_id',$sub],['group_num',$group]])->first();

  if($check !=null){
   $action=Test_result::where([['user_id',$user],['sub_id',$sub],['group_num',$group]])->update([

        'E'=>$E,
        'I'=>$I,
        'S'=>$S,
        'N'=>$N,
        'F'=>$F,
        'T'=>$T,
        'P'=>$P,
        'J'=>$J,
        'updated_at'=>$dateTime 
 
   ]);
  }else{

  
            $action=new Test_result;
            $action->user_id=$user;
            $action->sub_id=$sub;
            $action->group_num=$group;
            $action->E=$E;
            $action->I=$I;
            $action->S=$S;
            $action->N=$N;
            $action->F=$F;
            $action->T=$T;
            $action->P=$P;
            $action->J=$J;
            $action->created_at=$dateTime;
            $action->save();
         
    }
          if($action ==true){


             //for update code of final result
 
               $select=final_result::where('user_id',3)->first(); 
           
            $a='';
            $m='';
            $h='';
            $n='';

            if($select['E']>$select['I']){
              $a='E';
            }else{
              $a='I';
            }

           if($select['N']>$select['S']){
              $h='N';
            }else{
              $h='S';
            }
      

        if($select['F']>$select['T']){
              $m='F';
            }else{
              $m='T';
            }

              if($select['J']>$select['P']){
              $n='J';
            }else{
              $n='P';
            }

            $code=$a.$h.$m.$n;
         
        
            $result_id=final_result::where([['user_id',$user],['sub_id',$sub]])->value('id');
            $update=final_result::where([['user_id',$user],['sub_id',$sub]])->update([
               'code'=>$code,
               'updated_at'=>$dateTime

            ]);

            if($request->has('report_id')){
                $update_report=Reports::where('id',$report)->update([
                   'result_code'=>$code,
                   'result_id'=>$result_id,
                   'updated_at'=>$dateTime
    
    
                ]);
            }
            
            
           
                
            
             $message['data']=$action;
             $message['error']=0;
             $message['message']='insert success';
     
          }else{
              $message['data']=$action;
              $message['error']=1;
              $message['message']='error in insert';

          }
  
        }elseif($sub==1){
             $man_count=Test_questions::where('type','mantky')->pluck('id')->toArray();

             $m_count=Test_questions::where('type','musical')->pluck('id')->toArray();
             $l_count=Test_questions::where('type','logical')->pluck('id')->toArray();
             $b_count=Test_questions::where('type','basary')->pluck('id')->toArray();
           $h_count=Test_questions::where('type','haraky')->pluck('id')->toArray();
            
          $s_count=Test_questions::where('type','social')->pluck('id')->toArray();
            $z_count=Test_questions::where('type','zaty')->pluck('id')->toArray();
             $n_count=Test_questions::where('type','natural')->pluck('id')->toArray();
             $mantky=0;  $mantky_null=0;
            $music=0;    $music_null=0;
            $logistic=0; $logistic_null=0;
            $basary=0;   $basary_null=0;
            $harky=0;    $harky_null=0;
            $social=0;   $social_null=0;
            $zaty=0;     $zaty_null=0;
            $natural=0;  $natural_null=0;
          
          
    //    $slice_mantky=array_slice($list,0,12);
         foreach ($list as $key => $value) {
           

                             if(in_array($value['id'],$man_count) ){
                                 if($value['answer']==0){
                                  $mantky_null=$mantky_null+1;
                                 }
                              $mantky=$mantky+$value['answer'];
                
                
                           }elseif(in_array($value['id'],$m_count)){
                               if($value['answer']==0){
                                  $music_null=$music_null+1;
                                 }
                              $music=$music+$value['answer'];
                
                           }
                           elseif(in_array($value['id'],$l_count)){
                                if($value['answer']==0){
                                  $logistic_null=$logistic_null+1;
                                 }
                              $logistic=$logistic+$value['answer'];
                           }elseif(in_array($value['id'],$b_count)){
                                if($value['answer']==0){
                                  $basary_null=$basary_null+1;
                                 }
                              $basary=$basary+$value['answer'];
                           }elseif(in_array($value['id'],$h_count)){
                               if($value['answer']==0){
                                  $haraky_null=$haraky_null+1;
                                 }
                              $haraky=$haraky+$value['answer'];
                           }elseif(in_array($value['id'],$s_count)){
                                if($value['answer']==0){
                                  $social_null=$social_null+1;
                                 }
                              $social=$social+$value['answer'];
                           }
                           elseif(in_array($value['id'],$z_count)){
                               if($value['answer']==0){
                                  $zaty_null=$zaty_null+1;
                                 }
                              $zaty=$zaty+$value['answer'];
                           }elseif(in_array($value['id'],$n_count)){
                                  if($value['answer']==0){
                                  $natural_null=$natural_null+1;
                                 }
                              $natural=$natural+$value['answer'];
                           }
           
           } 

         $total_mantky=($mantky/((count($man_count)-$mantky_null)*5))*100;
         $total_music=($music/((count($m_count)-$music_null)*5))*100;
         $total_logistic=($logistic/((count($l_count)-$logistic_null)*5))*100;
         $total_basary=($basary/((count($b_count)-$basary_null)*5))*100;
         $total_harky=($harky/((count($h_count)-$haraky_null)*5))*100;
         $total_social=($social/((count($s_count)-$social_null)*5))*100;
          $total_zaty=($zaty/((count($z_count)-$zaty_null)*5))*100;

         $total_natural=($natural/((count($n_count)-$natural_null)*5))*100;

       $insert=new inteligent_testresult;
       $insert->user_id=$user;
       $insert->logical=round($total_mantky,1);
       $insert->musical=round($total_music,1);
       $insert->Linguist=round($total_logistic,1);
       $insert->basary=round($total_basary,1);
       $insert->haraky=round($total_harky,1);
       $insert->social=round($total_social,1);
       $insert->zaty=round($total_zaty,1);
       $insert->naturally=round($total_natural,1);
       $insert->created_at=$dateTime;
       $insert->save();

                  if($insert == true){
        
        
                     $update_report=reports::where('id',$report)->update([
                       
                       'result_id'=>$insert->id,
                       'created_at'=>$dateTime
        
        
                    ]);
                     $message['data']=$insert;
                     $message['error']=0;
                     $message['message']='insert success';
             
                  }else{
                      $message['data']=$insert;
                      $message['error']=1;
                      $message['message']='error in insert';
        
                  }

        }
        
                 
                 
          }catch(Exception $ex){
               $message['error']=2;
               $message['message']='error'.$ex->getMessage();
          }
          
           return response()->json($message);
          
      
          
  }*/

   public function test_questions(Request $request)
   {
      try{
   
          
          
                      $token=$request->input('user_token');
          $report=$request->input('report_id');
          $test=$request->input('sub_id');
          $group=$request->input('group_number');
          $id=$request->input('id');
          $type=$request->input('test_type');          $question=[];
           //  if($lang=='en' || $lang=='EN'){
                 
   $mycount=0;
   
   
   
                            $check_token=User::where('user_token',$token)->first();

   
     $check=user_tests::where([['test_id',$test],['user_id',$check_token['id']]])->first();
   
            if($check !=null){
              
   
   
        if($type ==1){
            
              if($request->has('user_token')){
                          
                         $data=User::where('user_token',$token)->first();
                         
                         
                         
                         $check=Test_answer::where([['user_id',$data['id']],['test_type',1],['test_id',$test]])->orderBy('id','DESC')->first();
                         
                         
                         if($check !=null){
                             
                             $count_data=Test_answer::where([['user_id',$data['id']],['test_type',1],['test_id',$test],['group_num',$group]])->count();
                         
                              if($check['question_id'] >=40 && $check['question_id'] < 239){
                                  
                                   $message['data']=[];
                                   $message['error']=3;
                                   $message['message']='you finished this exam move to next exam';
                                   
                                   return response()->json($message);
                              
                              }elseif($check['question_id'] >= 239){
                                  
                                      $question=Test_questions::select('test_questions.id','test_questions.question')
                               
                                     ->where([['test_questions.test_id','=',$test],['group_num',$group]])->limit(20)->get();
                                     
                                     
                                     $mycount=count($question);
                                               
                                    for($i=0;$i<count($question);$i++){
                        
                                    $question[$i]['answers']=Answer::select('id','answer')
                                         ->where('question_id',0)->get();
                                         
                                         
                                       } 
                                  
                              
                              }else{
    
                             
                                      $question=Test_questions::select('test_questions.id','test_questions.question')
                                   
                                         ->where([['test_questions.id','>',$check['question_id']],['test_id',$test],['group_num',$group]])->limit(20)->get();
                                         $mycount=count($question);
                                                   
                                        for($i=0;$i<count($question);$i++){
                            
                                        $question[$i]['answers']=Answer::select('id','answer')
                                             ->where('question_id',0)->get();
                                             
                                             
                                       }
                              }
                              
                              
                              
                         }else{
                             
                                      $question=Test_questions::select('test_questions.id','test_questions.question')
                               
                                     ->where([['test_questions.test_id','=',$test],['group_num',$group]])->limit(20)->get();
                                     
                                     $mycount=count($question);
                                               
                                    for($i=0;$i<count($question);$i++){
                        
                                    $question[$i]['answers']=Answer::select('id','answer')
                                         ->where('question_id',0)->get();
                                         
                                         
                                       } 
                                     
                             
                         }
                         
                         
                           
                           if(count($question)>0){
                               
                               
                               $message['data']=$question;
                               $message['count']=$mycount;
                               $message['error']=0;
                               $message['message']='show data success';
                           }else{
                               $message['data']=$question;
                               $message['count']=0;
                               $message['error']=1;
                               $message['message']='error in show data';
                               
                           }
              }else{
                     $data=Reports::where('id',$report)->first();
                  
                     $check=Test_answer::where([['user_id',$data['user_id']],['test_type',1],['test_id',$test],['group_num',$group]])->orderBy('id','DESC')->first();
                      
                      if($check !=null){
                          
                          $count_data=Test_answer::where([['user_id',$data['user_id']],['test_type',1],['test_id',$test],['group_num',$group]])->count();
                         
                              if($check['question_id'] >=223 && $check['question_id'] < 239){
                                  
                                  
                                   $message['error']=3;
                                   $message['message']='you finished this exam move to next exam';
                                   
                                   return response()->json($message);
                              
                              }elseif($check['question_id'] >= 239){
                                  
                                      $question=Test_questions::select('test_questions.id','test_questions.question')
                               
                                     ->where([['test_questions.test_id','=',$test],['group_num',$group]])->limit(20)->get();
                                     
                                     $mycount=count($question);
                                               
                                    for($i=0;$i<count($question);$i++){
                        
                                    $question[$i]['answers']=Answer::select('id','answer')
                                         ->where('question_id',0)->get();
                                         
                                         
                                       } 
                                  
                              
                              }else{
                         
                         
                                  $question=Test_questions::select('test_questions.id','test_questions.question')
                               
                                     ->where([['test_questions.id','>',$check['question_id']],['test_id',$test],['group_num',$group]])->limit(20)->get();
                                     $mycount=count($question);
                                               
                                    for($i=0;$i<count($question);$i++){
                        
                                    $question[$i]['answers']=Answer::select('id','answer')
                                         ->where('question_id',0)->get();
                                         
                                         
                                   }
                              }
                         }else{
                             
                                      $question=Test_questions::select('test_questions.id','test_questions.question')
                               
                                     ->where([['test_questions.test_id','=',$test],['group_num',$group]])->limit(20)->get();
                                     $mycount=count($question);
                                               
                                    for($i=0;$i<count($question);$i++){
                        
                                    $question[$i]['answers']=Answer::select('id','answer')
                                         ->where('question_id',0)->get();
                                         
                                         
                                       } 
                                     
                             
                         }
                           
                              if(count($question)>0){
                               $message['data']=$question;
                               $message['count']=$mycount;
                               $message['error']=0;
                               $message['message']='show data success';
                           }else{
                               $message['data']=$question;
                               $message['count']=0;
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
                                  
                                  
                                   $message['error']=3;
                                   $message['message']='you finished this exam move to next exam';
                                   
                                   return response()->json($message);
                              
                              }elseif($check['question_id'] >= 239){
                                  
                                      $question=Test_questions::select('test_questions.id','test_questions.question')
                               
                                     ->where([['test_questions.test_id','=',$test],['group_num',$group]])->limit(20)->get();
                                     $mycount=count($question);
                                               
                                    for($i=0;$i<count($question);$i++){
                        
                                    $question[$i]['answers']=Answer::select('id','answer')
                                         ->where('question_id',0)->get();
                                         
                                         
                                       } 
                              
                              }else{
                         
                         
                                  $question=Test_questions::select('test_questions.id','test_questions.question')
                               
                                     ->where([['test_questions.id','>',$check['question_id']],['test_id',$test],['group_num',$group]])->limit(20)->get();
                                               
                                               $mycount=count($question);
                                    for($i=0;$i<count($question);$i++){
                        
                                    $question[$i]['answers']=Answer::select('id','answer')
                                         ->where('question_id',0)->get();
                                         
                                         
                                   }
                              }
                         }else{
                             
                                      $question=Test_questions::select('test_questions.id','test_questions.question')
                               
                                     ->where([['test_questions.test_id','=',$test],['group_num',$group]])->limit(20)->get();
                                               
                                               $mycount=count($question);
                                    for($i=0;$i<count($question);$i++){
                        
                                    $question[$i]['answers']=Answer::select('id','answer')
                                         ->where('question_id',0)->get();
                                         
                                         
                                       } 
                                     
                             
                         }
                         
            
                          if(count($question)>0){
                               $message['data']=$question;
                               $message['count']=$mycount;
                               $message['error']=0;
                               $message['message']='show data success';
                           }else{
                               $message['data']=$question;
                               $message['count']=0;
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
                                     $mycount=count($question);
                                               
                                    for($i=0;$i<count($question);$i++){
                        
                                    $question[$i]['answers']=Answer::select('id','answer')
                                         ->where('question_id',0)->get();
                                         
                                         
                                       } 
                              }else{
                         
                         
                                  $question=Test_questions::select('test_questions.id','test_questions.question')
                               
                                     ->where([['test_questions.id','>',$check['question_id']],['test_id',$test],['group_num',$group]])->limit(20)->get();
                                     $mycount=count($question);
                                               
                                    for($i=0;$i<count($question);$i++){
                        
                                    $question[$i]['answers']=Answer::select('id','answer')
                                         ->where('question_id',0)->get();
                                         
                                         
                                   }
                              }
                         }else{
                             
                                      $question=Test_questions::select('test_questions.id','test_questions.question')
                               
                                     ->where([['test_questions.test_id','=',$test],['group_num',$group]])->limit(20)->get();
                                               $mycount=count($question);
                                    for($i=0;$i<count($question);$i++){
                        
                                    $question[$i]['answers']=Answer::select('id','answer')
                                         ->where('question_id',0)->get();
                                         
                                         
                                       } 
                                     
                             
                         }
                    
                         if(count($question)>0){
                               $message['data']=$question;
                               $message['count']=$mycount;
                               $message['error']=0;
                               $message['message']='show data success';
                           }else{
                               $message['data']=$question;
                               $message['count']=0;
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
                                     $mycount=count($question);
                                               
                                    for($i=0;$i<count($question);$i++){
                        
                                    $question[$i]['answers']=Answer::select('id','answer')
                                         ->where('question_id',0)->get();
                                         
                                         
                                       } 
                              
                              }else{
                                  $question=Test_questions::select('test_questions.id','test_questions.question')
                               
                                     ->where([['test_questions.id','>',$check['question_id']],['test_id',$test],['group_num',$group]])->limit(20)->get();
                                     $mycount=count($question);
                                               
                                    for($i=0;$i<count($question);$i++){
                        
                                    $question[$i]['answers']=Answer::select('id','answer')
                                         ->where('question_id',0)->get();
                                         
                                         
                                   }
                              }
                         }else{
                             
                                      $question=Test_questions::select('test_questions.id','test_questions.question')
                               
                                     ->where([['test_questions.test_id','=',$test],['group_num',$group]])->limit(20)->get();
                                     $mycount=count($question);
                                               
                                    for($i=0;$i<count($question);$i++){
                        
                                    $question[$i]['answers']=Answer::select('id','answer')
                                         ->where('question_id',0)->get();
                                         
                                         
                                       } 
                                     
                             
                         }
                             if(count($question)>0){
                               $message['data']=$question;
                               $message['count']=$mycount;
                               $message['error']=0;
                               $message['message']='show data success';
                           }else{
                               $message['data']=$question;
                               $message['count']=0;
                               $message['error']=1;
                               $message['message']='error in show data';
                               
                           }
                      
                      
                }
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
        }
                     
               }else{
                    $message['error']=4;
                    $message['message']='please buy this exam';
               }
          
            
            }catch(Exception $ex){
               $message['error']=2;
               $message['message']='error'.$ex->getMessage();
            }
      
            return response()->json($message);
    }
    
    
    
    


  public function testA_questions_group2(Request $request)
 {
      try{
           $test=$request->input('sub_id');
         // $lang=$request->input('lang');
        
         
            // if($lang=='ar' || $lang=='AR'){
                 

      $question=Test_questions::select('test_questions.id','test_questions.question')
                       
         ->where([['test_questions.test_id','=',$test],['group_num','=',2]])->limit(20)->get();
                       
             for($i=0;$i<count($question);$i++){

               $question[$i]['answers']=Answer::select('id','answers.answer')
               ->where('answers.question_id',$question[$i]->id)->get();
                            
                }
                       
                       
          /*    }else{
               

                   $question=Test_questions::select('test_questions.id','test_questions.E_question as question')
         
                     ->where([['test_questions.sub_id','=',4],['group_num','=',2]])->get();
                  
                   for($i=0;$i<count($question);$i++){
                     $question[$i]['answers']=Answer::select('answers.E_answer as answer')->where('answers.question_id',$question[$i]->id)
                       ->get();

                            
                        }
              }*/
               $count=Test_questions::where([['test_questions.sub_id','=',4],['group_num','=',2]])->count();

              if(count($question)>0){
               
                   $message['data']=$question;
                   $message['count']=16;
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


 public function testB_questions(Request $request)
 {
      try{
         
          $lang=$request->input('lang');
        
         
        //     if($lang=='ar' || $lang=='AR'){
                 
      
      $question=Test_questions::select('test_questions.id','test_questions.question')
                       
         ->where('test_questions.sub_id','=',1)->get();
                       
        for($i=0;$i<count($question);$i++){

         $question[$i]['answers']=Answer::select('id','answer')
         ->where('question_id',0)->get();
       }
                            
              
                       
                       
         /*     }else{
               
     $question=Test_questions::select('test_questions.id','test_questions.E_question as question')
                       
         ->where('test_questions.sub_id','=',1)->get();
                       
            
         $answers=Answer::select('id','E_answer as answer')
         ->whereIn('id',[60,61,62,63,64,65])->get();
                        
              }*/
      $count=Test_questions::where('test_questions.sub_id','=',1)->count();

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

}
