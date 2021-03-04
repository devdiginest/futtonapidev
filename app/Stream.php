<?php
    namespace App;

    use Illuminate\Database\Eloquent\Model;

    class Stream extends Model {
        public $incrementing = true;

        protected $table     = 'streams';
        protected $fillable  = [ 'id', 'name', 'display_order' ];
        protected $hidden    = [ 'created_at', 'updated_at', 'pivot' ];

        public function exams(){
        	return $this->belongsToMany('App\Exam', 'exams_streams', 'streams_id', 'exams_id' );
        }
    }
?>