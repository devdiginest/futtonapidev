<?php
    namespace App\Http\Controllers\Admin;

    use Carbon\Carbon;
    use Illuminate\Http\Request;
    use Illuminate\Support\Str;
    use Illuminate\Support\Facades\Validator;
    use Illuminate\Support\Facades\DB;

    use App\Http\Controllers\Controller;
    use App\Subject;
    use App\User;
    use App\TeacherSubject;
    use App\CourseSubject;

    class SubjectsController extends Controller {
        public function __construct() {

        }

        public function index() {
            $subjects = Subject::orderBy('created_at', 'asc')->get();

            foreach ($subjects as $subject) {
               $subject->teachers = DB::table('teacher_subjects')
                                    ->join('users', 'users.id','=','teacher_subjects.teacher_id')
                                    ->where('subject_id',$subject->id)
                                    ->select('users.id','users.name')
                                    ->get();
            }

            return response()->json($subjects);
        }

        public function show($id) {
            $subject = Subject::find($id);
            return response()->json($subject);
        }

        public function create(Request $request) {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:50|unique:subjects,name'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
                    'message' => $validator->errors()
                ], 409);
            }

            try {
                $subjectId = Str::random(24);
                $currentTs = Carbon::now();

                $subject             = new Subject;
                $subject->id         = $subjectId;
                $subject->name       = $request->input('name');
                $subject->status     = 'Active';
                $subject->updated_at = $currentTs;
                $subject->save();

                return response()->json([
                    'status'     => true,
                    'message'    => 'Subject created successfully',
                    'subject_id' => $subjectId
                ], 201);
            } catch (\Exception $e) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Unable to create Subject',
                    'reason'  => $e
                ], 409);
            }
        }

        public function update(Request $request) {
            $validator = Validator::make($request->all(), [
                'id'     => 'required|string|max:24',
                'name'   => 'string|max:50|unique:subjects,name',
                'status' => 'required|string|max:15',
                'teacher_id'    => 'array|max:24|exists:users,id'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
                    'message' => $validator->errors()
                ], 409);
            }

            $teacher_id     =   $request->input('teacher_id');
            $subject_id     =   $request->input('id');

            try {
                $currentTs = Carbon::now();

                $subject             = Subject::find($subject_id);
                if($request->input('name') != null){
                    $subject->name       = $request->input('name');
                }
                $subject->status     = $request->input('status');
                $subject->updated_at = $currentTs;
                $subject->save();

                

                if($teacher_id != null){
                    $tsubjects  = TeacherSubject::where('subject_id',$subject_id);
                    $tsubjects->delete();
                    foreach ($teacher_id as $tid) {
                        $teacherSubject = new TeacherSubject;
                        $teacherSubject->teacher_id     =   $tid;
                        $teacherSubject->subject_id     =   $subject_id;
                        $teacherSubject->save();
                    }
                    
                }

                return response()->json([
                    'status'  => true,
                    'message' => 'Subject updated successfully'
                ], 200);
            } catch (\Exception $e) {
                echo $e;
                return response()->json([
                    'status'  => false,
                    'message' => 'Unable to update Subject',
                    'reason'  => $e
                ], 409);
            }
        }

        public function destroy($id) {
            $subject = Subject::find($id);

            if ($subject != null) {
                $courseSubject = CourseSubject::where('subject_id',$id)
                                ->join('courses','course_subjects.course_id','=','courses.id')
                                ->join('questions','course_subjects.subject_id','=','questions.subject')
                                ->where('courses.status','=','Active');
                if($courseSubject != null){
                    return response()->json([
                        'status'  => false,
                        'message' => 'Unable to delete subject'
                    ], 409);
                }
                else{
                   $subject->delete();
                   return response()->json([
                        'status'  => true,
                        'message' => 'Deleted selected Subject'
                    ], 200);
                }
                
            }

            return response()->json([
                'status'  => false,
                'message' => 'No Subject exists with the given ID'
            ], 409);
        }
    }
?>