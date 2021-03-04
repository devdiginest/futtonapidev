<?php
    namespace App\Http\Controllers\Student;

    use Illuminate\Http\Request;
    use Tymon\JWTAuth\JWTAuth;

    use App\Http\Controllers\Controller;
    use App\CourseLesson;
    use App\CourseSubject;
    use App\CourseLiveClass;

    class CourseDetailsController extends Controller {
        protected $jwt;

        public function __construct(JWTAuth $jwt) {
            $this->jwt = $jwt;
        }

        /********** COURSE-SUBJECT'S LESSON RELATED */

        public function getlessons(Request $request, $cid) {
            
            $userId = $this->getUserID($request);


            // $userId = "dNxRPFoTgCIoYV3zb0PBhGG7";

            //$lessonarray = array();

            // $courseLessons = CourseLesson::where('course', $cid)->with('subjects')->get();
            // if($courseLessons->isEmpty()){
                $courseLessons = CourseSubject::where('course_id', $cid)
                                ->with('lessons',function($q)
                                    {
                                        $q->where('status','=', 0);

                                    })
                                ->join('subjects','course_subjects.subject_id','=','subjects.id')
                                ->with('liveclasses')
                                ->get();
            //     foreach ($courseLessons as $subject) {
            //         $lessonarray[] = CourseLesson::where('subject', $subject->subject_id)->with('subjects')->get();
            //     }
            //     $new_array = $lessonarray[0];
            //     return response()->json($courseLessons);
            // }
            return response()->json($courseLessons);
        }

        public function getlesson(Request $request, $lid) {
            $userId = $this->getUserID($request);
            //$userId = "dNxRPFoTgCIoYV3zb0PBhGG7";
            $courseLesson = CourseLesson::where('id',$lid)->with('courses')->with('subjects')->get();
            return response()->json($courseLesson);
        }

        /********** COURSE-SUBJECT-LESSON'S LIVE-CLASS RELATED */

        public function getliveclasses(Request $request, $cid) {
            $userId = $this->getUserID($request);

            $courseLiveClasses = CourseLiveClass::where('course', $cid)->get();
            return response()->json($courseLiveClasses);
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
