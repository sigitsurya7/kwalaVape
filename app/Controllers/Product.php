<?php

namespace App\Controllers;

use App\Models\ImagesModel;
use App\Models\ProductModel;
use App\Models\VariantModel;
use CodeIgniter\RESTful\ResourceController;

class Product extends ResourceController
{
    public function addProduct()
{
    $token = $this->request->getServer('HTTP_AUTHORIZATION');

    if ($token) {
        $token = str_replace('Bearer ', '', $token);

        $cache = \Config\Services::cache();
        $user = $cache->get('user_' . $token);

        if ($user) {
            $productModel = new ProductModel();
            $variantModel = new VariantModel();
            $imageModel = new ImagesModel();

            $variantInputs = $this->request->getPost('variant');
            $images = $this->request->getFileMultiple('images');

            $variantArray = [];
            if ($variantInputs) {
                $variantValues = explode(',', $variantInputs);
                $stockValues = explode(',', $this->request->getPost('stock'));

                foreach ($variantValues as $key => $value) {
                    $variantArray[] = [
                        'variant' => trim($value),
                        'stock_available' => trim($stockValues[$key])
                    ];
                }
            }

            $variantJson = json_encode($variantArray);

            $imagePaths = [];
            if ($images) {
                foreach ($images as $image) {
                    if ($image->isValid() && !$image->hasMoved()) {
                        $newName = $image->getRandomName();
                        $image->move(ROOTPATH . 'public/assets/uploads/product', $newName);
                        $imagePaths[] = $newName;
                    }
                }
            }

            $productData = [
                'product_name' => $this->request->getPost('product_name'), // Updated field name
                'description' => $this->request->getPost('description'),
                'price' => $this->request->getPost('price'),
                'category' => $this->request->getPost('category'),
                'created_by' => $user['name']
            ];

            $productModel->addProduct($productData);
            $productID = $productModel->getInsertID();

            foreach ($variantArray as $variant) {
                $variantModel->addVariant($productID, $variant['variant'], $variant['stock_available']); // Updated parameter name
            }

            foreach ($imagePaths as $imagePath) {
                $imageModel->addImage($productID, $imagePath);
            }

            return $this->respond(['message' => 'Success'], 200);
        }
    }

    return $this->respond('Unauthorized', 401);
}


    public function getProduct()
    {
        $token = $this->request->getServer('HTTP_AUTHORIZATION');

        if ($token) {
            $token = str_replace('Bearer ', '', $token);

            $cache = \Config\Services::cache();
            $user = $cache->get('user_' . $token);

            if ($user) {
                $model = new ProductModel();

                $data = $model->getProd();

                return $this->respond($data, 200);
            }
        }

        return $this->failUnauthorized('Unauthorized');
    }

    public function productFe()
    {
        $model = new ProductModel();

        $data = $model->getProd();

        return $this->respond($data, 200);
    }

    public function productById($productId)
    {
        $model = new ProductModel();

        $data = $model->getProductWithDetailsById($productId);

        return $this->respond($data, 200);
    }

}
