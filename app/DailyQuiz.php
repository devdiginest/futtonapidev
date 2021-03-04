<?php
    namespace App;

    use Illuminate\Database\Eloquent\Model;

    class DailyQuiz extends Model {
        
        public $incrementing = true;

        protected $table    = 'daily_quiz';
        protected $fillable = [ 'id', 'qutitle', 'qucount', 'examtype', 'validity','status'];
        protected $hidden   = [ 'expiry_date', 'created_at', 'updated_at', 'pivot' ];

        public function questions() {
            return $this->belongsToMany('App\Question', 'dailyquiz_questions', 'dailyquiz_id', 'question_id');
        }
    }
?>