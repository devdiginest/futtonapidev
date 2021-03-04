<?php
    namespace App;

    use Illuminate\Database\Eloquent\Model;

    class TestOrQuizQuestion extends Model {
        public $incrementing = false;
        public $timestamps   = false;

        protected $table    = 'testorquiz_questions';
        protected $fillable = [ 'testorquiz_id', 'question_id' ];
        protected $hidden   = [ ];
    }
?>