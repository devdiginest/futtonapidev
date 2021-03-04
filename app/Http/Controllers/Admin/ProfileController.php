<?php
    namespace App\Http\Controllers\Admin;

    use App\Http\Controllers\Controller;
    use App\Profile;

    class ProfileController extends Controller {
        public function __construct() {

        }

        public function show($id) {
            $profile = Profile::find($id);
            return response()->json($profile);
        }
    }
?>