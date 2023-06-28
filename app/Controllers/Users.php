<?php

namespace App\Controllers;

use App\Models\UsersModel;
use CodeIgniter\RESTful\ResourceController;

class Users extends ResourceController
{
    public function createUser()
    {
        $model = new UsersModel();

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        if (empty($username) || empty($password)) {
            return $this->respond('Username and password must be filled', 500);
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'username' => $username,
            'password' => $hashedPassword,
            'role' => $this->request->getPost('role'),
            'photo' => $this->request->getPost('photo')
        ];

        $model->createNew($data);

        if ($model->affectedRows() > 0) {
            return $this->respond(['message' => 'Success'], 200);
        } else {
            return $this->fail('Error! Failed to update post.', 500);
        }
    }

    public function getUser()
    {
        $token = $this->request->getServer('HTTP_AUTHORIZATION');

        if($token)
        {
            $token = str_replace('Bearer ', '', $token);

            $cache = \Config\Services::cache();
            $user = $cache->get('user_' . $token);

            if($user)
            {
                $model = new UsersModel();

                $data = $model->getUserData();

                return $this->respond($data, 200);

            }
        }

        return $this->respond('Unauthorized', 401);
    }

    public function getUserId($id)
    {
        $token = $this->request->getServer('HTTP_AUTHORIZATION');

        if($token)
        {
            $token = str_replace('Bearer ', '', $token);

            $cache = \Config\Services::cache();
            $user = $cache->get('user_' . $token);

            if($user)
            {
                $model = new UsersModel();

                $data = $model->getuserId($id);

                return $this->respond($data, 200);
            }
        }

        return $this->respond('Unauthorized', 401);
    }

    public function updateUsers($id = null)
    {
        $token = $this->request->getServer('HTTP_AUTHORIZATION');

        if($token)
        {
            $token = str_replace('Bearer ', '', $token);

            $cache = \Config\Services::cache();
            $user = $cache->get('user_' . $token);

            if($user)
            {
                $model = new UsersModel();

                $data = [
                    'name' => $this->request->getVar('name'),
                    'username' => $this->request->getVar('username'),
                    'password' => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT),
                    'role' => $this->request->getVar('role'),
                    'photo' => $this->request->getVar('photo')
                ];

                $data = array_filter($data, function ($value) {
                    return $value !== null;
                });

                if (empty($data)) {
                    return $this->fail('No data provided for update', 400);
                }

                $model->updateUser($id, $data);

                if ($model->affectedRows() > 0) {
                    return $this->respond(['message' => 'Data updated successfully'], 200);
                } else {
                    return $this->fail('Failed to update data', 500);
                }
            }
        }

        return $this->respond('Unauthorized', 401);
        
    }



}
