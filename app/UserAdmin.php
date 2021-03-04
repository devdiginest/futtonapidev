<?php
    namespace App;

    use Illuminate\Auth\Authenticatable;
    use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
    use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
    use Illuminate\Database\Eloquent\Model;
    use Laravel\Lumen\Auth\Authorizable;
    use Tymon\JWTAuth\Contracts\JWTSubject;

    class UserAdmin extends Model implements JWTSubject, AuthenticatableContract, AuthorizableContract {
        use Authenticatable, Authorizable;

        public $incrementing = false;

        protected $table    = 'users_admin';
        protected $fillable = [ 'id', 'profile', 'name', 'mobile_no', 'email', 'joining_date', 'subject', 'status' ];
        protected $hidden   = [ 'password', 'token_resetpwd', 'created_at', 'updated_at' ];

        /**
         * Get the identifier that will be stored in the subject claim of the JWT.
         *
         * @return mixed
         */
        public function getJWTIdentifier() {
            return $this->getKey();
        }

        /**
         * Return a key value array, containing any custom claims to be added to the JWT.
         *
         * @return array
         */
        public function getJWTCustomClaims() {
            return [];
        }
    }
?>