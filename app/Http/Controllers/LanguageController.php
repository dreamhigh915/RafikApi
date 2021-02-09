<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Languages;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Roles;
use App\Trainer_Language;
use App\Country;
use carbon\carbon;

class LanguageController extends Controller
{
     public $message=array();

        public function show_Languages(Request $request)
     {
      try{
           $token=$request->input('user_token');
           $lang=$request->input('lang');
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
              
              if($lang=='ar' ||$lang=='AR'){
                    $about=Languages::select('id','name','created_at','updated_at')->get();
              }else{
                     $about=Languages::select('id','E_name as name','created_at','updated_at')->get();
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
                 $message['message']='this token is not exit';
             }         
             }catch(Exception $ex){
                 $message['error']=2;
                 $message['message']='error'.$ex->getMessage();
            }  
         

       
         return response()->json($message);
     }
     
         public function show_TrainerLanguages(Request $request)
     {
      try{
           $token=$request->input('user_token');
           $lang=$request->input('lang');
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
              
              if($lang=='ar' ||$lang=='AR'){
                    $about=Trainer_Language::select('trainer_language.id','languages.name','trainer_language.created_at','trainer_language.updated_at')
                    ->join('languages','trainer_language.language_id','=','languages.id')
                     ->where('trainer_language.trainer_id',$check_token['id'])
                    ->get();
              }else{
                     $about=Trainer_Language::select('trainer_language.id','languages.E_name as name','trainer_language.created_at','trainer_language.updated_at')
                      ->join('languages','trainer_language.language_id','=','languages.id')
                      ->where('trainer_language.trainer_id',$check_token['id'])
                     ->get();
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
                 $message['message']='this token is not exit';
             }         
             }catch(Exception $ex){
                 $message['error']=2;
                 $message['message']='error'.$ex->getMessage();
            }  
         

       
         return response()->json($message);
     }
        public function show_allLanguages(Request $request)
     {
      try{
           
           $lang=$request->input('lang');
          
              
              if($lang=='ar' ||$lang=='AR'){
                    $about=Languages::select('id','name','created_at','updated_at')->get();
              }else{
                     $about=Languages::select('id','E_name as name','created_at','updated_at')->get();
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

          
                   
             }catch(Exception $ex){
                 $message['error']=2;
                 $message['message']='error'.$ex->getMessage();
            }  
         

       
         return response()->json($message);
     }


    public function show_languageByid(Request $request)
    {
       try{
         $token=$request->input('user_token');
           $lang=$request->input('lang');
            $id=$request->input('id');
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
              
              
                    $select=Languages::select('id','name','E_name','created_at','updated_at')->where('id',$id)->first();
             
        
          
            

          if( $select !=null){
              $message['data']=$select;
              $message['error']=0;
              $message['message']='show success';
          }else{
              $message['data']=$select;
              $message['error']=1;
              $message['message']='no data';
          }
          }else{
               $message['error']=3;
              $message['message']='tis token is not exist'; 
          }
        }catch(Exception $ex){
             
              $message['error']=2;
              $message['message']='error'.$ex->getMessage();

        }
       return response()->json($message);
    }
    
