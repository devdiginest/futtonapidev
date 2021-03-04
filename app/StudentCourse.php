<?php
    namespace App;

    use Illuminate\Database\Eloquent\Model;

    class StudentCourse extends Model {
        public $incrementing = false;
        public $timestamps   = false;

        protected $table    = 'student_courses';
        protected $fillable = [ 'student_id', 'course_id', 'course_progress' ];
        protected $hidden   = [ ];

        public function students(){
        	$this->hasMany('App\User','student_id');
        }
    }
?>