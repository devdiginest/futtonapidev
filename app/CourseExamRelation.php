<?php
    namespace App;

    use Illuminate\Database\Eloquent\Model;

    class CourseExamRelation extends Model {
        public $incrementing = true;
        public $timestamps = false;

        protected $table    = 'course_exam_relation';
        protected $fillable = [ 'id', 'exams_id', 'course_id' ];
        protected $hidden   = [ ];
    }
?>