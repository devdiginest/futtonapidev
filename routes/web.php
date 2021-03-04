<?php

      // Dev Routes
    
    use Illuminate\Support\Facades\File;
    use Illuminate\Support\Facades\Response;
    
    $router->get('/', function() use ($router) {
        return $router->app->version();
    });
    
    $router->group(['prefix' => 'api/v1'], function() use ($router) {
        $router->group(['prefix' => 'admin'], function() use ($router) {
            // AUTH
            $router->post('/register',          'Admin\AuthController@register');
            $router->post('/login',             'Admin\AuthController@login');
            $router->post('/fpassword',         'Admin\AuthController@forgotpassword');
            $router->post('/rpassword/{token}' ,'Admin\AuthController@resetpassword');
            $router->post('/logout',            'Admin\AuthController@logout');
            $router->post('/cpassword'          ,'Admin\AuthController@changepassword');

            // DASHBOARD
            $router->get('/dashboard',              'Admin\DashboardController@index');
            $router->get('/dashboard/csold/{no}',   'Admin\DashboardController@show');
            $router->get('/dashboard/usercount',   'Admin\DashboardController@usercount');
            $router->get('/dashboard/coursedist',   'Admin\DashboardController@coursedist');

            // SUBJECTS
            $router->get('/subjects',           'Admin\SubjectsController@index');
            $router->get('/subjects/{id}',      'Admin\SubjectsController@show');
            $router->post('/subjects',          'Admin\SubjectsController@create');
            $router->put('/subjects',           'Admin\SubjectsController@update');
            $router->delete('/subjects/{id}',   'Admin\SubjectsController@destroy');

            // ADMINS
            $router->get('/admins',           'Admin\AdminController@index');
            $router->get('/admins/{id}',      'Admin\AdminController@show');
            $router->post('/admins',          'Admin\AdminController@create');
            $router->put('/admins',           'Admin\AdminController@update');
            $router->delete('/admins/{id}',   'Admin\AdminController@destroy');

            // TEACHERS
            $router->get('/teachers',           'Admin\TeachersController@index');
            $router->get('/teachers/{id}',      'Admin\TeachersController@show');
            $router->get('/teacher_courses/{id}',      'Admin\TeachersController@getcourse');
            $router->get('/teachersreport/{teacherid}',      'Admin\TeachersController@teachersreports');
            $router->post('/teachers',          'Admin\TeachersController@create');
            $router->put('/teachers',           'Admin\TeachersController@update');
            $router->delete('/teachers/{id}',   'Admin\TeachersController@destroy');

            $router->get('/getstudents/{subjectid}', 'Admin\TeachersController@getstudents');

            $router->get('/liststudents/{teacherid}', 'Admin\TeachersController@liststudents');
            $router->get('/fullliststudents/{teacherid}', 'Admin\TeachersController@fullliststudents');

            $router->get('/allteachers', 'Admin\TeachersController@allteachers');



            //DAILY REPORTS
            $router->get('/dailyreports',          'Admin\DailyReportController@index');
            $router->get('/dailyreports/{id}',     'Admin\DailyReportController@show');
            $router->post('/dailyreports/adddailyreport',       'Admin\DailyReportController@create');
            $router->put('/dailyreports/updatedailyreport',       'Admin\DailyReportController@update');
            $router->delete('/dailyreports/{id}',       'Admin\DailyReportController@destroy');

            $router->get('/dailyreport/forapproval', 'Admin\DailyReportController@adminapproval');

            // CLASSES
            $router->get('/classes',            'Admin\ClassesController@index');
            $router->get('/classes/{id}',       'Admin\ClassesController@show');
            $router->post('/classes',           'Admin\ClassesController@create');
            $router->put('/classes',            'Admin\ClassesController@update');
            $router->delete('/classes/{id}',    'Admin\ClassesController@destroy');

            // COURSES
            $router->get('/courses',            'Admin\CoursesController@index');
            $router->get('/courses/{id}',       'Admin\CoursesController@show');
            $router->post('/courses',           'Admin\CoursesController@create');
            $router->put('/courses/basic',      'Admin\CoursesController@updatebasicsection');
            $router->put('/courses/advan',      'Admin\CoursesController@updateadvancedsection');
            $router->post('/courses/store-file', 'Admin\CoursesController@storethumb');
            $router->delete('/courses/{id}',    'Admin\CoursesController@destroy');

            $router->get('/allcourses', 'Admin\CoursesController@allcourses');



            //COURSE CATEGORIES
            $router->get('/course-category', 'Admin\CourseCategoryController@index');
            $router->get('/course-category/{id}', 'Admin\CourseCategoryController@show');
            $router->post('/course-category', 'Admin\CourseCategoryController@create');
            $router->put('/course-category', 'Admin\CourseCategoryController@update');
            $router->delete('/course-category/{id}', 'Admin\CourseCategoryController@destroy');

            // COURSE DETAILS
            $router->get('/csubjects/{cid}',      'Admin\CourseDetailsController@getsubjects');
            $router->post('/csubjects',           'Admin\CourseDetailsController@createsubject');
            $router->put('/csubjects',            'Admin\CourseDetailsController@updatesubject');
            $router->delete('/csubjects/{id}',    'Admin\CourseDetailsController@deletecoursesubject');

            // COURSE COMMENTS
            $router->get('/course-comments/{id}', 'Admin\CourseCommentsController@show');
            $router->post('/course-comments', 'Admin\CourseCommentsController@create');
            $router->put('/course-comments', 'Admin\CourseCommentsController@update');
            $router->delete('/course-comments/{id}', 'Admin\CourseCommentsController@destroy');

            // EXAMS
            $router->get('/exams', 'Admin\ExamController@index');
            $router->get('/exams/{id}', 'Admin\ExamController@show');
            $router->post('/exams', 'Admin\ExamController@create');
            $router->put('/exams', 'Admin\ExamController@update');
            $router->delete('/exams/{id}', 'Admin\ExamController@destroy');

            // STREAMS

            $router->get('/streams', 'Admin\StreamsController@index');
            $router->get('/streams/{id}', 'Admin\StreamsController@show');
            $router->post('/streams', 'Admin\StreamsController@create');
            $router->put('/streams', 'Admin\StreamsController@update');
            $router->delete('/streams/{id}', 'Admin\StreamsController@destroy');


            $router->get('/lessons/{sid}',      'Admin\CourseDetailsController@getlessons');
            $router->post('/lessons',           'Admin\CourseDetailsController@createlesson');
            $router->post('/updatelessons',     'Admin\CourseDetailsController@updatelesson');
            $router->delete('/lessons/{id}',    'Admin\CourseDetailsController@deletelesson');
            $router->post('/liveclasses',       'Admin\CourseDetailsController@scheduleliveclass');
            $router->get('/getliveclasses',       'Admin\CourseDetailsController@getliveclasses');

            $router->get('/lessonsapproval', 'Admin\CourseDetailsController@lessonsapproval');

            // QUESTION BANK
            $router->get('/questions',          'Admin\QuestionBankController@index');
            $router->get('/questions/{id}',     'Admin\QuestionBankController@show');
            $router->post('/questions',         'Admin\QuestionBankController@create');
            $router->post('/updatequestions',          'Admin\QuestionBankController@update');
            $router->delete('/questions/{id}',  'Admin\QuestionBankController@destroy');
            $router->post('/questions/upload',  'Admin\QuestionBankController@upload');

            $router->get('/questionsapproval', 'Admin\QuestionBankController@forapproval');

            // TESTS AND QUIZZES
            $router->get('/tandq',              'Admin\TestsAndQuizzesController@index');
            $router->get('/tandq/{id}',         'Admin\TestsAndQuizzesController@show');
            $router->post('/tandq',             'Admin\TestsAndQuizzesController@create');
            $router->put('/tandq',              'Admin\TestsAndQuizzesController@update');
            $router->delete('/tandq/{id}',      'Admin\TestsAndQuizzesController@destroy');

            $router->post('/tandq/addmore', 'Admin\TestsAndQuizzesController@addmore');
            $router->get('/tandqforapproval', 'Admin\TestsAndQuizzesController@forapproval');

            //DAILY QUIZES
            $router->get('/dailyquiz',         'Admin\DailyQuizController@index');
            $router->get('/dailyquiz/{id}',         'Admin\DailyQuizController@show');
            $router->post('/dailyquiz',         'Admin\DailyQuizController@create');
            $router->put('/dailyquiz',          'Admin\DailyQuizController@update');
            $router->delete('/dailyquiz/{id}',  'Admin\DailyQuizController@destroy');

            $router->post('/add-more-question',          'Admin\DailyQuizController@morequestion');
            $router->get('/dqforapproval',          'Admin\DailyQuizController@forapproval');

            // SETTINGS
            $router->get('/settings',           'Admin\SettingsController@index');
            $router->put('/settings/smtp',      'Admin\SettingsController@updatesmtp');
            $router->put('/settings/smsg',      'Admin\SettingsController@updatesmsgateway');
            $router->put('/settings/payg',      'Admin\SettingsController@updatepaymentgateway');

            // REPORTS
            $router->post('/reports/cviews'     ,'Admin\ReportsController@getcourseviewsreport');
            $router->post('/reports/creviews'   ,'Admin\ReportsController@getcoursereviewsreport');
            $router->post('/reports/courses'    ,'Admin\ReportsController@getcoursereport');
            $router->post('/reports/fcourses'    ,'Admin\ReportsController@fullcoursereport');
            $router->post('/reports/sales'      ,'Admin\ReportsController@getsalesreport');
            $router->post('/reports/fsales'      ,'Admin\ReportsController@fullsalesreport');
            $router->post('/reports/tests' 		,'Admin\ReportsController@gettestreport');
            $router->post('/reports/ftests' 		,'Admin\ReportsController@fulltestreport');
            $router->post('/reports/dailyquiz' 		,'Admin\ReportsController@getdailyQuizreport');
            $router->post('/reports/users'      ,'Admin\ReportsController@getusersreport');
            $router->post('/reports/fullusers'      ,'Admin\ReportsController@fullusersreport');
            $router->post('/reports/fteachers'      ,'Admin\ReportsController@teacherreport');
            $router->post('/reports/teachersreports'      ,'Admin\ReportsController@getteacherreport');

            // STUDENTS
            $router->get('/students'            ,'Admin\StudentsController@index');
            $router->get('/students/{id}'       ,'Admin\StudentsController@show');
            $router->put('/students'            ,'Admin\StudentsController@update');
            $router->delete('/students/{id}'    ,'Admin\StudentsController@destroy');
            $router->get('/allstudents' , 'Admin\StudentsController@fulllist');

            //UPLOAD API
            $router->post('/uploadfile',		'Admin\UploadController@upload');

            //SLIDERS

            $router->get('/sliders',        'Admin\SliderController@index');
            $router->post('/sliders',        'Admin\SliderController@create');
            $router->delete('/sliders/{id}',        'Admin\SliderController@destroy');

            //STUDENT WEB SLIDER

            $router->get('/studentwebsliders',        'Admin\StudentWebSliderController@index');
            $router->post('/studentwebsliders',        'Admin\StudentWebSliderController@create');
            $router->delete('/studentwebsliders/{id}',        'Admin\StudentWebSliderController@destroy');

            //SUPPORT API
            $router->post('/support',			'Admin\SupportController@support');



            //SMS API
            // $router->post('/sendsms',  'Student\AuthController@otpfortest');

            // APIs FOR MOBILE APP
            $router->group(['prefix' => 'mobile'], function() use ($router) {
                // HOME
                $router->get('/home',                'Admin\Mobile\HomeController@show');
                $router->get('/mycourses',           'Admin\Mobile\MyCoursesController@show');
                $router->get('/mycourses/{tid}',     'Admin\Mobile\MyCoursesController@mycourses');
                $router->get('/coursereviews/{cid}', 'Admin\Mobile\MyCoursesController@getcoursereviews');
                $router->get('/notifications',       'Admin\Mobile\NotificationsController@show');

                // PROFILE
                  $router->get('/profile'             ,'Admin\Mobile\ProfileController@show');
                  $router->post('/profile'             ,'Admin\Mobile\ProfileController@update');


                //SLIDERS

	            $router->get('/sliders',        'Admin\Mobile\SliderController@index');
	            $router->post('/sliders',        'Admin\Mobile\SliderController@create');
	            $router->delete('/sliders/{id}',  'Admin\Mobile\SliderController@destroy');

            });
        });

        $router->group(['prefix' => 'student'], function() use ($router) {
            // PREFERENCES
            $router->get('/exams1'              ,'Student\PreferencesController@getexams1');
            $router->get('/exams2'              ,'Student\PreferencesController@getexams2');
            $router->get('/exams/{sid}'              ,'Student\PreferencesController@getexams');
            $router->get('/languages'           ,'Student\PreferencesController@getlanguages');
            $router->get('/streams'             ,'Student\PreferencesController@getstreams');

            $router->put('/preferences'  		,'Student\PreferencesController@editexams');

            // AUTH
            $router->post('/register'           ,'Student\AuthController@register');
            $router->post('/preferences'        ,'Student\AuthController@storepreferences');
            $router->post('/verifyotp'          ,'Student\AuthController@verifyotp');
            $router->post('/resendotp'          ,'Student\AuthController@resendotp');
            $router->post('/login'              ,'Student\AuthController@login');
            $router->post('/logout'             ,'Student\AuthController@logout');
            $router->post('/fpassword'          ,'Student\AuthController@forgotpassword');
            $router->post('/rpassword'          ,'Student\AuthController@resetpassword');
            $router->post('/cpassword'          ,'Student\AuthController@changepassword');

            // HOME
            $router->get('/home'                ,'Student\HomeController@show');

            // HOME TESTS
            $router->get('/tandq',              'Student\HomeController@tests');

            // COURSES
            $router->get('/courses'             ,'Student\CoursesController@index');
            $router->get('/courses/{id}'        ,'Student\CoursesController@show');

            //COURSEREVIEW
            $router->post('/coursereview', 'Student\CoursesController@coursereview');

            // ORDERS
            $router->get('/orders'              ,'Student\OrdersController@index');
            $router->post('/orders'             ,'Student\OrdersController@create');
            $router->put('/orders'              ,'Student\OrdersController@update');

            // TEST ORDERS
            $router->post('/testorders'			,'Student\OrdersController@createtest');

            $router->post('/freecourse'         ,'Student\OrdersController@freecourse');
            $router->post('/freetest'         ,'Student\OrdersController@freetest');

            // MY COURSES
            $router->get('/mycourses'           ,'Student\MyCoursesController@index');
            $router->get('/mycourses/{id}'      ,'Student\MyCoursesController@show');

            $router->get('/getteachers/{courseid}', 'Student\MyCoursesController@getteachers');
            $router->get('/fullteachers', 'Student\MyCoursesController@fullteachers');

            // MY COURSES - DETAILS
            $router->get('/clessons/{cid}'      ,'Student\CourseDetailsController@getlessons');
            $router->get('/clesson/{lid}'       ,'Student\CourseDetailsController@getlesson');
            $router->get('/cliveclasses/{cid}'  ,'Student\CourseDetailsController@getliveclasses');

            // $router->get('/videoduration/{lessonid}', 'Student\CourseDetailsController@getvideo');



            // COURSE REVIEWS
            $router->get('/coursereviews/{cid}' ,'Student\CoursesController@getcoursereviews');

            // MAIN SEARCH
            $router->get('/search/{value}', 'Student\CoursesController@search');

            // TESTS (WITH TEST SERIES REPORTS)
            $router->get('/tests'               ,'Student\TestsController@index');
            $router->get('/tests/{id}'          ,'Student\TestsController@show');
            $router->post('/tests'              ,'Student\TestsController@savetestresponse');
            $router->get('/testseriesreports'   ,'Student\TestsController@gettestseriesreports');
            $router->post('/examresults'   		,'Student\TestsController@examresult');
            $router->get('/getpositions'   		,'Student\TestsController@getposition');
            $router->get('/getpositions/{testid}'   		,'Student\TestsController@getpositionbytest');

            $router->get('/studenttests/{studentid}'	,'Student\TestsController@gettests');

            // QUIZZES
            $router->get('/quizzes'             ,'Student\QuizzesController@index');
            $router->get('/quizzes/{id}'        ,'Student\QuizzesController@show');
            $router->post('/quizzes'            ,'Student\QuizzesController@savequizresponse');

            // PROFILE
            $router->get('/profile'             ,'Student\ProfileController@show');
            $router->post('/profile'             ,'Student\ProfileController@update');

            // NOTIFICATIONS
            $router->get('/notifications'       ,'Student\NotificationsController@show');

            //BOOKMARK
            $router->get('/bookmark/{id}', 'Student\BookmarkController@show');
            $router->post('/bookmark', 'Student\BookmarkController@create');

            // APIs FOR MOBILE APP
            $router->group(['prefix' => 'mobile'], function() use ($router) {

            });
        });
    });

    Route::get('storage/{filename}', function ($filename)
        {
            $path = storage_path('app/public/uploads/courses/' . $filename);

            if (!File::exists($path)) {
                abort(404);
            }

            $file = File::get($path);
            $type = File::mimeType($path);

            $response = Response::make($file, 200);
            $response->header("Content-Type", $type);

            return $response;
        });

    Route::get('lessonsfiles/{filename}', function ($filename)
        {
            $path = storage_path('app/public/uploads/lessons/' . $filename);

            if (!File::exists($path)) {
                abort(404);
            }

            $file = File::get($path);
            $type = File::mimeType($path);

            $response = Response::make($file, 200);
            $response->header("Content-Type", $type);

            return $response;
        });

    Route::get('profilepics/{filename}', function ($filename)
        {
            $path = storage_path('app/public/uploads/profile/' . $filename);

            if (!File::exists($path)) {
                abort(404);
            }

            $file = File::get($path);
            $type = File::mimeType($path);

            $response = Response::make($file, 200);
            $response->header("Content-Type", $type);

            return $response;
        });

    Route::get('messages/{filename}', function ($filename)
        {
            $path = storage_path('app/public/uploads/messages/' . $filename);

            if (!File::exists($path)) {
                abort(404);
            }

            $file = File::get($path);
            $type = File::mimeType($path);

            $response = Response::make($file, 200);
            $response->header("Content-Type", $type);

            return $response;
        });

    Route::get('questions/{filename}', function ($filename)
        {
            $path = storage_path('app/public/uploads/questions/' . $filename);

            if (!File::exists($path)) {
                abort(404);
            }

            $file = File::get($path);
            $type = File::mimeType($path);

            $response = Response::make($file, 200);
            $response->header("Content-Type", $type);

            return $response;
        });

    Route::get('sliders/{filename}', function ($filename)
        {
            $path = storage_path('app/public/uploads/sliders/' . $filename);

            if (!File::exists($path)) {
                abort(404);
            }

            $file = File::get($path);
            $type = File::mimeType($path);

            $response = Response::make($file, 200);
            $response->header("Content-Type", $type);

            return $response;
        });


?>
