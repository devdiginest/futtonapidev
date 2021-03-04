<?php
    namespace App\Http\Controllers\Admin;

    use Illuminate\Http\Request;
    use Illuminate\Support\Str;
    use Illuminate\Support\Facades\Validator;
    use Illuminate\Support\Facades\DB;

    use App\Http\Controllers\Controller;

    use App\User;
    use App\Order;
    use App\StudentScore;
    use App\DailyReport;

    class ReportsController extends Controller {
       

        public function fulltestreport(Request $request) {

            $validator = Validator::make($request->all(), [
                'test_id'   => 'required|string|max:24',
                'from'      => 'required|date',
                'to'        => 'date'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
                    'message' => $validator->errors()
                ], 409);
            }

            $from           =   $request->input('from');
            $to             =   $request->input('to');
            $test_id        =   $request->input('test_id');

            if($from != null && $to != null) {
               
                $studentlist = StudentScore::where('student_scores.test_id',$test_id)
                                ->whereBetween('student_scores.updated_at',[$from, $to])
                                ->join('users','student_scores.student_id','=','users.id')
                                ->select('users.name','student_scores.score','student_scores.student_id')
                                ->distinct()
                                ->get();
            }
            elseif($from != null){
                
                $studentlist = StudentScore::where('student_scores.test_id',$test_id)
                                ->where('student_scores.updated_at','>=', $from)
                                ->join('users','student_scores.student_id','=','users.id')
                                ->select('users.name','student_scores.score','student_scores.student_id')
                                ->distinct()
                                ->get();
            }


            return response()->json($studentlist);

        }

        // GET TEST REPORT

        public function gettestreport(Request $request) {

            $validator = Validator::make($request->all(), [
                'test_id'   => 'required|string|max:24',
                'from'      => 'required|date',
                'to'        => 'date',
                'page'      => 'int'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
                    'message' => $validator->errors()
                ], 409);
            }

            $from           =   $request->input('from');
            $to             =   $request->input('to');
            $test_id        =   $request->input('test_id');

            if($from != null && $to != null) {
               
                $studentlist = StudentScore::where('student_scores.test_id',$test_id)
                                ->whereBetween('student_scores.updated_at',[$from, $to])
                                ->join('users','student_scores.student_id','=','users.id')
                                ->select('users.name','student_scores.score','student_scores.student_id')
                                ->distinct()
                                ->get();

                $data = array();

                foreach ($studentlist as $key => $value) {
                    $data[] = array(
                        'name' => $value->name,
                        'score' => $value->score,
                        'student_id' => $value->student_id
                    );
                }
            }
            elseif($from != null){
                
                $studentlist = StudentScore::where('student_scores.test_id',$test_id)
                                ->where('student_scores.updated_at','>=', $from)
                                ->join('users','student_scores.student_id','=','users.id')
                                ->select('users.name','student_scores.score','student_scores.student_id')
                                ->distinct()
                                ->get();

                    $data = array();

                foreach ($studentlist as $key => $value) {
                    $data[] = array(
                        'name' => $value->name,
                        'score' => $value->score,
                        'student_id' => $value->student_id
                    );
                }
            }

            //return ($data);

            if($studentlist->isNotEmpty()){

                $scores = collect($data);
            
                $fulldata = collect($scores)
                        ->sortByDesc('score')
                        ->zip(range(1, $scores->count()))
                        ->map(function ($scoreAndRank){
                                                        list($score, $rank) = $scoreAndRank;
                                                        return array_merge($score, [
                                                            'rank' => $rank
                                                        ]);
                                                    })
                        ->groupBy('score')
                        ->map(function ($tiedScores){
                            $lowestRank = $tiedScores->pluck('rank')->min();
                            return $tiedScores->map(function ($rankedScore) use ($lowestRank){
                                return array_merge($rankedScore, [
                                    'rank' => $lowestRank,
                                ]);
                            });

                        })
                        ->collapse()
                        ->sortBy('rank');

                $items = $fulldata->forPage($request->input('page'), 3); //Filter the page var

                return response()->json($items);
            }
            else {
                return response()->json([
                    'status'  => false,
                    'message' => "No data found"
                ], 409);
            }

        }

        // GET COURSE REPORT

        public function getcoursereport(Request $request) {

            $validator = Validator::make($request->all(), [
                'course_id' => 'required|string|max:24|exists:courses,id',
                'from'      => 'required|date',
                'to'        => 'date'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
                    'message' => $validator->errors()
                ], 409);
            }

            $from           =   $request->input('from');
            $to             =   $request->input('to');
            $course_id      =   $request->input('course_id');

            if($from != null && $to != null) {
               $studentlist = Order::where('status', '=', 'paid')
                              ->where('course_id','=', $course_id)
                              ->whereBetween('updated_at', [$from, $to])
                              ->with('students')
                              ->distinct()
                              ->paginate(5); 
            }
            elseif($from != null){
                $studentlist = Order::where('status', '=', 'paid')
                                ->where('course_id','=', $course_id)
                                ->where('updated_at','>=', $from)
                                ->with('students')
                                ->distinct()
                                ->paginate(5);
            }
            

            return response()->json($studentlist);
        }

        // FULL COURSE REPORT

        public function fullcoursereport(Request $request) {

            $validator = Validator::make($request->all(), [
                'course_id' => 'required|string|max:24|exists:courses,id',
                'from'      => 'required|date',
                'to'        => 'date'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
                    'message' => $validator->errors()
                ], 409);
            }

            $from           =   $request->input('from');
            $to             =   $request->input('to');
            $course_id      =   $request->input('course_id');

            if($from != null && $to != null) {
               $studentlist = Order::where('status', '=', 'paid')
                              ->where('course_id','=', $course_id)
                              ->whereBetween('updated_at', [$from, $to])
                              ->with('students')
                              ->distinct()
                              ->get(); 
            }
            elseif($from != null){
                $studentlist = Order::where('status', '=', 'paid')
                                ->where('course_id','=', $course_id)
                                ->where('updated_at','>=', $from)
                                ->with('students')
                                ->distinct()
                                ->get();
            }
            

            return response()->json($studentlist);
        }

        // GET SALES REPORT

        public function getsalesreport(Request $request) {
            $validator = Validator::make($request->all(), [
                'from' => 'required|date',
                'to'   => 'date'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
                    'message' => $validator->errors()
                ], 409);
            }

            $from   =   $request->input('from');
            $to     =   $request->input('to');

            if($from != null && $to != null){
               $totalsales =  Order::where('orders.status', '=', 'paid')
                        ->whereBetween('orders.updated_at', [$from, $to])
                        ->join('courses','orders.course_id','=','courses.id')
                        ->select('courses.name as coursename','orders.course_id', \DB::raw('count(*) as count'))
                        ->groupBy('orders.course_id')
                        ->paginate(5); 
            }
            elseif($from != null){
                $totalsales = Order::where('status', '=', 'paid')
                        ->where('updated_at','>=', $from)
                        ->join('courses','orders.course_id','=','courses.id')
                        ->select('courses.name as coursename','orders.course_id', \DB::raw('count(*) as count'))
                        ->groupBy('orders.course_id')
                        ->paginate(5);
            }

            return response()->json($totalsales);
        }

        // FULL SALES REPORT

        public function fullsalesreport(Request $request) {
            $validator = Validator::make($request->all(), [
                'from' => 'required|date',
                'to'   => 'date'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
                    'message' => $validator->errors()
                ], 409);
            }

            $from   =   $request->input('from');
            $to     =   $request->input('to');

            if($from != null && $to != null){
               $totalsales = Order::where('orders.status', '=', 'paid')
                        ->whereBetween('orders.updated_at', [$from, $to])
                        ->join('courses','orders.course_id','=','courses.id')
                        ->select('courses.name as coursename','orders.course_id', \DB::raw('count(*) as count'))
                        ->groupBy('orders.course_id')
                        ->get(); 
            }
            elseif($from != null){
                $totalsales = Order::where('status', '=', 'paid')
                        ->where('updated_at','>=', $from)
                        ->join('courses','orders.course_id','=','courses.id')
                        ->select('courses.name as coursename','orders.course_id', \DB::raw('count(*) as count'))
                        ->groupBy('orders.course_id')
                        ->get(); 
            }

            return response()->json($totalsales);
        }


        // Get user Report

        public function getusersreport(Request $request) {
            $validator = Validator::make($request->all(), [
                'from' => 'required|date',
                'to'   => 'date'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
                    'message' => $validator->errors()
                ], 409);
            }

            $from   =   $request->input('from');
            $to     =   $request->input('to');

            if($from != null && $to != null){
               $totalusers = User::where('status', '=', 'Active')
                        ->whereBetween('joining_date', [$from, $to])
                        ->distinct()
                        ->paginate(5); 
            }
            elseif($from != null){
                $totalusers = User::where('status', '=', 'Active')
                        ->where('joining_date','>=',$from)
                        ->distinct()
                        ->paginate(5);
            }

            return response()->json($totalusers);
        }

        // Full User REport

        public function fullusersreport(Request $request) {
            $validator = Validator::make($request->all(), [
                'from' => 'required|date',
                'to'   => 'date'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
                    'message' => $validator->errors()
                ], 409);
            }

            $from   =   $request->input('from');
            $to     =   $request->input('to');

            if($from != null && $to != null){
               $totalusers = User::where('status', '=', 'Active')
                        ->whereBetween('joining_date', [$from, $to])
                        ->distinct()
                        ->get(); 
            }
            elseif($from != null){
                $totalusers = User::where('status', '=', 'Active')
                        ->where('joining_date','>=',$from)
                        ->distinct()
                        ->get();
            }

            return response()->json($totalusers);
        }

        //Teacher Report

        public function teacherreport(Request $request){
            $validator = Validator::make($request->all(), [
                'teacher_id'    => 'required|string|max:24|exists:users,id',
                'from'          => 'required|date',
                'to'            => 'date'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
                    'message' => $validator->errors()
                ], 409);
            }

            $from   =   $request->input('from');
            $to     =   $request->input('to');
            $teacher_id = $request->input('teacher_id');

            if($from != null && $to != null){
               $totalusers = DailyReport::where('teacher_id', '=', $teacher_id)
                        ->whereBetween('date', [$from, $to])
                        ->with('teachers')
                        ->with('subjects')
                        ->distinct()
                        ->get(); 
            }
            elseif($from != null){
                $totalusers = DailyReport::where('teacher_id', '=', $teacher_id)
                        ->where('date','>=',$from)
                        ->with('teachers')
                        ->with('subjects')
                        ->distinct()
                        ->get();
            }

            return response()->json($totalusers);

        }

        public function getteacherreport(Request $request){
            $validator = Validator::make($request->all(), [
                'teacher_id'    => 'required|string|max:24|exists:users,id',
                'from'          => 'required|date',
                'to'            => 'date'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
                    'message' => $validator->errors()
                ], 409);
            }

            $from   =   $request->input('from');
            $to     =   $request->input('to');
            $teacher_id = $request->input('teacher_id');

            if($from != null && $to != null){
               $totalusers = DailyReport::where('teacher_id', '=', $teacher_id)
                        ->whereBetween('date', [$from, $to])
                        ->with('teachers')
                        ->with('subjects')
                        ->distinct()
                        ->paginate(5); 
            }
            elseif($from != null){
                $totalusers = DailyReport::where('teacher_id', '=', $teacher_id)
                        ->where('date','>=',$from)
                        ->with('teachers')
                        ->with('subjects')
                        ->distinct()
                        ->paginate(5);
            }

            return response()->json($totalusers);

        }


    }
?>