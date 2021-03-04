<?php
    namespace App;

    use Illuminate\Database\Eloquent\Model;

    class CourseComment extends Model {
        public $incrementing = true;
        public $timestamps = false;

        protected $table    = 'course_comments';
        protected $fillable = [ 'user_id', 'profile', 'course_id', 'comment'];
        protected $hidden   = [ 'created_at', 'updated_at', 'pivot' ];

        public function users(){
        	return $this->hasMany('App\User','profile');
        }
        
    }
?>