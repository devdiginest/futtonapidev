<?php
    namespace App;

    use Illuminate\Database\Eloquent\Model;

    class Notification extends Model {
        public $incrementing = false;
        public $timestamps   = false;

        protected $table    = 'notifications';
        protected $fillable = [ 'id', 'user', 'title', 'msg', 'received_date' ];
        protected $hidden   = [ ];
    }
?>