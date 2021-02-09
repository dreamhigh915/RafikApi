<?php

use Illuminate\Http\Request;

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: *');
header('Access-Control-Allow-Headers: *');
header('Access-Control-Allow-Credentials: true');

header('Content-Type: application/json; charset=UTF-8', true); 



/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. Theseblock
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('showUser','SpecialController@showUser');
Route::get('getdata','SpecialController@getdata');


Route::get('show_all_users','UserController@show_all_users');

Route::get('sent_user_notification','UserController@sent_user_notification');

Route::get('show_userNotification','UserController@show_userNotification');

Route::get('user_logout','UserController@user_logout');


Route::post('show_meeting','AdminController@show_meeting');

Route::get('E_chart','chartController@E_chart');

Route::get('E_degree','chartController@E_degree');

Route::get('S_chart','chartController@S_chart');

Route::get('S_degree','chartController@S_degree');
Route::post('verify_code','UserController@verify_code');


Route::get('show_mychart','chartController@show_mychart');

Route::get('R_chart','chartController@R_chart');

Route::get('R_degree','chartController@R_degree');

Route::get('I_chart','chartController@I_chart');

Route::get('I_degree','chartController@I_degree');

Route::get('C_chart','chartController@C_chart');

Route::get('C_degree','chartController@C_degree');

Route::get('A_chart','chartController@A_chart');

Route::get('A_degree','chartController@A_degree');

Route::get('code_chart','chartController@code_chart');
Route::get('main_degree','chartController@main_degree');


Route::get('Takder','searchController@Takder');

Route::get('countsubject_Monthchart','searchController@countsubject_Monthchart');
Route::get('countdegree_Monthchart','searchController@countdegree_Monthchart');
Route::get('classdegree_Monthchart','searchController@classdegree_Monthchart');

Route::post('make_search','searchController@make_search');
Route::post('insert_degree','searchController@insert_degree');


Route::get('show_specialreports','searchController@show_specialreports');
Route::get('show_generalreports','searchController@show_generalreports');
Route::get('show_slokyreports','searchController@show_slokyreports');
Route::post('add_generalReport','searchController@add_generalReport');
Route::post('add_specialReport','searchController@add_specialReport');
Route::get('show_reportsType','searchController@show_reportsType');
Route::post('make_rating','searchController@make_rating');


Route::post('rate_myself','searchController@rate_myself');
Route::post('rate_management','searchController@rate_management');
Route::post('rate_school','searchController@rate_school');


Route::get('show_onestudent','searchController@show_onestudent');
Route::get('show_studentrating','searchController@show_studentrating');





Route::get('social','homeController@social');

Route::get('show_homeData','homeController@show_homeData');
Route::post('update_homeData','homeController@update_homeData');


Route::get('home_data','homeController@home_data');

Route::get('choose_main_post','homeController@choose_main_post');
Route::get('rafik_work1','homeController@rafik_work1');
Route::get('rafik_work2','homeController@rafik_work2');

Route::get('home_blog','homeController@home_blog');
Route::get('show_steps','homeController@show_steps');
Route::get('show_stepbyid','homeController@show_stepbyid');
Route::post('update_steps','homeController@update_steps');

Route::post('make_opinion','homeController@make_opinion');



Route::get('show_posts_incategory','BlogController@show_posts_incategory');
//blogger
Route::get('show_category','BlogController@show_category');
Route::get('show_categorybyid','BlogController@show_categorybyid');
Route::get('delete_category','BlogController@delete_category');
Route::post('add_category','BlogController@add_category');
Route::post('update_category','BlogController@update_category');

Route::get('show_onepost','BlogController@show_onepost');


Route::get('show_blog','BlogController@show_blog');

Route::get('show_posts','BlogController@show_posts');
Route::get('show_postbyid','BlogController@show_postbyid');
Route::get('delete_post','BlogController@delete_post');
Route::post('add_post','BlogController@add_post');
Route::post('update_post','BlogController@update_post');








Route::get('show_classstudents','InstitutionDash@show_classstudents');
Route::get('show_classteachers','InstitutionDash@show_classteachers');

