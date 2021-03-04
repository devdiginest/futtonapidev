<?php
    namespace App\Http\Controllers\Student;

    use Carbon\Carbon;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Validator;
    use Illuminate\Support\Facades\DB;
    use Tymon\JWTAuth\JWTAuth;

    use App\Http\Controllers\Controller;
    use App\CourseLesson;
    use App\MCQOption;
    use App\Subject;
    use App\TestAndQuiz;
    use App\Question;
    use App\StudentScore;
    use App\Course;

    class TestsController extends Controller {
        protected $jwt;

        public function __construct(JWTAuth $jwt) {
            $this->jwt = $jwt;
        }

        public function index(Request $request) {
            // $token  = $request->bearerToken();
            // $user   = $this->jwt->User($token);
            // $userId = $user->id;

            $userId = "dNxRPFoTgCIoYV3zb0PBhGG7";

            $currentTs      = Carbon::now();

            $ongoingCourses = Course::select('id','name','thumbnail','start_date','end_date','student_courses.course_progress as progress','validity')
                                ->join('student_courses','courses.id','=','student_courses.course_id')
                                ->where('start_date', '<=', $currentTs)
                                ->where('end_date', '>', $currentTs)
                                ->where('status','=','Active')
                                ->where('student_courses.student_id',$userId)
                                ->distinct()
                                ->get();

            // FOR THESE COURSES, GET TESTS CREATED
            foreach ($ongoingCourses as $course) {
                $tests = TestAndQuiz::where('course', $course->id)->where('status','=','0')->get();
            }

            $questions = Question::select('id','question','type','subject','lesson')->orderBy('created_at', 'desc')->get();

            foreach ($questions as $question) {
                $subject = Subject::where('id', $question->subject)->first();
                if($subject != null){
                    $question->subject = $subject->name;
                }
                

                $lesson = CourseLesson::where('id', $question->lesson)->first();
                if($lesson != null){
                    $question->lesson = $lesson->name;
                }
                
            }

            return response()->json($questions);
        }

        public function show($id) {
            $question = Question::find($id);
            $question->options = MCQOption::where('question', $question->id)->get();
            return response()->json($question);
        }

        public function update(Request $request) {
            $validator = Validator::make($request->all(), [
                'id'          => 'required|numeric|exists:questions',
                'question'    => 'required|string|max:1000',
                'type'        => 'required|string|max:1',
                'subject'     => 'required|string|max:24',
                'lesson'      => 'required|numeric',
                'correct_opt' => 'required|numeric'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
                    'message' => $validator->errors()
                ], 409);
            }

            try {
                $questionId = $request->input('id');
                $currentTs = Carbon::now();

                $question              = Question::find($questionId);
                $question->question    = $request->input('question');
                $question->type        = $request->input('type');
                $question->subject     = $request->input('subject');
                $question->lesson      = $request->input('lesson');
                $question->correct_opt = $request->input('correct_opt');
                $question->updated_at  = $currentTs;
                $question->save();

                return response()->json([
                    'status'  => true,
                    'message' => 'Successfully updated Question'
                ], 200);
            } catch (\Exception $e) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Question updation failed'
                ], 409);
            }
        }

        public function getposition(){
            //$positions = StudentScore::orderBy('score', 'desc')->get();
            $positions = DB::select('SELECT student_id, score, (SELECT COUNT(*)+1 FROM student_scores B WHERE A.score<B.score) AS position FROM student_scores A ORDER BY score DESC');

            return response()->json($positions);
        }

        public function getpositionbytest($testid){
            //$positions = StudentScore::orderBy('score', 'desc')->get();
            $positions = StudentScore::where('test_id',$testid)
                        ->join('users','student_scores.student_id','=','users.id')
                        ->select('student_scores.*','users.name')->get();

            return response()->json($positions);
        }

        public function gettests($studentid){
            
            $userId = $studentid;

            // $userId = "dNxRPFoTgCIoYV3zb0PBhGG7";

            $tests = StudentScore::where('student_id',$userId)
                    ->join('tests_n_quizzes','student_scores.test_id','=','tests_n_quizzes.id')->get();

            foreach ($tests as $test) {
                $test->position = DB::select("SELECT (SELECT COUNT(*)+1 FROM student_scores B WHERE A.score<B.score AND B.test_id = '$test->test_id') AS position, (SELECT COUNT(*) FROM student_scores B WHERE B.test_id = '$test->test_id') AS totalparticipants FROM student_scores A WHERE A.test_id = '$test->test_id' AND A.student_id = '$test->student_id' ORDER BY score DESC");
            }
            return response()->json($tests);
        }


        public function examresult(Request $request){

            $validator = Validator::make($request->all(), [
                'student_id'    => 'required|string|max:24|exists:users,id',
                'test_id'       => 'required|string|max:24',
                'score'         => 'required|numeric|max:100'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
                    'message' => $validator->errors()
                ], 409);
            }

            $id             =   $request->input('student_id');
            $test_id        =   $request->input('test_id');

            $student    =   StudentScore::where('student_id', $id)->where('test_id',$test_id)->get();

            if($student->isEmpty()){

                try {
                
                    $currentTs = Carbon::now();

                    $studentScore                   = new StudentScore;
                    $studentScore->student_id       = $id;
                    $studentScore->test_id          = $request->input('test_id');
                    $studentScore->score            = $request->input('score');
                    $studentScore->updated_at       = $currentTs;
                    $studentScore->save();

                    $positions = DB::select("SELECT student_id, score, test_id, (SELECT COUNT(*)+1 FROM student_scores B WHERE A.score<B.score AND B.test_id = '$test_id') AS position FROM student_scores A WHERE A.test_id = '$test_id' ORDER BY score DESC");

                    foreach ($positions as $position) {
                        if($position->student_id == $id){
                            $rank = $position->position;
                        }
                    }

                    return response()->json([
                        'status'  => true,
                        'message' => 'Score added',
                        'Rank'     =>  $rank 
                    ], 200);
                } catch (\Exception $e) {
                    echo $e;
                    return response()->json([
                        'status'  => false,
                        'message' => 'Score adding failed'
                    ], 409);
                }
            }
            else{
                return response()->json([
                        'status'  => false,
                        'message' => 'Student already attended'
                    ], 409);
            }


        }
    }
?>