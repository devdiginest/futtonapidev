<?php
    namespace App\Http\Controllers\Admin;

    use Carbon\Carbon;
    use Illuminate\Http\Request;
    use Illuminate\Support\Str;
    use Illuminate\Support\Facades\Mail;
    use Illuminate\Support\Facades\Validator;
    use Illuminate\Support\Facades\DB;

    use App\Http\Controllers\Controller;
    use App\User;
    use App\Subject;
    use App\TeacherSubject;
    use App\CourseSubject;
    use App\StudentCourse;
    use App\DailyReport;

    class TeachersController extends Controller {
        private $fromEmailName;
        private $fromEmailAddress;

        public function __construct() {
            $this->fromEmailName = env('MAIL_FROM_NAME');
            $this->fromEmailAddress = env('MAIL_FROM_ADDRESS');
        }

        public function index() {
            $teachers = User::with('tsubjects')->orderBy('created_at', 'desc')->where('profile', '=', 'Jd3kyosci1sCSyeOo9sX1c9P')->paginate(5);
            //return response()->json($teachers);

            // foreach ($teachers as $teacher) {
            //     $teacher->subject = Subject::select('id','name')->where('id', $teacher->subject)->first();
            // }

            //return $teachers->tojson();
            
            return response()->json($teachers);
        }

        public function show($id) {
            $teacher = User::with('tsubjects')->where('id',$id)->get();

            $subjects = TeacherSubject::select('subject_id')->where('teacher_id',$id)->get();
            $courseArray = array();
            foreach ($subjects as $subject) {
                $courses = CourseSubject::where('subject_id',$subject->subject_id)
                        ->join('student_courses','course_subjects.course_id','=','student_courses.course_id')
                        ->join('users','student_courses.student_id','=','users.id')
                        ->distinct()
                        ->get();
                $courseArray[] = $courses;
            }

            return response()->json([
                'teacherdetails' => $teacher,
                'coursedetails'    => $courseArray
            ]);
        }

        public function getcourse($id) {
            $teacher = User::with('tsubjects')->where('id',$id)->get();

            $courses = TeacherSubject::where('teacher_id',$id)
                        ->join('course_subjects','teacher_subjects.subject_id','=','course_subjects.subject_id')
                        ->select('course_subjects.course_id')
                        ->distinct()
                        ->get();
            // $courseArray = array();
            // foreach ($subjects as $subject) {
            //     $courses = CourseSubject::where('subject_id',$subject->subject_id)
            //             ->select('course_subjects.course_id')
            //             ->groupBy('course_subjects.course_id')
            //             ->distinct()
            //             ->get();
            //     $courseArray[] = $courses;
            // }

            return response()->json([
                'teacherdetails' => $teacher,
                'coursedetails'    => $courses
            ]);
        }

        public function allteachers() {
            $teachers = User::with('tsubjects')->orderBy('created_at', 'desc')->where('profile', '=', 'Jd3kyosci1sCSyeOo9sX1c9P')->get();
            
            return response()->json($teachers);
        }

        public function create(Request $request) {
            $validator = Validator::make($request->all(), [
                'name'              => 'required|string|max:30',
                'mobile_no'         => 'required|digits:10|unique:users',
                'email'             => 'required|email|max:50|unique:users',
                'password'          => 'required|string|min:8|max:12',
                'joining_date'      => 'required|date',
                'tsubjects'         => 'required|array|exists:subjects,id'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
                    'message' => $validator->errors()
                ], 409);
            }

            try {
                $teacherId = Str::random(24);
                $name      = $request->input('name');
                $email     = $request->input('email');
                $password  = app('hash')->make($request->input('password'));
                $currentTs = Carbon::now();

                $userAdmin               = new User;
                $userAdmin->id           = $teacherId;
                $userAdmin->profile      = 'Jd3kyosci1sCSyeOo9sX1c9P';
                $userAdmin->name         = $name;
                $userAdmin->mobile_no    = $request->input('mobile_no');
                $userAdmin->email        = $email;
                $userAdmin->password     = $password;
                $userAdmin->joining_date = $request->input('joining_date');
                //$userAdmin->subject      = $request->input('subject');
                $userAdmin->status       = 'Active';
                $userAdmin->updated_at   = $currentTs;
                $userAdmin->save();

                if($userAdmin->save() != null){
                    $subjectArray = $request->input('tsubjects');
                    foreach ($subjectArray as $subject) {
                        $teacherSubject             = new TeacherSubject;
                        $teacherSubject->teacher_id = $teacherId;
                        $teacherSubject->subject_id = $subject;
                        $teacherSubject->save();
                    }
                }

                // TODO: SEND WELCOME EMAIL TO TEACHER
                // sendwelcomeemail($name, $email, $password);

                return response()->json([
                    'status'   => true,
                    'message'  => 'Account created. Welcome email sent to ' . $email,
                    'teacher_id' => $teacherId
                ], 201);
            } catch (\Exception $e) {
                echo $e;
                return response()->json([
                    'status'  => false,
                    'message' => 'Teacher registration failed'
                ], 409);
            }
        }

        public function update(Request $request) {
            $validator = Validator::make($request->all(), [
                'id'           => 'required|string|max:24',
                'name'         => 'required|string|max:30',
                'mobile_no'    => 'required|digits:10|exists:users',
                'email'        => 'required|email|max:50|exists:users',
                'password'     => 'string|min:8|max:12',
                'joining_date' => 'required|date',
                'tsubjects'      => 'required|array|exists:subjects,id',
                'status'       => 'required|string|max:15'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
                    'message' => $validator->errors()
                ], 409);
            }

            try {
                $currentTs = Carbon::now();
                $password  = app('hash')->make($request->input('password'));

                $userAdmin               = User::find($request->input('id'));
                $userAdmin->name         = $request->input('name');
                $userAdmin->password     = $password;
                $userAdmin->mobile_no    = $request->input('mobile_no');
                $userAdmin->email        = $request->input('email');
                $userAdmin->joining_date = $request->input('joining_date');
                $userAdmin->status       = $request->input('status');
                $userAdmin->updated_at   = $currentTs;
                $userAdmin->save();

                if($userAdmin->save() != null){
                    $teacherId = $request->input('id');
                    $subjectArray = $request->input('tsubjects');
                    //$teacherSubs = TeacherSubject::where('teacher_id', '=', $teacherId)->get();
                    
                    // if(!empty($teacherSubs)){
                    $deleteold = TeacherSubject::where('teacher_id', '=', $teacherId)->delete();
                    //     if($deleteold != null){
                            foreach ($subjectArray as $subject) {

                                $getSubjects = TeacherSubject::where('teacher_id', '=', $teacherId)
                                                ->where('subject_id','=',$subject)->get();
                                if($getSubjects->isEmpty()){
                                    $teacherSubject             = new TeacherSubject;
                                    $teacherSubject->teacher_id = $teacherId;
                                    $teacherSubject->subject_id = $subject;
                                    $teacherSubject->save();
                                }  
                            }
                            //return $getSubjects;
                    //     }
                    // }
                    // else{
                    //     foreach ($subjectArray as $subject) {
                    //         $teacherSubject             = new TeacherSubject;
                    //         $teacherSubject->teacher_id = $teacherId;
                    //         $teacherSubject->subject_id = $subject;
                    //         $teacherSubject->save();
                    //     }
                    // }
                    

                }

                // TODO: SEND EMAIL IN REQUIRED CONDITIONS

                return response()->json([
                    'status'  => true,
                    'message' => 'Teacher updated successfully'
                ], 200);
            } catch (\Exception $e) {
                echo $e;
                return response()->json([
                    'status'  => false,
                    'message' => 'Unable to update Teacher'
                ], 409);
            }
        }

        public function destroy($id) {
            // $validator = Validator::make($request->all(), [
            //     'teacher_id' => 'required|string|max:24|exists:users,id'
            // ]);

            // if ($validator->fails()) {
            //     return response()->json([
            //         'status'  => false,
            //         'message' => $validator->errors()
            //     ], 409);
            // }

            // TODO: Reassign to the Selected Teacher

            $userAdmin = User::find($id);

            if ($userAdmin != null) {
                //$userAdmin->delete();

                try {
                    $currentTs = Carbon::now();
                    $userAdmin->status          = "Inactive";
                    $userAdmin->updated_at      = $currentTs;
                    $userAdmin->save();

                    // TODO: SEND EMAIL IN REQUIRED CONDITIONS

                    return response()->json([
                        'status'  => true,
                        'message' => 'Deleted selected Teacher'
                    ], 200);
                } catch (\Exception $e) {
                    return response()->json([
                        'status'  => false,
                        'message' => 'Unable to delete Teacher'
                    ], 409);
                }

            }

            return response()->json([
                'status'  => false,
                'message' => 'No Teacher exists with the given ID'
            ], 200);
        }

        public function getstudents($subjectid){

            $courses = CourseSubject::where('subject_id',$subjectid)
                        ->join('student_courses','course_subjects.course_id','=','student_courses.course_id')
                        ->join('users','student_courses.student_id','=','users.id')

                        ->distinct()
                        ->paginate(5);
            return response()->json($courses);
        }

        public function liststudents($teacherid){

            $courses = TeacherSubject::where('teacher_id',$teacherid)
                        ->join('course_subjects','course_subjects.subject_id','=','teacher_subjects.subject_id')
                        ->join('student_courses','course_subjects.course_id','=','student_courses.course_id')
                        ->join('users','student_courses.student_id','=','users.id')
                        ->distinct()
                        ->paginate(5);
            return response()->json($courses);
        }

        public function fullliststudents($teacherid){

            $courses = TeacherSubject::where('teacher_id',$teacherid)
                        ->join('course_subjects','course_subjects.subject_id','=','teacher_subjects.subject_id')
                        ->join('student_courses','course_subjects.course_id','=','student_courses.course_id')
                        ->join('users','student_courses.student_id','=','users.id')                        
                        ->distinct()
                        ->get();
            return response()->json($courses);
        }

        public function teachersreports($teacherid){

            $tReport = DailyReport::where('teacher_id',$teacherid)
                        ->select('teacher_id','date','working_hours','daily_report.status as reportstatus','subject_id')
                        ->with('subjects')
                        ->get();

            return response()->json($tReport);
        }

        /***********/
        /* PRIVATE */

        private function sendwelcomeemail($name, $email, $password) {
            $data = array(
                'name'     => $name,
                'email'    => $email,
                'password' => $password
            );

            Mail::send('welcome-teacher', $data, function($message) use ($data) {
                $message->to($data['email'])->subject('Welcome to Futton');
                $message->from($this->fromEmailAddress, $this->fromEmailName);
            });
        }
    }
?>