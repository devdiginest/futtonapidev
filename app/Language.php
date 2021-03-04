<?php
    namespace App;

    use Illuminate\Database\Eloquent\Model;

    class Language extends Model {
        public $incrementing = true;

        protected $table     = 'languages';
        protected $fillable  = [ 'id', 'name' ];
        protected $hidden    = [ 'display_order', 'created_at', 'updated_at' ];
    }
?>