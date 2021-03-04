<?php
    namespace App;

    use Illuminate\Database\Eloquent\Model;

    class SettingsSMTP extends Model {
        public $incrementing = false;
        public $timestamps   = false;

        protected $table    = 'settings_smtp';
        protected $fillable = [ 'protocol', 'port', 'host', 'username', 'password'];
        protected $hidden   = [ 'updated_at' ];
    }
?>