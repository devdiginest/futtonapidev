<?php
    namespace App\Http\Controllers\Admin;

    use Carbon\Carbon;
    use Illuminate\Http\Request;
    use Illuminate\Support\Str;
    use Illuminate\Support\Facades\Validator;

    use App\Http\Controllers\Controller;
    use App\CourseReview;
    use App\NotificationStudent;
    use App\StudentCourse;
    use App\User;
    use App\Preference;

    class StudentsController extends Controller {
        public function index() {
            $students = User::with('courses')->orderBy('created_at', 'desc')->where('profile', '=', 'HpQ8T868WqcZnETcjUe54K2Z')->paginate(5);
            return response()->json($students);
        }

        public function show($id) {
            $student = User::find($id);
            return response()->json($student);
        }

        public function fulllist(){
            $students = User::with('courses')->orderBy('created_at', 'desc')->where('profile', '=', 'HpQ8T868WqcZnETcjUe54K2Z')->get();
            return response()->json($students);
        }

        public function update(Request $request) {
            $validator = Validator::make($request->all(), [
                'id'        => 'required|string|max:24',
                'name'      => 'required|string|max:30',
                'mobile_no' => 'required|digits:10',
                'email'     => 'required|email|max:50',
                'status'    => 'required|string|max:15'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
                    'message' => $validator->errors()
                ], 409);
            }

            try {
                $currentTs = Carbon::now();

                $userStudent             = User::find($request->input('id'));
                $userStudent->name       = $request->input('name');
                $userStudent->mobile_no  = $request->input('mobile_no');
                $userStudent->email      = $request->input('email');
                $userStudent->status     = $request->input('status');
                $userStudent->updated_at = $currentTs;
                $userStudent->save();

                return response()->json([
                    'status'  => true,
                    'message' => 'Student updated successfully'
                ], 200);
            } catch (\Exception $e) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Unable to update Student'
                ], 409);
            }
        }

        public function destroy($id) {
            $userStudent    = User::find($id);
            // $courseReview   = CourseReview::where('student', $id)->get();
            // $studentCourse  = StudentCourse::where('student_id', $id)->get();
            // $notiStudent    = NotificationStudent::where('user', $id)->get();
            // $prefStudent    = Preference::where('student_id', $id)->get();

            if ($userStudent != null) {

                // if($courseReview != null){
                //     CourseReview::where('student', $id)->delete();
                // }
                // if($studentCourse != null){
                //     StudentCourse::where('student_id', $id)->delete();
                // }
                // if($notiStudent != null){
                //     NotificationStudent::where('user', $id)->delete();
                // }
                // if($prefStudent != null){
                //     Preference::where('student_id', $id)->delete();
                // }

                try {
                    $currentTs = Carbon::now();
                    $userStudent->status     = "Inactive";
                    $userStudent->updated_at = $currentTs;
                    $userStudent->save();

                    return response()->json([
                        'status'  => true,
                        'message' => 'Deleted selected Student'
                    ], 200);
                } catch (\Exception $e) {
                    return response()->json([
                        'status'  => false,
                        'message' => 'Unable to delete Student'
                    ], 409);
                }

                //$userStudent->delete();
            }

            return response()->json([
                'status'  => false,
                'message' => 'No Student exists with the given ID'
            ], 200);
        }
    }
?>