<?php
    namespace App\Http\Controllers\Admin;

    use Carbon\Carbon;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Validator;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\DB;

    use App\Http\Controllers\Controller;
    use App\Course;
    use App\CourseLesson;
    use App\CourseSubject;
    use App\CourseLiveClass;
    use App\User;
    use App\Subject;

    class CourseDetailsController extends Controller {
        public function __construct() {

        }

        /********** COURSE-SUBJECT RELATED */

        public function getsubjects($cid) {
            $courseSubjects = CourseSubject::where('course_id', $cid)->get();
           // $courseSubjects = DB::table('course_subjects')
           // ->join('subjects', 'subjects.id', '=', 'course_subjects.subject_id')
           
           //  ->join("course_lessons",function($join){
           //  $join->on("course_lessons.course","=","course_subjects.course_id")
           //      ->on("course_lessons.subject","=","course_subjects.subject_id");
           //  })
           //  ->where('course_subjects.course_id','=', $cid)
           //  ->where('course_lessons.status', '=', 0)
           //  ->select('course_subjects.*','course_lessons.*','subjects.name as subjectname','subjects.id as subjectid')
           //  ->get();

            //return $courseSubjects;

            if( $courseSubjects->isNotEmpty()){
                foreach ($courseSubjects as $subjects) {
                    $course = Course::with('subjects')->find($subjects->course_id);
                    foreach ($course->subjects as $subject) {
                        $subject->lessons = CourseLesson::where('subject', $subject->id)->get();

                        foreach ($subject->lessons as $lesson) {
                            $lesson->liveclasses = CourseLiveClass::where('subject', $subject->id)->get();
                        }
                    }
                }

                unset($course['category'],$course['validity'],$course['start_date'],$course['end_date'],$course['price'],$course['course_provider'],$course['short_desc'],$course['long_desc'],$course['level'],$course['overview_provider'],$course['overview_url'],$course['thumbnail'],$course['content_provider']);
                return response()->json($course);
            }
            else{
                return response()->json([
                    'status'  => false,
                    'message' => 'No Course Subject exists for the ID'
                ], 409);
            }

            
        }

        public function createsubject(Request $request) {
            $validator = Validator::make($request->all(), [
                'course_id'            => 'required|string|max:24|exists:courses,id',
                'subject_id'           => 'required|array|max:24|exists:subjects,id' 
            ]);


            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
                    'message' => $validator->errors()
                ], 409);
            }

            try {
                foreach ($request->subject_id as $subjects) {
                    $checkSubject = CourseSubject::where("subject_id", "=", $subjects)->get();
                    if($checkSubject->isEmpty()){
                        $courseSubject                       = new CourseSubject;
                        $courseSubject->course_id            = $request->input('course_id');
                        $courseSubject->subject_id           = $subjects;
                        $courseSubject->save();

                         return response()->json([
                            'status'  => true,
                            'message' => 'Successfully created new Course Subject'
                        ], 201);
                    }
                    else{
                        return response()->json([
                            'status'  => false,
                            'message' => 'Course Subject already added'
                        ], 409);
                    }
                }
                
               
            } catch (\Exception $e) {
                echo $e;
                return response()->json([
                    'status'  => false,
                    'message' => 'Course Subject creation failed'
                ], 409);
            }
        }

        public function updatesubject(Request $request) {
            $validator = Validator::make($request->all(), [
                'course'            => 'required|string|max:24|exists:courses,id',
                'subject'           => 'required|array|max:24|exists:subjects,id',
                'email'             => 'required|email'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
                    'message' => $validator->errors()
                ], 409);
            }

            try {

                foreach ($request->subject as $subjects) {
                    $checkSubject = CourseSubject::where("subject_id", "=", $subjects)->first();
                    if($checkSubject == null){
                        $courseSubject                       = new CourseSubject;
                        $courseSubject->course_id            = $request->input('course');
                        $courseSubject->subject_id           = $subjects;

                        $userAdmin = User::where('email', $request->input('email'))
                        ->first();
                        if($userAdmin->profile === "Jd3kyosci1sCSyeOo9sX1c9P"){
                            $courseSubject->status = "1"; 
                        }
                        else{
                            $courseSubject->status = "0";
                        }
                        
                        $courseSubject->save();
                    }
                }
                return response()->json([
                    'status'  => true,
                    'message' => 'Successfully updated Course Subject'
                ], 200);
            } catch (\Exception $e) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Course Subject updation failed'
                ], 409);
            }
        }

        public function deletecoursesubject($id) {
            $courseSubject = CourseSubject::find($id);

            if ($courseSubject != null) {
                $courseSubject->delete();

                return response()->json([
                    'status'  => true,
                    'message' => 'Deleted selected Course Subject'
                ], 200);
            }

            return response()->json([
                'status'  => false,
                'message' => 'No Course Subject exists with the given ID'
            ], 200);
        }


        /********** COURSE-SUBJECT'S LESSON RELATED */

        public function getlessons($sid) {
            $courseLessons = CourseLesson::where('subject', $sid)->where('status','0')->get();
            return response()->json($courseLessons);
        }

         public function getvideo($lessonid){

            $lesson = CourseLesson::select('file')->where('id',$lessonid)->first();
            
            $video_path = storage_path('app/public/uploads/lessons/'.$lesson->file);
            $getID3 = new \getID3;
            $file = $getID3->analyze($video_path);
            $duration = date('H:i:s.v', $file['playtime_seconds']);

            return response()->json($duration);
        }

        public function createlesson(Request $request) {
            $validator = Validator::make($request->all(), [
                'course'            => 'required|string|max:24|exists:courses,id',
                'subject'           => 'required|string|max:24|exists:subjects,id',
                'name'              => 'required|string|max:100',
                'description'       => 'string|max:500',
                'resource_url'      => 'required|string|max:100',
                'resource_type'     => 'required|string|max:10',
                'resource_provider' => 'required|string|max:10',
                'file'              => 'mimetypes:application/pdf,video/x-msvideo,video/mpeg,video/quicktime,video/mp4',
                'created_by'        => 'required|string|max:24|exists:users,id',
                'profile'           => 'required|string|max:24|exists:users,profile'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
                    'message' => $validator->errors()
                ], 409);
            }

            $profile = $request->input('profile');



            try {
                $courseLesson                    = new CourseLesson;
                $courseLesson->course            = $request->input('course');
                $courseLesson->subject           = $request->input('subject');
                $courseLesson->name              = $request->input('name');
                $courseLesson->description       = $request->input('description');
                $courseLesson->resource_url      = $request->input('resource_url');
                $courseLesson->resource_type     = $request->input('resource_type');
                $courseLesson->resource_provider = $request->input('resource_provider');
                $courseLesson->created_by        = $request->input('created_by');

                

                
                if($profile == 'Jd3kyosci1sCSyeOo9sX1c9P'){
                    $courseLesson->status = "1";
                }
                else{
                    $courseLesson->status = "0";
                }

                    if ($request->hasFile('file')) {

                        $md5Name = md5_file($request->file('file')->getRealPath());
                        $guessExtension = $request->file('file')->guessExtension();
                        $filename = $md5Name.'.'.$guessExtension;

                        $destinationPath = storage_path('app/public/uploads/lessons/');
                        $file = $request->file('file')->move($destinationPath,$filename);

                        $video_path = $destinationPath.$filename;

                        $the_content_type = mime_content_type($video_path);

                        $filetype = explode("/",$the_content_type);

                        if($filetype['0'] == 'video'){
                            $getID3 = new \getID3;
                            $video = $getID3->analyze($video_path);
                            $duration = date('H:i:s.v', $video['playtime_seconds']);
                            $courseLesson->duration          = $duration;
                        }
             
                        $courseLesson->file          = $filename;
                        
              
                    }

                    $courseLesson->save();
                

                return response()->json([
                    'status'  => true,
                    'message' => 'Successfully created new Subject Lesson'
                ], 201);
            } catch (\Exception $e) {
                echo $e;
                return response()->json([
                    'status'  => false,
                    'message' => 'Subject Lesson creation failed'
                ], 409);
            }
        }

        public function updatelesson(Request $request) {
            $validator = Validator::make($request->all(), [
                'id'                => 'required|numeric',
                'course'            => 'required|string|max:24|exists:courses,id',
                'subject'           => 'required|string|max:24|exists:subjects,id',
                'name'              => 'required|string|max:100',
                'description'       => 'string|max:500',
                'resource_url'      => 'required|string|max:100',
                'resource_type'     => 'required|string|max:10',
                'resource_provider' => 'required|string|max:10',
                'file'              => 'mimetypes:application/pdf,video/x-msvideo,video/mpeg,video/quicktime,video/mp4',
                'created_by'        => 'required|string|max:24|exists:users,id',
                'profile'           => 'string|max:24|exists:users,profile',
                'status'            => 'required|int'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
                    'message' => $validator->errors()
                ], 409);
            }

            $profile = $request->input('profile');

            try {
                $courseLesson                    = CourseLesson::find($request->input('id'));
                $courseLesson->course            = $request->input('course');
                $courseLesson->subject           = $request->input('subject');
                $courseLesson->name              = $request->input('name');
                $courseLesson->description       = $request->input('description');
                $courseLesson->resource_url      = $request->input('resource_url');
                $courseLesson->resource_type     = $request->input('resource_type');
                $courseLesson->resource_provider = $request->input('resource_provider');
                $courseLesson->created_by        = $request->input('created_by');
                $courseLesson->status            = $request->input('status');

                // if($profile == 'Jd3kyosci1sCSyeOo9sX1c9P'){
                //     $courseLesson->status = "1";
                // }
                // else{
                //     $courseLesson->status = "0";
                // }

                
                if ($request->hasFile('file')) {

                    $md5Name = md5_file($request->file('file')->getRealPath());
                    $guessExtension = $request->file('file')->guessExtension();
                    $filename = $md5Name.'.'.$guessExtension;

                    $destinationPath = storage_path('app/public/uploads/lessons/');
                    $file = $request->file('file')->move($destinationPath,$filename);

                    $video_path = $destinationPath.$filename;

                    $the_content_type = mime_content_type($video_path);

                    $filetype = explode("/",$the_content_type);

                    if($filetype['0'] == 'video'){
                        $getID3 = new \getID3;
                        $video = $getID3->analyze($video_path);
                        $duration = date('H:i:s.v', $video['playtime_seconds']);
                        $courseLesson->duration          = $duration;
                    }
                    elseif ($filetype['0'] == 'application') {
                        $courseLesson->duration          = "NULL";
                    }
         
                    $courseLesson->file          = $filename;
          
                }

                $courseLesson->save();

                return response()->json([
                    'status'  => true,
                    'message' => 'Successfully updated Course Lesson'
                ], 200);
            } catch (\Exception $e) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Course Lesson updation failed'
                ], 409);
            }
        }

        public function deletelesson($id) {
            $courseLesson = CourseLesson::find($id);

            if ($courseLesson != null) {
                $courseLesson->delete();

                return response()->json([
                    'status'  => true,
                    'message' => 'Deleted selected Course Lesson'
                ], 200);
            }

            return response()->json([
                'status'  => false,
                'message' => 'No Course Lesson exists with the given ID'
            ], 200);
        }

        public function lessonsapproval(){
            $lessons = CourseLesson::where('course_lessons.status','1')
                        ->join('courses','course_lessons.course','=','courses.id')
                        ->join('subjects','course_lessons.subject','=','subjects.id')
                        ->join('users','course_lessons.created_by','=','users.id')
                        ->select('courses.name as course_name', 'subjects.name as subject_name', 'users.name as user_name','course_lessons.*')->get();

            return response()->json($lessons);
        }


        /********** LIVE CLASS RELATED */

        public function scheduleliveclass(Request $request) {
            $validator = Validator::make($request->all(), [
                'course'   => 'required|string|max:24|exists:courses,id',
                'subject'  => 'required|string|max:24|exists:subjects,id',
                'lesson'   => 'required|numeric|exists:course_lessons,id',
                'name'     => 'required|string|max:100',
                'date'     => 'required|date',
                'time'     => 'required|string',
                'duration' => 'required|string|max:5'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
                    'message' => $validator->errors()
                ], 409);
            }

            try {
                $currentTs = Carbon::now();

                $courseLiveClass             = new CourseLiveClass;
                $courseLiveClass->course     = $request->input('course');
                $courseLiveClass->subject    = $request->input('subject');
                $courseLiveClass->lesson     = $request->input('lesson');
                $courseLiveClass->name       = $request->input('name');
                $courseLiveClass->date       = $request->input('date');
                $courseLiveClass->time       = $request->input('time');
                $courseLiveClass->duration   = $request->input('duration');
                $courseLiveClass->updated_at = $currentTs;
                $courseLiveClass->save();

                return response()->json([
                    'status'  => true,
                    'message' => 'Successfully scheduled new Live Class'
                ], 201);
            } catch (\Exception $e) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Live Class creation failed'
                ], 409);
            }
        }

        public function getliveclasses(){

            $currentTs = Carbon::now()->toDateString();
            
            $courseLiveClass = CourseLiveClass::where('date','>=',$currentTs)
                                ->with('courses')
                                ->with('subjects')
                                ->with('lessons')
                                ->get();

            return response()->json($courseLiveClass);
        }
    }
?>
