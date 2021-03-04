<?php
    namespace App;

    use Illuminate\Database\Eloquent\Model;

    class CourseSubject extends Model {
        public $incrementing = false;
        public $timestamps   = false;

        protected $table    = 'course_subjects';
        protected $fillable = [ 'course_id', 'subject_id'];
        protected $hidden   = [ ];

        public function lessons(){
        	return $this->hasMany('App\CourseLesson','subject','subject_id');
        }
        public function subjects(){
            return $this->hasMany('App\Subject','id','subject_id');
        }
        public function liveclasses(){
            return $this->hasMany('App\CourseLiveClass', 'course','course_id');
        }
    }


?>