<?php

namespace App\Models;

use CodeIgniter\Model;

class BannerModel extends Model
{
    // Setting table
    protected $table = 'tbl_banners';
    protected $primaryKey = 'id';
    protected $allowedFields = ['title', 'filename', 'created_by', 'updated_by'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Crud

    public function addBanner($data)
    {
        return $this->insert($data);
    }

    public function getBanner()
    {
        return $this->findAll();
    }

    public function getBanId($id)
    {
        return $this->where('id', $id)->first();
    }

    public function deleteBan($id)
    {
        return $this->delete($id);
    }
}
