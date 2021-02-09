<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use carbon\carbon;
use App\User;

class coponController extends Controller{
    
    public $message = array();
    
    public function add_copon(Request $request){
        try{
          
                   $token=$request->input('user_token');
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
            $created_at = carbon::now()->toDateTimeString();
           $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($created_at)));
           
            
            $insertCopon = new \App\Copon;
            
            $insertCopon->code = $request->input('code');
            $insertCopon->value = $request->input('value');
            $insertCopon->end_date = $request->input('end_date');
            $insertCopon->created_at = $dateTime;
            
            $insertCopon->save();
            
            if($insertCopon == true){
                $message['error'] = 0;
                $message['message'] = "a new copon is inserted successfully";
            }else{
                $message['error'] = 1;
                $message['message'] = "there is an error, please try again";
            }
          }else{
              
               $message['error']=3;
              $message['message']='this token is\'t exist ';
          }    
            
        }catch(Exception $ex){
    	    $message['error'] = 2;
            $message['message'] = "error('DataBase Error :{$ex->getMessage()}')";
         }
    
          return response()->json($message);
    }
    
    
    
    public function show_allCopons(Request $request){
        try{
                $token=$request->input('user_token');
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
            $get_allCopons = \App\Copon::select('id','code','value','end_date','created_at')->get();
            
            if( count($get_allCopons)>0 ){
                $message['data'] = $get_allCopons;
                $message['error'] = 0;
                $message['message'] = "there is all the copon data";
            }else{
                $message['data'] = $get_allCopons;
                $message['error'] = 1;
                $message['message'] = "there is an errorn please try again";
            }
        }else{
              
               $message['error']=3;
              $message['message']='this token is\'t exist ';
          }    
            
        }catch(Exception $ex){
    	    $message['error'] = 2;
            $message['message'] = "error('DataBase Error :{$ex->getMessage()}')";
         }
    
          return response()->json($message);
    }
    
    
    public function delete_copon(Request $request){
        try{
                $token=$request->input('user_token');
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
            $copon_id = $request->input('id');
            
            $deleteCopon = \App\Copon::where('id',$copon_id)->delete();
            
            if($deleteCopon == true){
                $message['error'] = 0;
                $message['message'] = "this copon is deleted succesfully";
            }else{
                $message['error'] = 1;
                $message['message'] = "there is an error, please try again";
            }
        }else{
              
               $message['error']=3;
              $message['message']='this token is\'t exist ';
          }    
        }catch(Exception $ex){
    	    $message['error'] = 2;
            $message['message'] = "error('DataBase Error :{$ex->getMessage()}')";
         }
    
          return response()->json($message);
    }
    
    public function show_copon_ByID(Request $request){
        try{
                $token=$request->input('user_token');
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
            $copon_id = $request->input('id');
            
            $show_copon = \App\Copon::select('id','code','value','end_date','created_at')
                                    ->where('id',$copon_id)->first();
                                    
            if($show_copon != NULL){
                $message['data'] = $show_copon;
                $message['error'] = 0;
                $message['message'] = "this is the data of that copon";
            }else{
                $message['data'] = $show_copon;
                $message['error'] = 1;
                $message['message'] = "there is no copon, please try again";
            }
        }else{
              
               $message['error']=3;
              $message['message']='this token is\'t exist ';
          }    
        }catch(Exception $ex){
    	    $message['error'] = 2;
            $message['message'] = "error('DataBase Error :{$ex->getMessage()}')";
         }
    
          return response()->json($message);
    }
    
    public function updated_copon(Request $request){
        try{
                $token=$request->input('user_token');
          
          $check_token=User::where('user_token',$token)->first();
          
          
          if($request->has('user_token') && $check_token !=NULL){
            $copon_id = $request->input('id');
            
            $updated_at = carbon::now()->toDateTimeString();
            $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($updated_at)));
           
            
            $update_copon =  \App\Copon::where('id',$copon_id)
                                        ->update([
                 
                                        'code' => $request->input('code'),
                                        'value' => $request->input('value'),
                                        'end_date' => $request->input('end_date'),
                                        'updated_at' => $dateTime,
                                          ]);
                                          
                                          
           if($update_copon == true){
               $message['error'] = 0;
               $message['message'] = "this copon data is updated successfully";
           }else{
               $message['error'] = 1;
               $message['message'] = "there is an error, please try again";
           }
        }else{
              
               $message['error']=3;
              $message['message']='this token is\'t exist ';
          } 

        }catch(Exception $ex){
    	    $message['error'] = 2;
            $message['message'] = "error('DataBase Error :{$ex->getMessage()}')";
         }
    
          return response()->json($message);
    }
}
 