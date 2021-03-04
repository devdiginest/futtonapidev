<?php
    namespace App;

    use Illuminate\Database\Eloquent\Model;

    class CourseExam extends Model {
        public $incrementing = true;
        public $timestamps   = false;

        protected $table    = 'category_exams';
        protected $fillable = [ ];
        protected $hidden   = [ 'category_id', 'exams_id', 'pivot'];
    }
?>