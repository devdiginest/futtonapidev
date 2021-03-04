<?php
    namespace App\Http\Controllers\Student;

    use App\Http\Controllers\Controller;
    use Tymon\JWTAuth\JWTAuth;

    use Carbon\Carbon;
    use Illuminate\Http\Request;
    use Illuminate\Support\Str;
    use Illuminate\Support\Facades\Validator;
    use Illuminate\Support\Facades\Response;
    use Illuminate\Support\Facades\Storage;

    use Illuminate\Support\Facades\DB;

    use App\Course;
    use App\CourseLesson;
    use App\CourseLiveClass;
    use App\CourseReview;
    use App\User;
    use App\Order;
    use App\StudentCourse;
    use App\Preference;
    use App\TestAndQuiz;

    class CoursesController extends Controller {

        protected $jwt;

        public function __construct(JWTAuth $jwt) {
            $this->jwt = $jwt;
        }

        public function index(Request $request) {
            $token  = $request->bearerToken();
            $user   = $this->jwt->User($token);
            $userId = $user->id;

            // $userId = "7KzXCwuP1ddSwRnrURt7nFDf";

            $currentTs  = Carbon::now();

            $courseArray = array();
            $examArray = array();

            $studentCourses = StudentCourse::select('course_id')->where('student_id',$userId)->distinct()->get();

            foreach ($studentCourses as $studentCourse) {
                $courseArray[] = $studentCourse->course_id;
            }

            $studentExams  = Preference::select('course_exam_relation.course_id')->where('preferences.student_id',$userId)
                            ->join('course_exam_relation','preferences.exam2_id','=','course_exam_relation.exams_id')->get();


            foreach ($studentExams as $studentExam) {
                $examArray[] = $studentExam->course_id;
            }

            $unlimitedCourses = Course::orderBy('created_at', 'desc')
                                ->where('validity','Unlimited')
                                ->where('status','=','Active')
                                ->with('categories')
                                ->whereIn('id',$examArray)
                                ->whereNotIn('id',$courseArray)
                                    ->with('exams')
                                    ->with('streams')
                                    ->with('lessons')
                                    ->with('reviews') 
                                    ->with('liveclasses')
                                ->distinct()
                                ->get();

            
            $courses = Course::orderBy('created_at', 'desc')
            ->where('status','=','Active')
            ->where('end_date','>',$currentTs)
            ->with('categories')
            ->whereIn('id',$examArray)
            ->whereNotIn('id',$courseArray)
                ->with('exams')
                ->with('streams')
                ->with('lessons')
                ->with('reviews') 
                ->with('liveclasses')               
                ->get();

        $coursesArray = array_merge($unlimitedCourses->toArray(), $courses->toArray());

            return response()->json($coursesArray);
        }

        public function show($id) {
            $currentTs  = Carbon::now();

            $course = Course::with('subjects')->with('liveclasses', function($q)
                        {
                            $currentTs  = Carbon::now()->toDateString();
                            $q->where('date','=', $currentTs);

                        })->find($id);

            foreach ($course->subjects as $subject) {
                $subject->lessons = CourseLesson::where('subject', $subject->id)->get();
                // foreach ($subject->lessons as $lesson) {
                //     $lesson->liveclasses = CourseLiveClass::where('subject', $subject->id)->get();
                // }
            }

            return response()->json($course);
        }

        public function getcoursereviews($cid) {
            $courseReviews = CourseReview::select('id','student','rating','review','date')->where('course', $cid)->get();

            foreach ($courseReviews as $courseReview) {
                $student = User::select('name')->where('id', $courseReview->student)->first();
                $courseReview->student = $student->name;
            }

            return response()->json($courseReviews);
        }

        public function search(Request $request,$value){

            $token  = $request->bearerToken();
            $user   = $this->jwt->User($token);
            $userId = $user->id;

           //$userId = "dNxRPFoTgCIoYV3zb0PBhGG7"; 

            $courseArray = array();
            $examArray = array();

            $studentCourses = StudentCourse::select('course_id')->where('student_id',$userId)->distinct()->get();

            foreach ($studentCourses as $studentCourse) {
                $courseArray[] = $studentCourse->course_id;
            }

            $studentExams  = Preference::select('course_exam_relation.course_id')->where('preferences.student_id',$userId)
                            ->join('course_exam_relation','preferences.exam2_id','=','course_exam_relation.exams_id')->get();


            foreach ($studentExams as $studentExam) {
                $examArray[] = $studentExam->course_id;
            }

            $courses = Course::orderBy('created_at', 'desc')
            ->with('subjects')
                        ->where('status','=','Active')
                        ->where('name','LIKE','%'.$value.'%')
                        ->whereIn('id',$examArray)
                        ->whereNotIn('id',$courseArray)                
                        ->get();

            $testArray = array();

            $studenttests = Order::select('test_id')
                            ->where('student_id',$userId)
                            ->where('status','paid')
                            ->where('test_id','!=','NULL')
                            ->get();
            $tests = TestAndQuiz::orderBy('created_at', 'desc')
                        ->with('subjects')
                        ->where('status','=','0')
                        ->where('title','LIKE','%'.$value.'%')
                        ->whereNotIn('id',$studenttests)                
                        ->get();

            $array = array_merge($courses->toArray(), $tests->toArray());
            return response()->json($array);
                
        }

        public function coursereview(Request $request){
            $validator = Validator::make($request->all(), [
                'student'               => 'required|string|max:24|exists:users,id',
                'course'                => 'required|string|max:24|exists:courses,id',
                'rating'                => 'required|numeric|between:0,5',
                'review'                => 'required|string|max:500'
            ]);


            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
                    'message' => $validator->errors()
                ], 409);
            }

            $student = $request->input('student');
            $course  = $request->input('course');

            $check = Order::where('student_id',$student)->where('course_id',$course)->where('status','paid')->get();
            $oldReview = CourseReview::where('student',$student)->where('course',$course)->get();

            if($check->isNotEmpty() && $oldReview->isEmpty()){
                try {
                
                    $currentTs  = Carbon::now()->toDateString();

                    $courseReview                   = new CourseReview;
                    $courseReview->student          = $request->input('student');
                    $courseReview->course           = $request->input('course');
                    $courseReview->rating           = $request->input('rating');
                    $courseReview->review           = $request->input('review');
                    $courseReview->date             = $currentTs;
                    $courseReview->save();

                    return response()->json([
                        'status'  => true,
                        'message' => 'Successfully added review'
                    ], 201);

                } catch (\Exception $e) {
                    return response()->json([
                        'status'  => false,
                        'message' => 'Review adding failed'
                    ], 409);
                }
            }
            elseif($check->isNotEmpty() && $oldReview->isNotEmpty()){

                try {
                
                    $currentTs  = Carbon::now()->toDateString();

                    $courseReview          = CourseReview::where('student',$student)->where('course',$course)->first();
                    $courseReview->student          = $request->input('student');
                    $courseReview->course           = $request->input('course');
                    $courseReview->rating           = $request->input('rating');
                    $courseReview->review           = $request->input('review');
                    $courseReview->date             = $currentTs;
                    $courseReview->save();

                    return response()->json([
                        'status'  => true,
                        'message' => 'Review updated'
                    ], 201);

                } catch (\Exception $e) {
                    echo $e;
                    return response()->json([
                        'status'  => false,
                        'message' => 'Review updating failed'
                    ], 409);
                }
            }
            else{
                return response()->json([
                        'status'  => false,
                        'message' => 'Not Authorized'
                    ], 409);
            }
            
        }
    }
?>