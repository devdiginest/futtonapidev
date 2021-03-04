<?php
    namespace App\Http\Controllers\Admin\Mobile;

    use Carbon\Carbon;
    use Illuminate\Http\Request;
    use Tymon\JWTAuth\JWTAuth;

    use App\Http\Controllers\Controller;
    use App\Course;
    use App\CourseReview;

    class HomeController extends Controller {
        protected $jwt;

        public function __construct(JWTAuth $jwt) {
            $this->jwt = $jwt;
        }

        public function show(Request $request) {
            $token     = $request->bearerToken();
            $teacher   = $this->jwt->User($token);
            $teacherId = $teacher->id;
            $subjectId = $teacher->subject;

            $upcomingCourses = Course::select('id','name','start_date')->where('start_date', '>', Carbon::now())->get();
            $ongoingCourses  = Course::select('id','name')->where('start_date', '<=', Carbon::now())->get();

            foreach ($ongoingCourses as $ongoingCourse) {
                $ongoingCourse->ratings = CourseReview::where('course', $ongoingCourse->id)->avg('rating');
                $ongoingCourse->reviews = CourseReview::where('course', $ongoingCourse->id)->where('review', '<>', '')->count();
            }

            return response()->json([
                'upcoming' => $upcomingCourses,
                'ongoing'  => $ongoingCourses
            ], 200);
        }
    }
?>