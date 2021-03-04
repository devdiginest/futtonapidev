<?php
    namespace App;

    use Illuminate\Database\Eloquent\Model;

    class AppSlider extends Model {
        public $incrementing = true;
        public $timestamps = false;

        protected $table    = 'app_slider';
        protected $fillable = [ 'id', 'title', 'image'];
        protected $hidden   = [ 'created_at', 'updated_at'];

    }
?>