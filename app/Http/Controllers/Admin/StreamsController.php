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
    use App\Stream;
    use App\ExamStream;

    class StreamsController extends Controller {
        public function __construct() {

        }

        public function index() {
            $streams = Stream::with('exams')->get();
            return response()->json($streams);
        }

        public function show($id) {
            $streams = Stream::with('exams')->where('id',$id)->get();
            return response()->json($streams);
        }

        public function create(Request $request) {

            $validator = Validator::make($request->all(), [
                'name'              => 'required|string|max:75',
                'display_order'     => 'required|numeric|max:100',
                'exams'             => 'required|array|exists:exams,id'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
                    'message' => $validator->errors()
                ], 409);
            }

            try {
                $currentTs = Carbon::now();

                $streams                              = new Stream;
                $streams->name                        = $request->input('name');
                $streams->display_order               = $request->input('display_order');
                $streams->updated_at                  = $currentTs;
                $streams->save();

                $streamId = $streams->id;
                
                $examStreams = $request->input('exams');

                foreach ($examStreams as $exams) {
                    $examStream                     =   new ExamStream;
                    $examStream->exams_id           =   $exams;
                    $examStream->streams_id         =   $streamId;
                    $examStream->save();
                }

                return response()->json([
                    'status'    => true,
                    'message'   => 'Successfully created new Stream',
                    'Stream Name' => $request->input('name')
                ], 201);
            } catch (\Exception $e) {
                echo $e;
                return response()->json([
                    'status'  => false,
                    'message' => 'Stream Creation failed'
                ], 409);
            }
        }

        public function update(Request $request) {
            
            $validator = Validator::make($request->all(), [
                'id'                => 'required|numeric',
                'name'              => 'required|string|max:75',
                'display_order'     => 'required|numeric|max:100',
                'exams'             => 'required|array|exists:exams,id'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
                    'message' => $validator->errors()
                ], 409);
            }

            try {
                $currentTs = Carbon::now();
                $streamId = $request->input('id');

                $streams                              = Stream::find($streamId);
                $streams->name                        = $request->input('name');
                $streams->display_order               = $request->input('display_order');
                $streams->updated_at                  = $currentTs;
                $streams->save();
                
                $examStreams = $request->input('exams');

                foreach ($examStreams as $exams) {
                    $streamExams = ExamStream::where('exams_id','=',$exams)->get();
                    if($streamExams->isEmpty()){
                        $streamExam                 =   new ExamStream;
                        $streamExam->exams_id       =   $exams;
                        $streamExam->streams_id     =   $streamId;
                        $streamExam->save();
                    }
                }

                return response()->json([
                    'status'    => true,
                    'message'   => 'Successfully Updated Stream',
                    'Stream Name' => $request->input('name')
                ], 201);
            } catch (\Exception $e) {
                echo $e;
                return response()->json([
                    'status'  => false,
                    'message' => 'Stream Updation failed'
                ], 409);
            }

        }

        public function destroy($id) {
            $streams = Stream::find($id);
            
            if ($streams != null) {
                $streams->delete();

                return response()->json([
                    'status'  => true,
                    'message' => 'Deleted selected Stream'
                ], 200);
            }

            return response()->json([
                'status'  => false,
                'message' => 'No Stream exists with the given ID'
            ], 200);
        }
    }
?>