<?php
    namespace App\Http\Controllers\Student;

    use Carbon\Carbon;
    use Illuminate\Http\Request;
    use Tymon\JWTAuth\JWTAuth;

    use Illuminate\Support\Facades\DB;

    use App\Http\Controllers\Controller;
    use App\Course;
    use App\CourseReview;
    use App\StudentCourse;
    use App\User;
    use App\CourseSubject;
    use App\Order;
    use App\TestAndQuiz;

    class MyCoursesController extends Controller {
        protected $jwt;

        public function __construct(JWTAuth $jwt) {
            $this->jwt = $jwt;
        }

        public function index(Request $request) {
            $userId = $this->getUserID($request);

             // $userId = "ZUPnVBBX1Vsrdrubmmj5K3I3";

            $ongoingCourses   = array();
            $completedCourses = array();
            $allCourses       = array();
            $alltests         = array();
            $currentTs        = Carbon::now();

            $mycourses  = StudentCourse::where('student_id', $userId)->distinct()->get();
            $tests      = Order::select('test_id')
                            ->where('student_id', $userId)
                            ->where('status','paid')
                            ->where('test_id','!=','null')
                            ->distinct()->get();

            $ongoingCourses = Course::select('id','name','thumbnail','start_date','end_date','student_courses.course_progress as progress','validity')
                                ->join('student_courses','courses.id','=','student_courses.course_id')
                                ->where('start_date', '<=', $currentTs)
                                ->where('end_date', '>', $currentTs)
                                ->where('status','=','Active')
                                ->where('student_courses.student_id',$userId)
                                ->distinct()
                                ->get();
            $unlimitedCourses = Course::select('id','name','thumbnail','start_date','end_date','student_courses.course_progress as progress','validity')
                                ->join('student_courses','courses.id','=','student_courses.course_id')
                                ->where('validity','Unlimited')
                                ->where('status','=','Active')
                                ->where('student_courses.student_id',$userId)
                                ->distinct()
                                ->get();
            $ongoingArray = array_merge($ongoingCourses->toArray(), $unlimitedCourses->toArray());

            $completedCourses = Course::select('id','name','thumbnail','start_date','end_date','student_courses.course_progress as progress')
                                ->join('student_courses','courses.id','=','student_courses.course_id')
                                ->where('end_date', '<', $currentTs)
                                ->where('validity', '!=', 'Unlimited')
                                ->where('status','=','Active')
                                ->where('student_courses.student_id',$userId)
                                ->distinct()
                                ->get();

            $allCourses = Course::select('id','name','thumbnail','start_date','end_date','student_courses.course_progress as progress','validity')->join('student_courses','courses.id','=','student_courses.course_id')
                                ->where('end_date', '>', $currentTs)
                                ->where('status','=','Active')
                                ->where('student_courses.student_id',$userId)
                                ->with('reviews')
                                ->distinct()->get();

            $allcoursesArray = array_merge($allCourses->toArray(), $unlimitedCourses->toArray());

            //$allCourse =  $allCourses->unique('id');


            // foreach ($mycourses as $mycourse) {
            //     $course = Course::select('id','name','start_date','end_date','validity')
            //                 ->where('status','=','Active')
            //                 ->with('reviews')
            //                 ->find($mycourse->course_id);
            //     $course->progress = $mycourse->course_progress;
            //     $allCourses[] = $course;

            //     if (($course->start_date <= $currentTs) && ($course->end_date >= $currentTs)) {

            //         $ongoingCourses[] = $course;
            //     }
            //     if ($course->end_date < $currentTs && $course->validity != 'Unlimited') {
            //         $completedCourses[] = $course;
            //     }
            // }

            foreach ($tests as $test) {
                $testData = TestAndQuiz::select('id','title','course','validity','end_date')
                            ->where('status','=','0')->find($test->test_id);
                if($testData != null){
                    $alltests[] = $testData;
                }
            }

            foreach ($ongoingCourses as $ongoingCourse) {
                $ongoingCourse->ratings = CourseReview::where('course', $ongoingCourse->id)->avg('rating');
                $ongoingCourse->reviews = CourseReview::where('course', $ongoingCourse->id)->where('review', '<>', '')->count();
            }

            foreach ($completedCourses as $completedCourse) {
                $completedCourse->reviews = CourseReview::where('course', $completedCourse->id)
                                            ->where('student',$userId)->where('review', '<>', '')->get();
            }

            return response()->json([
                'ongoing'   => $ongoingArray,
                'completed' => $completedCourses,
                'allcourses'    =>  $allcoursesArray,
                'tests'      => $alltests
            ], 200);

        }

        public function show(Request $request) {
            $userId = $this->getUserID($request);
            $currentTs = Carbon::now();

            $upcomingCourses = Course::select('id','name','start_date')
                ->where('start_date', '>', $currentTs)
                ->get();
            $ongoingCourses = Course::select('id','name')
                ->where('start_date', '<=', $currentTs)
                ->where('end_date', '>=', $currentTs)
                ->get();
            $completedCourses = Course::select('id','name')
                ->where('end_date', '<', $currentTs)
                ->get();

            foreach ($ongoingCourses as $ongoingCourse) {
                $ongoingCourse->ratings = CourseReview::where('course', $ongoingCourse->id)->avg('rating');
                $ongoingCourse->reviews = CourseReview::where('course', $ongoingCourse->id)->where('review', '<>', '')->count();
            }

            return response()->json([
                'upcoming'  => $upcomingCourses,
                'ongoing'   => $ongoingCourses,
                'completed' => $completedCourses
            ], 200);
        }

        public function getteachers($courseid){
            $teachers = CourseSubject::select('course_subjects.subject_id','users.*')->where('course_id','=',$courseid)
                        ->join('teacher_subjects','course_subjects.subject_id','=','teacher_subjects.subject_id')
                        ->join('users','teacher_subjects.teacher_id','=','users.id')
                        ->get();

            return response()->json($teachers);
        }

        public function fullteachers(Request $request){
            $userId = $this->getUserID($request);

            //$userId = "SbzP4QYI0FguOyk3wvsPOtug";

                $teachers = CourseSubject::select('course_subjects.subject_id','users.*')
                ->join('orders','orders.course_id','=','course_subjects.course_id')
                        ->join('teacher_subjects','course_subjects.subject_id','=','teacher_subjects.subject_id')
                        ->join('users','teacher_subjects.teacher_id','=','users.id')
                        ->where('orders.student_id','=',$userId)
                        ->get();
            

            return response()->json($teachers);
        }

        /***********/
        /* PRIVATE */

        private function getUserID($request) {
            $token = $request->bearerToken();
            $user = $this->jwt->User($token);
            return $user->id;
        }
    }
?>
