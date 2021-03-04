<?php
    namespace App\Http\Controllers\Admin;

    use Carbon\Carbon;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Validator;

    use App\Http\Controllers\Controller;

    class UploadController extends Controller {
        public function __construct() {

        }

        public function upload(Request $request) {
            $validator = Validator::make($request->all(), [
                'file'  => 'mimetypes:application/pdf,video/x-msvideo,video/mpeg,video/quicktime,video/mp4,image/jpeg,image/png,image/bmp'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
                    'message' => $validator->errors()
                ], 409);
            }


            if ($request->hasFile('file')) {

                $md5Name = md5_file($request->file('file')->getRealPath());
                    $guessExtension = $request->file('file')->guessExtension();
                    $filename = $md5Name.'.'.$guessExtension;

                    $destinationPath = storage_path('app/public/uploads/messages/');
                    $file = $request->file('file')->move($destinationPath,$filename);
                return response()->json([
                    'status'  => true,
                    'message' => 'file uploaded successfully',
                    'path'    => 'https://api.myfutton.com/messages/'.$filename
                ], 201);
            }
            else{
                return response()->json([
                    'status'  => false,
                    'message' => 'file not uploaded'
                ], 409);
            }
        }

    }
?>
