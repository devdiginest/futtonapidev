<?php
    

    namespace App\Http\Controllers\Student;
    require_once base_path()."/vendor/razorpay/razorpay/Razorpay.php";
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Validator;
    use Razorpay\Api\Api;
    use Tymon\JWTAuth\JWTAuth;

    use App\Http\Controllers\Controller;
    use App\Order;
    use App\StudentCourse;
    use App\StudentScore;


    class OrdersController extends Controller {

        private $razorpayKey;
        private $razorpaySecret;
        protected $jwt;

        public function __construct(JWTAuth $jwt) {
            $this->jwt = $jwt;
            $this->razorpayKey = env('RAZOR_KEY');
            $this->razorpaySecret = env('RAZOR_SECRET');
        }

        public function index(Request $request) {
            $userId = $this->getUserID($request);
            $orders = Order::where('student_id', $userId)
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json($orders);
        }

        public function create(Request $request) {
            $userId = $this->getUserID($request);

            //$userId = $request->input('student_id');

            $validator = Validator::make($request->all(), [
                'course_id' => 'required|string|max:24|exists:courses,id',
                'amount'    => 'required|numeric'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
                    'message' => $validator->errors()
                ], 409);
            }

            try {
                $rpReceipt = 'FREC-' . $userId . '-' . time();
                $rpAmount  = $request->input('amount') * 100;

                $razorpayAPI   = new Api($this->razorpayKey, $this->razorpaySecret);
                $razorpayOrder = $razorpayAPI->order->create(array(
                    'amount'   => $rpAmount,
                    'currency' => 'INR',
                    'receipt'  => $rpReceipt
                ));

                $order             = new Order;
                $order->student_id = $userId;
                $order->course_id  = $request->input('course_id');
                $order->amount     = $request->input('amount');
                $order->receipt    = $rpReceipt;
                $order->order_id   = $razorpayOrder->id;
                $order->status     = $razorpayOrder->status;
                $order->save();

                

                return response()->json([
                    'status'   => true,
                    'message'  => 'Order created',
                    'order_id' => $razorpayOrder->id
                ], 201);
            } catch (\Exception $e) {
                echo $e;
                return response()->json([
                    'status'  => false,
                    'message' => 'Order creation failed'
                ], 409);
            }
        }

        public function update(Request $request) {
            $userId = $this->getUserID($request);

            $validator = Validator::make($request->all(), [
                'order_id'   => 'required|string',
                'payment_id' => 'required|string',
                'signature'  => 'required|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
                    'message' => $validator->errors()
                ], 409);
            }

            try {
                $order             = Order::where('order_id', $request->input('order_id'))->first();
                $order->status     = 'paid';
                $order->payment_id = $request->input('payment_id');
                $order->signature  = $request->input('signature');
                $order->save();

                $studentCourse = new StudentCourse;
                $studentCourse->student_id = $userId;
                $studentCourse->course_id  = $order->course_id;
                $studentCourse->save();

                return response()->json([
                    'status'  => true,
                    'message' => 'Order updated'
                ], 201);
            } catch (\Exception $e) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Order updation failed'
                ], 409);
            }
        }

        public function freecourse(Request $request){
            $userId = $this->getUserID($request);

            //$userId = $request->input('student_id');

            $validator = Validator::make($request->all(), [
                'student_id'    =>  'required|string|max:24|exists:users,id',
                'course_id'     =>  'required|string|max:24|exists:courses,id'

            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
                    'message' => $validator->errors()
                ], 409);
            }

            try
            {

                //$userId = $request->input('student_id');

                $order             = new Order;
                $order->student_id = $userId;
                $order->course_id  = $request->input('course_id');
                $order->amount     = "0";
                $order->status     = "paid";
                $order->save();

                $studentCourse = new StudentCourse;
                $studentCourse->student_id = $userId;
                $studentCourse->course_id  = $request->input('course_id');
                $studentCourse->save();

                return response()->json([
                    'status'   => true,
                    'message'  => 'Subscribed to free course'
                ], 201);
            } catch (\Exception $e) {
                echo $e;
                return response()->json([
                    'status'  => false,
                    'message' => 'Subscription failed'
                ], 409);
            }
        }

        public function createtest(Request $request) {
            $userId = $this->getUserID($request);

            //$userId = $request->input('student_id');

            $validator = Validator::make($request->all(), [
                'test_id' => 'required|string|max:24|exists:tests_n_quizzes,id',
                'amount'    => 'required|numeric'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
                    'message' => $validator->errors()
                ], 409);
            }

            $test_id = $request->input('test_id');

            $checkUser = Order::where('test_id',$test_id)->where('student_id',$userId)->get();

            if($checkUser->isEmpty()){

               try {
                    $rpReceipt = 'FREC-' . $userId . '-' . time();
                    $rpAmount  = $request->input('amount') * 100;

                    $razorpayAPI   = new Api($this->razorpayKey, $this->razorpaySecret);
                    $razorpayOrder = $razorpayAPI->order->create(array(
                        'amount'   => $rpAmount,
                        'currency' => 'INR',
                        'receipt'  => $rpReceipt
                    ));

                    $order             = new Order;
                    $order->student_id = $userId;
                    $order->test_id    = $test_id;
                    $order->amount     = $request->input('amount');
                    $order->receipt    = $rpReceipt;
                    $order->order_id   = $razorpayOrder->id;
                    $order->status     = $razorpayOrder->status;
                    $order->save();

                    return response()->json([
                        'status'   => true,
                        'message'  => 'Order created',
                        'order_id' => $razorpayOrder->id
                    ], 201);
                } catch (\Exception $e) {
                    echo $e;
                    return response()->json([
                        'status'  => false,
                        'message' => 'Order creation failed'
                    ], 409);
                } 
            }

            else{
                return response()->json([
                        'status'  => false,
                        'message' => 'Already Purchased'
                    ], 409);
            }

            
        }

        public function freetest(Request $request){
            $userId = $this->getUserID($request);

            //$userId = $request->input('student_id');

            $validator = Validator::make($request->all(), [
                'student_id'    =>  'required|string|max:24|exists:users,id',
                'test_id'     =>  'required|string|max:24|exists:tests_n_quizzes,id'

            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
                    'message' => $validator->errors()
                ], 409);
            }

            $checkUser = Order::where('test_id',$test_id)->where('student_id',$userId)->get();

            if($checkUser->isEmpty()){
                try
                    {

                        //$userId = $request->input('student_id');

                        $order             = new Order;
                        $order->student_id = $userId;
                        $order->test_id  = $request->input('test_id');
                        $order->amount     = "0";
                        $order->status     = "paid";
                        $order->save();

                        return response()->json([
                            'status'   => true,
                            'message'  => 'Subscribed to free test'
                        ], 201);
                    } catch (\Exception $e) {
                        echo $e;
                        return response()->json([
                            'status'  => false,
                            'message' => 'Subscription failed'
                        ], 409);
                    }
            }
            else{
                return response()->json([
                            'status'  => false,
                            'message' => 'Already Subscribed'
                        ], 409);
            }

            
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
