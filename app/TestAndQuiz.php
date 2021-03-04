<?php
    namespace App;

    use Illuminate\Database\Eloquent\Model;

    use Illuminate\Support\Facades\DB;

    class TestAndQuiz extends Model {
        public $incrementing = false;

        protected $table    = 'tests_n_quizzes';
        protected $fillable = [ 'id', 'type', 'title', 'exam_type', 'qus_count', 'course', 'subject',
                                'lesson', 'validity','price','status', 'start_date', 'end_date' ];
        protected $hidden   = [ 'created_at', 'updated_at', 'pivot' ];

        public function questions() {
            return $this->belongsToMany('App\Question', 'testorquiz_questions', 'testorquiz_id', 'question_id');
        }
        public function mcqoptions() {
            return $this->belongTo('App\MCQOption','question');
        }
        public function courses() {
           return $this->hasMany('App\Course', 'id', 'course');
        }
        public function subjects() {
           return $this->hasMany('App\Subject', 'id', 'subject');
        }
    }
?>