<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use carbon\carbon;

define( 'API_ACCESS_KEY12', 'AAAAz-GV50A:APA91bFXom0sxT5MNl_IQUbzEEsxJK55cgoI6k4ucPJctyN8wshU_ZOGZnADHyFfKOvcOxLsfuaAKrTJ3-2lGjmzdDAFH2IhiP0dtEtLMUxdyt5ZOPacOq5D9wHk0W0Wgxlncu3iMPl7');

class reportController extends Controller
{
    public $message = array();

    public function make_sessionReport(Request $request){
        try{
            $user_token = $request->input('user_token');

            $check_token = \App\User::where('user_token',$user_token)->first();

            if($request->has('user_token') && $check_token !=NULL){

                $updated_at = carbon::now()->toDateTimeString();
                $dateTime = date('Y-m-d H:i:s',strtotime('+2 hours',strtotime($updated_at)));
                
                $trainner_id = $check_token->id;
                $reservation_id = $request->input('reservation_id');
                $user_id = $request->input('user_id');
                $content = $request->input('content');
                $image = $request->file('image');
                
                if(isset($image)) {
                    $new_name = $image->getClientOriginalName();
                    $savedFileName = rand(100000,999999).time()."_".$new_name; // give a unique name to file to be saved
                    $destinationPath_id = 'uploads/sessionReports';
                    $image->move($destinationPath_id, $savedFileName);
                    $extention = $image->getClientOriginalExtension();

                    $images = $savedFileName;
                  
                }else{
                    $images = NULL;
                }

                if( $request->has('content')){
                    $add_report = new \App\Session_report;
                    $add_report->user_id = $user_id;
                    $add_report->trainner_id = $trainner_id;
                    $add_report->content = $content ;
                    $add_report->image = $images;
                    $add_report->extention = $extention;
                    $add_report->reservation_id = $reservation_id;
                    $add_report->created_at = $dateTime;
                    $add_report->updated_at = $dateTime;
                    $add_report->save();
                }else{
                    $message['error'] = 4;
                    $message['message'] = " please enter the content";
                    return response()->json($message);
                }

                $trainnerData = \App\User::where('id' , $trainner_id)->first();

                $reservation_Date = \App\Reservation::select('timeline.date')   
                                                    ->join('timeline' , 'reservations.timeline_id', '=', 'timeline.id')
                                                    ->where('reservations.id' , $reservation_id)
                                                    ->first();

                                                    
                $user_data = \App\User::where('id' , $user_id)->first();

                if( $add_report == true){

                    $title = $trainnerData->first_name. " ".$trainnerData->last_name ." send you report";
                    $title_ar =" أرسل لك تقرير " .$trainnerData->first_name. " ".$trainnerData->last_name;
                    $body = " Report about your session in ". $reservation_Date->date;
                    $body_ar =  $reservation_Date->date ." تقرير عن جلستك  بتاريخ ";

                    $msg = array(
                        'body' 	=> $body,
                        'title'	=> $title,     	
                        );
                    $fields = array
                            (
                                'to'		=> $user_data->firebase_token,
                                'data'      => $msg,
                                'notification'	=> $msg
                            );
                    
                                    
        
                    $headers = array
                            (
                                'Authorization: key=' . API_ACCESS_KEY12,
                                'Content-Type: application/json'
                            );
                    #Send Reponse To FireBase Server	
                        $ch = curl_init();
                        curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
                        curl_setopt( $ch,CURLOPT_POST, true );
                        curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
                        curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
                        curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
                        curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
                        $result = curl_exec($ch );
                        //echo $result;
                        curl_close( $ch );
            
            
            
                    $save=new \App\Notification;
                    $save->sender_id = $trainner_id;
                    $save->title   = $title;
                    $save->message = $body;
                    $save->title_ar = $title_ar;
                    $save->message_ar = $body_ar;
                    $save->user_id = $user_id;
                    $save->created_at = $dateTime;
                    $save->save();

                    $message['error'] = 0;
                    $message['message'] = "report is send successfuly";
                }else{
                    $message['error'] = 1;
                    $message['message'] = "error, please try again";
                }
            }else{
                $message['error'] = 3;
                $message['message'] = 'this token is not exist'; 
            }

        }catch(Exception $ex){
         
            $message['error']=2;
            $message['message']='error'.$ex->getMessage();

       }
       return response()->json($message);
    }

    public function show_sessionReports(Request $request){
        try{

            $data = array();
            $image = NULL;
            $video = NULL;
            $pdf = NULL;
            $user_token = $request->input('user_token');

            $check_token = \App\User::where('user_token',$user_token)->first();

            if($request->has('user_token') && $check_token !=NULL){

                $show_data = \App\Session_report::select('session_reports.id as report_id', 'users.id as trainner_id', 'users.first_name' , 'users.last_name',  'session_reports.content', 'timeline.date' ,'session_reports.image' , 'session_reports.extention', 'session_reports.created_at as datex')
                                                ->join('users' , 'session_reports.trainner_id' ,'=', 'users.id')
                                                ->join('reservations' ,'session_reports.reservation_id' ,'=' ,'reservations.id')
                                                ->join('timeline' , 'reservations.timeline_id' ,'='  ,'timeline.id')
                                                ->where('session_reports.user_id' , $check_token->id)->get();

                foreach( $show_data as $each){
                    if( $each->extention == 'png' || $each->extention == 'jpg' || $each->extention == 'jpeg'){
                        $image = $each->image;
                        $video = NULL;
                        $pdf = NULL;
                    }elseif( $each->extention == 'pdf'){
                        $image = NULL;
                        $video = NULL;
                        $pdf = $each->image;
                    }elseif( $each->extention == 'mp4'){
                        $image = NULL;
                        $video = $each->image;
                        $pdf = NULL;
                    }

                    array_push($data , (object)array(
                        "report_id" => $each->report_id,
                        "trainner_id" => $each->trainner_id,
                        "first_name" => $each->first_name,
                        "last_name" => $each->last_name,
                        "content" => $each->content,
                        "date" => $each->date,
                        "image" => $image,
                        "video" => $video,
                        "pdf" => $pdf,
                        "created_at" => $each->datex
                    ));
                }
                                    
                if( count( $data ) >0 ){
                    $message['data'] = $data;
                    $message['error'] = 0;
                    $message['message'] = "all user reports";
                }else{
                    $message['data'] = $data;
                    $message['error'] = 1;
                    $message['message'] = "No user reports";
                }
            }else{
                $message['error'] = 3;
                $message['message'] = 'this token is not exist'; 
            }
        }catch(Exception $ex){
         
            $message['error']=2;
            $message['message']='error'.$ex->getMessage();

       }
       return response()->json($message);
    }
}
