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
    use App\DailyQuiz;
    use App\DailyQuizQuestion;
    use App\Question;
    use App\MCQOption;

    class DailyQuizController extends Controller {
        public function index() {

            $currentTs  = Carbon::now();
            $tomorrow   = date('Y-m-d h:i:s', strtotime('+1 day', strtotime($currentTs)));
            $expiryDate = Carbon::createFromFormat('Y-m-d h:i:s', $tomorrow)->endOfDay()->toDateTimeString();
            
            $dailyQuiz = DailyQuiz::with('questions')->where('expiry_date', '>=', $expiryDate)
                    ->orderBy('created_at', 'desc')->get();
            return response()->json($dailyQuiz);
        }

        public function show($id) {
            $dailyQuiz = DailyQuiz::with('questions')->where('id', '=', $id)->get();
            return response()->json($dailyQuiz);
        }

        public function create(Request $request) {

            $validator = Validator::make($request->all(), [
                'title'       => 'required|string|max:100',
                'count'       => 'required|numeric',
                'examtype'    => 'required|string|max:100',
                'quizdate'    => 'required|date',
                'profile'    => 'required|string|max:24|exists:users,profile'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
                    'message' => $validator->errors()
                ], 409);
            }

            $profile = $request->input('profile');

            try {
                
                    $currentTs  = $request->input('quizdate');
                    $tomorrow   = date('Y-m-d h:i:s', strtotime('+1 day', strtotime($currentTs)));
                    $expiryDate = Carbon::createFromFormat('Y-m-d h:i:s', $tomorrow)->endOfDay()->toDateTimeString();
                    
                    $dailyQuiz                  = new DailyQuiz;
                    $dailyQuiz->qutitle         = $request->input('title');
                    $dailyQuiz->qucount         = $request->input('count');
                    $dailyQuiz->examtype        = $request->input('examtype');
                    $dailyQuiz->validity        = "24hrs";
                    if($profile == 'Jd3kyosci1sCSyeOo9sX1c9P'){
                    $dailyQuiz->status = "1";
                    }
                    else{
                        $dailyQuiz->status = "0";
                    }
                    $dailyQuiz->expiry_date     = $expiryDate;
                    $dailyQuiz->updated_at      = $currentTs;
                    $dailyQuiz->save();

                return response()->json([
                    'status'  => true,
                    'message' => 'Successfully created new quiz',
                    'dailyQuizid' => $dailyQuiz->id
                ], 201);

            } catch (\Exception $e) {
                return response()->json([
                    'status'  => false,
                    'message' => ' Quiz creation failed'
                ], 409);
            }
            
        }

        public function update(Request $request) {
            $validator = Validator::make($request->all(), [
                'daily_quiz_id'     => 'required|string|max:24|exists:daily_quiz,id',
                'questions'         => 'required|array',
                'status'            => 'required|numeric|max:2',
                'profile'    => 'required|string|max:24|exists:users,profile'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
                    'message' => $validator->errors()
                ], 409);
            }

            $dailyQuizid            = $request->input('daily_quiz_id');
            //$quizquestions          = $request->input('questions');

            $dailyQuiz = DailyQuiz::select('qucount')->where('id', '=', $dailyQuizid)->first();

            //$randomQuestions = Question::inRandomOrder()->limit($dailyQuiz->qucount)->get();
            $profile = $request->input('profile');
            try {
                DailyQuizQuestion::where('dailyquiz_id', $dailyQuizid)->delete();

                // foreach ($randomQuestions as $questions) {
                //     $dailyQuizQuestion               = new DailyQuizQuestion;
                //     $dailyQuizQuestion->dailyquiz_id = $dailyQuizid;
                //     $dailyQuizQuestion->question_id  = $questions->id;
                //     $dailyQuizQuestion->save();
                // }

                $randomQuestions =  $request->input('questions');
                foreach ($randomQuestions as $questions) {
                    $qID = $questions['id'];
                    // $question              = Question::find($qID);
                    // $question->ans_desc    = $questions['ans_desc'];
                    // $question->difficulty  = $questions['difficulty'];
                    // $question->save();
                    $dailyQuizQuestion               = new DailyQuizQuestion;
                    $dailyQuizQuestion->dailyquiz_id = $dailyQuizid;
                    $dailyQuizQuestion->question_id  = $qID;
                    $dailyQuizQuestion->save();
                }

                $questionCount = DailyQuizQuestion::where('dailyquiz_id', $dailyQuizid)->count();
                $dQuiz   = DailyQuiz::find($dailyQuizid); 
                $dQuiz->qucount = $questionCount;
                $dQuiz->status = $request->input('status');
                // if($profile == 'Jd3kyosci1sCSyeOo9sX1c9P'){
                //     $dQuiz->status = "1";
                //     }
                //     else{
                //         $dQuiz->status = "0";
                //     }
                $dQuiz->save();

                return response()->json([
                    'status'  => true,
                    'message' => 'Successfully updated Daily Quiz Questions'
                ], 200);
            } catch (\Exception $e) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Question updation failed'
                ], 409);
            }
        }

        public function morequestion(Request $request){

            $validator = Validator::make($request->all(), [
                'daily_quiz_id'     => 'required|string|max:24|exists:daily_quiz,id',
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
                'difficulty'        =>  'required|string|max:6',
                'file'              => 'mimes:jpg,bmp,png',
                'profile'    => 'required|string|max:24|exists:users,profile'
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

                $dailyQuizid = $request->input('daily_quiz_id');

                $addQuestion                = new DailyQuizQuestion;
                $addQuestion->dailyquiz_id  = $dailyQuizid;
                $addQuestion->question_id   = $questionId;
                $addQuestion->save();

                $dailyQuiz = DailyQuiz::select('qucount')->where('id', '=', $dailyQuizid)->first();
                $codailyQuiz = DailyQuiz::find($dailyQuizid);
                // Make sure you've got the Page model
                if($codailyQuiz) {
                    $codailyQuiz->qucount = $dailyQuiz->qucount + 1;
                    if($profile == 'Jd3kyosci1sCSyeOo9sX1c9P'){
                        $codailyQuiz->status = "1";
                    }
                    else{
                        $codailyQuiz->status = "0";
                    }
                    $codailyQuiz->save();
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

            $dailyQuiz = DailyQuiz::find($id);

            if ($dailyQuiz != null) {
                $dailyQuiz->delete();

                return response()->json([
                    'status'  => true,
                    'message' => 'Deleted selected Quiz'
                ], 200);
            }

            return response()->json([
                'status'  => false,
                'message' => 'No Quiz exists with the given ID'
            ], 200);

            
        }

        public function forapproval(){
            $approve = DailyQuiz::where('status','=','1')->with('questions')->get();

            return response()->json($approve);
        }
    }
?>