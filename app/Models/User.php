<?php

    namespace App\Models;

    use App\helpers\CustomValidator;
    use App\Models\inventory\Contact;
    use App\Models\inventory\Expense;
    use App\Models\inventory\Product;
    use App\Models\inventory\Sale;
    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Relations\HasMany;
    use Illuminate\Foundation\Auth\User as Authenticatable;
    use Illuminate\Http\Request;
    use Illuminate\Notifications\Notifiable;
    use Laravel\Sanctum\HasApiTokens;

    class User extends Authenticatable
    {
        use HasApiTokens , HasFactory , Notifiable;

        /**
         * The attributes that are mass assignable.
         *
         * @var array<int, string>
         */
        protected $fillable = [
            'name' ,
            'email' ,
            'password' ,
        ];
//        protected $table    = 'inv_users';
        protected $table = 'users';

        /**
         * The attributes that should be hidden for serialization.
         *
         * @var array<int, string>
         */
        protected $hidden = [
            'password' ,
            'remember_token' , 'created_at' , 'updated_at'
        ];

        /**
         * The attributes that should be cast.
         *
         * @var array<string, string>
         */
        protected $casts = [
            'email_verified_at' => 'datetime' ,
        ];

        public static function UserExists ( int $user_id ) : bool
        {
            return User ::where( 'id' , $user_id ) -> exists();

        }

        public static function validateUserId ( Request $request ) : ?string
        {
            $validator = CustomValidator ::validateUserId( $request );
            $errors    = null;
            if ( $validator -> stopOnFirstFailure() -> fails() ) {
                $errors = $validator -> messages() -> first();
            }
            return $errors;
        }

        public function expenses () : HasMany
        {
            return $this -> hasMany( Expense::class , 'user_id' , 'id' );
        }

        public function sales () : HasMany
        {
            return $this -> hasMany( Sale::class , 'user_id' , 'id' );
        }

        public function products () : HasMany
        {
            return $this -> hasMany( Product::class , 'user_id' , 'id' );
        }

        public function contacts () : HasMany
        {
            return $this -> hasMany( Contact::class , 'user_id' , 'id' );
        }
    }
