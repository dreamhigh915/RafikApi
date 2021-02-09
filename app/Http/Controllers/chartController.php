<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use carbon\carbon;
use App\Classes;
use App\Institution_branch;
use Illuminate\Support\Facades\DB;
use App\Leveles;
use App\Teacher_class;
use App\Rating;
use App\Reports;
use App\ reports_type;
use App\exam_result;
use App\Test_answer;
use App\test_code;

class chartController extends Controller
{
    public $message=array();
    
    
       

  
    
          public function show_mychart(Request $request){
      try{
             $id=57;
             //E
             
         $get_chart = test_code::select('name as label')->whereIn('id',range(18,23))->get();

          
              
                  $all_price = array();
                  $price = array();
                  $date = array();
                        $my_arr=array();

                  $report=Reports::where('id',$id)->first();
                  $i=3;
                    
           for($j=7;$j<=43;$j++){
      ///A
      $A=Test_answer::where([['cat_id',$i],['sub_id',$j],['user_id', $report['user_id']],['test_id',$report['test_category']]])->count();//a count
       //     echo 'A ='.$A .'<br>';

      $a_answered=Test_answer::where([['cat_id',$i],['sub_id',$j],['user_id', $report['user_id']],['test_id',$report['test_category']],['answer','!=',NULL]])->count();

      //  echo 'a answerd ='.$a_answered .'<br>';

        $a_accept=Test_answer::where([['cat_id',$i],['sub_id',$j],['user_id', $report['user_id']],['test_id',$report['test_category']],['answer','=',1]])->count();

        //  echo 'a_accept ='.$a_accept .'<br>';

         $a_refused=Test_answer::where([['cat_id',$i],['sub_id',$j],['user_id', $report['user_id']],['test_id',$report['test_category']],['answer','=',2]])->count();

         //  echo 'a_refused ='.$a_refused .'<br>';

          $a_dont=Test_answer::where([['cat_id',$i],['sub_id',$j],['user_id', $report['user_id']],['test_id',$report['test_category']],['answer','=',3]])->count();

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

          array_push($my_arr,ceil($accept_precentage));

}

        }
        
        for($i=0;$i<count($get_chart);$i++){
            
            $get_chart[$i]['y']=$my_arr[$i];
        }
                
                  
                            
                   // $message['degree'] = $my_arr;
                    $message=$get_chart;
            
        
          
      }catch(Exception $ex){
              $message['error']=3;
             $message['message']='error'.$ex->getMessage();

    }

