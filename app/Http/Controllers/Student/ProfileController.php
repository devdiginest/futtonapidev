<?php
    namespace App\Http\Controllers\Student;

    use Illuminate\Http\Request;
    use Tymon\JWTAuth\JWTAuth;

    use App\Http\Controllers\Controller;
    use Illuminate\Support\Facades\Validator;
    use App\User;

    class ProfileController extends Controller {
        protected $jwt;

        public function __construct(JWTAuth $jwt) {
            $this->jwt = $jwt;
        }

        public function show(Request $request) {
            $token  = $request->bearerToken();
            $user   = $this->jwt->User($token);
            $userId = $user->id;

            // $userId = "E8U9H7RJRCxQKJdluzV8sk9L";

            $userStudent = User::select('id','name','mobile_no','email','photo')->where('id', $userId)->first();
            return response()->json($userStudent);
        }

        public function update(Request $request) {
            // $token  = $request->bearerToken();
            // $user   = $this->jwt->User($token);
            // $userId = $user->id;

            

            $validator = Validator::make($request->all(), [
                'id'          => 'required|string|max:24',
                'name'        => 'required|regex:/^[\pL\s]+$/u|max:255',
                'photo'       => 'mimes:jpg,bmp,png'  
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
                    'message' => $validator->errors()
                ], 409);
            }

            $id = $request->input('id');

            try{
                $userStudent = User::find($id);
                $userStudent->name = $request->input('name');
                if ($request->hasFile('photo')) {

                    //$filename = $request->file('photo')->getClientOriginalName();

                    $md5Name = md5_file($request->file('photo')->getRealPath());
                    $guessExtension = $request->file('photo')->guessExtension();
                    $filename = $md5Name.'.'.$guessExtension;

                    $destinationPath = storage_path('app/public/uploads/profile/');
                    $file = $request->file('photo')->move($destinationPath,$filename);
         
                    $userStudent->photo          = $filename;
          
                }
                $userStudent->save();

                return response()->json([
                    'status'  => true,
                    'message' => 'Successfully updated Profile'
                ], 200);
            }
            catch (\Exception $e){
                return response()->json([
                    'status'  => false,
                    'message' => 'Profile updation failed'
                ], 409);
            }
        }
    }
?>