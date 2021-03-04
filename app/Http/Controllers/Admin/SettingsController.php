<?php
    namespace App\Http\Controllers\Admin;

    use Carbon\Carbon;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Validator;

    use App\Http\Controllers\Controller;
    use App\SettingsSMTP;
    use App\SettingsSMSGateway;
    use App\SettingsPaymentGateway;

    class SettingsController extends Controller {
        public function __construct() {

        }

        public function index() {
            return response()->json([
                'smtp' => SettingsSMTP::first(),
                'smsg' => SettingsSMSGateway::first(),
                'payg' => SettingsPaymentGateway::first()
            ]);
        }

        public function updatesmtp(Request $request) {
            $validator = Validator::make($request->all(), [
                'protocol' => 'required|string|max:5',
                'port'     => 'required|string|max:5',
                'host'     => 'required|string|max:50',
                'username' => 'required|string|max:50',
                'password' => 'required|string|max:50'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
                    'message' => $validator->errors()
                ], 409);
            }

            try {
                $currentTs = Carbon::now();

                SettingsSMTP::query()->delete();

                $settingsSMTP             = new SettingsSMTP;
                $settingsSMTP->protocol   = $request->input('protocol');
                $settingsSMTP->port       = $request->input('port');
                $settingsSMTP->host       = $request->input('host');
                $settingsSMTP->username   = $request->input('username');
                $settingsSMTP->password   = $request->input('password');
                $settingsSMTP->updated_at = $currentTs;
                $settingsSMTP->save();

                return response()->json([
                    'status'  => true,
                    'message' => 'SMTP settings updated successfully'
                ], 200);
            } catch (\Exception $e) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Unable to update SMTP settings'
                ], 409);
            }
        }

        public function updatesmsgateway(Request $request) {
            $validator = Validator::make($request->all(), [
                'provider'   => 'required|string|max:15',
                'mode'       => 'required|string|max:10',
                'api_key'    => 'required|string|max:64',
                'auth_token' => 'required|string|max:64',
                'salt'       => 'required|string|max:64'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
                    'message' => $validator->errors()
                ], 409);
            }

            try {
                $currentTs = Carbon::now();

                SettingsSMSGateway::query()->delete();

                $settingsSMSG             = new SettingsSMSGateway;
                $settingsSMSG->provider   = $request->input('provider');
                $settingsSMSG->mode       = $request->input('mode');
                $settingsSMSG->api_key    = $request->input('api_key');
                $settingsSMSG->auth_token = $request->input('auth_token');
                $settingsSMSG->salt       = $request->input('salt');
                $settingsSMSG->updated_at = $currentTs;
                $settingsSMSG->save();

                return response()->json([
                    'status'  => true,
                    'message' => 'SMS Gateway settings updated successfully'
                ], 200);
            } catch (\Exception $e) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Unable to update SMS Gateway settings'
                ], 409);
            }
        }

        public function updatepaymentgateway(Request $request) {
            $validator = Validator::make($request->all(), [
                'provider'   => 'required|string|max:15',
                'mode'       => 'required|string|max:10',
                'api_key'    => 'required|string|max:64',
                'auth_token' => 'required|string|max:64',
                'salt'       => 'required|string|max:64'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
                    'message' => $validator->errors()
                ], 409);
            }

            try {
                $currentTs = Carbon::now();

                SettingsPaymentGateway::query()->delete();

                $settingsPayG             = new SettingsPaymentGateway;
                $settingsPayG->provider   = $request->input('provider');
                $settingsPayG->mode       = $request->input('mode');
                $settingsPayG->api_key    = $request->input('api_key');
                $settingsPayG->auth_token = $request->input('auth_token');
                $settingsPayG->salt       = $request->input('salt');
                $settingsPayG->updated_at = $currentTs;
                $settingsPayG->save();

                return response()->json([
                    'status'  => true,
                    'message' => 'Payment Gateway settings updated successfully'
                ], 200);
            } catch (\Exception $e) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Unable to update Payment Gateway settings'
                ], 409);
            }
        }
    }
?>