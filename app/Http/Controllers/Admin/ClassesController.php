<?php
    namespace App\Http\Controllers\Admin;

    use Carbon\Carbon;
    use Illuminate\Http\Request;
    use Illuminate\Support\Str;
    use Illuminate\Support\Facades\Validator;

    use App\Http\Controllers\Controller;
    use App\Clazz;
    use App\User;

    class ClassesController extends Controller {
        public function __construct() {

        }

        public function index() {
            $classes = Clazz::orderBy('created_at', 'asc')->get();

            foreach ($classes as $class) {
                $class->teacher = User::select('id', 'name')->where('id', $class->class_teacher)->first();
            }

            return response()->json($classes);
        }

        public function show($id) {
            $class = Clazz::find($id);
            return response()->json($class);
        }

        public function create(Request $request) {
            $validator = Validator::make($request->all(), [
                'name'          => 'required|string|max:30|unique:classes',
                'class_teacher' => 'required|string|max:24|exists:users,id'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
                    'message' => $validator->errors()
                ], 409);
            }

            try {
                $classId = Str::random(24);
                $currentTs = Carbon::now();

                $class                = new Clazz;
                $class->id            = $classId;
                $class->name          = $request->input('name');
                $class->class_teacher = $request->input('class_teacher');
                $class->status        = 'Active';
                $class->updated_at    = $currentTs;
                $class->save();

                return response()->json([
                    'status'   => true,
                    'message'  => 'Class created successfully',
                    'class_id' => $classId
                ], 201);
            } catch (\Exception $e) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Unable to create Class',
                    'reason'  => $e
                ], 409);
            }
        }

        public function update(Request $request) {
            $validator = Validator::make($request->all(), [
                'id'            => 'required|string|max:24',
                'name'          => 'required|string|max:30',
                'class_teacher' => 'required|string|max:24|exists:users,id',
                'status'        => 'required|string|max:15'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
                    'message' => $validator->errors()
                ], 409);
            }

            try {
                $currentTs = Carbon::now();

                $class                = Clazz::find($request->input('id'));
                $class->name          = $request->input('name');
                $class->class_teacher = $request->input('class_teacher');
                $class->status        = $request->input('status');
                $class->updated_at    = $currentTs;
                $class->save();

                return response()->json([
                    'status'  => true,
                    'message' => 'Class updated successfully'
                ], 200);
            } catch (\Exception $e) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Unable to update Class',
                    'reason'  => $e
                ], 409);
            }
        }

        public function destroy($id) {
            $class = Clazz::find($id);

            if ($class != null) {
                $class->delete();

                return response()->json([
                    'status'  => true,
                    'message' => 'Deleted selected Class'
                ], 200);
            }

            return response()->json([
                'status'  => false,
                'message' => 'No Class exists with the given ID'
            ], 200);
        }
    }
?>