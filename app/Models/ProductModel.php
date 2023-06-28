<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    // Setting table
    protected $table = 'tbl_product';
    protected $primaryKey = 'id';
    protected $allowedFields = ['product_name', 'description', 'variant', 'price', 'stock', 'images', 'created_by', 'updated_by'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Crud

    public function addProduct($data)
    {
        return $this->insert($data);
    }

    public function getProd()
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
