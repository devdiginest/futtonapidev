<?php
    namespace App;

    use Illuminate\Database\Eloquent\Model;

    class TeacherSubject extends Model {
        public $incrementing = false;
        public $timestamps   = false;

        protected $table    = 'teacher_subjects';
        protected $fillable = [ 'teacher_id', 'subject_id' ];
        protected $hidden   = [ ];

        public function teachers() {
            return $this->belongsToMany('App\TeacherSubject', 'user', 'teacher_id', 'id');
        }
    }
?>