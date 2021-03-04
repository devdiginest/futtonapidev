<?php
    namespace App;

    use Illuminate\Database\Eloquent\Model;

    class StudentWebSlider extends Model {
        public $incrementing = true;
        public $timestamps = false;

        protected $table    = 'student_web_slider';
        protected $fillable = [ 'id', 'title', 'image'];
        protected $hidden   = [ 'created_at', 'updated_at'];

    }
?>