///////


Route::get('delete_branche','InstitutionDash@delete_branche');
Route::post('insert_branch','InstitutionDash@insert_branch');
Route::post('update_branch','InstitutionDash@update_branch');

Route::get('data_count','InstitutionDash@data_count');

Route::get('show_institution_Byid','InstitutionDash@show_institution_Byid');
Route::post('edit_institutionProfle','InstitutionDash@edit_institutionProfle');
Route::post('institution_login','InstitutionDash@institution_login');
Route::post('update_student','InstitutionDash@update_student');


Route::get('show_schoolstudents','InstitutionDash@show_schoolstudents');

Route::get('show_schooltrainer','InstitutionDash@show_schooltrainer');


Route::get('show_branches','InstitutionDash@show_branches');

Route::get('show_branchebyid','InstitutionDash@show_branchebyid');
Route::get('show_levelbyid','InstitutionDash@show_levelbyid');
Route::get('delete_level','InstitutionDash@delete_level');
Route::get('show_levels','InstitutionDash@show_levels');
Route::get('show_classes','InstitutionDash@show_classes');





Route::get('show_Trainerspecialists','InstitutionDash@show_Trainerspecialists');

Route::post('insert_level','InstitutionDash@insert_level');
Route::post('update_level','InstitutionDash@update_level');


Route::get('show_classbyid','InstitutionDash@show_classbyid');
Route::get('delete_class','InstitutionDash@delete_class');
Route::post('insert_class','InstitutionDash@insert_class');

Route::post('update_class','InstitutionDash@update_class');

Route::post('insert_student','InstitutionDash@insert_student');

Route::post('add_teacher_class','InstitutionDash@add_teacher_class');

Route::get('delete_student_class','InstitutionDash@delete_student_class');
Route::get('delete_student','InstitutionDash@delete_student');

Route::get('delete_teacher','InstitutionDash@delete_teacher');

Route::post('update_teacher','InstitutionDash@update_teacher');

Route::get('show_trainer_byid','InstitutionDash@show_trainer_byid');


Route::post('insert_teacher','InstitutionDash@insert_teacher');

Route::get('show_studentbyid','InstitutionDash@show_studentbyid');

//////

Route::post('make_atest','SpecialController@make_atest');

Route::post('el3amil_code','SpecialController@el3amil_code');
Route::post('create_El3mail_account','SpecialController@create_El3mail_account');

//admin

//classes

Route::get('show_myclasses','ClassController@show_myclasses');

Route::get('show_oneClass','ClassController@show_oneClass');
Route::get('show_institutionClasses','ClassController@show_institutionClasses');

Route::post('change_password','UserController@change_password');
Route::get('show_result','NewController@show_result');

Route::get('test_questions','NewController@test_questions');

Route::get('testA_questions_group2','NewController@testA_questions_group2');


Route::get('testB_questions','NewController@testB_questions');

Route::get('show_blockedstudent','UserController@show_blockedstudent');
Route::get('show_blockedwlyamer','UserController@show_blockedwlyamer');

Route::get('show_blockedtrainer','UserController@show_blockedtrainer');
Route::get('show_trainer','UserController@show_trainer');
Route::get('show_students','UserController@show_students');
Route::get('show_wlyAlamer','UserController@show_wlyAlamer');
Route::post('admin_login','UserController@admin_login');

Route::post('myadmin_login','TimeController@myadmin_login');
Route::get('show_adminById','UserController@show_adminById');


Route::get('convert_to_trainer','UserController@convert_to_trainer');
Route::post('train_regesteration','UserController@train_regesteration');


Route::post('edit_adminprofile','UserController@edit_adminprofile');

//about
Route::get('show_allusers','UserController@show_allusers');

Route::get('show_aboutus','UserController@show_aboutus');

Route::get('show_about','UserController@show_about');
Route::post('update_about','UserController@update_about');

//trainer

Route::post('edit_userProfile','UserController@edit_userProfile');
Route::get('show_user','UserController@show_user');

