<?php
namespace App\Model;

/**
 * Class RefreshToken
 *
 * @property integer        $id
 * @property string         $refresh_token
 * @property integer        $user_id
 * @property \Carbon\Carbon $created_at
 *
 * @package App\Model
 */
final class RefreshToken extends BaseModel
{
    protected $table = 'refresh_tokens';

    protected $fillable = [
        'refresh_token',
        'user_id',
    ];

    public $timestamps = false;

    public function user(){
        return $this->belongsTo('App\Model\User');
    }

    /**
     * @param string $refreshToken
     *
     * @return User|null
     */
    public static function getUserByRefreshToken($refreshToken)
    {
        $user         = null;
        $refreshToken = self::where('refresh_token', $refreshToken)->first();

        if ($refreshToken) {
            $user = $refreshToken->user;
        }

        return $user;
    }
}
