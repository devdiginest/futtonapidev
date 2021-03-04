<?php
    namespace App\Http\Controllers\Student;

    use Carbon\Carbon;
    use Illuminate\Http\Request;
    use Illuminate\Support\Str;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Config;
    use Illuminate\Support\Facades\Hash;
    use Illuminate\Support\Facades\Mail;
    use Illuminate\Support\Facades\Validator;
    use Tymon\JWTAuth\JWTAuth;
    use Tymon\JWTAuth\Token;

    use App\Http\Controllers\Controller;
    use App\User;
    use App\Preference;

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
                'name'      => 'required|string|max:30',
                'mobile_no' => 'required|digits:10|unique:users',
                'email'     => 'required|email|max:50|unique:users',
                'password'  => 'required|string|min:8|max:12'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
                    'message' => $validator->errors()
                ], 409);
            }

            try {
                $studentId = Str::random(24);
                $name      = $request->input('name');
                $email     = $request->input('email');
                $mobileNo  = $request->input('mobile_no');
                $password  = app('hash')->make($request->input('password'));
                $otp       = rand(1000, 9999);
                $currentTs = Carbon::now();

                $userStudent             = new User;
                $userStudent->profile    = 'HpQ8T868WqcZnETcjUe54K2Z';
                $userStudent->id         = $studentId;
                $userStudent->name       = $name;
                $userStudent->mobile_no  = $mobileNo;
                $userStudent->email      = $email;
                $userStudent->password   = $password;
                $userStudent->otp        = $otp;
                $userStudent->status     = 'Active';
                $userStudent->updated_at = $currentTs;
                $userStudent->save();

                // TODO: SEND WELCOME EMAIL TO STUDENT
                // sendwelcomeemail($name, $email, $password);

                // TODO: SEND SMS WITH OTP
                $this->sendOtpMsg($mobileNo, $otp);

                return response()->json([
                    'status'     => true,
                    'message'    => 'Registered Successfully',
                    'student_id' => $studentId
                ], 201);
            } catch (\Exception $e) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Unable to complete registration'
                ], 409);
            }
        }

        public function storepreferences(Request $request) {
            $validator = Validator::make($request->all(), [
                'student_id'  => 'required|string|max:24|exists:users,id',
                'exam1_id'    => 'required|int',
                'exam2_id'    => 'required|int',
                'language_id' => 'required|int',
                'stream_id'   => 'required|int'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
                    'message' => $validator->errors()
                ], 409);
            }

            try {
                $preference              = new Preference;
                $preference->student_id  = $request->input('student_id');
                $preference->exam1_id    = $request->input('exam1_id');
                $preference->exam2_id    = $request->input('exam2_id');
                $preference->language_id = $request->input('language_id');
                $preference->stream_id   = $request->input('stream_id');
                $preference->save();

                return response()->json([
                    'status'  => true,
                    'message' => 'Saved exam preference'
                ], 200);
            } catch (\Exception $e) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Failed to save exam preference'
                ], 409);
            }
        }

        
        public function verifyotp(Request $request) {
            $validator = Validator::make($request->all(), [
                'student_id' => 'required|string|max:24|exists:users,id',
                'otp'        => 'required|regex:/^\d{4}$/'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
                    'message' => $validator->errors()
                ], 409);
            }

            $student_id = $request->input('student_id');

            try {
                
                $userStudent = User::find($student_id);

                if ($userStudent != null) {
                    if ($userStudent->otp == $request->input('otp')) {
                        $userStudent->otp         = '';
                        $userStudent->is_verified = 1;
                        $userStudent->save();

                        return response()->json([
                            'status'  => true,
                            'message' => 'OTP verification complete'
                        ], 200);
                    } else {
                        return response()->json([
                            'status'  => false,
                            'message' => 'Incorrect OTP'
                        ], 409);
                    }
                } 
            } catch (\Exception $e) {
                echo $e;
                return response()->json([
                    'status'  => false,
                    'message' => 'OTP verification failed'
                ], 409);
            }
        }

        public function resendotp(Request $request) {
            $validator = Validator::make($request->all(), [
                'student_id' => 'required|string|max:24|exists:users,id'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
                    'message' => $validator->errors()
                ], 409);
            }

            try {
                $userStudent = User::find($request->input('student_id'));

                if ($userStudent) {
                    $userStudent->otp = rand(1000, 9999);
                    $userStudent->save();

                    return response()->json([
                        'status'  => true,
                        'message' => 'Resend OTP successfully'
                    ], 200);
                } else {
                    return response()->json([
                        'status'  => false,
                        'message' => 'Failed to resend OTP'
                    ], 409);
                }
            } catch (\Exception $e) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Failed to resend OTP'
                ], 409);
            }
        }

        public function login(Request $request) {
            $validator = Validator::make($request->all(), [
                'email'    => 'required|email|exists:users,email',
                'password' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
                    'message' => $validator->errors()
                ], 409);
            }

            try {
                $userStudent = User::where('profile', 'HpQ8T868WqcZnETcjUe54K2Z')
                    ->where('email', $request->input('email'))
                    ->where('status','!=','Inactive')
                    ->first();

                if ($userStudent) {
                    if (Hash::check($request->input('password'), $userStudent->password)) {
                        $credentials = [
                            'email'    => $request->input('email'),
                            'password' => $request->input('password')
                        ];

                        return response()->json([
                            'status'  => true,
                            'message' => 'Login success',
                            'token'   => $this->jwt->attempt($credentials),
                            'student_id'    => $userStudent->id,
                            'profile'       => $userStudent->profile
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
                return response()->json([
                    'status'  => false,
                    'message' => 'Login failed'
                ], 409);
            }
        }

        public function logout(Request $request) {
            try {
                $token = $request->bearerToken();
                $this->jwt->manager()->invalidate(new Token($token), $forceForever = false);

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

        public function forgotpassword(Request $request) {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email|exists:users,email'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
                    'message' => $validator->errors()
                ], 409);
            }

            try {
                $email = $request->input('email');
                $userStudent = User::where('profile', 'HpQ8T868WqcZnETcjUe54K2Z')
                    ->where('email', $email)
                    ->first();

                if ($userStudent) {
                    $resetPwdToken = Str::random(24);
                    $currentTs = Carbon::now();

                    $userStudent->token_resetpwd = $resetPwdToken;
                    $userStudent->updated_at     = $currentTs;
                    $userStudent->save();

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

        public function resetpassword(Request $request) {
            $validator = Validator::make($request->all(), [
                'token'    => 'required',
                'password' => 'required|string|min:8|max:12'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
                    'message' => $validator->errors()
                ], 409);
            }

            try {
                $token = $request->input('token');
                $userStudent = User::where('token_resetpwd', $token)->first();

                if ($userStudent) {
                    $password  = app('hash')->make($request->input('password'));
                    $currentTs = Carbon::now();

                    $userStudent->password       = $password;
                    $userStudent->token_resetpwd = '';
                    $userStudent->updated_at     = $currentTs;
                    $userStudent->save();

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

        public function changepassword(Request $request) {
            $validator = Validator::make($request->all(), [
                'student_id'    => 'required|exists:users,id',
                'password'      => 'required|string|min:8|max:12'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
                    'message' => $validator->errors()
                ], 409);
            }

            try {
                $student_id = $request->input('student_id');
                $userStudent = User::where('id', $student_id)->first();

                if ($userStudent) {
                    $password  = app('hash')->make($request->input('password'));
                    $currentTs = Carbon::now();

                    $userStudent->password       = $password;
                    $userStudent->updated_at     = $currentTs;
                    $userStudent->save();

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

        // public function otpfortest(Request $request){

        //     $mobileNo = $request->input('mobile');
        //     $otp = $request->input('otp');
            
        //     $varUserName        =       "t2ED-EM-Future";
        //     $varPWD             =       "62894227";
        //     $varSenderID        =       "EDFUTN";
        //     $url                =       "http://sms.nextsms.in/api/swsendSingle.asp";
        //     $message            =       $otp .' '. " is your OTP for verifying your mobile number on FUTTON.";
        //     $msg               = urlencode($message);
        //     $data               =       "username=".$varUserName."&password=".$varPWD."&sender=".$varSenderID."&sendto=91".$mobileNo."&message=".$msg;

        //     $this->postData($url,$data);
        // } 

        /***********/
        /* PRIVATE */

        private function sendwelcomeemail($name, $email, $password) {
            $data = array(
                'name'     => $name,
                'email'    => $email,
                'password' => $password
            );

            Mail::send('welcome-student', $data, function($message) use ($data) {
                $message->to($data['email'])->subject('Welcome to Futton');
                $message->from($this->fromEmailAddress, $this->fromEmailName);
            });
        }

        private function sendOtpMsg($mobileNo,$otp){
            
            $varUserName        =       "t2ED-EM-Future";
            $varPWD             =       "62894227";
            $varSenderID        =       "EDFUTN";
            $url                =       "http://sms.nextsms.in/api/swsendSingle.asp";
            $message            =       $otp .' '. " is your OTP for verifying your mobile number on FUTTON.";
            $msg                =       urlencode($message);
            $data               =       "username=".$varUserName."&password=".$varPWD."&sender=".$varSenderID."&sendto=91".$mobileNo."&message=".$msg;

            $this->postData($url,$data);
        } 

        private function sendforgotpasswordemail($email, $token) {
            $link = 'http://student.myfutton.com/auth/reset-password?token=' . $token;
            $data = array(
                'email' => $email,
                'link'  => $link
            );

            Mail::send('forgot-password', $data, function($message) use ($data) {
                $message->to($data['email'])->subject('Futton Password Reset Notification');
                $message->from($this->fromEmailAddress, $this->fromEmailName);
            });
        }

        
        private function postData($url,$data){
            $objURL = curl_init($url);
            curl_setopt($objURL, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($objURL,CURLOPT_POST,1);
            curl_setopt($objURL, CURLOPT_POSTFIELDS,$data);
             $retval = trim(curl_exec($objURL));
            curl_close($objURL);
            return $retval;
        }

             //The function uses CURL for posting data to server
                            
    }
?>
