<?php
    namespace App\Http\Controllers\Admin;

    use Carbon\Carbon;
    use Illuminate\Http\Request;
    use Illuminate\Support\Str;
    use Illuminate\Support\Facades\Validator;
    use Illuminate\Support\Facades\Response;
    use Illuminate\Support\Facades\Storage;

    use App\Http\Controllers\Controller;
    use App\Course;
    use App\CourseSubject;
    use App\CourseLesson;
    use App\CourseLiveClass;
    use App\CourseCatRelation;
    use App\CourseExamRelation;
    use App\CourseStreamRelation;
    use App\CourseComment;

    class CourseCommentsController extends Controller {
        public function __construct() {

        }

        public function index() {
            $courseComment = CourseComment::orderBy('course_comments.created_at', 'desc')
                ->get();

            return response()->json($courseComment);
        }

        public function show($id) {
            $courseComment = CourseComment::orderBy('course_comments.created_at', 'desc')->where('course_id',$id)
                            ->join('users','course_comments.user_id','=','users.id')
                ->get();


            return response()->json($courseComment);        
        }

        public function create(Request $request) {
            $validator = Validator::make($request->all(), [
                'user_id'           => 'required|string|max:24|exists:users,id',
                'profile'           => 'required|string|max:24|exists:users,profile',
                'course_id'         => 'required|string|max:24|exists:courses,id',
                'comment'           => 'required|string|max:500'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
                    'message' => $validator->errors()
                ], 409);
            }

            try {
                
                $currentTs = Carbon::now();

                $courseComment                      = new CourseComment;
                $courseComment->user_id             = $request->input('user_id');
                $courseComment->profile             = $request->input('profile');
                $courseComment->course_id           = $request->input('course_id');
                $courseComment->comment             = $request->input('comment');
                $courseComment->updated_at          = $currentTs;
                $courseComment->save();
                

                return response()->json([
                    'status'    => true,
                    'message'   => 'Comment added'
                ], 201);
            } catch (\Exception $e) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Comment adding failed'
                ], 409);
            }
        }

        public function update(Request $request) {

            $validator = Validator::make($request->all(), [
                'id'                => 'required|numeric|exists:course_comments,id',
                'user_id'           => 'required|string|max:24|exists:users,id',
                'profile'           => 'required|string|max:24|exists:users,profile',
                'course_id'         => 'required|string|max:24|exists:courses,id',
                'comment'           => 'required|string|max:500'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
                    'message' => $validator->errors()
                ], 409);
            }

            $id        =    $request->input('id');
            $currentTs = Carbon::now();
            $courseComment  = CourseComment::find($id);

            if($courseComment != null){
                try {

                    $courseComment->user_id             = $request->input('user_id');
                    $courseComment->profile             = $request->input('profile');
                    $courseComment->course_id           = $request->input('course_id');
                    $courseComment->comment             = $request->input('comment');
                    $courseComment->updated_at          = $currentTs;
                    $courseComment->save();
                    

                    return response()->json([
                        'status'    => true,
                        'message'   => 'Comment Updated'
                    ], 201);
                } catch (\Exception $e) {
                    return response()->json([
                        'status'  => false,
                        'message' => 'Comment Updation failed'
                    ], 409);
                }
            }
            else{
                return response()->json([
                        'status'  => false,
                        'message' => 'Comment Not found for the particular ID'
                    ], 409);
            }
            
        }


        public function destroy($id) {

            $courseComment  = CourseComment::find($id);

            if($courseComment != null){
                
                $courseComment->delete();
                return response()->json([
                        'status'    => true,
                        'message'   => 'Comment deleted'
                    ], 201);
            }
            else{
                return response()->json([
                        'status'  => false,
                        'message' => 'Comment Not found for the partcular ID'
                    ], 409);
            }
           
        }
    }
?>