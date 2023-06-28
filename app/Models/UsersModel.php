<?php

namespace App\Models;

use CodeIgniter\Model;

class UsersModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'username', 'password', 'role', 'photo'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function createNew($data)
    {
        return $this->insert($data);
    }

    public function getUserData()
    {
        return $this->findAll();
    }

    public function getuserId($id)
    {
        return $this->where('id', $id)->first();
    }

    public function updateUser($id, $data)
    {
        return $this->update($id, $data);
    }
}
