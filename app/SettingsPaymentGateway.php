<?php
    namespace App;

    use Illuminate\Database\Eloquent\Model;

    class SettingsPaymentGateway extends Model {
        public $incrementing = false;
        public $timestamps   = false;

        protected $table    = 'settings_smsg';
        protected $fillable = [ 'provider', 'mode', 'api_key', 'auth_token', 'salt'];
        protected $hidden   = [ 'updated_at' ];
    }
?>