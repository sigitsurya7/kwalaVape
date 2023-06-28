<?php

namespace App\Models;

use CodeIgniter\Model;

class KategoriModel extends Model
{
    // Setting table
    protected $table = 'tbl_kategori';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'created_by', 'updated_by'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Crud
    public function addKategori($data)
    {
        return $this->insert($data);
    }

    public function allK()
    {
        return $this->findAll();
    }

    public function idK($id)
    {
        return $this->where('id', $id)->first();
    }

    public function updateK($id, $data)
    {
        return $this->update($id, $data);
    }

    public function deleteK($id)
    {
        return $this->delete($id);
    }
}
