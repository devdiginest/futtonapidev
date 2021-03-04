<?php
    namespace App;

    use Illuminate\Database\Eloquent\Model;

    class Exam extends Model {
        public $incrementing = true;

        protected $table     = 'exams';
        protected $fillable  = [ 'id', 'name','display_order' ];
        protected $hidden    = [ 'created_at', 'updated_at', 'pivot' ];

        public function categories(){
        	return $this->belongsToMany('App\CourseCategory', 'category_exams', 'exams_id', 'category_id' );
        }
    }
?>