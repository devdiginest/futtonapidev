<?php
    namespace App;

    use Illuminate\Database\Eloquent\Model;

    use Illuminate\Support\Facades\DB;

    class StudentScore extends Model {
        public $incrementing = true;

        protected $table    = 'student_scores';
        protected $fillable = [ 'id', 'student_id', 'test_id', 'score'];
        protected $hidden   = [ 'created_at', 'updated_at', 'pivot' ];

        public function students(){
        	return $this->hasMany('App\User', 'id', 'student_id');
        }

        public function tests(){
            return $this->hasMany('App\TestAndQuiz', 'id', 'test_id');
        }

    }
?>