    return response()->json($message);
  }

    
    
    
    
      public function code_chart(Request $request){
      try{
          
        $message=array();
              $arr=array();
                  $all_price = array();
                  $price = array();
                  $date = array();
                  
                  $get_chart = test_code::whereIn('id',[1,2,3,4,5,6])->pluck('name')->toArray();
         
                    
            $message['Date'] = $get_chart;
                 
                
            
         
          
      }catch(Exception $ex){
              $message['error']=3;
             $message['message']='error'.$ex->getMessage();

    }

    return response()->json($message);
  }
  

    
     public function main_degree(Request $request){
      try{
             $id=$request->input('report_id');
           
          
              
                  $all_price = array();
                  $price = array();
                  $date = array();
                        $dd_arr=array();

                  $report=Reports::where('id',$id)->first();
                  
                     for($i=1;$i<=6;$i++){
      ///A
              $A=Test_answer::where([['cat_id',$i],['user_id', $report['user_id']],['test_id',$report['test_category']]])->count();//a count
                //    echo 'A ='.$A .'<br>';
        
              $a_answered=Test_answer::where([['cat_id',$i],['user_id', $report['user_id']],['answer','!=',NULL],['test_id',$report['test_category']]])->count();
        
              //  echo 'a answerd ='.$a_answered .'<br>';
        
                $a_accept=Test_answer::where([['cat_id',$i],['user_id', $report['user_id']],['answer','=',1],['test_id',$report['test_category']]])->count();
        
               //   echo 'a_accept ='.$a_accept .'<br>';
        
                 $a_refused=Test_answer::where([['cat_id',$i],['user_id',$report['user_id']],['answer','=',2],['test_id',$report['test_category']]])->count();
        
               //    echo 'a_refused ='.$a_refused .'<br>';
        
                  $a_dont=Test_answer::where([['cat_id',$i],['user_id', $report['user_id']],['answer','=',3],['test_id',$report['test_category']]])->count();
        
               //     echo 'a_dont ='.$a_dont .'<br>';
        
                    
                    $total_dont=$a_answered - $a_dont;
                    
                     //echo 'total - dont ='.$total_dont .'<br>';
            
                     if($total_dont !=0){
                              $accept_precentage=round(($a_accept/$total_dont)*100,2);
                     }else{
                     	$accept_precentage=0;
                     }
                    
            
                  //    echo $accept_precentage .'<br>';
            
            $select1=test_code::where('id',$i)->value('code');
              array_push($dd_arr,ceil($accept_precentage));
}
        $arr=array();
                      array_push($arr,
                    
                        (object)array(
                                'data'   =>  $dd_arr
                        )
                    ); 
                    
            $message['all'] = $arr;
         
        
          
      }catch(Exception $ex){
              $message['error']=3;
             $message['message']='error'.$ex->getMessage();

    }

    return response()->json($message);
  }
  
  
  
  
  
       public function A_degree(Request $request){
      try{
             $id=$request->input('report_id');
           
          
              
                  $all_price = array();
                  $price = array();
                  $date = array();
                        $my_arr=array();

                  $report=Reports::where('id',$id)->first();
                  $i=1;
                    
           for($j=7;$j<=43;$j++){
      ///A
      $A=Test_answer::where([['cat_id',$i],['sub_id',$j],['user_id', $report['user_id']],['test_id',$report['test_category']]])->count();//a count
       //     echo 'A ='.$A .'<br>';

      $a_answered=Test_answer::where([['cat_id',$i],['sub_id',$j],['user_id', $report['user_id']],['test_id',$report['test_category']],['answer','!=',NULL]])->count();

      //  echo 'a answerd ='.$a_answered .'<br>';

        $a_accept=Test_answer::where([['cat_id',$i],['sub_id',$j],['user_id', $report['user_id']],['test_id',$report['test_category']],['answer','=',1]])->count();

        //  echo 'a_accept ='.$a_accept .'<br>';

         $a_refused=Test_answer::where([['cat_id',$i],['sub_id',$j],['user_id', $report['user_id']],['test_id',$report['test_category']],['answer','=',2]])->count();

         //  echo 'a_refused ='.$a_refused .'<br>';

          $a_dont=Test_answer::where([['cat_id',$i],['sub_id',$j],['user_id', $report['user_id']],['test_id',$report['test_category']],['answer','=',3]])->count();

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

          array_push($my_arr,ceil($accept_precentage));

}

        }
          $arr=array();
                      array_push($arr,
                    
                        (object)array(
                                'data'   =>  $my_arr
                        )
                    ); 
                    
            $message['all'] = $arr;
        
          
      }catch(Exception $ex){
              $message['error']=3;
             $message['message']='error'.$ex->getMessage();

    }

    return response()->json($message);
  }
  public function A_chart(Request $request){
      try{
          
        
              
                  $arr = array();
                  $price = array();
                  $date = array();
                  
                  $get_chart = test_code::whereIn('id',[7,8,9,10,11,12])->pluck('name')->toArray();
        
                 
                $message['Date']=$get_chart;
          
      }catch(Exception $ex){
              $message['error']=3;
             $message['message']='error'.$ex->getMessage();

    }

    return response()->json($message);
  }
  
  //B
       public function C_degree(Request $request){
      try{
             $id=$request->input('report_id');
           
          
              
                  $all_price = array();
                  $price = array();
                  $date = array();
                        $my_arr=array();

                  $report=Reports::where('id',$id)->first();
                  $i=2;
                    
           for($j=7;$j<=43;$j++){
      ///A
      $A=Test_answer::where([['cat_id',$i],['sub_id',$j],['user_id', $report['user_id']],['test_id',$report['test_category']]])->count();//a count
       //     echo 'A ='.$A .'<br>';

      $a_answered=Test_answer::where([['cat_id',$i],['sub_id',$j],['user_id', $report['user_id']],['test_id',$report['test_category']],['answer','!=',NULL]])->count();

      //  echo 'a answerd ='.$a_answered .'<br>';

        $a_accept=Test_answer::where([['cat_id',$i],['sub_id',$j],['user_id', $report['user_id']],['test_id',$report['test_category']],['answer','=',1]])->count();

        //  echo 'a_accept ='.$a_accept .'<br>';

         $a_refused=Test_answer::where([['cat_id',$i],['sub_id',$j],['user_id', $report['user_id']],['test_id',$report['test_category']],['answer','=',2]])->count();

         //  echo 'a_refused ='.$a_refused .'<br>';

          $a_dont=Test_answer::where([['cat_id',$i],['sub_id',$j],['user_id', $report['user_id']],['test_id',$report['test_category']],['answer','=',3]])->count();

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

          array_push($my_arr,ceil($accept_precentage));

}

        }
        
          $arr=array();
                      array_push($arr,
                    
                        (object)array(
                                'data'   =>  $my_arr
                        )
                    ); 
                    
            $message['all'] = $arr;
            
        
          
      }catch(Exception $ex){
              $message['error']=3;
             $message['message']='error'.$ex->getMessage();

    }

    return response()->json($message);
  }
  public function C_chart(Request $request){
      try{
          
        
              
                  $arr= array();
                  $price = array();
                  $date = array();
                  
                  $get_chart = test_code::whereIn('id',range(13,17))->pluck('name')->toArray();
        
               
                    
            $message['Date'] = $get_chart;
            
         
          
      }catch(Exception $ex){
              $message['error']=3;
             $message['message']='error'.$ex->getMessage();

    }

    return response()->json($message);
  }
  
  
  
  
      public function E_degree(Request $request){
      try{
             $id=$request->input('report_id');
           
          
              
                  $all_price = array();
                  $price = array();
                  $date = array();
                        $my_arr=array();

                  $report=Reports::where('id',$id)->first();
                  $i=3;
                    
           for($j=7;$j<=43;$j++){
      ///A
      $A=Test_answer::where([['cat_id',$i],['sub_id',$j],['user_id', $report['user_id']],['test_id',$report['test_category']]])->count();//a count
       //     echo 'A ='.$A .'<br>';

      $a_answered=Test_answer::where([['cat_id',$i],['sub_id',$j],['user_id', $report['user_id']],['test_id',$report['test_category']],['answer','!=',NULL]])->count();

      //  echo 'a answerd ='.$a_answered .'<br>';

        $a_accept=Test_answer::where([['cat_id',$i],['sub_id',$j],['user_id', $report['user_id']],['test_id',$report['test_category']],['answer','=',1]])->count();

        //  echo 'a_accept ='.$a_accept .'<br>';

         $a_refused=Test_answer::where([['cat_id',$i],['sub_id',$j],['user_id', $report['user_id']],['test_id',$report['test_category']],['answer','=',2]])->count();

         //  echo 'a_refused ='.$a_refused .'<br>';

          $a_dont=Test_answer::where([['cat_id',$i],['sub_id',$j],['user_id', $report['user_id']],['test_id',$report['test_category']],['answer','=',3]])->count();

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

          array_push($my_arr,ceil($accept_precentage));

}

        }
        
        
            $arr=array();
                      array_push($arr,
                    
                        (object)array(
                                'data'   =>  $my_arr
                        )
                    ); 
                    
            $message['all'] = $arr;
            
        
          
      }catch(Exception $ex){
              $message['error']=3;
             $message['message']='error'.$ex->getMessage();

    }

    return response()->json($message);
  }
  public function E_chart(Request $request){
      try{
          
        
              
                  $all_price = array();
                  $price = array();
                  $date = array();
                  
                  $get_chart = test_code::whereIn('id',range(18,23))->pluck('name')->toArray();
        
                 
                  
                            
                    $message['Date'] = $get_chart;
            
         
          
      }catch(Exception $ex){
              $message['error']=3;
             $message['message']='error'.$ex->getMessage();

    }

    return response()->json($message);
  }
  
  
  
    public function I_degree(Request $request){
      try{
             $id=$request->input('report_id');
           
          
              
                  $all_price = array();
                  $price = array();
                  $date = array();
                        $my_arr=array();

                  $report=Reports::where('id',$id)->first();
                  $i=4;
                    
           for($j=7;$j<=43;$j++){
      ///A
      $A=Test_answer::where([['cat_id',$i],['sub_id',$j],['user_id', $report['user_id']],['test_id',$report['test_category']]])->count();//a count
       //     echo 'A ='.$A .'<br>';

      $a_answered=Test_answer::where([['cat_id',$i],['sub_id',$j],['user_id', $report['user_id']],['test_id',$report['test_category']],['answer','!=',NULL]])->count();

      //  echo 'a answerd ='.$a_answered .'<br>';

        $a_accept=Test_answer::where([['cat_id',$i],['sub_id',$j],['user_id', $report['user_id']],['test_id',$report['test_category']],['answer','=',1]])->count();

        //  echo 'a_accept ='.$a_accept .'<br>';

         $a_refused=Test_answer::where([['cat_id',$i],['sub_id',$j],['user_id', $report['user_id']],['test_id',$report['test_category']],['answer','=',2]])->count();

         //  echo 'a_refused ='.$a_refused .'<br>';

          $a_dont=Test_answer::where([['cat_id',$i],['sub_id',$j],['user_id', $report['user_id']],['test_id',$report['test_category']],['answer','=',3]])->count();

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

          array_push($my_arr,ceil($accept_precentage));

}

        }
        
        
                 $arr=array();
                      array_push($arr,
                    
                        (object)array(
                                'data'   =>  $my_arr
                        )
                    ); 
                    
            $message['all'] = $arr;
        
          
      }catch(Exception $ex){
              $message['error']=3;
             $message['message']='error'.$ex->getMessage();

    }

    return response()->json($message);
  }
  public function I_chart(Request $request){
      try{
          
        
              
                  $all_price = array();
                  $price = array();
                  $date = array();
                  
                  $get_chart = test_code::whereIn('id',range(24,32))->pluck('name')->toArray();
        
                 
                  
                            
                    $message['Date'] = $get_chart;
            
         
          
      }catch(Exception $ex){
              $message['error']=3;
             $message['message']='error'.$ex->getMessage();

    }

    return response()->json($message);
  }
  
  
      public function R_degree(Request $request){
      try{
             $id=$request->input('report_id');
           
          
              
                  $all_price = array();
                  $price = array();
                  $date = array();
                        $my_arr=array();

                  $report=Reports::where('id',$id)->first();
                  $i=5;
                    
           for($j=7;$j<=43;$j++){
      ///A
      $A=Test_answer::where([['cat_id',$i],['sub_id',$j],['user_id', $report['user_id']],['test_id',$report['test_category']]])->count();//a count
       //     echo 'A ='.$A .'<br>';

      $a_answered=Test_answer::where([['cat_id',$i],['sub_id',$j],['user_id', $report['user_id']],['test_id',$report['test_category']],['answer','!=',NULL]])->count();

      //  echo 'a answerd ='.$a_answered .'<br>';

        $a_accept=Test_answer::where([['cat_id',$i],['sub_id',$j],['user_id', $report['user_id']],['test_id',$report['test_category']],['answer','=',1]])->count();

        //  echo 'a_accept ='.$a_accept .'<br>';

         $a_refused=Test_answer::where([['cat_id',$i],['sub_id',$j],['user_id', $report['user_id']],['test_id',$report['test_category']],['answer','=',2]])->count();

         //  echo 'a_refused ='.$a_refused .'<br>';

          $a_dont=Test_answer::where([['cat_id',$i],['sub_id',$j],['user_id', $report['user_id']],['test_id',$report['test_category']],['answer','=',3]])->count();

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

          array_push($my_arr,ceil($accept_precentage));

}

        }
        
        
                 $arr=array();
                      array_push($arr,
                    
                        (object)array(
                                'data'   =>  $my_arr
                        )
                    ); 
                    
            $message['all'] = $arr;
            
        
          
      }catch(Exception $ex){
              $message['error']=3;
             $message['message']='error'.$ex->getMessage();

    }

    return response()->json($message);
  }
  public function R_chart(Request $request){
      try{
          
        
              
                  $all_price = array();
                  $price = array();
                  $date = array();
                  
                  $get_chart = test_code::whereIn('id',range(33,37))->pluck('name')->toArray();
        
                 
                  
                            
                    $message['Date'] = $get_chart;
            
         
          
      }catch(Exception $ex){
              $message['error']=3;
             $message['message']='error'.$ex->getMessage();

    }

    return response()->json($message);
  }
  
  
  
   public function S_degree(Request $request){
      try{
             $id=$request->input('report_id');
           
          
              
                  $all_price = array();
                  $price = array();
                  $date = array();
                        $my_arr=array();

                  $report=Reports::where('id',$id)->first();
                  $i=6;
                    
           for($j=7;$j<=43;$j++){
      ///A
      $A=Test_answer::where([['cat_id',$i],['sub_id',$j],['user_id', $report['user_id']],['test_id',$report['test_category']]])->count();//a count
       //     echo 'A ='.$A .'<br>';

      $a_answered=Test_answer::where([['cat_id',$i],['sub_id',$j],['user_id', $report['user_id']],['test_id',$report['test_category']],['answer','!=',NULL]])->count();

      //  echo 'a answerd ='.$a_answered .'<br>';

        $a_accept=Test_answer::where([['cat_id',$i],['sub_id',$j],['user_id', $report['user_id']],['test_id',$report['test_category']],['answer','=',1]])->count();

        //  echo 'a_accept ='.$a_accept .'<br>';

         $a_refused=Test_answer::where([['cat_id',$i],['sub_id',$j],['user_id', $report['user_id']],['test_id',$report['test_category']],['answer','=',2]])->count();

         //  echo 'a_refused ='.$a_refused .'<br>';

          $a_dont=Test_answer::where([['cat_id',$i],['sub_id',$j],['user_id', $report['user_id']],['test_id',$report['test_category']],['answer','=',3]])->count();

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

          array_push($my_arr,ceil($accept_precentage));

}

        }
        
        
                
                  
                 $arr=array();
                      array_push($arr,
                    
                        (object)array(
                                'data'   =>  $my_arr
                        )
                    ); 
                    
            $message['all'] = $arr;
            
        
          
      }catch(Exception $ex){
              $message['error']=3;
             $message['message']='error'.$ex->getMessage();

    }

    return response()->json($message);
  }
  public function S_chart(Request $request){
      try{
          
        
              
                  $all_price = array();
                  $price = array();
                  $date = array();
                  
                  $get_chart = test_code::whereIn('id',range(38,43))->pluck('name')->toArray();
        
                 
                  
                            
                    $message['Date'] = $get_chart;
            
         
          
      }catch(Exception $ex){
              $message['error']=3;
             $message['message']='error'.$ex->getMessage();

    }

    return response()->json($message);
  }
}
