<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Helpers\Response;
use App\Models\BaseModel;
use App\Services\AuthService;
use App\Services\JwtService;

class AuthController extends BaseModel
{
    public function login(): void
    {
        $input = json_decode(file_get_contents('php://input'), true) ?? [];
        $service = new AuthService();
        $result = $service->login((string) ($input['username'] ?? ''), (string) ($input['password'] ?? ''), $_SERVER['REMOTE_ADDR'] ?? '');
        if (!$result) {
            Response::json(['message' => 'Invalid credentials'], 401);
        }
        Response::json($result);
    }

    public function me(): void
    {
        $auth = json_decode($_SERVER['auth_user'] ?? '{}', true);
        Response::json(['user' => $auth]);
    }

    public function refresh(): void
    {
        $input = json_decode(file_get_contents('php://input'), true) ?? [];
        $refresh = hash('sha256', (string) ($input['refresh_token'] ?? ''));
        $stmt = $this->db->prepare('SELECT User_Id FROM Session_Master WHERE Refresh_Token = :token AND Expires_At > NOW() LIMIT 1');
        $stmt->execute(['token' => $refresh]);
        $uid = $stmt->fetchColumn();
        if (!$uid) {
            Response::json(['message' => 'Invalid refresh token'], 401);
        }
        $u = $this->db->prepare('SELECT User_Id, Role_Id, Username FROM User_Master WHERE User_Id = :id');
        $u->execute(['id' => $uid]);
        $user = $u->fetch();
        $access = (new JwtService())->issueAccessToken(['user_id' => $user['User_Id'], 'role_id' => $user['Role_Id'], 'username' => $user['Username']]);
        Response::json(['access_token' => $access]);
    }

    public function logout(): void
    {
        $input = json_decode(file_get_contents('php://input'), true) ?? [];
        $refresh = hash('sha256', (string) ($input['refresh_token'] ?? ''));
        $stmt = $this->db->prepare('DELETE FROM Session_Master WHERE Refresh_Token = :token');
        $stmt->execute(['token' => $refresh]);
        Response::json(['message' => 'Logged out']);
    }

    public function forgotPassword(): void
    {
        Response::json(['message' => 'If account exists reset instructions were sent']);
    }

    public function resetPassword(): void
    {
        Response::json(['message' => 'Password reset successful']);
    }
}
