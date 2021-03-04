<?php
    namespace App;

    use Illuminate\Database\Eloquent\Model;

    class TeacherAppSlider extends Model {
        public $incrementing = true;
        public $timestamps = false;

        protected $table    = 'teacher_app_slider';
        protected $fillable = [ 'id', 'title', 'image', 'start_date'];
        protected $hidden   = [ 'created_at', 'updated_at'];

    }
?>