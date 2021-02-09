<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Reservation;
use App\Rating;
use App\Timeline;
use Illuminate\Support\Facades\DB;
use App\Service;
use App\Certificate;
use App\Articles;
use carbon\carbon;
use App\Trainer_Language;
use App\Trainer_specialist;

class TrainerController extends Controller
{
      	 public $message=array();
      	 
      	 
    public function make_rate(Request $request)
    {
      try{

         $token=$request->input('user_token');
          $trainer=$request->input('trainer_id');
          
          
          $check_token=User::select('id')->where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
               
        $rate=$request->input('rate');
        $comment=$request->input('comment');
   
      

      $created_at = carbon::now()->toDateTimeString();
      $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($created_at)));
      
      
                  $check=Rating::where([['user_id',$check_token['id']],['trainer_id',$trainer]])->get();
                  if(count($check)>0){
                       
                        
                      $insert=Rating::where([['user_id',$check_token['id']],['trainer_id',$trainer]])->update([
                          
                            
                    'rate'=>$rate,
                     'comment'=>$comment,
      
                    'updated_at'=>$dateTime
                          
                          ]);
      
                  }else{
                     
    $insert=new Rating;
      $insert->user_id=$check_token['id'];
       $insert->trainer_id=$trainer;
       $insert->rate=$rate;
        $insert->comment=$comment;
      
         $insert->created_at=$dateTime;
          $insert->save();

      
                  }
                  
                   if($insert ==true){

          
               
                 $message['error']=0;
                 $message['message']='make rate successfuly';

                }else{
                        
                         $message['error']=1;
                         $message['message']='can not make rate';
        
                } 

    
      }else{
           $message['error']=3;
           $message['message']='this token is not exist';

      }


      }catch(Exception  $ex){
         $message['error']=2;
             $message['message']='error'.$ex->getMessage();

      }
      return response()->json($message);
    }
      	 
