<?php
    namespace App;

    use Illuminate\Database\Eloquent\Model;

    class CourseLiveClass extends Model {
        public $incrementing = true;

        protected $table    = 'course_live_classes';
        protected $fillable = [ 'id', 'course', 'subject', 'lesson', 'name', 'date', 'time', 'duration' ];
        protected $hidden   = [ 'created_at', 'updated_at' ];

        public function courses() {
            return $this->hasMany('App\Course', 'id', 'course');
        }
        public function subjects() {
            return $this->hasMany('App\Subject', 'id', 'subject');
        }
        public function lessons() {
            return $this->hasMany('App\CourseLesson', 'id', 'lesson');
        }
    }
?>