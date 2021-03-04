<?php
    namespace App\Http\Controllers\Student;

    use Carbon\Carbon;
    use Illuminate\Http\Request;
    use Tymon\JWTAuth\JWTAuth;

    use App\Http\Controllers\Controller;
    use App\Course;
    use App\CourseLiveClass;
    use App\CourseReview;
    use App\StudentCourse;
    use App\TestAndQuiz;
    use App\Subject;
    use App\CourseLesson;
    use App\Order;

    class HomeController extends Controller {
        protected $jwt;

        public function __construct(JWTAuth $jwt) {
            $this->jwt = $jwt;
        }

        public function show(Request $request) {
            $token  = $request->bearerToken();
            $user   = $this->jwt->User($token);
            $userId = $user->id;

            //$userId = "dNxRPFoTgCIoYV3zb0PBhGG7";

            $currentTs = Carbon::now();

            // COURSES : UPCOMING SECTION
            $upcomingCourses = Course::select('id','name','thumbnail','start_date')
                                ->join('student_courses','courses.id','=','student_courses.course_id')
                                ->where('start_date', '>', $currentTs)
                                ->where('status','=','Active')
                                ->where('student_courses.student_id',$userId)->get();

            // CLASSES : FOR TODAY
            $todaysClasses = CourseLiveClass::select('course_live_classes.id','course_live_classes.name','course_live_classes.course','course_live_classes.subject','course_live_classes.date','course_live_classes.time','courses.name as coursename', 'subjects.name as subjectname','courses.name as coursename', 'course_lessons.name as lessonname')
                            ->join('orders','course_live_classes.course','=','orders.course_id')
                            ->join('courses','course_live_classes.course','=','courses.id')
                            ->join('subjects','course_live_classes.subject','=','subjects.id')
                            ->join('course_lessons','subjects.id','=','course_lessons.subject')
                            ->where('orders.student_id',$userId)
                            ->where('orders.status','paid')
                            ->distinct()
                            ->where('course_live_classes.date', $currentTs->toDateString())->get();
            $todaysClass = $todaysClasses->unique('id');

            // // COURSES : ONGOING (SUBSCRIBED)
            // $ongoingCourses = array();
            // $ongoingCourseIds = array();

            // $mycourses = StudentCourse::where('student_id', $userId)->distinct()->get();
            // foreach ($mycourses as $mycourse) {
            //     $course = Course::select('id','name','thumbnail','start_date','end_date')->where('status','=','Active')->find($mycourse->course_id);
            //     $course->progress = $mycourse->course_progress;

            //     if (($course->start_date <= $currentTs) && ($course->end_date >= $currentTs)) {
            //         $ongoingCourses[] = $course;
            //         $ongoingCourseIds[] = $course->id;
            //     }
            // }

            $ongoingCourses = Course::select('id','name','thumbnail','start_date','end_date','student_courses.course_progress as progress','validity')
                                ->join('student_courses','courses.id','=','student_courses.course_id')
                                ->where('start_date', '<=', $currentTs)
                                ->where('end_date', '>', $currentTs)
                                ->where('status','=','Active')
                                ->where('student_courses.student_id',$userId)->get();

            // // COURSES : ONGOING (NOT SUBSCRIBED)
            // $runningCourses = Course::select('id','name','price','thumbnail')
            //     ->where('start_date', '<=', $currentTs)
            //     ->where('end_date', '>=', $currentTs)
            //     ->whereNotIn('id', $ongoingCourseIds)
            //     ->where('status','=','Active')
            //     ->get();

            // foreach ($runningCourses as $runningCourse) {
            //     $runningCourse->ratings = CourseReview::where('course', $runningCourse->id)->avg('rating');
            //     $runningCourse->reviews = CourseReview::where('course', $runningCourse->id)->where('review', '<>', '')->count();
            // }

            return response()->json([
                'tclasses' => $todaysClass,
                'upcoming' => $upcomingCourses,
                'ongoing'  => $ongoingCourses
                // 'running'  => $runningCourses
            ], 200);
        }

        public function tests(Request $request){

            $token  = $request->bearerToken();
            $user   = $this->jwt->User($token);
            $userId = $user->id;

            //$userId = "dNxRPFoTgCIoYV3zb0PBhGG7";
            $testids = array();

            $getStudentTests = Order::select('test_id')->where('student_id','=', $userId)->where('status','paid')
            ->where('test_id','!=','null')->distinct()->get();

            foreach ($getStudentTests as $testid) {

                $testids[] = $testid->test_id;
            }

             $testsAndQuizzes = TestAndQuiz::orderBy('created_at', 'desc')->with('questions')->with('courses')
                ->whereNotIn('id', $testids)->where('status','=','0')
                ->get();

            foreach ($testsAndQuizzes as $testOrQuizz) {
                // $course = Course::where('id', $testOrQuizz->course)->first();
                // $testOrQuizz->course = $course->name;

                $subject = Subject::where('id', $testOrQuizz->subject)->first();
                if($subject != null){
                    $testOrQuizz->subject = $subject->name;
                }
                

                $lesson = CourseLesson::where('id', $testOrQuizz->lesson)->first();
                if($lesson != null){
                   $testOrQuizz->lesson = $lesson->name; 
                }
                

                // $question = TestOrQuizQuestion::where('testorquiz_id', $testOrQuizz->id)->first();
                // $testOrQuizz['questions']->mcqoptions = $question->question_id;

            }

            return response()->json($testsAndQuizzes);
        }
    }
?>
