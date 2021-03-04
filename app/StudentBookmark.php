<?php
    namespace App;

    use Illuminate\Database\Eloquent\Model;

    class StudentBookmark extends Model {
        public $incrementing = false;
        public $timestamps   = false;

        protected $table    = 'student_bookmark';
        protected $fillable = [ 'student_id', 'question_id' ];
        protected $hidden   = [ ];

        //  public function questions() {
        //     return $this->belongsToMany('App\Question', 'student_bookmark', 'student_id', 'question_id');
        // }
    }
?>