Route::post('regesteration','UserController@regesteration');
Route::post('user_login','UserController@user_login');

Route::get('show_pendingtrainer','UserController@show_pendingtrainer');
Route::get('unblock_user','UserController@unblock_user');
Route::get('block_user','UserController@block_user');



Route::post('forget_password','UserController@forget_password');
Route::post('Reset_password','UserController@Reset_password');






Route::get('show_allquestiontype','Special_questionController@show_allquestiontype');

Route::get('show_typequestions','Special_questionController@show_typequestions');


Route::get('show_someNews','Special_questionController@show_someNews');
Route::get('show_allNews','Special_questionController@show_allNews');
Route::get('show_News','Special_questionController@show_News');

Route::get('show_Newbyid','Special_questionController@show_Newbyid');
Route::get('delete_New','Special_questionController@delete_New');

Route::post('insert_New','Special_questionController@insert_New');
Route::post('update_New','Special_questionController@update_New');

Route::get('show_opinions','Special_questionController@show_opinions');


Route::get('show_questiontype','Special_questionController@show_questiontype');
Route::get('show_typeByid','Special_questionController@show_typeByid');

Route::get('show_onetype','Special_questionController@show_onetype');

Route::get('delete_type','Special_questionController@delete_type');
Route::post('insert_type','Special_questionController@insert_type');
Route::post('update_type','Special_questionController@update_type');



//
//special question
Route::get('show_questionBytype','Special_questionController@show_questionBytype');
Route::get('show_questionByid','Special_questionController@show_questionByid');
Route::get('delete_question','Special_questionController@delete_question');
Route::post('insert_question','Special_questionController@insert_question');
Route::post('update_question','Special_questionController@update_question');




Route::get('show_textconditions','Special_questionController@show_textconditions');
Route::get('show_conditions','Special_questionController@show_conditions');
Route::post('update_conditions','Special_questionController@update_conditions');

Route::get('show_conditionByid','Special_questionController@show_conditionByid');


Route::get('show_conditions','Special_questionController@show_conditions');
Route::post('update_conditionsubject','Special_questionController@update_conditionsubject');
Route::post('insert_conditionsubject','Special_questionController@insert_conditionsubject');
Route::get('delete_subjectconditions','Special_questionController@delete_subjectconditions');



//
Route::get('show_roles_conditions','Special_questionController@show_roles_conditions');

Route::get('show_allopinions','Special_questionController@show_allopinions');


Route::get('show_rolesubject','Special_questionController@show_rolesubject');

Route::get('show_subjectPolicy','Special_questionController@show_subjectPolicy');


Route::get('show_PrivacePolicy','Special_questionController@show_PrivacePolicy');
Route::get('show_textpolicy','Special_questionController@show_textpolicy');
Route::get('show_subjectByid','Special_questionController@show_subjectByid');
Route::get('show_policyByid','Special_questionController@show_policyByid');
//http://rafikapi.codecaique.com/api

Route::get('show_onesubject','Special_questionController@show_onesubject');

Route::get('delete_subject','Special_questionController@delete_subject');

Route::post('insert_subject','Special_questionController@insert_subject');
Route::post('update_subject','Special_questionController@update_subject');



Route::get('show_policy','Special_questionController@show_policy');
Route::post('update_policy','Special_questionController@update_policy');


//specialist

Route::get('show_doctorspecialists','SpecialistController@show_doctorspecialists');
Route::get('show_specialists','SpecialistController@show_specialists');

Route::get('show_allspecialists','SpecialistController@show_allspecialists');
Route::get('show_doctorinspecialists','SpecialistController@show_doctorinspecialists');




Route::get('show_specialistsByid','SpecialistController@show_specialistsByid');
Route::get('delete_specialist','SpecialistController@delete_specialist');
Route::post('insert_specialists','SpecialistController@insert_specialists');
Route::post('update_specialists','SpecialistController@update_specialists');


//child


Route::get('show_Trainerreports','childController@show_Trainerreports');

