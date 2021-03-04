<?php
    namespace App\Http\Controllers\Admin;

    use Carbon\Carbon;
    use Illuminate\Http\Request;
    use Illuminate\Support\Str;
    use Illuminate\Support\Facades\DB;

    use App\Http\Controllers\Controller;
    use App\Subject;
    use App\User;
    use App\Order;
    use App\UserStudent;
    use App\Course;

    class DashboardController extends Controller {
        public function __construct() {

        }

        public function index() {
            
            $courseCount        = Order::where('status', '=', 'paid')->count();
            $studentcount       = User::where('status', '=', 'Active')->where('profile', '=', 'HpQ8T868WqcZnETcjUe54K2Z')                   ->count();
            $totalusers         = User::where('status', '=', 'Active')->count();
            $newstudentsCount   = User::where('status', '=', 'Active')
                                ->where('profile', '=', 'HpQ8T868WqcZnETcjUe54K2Z')
                                ->whereMonth('created_at', Carbon::now()->month)->count();
            $subjectsCount      = Subject::where('status', '=', 'Active')->count();
            $teacherCount       = User::where('profile', '=', 'Jd3kyosci1sCSyeOo9sX1c9P')->count();
            $accourseCount      = Course::where('status', '=', 'Active')->count();
            $salesCount         = Order::where('status', '=', 'paid')->whereMonth('created_at', Carbon::now()->month)->count();

            //$countArray = [];
            $countArray['coursespurchased']     = $courseCount;
            $countArray['studentcount']         = $studentcount;
            $countArray['subjectcount']         = $subjectsCount;
            $countArray['teachercount']         = $teacherCount;
            $countArray['activecourses']        = $accourseCount;
            $countArray['newstudentcount']        = $newstudentsCount;
            $countArray['salesthismonth']        = $salesCount;
            $countArray['totalusers']        = $totalusers;

            return response()->json($countArray);
        }

        public function show($no){
            $coursesoldCount = Order::select('id', 'created_at')
                        ->where('status','=','paid')
                        ->get()
                        ->groupBy(function($date) {
                            //return Carbon::parse($date->created_at)->format('Y'); // grouping by years
                            return Carbon::parse($date->created_at)->format('m'); // grouping by months
                        });

                        $coursecount = [];
                        $countArray = [];

                        foreach ($coursesoldCount as $key => $value) {
                            $coursecount[(int)$key] = count($value);
                        }

                        for($i = 13 - $no; $i <= 12; $i++){
                            if(!empty($coursecount[$i])){
                                $countArray[$i] = $coursecount[$i];    
                            }else{
                                    $countArray[$i] = 0;    
                                }
                        }
            return response()->json($countArray);
        }

        public function usercount(){
            $users = User::select('id', 'updated_at')
                        ->where('status','=','Active')
                        ->where('profile', '=', 'HpQ8T868WqcZnETcjUe54K2Z')
                        ->get()
                        ->groupBy(function($date) {
                            //return Carbon::parse($date->created_at)->format('Y'); // grouping by years
                            return Carbon::parse($date->updated_at)->format('m'); // grouping by months
                        });

                        $usermcount = [];
                        $userArr = [];

                        foreach ($users as $key => $value) {
                            $usermcount[(int)$key] = count($value);
                        }

                        for($i = 1; $i <= 12; $i++){
                            if(!empty($usermcount[$i])){
                                $userArr[$i] = $usermcount[$i];    
                            }else{
                                    $userArr[$i] = 0;    
                                }
                        }

            return response()->json($userArr);
        }

        public function coursedist(){
        $courses = DB::table('orders')
                    ->select(DB::raw('COUNT(orders.id) as count'), 'courses.name')
                    ->join('courses','orders.course_id', '=', 'courses.id')
                    ->groupBy('course_id')
                    ->take(5)
                    ->orderBy('count', 'DESC')
                    ->get();
            return response()->json($courses);
        }
    }
?>