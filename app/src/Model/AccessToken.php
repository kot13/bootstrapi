<?php
namespace App\Model;

use Firebase\JWT\JWT;

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

    public function user()
    {
        return $this->belongsTo('App\Model\User');
    }

    /**
     * @param string $accessToken
     *
     * @return User|null
     */
    public static function getUserByToken($accessToken)
    {
        $user        = null;
        $accessToken = self::where('access_token', md5($accessToken))->first();

        if ($accessToken) {
            $user = $accessToken->user;
        }

        return $user;
    }

    /**
     * @param string $token
     * @param array  $whiteList
     *
     * @return bool
     */
    public static function validateToken($token, $whiteList = [])
    {
        try {
            $payload = JWT::decode($token, getenv('SECRET_KEY'), ['HS256']);
            return in_array($payload->aud, $whiteList);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * @param string $host
     * @param User   $user
     * @param int    $tokenExpire
     *
     * @return string
     */
    public static function createToken($host, User $user, $tokenExpire = 3600)
    {
        $secret_key = getenv('SECRET_KEY');
        $token      = [
            'iss' => getenv('AUTH_ISS'),
            'aud' => $host,
            'iat' => time(),
            'exp' => time() + $tokenExpire,
        ];

        $jwt = JWT::encode($token, $secret_key);

        $user->access_tokens()->create([
            'access_token' => md5($jwt),
            'created_at'   => date('Y-m-d H:i:s'),
        ]);

        return $jwt;
    }
}
