<?php
    namespace App;

    use Illuminate\Database\Eloquent\Model;

    class CourseStreamRelation extends Model {
        public $incrementing = true;
        public $timestamps = false;
        
        protected $table    = 'course_stream_relation';
        protected $fillable = [ 'id', 'streams_id', 'course_id' ];
        protected $hidden   = [ ];
    }
?>