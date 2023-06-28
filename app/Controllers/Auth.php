<?php

namespace App\Controllers;

use App\Models\AuthModel;
use CodeIgniter\RESTful\ResourceController;

class Auth extends ResourceController
{
    public function signIn()
    {
        $model = new AuthModel;

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $user = $model->login($username, $password);

        if ($user) {
            // User authentication successful, save user data to cache
            $cache = \Config\Services::cache();
            $token = $user['token'];
            $cache->save('user_' . $token, $user, 3600); // Cache for 1 hour

            return $this->respond($user);
        } else {
            // User authentication failed
            return $this->failUnauthorized('Invalid username or password');
        }
    }

    public function logoutUser()
    {
        $model = new AuthModel();

        $token = $this->request->getServer('HTTP_AUTHORIZATION');

        $token = str_replace('Bearer ', '', $token);

        $cache = \Config\Services::cache();
        $user = $cache->get('user_' . $token);

        if (!$user) {
            return $this->fail('Invalid token', 401);
        }

        $userId = $user['id'];

        $loggedOut = $model->logout($userId);

        if ($loggedOut) {
            // Remove user data from cache
            $cache->delete('user_' . $token);

            return $this->respond(['message' => 'Logged out successfully'], 200);
        } else {
            return $this->fail('Failed to logout', 500);
        }
    }

}
