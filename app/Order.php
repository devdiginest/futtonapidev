<?php
    namespace App;

    use Illuminate\Database\Eloquent\Model;

    class Order extends Model {
        public $incrementing = true;

        protected $table    = 'orders';
        protected $fillable = [ 'id', 'student_id', 'course_id', 'amount', 
            'receipt', 'order_id', 'status', 'payment_id' ];
        protected $hidden   = [ 'signature', 'created_at', 'updated_at' ];

        public function courses() {
	        return $this->hasMany('App\Course', 'id', 'course_id');
	    }
	    public function students() {
	        return $this->hasMany('App\User', 'id', 'student_id');
	    }
	    
    }

?>