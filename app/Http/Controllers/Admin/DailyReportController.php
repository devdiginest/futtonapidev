<?php
    namespace App\Http\Controllers\Admin;

    use Carbon\Carbon;
    use Illuminate\Http\Request;
    use Illuminate\Support\Str;
    use Illuminate\Support\Facades\Mail;
    use Illuminate\Support\Facades\Validator;
    use Illuminate\Support\Facades\DB;

    use App\Http\Controllers\Controller;
    use App\User;
    use App\Subject;
    use App\TeacherSubject;
    use App\DailyReport;

    class DailyReportController extends Controller {
        

        public function index() {
            //$dailyReports = DailyReport::where('status', '=', '0')->get();
            $dailyReports = DB::table('daily_report')
                            ->join('users', 'daily_report.teacher_id', '=', 'users.id')
                            ->join('subjects', 'daily_report.subject_id', '=', 'subjects.id')
                            ->select('daily_report.*','users.name as teachername','users.id as teacher_id','subjects.name','subjects.id as subject_id')
                            ->get();
            
            return response()->json($dailyReports);
        }

        public function show($id) {
            //$dailyReports = DailyReport::where('id',$id)->get();
            $dailyReports = DB::table('daily_report')
                            ->join('users', 'daily_report.teacher_id', '=', 'users.id')
                            ->join('subjects', 'daily_report.subject_id', '=', 'subjects.id')
                            ->select('daily_report.*','users.name as teachername','users.id as teacher_id','subjects.name','subjects.id as subject_id')
                            ->where('daily_report.id','=',$id)
                            ->get();
            return response()->json($dailyReports);
        }

        

        public function create(Request $request) {
            $validator = Validator::make($request->all(), [
                'teacher_id'            => 'required|string|max:24',
                'date'                  => 'required|date',
                'working_hours'         => 'required|numeric|max:16',
                'subject_id'            => 'required|string|max:24',
                'profile'               => 'required|string|max:24'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
                    'message' => $validator->errors()
                ], 409);
            }

            $profile = $request->input('profile');

            if($profile == 'Jd3kyosci1sCSyeOo9sX1c9P' || $profile == 'eZaa8CJEnQYal9XWaqoCpVSF'){

                try {

                    $teacher_id             = $request->input('teacher_id');
                    $date                   = $request->input('date');
                    $hrs                    = $request->input('working_hours');
                    $subject_id             = $request->input('subject_id');
                    $currentTs              = Carbon::now();

                    $dailyReport                    = new DailyReport;
                    $dailyReport->teacher_id        = $teacher_id;
                    $dailyReport->date              = $date;
                    $dailyReport->working_hours     = $hrs;
                    $dailyReport->subject_id        = $subject_id;

                    if($profile == 'Jd3kyosci1sCSyeOo9sX1c9P'){
                        $dailyReport->status = "1";
                    }
                    else{
                        $dailyReport->status = "0";
                    }
                    $dailyReport->updated_at   = $currentTs;
                    $dailyReport->save();


                    return response()->json([
                        'status'   => true,
                        'message'  => 'Daily Report Added Successfully'
                    ], 201);
                } catch (\Exception $e) {
                    return response()->json([
                        'status'  => false,
                        'message' => 'Daily Report adding failed'
                    ], 409);
                }

            }
            else{
                return response()->json([
                    'status'  => false,
                    'message' => 'Not Authorized to add report'
                ], 409);
            }
        }

        public function update(Request $request) {
            $validator = Validator::make($request->all(), [
                'id'                    => 'required|exists:daily_report',
                'teacher_id'            => 'required|string|max:24',
                'date'                  => 'required|date',
                'working_hours'          => 'required|numeric|max:16',
                'subject_id'            => 'required|string|max:24',
                'status'                => 'required|numeric|max:2',
                'profile'               => 'required|string|max:24'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
                    'message' => $validator->errors()
                ], 409);
            }

            $profile = $request->input('profile');

            if($profile == 'Jd3kyosci1sCSyeOo9sX1c9P' || $profile == 'eZaa8CJEnQYal9XWaqoCpVSF'){

                try {

                    $teacher_id             = $request->input('teacher_id');
                    $date                   = $request->input('date');
                    $hrs                    = $request->input('working_hours');
                    $subject_id             = $request->input('subject_id');
                    $currentTs              = Carbon::now();

                    $dailyReport        = DailyReport::find($request->input('id'));
                    $dailyReport->teacher_id        = $teacher_id;
                    $dailyReport->date              = $date;
                    $dailyReport->working_hours     = $hrs;
                    $dailyReport->subject_id        = $subject_id;
                    $dailyReport->status            = $request->input('status');

                    // if($profile == 'Jd3kyosci1sCSyeOo9sX1c9P'){
                    //     $dailyReport->status = "1";
                    // }
                    // else{
                    //     $dailyReport->status = "0";
                    // }
                    $dailyReport->updated_at   = $currentTs;
                    $dailyReport->save();


                    return response()->json([
                        'status'   => true,
                        'message'  => 'Daily Report Updated Successfully'
                    ], 201);
                } catch (\Exception $e) {
                    echo $e;
                    return response()->json([
                        'status'  => false,
                        'message' => 'Daily Report updation failed'
                    ], 409);
                }

            }
            else{
                return response()->json([
                    'status'  => false,
                    'message' => 'Not Authorized to update report'
                ], 409);
            }
        }

        public function destroy($id) {

            $dailyReport = DailyReport::find($id);

            if ($dailyReport != null) {

                $dailyReport->delete();

                return response()->json([
                        'status'  => true,
                        'message' => 'Daily Report Deleted'
                    ], 200);
                
                // try {
                //     $currentTs = Carbon::now();
                //     $dailyReport->status          = "1";
                //     $dailyReport->updated_at      = $currentTs;
                //     $dailyReport->save();

                //     return response()->json([
                //         'status'  => true,
                //         'message' => 'Daily Report Deleted'
                //     ], 200);
                // } catch (\Exception $e) {
                //     return response()->json([
                //         'status'  => false,
                //         'message' => 'Unable to delete Daily Report'
                //     ], 409);
                // }

            }

            return response()->json([
                'status'  => false,
                'message' => 'No report exists with the given ID'
            ], 200);
        }

       

        public function adminapproval() {
            
            $dailyReports = DB::table('daily_report')
                            ->join('users', 'daily_report.teacher_id', '=', 'users.id')
                            ->join('subjects', 'daily_report.subject_id', '=', 'subjects.id')
                            ->select('daily_report.*','users.name as teachername','users.id as teacher_id','subjects.name','subjects.id as subject_id')
                            ->where('daily_report.status','=','1')
                            ->get();

            return response()->json($dailyReports);
        }
    }
?>