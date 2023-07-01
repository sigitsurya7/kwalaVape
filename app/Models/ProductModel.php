<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table = 'tbl_product';
    protected $primaryKey = 'id';
    protected $allowedFields = ['product_name', 'category', 'description', 'variant', 'price', 'stock', 'images', 'created_by', 'updated_by'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function addProduct($data)
    {
        $this->insert($data);
        return $this->insertID();
    }


    public function getProd()
    {
        $builder = $this->db->table($this->table);
        $builder->select('tbl_product.id, tbl_product.product_name, tbl_product.category, tbl_product.price, tbl_product.description, tbl_product.created_by, tbl_product.updated_by, tbl_product.created_at, tbl_product.updated_at');
        $builder->groupBy('tbl_product.id');
        $query = $builder->get();
        $products = $query->getResultArray();

        $imageModel = new ImagesModel();
        $variantModel = new VariantModel();

        foreach ($products as &$product) {
            $product['images'] = $imageModel->getImagesByProductId($product['id']);
            $product['images'] = array_combine(range(1, count($product['images'])), $product['images']);
            $product['stock'] = $variantModel->getStockByProductId($product['id']);
        }

        return $products;
    }

    public function getProductById($id)
    {
        return $this->where('id', $id)->first();
    }

    public function getProductWithDetailsById($productId)
    {
        $builder = $this->db->table($this->table);
        $builder->select('tbl_product.id, tbl_product.product_name, tbl_product.price, tbl_product.description, tbl_product.created_by, tbl_product.updated_by, tbl_product.created_at, tbl_product.updated_at');
        $builder->where('tbl_product.id', $productId);
        $query = $builder->get();
        $product = $query->getRowArray();

        if ($product) {
            $imageModel = new ImagesModel();
            $variantModel = new VariantModel();

            $product['images'] = $imageModel->getImagesByProductId($productId);
            $product['images'] = array_combine(range(1, count($product['images'])), $product['images']);
            $product['stock'] = $variantModel->getStockByProductId($productId);

            return [$product];
        }

        return [];
    }

    public function deleteProduct($id)
    {
        return $this->delete($id);
    }
}
