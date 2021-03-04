<?php
    namespace App\Http\Controllers\Admin\Mobile;

    use Carbon\Carbon;
    use Illuminate\Http\Request;
    use Tymon\JWTAuth\JWTAuth;
    use Illuminate\Support\Facades\DB;

    use App\Http\Controllers\Controller;
    use App\Course;
    use App\CourseReview;
    use App\User;
    use App\TeacherSubject;


    class MyCoursesController extends Controller {
        protected $jwt;

        public function __construct(JWTAuth $jwt) {
            $this->jwt = $jwt;
        }

        public function show(Request $request) {
            // $token     = $request->bearerToken();
            // $teacher   = $this->jwt->User($token);
            // $teacherId = $teacher->id;

            $teacherId = "TbCP6dC2d8sCEunigvMIYwoa";

            //$subjectId = $teacher->subject;

            $upcomingCourses = TeacherSubject::where('teacher_id',$teacherId)
                                ->join('course_subjects','teacher_subjects.subject_id','=','course_subjects.subject_id')
                                ->join('courses','course_subjects.course_id','=','courses.id')
                                ->join('subjects','teacher_subjects.subject_id','=','subjects.id')
                                ->join('course_lessons','teacher_subjects.subject_id','=','course_lessons.subject')
                                ->select('courses.id','courses.name','subjects.name as subjectname','subjects.id as subject_id','course_lessons.name as lessonname','course_lessons.id as lesson_id','courses.status','courses.thumbnail','courses.start_date','courses.end_date')
                                ->where('courses.start_date', '>', Carbon::now())
                                ->get();

            $upcoursesUnique = $upcomingCourses->unique('id');

            $ongoingCourses  = TeacherSubject::where('teacher_id',$teacherId)
                                ->join('course_subjects','teacher_subjects.subject_id','=','course_subjects.subject_id')
                                ->join('courses','course_subjects.course_id','=','courses.id')
                                ->join('subjects','teacher_subjects.subject_id','=','subjects.id')
                                ->join('course_lessons','teacher_subjects.subject_id','=','course_lessons.subject')
                                ->select('courses.id','courses.name','subjects.name as subjectname','subjects.id as subject_id', 'course_lessons.name as lessonname','course_lessons.id as lesson_id', 'courses.status','courses.thumbnail','courses.start_date','courses.end_date')
                                ->where('courses.start_date', '<=', Carbon::now())
                                ->where('courses.end_date','>',Carbon::now())
                                ->get();

            $coursesUnique      = $ongoingCourses->unique('id');

            $completedCourses  = TeacherSubject::where('teacher_id',$teacherId)
                                ->join('course_subjects','teacher_subjects.subject_id','=','course_subjects.subject_id')
                                ->join('courses','course_subjects.course_id','=','courses.id')
                                ->join('subjects','teacher_subjects.subject_id','=','subjects.id')
                                ->join('course_lessons','teacher_subjects.subject_id','=','course_lessons.subject')
                                ->select('courses.id','courses.name','subjects.name as subjectname','subjects.id as subject_id', 'course_lessons.name as lessonname','course_lessons.id as lesson_id', 'courses.status','courses.thumbnail','courses.start_date','courses.end_date')
                                ->where('courses.end_date','<',Carbon::now())
                                ->distinct()
                                ->get();

            $comUnique          = $completedCourses->unique('id');


            foreach ($ongoingCourses as $ongoingCourse) {
                $ongoingCourse->ratings = CourseReview::where('course', $ongoingCourse->id)->avg('rating');
                $ongoingCourse->reviews = CourseReview::where('course', $ongoingCourse->id)->where('review', '<>', '')->count();
            }

            return response()->json([
                'upcoming' => $upcoursesUnique,
                'ongoing'  => $coursesUnique,
                'completed' => $completedCourses
            ], 200);
        }

        public function getcoursereviews($cid) {
            $courseReviews = CourseReview::select('id','student','rating','review','date')->where('course', $cid)->get();

            foreach ($courseReviews as $courseReview) {
                $student = User::select('name')->where('id', $courseReview->student)->first();
                $courseReview->student = $student->name;
            }

            return response()->json($courseReviews);
        }

        public function mycourses($tid){
            $courses = TeacherSubject::where('teacher_id',$tid)
                        ->join('course_subjects','teacher_subjects.subject_id','=','course_subjects.subject_id')
                        ->join('courses','course_subjects.course_id','=','courses.id')
                        ->join('subjects','teacher_subjects.subject_id','=','subjects.id')
                        ->join('course_lessons','teacher_subjects.subject_id','=','course_lessons.subject')
                        ->select('courses.id','courses.name','subjects.name as subjectname','course_lessons.name as lessonname','course_lessons.id as lesson_id','courses.status','courses.thumbnail')
                        ->distinct()
                        ->get();
            return response()->json($courses);
        }
    }
?>