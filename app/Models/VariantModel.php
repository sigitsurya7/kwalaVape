<?php

namespace App\Models;

use CodeIgniter\Model;

class VariantModel extends Model
{
    protected $table = 'tbl_stock';
    protected $primaryKey = 'id';
    protected $allowedFields = ['product_id', 'variant', 'stock_available'];

    public function addVariant($productId, $variant, $stockAvailable)
    {
        $data = [
            'product_id' => $productId,
            'variant' => $variant,
            'stock_available' => $stockAvailable
        ];

        return $this->insert($data);
    }

    public function getStockByProductId($productId)
    {
        $builder = $this->db->table($this->table);;
        $builder->select('variant, stock_available');
        $builder->where('product_id', $productId);
        $query = $builder->get();
        $variants = $query->getResultArray();

        $mappedStock = [];
        foreach ($variants as $variant) {
            $mappedStock[$variant['variant']] = $variant['stock_available'];
        }

        return $mappedStock;
    }

}
