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

    class CoursesController extends Controller {
        public function __construct() {

        }

        public function index() {
            $courses = Course::orderBy('courses.created_at', 'desc')->where('courses.status','!=','Deleted')->with('categories')
                ->with('exams')
                ->with('streams')
                ->with('lessons')
                ->with('reviews')
                // ->join('course_lessons','courses.id','=','course_lessons.course')
                // ->select('courses.*', 'course_lessons.name as lessons_name', 'course_lessons.*','course_lessons.id as lessons_id')
                ->paginate(5);

            // foreach($courses as $course) 
            // { 
            //     $course['lessons'] = [
            //         'id' => $course['lessons_id'], 
            //         'name' => $course['lessons_name'],
            //         'description' => $course['description'],
            //         'resource_url' => $course['resource_url'],
            //         'resource_type' => $course['resource_type'],
            //         'resource_provider' => $course['resource_provider'],
            //         'file' => $course['file'],
            //     ]; 
            //     unset($course['lessons_id'], $course['lessons_name'],$course['description'],$course['resource_url'],$course['resource_type'],$course['resource_provider'],$course['file']);
            // }

            return response()->json($courses);
        }

        public function allcourses() {
            $courses = Course::orderBy('courses.created_at', 'desc')->where('courses.status','!=','Deleted')->with('categories')
                ->with('exams')
                ->with('streams')
                ->with('lessons')
                ->with('reviews')
                ->get();

            return response()->json($courses);
        }

        public function show($id) {
            $course = Course::with('subjects')->with('categories')->with('exams')->with('streams')->find($id);

            foreach ($course->subjects as $subject) {
                $subject->lessons = CourseLesson::where('subject', $subject->id)->get();

                foreach ($subject->lessons as $lesson) {
                    $lesson->liveclasses = CourseLiveClass::where('subject', $subject->id)->get();
                }
            }

            return response()->json($course);
        }

        public function create(Request $request) {
            $validator = Validator::make($request->all(), [
                'name'              => 'required|string|max:75',
                'validity'          => 'required|string|max:9',
                'start_date'        => 'date',
                'end_date'          => 'date',
                'price'             => 'required|digits_between:1,6',
                'category'          => 'required|array|exists:course_categories,id',
                'exams'             => 'required|array|exists:exams,id',
                'streams'           => 'required|array|exists:streams,id'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
                    'message' => $validator->errors()
                ], 409);
            }

            try {
                $courseId = Str::random(24);
                $currentTs = Carbon::now();

                $course             = new Course;
                $course->id         = $courseId;
                $course->name       = $request->input('name');
                $course->validity   = $request->input('validity');
                $course->start_date = $request->input('start_date');
                $course->end_date   = $request->input('end_date');
                $course->price      = $request->input('price');
                $course->status     = 'Draft';
                $course->updated_at = $currentTs;
                $course->save();

                $courseCategory     =   $request->input('category');
                $courseExams        =   $request->input('exams');
                $courseStreams      =   $request->input('streams');

                foreach ($courseCategory as $category) {
                    $courseCat                  =   new CourseCatRelation;
                    $courseCat->category_id     =   $category;
                    $courseCat->course_id       =   $courseId;
                    $courseCat->save();
                }
 
                foreach ($courseExams as $cexams) {
                    $courseExam                  =   new CourseExamRelation;
                    $courseExam->exams_id        =   $cexams;
                    $courseExam->course_id       =   $courseId;
                    $courseExam->save();
                }

                foreach ($courseStreams as $cstreams) {
                    $courseStream                    =   new CourseStreamRelation;
                    $courseStream->streams_id        =   $cstreams;
                    $courseStream->course_id         =   $courseId;
                    $courseStream->save();
                }

                return response()->json([
                    'status'    => true,
                    'message'   => 'Successfully created new Course',
                    'course_id' => $courseId
                ], 201);
            } catch (\Exception $e) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Course creation failed'
                ], 409);
            }
        }

        public function updatebasicsection(Request $request) {
            $validator = Validator::make($request->all(), [
                'id'              => 'required|string|max:24',
                'name'            => 'required|string|max:75',
                'validity'        => 'required|string|max:9',
                'start_date'      => 'date',
                'end_date'        => 'date',
                'price'           => 'required|digits_between:1,6',
                'subjects'        => 'required|array',
                'course_provider' => 'string|max:75',
                'status'          => 'required|string|max:15',
                'category'        => 'required|array|exists:course_categories,id',
                'exams'           => 'required|array|exists:exams,id',
                'streams'         => 'required|array|exists:streams,id'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
                    'message' => $validator->errors()
                ], 409);
            }

            try {
                $courseId = $request->input('id');
                $subjects = $request->input('subjects');
                $currentTs = Carbon::now();

                $course                  = Course::find($courseId);
                $course->name            = $request->input('name');
                $course->validity        = $request->input('validity');
                $course->start_date      = $request->input('start_date');
                $course->end_date        = $request->input('end_date');
                $course->price           = $request->input('price');
                $course->course_provider = $request->input('course_provider');
                $course->status          = $request->input('status');
                $course->updated_at      = $currentTs;
                $course->save();

                CourseSubject::where('course_id', $courseId)->delete();

                foreach ($subjects as $subjectId) {
                    $courseSubject             = new CourseSubject;
                    $courseSubject->course_id  = $courseId;
                    $courseSubject->subject_id = $subjectId;
                    $courseSubject->save();
                }

                $courseCategory     =   $request->input('category');
                $courseExams        =   $request->input('exams');
                $courseStreams      =   $request->input('streams');

                foreach ($courseCategory as $category) {
                    $cats = CourseCatRelation::where('category_id','=',$category)->get();
                    if($cats->isEmpty()){
                        $courseCat                  =   new CourseCatRelation;
                        $courseCat->category_id     =   $category;
                        $courseCat->course_id       =   $courseId;
                        $courseCat->save();
                    }
                }
 
                foreach ($courseExams as $cexams) {

                    $exams = CourseExamRelation::where('exams_id','=',$cexams)->get();
                    if($exams->isEmpty()){
                        $courseExam                  =   new CourseExamRelation;
                        $courseExam->exams_id        =   $cexams;
                        $courseExam->course_id       =   $courseId;
                        $courseExam->save();
                    }
                }

                foreach ($courseStreams as $cstreams) {

                    $streams = CourseStreamRelation::where('streams_id','=',$cstreams)->get();
                    if($streams->isEmpty()){
                        $courseStream                    =   new CourseStreamRelation;
                        $courseStream->streams_id        =   $cstreams;
                        $courseStream->course_id         =   $courseId;
                        $courseStream->save();
                    }
                }

                return response()->json([
                    'status'  => true,
                    'message' => 'Course basic details updated successfully'
                ], 200);
            } catch (\Exception $e) {
                echo $e;
                return response()->json([
                    'status'  => false,
                    'message' => 'Unable to update Course basic details'
                ], 409);
            }
        }

        public function updateadvancedsection(Request $request) {
            $validator = Validator::make($request->all(), [
                'id'                => 'required|string|max:24',
                'short_desc'        => 'string|max:255',
                'long_desc'         => 'required|string|max:1000',
                'level'             => 'string|max:15',
                'overview_provider' => 'string|max:15',
                'overview_url'      => 'string|max:75',
                'content_provider'  => 'string|max:15'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
                    'message' => $validator->errors()
                ], 409);
            }

            try {
                // TODO: SAVE/REPLACE IMAGE & CREATE/UPDATE THE IMAGE NAME WITH $thumbnail value
                $currentTs = Carbon::now();

                $course                    = Course::find($request->input('id'));
                $course->short_desc        = $request->input('short_desc');
                $course->long_desc         = $request->input('long_desc');
                $course->level             = $request->input('level');
                $course->overview_provider = $request->input('overview_provider');
                $course->overview_url      = $request->input('overview_url');
                $course->content_provider  = $request->input('content_provider');
                $course->updated_at        = $currentTs;
                $course->save();

                return response()->json([
                    'status'  => true,
                    'message' => 'Course advanced details updated successfully'
                ], 200);
            } catch (\Exception $e) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Unable to update Course advanced details'
                ], 409);
            }
        }

        public function storethumb(Request $request){
            $validator = Validator::make($request->all(), [
                'id'      => 'required|string|max:24',
                'thumb'   => 'required|mimetypes:video/avi,video/mpeg,video/quicktime,video/mp4,image/jpeg,image/jpg,image/png,image/bmp',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
                    'message' => $validator->errors()
                ], 409);
            }

             if ($request->hasFile('thumb')) {
                $md5Name = md5_file($request->file('thumb')->getRealPath());
                $guessExtension = $request->file('thumb')->guessExtension();
                $filename = $md5Name.'.'.$guessExtension;

                $destinationPath = storage_path('app/public/uploads/courses/');
                $file = $request->file('thumb')->move($destinationPath,$filename);
     
                //store your file into database
                $course                     = Course::find($request->input('id'));
                $course->thumbnail          = $filename;
                $course->save();

                return response()->json([
                    "success" => true,
                    "message" => "File successfully uploaded"
                ]);
      
            }
        }


        public function destroy($id) {
            $course = Course::find($id);

            if ($course != null) {
                $courseSubject = CourseSubject::where('course_id', $id)->delete();
                $course->status = 'Deleted';
                $course->save();

                return response()->json([
                    'status'  => true,
                    'message' => 'Deleted selected Course'
                ], 200);
            }

            return response()->json([
                'status'  => false,
                'message' => 'No Course exists with the given ID'
            ], 200);
        }
    }
?>