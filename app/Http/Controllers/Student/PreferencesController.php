<?php
    namespace App\Http\Controllers\Student;

    use App\Http\Controllers\Controller;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Validator;

    use App\CourseCategory;
    use App\Exam;
    use App\Language;
    use App\Stream;
    use App\Preference;

    class PreferencesController extends Controller {
        public function getexams1() {
            $exams1 = CourseCategory::orderBy('display_order', 'asc')->get();
            return response()->json($exams1);
        }

        public function getexams2() {
            $exams2 = Exam::orderBy('display_order', 'asc')->get();
            return response()->json($exams2);
        }

        public function getexams($sid) {
            $exams = Preference::select('exam2_id')->where('student_id', $sid)->get();
            return response()->json($exams);
        }

        public function getlanguages() {
            $languages = Language::orderBy('display_order', 'asc')->get();
            return response()->json($languages);
        }

        public function getstreams() {
            $streams = Stream::orderBy('display_order', 'asc')->get();
            return response()->json($streams);
        }

        public function editexams(Request $request){
            $validator = Validator::make($request->all(), [
                'student_id'  => 'required|string|max:24|exists:users,id',                
                'exam2_id'    => 'required|int'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
                    'message' => $validator->errors()
                ], 409);
            }

            $student_id = $request->input('student_id');
            $exam2_id   = $request->input('exam2_id');

            $preferences = Preference::where('student_id',$student_id)->get();

            if($preferences->isNotEmpty()) {
                try {
                    
                    $preference = Preference::where('student_id',$student_id)->update(['exam2_id' => $exam2_id]);

                    return response()->json([
                        'status'  => true,
                        'message' => 'Updated exam preference'
                    ], 200);
                } catch (\Exception $e) {
                    return response()->json([
                        'status'  => false,
                        'message' => 'Failed to update exam preference'
                    ], 409);
                }
            }
            else{
                return response()->json([
                        'status'  => false,
                        'message' => 'Failed to find exam id for the student'
                    ], 409);
            }
        }
    }
?>