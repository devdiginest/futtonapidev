<?php
    namespace App;

    use Illuminate\Database\Eloquent\Model;

    class Preference extends Model {
        public $incrementing = true;

        protected $table     = 'preferences';
        protected $fillable  = [ 'id', 'student_id', 'exam1_id', 'exam2_id', 'language_id', 'stream_id' ];
        protected $hidden    = [ 'created_at', 'updated_at' ];
    }
?>