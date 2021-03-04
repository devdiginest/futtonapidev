<?php
    namespace App;

    use Illuminate\Database\Eloquent\Model;

    class ExamStream extends Model {
        public $incrementing = true;
        public $timestamps   = false;

        protected $table    = 'exams_streams';
        protected $fillable = [ ];
        protected $hidden   = [ 'exams_id', 'streams_id', 'pivot'];
    }
?>