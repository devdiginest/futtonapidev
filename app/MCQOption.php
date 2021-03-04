<?php
    namespace App;

    use Illuminate\Database\Eloquent\Model;

    class MCQOption extends Model {
        public $incrementing = true;

        protected $table    = 'mcq_options';
        protected $fillable = [ 'id', 'question', 'option' ];
        protected $hidden   = [ 'created_at', 'updated_at' ];
    }
?>