<?php
    namespace App\Http\Controllers\Admin;

    use Carbon\Carbon;
    use Illuminate\Http\Request;
    use Illuminate\Support\Str;
    use Illuminate\Support\Facades\Hash;
    use Illuminate\Support\Facades\Mail;
    use Illuminate\Support\Facades\Validator;

    use App\Http\Controllers\Controller;

    class SupportController extends Controller {
        private $toEmailAddress;

        public function __construct() {
            $this->toEmailAddress = env('MAIL_TO_ADDRESS');
        }

        public function support(Request $request){
            $validator = Validator::make($request->all(), [
                'name'         => 'required|string|max:30',
                'mobile_no'    => 'required|digits:10',
                'email'        => 'required|email|max:50',
                'description'  => 'required|string|min:20|max:1000',
                'file'         => 'mimetypes:image/jpeg,image/png,image/bmp'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
                    'message' => $validator->errors()
                ], 409);
            }

            $name = $request->input('name');
            $mobile_no = $request->input('mobile_no');
            $email = $request->input('email');
            $description = $request->input('description');
            $file = $request->file('file');

            if($email != null){
                $this->sendemail($name, $mobile_no, $email, $description, $file);

                return response()->json([
                        'status'  => true,
                        'message' => 'Thanks for your email, our team will contact you soon'
                    ], 200);
            }
            else{
                return response()->json([
                        'status'  => false,
                        'message' => 'Email not sent'
                    ], 409);
            }

            
        }

        /***********/
        /* PRIVATE */

        private function sendemail($name, $mobile_no, $email, $description, $file) {
            $data = array(
                'name'     => $name,
                'email'    => $email,
                'mobile_no' => $mobile_no,
                'description'   => $description,
                'file'          => $file
            );

            Mail::send('support', $data, function($message) use ($data) {
                $message->to($this->toEmailAddress)->subject('Futton Customer Support');
                if($data['file'] != null){
                    $message->attach($data['file']->getRealPath(),
                    [
                        'as' => $data['file']->getClientOriginalName(),
                        'mime' => $data['file']->getClientMimeType(),
                    ]);
                }
                
                $message->from($data['email'], $data['name']);
            });
        }
    }
?>