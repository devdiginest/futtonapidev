<?php
    namespace App;

    use Illuminate\Database\Eloquent\Model;

    class Question extends Model {
        public $incrementing = true;

        protected $table    = 'questions';
        protected $fillable = [ 'id', 'question', 'type', 'subject', 'lesson', 'correct_opt', 'answer', 'ans_desc',
                                'ans_range_start', 'ans_range_end', 'difficulty', 'file' ];
        protected $hidden   = [ 'created_at', 'updated_at', 'pivot' ];
    
        public function options(){
	    	return $this->hasMany('App\MCQOption','question');
	    }

    }
?>