Route::get('show_medicalreports','childController@show_medicalreports');
Route::get('show_acadmyreports','childController@show_acadmyreports');
Route::get('show_slokyreports','childController@show_slokyreports');


//Route::get('show_result','PaymentController@show_result');

Route::get('show_social_status','PaymentController@show_social_status');

Route::post('update_commission','PaymentController@update_commission');

Route::get('show_wallet','PaymentController@show_wallet');
Route::post('recharge_wallet','PaymentController@recharge_wallet');
Route::post('transfer_money','PaymentController@transfer_money');


Route::get('show_PreviousDealings','PaymentController@show_PreviousDealings');

Route::post('add_child','childController@add_child');
Route::post('add_child1','childController@add_child1');
Route::get('show_child','childController@show_child');
Route::get('show_allchildern','childController@show_allchildern');
Route::get('accept_parent_request','childController@accept_parent_request');


Route::get('show_TrainerByspecialist','TrainerController@show_TrainerByspecialist');

Route::get('show_Timelinebyday','TrainerController@show_Timelinebyday');


Route::get('show_oneTrainer','TrainerController@show_oneTrainer');
Route::get('show_Trainer','TrainerController@show_Trainer');


Route::get('show_articles','TrainerController@show_articles');
Route::get('show_articleByid','TrainerController@show_articleByid');
Route::get('delete_article','TrainerController@delete_article');

Route::post('insert_article','TrainerController@insert_article');
Route::post('update_article','TrainerController@update_article');


Route::get('show_certificate','TrainerController@show_certificate');
Route::get('show_certificateByid','TrainerController@show_certificateByid');
Route::get('delete_certificate','TrainerController@delete_certificate');

Route::post('insert_certficate','TrainerController@insert_certficate');
Route::post('update_certificate','TrainerController@update_certificate');



Route::get('show_TrainerByid','TrainerController@show_TrainerByid');
Route::get('show_TrainerByname_and_specialist','TrainerController@show_TrainerByname_and_specialist');
Route::get('show_TrainerByname','TrainerController@show_TrainerByname');
Route::post('insert_trainerSpecialist','TrainerController@insert_trainerSpecialist');



Route::get('sort_Trainer','TrainerController@sort_Trainer');
Route::get('delete_trainerSpecialist','TrainerController@delete_trainerSpecialist');


Route::get('show_rating','TrainerController@show_rating');
Route::post('make_rate','TrainerController@make_rate');





//tests category

Route::post('answer_Btest','newTestController@answer_Btest');

Route::post('answer_atest22','newTestController@answer_atest22');

Route::get('test_atest','newTestController@test_atest');

Route::get('show_digram','newTestController@show_digram');

Route::post('answer_atest','newTestController@answer_atest');

Route::get('show_Categoryoftest','TestController@show_Categoryoftest');
Route::get('show_subofcategory','TestController@show_subofcategory');

Route::get('show_secondcategory','TestController@show_secondcategory');

Route::get('show_testinsubcategory','TestController@show_testinsubcategory');
Route::get('show_alltestquestion','TestController@show_alltestquestion');
Route::get('show_questionanswers','TestController@show_questionanswers');


Route::get('count_data','TestController@count_data');
Route::get('show_testCategory','TestController@show_testCategory');
/*Route::get('show_categoryByid','TestController@show_categoryByid');
Route::post('insert_category','TestController@insert_category');
Route::post('update_category','TestController@update_category');*/





Route::get('check_testbuy','TestController@check_testbuy');

//sub
Route::get('show_tests','TestController@show_tests');
Route::post('insert_subcategory','TestController@insert_subcategory');
Route::post('update_subcategory','TestController@update_subcategory');
Route::get('delete_subcategory','TestController@delete_subcategory');

Route::get('show_questionsByid','TestController@show_questionsByid');


//test
Route::get('show_testByid','TestController@show_testByid');
Route::post('insert_test','TestController@insert_test');
Route::post('update_test','TestController@update_test');
Route::get('delete_test','TestController@delete_test');

Route::get('show_onetest','TestController@show_onetest');

//questions

Route::get('show_question_answers','TestController@show_question_answers');

