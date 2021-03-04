<?php
    namespace App\Http\Controllers\Admin;

    use Carbon\Carbon;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Validator;

    use App\Http\Controllers\Controller;

    use App\AppSlider;

    class SliderController extends Controller {
        public function __construct() {

        }

        public function index(){

            $sliders = AppSlider::get();

            return response()->json($sliders);
        }


        public function create(Request $request) {
            $validator = Validator::make($request->all(), [
                'title' =>  'string',
                'image'  => 'mimetypes:image/jpeg,image/png,image/bmp'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
                    'message' => $validator->errors()
                ], 409);
            }


            if ($request->hasFile('image')) {

                $currentTs = Carbon::now();

                try {
                    
                        $md5Name = md5_file($request->file('image')->getRealPath());
                        $guessExtension = $request->file('image')->guessExtension();
                        $filename = $md5Name.'.'.$guessExtension;

                        $destinationPath = storage_path('app/public/uploads/sliders/');
                        $file = $request->file('image')->move($destinationPath,$filename);

                        $slider             = new AppSlider;
                        $slider->title      = $request->input('title');
                        $slider->image      = $filename;
                        $slider->save();

                    return response()->json([
                        'status'  => true,
                        'message' => 'file uploaded successfully',
                        'path'    => env('APP_URL').'/sliders/'.$filename
                    ], 201);
                } catch (\Exception $e) {
                    return response()->json([
                        'status'  => false,
                        'message' => 'File Upload failed'
                    ], 409);
                }
            }
            else{
                return response()->json([
                    'status'  => false,
                    'message' => 'file not uploaded'
                ], 409);
            }
        }


        public function destroy($id){
            $slider = AppSlider::find($id);

            if ($slider != null) {
                $slider = AppSlider::where('id', $id)->delete();

                return response()->json([
                    'status'  => true,
                    'message' => 'Deleted selected Slider'
                ], 200);
            }

            return response()->json([
                'status'  => false,
                'message' => 'No Slider exists with the given ID'
            ], 200);
        }

    }
?>