     public function delete_language(Request $request)
    {
       try{
         $token=$request->input('user_token');
           $lang=$request->input('lang');
            $id=$request->input('id');
            
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
          
                    $select=Languages::where('id',$id)->delete();
        
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
    
     public function insert_language(Request $request)
    {
       try{
           
             $token=$request->input('user_token');
           $lang=$request->input('lang');

            
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
       
           $name=$request->input('name');
            $e_name=$request->input('E_name');
                $created_at = carbon::now()->toDateTimeString();
          $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($created_at)));
          
          $check=Languages::where('name',$name)->orwhere('E_name',$e_name)->get();
          
          if(count($check)>0){
              
              $message['error']=4;
              $message['message']='this name is exist';
              
          }else{
              
            $select=new Languages;
            $select->name=$name;
             $select->E_name=$e_name;
            $select->created_at=$dateTime;
            $select->save();

          if( $select ==true){
              $message['data']=$select;
              $message['error']=0;
              $message['message']='insert success';
          }else{
              $message['data']=$select;
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
    
    
     public function update_language(Request $request)
    {
       try{
             $token=$request->input('user_token');
           $lang=$request->input('lang');

            
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
       
           $id=$request->input('id');
           $name=$request->input('name');
           $e_name=$request->input('E_name');
                $created_at = carbon::now()->toDateTimeString();
          $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($created_at)));

           
            $update=Languages::where('id',$id)->update([
                'name'=>$name,
                'E_name'=>$e_name,
                'updated_at'=>$dateTime
                
                ]);
            
             $select=Languages::where('id',$id)->first();
         
          if( $update ==true){
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


    //trainer insert Language
  public function delete_trainerlanguage(Request $request)
    {
       try{ 
          
           $token=$request->input('user_token');
           //$lang=$request->input('lang');

            $id = $request->input('id');
            
          
          $check_token=User::select('id')->where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
       
          
            //$select=Trainer_Language::where('id',$check_token['id'])->delete();

            $select=Trainer_Language::where('id',$id)->delete();

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
    
     public function insert_trainerlanguage(Request $request)
    {
       try{
             
          
           $lang=$request->input('language_id');
            $token=$request->input('user_token');
          

            
          
          $check_token=User::select('id')->where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
       

                $created_at = carbon::now()->toDateTimeString();
          $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($created_at)));
        
         $check=Trainer_Language::where([['trainer_id',$check_token['id']],['language_id',$lang]])->get();
         
         if(count($check)>0)
         {
           $message['error']=4;
              $message['message']='this language is exist';   
         }else{
                $select=new Trainer_Language;
            $select->trainer_id=$check_token['id'];
            $select->language_id=$lang;
            $select->created_at=$dateTime;
            $select->save();

          if( $select ==true){
              $message['data']=$select;
              $message['error']=0;
              $message['message']='insert success';
          }else{
              $message['data']=$select;
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


    //country
      public function show_allcountry(Request $request)
     {
      try{
          
           $lang=$request->input('lang');
          
            if($lang=='ar' ||$lang=='AR'){
                $about=Country::select('id','a_name as name','created_at','updated_at')->get();
            }else{
                $about=Country::select('id','E_name as name','created_at','updated_at')->get();
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
         

            }catch(Exception $ex){
                 $message['error']=2;
                 $message['message']='error'.$ex->getMessage();
            }  
         

       
         return response()->json($message);
     }
     
     
    public function show_roles(Request $request)
     {
      try{
        
            $arr=[2,3];
            $lang=$request->input('lang');
          
            if($lang=='ar' ||$lang=='AR'){  
                $about=Roles::select('id','Ar_name as name')->whereIn('id',$arr)->get();
            }else{
                $about=Roles::select('id','name')->whereIn('id',$arr)->get();
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
         

            }catch(Exception $ex){
                 $message['error']=2;
                 $message['message']='error'.$ex->getMessage();
            }  
         

       
         return response()->json($message);
     }
     
     
       public function show_doctorcountry(Request $request)
     {
      try{
          
           $lang=$request->input('lang');
          
          
              
              if($lang=='ar' ||$lang=='AR'){
                    $about=DB::select('SELECT country.id,country.a_name as name,(SELECT COUNT(users.id) FROM users WHERE users.country_id=country.id AND users.state=4)as count FROM country LIMIT 6');
              }else{
                     $about=DB::select('SELECT country.id,country.E_name as name,(SELECT COUNT(users.id) FROM users WHERE users.country_id=country.id AND users.state=4)as count FROM country LIMIT 6');
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
         

            }catch(Exception $ex){
                 $message['error']=2;
                 $message['message']='error'.$ex->getMessage();
            }  
         

       
         return response()->json($message);
     }
     
    public function show_doctorincountry(Request $request)
     {
      try{
          
           $id=$request->input('country_id');
           $type=$request->input('type');
          
          $about=array();
               if($type==1){
            
                    $about=DB::select('select DISTINCT users.id,users.first_name,users.last_name,users.image,users.surname,appreviation.name as appreviation,users.rate,(SELECT MIN(service.price)from service WHERE service.trainer_id=users.id) as price from users
                        
                        LEFT JOIN appreviation ON users.appreviation =appreviation.id
                         where users.state=4 AND users.country_id='.$id);
               }elseif($type==2){
                   
                     $about=DB::select('select DISTINCT users.id,users.first_name,users.last_name,users.image,users.surname,appreviation.name as appreviation,users.rate,(SELECT MIN(service.price)from service WHERE service.trainer_id=users.id) as price from users
            
                    LEFT JOIN appreviation ON users.appreviation =appreviation.id
                    JOIN trainer_specialties ON trainer_specialties.trainer_id=users.id
                     where users.state=4 AND trainer_specialties.specialties_id='.$id);
               }else{
                     $message['error']=3;
                     $message['message']='please enter type of select';
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
         

            }catch(Exception $ex){
                 $message['error']=2;
                 $message['message']='error'.$ex->getMessage();
            }  
         

       
         return response()->json($message);
     }
     
     
     
        public function show_country(Request $request)
     {
      try{
            $token=$request->input('user_token');
           $lang=$request->input('lang');
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
              
              if($lang=='ar' ||$lang=='AR'){
                    $about=Country::select('id','a_name as name','created_at','updated_at')->get();
              }else{
                     $about=Country::select('id','E_name as name','created_at','updated_at')->get();
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

    public function show_countryByid(Request $request)
    {
       try{
             $token=$request->input('user_token');
           $lang=$request->input('lang');
             $id=$request->input('id');
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
              
                    $select=Country::select('id','a_name as name','E_name','created_at','updated_at')->where('id',$id)->first();
           
         
            

          if( $select !=null){
              $message['data']=$select;
              $message['error']=0;
              $message['message']='show success';
          }else{
              $message['data']=$select;
              $message['error']=1;
              $message['message']='no data';
          }
          }else{
              $message['error']=3;
              $message['message']='this token is not exist ';
          }
        }catch(Exception $ex){
             
              $message['error']=2;
              $message['message']='error'.$ex->getMessage();

        }
       return response()->json($message);
    }
    
     public function delete_country(Request $request)
    {
       try{
            $token=$request->input('user_token');
           $lang=$request->input('lang');
             $id=$request->input('id');
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
         
            $action=Country::where('id',$id)->delete();

          if( $action ==true){
             
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
    
     public function insert_country(Request $request)
    {
       try{
             $token=$request->input('user_token');
           
             
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
         
       
              $name=$request->input('a_name');
           $e_name=$request->input('E_name');
                $created_at = carbon::now()->toDateTimeString();
          $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($created_at)));

           
            $select=new Country;
            $select->a_name=$name;
            $select->E_name=$e_name;
            $select->created_at=$dateTime;
            $select->save();

          if( $select ==true){
              $message['data']=$select;
              $message['error']=0;
              $message['message']='insert success';
          }else{
              $message['data']=$select;
              $message['error']=1;
              $message['message']='error in insert data';
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
    
    
     public function update_country(Request $request)
    {
       try{
               
               
                $token=$request->input('user_token');
             
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
         
           
           $id=$request->input('id');
           $name=$request->input('a_name');
           $e_name=$request->input('E_name');

                $created_at = carbon::now()->toDateTimeString();
          $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($created_at)));

           
            $update=Country::where('id',$id)->update([
                'a_name'=>$name,
                'E_name'=>$e_name,
                'updated_at'=>$dateTime
                
                ]);
            
             $select=Country::where('id',$id)->first();
         
          if( $update ==true){
              $message['data']=$select;
              $message['error']=0;
              $message['message']='update success';
          }else{
              $message['data']=$select;
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

}

