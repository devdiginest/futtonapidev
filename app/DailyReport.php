<?php
    namespace App;

    use Illuminate\Database\Eloquent\Model;

    class DailyReport extends Model {
        public $incrementing = true;

        protected $table    = 'daily_report';
        protected $fillable = [ 'id', 'teacher_id', 'date', 'workinghours', 'subject_id'];
        protected $hidden   = [ 'status', 'created_at', 'updated_at' ];

        public function teachers() {
            return $this->hasMany('App\User', 'id', 'teacher_id');
        }
        public function subjects() {
            return $this->hasMany('App\Subject', 'id', 'subject_id');
        }
    }
?>