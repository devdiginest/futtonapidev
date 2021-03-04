<?php
    namespace App;

    use Illuminate\Auth\Authenticatable;
    use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
    use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
    use Illuminate\Database\Eloquent\Model;
    use Laravel\Lumen\Auth\Authorizable;
    use Tymon\JWTAuth\Contracts\JWTSubject;

    class UserStudent extends Model implements JWTSubject, AuthenticatableContract, AuthorizableContract {
        use Authenticatable, Authorizable;

        public $incrementing = false;

        protected $table    = 'users_student';
        protected $fillable = [ 'id', 'name', 'mobile_no', 'email', 'status' ];
        protected $hidden   = [ 'password', 'otp', 'is_verified', 'token_resetpwd', 'created_at', 'updated_at', 'pivot' ];

        public function getJWTIdentifier() {
            return $this->getKey();
        }

        public function getJWTCustomClaims() {
            return [];
        }

        public function courses() {
            return $this->belongsToMany('App\Course', 'student_courses', 'student_id', 'course_id');
        }
    }
?>