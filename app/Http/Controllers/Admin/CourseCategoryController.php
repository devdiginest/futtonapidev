<?php
    namespace App\Http\Controllers\Admin;

    use Carbon\Carbon;
    use Illuminate\Http\Request;
    use Illuminate\Support\Str;
    use Illuminate\Support\Facades\Validator;
    use Illuminate\Support\Facades\Response;
    use Illuminate\Support\Facades\Storage;

    use App\Http\Controllers\Controller;
    use App\CourseCategory;

    class CourseCategoryController extends Controller {
        public function __construct() {

        }

        public function index() {
            $courseCategory = CourseCategory::select('id','name','display_order')->get();
            return response()->json($courseCategory);
        }

        public function show($id) {
            $courseCategory = CourseCategory::select('name','display_order')->where('id','=', $id)->get();
            return response()->json($courseCategory);
        }

        public function create(Request $request) {
            $validator = Validator::make($request->all(), [
                'name'              => 'required|string|max:75',
                'display_order'     => 'required|numeric|max:100'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
                    'message' => $validator->errors()
                ], 409);
            }

            try {
                $currentTs = Carbon::now();

                $courseCategory                     = new CourseCategory;
                $courseCategory->name               = $request->input('name');
                $courseCategory->display_order      = $request->input('display_order');
                $courseCategory->updated_at         = $currentTs;
                $courseCategory->save();

                return response()->json([
                    'status'    => true,
                    'message'   => 'Successfully created new Course Category',
                    'Category Name' => $request->input('name')
                ], 201);
            } catch (\Exception $e) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Course Category creation failed'
                ], 409);
            }
        }

        public function update(Request $request) {
            
            $validator = Validator::make($request->all(), [
                'id'                => 'required|numeric',
                'name'              => 'required|string|max:75',
                'display_order'     => 'required|numeric|max:100'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
                    'message' => $validator->errors()
                ], 409);
            }

            try {
                $currentTs = Carbon::now();

                $courseCategory                     = CourseCategory::find($request->input('id'));
                $courseCategory->name               = $request->input('name');
                $courseCategory->display_order      = $request->input('display_order');
                $courseCategory->updated_at         = $currentTs;
                $courseCategory->save();

                return response()->json([
                    'status'    => true,
                    'message'   => 'Successfully updated Course Category',
                    'Category Name' => $request->input('name')
                ], 201);
            } catch (\Exception $e) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Course Category updation failed'
                ], 409);
            }
        }


        public function destroy($id) {
            $courseCategory = CourseCategory::find($id);

            if ($courseCategory != null) {
                $courseCategory = CourseCategory::where('id', $id)->delete();

                return response()->json([
                    'status'  => true,
                    'message' => 'Deleted selected Course Category'
                ], 200);
            }

            return response()->json([
                'status'  => false,
                'message' => 'No Course Category exists with the given ID'
            ], 200);
        }
    }
?>