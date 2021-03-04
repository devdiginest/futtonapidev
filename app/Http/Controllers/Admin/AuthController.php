<?php
    namespace App\Http\Controllers\Admin;

    use Carbon\Carbon;
    use Illuminate\Http\Request;
    use Illuminate\Support\Str;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Hash;
    use Illuminate\Support\Facades\Mail;
    use Illuminate\Support\Facades\Validator;
    use Tymon\JWTAuth\JWTAuth;

    use App\Http\Controllers\Controller;
    use App\User;

    class AuthController extends Controller {
        private $fromEmailName;
        private $fromEmailAddress;
        protected $jwt;

        public function __construct(JWTAuth $jwt) {
            $this->jwt = $jwt;
            $this->fromEmailName = env('MAIL_FROM_NAME');
            $this->fromEmailAddress = env('MAIL_FROM_ADDRESS');
        }

        public function register(Request $request) {
            $validator = Validator::make($request->all(), [
                'name'         => 'required|string|max:30',
                'mobile_no'    => 'required|digits:10|unique:users',
                'email'        => 'required|email|max:50|unique:users',
                'password'     => 'required|string|min:8|max:12',
                'joining_date' => 'required|date',
                'subject'      => 'required|string|exists:subjects,id'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
                    'message' => $validator->errors()
                ], 409);
            }

            try {
                $adminId   = Str::random(24);
                $name      = $request->input('name');
                $email     = $request->input('email');
                $password  = app('hash')->make($request->input('password'));
                $currentTs = Carbon::now();

                $userAdmin               = new User;
                $userAdmin->id           = $adminId;
                $userAdmin->profile      = 'Jd3kyosci1sCSyeOo9sX1c9P';
                $userAdmin->name         = $name;
                $userAdmin->mobile_no    = $request->input('mobile_no');
                $userAdmin->email        = $email;
                $userAdmin->password     = $password;
                $userAdmin->joining_date = $request->input('joining_date');
                $userAdmin->subject      = $request->input('subject');
                $userAdmin->status       = 'Active';
                $userAdmin->updated_at   = $currentTs;
                $userAdmin->save();

                // TODO: SEND WELCOME EMAIL TO TEACHER
                // sendwelcomeemail($name, $email, $password);

                return response()->json([
                    'status'   => true,
                    'message'  => 'Account created. Welcome email sent to ' . $email,
                    'admin_id' => $adminId
                ], 201);
            } catch (\Exception $e) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Teacher registration failed'
                ], 409);
            }
        }

        public function login(Request $request) {
            $validator = Validator::make($request->all(), [
                'email'    => 'required|email',
                'password' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
                    'message' => $validator->errors()
                ], 409);
            }

            try {
                $userAdmin = User::where('email', $request->input('email'))
                    ->where('profile', '<>', 'HpQ8T868WqcZnETcjUe54K2Z')
                    ->where('status', '<>', 'Inactive')
                    ->first();

                if ($userAdmin) {
                    if (Hash::check($request->input('password'), $userAdmin->password)) {
                        $credentials = [
                            'email'    => $request->input('email'),
                            'password' => $request->input('password')
                        ];

                        return response()->json([
                            'status'   => true,
                            'message'  => 'Login success',
                            'admin_id' => $userAdmin->id,
                            'profile'  => $userAdmin->profile,
                            'token'    => $this->jwt->attempt($credentials)
                        ], 200);
                    } else {
                        return response()->json([
                            'status'  => false,
                            'message' => 'Incorrect password'
                        ], 401);
                    }
                } else {
                    return response()->json([
                        'status'  => false,
                        'message' => 'User not found'
                    ], 404);
                }
            } catch (\Exception $e) {
                echo $e;
                return response()->json([
                    'status'  => false,
                    'message' => 'Login failed'
                ], 409);
            }
        }

        public function changepassword(Request $request) {
            $validator = Validator::make($request->all(), [
                'teacher_id'    => 'required',
                'password'      => 'required|string|min:8|max:12'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
                    'message' => $validator->errors()
                ], 409);
            }

            try {
                $teacher_id = $request->input('teacher_id');
                $userTeacher = User::where('id', $teacher_id)->first();

                if ($userTeacher) {
                    $password  = app('hash')->make($request->input('password'));
                    $currentTs = Carbon::now();

                    $userTeacher->password       = $password;
                    $userTeacher->updated_at     = $currentTs;
                    $userTeacher->save();

                    return response()->json([
                        'status'  => true,
                        'message' => 'Password changed successfully'
                    ], 200);
                } else {
                    return response()->json([
                        'status'  => false,
                        'message' => 'Invalid'
                    ], 409);
                }
            } catch (\Exception $e) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Unable to change password'
                ], 409);
            }
        }

        public function forgotpassword(Request $request) {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
                    'message' => $validator->errors()
                ], 409);
            }

            try {
                $email     = $request->input('email');
                $userAdmin = User::where('profile', 'Jd3kyosci1sCSyeOo9sX1c9P')
                    ->where('email', $email)
                    ->first();

                if ($userAdmin) {
                    $resetPwdToken = Str::random(24);
                    $currentTs = Carbon::now();

                    $userAdmin->token_resetpwd = $resetPwdToken;
                    $userAdmin->updated_at     = $currentTs;
                    $userAdmin->save();

                    $this->sendforgotpasswordemail($email, $resetPwdToken);

                    return response()->json([
                        'status'  => true,
                        'message' => 'An email with instructions on resetting the password has been sent to your registered email'
                    ], 200);
                } else {
                    return response()->json([
                        'status'  => false,
                        'message' => 'Email not registered'
                    ], 404);
                }
            } catch (\Exception $e) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Sending password reset email failed'
                ], 409);
            }
        }

        public function resetpassword(Request $request, $token) {
            $validator = Validator::make($request->all(), [
                'password' => 'required|string|min:8|max:12|confirmed'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
                    'message' => $validator->errors()
                ], 409);
            }

            try {
                $userAdmin = User::where('token_resetpwd', $token)->first();

                if ($userAdmin) {
                    $password  = app('hash')->make($request->input('password'));
                    $currentTs = Carbon::now();

                    $userAdmin->password       = $password;
                    $userAdmin->token_resetpwd = '';
                    $userAdmin->updated_at     = $currentTs;
                    $userAdmin->save();

                    return response()->json([
                        'status'  => true,
                        'message' => 'Password reset successfully'
                    ], 200);
                } else {
                    return response()->json([
                        'status'  => false,
                        'message' => 'Invalid token'
                    ], 409);
                }
            } catch (\Exception $e) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Unable to reset password'
                ], 409);
            }
        }

        public function logout(Request $request) {
            try {
                // Auth::logout();
                return response()->json([
                    'status'  => true,
                    'message' => 'Logout successfull'
                ], 200);
            } catch (\Exception $e) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Logout failed'
                ], 409);
            }
        }

        /***********/
        /* PRIVATE */

        private function sendwelcomeemail($name, $email, $password) {
            $data = array(
                'name'     => $name,
                'email'    => $email,
                'password' => $password
            );

            Mail::send('welcome-teacher', $data, function($message) use ($data) {
                $message->to($data['email'])->subject('Welcome to Futton');
                $message->from($this->fromEmailAddress, $this->fromEmailName);
            });
        }

        private function sendforgotpasswordemail($email, $token) {
            $link = 'link://http://admin.myfutton.com/password/reset/' . $token;
            $data = array(
                'email' => $email,
                'link'  => $link
            );

            Mail::send('forgot-password', $data, function($message) use ($data) {
                $message->to($data['email'])->subject('Futton Password Reset Notification');
                $message->from($this->fromEmailAddress, $this->fromEmailName);
            });
        }
    }
?>