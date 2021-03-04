<?php
    namespace App;

    use Illuminate\Database\Eloquent\Model;

    class CourseCatRelation extends Model {
        public $incrementing = true;
        public $timestamps = false;

        protected $table    = 'course_cat_relation';
        protected $fillable = [ 'id', 'category_id', 'course_id' ];
        protected $hidden   = [ ];
    }
?>