<?php
namespace App\Model;

/**
 * Class AccessToken
 *
 * @property integer        $id
 * @property string         $access_token
 * @property integer        $user_id
 * @property \Carbon\Carbon $created_at
 *
 * @package App\Model
 */
final class AccessToken extends BaseModel
{
    protected $table = 'access_tokens';

    protected $fillable = [
        'access_token',
        'user_id',
    ];

    public $timestamps = false;

    public function user(){
        return $this->belongsTo('App\Model\User');
    }

    /**
     * @param string $accessToken
     *
     * @return User|null
     */
    public static function getUserByAccessToken($accessToken)
    {
        $user        = null;
        $accessToken = self::where('access_token', md5($accessToken))->first();

        if ($accessToken) {
            $user = $accessToken->user;
        }

        return $user;
    }
}
