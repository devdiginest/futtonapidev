<?php
    namespace App\Http\Controllers\Admin;

    use Carbon\Carbon;
    use Illuminate\Http\Request;
    use Illuminate\Support\Str;
    use Illuminate\Support\Facades\Validator;
    use Illuminate\Support\Facades\Response;
    use Illuminate\Support\Facades\Storage;

    use App\Http\Controllers\Controller;
    use App\Exam;
    use App\CourseExam;

    class ExamController extends Controller {
        public function __construct() {

        }

        public function index() {
            $exams = Exam::with('categories')->get();
            return response()->json($exams);
        }

        public function show($id) {
            $exams = Exam::with('categories')->where('id',$id)->get();
            return response()->json($exams);
        }

        public function create(Request $request) {

            $validator = Validator::make($request->all(), [
                'name'              => 'required|string|max:75',
                'display_order'     => 'required|numeric|max:100',
                'course_category'   => 'required|array|exists:course_categories,id'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
                    'message' => $validator->errors()
                ], 409);
            }

            try {
                $currentTs = Carbon::now();

                $exams                              = new Exam;
                $exams->name                        = $request->input('name');
                $exams->display_order               = $request->input('display_order');
                $exams->updated_at                  = $currentTs;
                $exams->save();

                $examId = $exams->id;
                
                $courseCategory = $request->input('course_category');

                foreach ($courseCategory as $categories) {
                    $courseExam                 =   new CourseExam;
                    $courseExam->category_id    = $categories;
                    $courseExam->exams_id       =   $examId;
                    $courseExam->save();
                }

                return response()->json([
                    'status'    => true,
                    'message'   => 'Successfully created new Exam',
                    'Exam Name' => $request->input('name')
                ], 201);
            } catch (\Exception $e) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Exam Creation creation failed'
                ], 409);
            }
        }

        public function update(Request $request) {
            
            $validator = Validator::make($request->all(), [
                'id'                => 'required|numeric',
                'name'              => 'required|string|max:75',
                'display_order'     => 'required|numeric|max:100',
                'course_category'   => 'required|array|exists:course_categories,id'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
                    'message' => $validator->errors()
                ], 409);
            }

            try {
                $currentTs = Carbon::now();
                $examId = $request->input('id');

                $exams                              = Exam::find($examId);
                $exams->name                        = $request->input('name');
                $exams->display_order               = $request->input('display_order');
                $exams->updated_at                  = $currentTs;
                $exams->save();
                
                $courseCategory = $request->input('course_category');

                foreach ($courseCategory as $categories) {
                    
                    $exams = CourseExam::where('category_id','=',$categories)->get();
                    if($exams->isEmpty()){
                        $courseExam                 =   new CourseExam;
                        $courseExam->category_id    =   $categories;
                        $courseExam->exams_id       =   $examId;
                        $courseExam->save();
                    }
                }

                return response()->json([
                    'status'    => true,
                    'message'   => 'Successfully Updated Exam',
                    'Exam Name' => $request->input('name')
                ], 201);
            } catch (\Exception $e) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Exam Updation failed'
                ], 409);
            }

        }

        public function destroy($id) {
            $exams = Exam::find($id);
            
            if ($exams != null) {
                $exams->delete();

                return response()->json([
                    'status'  => true,
                    'message' => 'Deleted selected Exam'
                ], 200);
            }

            return response()->json([
                'status'  => false,
                'message' => 'No Exam exists with the given ID'
            ], 200);
        }
    }
?>