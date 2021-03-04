<?php
    namespace App;

    use Illuminate\Database\Eloquent\Model;

    class CourseReview extends Model {
        public $incrementing = true;
        public $timestamps   = false;

        protected $table    = 'course_reviews';
        protected $fillable = [ 'id', 'student', 'course', 'rating', 'review', 'date' ];
        protected $hidden   = [ ];
    }
?>