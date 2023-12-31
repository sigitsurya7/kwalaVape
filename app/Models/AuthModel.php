<?php

namespace App\Models;

use CodeIgniter\Model;

class AuthModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'username', 'password', 'role', 'photo'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function login($username, $password)
    {
        $user = $this->where('username', $username)->first();

        if ($user && password_verify($password, $user['password'])) {
            // Generate a Bearer token
            $token = $this->generateToken();

            // Store the token in the user's record
            $user['token'] = $token;
            $this->update($user['id'], $user);

            return $user;
        }

        return null;
    }

    
    private function generateToken()
    {
        // Generate a random token
        $token = bin2hex(random_bytes(32));
    
        return $token;
    }

    public function logout($userId)
    {
        $user = $this->find($userId);

        if ($user) {
            // Clear the bearer token
            $user['token'] = null;
            $this->update($user['id'], $user);

            return true;
        }

        return false;
    }
}
