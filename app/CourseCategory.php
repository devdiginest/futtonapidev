<?php
    namespace App;

    use Illuminate\Database\Eloquent\Model;

    class CourseCategory extends Model {
        public $incrementing = true;

        protected $table     = 'course_categories';
        protected $fillable  = [ 'id', 'name','display_order' ];
        protected $hidden    = [ 'created_at', 'updated_at', 'pivot' ];
    }
?>