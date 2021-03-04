<?php
    namespace App;

    use Illuminate\Database\Eloquent\Model;

    class CourseLesson extends Model {
        public $incrementing = true;

        protected $table    = 'course_lessons';
        protected $fillable = [ 'id', 'course', 'subject', 'name', 'description', 'resource_url', 'resource_type', 'resource_provider', 'file', 'status' ];
        protected $hidden   = [ 'created_at', 'updated_at' ];

        public function courses()
	    {
	        return $this->hasMany('App\Course','id','course');
	    }

        public function subjects(){
            return $this->hasMany('App\Subject','id','subject');
        }
    }
?>
