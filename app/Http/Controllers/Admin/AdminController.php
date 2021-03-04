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

    class AdminController extends Controller {
        private $fromEmailName;
        private $fromEmailAddress;

        public function __construct() {
            $this->fromEmailName = env('MAIL_FROM_NAME');
            $this->fromEmailAddress = env('MAIL_FROM_ADDRESS');
        }

        public function index() {
            $admins = User::orderBy('created_at', 'desc')->get()->where('profile', '=', 'eZaa8CJEnQYal9XWaqoCpVSF');
            return response()->json($admins);

            
        }

        public function show($id) {
            $admin = User::find($id);
            return response()->json($admin);
        }

        public function create(Request $request) {
            $validator = Validator::make($request->all(), [
                'name'         => 'required|string|max:30',
                'mobile_no'    => 'required|digits:10|unique:users',
                'email'        => 'required|email|max:50|unique:users',
                'password'     => 'required|string|min:8|max:12',
                'joining_date' => 'date'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
                    'message' => $validator->errors()
                ], 409);
            }

            try {
                $adminId = Str::random(24);
                $name      = $request->input('name');
                $email     = $request->input('email');
                $password  = app('hash')->make($request->input('password'));
                $currentTs = Carbon::now();

                $userAdmin               = new User;
                $userAdmin->id           = $adminId;
                $userAdmin->profile      = 'eZaa8CJEnQYal9XWaqoCpVSF';
                $userAdmin->name         = $name;
                $userAdmin->mobile_no    = $request->input('mobile_no');
                $userAdmin->email        = $email;
                $userAdmin->password     = $password;
                $userAdmin->joining_date = $request->input('joining_date');
                //$userAdmin->subject      = $request->input('subject');
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
                    'message' => 'Admin registration failed'
                ], 409);
            }
        }

        public function update(Request $request) {
            $validator = Validator::make($request->all(), [
                'id'           => 'required|string|max:24',
                'name'         => 'required|string|max:30',
                'mobile_no'    => 'required|digits:10|exists:users',
                'email'        => 'required|email|max:50|exists:users',
                'joining_date' => 'date',
                'status'       => 'required|string|max:15'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
                    'message' => $validator->errors()
                ], 409);
            }

            try {
                $currentTs = Carbon::now();

                $userAdmin               = User::find($request->input('id'));
                $userAdmin->name         = $request->input('name');
                $userAdmin->mobile_no    = $request->input('mobile_no');
                $userAdmin->email        = $request->input('email');
                $userAdmin->joining_date = $request->input('joining_date');
                $userAdmin->status       = $request->input('status');
                $userAdmin->updated_at   = $currentTs;
                $userAdmin->save();

                // TODO: SEND EMAIL IN REQUIRED CONDITIONS

                return response()->json([
                    'status'  => true,
                    'message' => 'Admin updated successfully'
                ], 200);
            } catch (\Exception $e) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Unable to update Admin'
                ], 409);
            }
        }

        public function destroy($id) {
            // $validator = Validator::make($request->all(), [
            //     'teacher_id' => 'required|string|max:24|exists:users,id'
            // ]);

            // if ($validator->fails()) {
            //     return response()->json([
            //         'status'  => false,
            //         'message' => $validator->errors()
            //     ], 409);
            // }

            // TODO: Reassign to the Selected Teacher

            $userAdmin = User::find($id);

            if ($userAdmin != null) {
                //$userAdmin->delete();

                try {
                    $currentTs = Carbon::now();
                    $userAdmin->status          = "Inactive";
                    $userAdmin->updated_at      = $currentTs;
                    $userAdmin->save();

                    // TODO: SEND EMAIL IN REQUIRED CONDITIONS

                    return response()->json([
                        'status'  => true,
                        'message' => 'Deleted selected Admin'
                    ], 200);
                } catch (\Exception $e) {
                    return response()->json([
                        'status'  => false,
                        'message' => 'Unable to delete Admin'
                    ], 409);
                }

            }

            return response()->json([
                'status'  => false,
                'message' => 'No Admin exists with the given ID'
            ], 200);
        }

        /***********/
        /* PRIVATE */

        private function sendwelcomeemail($name, $email, $password) {
            $data = array(
                'name'     => $name,
                'email'    => $email,
                'password' => $password
            );

            Mail::send('welcome-admin', $data, function($message) use ($data) {
                $message->to($data['email'])->subject('Welcome to Futton');
                $message->from($this->fromEmailAddress, $this->fromEmailName);
            });
        }
    }
?>