<?php

namespace App\Model;

use App\Common\Helper;

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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Model\User');
    }

    /**
     * @param string $refreshToken
     *
     * @return User|null
     */
    public static function getUserByToken($refreshToken)
    {
        $user         = null;
        $refreshToken = self::where('refresh_token', $refreshToken)->first();

        if ($refreshToken) {
            $user = $refreshToken->user;
        }

        return $user;
    }

    /**
     * @param User $user
     *
     * @return string
     */
    public static function createToken(User $user)
    {
        $refreshToken = md5(Helper::generateRandomString().'_'.time());

        $user->refreshTokens()->create([
            'refresh_token' => $refreshToken,
            'created_at'    => date('Y-m-d H:i:s'),
        ]);

        return $refreshToken;
    }
}
