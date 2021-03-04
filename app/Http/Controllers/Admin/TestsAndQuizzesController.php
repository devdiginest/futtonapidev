<?php
    namespace App\Http\Controllers\Admin;

    use Carbon\Carbon;
    use Illuminate\Http\Request;
    use Illuminate\Support\Str;
    use Illuminate\Support\Facades\Validator;

    use App\Http\Controllers\Controller;
    use App\Course;
    use App\CourseLesson;
    use App\Subject;
    use App\TestAndQuiz;
    use App\TestOrQuizQuestion;
    use App\Question;
    use App\MCQOption;

    class TestsAndQuizzesController extends Controller {
        public function index() {
            $testsAndQuizzes = TestAndQuiz::orderBy('created_at', 'desc')->with('questions')->with('courses')->get();

            foreach ($testsAndQuizzes as $testOrQuizz) {
                // $course = Course::where('id', $testOrQuizz->course)->first();
                // $testOrQuizz->course = $course->name;

                $subject = Subject::where('id', $testOrQuizz->subject)->first();
                $testOrQuizz->subject = $subject->name;

                $lesson = CourseLesson::where('id', $testOrQuizz->lesson)->first();
                $testOrQuizz->lesson = $lesson->name;

                // $question = TestOrQuizQuestion::where('testorquiz_id', $testOrQuizz->id)->first();
                // $testOrQuizz['questions']->mcqoptions = $question->question_id;

            }

            return response()->json($testsAndQuizzes);
        }

        public function show($id) {
            $testOrQuizz = TestAndQuiz::with('questions')->find($id);

            if ($testOrQuizz != null) {
                $course = Course::where('id', $testOrQuizz->course)->first();
                $testOrQuizz->course = ['id' => $testOrQuizz->course, 'name' => $course->name];

                $subject = Subject::where('id', $testOrQuizz->subject)->first();
                $testOrQuizz->subject = ['id' => $testOrQuizz->subject, 'name' => $subject->name];

                $lesson = CourseLesson::where('id', $testOrQuizz->lesson)->first();
                $testOrQuizz->lesson = ['id' => $testOrQuizz->lesson, 'name' => $lesson->name];

                //$testOrQuizz->questions = TestOrQuizQuestion::where('testorquiz_id', $id)->get();
            }

            return response()->json($testOrQuizz);
        }

        public function create(Request $request) {
            $validator = Validator::make($request->all(), [
                'type'       => 'required|string|max:1',
                'title'      => 'required|string|max:100',
                'exam_type'  => 'required|string|max:1',
                'qus_count'  => 'required|numeric',
                'course'     => 'required|string|max:24|exists:courses,id',
                'subject'    => 'required|string|max:24',
                'lesson'     => 'required|numeric',
                'validity'   => 'required|string|max:9',
                'price'      => 'required|digits_between:1,6',
                'start_date' => 'date',
                'end_date'   => 'date',
                'profile'    => 'required|string|max:24|exists:users,profile'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
                    'message' => $validator->errors()
                ], 409);
            }

            $testOrQuizzType = $request->input('type');

            if ($testOrQuizzType == 1) {
                $testOrQuizzTypeStr = 'Test';
            } else if ($testOrQuizzType == 2) {
                $testOrQuizzTypeStr = 'Quiz';
            }

            $profile = $request->input('profile');

            try {
                $testOrQuizzId = Str::random(24);
                $currentTs = Carbon::now();

                $testOrQuizz              = new TestAndQuiz;
                $testOrQuizz->id          = $testOrQuizzId;
                $testOrQuizz->type        = $testOrQuizzType;
                $testOrQuizz->title       = $request->input('title');
                $testOrQuizz->exam_type   = $request->input('exam_type');
                $testOrQuizz->qus_count   = $request->input('qus_count');
                $testOrQuizz->course      = $request->input('course');
                $testOrQuizz->subject     = $request->input('subject');
                $testOrQuizz->lesson      = $request->input('lesson');
                $testOrQuizz->validity    = $request->input('validity');
                $testOrQuizz->price       = $request->input('price');
                if($profile == 'Jd3kyosci1sCSyeOo9sX1c9P'){
                    $testOrQuizz->status = "1";
                }
                else{
                    $testOrQuizz->status = "0";
                }
                $testOrQuizz->start_date  = $request->input('start_date');
                $testOrQuizz->end_date    = $request->input('end_date');
                $testOrQuizz->updated_at  = $currentTs;
                $testOrQuizz->save();

                return response()->json([
                    'status'  => true,
                    'message' => 'Successfully created new ' . $testOrQuizzTypeStr,
                    'testOrQuizz'   => $testOrQuizzId
                ], 201);
            } catch (\Exception $e) {
                return response()->json([
                    'status'  => false,
                    'message' => $testOrQuizzTypeStr . ' creation failed'
                ], 409);
            }
        }

        public function update(Request $request) {
            $validator = Validator::make($request->all(), [
                'test_or_quizz_id'  => 'required|string|max:24|exists:tests_n_quizzes,id',
                'questions'         => 'required|array',
                'status'            => 'required|numeric|max:2',
                'profile'           => 'required|string|max:24|exists:users,profile'

            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
                    'message' => $validator->errors()
                ], 409);
            }

            $testOrQuizId = $request->input('test_or_quizz_id');
            $randomQuestions =  $request->input('questions');

            foreach ($randomQuestions as $questions) {
                $qID = $questions['id'];
                $question              = Question::find($qID);
                $question->ans_desc    = $questions['ans_desc'];
                $question->difficulty    = $questions['difficulty'];
                $question->save();
            }

            $testOrQuizz = TestAndQuiz::select('type')->find($testOrQuizId);
            $testOrQuizType = $testOrQuizz->type;

            if ($testOrQuizType == 1) {
                $testOrQuizTypeStr = 'Test';
            } else if ($testOrQuizType == 2) {
                $testOrQuizTypeStr = 'Quiz';
            }
            
            $profile = $request->input('profile');

            try {
                
                TestOrQuizQuestion::where('testorquiz_id', $testOrQuizId)->delete();
                
                foreach ($randomQuestions as $testQuestionId) {
                    $testOrQuizQuestion                = new TestOrQuizQuestion;
                    $testOrQuizQuestion->testorquiz_id = $testOrQuizId;
                    $testOrQuizQuestion->question_id   = $testQuestionId['id'];
                    $testOrQuizQuestion->save();
                }

                $questionCount = TestOrQuizQuestion::where('testorquiz_id', $testOrQuizId)->count();
                $testandQuiz   = TestAndQuiz::find($testOrQuizId); 
                $testandQuiz->qus_count = $questionCount;
                $testandQuiz->status = $request->input('status');
                // if($profile == 'Jd3kyosci1sCSyeOo9sX1c9P'){
                //     $testandQuiz->status = "1";
                // }
                // else{
                //     $testandQuiz->status = "0";
                // }
                $testandQuiz->save();

                return response()->json([
                    'status'  => true,
                    'message' => 'Successfully updated ' . $testOrQuizTypeStr . ' Questions'
                ], 200);
            } catch (\Exception $e) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Question updation failed'
                ], 409);
            }
        }

        public function addmore(Request $request){

            $validator = Validator::make($request->all(), [
                'test_or_quizz_id'  => 'required|string|max:24|exists:tests_n_quizzes,id',
                'question'          => 'required|string|max:1000',
                'type'              => 'required|string|max:1',
                'subject'           => 'required|string|max:24',
                'lesson'            => 'required|numeric',
                'correct_opt'       => 'required|numeric',
                'options'           => 'exclude_unless:type,1|required|array',
                'ans_range_start'   => 'exclude_unless:type,2|required|numeric',
                'ans_range_end'     => 'exclude_unless:type,2|required|numeric',
                'answer'            => 'string|max:255',
                'ans_desc'          => 'required|string',
                'difficulty'        => 'required|string|max:6',
                'file'              => 'mimes:jpg,bmp,png',
                'profile'           => 'required|string|max:24|exists:users,profile'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
                    'message' => $validator->errors()
                ], 409);
            }

            $profile = $request->input('profile');
            $type = $request->input('type');

            try {
                $options = $request->input('options');
                $currentTs = Carbon::now();

                $question              = new Question;
                $question->question    = $request->input('question');
                $question->type        = $request->input('type');
                $question->subject     = $request->input('subject');
                $question->lesson      = $request->input('lesson');
                $question->correct_opt = $request->input('correct_opt');
                $question->answer      = $request->input('answer');
                $question->ans_desc    = $request->input('ans_desc');

                if($type == '2'){
                    $question->ans_range_start  =   $request->input('ans_range_start');
                    $question->ans_range_end    =   $request->input('ans_range_end');
                }
                
                $question->difficulty  = $request->input('difficulty');

                if ($request->hasFile('file')) {

                    $md5Name = md5_file($request->file('file')->getRealPath());
                    $guessExtension = $request->file('file')->guessExtension();
                    $filename = $md5Name.'.'.$guessExtension;

                    $destinationPath = storage_path('app/public/uploads/questions/');
                    $file = $request->file('file')->move($destinationPath,$filename);
         
                    $question->file          = $filename;
          
                }

                if($profile == 'Jd3kyosci1sCSyeOo9sX1c9P'){
                    $question->status = "1";
                }
                else{
                    $question->status = "0";
                }
                
                $question->updated_at  = $currentTs;
                $question->save();

                $questionId = $question->id;

                if($type == '1'){
                    foreach ($options as $option) {
                        $mcqOption           = new MCQOption;
                        $mcqOption->question = $questionId;
                        $mcqOption->option   = $option;
                        $mcqOption->save();
                    }

                }


                $testOrQuizId = $request->input('test_or_quizz_id');

                $testOrQuizQuestion                = new TestOrQuizQuestion;
                $testOrQuizQuestion->testorquiz_id = $testOrQuizId;
                $testOrQuizQuestion->question_id   = $questionId;
                $testOrQuizQuestion->save();

                $qusCount = TestAndQuiz::select('qus_count')->where('id', '=', $testOrQuizId)->first();
                $cotestOrQuiz = TestAndQuiz::find($testOrQuizId);

                if($cotestOrQuiz) {
                    $cotestOrQuiz->qus_count = $qusCount->qus_count + 1;
                    if($profile == 'Jd3kyosci1sCSyeOo9sX1c9P'){
                        $cotestOrQuiz->status = "1";
                    }
                    else{
                        $cotestOrQuiz->status = "0";
                    }
                    $cotestOrQuiz->save();
                }


                return response()->json([
                    'status'      => true,
                    'message'     => 'Successfully added new Question',
                    'question_id' => $questionId
                ], 201);
            } catch (\Exception $e) {
                echo $e;
                return response()->json([
                    'status'  => false,
                    'message' => 'Question creation failed'
                ], 409);
            }

        }

        public function destroy($id) {
            $testOrQuizz = TestAndQuiz::find($id);
            $testOrQuizType = $testOrQuizz->type;

            if ($testOrQuizType == 1) {
                $testOrQuizTypeStr = 'Test';
            } else if ($testOrQuizType == 2) {
                $testOrQuizTypeStr = 'Quiz';
            }

            if ($testOrQuizz != null) {
                TestOrQuizQuestion::where('testorquiz_id', $testOrQuizz->id)->delete();
                $testOrQuizz->delete();

                return response()->json([
                    'status'  => true,
                    'message' => 'Deleted selected ' . $testOrQuizTypeStr
                ], 200);
            }

            return response()->json([
                'status'  => false,
                'message' => 'No ' . $testOrQuizTypeStr . ' exists with the given ID'
            ], 200);
        }

        public function forapproval(){
            $approve = TestAndQuiz::where('status','=','1')->with('questions')->get();

            return response()->json($approve);
        }
    }
?>