//            $art=Articles::select('link')->where('trainer_id',$id)->get();

    public function show_Trainer(Request $request)
    {
       try{
        
        
          $userexist=DB::select('select DISTINCT users.id,users.first_name,users.last_name,users.image,users.surname,appreviation.name as appreviation,users.rate,(SELECT MIN(timeline.price)from timeline WHERE timeline.trainer_id=users.id) as price from users
        
        left JOIN appreviation ON users.appreviation =appreviation.id
         where users.state=4');

          if(count($userexist)>0){

            $message['data']=$userexist;
            $message['error']=0;
            $message['message']='user data';

          }else{
            $message['data']=$userexist;
            $message['error']=1;
            $message['message']='no data for user ';
          }

       }catch(Exception $ex){
         
            $message['error']=2;
            $message['message']='error'.$ex->getMessage();

       }
       return response()->json($message);
    }
    
    
    //  $cert=Certificate::select('name')->where('trainer_id',$id)->get();
    
    
      public function show_certificate(Request $request)
    {
       try{
        
         $token=$request->input('user_token');
        
          
          
          $check_token=User::select('id')->where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
              
      $created_at = carbon::now()->toDateTimeString();
      $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($created_at)));
               
        
          $art=Certificate::select('id','name','from_institution','created_at','updated_at')->where('trainer_id',$check_token['id'])->get();

          if(count($art)>0){

            $message['data']=$art;
            $message['error']=0;
            $message['message']='show data';

          }else{
            $message['data']=$art;
            $message['error']=1;
            $message['message']='no data for you ';
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
    
      public function show_certificateByid(Request $request)
    {
       try{
        
         $token=$request->input('user_token');
          $id=$request->input('id');
          
          
          $check_token=User::select('id')->where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
              
      $created_at = carbon::now()->toDateTimeString();
      $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($created_at)));
               
        
          $art=Certificate::select('id','name','from_institution','created_at','updated_at')->where('id',$id)->first();

          if($art !=null){

            $message['data']=$art;
            $message['error']=0;
            $message['message']='show data';

          }else{
            $message['data']=$art;
            $message['error']=1;
            $message['message']='no data for you ';
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
    
     public function delete_certificate(Request $request)
    {
       try{
        
         $token=$request->input('user_token');
          $id=$request->input('id');
          
          
          $check_token=User::select('id')->where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
              
      $created_at = carbon::now()->toDateTimeString();
      $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($created_at)));
               
        
          $art=Certificate::where('id',$id)->delete();

          if($art==true){

         
            $message['error']=0;
            $message['message']='delete success';

          }else{
           
            $message['error']=1;
            $message['message']='error in delete ';
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
    
     public function insert_certficate(Request $request)
    {
       try{
        
          $token=$request->input('user_token');
          $link=$request->input('name');
          $from=$request->input('from_institution');
          
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
              
      $created_at = carbon::now()->toDateTimeString();
      $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($created_at)));
               
        
        $check=Certificate::where([['name',$link],['from_institution',$from]])->get();
        
        if($request->has('name') && $request->has('from_institution')){
        if(count($check)>0){
            $message['error']=4;
            $message['message']='this ceritifcate exists';
            
        }else{
          $art=new Certificate;
          $art->name=$link;
          $art->from_institution=$from;
          $art->trainer_id=$check_token['id'];
          $art->created_at=$dateTime;
          $art->save();

          if($art ==true){

            //['data']=$art;
            $message['error']=0;
            $message['message']='success';

          }else{
           // $message['data']=$art;
            $message['error']=1;
            $message['message']='error in insert data';
          }
        }
        }else{
            $message['error'] = 1;
            $message['message'] = "please fill all the data";
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
    
     public function update_certificate(Request $request)
    {
       try{
        
         $token=$request->input('user_token');
         $id=$request->input('id');
          $link=$request->input('name');
          $from=$request->input('from_institution');
          
          
          $check_token=User::select('id')->where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
              
      $created_at = carbon::now()->toDateTimeString();
      $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($created_at)));
               
        
          $art=Certificate::where('id',$id)->update([
                'name'=>$link,
                'from_institution'=>$from,
                'updated_at'=>$dateTime,
              ]);
         
          

          if($art ==true){

          
            $message['error']=0;
            $message['message']='updated data success';

          }else{
            
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
    
    
    
    
    
    //articles
     public function show_articles(Request $request)
    {
       try{
        
         $token=$request->input('user_token');
        
          
          
          $check_token=User::select('id')->where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
              
      $created_at = carbon::now()->toDateTimeString();
      $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($created_at)));
               
        
          $art=Articles::select('id','link','created_at','updated_at')->where('trainer_id',$check_token['id'])->get();

          if(count($art)>0){

            $message['data']=$art;
            $message['error']=0;
            $message['message']='show data';

          }else{
            $message['data']=$art;
            $message['error']=1;
            $message['message']='no data for you ';
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
    
      public function show_articleByid(Request $request)
    {
       try{
        
         $token=$request->input('user_token');
          $id=$request->input('article_id');
          
          
          $check_token=User::select('id')->where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
              
      $created_at = carbon::now()->toDateTimeString();
      $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($created_at)));
               
        
          $art=Articles::select('id','link','created_at','updated_at')->where('id',$id)->first();

          if($art !=null){

            $message['data']=$art;
            $message['error']=0;
            $message['message']='show data';

          }else{
            $message['data']=$art;
            $message['error']=1;
            $message['message']='no data for you ';
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
    
     public function delete_article(Request $request)
    {
       try{
        
         $token=$request->input('user_token');
          $id=$request->input('article_id');
          
          
          $check_token=User::select('id')->where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
              
      $created_at = carbon::now()->toDateTimeString();
      $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($created_at)));
               
        
          $art=Articles::where('id',$id)->delete();

          if($art==true){

           
            $message['error']=0;
            $message['message']='delete success';

          }else{
           
            $message['error']=1;
            $message['message']='error in delete ';
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
    
     public function insert_article(Request $request)
    {
       try{
        
         $token=$request->input('user_token');
          $link=$request->input('link');
          
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
              
      $created_at = carbon::now()->toDateTimeString();
      $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($created_at)));
               
        
        $check=Articles::where('link',$link)->get();
        
        if(count($check)>0){
            
        }else{
          $art=new Articles;
          $art->link=$link;
          $art->trainer_id=$check_token['id'];
          $art->created_at=$dateTime;
          $art->save();

          if($art ==true){

            $message['data']=$art;
            $message['error']=4;
            $message['message']='this link is exist';

          }else{
            $message['data']=$art;
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
    
     public function update_article(Request $request)
    {
       try{
        
         $token=$request->input('user_token');
         $id=$request->input('id');
          $link=$request->input('link');
          
          
          $check_token=User::select('id')->where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
              
      $created_at = carbon::now()->toDateTimeString();
      $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($created_at)));
               
        
          $art=Articles::where('id',$id)->update([
                'linlk'=>$link,
                'updated_at'=>$dateTime,
              ]);
         
          

          if($art ==true){

          
            $message['error']=0;
            $message['message']='updated data success';

          }else{
            
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
    
    
    
    
    
    
    
    
    
     public function sort_Trainer(Request $request)
    {
       try{
          $sort=$request->input('sort_id');//1->rate ,2->DESC ,3->ASC
        
          
            switch ($sort) {
    	case '1':
    		   $userexist=DB::select('select DISTINCT users.id,users.first_name,users.last_name,users.image,users.surname,appreviation.name as appreviation,users.rate,(SELECT MIN(service.price)from service WHERE service.trainer_id=users.id) as price from users
         JOIN service ON users.id =service.trainer_id
        JOIN appreviation ON users.appreviation =appreviation.id
         where users.state=4 ORDER BY users.rate DESC');
    		 
    		break;
    	case '2':
    		  $userexist=DB::select('select DISTINCT users.id,users.first_name,users.last_name,users.image,users.surname,appreviation.name as appreviation,users.rate,(SELECT MIN(service.price)from service WHERE service.trainer_id=users.id) as price from users
         JOIN service ON users.id =service.trainer_id
        JOIN appreviation ON users.appreviation =appreviation.id
         where users.state=4 ORDER BY users.first_name DESC');
    		
    		break;
    	case '3':
    		  $userexist=DB::select('select DISTINCT users.id,users.first_name,users.last_name,users.image,users.surname,appreviation.name as appreviation,users.rate,(SELECT MIN(service.price)from service WHERE service.trainer_id=users.id) as price from users
         JOIN service ON users.id =service.trainer_id
        JOIN appreviation ON users.appreviation =appreviation.id
         where users.state=4 ORDER BY users.first_name ASC');
    		
    		break;
    	
    	default:
    		
    		  $userexist=DB::select('select DISTINCT users.id,users.first_name,users.last_name,users.image,users.surname,appreviation.name as appreviation,users.rate,(SELECT MIN(service.price)from service WHERE service.trainer_id=users.id) as price from users
         JOIN service ON users.id =service.trainer_id
        JOIN appreviation ON users.appreviation =appreviation.id
         where users.state=4');
    		break;
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

       }catch(Exception $ex){
         
            $message['error']=2;
            $message['message']='error'.$ex->getMessage();

       }
       return response()->json($message);
    }


      public function show_TrainerByname(Request $request)
     {
       try{
         	$name=$request->input('name');
        
	           $userexist=DB::select('select DISTINCT users.id,users.first_name,users.last_name,users.image,users.surname,appreviation.name as appreviation,users.rate,(SELECT MIN(service.price)from service WHERE service.trainer_id=users.id) as price from users
         JOIN service ON users.id =service.trainer_id
        JOIN appreviation ON users.appreviation =appreviation.id
         where (users.first_name  LIKE "%'.$name.'%" OR users.last_name  LIKE "%'.$name.'%" )
         AND( users.state=4 )
         ');

	          if(count($userexist)>0){

	            $message['data']=$userexist;
	            $message['error']=0;
	            $message['message']='user data';

	          }else{
	            $message['data']=$userexist;
	            $message['error']=1;
	            $message['message']='no data for user ';
	          }

	       }catch(Exception $ex){
	         
	            $message['error']=2;
	            $message['message']='error'.$ex->getMessage();

	       }
       return response()->json($message);
    }
    public function show_TrainerByspecialist(Request $request)
     {
       try{
         	$name=$request->input('specialist');
        
	           $userexist=DB::select('select DISTINCT users.id,users.first_name,users.last_name,users.image,users.surname,appreviation.name as appreviation,users.rate,(SELECT MIN(service.price)from service WHERE service.trainer_id=users.id) as price from users
        left JOIN service ON users.id =service.trainer_id
        left JOIN appreviation ON users.appreviation =appreviation.id
        JOIN trainer_specialties ON users.id =trainer_specialties.trainer_id
         where users.state=4 AND trainer_specialties.specialties_id='.$name);
	         

	          if(count($userexist)>0){

	            $message['data']=$userexist;
	            $message['error']=0;
	            $message['message']='user data';

	          }else{
	            $message['data']=$userexist;
	            $message['error']=1;
	            $message['message']='no data for user ';
	          }

	       }catch(Exception $ex){
	         
	            $message['error']=2;
	            $message['message']='error'.$ex->getMessage();

	       }
       return response()->json($message);
    }
    public function show_TrainerByname_and_specialist(Request $request)
     {
       try{
           
           
           
         //	$special=$request->input('specialist');
           //  $name=$request->input('name');
             
             if($request->has('specialist')&& $request->has('name')){
                 
                  $userexist=DB::select('select DISTINCT users.id,users.first_name,users.last_name,users.image,users.surname,appreviation.name as appreviation,users.rate,(SELECT MIN(service.price)from service WHERE service.trainer_id=users.id) as price from users
       
                    JOIN appreviation ON users.appreviation =appreviation.id
                      JOIN trainer_specialties ON users.id =trainer_specialties.trainer_id
                     where (users.first_name  LIKE "%'.$request->name.'%" OR users.last_name  LIKE "%'.$request->name.'%" )
                     AND( users.state=4 ) AND (trainer_specialties.specialties_id='.$request->specialist.')
                     ');

                 
                 
             }elseif(!$request->has('specialist') && $request->has('name')){
                 
                  $userexist=DB::select('select DISTINCT users.id,users.first_name,users.last_name,users.image,users.surname,appreviation.name as appreviation,users.rate,(SELECT MIN(service.price)from service WHERE service.trainer_id=users.id) as price from users
         
                    JOIN appreviation ON users.appreviation =appreviation.id
                     where (users.first_name  LIKE "%'.$request->name.'%" OR users.last_name  LIKE "%'.$request->name.'%" )
                     AND( users.state=4 )
                     ');
                 
                 
             }elseif($request->has('specialist') && !$request->has('name')){
                 
                 $userexist=DB::select('select DISTINCT users.id,users.first_name,users.last_name,users.image,users.surname,appreviation.name as appreviation,users.rate,(SELECT MIN(service.price)from service WHERE service.trainer_id=users.id) as price from users
         
                JOIN appreviation ON users.appreviation =appreviation.id
                JOIN trainer_specialties ON users.id =trainer_specialties.trainer_id
                 where users.state=4 AND trainer_specialties.specialties_id='.$request->specialist);
                         
                 
             }elseif(!$request->has('specialist') &&! $request->has('name')){
                 
                  $userexist=DB::select('select DISTINCT users.id,users.first_name,users.last_name,users.image,users.surname,appreviation.name as appreviation,users.rate,(SELECT MIN(service.price)from service WHERE service.trainer_id=users.id) as price from users
         
                        JOIN appreviation ON users.appreviation =appreviation.id
                         where  users.state=4 
                         ');
                 
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

	       }catch(Exception $ex){
	         
	            $message['error']=2;
	            $message['message']='error'.$ex->getMessage();

	       }
       return response()->json($message);
    }

      public function make_rating(Request $request)
    {
      try{
          $user_id=$request->input('user_id');
         $pro_id=$request->input('trainer_id');
         $rate_num=$request->input('rate_number');
         $comment=$request->input('comment');


          $created_at = carbon::now()->toDateTimeString();
          $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($created_at)));
           

               
        $check=Rating::where([['user_id',$user_id],['trainer_id',$pro_id]])->get();
     //   return count($check);
         if(count($check) > 0){

         	if(empty($rate_num))
         	{
         		$rate_num=Rating::where([['user_id',$user_id],['trainer_id',$pro_id]])->value('rate');

         	}

           $update=Rating::where([['user_id',$user_id],['trainer_id',$pro_id]])->update(['rate'=>$rate_num,'comment'=>$comment,'updated_at'=>$dateTime]);

        $check=Rating::where([['user_id',$user_id],['trainer_id',$pro_id]])->get();
               if($update==true){
               
                    $message['update_rate']=$check;
                     $message['error']=0;
                     $message['message']='update rate of trainer';

                }else{
                         $message['update_rate']=$check;
                         $message['error']=1;
                         $message['message']='can not update rate';
                }

	         }else{
	          $insert=new Rating;
	          $insert->user_id=$user_id;
	          $insert->trainer_id=$pro_id;
	          $insert->rate=$rate_num;
	          $insert->comment=$comment;

	          $insert->created_at=$dateTime;
	          $insert->save();

	         
	      
	        if($insert==true){
	          
	                $message['data']=$insert;
	                 $message['error']=0;
	                 $message['message']='make rate';

	        }else{
	                 $message['data']=$insert;
	                 $message['error']=1;
	                 $message['message']='can not make rate';
	        }
	      }
      }catch(Exception  $ex){
         $message['error']=2;
          $message['message']='error'.$ex->getMessage();

      }
      return response()->json($message);
    }  

  public function show_rating(Request $request)
    {
      try{
         
         $pro_id=$request->input('trainer_id');
 
               
        $check=Rating::select('users.first_name','users.last_name','users.image','rating.rate','rating.comment','rating.created_at','rating.updated_at')->join('users','rating.user_id','=','users.id')->where('rating.trainer_id',$pro_id)->get();
     
      
	      
	        if( count($check)>0){
	          
	                $message['data']=$check;
	                 $message['error']=0;
	                 $message['message']='show rate for this trainer';

	        }else{
	                 $message['data']=$check;
	                 $message['error']=1;
	                 $message['message']='no  rate';
	        }
	      
      }catch(Exception  $ex){
         $message['error']=2;
          $message['message']='error'.$ex->getMessage();

      }
      return response()->json($message);
    }



    

    public function insert_trainerSpecialist(Request $request)
    {

      try{
         
          
          $specialist=$request->input('specialist_id');
              $token=$request->input('user_token');
        
          
          $check_token=User::select('id')->where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){


          $updated_at = carbon::now()->toDateTimeString();
          $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($updated_at)));


           $userexist=Trainer_specialist::where([['trainer_id',$check_token['id']],['specialties_id',$specialist]])->get();

		  if(count($userexist)>0){
		          $message['error']=4;
			      $message['message']='this specialist is exist';
		  }else{
		  	        $check=new Trainer_specialist;
			        $check->trainer_id=$check_token['id'];
			        $check->specialties_id=$specialist;
			        $check->created_at=$dateTime;
			        $check->save();
		     
       
	      
	        if($check ==true){

	       $select=Trainer_specialist::select('trainer_specialties.id','specialties.name','specialties.E_name','trainer_specialties.created_at','trainer_specialties.updated_at')
            ->leftjoin('specialties','trainer_specialties.specialties_id','=','specialties.id')
            ->where('trainer_specialties.id',$check->id)->get();
	          
	          //      $message['data']=$select;
	                 $message['error']=0;
	                 $message['message']='insert specialists for this trainer';

	        }else{
	             //    $message['data']=$check;
	                 $message['error']=1;
	                 $message['message']='error in insert';
	        }
	      
  }
          }else{
              $message['error']=3;
	          $message['message']='this token is not exist';
          }  
	      
         }catch(Exception  $ex){
         $message['error']=2;
          $message['message']='error'.$ex->getMessage();

          }
           return response()->json($message);
    }

 public function delete_trainerSpecialist(Request $request){

      try{
             $token=$request->input('user_token');
        
          
          $check_token=User::select('id')->where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
         $id=$request->input('id');
               
        $check=Trainer_specialist::where('id',$id)->delete();
	      
	        if($check ==true){
	          
	              
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
	      
      }catch(Exception  $ex){
         $message['error']=2;
          $message['message']='error'.$ex->getMessage();

      }
      return response()->json($message);
    }

        public function show_oneTrainer(Request $request)
    {
       try{
            $id= $request->input('trainer_id');
             $date= date('Y-m-d');
             
              $lang= $request->input('lang');

          $userexist=User::select('users.id','users.first_name','users.last_name','users.image','users.surname','users.appreviation','users.rate')
        
          
          ->where('users.id',$id)->get();


        

            if($lang=='ar' ||$lang=='AR'){
                 $show=Timeline::select('timeline.id','timeline.day','timeline.time_from', 'timeline.time_to', 'timeline_state.name as state','time.value','time.a_name' )
           ->join('timeline_state','timeline.state_id','=','timeline_state.id')
            ->join('time','timeline.time_id','=','time.id')
                ->where([['timeline.trainer_id',$id],['timeline.date',$date]])->get();
                


                $service=Service::select('service.id','service.name','time.value as duration','time.a_name as time_name','service.price','service.created_at','service.updated_at')
                ->join('time','service.time_id','=','time.id')

                ->where('trainer_id',$id)->limit(2)->get();

               
                
            }else{
                 $show=Timeline::select('timeline.id','timeline.E_day as day', 'timeline.time_from', 'timeline.time_to', 'timeline_state.E_name as state','time.value','time.E_name' )
           ->join('timeline_state','timeline.state_id','=','timeline_state.id')
            ->join('time','timeline.time_id','=','time.id')
                ->where([['timeline.trainer_id',$id],['timeline.date',$date]])->get();


                $service=Service::select('service.id','service.E_name as name','time.value as duration','time.E_name as time_name','service.price','service.created_at','service.updated_at')
                ->join('time','service.time_id','=','time.id')

                ->where('trainer_id',$id)->limit(2)->get();

               
            }
            

           

          if($userexist !=null){

            $message['data']=$userexist;
           
            $message['timeline']=$show;
            $message['service']=$service;
            
            $message['error']=0;
            $message['message']='user data';

          }else{
            $message['data']=$userexist;
            $message['error']=1;
            $message['message']='no data for user ';
          }

       }catch(Exception $ex){
         
            $message['error']=2;
            $message['message']='error'.$ex->getMessage();

       }
       return response()->json($message);
    }
    
    
     public function show_Timelinebyday(Request $request)
    {
       try{
            $id= $request->input('trainer_id');
        
            
            
            $day = $request->input('day');
            $month_number = $request->input('month');
            $month_name = date("F", mktime(0, 0, 0, $month_number, 10));
            //var_dump($day);
        /*    $message['data']=$day;
            
            return response()->json($message);
            
            $day=explode(' ',$day);
            
            $m=array_shift($day);
            
            $arr=array( );
           
            $day="[".$day[0]."]";
            
             $data=json_decode($day,true);
             
           
            return $data[0]['day'];
            // $date= date('Y-m-d');*/
             
              $lang= $request->input('lang');

        
               //$month=date('F');
              // $year=date('Y');

              $now= date('Y-m-d');

      
      
    
        if($lang=='ar' ||$lang=='AR'){
       
            $data=Timeline::select('timeline.day','timeline.day_num','timeline.month','timeline.year','date')->distinct()->where([['timeline.day_num',$day],['timeline.trainer_id',$id],['timeline.month',$month_name]])->get();
          
            if(count($data)>0){
                if( $data[0]['date'] >= $now){
               
                   $show=Timeline::select('timeline.id','timeline.day', 'timeline.time_from', 'timeline.time_to', 'timeline.trainer_id', 'timeline.price','timeline_state.name  as state', 'timeline.created_at', 'timeline.updated_at' )
                                 ->join('timeline_state','timeline.state_id','=','timeline_state.id')
                                 ->where([['timeline.day_num',$day],['timeline.trainer_id',$id],['timeline.month',$month_name]])->get();
                }else{
                    $show=array();
                }
            }else{
               $show=array();
            }


               
                
        }else{
          
          $data=Timeline::select('timeline.E_day as day','timeline.day_num','timeline.month','timeline.year','date')->distinct()->where([['timeline.day_num',$day],['timeline.trainer_id',$id],['timeline.month',$month_name]])->get();
          
            if(count($data)>0){
                if( $data[0]['date'] >= $now){
               
                   $show=Timeline::select('timeline.id','timeline.E_day as day', 'timeline.time_from', 'timeline.time_to', 'timeline.trainer_id', 'timeline.price','timeline_state.name  as state', 'timeline.created_at', 'timeline.updated_at' )
                                 ->join('timeline_state','timeline.state_id','=','timeline_state.id')
                                 ->where([['timeline.day_num',$day],['timeline.trainer_id',$id],['timeline.month',$month_name]])->get();
                }else{
                   
                   $show=array();
                }
            }else{
               $show=array();
            }

               
         }
            

           

          if(count($show)>0){

       
           $message['date']=$data;
            $message['data']=$show;
            
            
            $message['error']=0;
            $message['message']='show data';

          }else{
              
            $message['data']=NULL;
            $message['error']=1;
            $message['message']='no data for user ';
          }

       }catch(Exception $ex){
         
            $message['error']=2;
            $message['message']='error'.$ex->getMessage();

       }
       return response()->json($message);
    }
  

    public function show_TrainerByid(Request $request)
    {
       try{
            $id= $request->input('trainer_id');
              $lang= $request->input('lang');

          $userexist=User::select('users.id','users.first_name','users.last_name','users.image','users.phone','users.job','service.price','users.rate','country.a_name as country_name','users.profile','users.views_number')
          ->leftjoin('service','users.id','=','service.trainer_id')
          ->leftjoin('country','users.country_id','=','country.id')
          ->where('users.id',$id)->first();
          
          
          $patientcount=Reservation::join('timeline','reservations.timeline_id','=','timeline.id')->where('timeline.trainer_id',$id)->count();
          if($userexist !=null)
          {
              $userexist['patient_count']=$patientcount;
          }
          
            $cert=Certificate::select('name')->where('trainer_id',$id)->get();
            $art=Articles::select('link')->where('trainer_id',$id)->get();

           $check=Rating::select('users.first_name','users.last_name','users.image','rating.rate','rating.comment','rating.created_at','rating.updated_at')
           ->leftjoin('users','rating.user_id','=','users.id')
           ->where('rating.trainer_id',$id)->get();

            if($lang=='ar' ||$lang=='AR'){
                


              /* $service=Service::select('service.id','service.name','time.value as duration','time.a_name as time_name','service.price','service.created_at','service.updated_at')
                ->join('time','service.time_id','=','time.id')
                ->where('trainer_id',$id)->get();*/

                $lang=Trainer_Language::select('trainer_language.id','languages.name as lang_name','trainer_language.created_at','trainer_language.updated_at')
                ->leftjoin('languages','trainer_language.language_id','=','languages.id')
                ->where('trainer_language.trainer_id',$id)->get();
              
         
            }else{
                 


                /*$service=Service::select('service.id','service.E_name as name','time.value as duration','time.E_name as time_name','service.price','service.created_at','service.updated_at')
               ->join('time','service.time_id','=','time.id')

               ->where('trainer_id',$id)->get();*/

                $lang=Trainer_Language::select('trainer_language.id','languages.E_name','trainer_language.created_at','trainer_language.updated_at')
                ->leftjoin('languages','trainer_language.language_id','=','languages.id')
                ->where('trainer_language.trainer_id',$id)->get();
            }

           

          if($userexist !=null){

            $message['data']=$userexist;
            $message['certificate']=$cert;
            $message['articles']=$art;
            $message['rating']=$check;
           // $message['timeline']=$show;
            //$message['service']=$service;
            $message['language']=$lang;
            $message['error']=0;
            $message['message']='user data';

          }else{
            $message['data']=$userexist;
            $message['error']=1;
            $message['message']='no data for user ';
          }

       }catch(Exception $ex){
         
            $message['error']=2;
            $message['message']='error'.$ex->getMessage();

       }
       return response()->json($message);
    }


}
