<?php
namespace App\Model;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Common\Helper;
use App\Common\Auth;

/**
 * Class User
 *
 * @property integer        $id
 * @property string         $email
 * @property string         $full_name
 * @property string         $password
 * @property string         $password_reset_token
 * @property integer        $role_id
 * @property integer        $created_by
 * @property integer        $updated_by
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 * @property integer        $status
 * @property-read Role      $role
 *
 * @package App\Model
 */
final class User extends BaseModel
{
    use SoftDeletes;

    const STATUS_BLOCKED = 0;
    const STATUS_ACTIVE  = 1;
    const STATUS_WAIT    = 2;

    const ROLE_ADMIN     = 1;
    const ROLE_USER      = 2;

    protected $table = 'users';

    protected $fillable = [
        'full_name',
        'email',
        'role_id',
        'status'
    ];

    protected $hidden = [
        'password',
        'password_reset_token',
    ];

    public static $rules = [
        'create' => [
            'email'    => 'required|email',
            'role_id'  => 'required',
            'password' => 'required',
        ],
        'update' => [
            'email'   => 'required|email',
            'role_id' => 'required',
        ]
    ];

    public function role()
    {
        return $this->hasOne('App\Model\Role', 'id', 'role_id');
    }

    public function access_tokens()
    {
        return $this->hasMany('App\Model\AccessToken', 'user_id', 'id');
    }

    public function refresh_tokens()
    {
        return $this->hasMany('App\Model\RefreshToken', 'user_id', 'id');
    }

    public function scopeCurrentUser($query)
    {
        $user = Auth::getUser();

        if ($user) {
            if ($user->role_id == self::ROLE_ADMIN) {
                return $query;
            }

            $query->where('id', $user->id);
        } else {
            $query->where('id', 0);
        }

        return $query;
    }

    /**
     * @param $email
     *
     * @return bool
     */
    public static function exist($email)
    {
        return self::where('email', $email)->count() > 0;
    }

    /**
     * @param string $email
     *
     * @return User|null
     */
    public static function findUserByEmail($email)
    {
        return self::where('email', $email)->where('status', self::STATUS_ACTIVE)->first();
    }

    /**
     * @param string $resetToken
     *
     * @return User|null
     */
    public static function findByPasswordResetToken($resetToken)
    {
        if (!self::isPasswordResetTokenValid($resetToken)) {
            return null;
        }

        return self::where('password_reset_token', $resetToken)->where('status', self::STATUS_ACTIVE)->first();
    }

    /**
     * @param string $token
     *
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire    = 3600;
        return $timestamp + $expire >= time();
    }

    /**
     * @void
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Helper::generateRandomString() . '_' . time();
    }

    /**
     * @void
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        AccessToken::where('user_id', $this->id)->delete();
        RefreshToken::where('user_id', $this->id)->delete();

        $this->password = password_hash($password, PASSWORD_DEFAULT, ['cost' => 13]);
    }
}
