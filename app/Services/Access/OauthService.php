<?php

namespace App\Access;

use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Access\ApiAuthServiceInterface;

class OauthService implements ApiAuthServiceInterface
{
    public function register(
        string $firstName,
        string $lastName,
        string $email,
        string $password
    )
    {
        return User::create([
            'firstName' => $firstName,
            'lastName' => $lastName,
            'email' => $email,
            'password' => Hash::make($password)
        ]);
    }

    public function login(string $email, string $password): ?string
    {
        $user = User::where('email', $email)->first();
        if (
            $user
            && Hash::check($password, $user->password)
        ){
            $client = DB::table('oauth_clients')
                ->where('password_client', true)
                ->first();
            $data = [
                'grant_type' => 'password',
                'client_id' => $client->id,
                'client_secret' => $client->secret,
                'username' => $email,
                'password' => $password,
            ];
            $request = Request::create('/oauth/token', 'POST', $data);
            $response = app()->handle($request)->getContent();
            $data = json_decode($response);
            return $data->access_token;
        }
        return null;
    }

    public function logout(User $user)
    {
        $token = $user->token();
        $refreshToken = DB::table('oauth_refresh_tokens')
            ->where('access_token_id', $token->id)
            ->update([
                'revoked' => true
            ]);
        $token->revoke();
    }
}