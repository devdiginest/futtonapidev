<?php
    namespace App;

    use Illuminate\Auth\Authenticatable;
    use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
    use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
    use Illuminate\Database\Eloquent\Model;
    use Laravel\Lumen\Auth\Authorizable;

    class Clazz extends Model implements AuthenticatableContract, AuthorizableContract {
        use Authenticatable, Authorizable;

        public $incrementing = false;

        protected $table     = 'classes';
        protected $fillable  = [ 'id', 'name', 'class_teacher', 'status', 'created_at', 'updated_at' ];
        protected $hidden    = [ ];
    }
?>