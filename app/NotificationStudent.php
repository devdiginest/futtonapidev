<?php
    namespace App;

    use Illuminate\Database\Eloquent\Model;

    class NotificationStudent extends Model {
        public $incrementing = false;
        public $timestamps   = false;

        protected $table    = 'notifications_student';
        protected $fillable = [ 'id', 'user', 'title', 'msg', 'received_date' ];
        protected $hidden   = [ ];
    }
?>