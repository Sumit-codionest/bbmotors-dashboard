<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\BaseModel;

class AuthService extends BaseModel
{
    public function login(string $username, string $password, string $ip): ?array
    {
        $stmt = $this->db->prepare('SELECT User_Id, Username, Password, Role_Id, Status, Is_Active FROM User_Master WHERE Username = :username AND Is_Deleted = 0 LIMIT 1');
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch();

        if (!$user || (int) $user['Is_Active'] !== 1 || !password_verify($password, $user['Password'])) {
            $this->logLoginAttempt($username, false, $ip);
            return null;
        }

        $jwt = new JwtService();
        $access = $jwt->issueAccessToken([
            'user_id' => $user['User_Id'],
            'role_id' => $user['Role_Id'],
            'username' => $user['Username'],
        ]);
        $refresh = bin2hex(random_bytes(32));

        $s = $this->db->prepare('INSERT INTO Session_Master (User_Id, Refresh_Token, Expires_At, Created_At, Updated_At) VALUES (:uid,:token,DATE_ADD(NOW(), INTERVAL 14 DAY),NOW(),NOW())');
        $s->execute(['uid' => $user['User_Id'], 'token' => hash('sha256', $refresh)]);
        $this->logLoginAttempt($username, true, $ip);

        return ['access_token' => $access, 'refresh_token' => $refresh, 'user' => $user];
    }

    private function logLoginAttempt(string $username, bool $ok, string $ip): void
    {
        $stmt = $this->db->prepare('INSERT INTO Login_History (Username, Ip_Address, Is_Success, Created_At) VALUES (:u,:ip,:s,NOW())');
        $stmt->execute(['u' => $username, 'ip' => $ip, 's' => $ok ? 1 : 0]);
    }
}
