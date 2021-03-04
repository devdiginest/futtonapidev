<?php
    namespace App\Http\Controllers\Student;

    use Carbon\Carbon;
    use Illuminate\Http\Request;
    use Illuminate\Support\Str;
    use Illuminate\Support\Facades\Validator;

    use App\Http\Controllers\Controller;

    use App\StudentBookmark;
    use App\StudentScore;
    use App\User;

    class BookmarkController extends Controller {
        public function index() {
            $list = User::with('questions')->get();
            return response()->json($list);
        }

        public function show($id) {
            $list = User::with('questions')->where('id',$id)->get();
            return response()->json($list);
        }

        public function create(Request $request) {
            $validator = Validator::make($request->all(), [
                'student_id'       => 'required|string|max:24',
                'question_id'      => 'required|numeric|exists:questions,id',
                'test_id'          => 'required|string|exists:tests_n_quizzes,id'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
                    'message' => $validator->errors()
                ], 409);
            }

            $student_id     = $request->input('student_id');
            $question_id    = $request->input('question_id');
            $test_id        = $request->input('test_id');

            $questions  = StudentBookmark::where('student_id',$student_id)->where('question_id',$question_id)->get();
            $tests      = StudentScore::where('student_id',$student_id)->where('test_id',$test_id)->get();

            
            if($questions->isEmpty() && $tests->isNotEmpty()){
                try {

                        $stdntBookmark                      = new StudentBookmark;
                        $stdntBookmark->student_id          = $student_id;
                        $stdntBookmark->question_id         = $question_id;
                        $stdntBookmark->save();

                        return response()->json([
                            'status'  => true,
                            'message' => 'Question Bookmarked'
                        ], 201);
                    }
                 catch (\Exception $e) {
                    return response()->json([
                        'status'  => false,
                        'message' => 'Bookmark failed'
                    ], 409);
                }
            }
            else{
                return response()->json([
                        'status'  => false,
                        'message' => 'Question already Bookmarked/Complete test for bookmark question'
                    ], 409);
            }
        }

        public function destroy($id) {
            
        }
    }
?>