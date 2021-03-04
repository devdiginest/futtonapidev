<?php
    namespace App\Http\Controllers\Admin\Mobile;

    use Illuminate\Http\Request;
    use Tymon\JWTAuth\JWTAuth;

    use App\Http\Controllers\Controller;
    use App\Notification;

    class NotificationsController extends Controller {
        protected $jwt;

        public function __construct(JWTAuth $jwt) {
            $this->jwt = $jwt;
        }

        public function show(Request $request) {
            $token     = $request->bearerToken();
            $teacher   = $this->jwt->User($token);
            $teacherId = $teacher->id;

            $notifications = Notification::select('id','title','msg','received_date')->where('user', $teacherId)->get();
            return response()->json($notifications);
        }
    }
?>