<?php
    namespace App\Http\Controllers\Student;

    use Carbon\Carbon;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Validator;
    use Tymon\JWTAuth\JWTAuth;

    use App\Http\Controllers\Controller;
    use App\CourseLesson;
    use App\MCQOption;
    use App\Subject;
    use App\TestAndQuiz;
    use App\Question;

    class QuizzesController extends Controller {
        protected $jwt;

        public function __construct(JWTAuth $jwt) {
            $this->jwt = $jwt;
        }

        public function index() {
            $userId = $this->getUserID($request);
            return response()->json([], 200);
        }

        public function show(Request $request, $id) {
            $userId = $this->getUserID($request);

            $quiz = TestAndQuiz::with('questions')->find($id);
            $questions = $quiz->questions;

            foreach ($questions as $question) {
                $question->options = MCQOption::where('question', $question->id)->get();
            }

            return response()->json($quiz);
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
