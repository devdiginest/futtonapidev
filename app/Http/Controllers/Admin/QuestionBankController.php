<?php
    namespace App\Http\Controllers\Admin;

    use Carbon\Carbon;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Validator;

    use App\Http\Controllers\Controller;
    use App\CourseLesson;
    use App\MCQOption;
    use App\Subject;
    use App\Question;

    class QuestionBankController extends Controller {
        public function index() {
            $questions = Question::select('id','question','type','subject','lesson','ans_range_start','ans_range_end')
            ->with('options')->orderBy('created_at', 'desc')->get();

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
            $question = Question::with('options')->find($id);
            //$question->options = MCQOption::where('question', $question->id)->get();
            return response()->json($question);
        }

        public function create(Request $request) {
            $validator = Validator::make($request->all(), [
                'question'          => 'required|string|max:1000',
                'type'              => 'required|string|max:1',
                'subject'           => 'required|string|max:24',
                'lesson'            => 'required|numeric',
                'correct_opt'       => 'required|numeric',
                'options'           => 'exclude_unless:type,1|required|array',
                'answer'            => 'string|max:255',
                'ans_desc'          => 'required|string',
                'ans_range_start'   => 'exclude_unless:type,2|required|numeric',
                'ans_range_end'     => 'exclude_unless:type,2|required|numeric',
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

            $profile    = $request->input('profile');
            $type       = $request->input('type');

            try {
                $options = $request->input('options');
                $currentTs = Carbon::now();

                $question                       = new Question;
                $question->question             = $request->input('question');
                $question->type                 = $request->input('type');
                $question->subject              = $request->input('subject');
                $question->lesson               = $request->input('lesson');
                $question->correct_opt          = $request->input('correct_opt');
                $question->answer               = $request->input('answer');
                $question->ans_desc             = $request->input('ans_desc');

                if($type == '2'){
                   $question->ans_range_start      = $request->input('ans_range_start');
                   $question->ans_range_end        = $request->input('ans_range_end'); 
                }
                
                $question->difficulty           = $request->input('difficulty');

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

                return response()->json([
                    'status'      => true,
                    'message'     => 'Successfully created new Question',
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

        public function update(Request $request) {
            $validator = Validator::make($request->all(), [
                'id'                => 'required|numeric|exists:questions',
                'question'          => 'required|string|max:1000',
                'type'              => 'required|string|max:1',
                'subject'           => 'required|string|max:24',
                'lesson'            => 'required|numeric',
                'correct_opt'       => 'required|numeric',
                'options'           => 'exclude_unless:type,1|required|array',
                'answer'            => 'string|max:255',
                'ans_desc'          => 'required|string',
                'ans_range_start'   => 'exclude_unless:type,2|required|numeric',
                'ans_range_end'     => 'exclude_unless:type,2|required|numeric',
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

            $profile    = $request->input('profile');
            $type       = $request->input('type');

            try {
                $questionId = $request->input('id');
                $options = $request->input('options');
                $currentTs = Carbon::now();

                $question              = Question::find($questionId);
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

                MCQOption::where('question', $questionId)->delete();

                if($type == '1'){
                    foreach ($options as $option) {
                        $mcqOption           = new MCQOption;
                        $mcqOption->question = $questionId;
                        $mcqOption->option   = $option;
                        $mcqOption->save();
                    }

                }

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

        public function destroy($id) {
            $question = Question::find($id);

            if ($question != null) {
                MCQOption::where('question', $question->id)->delete();
                $question->delete();

                return response()->json([
                    'status'  => true,
                    'message' => 'Deleted selected Question'
                ], 200);
            }

            return response()->json([
                'status'  => false,
                'message' => 'No Question exists with the given ID'
            ], 200);
        }

        public function upload(Request $request) {
            $validator = Validator::make($request->all(), [
                'questions_file' => 'required|file'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'errors' => $validator->errors()
                ], 409);
            }

            try {
                //$questionsCount = $this->processQuestionsFile($request);

                $filename = $request->file('questions_file')->getClientOriginalName();

                 if (($handle = fopen ($request->file('questions_file'), 'r' )) !== FALSE) {
                        while ( ($data = fgetcsv ( $handle, 1000, ',' )) !== FALSE ) {
                            $question              = new Question;
                            $question->question    = $data[0];
                            $question->type        = $data[1];
                            $question->subject     = $data[2];
                            $question->lesson      = $data[3];
                            $question->correct_opt = $data[4];
                            $question->answer      = $data[5];
                            $question->ans_desc    = $data[6];

                            if($data[1] == '2'){
                                $question->ans_range_start    = $data[7];
                                $question->ans_range_end    = $data[8];
                            }
                            
                            $question->difficulty  = $data[9];
                            $question->file  = $data[10];
                            $question->status      = $data[11];
                            $question->save();

                            $questionId = $question->id;
                            $string    = $data[12];
                            $options = explode (",", $string);

                            if($data[1] == '1'){
                                foreach ($options as $option) {
                                    $mcqOption           = new MCQOption;
                                    $mcqOption->question = $questionId;
                                    $mcqOption->option   = $option;
                                    $mcqOption->save();
                                }
                            }

                             
                        }
                        fclose ( $handle );
                        return response()->json([
                            'status'  => true,
                            'message' => 'Successfully created new Questions'
                        ], 201);
                }

                else{
                    echo "No file present";
                }

                
            } catch (\Exception $e) {
                echo $e;
                return response()->json([
                    'status'  => false,
                    'message' => 'Unable to create questions'
                ], 409);
            }
        }

        public function forapproval(){
            $approval = Question::where('status','=','1')->get();

            return response()->json($approval);
        }

        /***********/
        /* PRIVATE */

        private function processQuestionsFile($request) {
            $questionsCount = [];

            if ($request->hasFile('questions_file')) {
                $questionsCount = [
                    't1' => 20,
                    't2' => 45,
                    't3' => 100
                ];
            } else {
                return response()->json([
                    'status'  => false,
                    'message' => 'Questions file is missing'
                ], 409);
            }

            return $questionsCount;
        }
    }
?>