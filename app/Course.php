<?php
    namespace App;

    use Illuminate\Database\Eloquent\Model;

    class Course extends Model {
        public $incrementing = false;
        public $timestamps = false;

        protected $table    = 'courses';
        protected $fillable = [ 'id', 'name', 'validity', 'start_date', 'end_date', 'price',
                                'course_provider', 'short_desc', 'long_desc', 'level', 'overview_provider',
                                'overview_url', 'thumbnail', 'content_provider', 'status' ];
        protected $hidden   = [ 'created_at', 'updated_at', 'pivot' ];

        public function subjects() {
            return $this->belongsToMany('App\Subject', 'course_subjects', 'course_id', 'subject_id');
        }

        public function categories(){
            return $this->belongsToMany('App\CourseCategory', 'course_cat_relation', 'course_id', 'category_id' );
        }

        public function exams(){
            return $this->belongsToMany('App\Exam', 'course_exam_relation', 'course_id', 'exams_id' );
        }

        public function streams(){
            return $this->belongsToMany('App\Stream', 'course_stream_relation', 'course_id', 'streams_id' );
        }

        public function lessons(){
            return $this->hasMany('App\CourseLesson', 'course');
        }
        public function reviews(){
            return $this->hasMany('App\CourseReview', 'course');
        }
        public function liveclasses(){
            return $this->hasMany('App\CourseLiveClass', 'course','id');
        }
    }
?>