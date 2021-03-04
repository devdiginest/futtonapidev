<?php
    namespace App;

    use Illuminate\Database\Eloquent\Model;

    class DailyQuizQuestion extends Model {
        public $incrementing = false;
        public $timestamps   = false;

        protected $table    = 'dailyquiz_questions';
        protected $fillable = [ 'dailyquiz_id', 'question_id' ];
        protected $hidden   = [ ];
    }
?>