Route::get('show_testquestionsByid','TestController@show_testquestionsByid');
Route::post('insert_testquestion','TestController@insert_testquestion');
Route::post('update_testquestions','TestController@update_testquestions');
Route::get('delete_testquestion','TestController@delete_testquestion');



//answer
Route::get('show_answerByid','TestController@show_answerByid');
Route::post('insert_answer','TestController@insert_answer');
Route::post('update_Answer','TestController@update_Answer');
Route::get('delete_Answer','TestController@delete_Answer');


//show_services
Route::get('show_services','ServiceController@show_services');

Route::get('delete_service','ServiceController@delete_service');

Route::get('show_serviceByid','ServiceController@show_serviceByid');

Route::post('insert_service','ServiceController@insert_service');

Route::post('update_service','ServiceController@update_service');


//time

Route::get('show_oneTimeline','TimeController@show_oneTimeline');

Route::get('show_Time','TimeController@show_Time');

Route::get('show_allTime','TimeController@show_allTime');
Route::get('show_TimeByid','TimeController@show_TimeByid');

Route::get('delete_Time','TimeController@delete_Time');
Route::post('insert_Time','TimeController@insert_Time');
Route::post('update_Time','TimeController@update_Time');




Route::get('show_Timeline','TimeController@show_Timeline');

Route::get('show_Timelinebyid','TimeController@show_Timelinebyid');

Route::get('delete_Timeline','TimeController@delete_Timeline');
Route::get('delete_Timeline_range','TimeController@delete_Timeline_range');



Route::post('insert_timeline','TimeController@insert_timeline');

Route::post('update_timeline','TimeController@update_timeline');


Route::get('Book_now','TimeController@Book_now');

Route::post('show_Timelinebyday','TimeController@show_Timelinebyday');

//reservation
Route::get('reserve_timeline','CourseController@reserve_timeline');
Route::get('choose_payment','CourseController@choose_payment');
Route::get('show_payments','CourseController@show_payments');

Route::get('show_allpayments','CourseController@show_allpayments');

Route::get('show_paymentByid','CourseController@show_paymentByid');

Route::get('delete_payment','CourseController@delete_payment');

Route::post('update_payment','CourseController@update_payment');
Route::post('insert_payment','CourseController@insert_payment');


Route::post('upload_video','CourseController@upload_video');
Route::get('show_video','CourseController@show_video');


//admins

Route::get('show_Adminroles','AdminController@show_Adminroles');
Route::get('delete_admin','AdminController@delete_admin');
Route::get('show_admins','AdminController@show_admins');
Route::get('show_adminbyid','AdminController@show_adminbyid');

Route::post('add_admin','AdminController@add_admin');
Route::post('update_admin','AdminController@update_admin');


Route::get('show_alladminRoles','AdminController@show_alladminRoles');
Route::get('delete_adminRole','AdminController@delete_adminRole');


Route::post('add_adminrole','AdminController@add_adminrole');

Route::post('update_E3lan','AdminController@update_E3lan');

Route::get('show_E3lan','AdminController@show_E3lan');
Route::get('show_dashE3lan','AdminController@show_dashE3lan');


Route::get('share_reports','AdminController@share_reports');
Route::get('share_class_result','AdminController@share_class_result');


Route::get('shared_class_result','AdminController@shared_class_result');
Route::get('shared_student_result','AdminController@shared_student_result');

Route::get('share_student_result','AdminController@share_student_result');
Route::post('buy_atest','AdminController@buy_atest');

Route::get('my_shared_reports','AdminController@my_shared_reports');

Route::get('my_tests','AdminController@my_tests');


//user orders

Route::get('show_usernext_session','CourseController@show_usernext_session');


Route::get('show_user_previouse_sessions','CourseController@show_user_previouse_sessions');
Route::get('cancel_usersession','CourseController@cancel_usersession');

//trainer orders


Route::get('show_sugesstedsessions','CourseController@show_sugesstedsessions');


Route::get('show_reservationshistory','CourseController@show_reservationshistory');

