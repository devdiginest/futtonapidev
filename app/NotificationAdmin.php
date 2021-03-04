<?php
    namespace App;

    use Illuminate\Database\Eloquent\Model;

    class NotificationAdmin extends Model {
        public $incrementing = false;
        public $timestamps   = false;

        protected $table    = 'notifications_admin';
        protected $fillable = [ 'id', 'user', 'title', 'msg', 'received_date' ];
        protected $hidden   = [ ];
    }
?>