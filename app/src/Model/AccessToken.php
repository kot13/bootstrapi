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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
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
     * @param array  $settings
     *
     * @return bool
     */
    public static function validateToken($token, array $settings)
    {
        try {
            $payload = JWT::decode($token, $settings['secretKey'], ['HS256']);
            return in_array($payload->aud, $settings['allowHosts']);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * @param User   $user
     * @param string $host
     * @param array  $settings
     *
     * @return string
     */
    public static function createToken(User $user, $host, array $settings)
    {
        $token = [
            'iss' => $settings['iss'],
            'aud' => $host,
            'iat' => time(),
            'exp' => time() + $settings['ttl'],
        ];

        $jwt = JWT::encode($token, $settings['secretKey']);

        $user->accessTokens()->create([
            'access_token' => md5($jwt),
            'created_at'   => date('Y-m-d H:i:s'),
        ]);

        return $jwt;
    }
}
