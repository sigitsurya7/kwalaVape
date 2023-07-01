<?php

namespace App\Models;

use CodeIgniter\Model;

class ImagesModel extends Model
{
    protected $table = 'tbl_images';
    protected $primaryKey = 'id';
    protected $allowedFields = ['product_id', 'image_path'];

    public function addImage($productID, $imagePath)
    {
        $data = [
            'product_id' => $productID,
            'image_path' => $imagePath
        ];
        
        return $this->insert($data);
    }

    public function getImagesByProductId($productId)
    {
        $builder = $this->db->table('tbl_images');
        $builder->select('id, image_path');
        $builder->where('product_id', $productId);
        $query = $builder->get();
        $images = $query->getResultArray();

        $mappedImages = [];
        foreach ($images as $image) {
            $mappedImages[$image['id']] = $image['image_path'];
        }

        return $mappedImages;
    }
}
