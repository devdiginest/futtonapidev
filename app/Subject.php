<?php
    namespace App;

    use Illuminate\Database\Eloquent\Model;

    class Subject extends Model {
        public $incrementing = false;

        protected $table     = 'subjects';
        protected $fillable  = [ 'id', 'name', 'status' ];
        protected $hidden    = [ 'created_at', 'updated_at', 'pivot' ];

        public function lessons() {
            return $this->hasMany('App\CourseLesson');
        }
    }
?>