Route::get('show_allSessions','TimeController@show_allSessions');







Route::get('show_acceptedreservations','CourseController@show_acceptedreservations');
Route::get('show_canceledreservations','CourseController@show_canceledreservations');
Route::get('show_trainernext_session','CourseController@show_trainernext_session');
Route::get('show_wlynext_session','CourseController@show_wlynext_session');




Route::post('search_reservations','CourseController@search_reservations');

Route::get('show_trainerWait_orders','CourseController@show_trainerWait_orders');
Route::get('show_trainerprevious_orders','CourseController@show_trainerprevious_orders');
Route::get('acceptReservation','CourseController@acceptReservation');
Route::get('cancel_Reservation','CourseController@cancel_Reservation');
Route::get('cancelReservation','CourseController@cancelReservation');

//for admin

Route::get('show_allreservations','CourseController@show_allreservations');

//languages


Route::get('show_Languages','LanguageController@show_Languages');

Route::get('show_allLanguages','LanguageController@show_allLanguages');

Route::get('show_languageByid','LanguageController@show_languageByid');

Route::get('delete_language','LanguageController@delete_language');
Route::post('insert_language','LanguageController@insert_language');
Route::post('update_language','LanguageController@update_language');

//trainer_language

Route::get('delete_trainerlanguage','LanguageController@delete_trainerlanguage');
Route::post('insert_trainerlanguage','LanguageController@insert_trainerlanguage');

Route::get('show_TrainerLanguages','LanguageController@show_TrainerLanguages');

//country
Route::get('show_roles','LanguageController@show_roles');


Route::get('show_country','LanguageController@show_country');

Route::get('show_doctorcountry','LanguageController@show_doctorcountry');



Route::get('show_doctorincountry','LanguageController@show_doctorincountry');

Route::get('show_allcountry','LanguageController@show_allcountry');

Route::get('show_countryByid','LanguageController@show_countryByid');
Route::get('delete_country','LanguageController@delete_country');
Route::post('insert_country','LanguageController@insert_country');
Route::post('update_country','LanguageController@update_country');


//institutions

Route::get('show_myinstitution','InsritutionController@show_myinstitution');


Route::get('show_allinstitutions','InsritutionController@show_allinstitutions');

Route::get('show_institutions','InsritutionController@show_institutions');
Route::get('show_institutionByid','InsritutionController@show_institutionByid');
Route::get('delete_institutions','InsritutionController@delete_institutions');
Route::post('insert_institutions','InsritutionController@insert_institutions');
Route::post('update_institution','InsritutionController@update_institution');

//message
Route::get('showmessage','InsritutionController@showmessage');
Route::get('showmessage','InsritutionController@showmessage');
Route::post('sentmessage','InsritutionController@sentmessage');
Route::post('sent_message','InsritutionController@sent_message');





Route::get('show_message','InsritutionController@show_message');
Route::post('sent_usermessage','InsritutionController@sent_usermessage');

// copons Apis ::


Route::post("add_copon","coponController@add_copon");

Route::get("show_allCopons","coponController@show_allCopons");

Route::get("delete_copon","coponController@delete_copon");

Route::get("show_copon_ByID","coponController@show_copon_ByID");

Route::post("updated_copon","coponController@updated_copon");



Route::post("send_studentNotification", "AdminController@send_studentNotification");

Route::post("send_parentNotification", "AdminController@send_parentNotification");

Route::post("send_trainerNotification","AdminController@send_trainerNotification");

Route::post("send_specialNotification","AdminController@send_specialNotification");

Route::post('getMeeting','AdminController@getMeeting');

Route::post('showMeeting' ,"ServiceController@showMeeting");
Route::get('createMeeting' ,"ServiceController@createMeeting");
Route::get('getUser' ,"ServiceController@getUser");


//********************** session data ********************************************/


Route::get('show_sessionـby_id','CourseController@show_sessionـby_id');


//********************** session reports ********************************************/



Route::post('make_sessionReport' ,"reportController@make_sessionReport");
Route::get('show_sessionReports' ,"reportController@show_sessionReports");



