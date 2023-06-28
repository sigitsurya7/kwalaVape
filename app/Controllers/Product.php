<?php

namespace App\Controllers;

use App\Models\ProductModel;
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
                $model = new ProductModel();

                $variantInputs = $this->request->getPost('variant');
                $images = $this->request->getFileMultiple('images');

                $variantArray = [];
                if ($variantInputs) {
                    $variantValues = explode(',', $variantInputs);
                    foreach ($variantValues as $value) {
                        $variantArray[] = trim($value);
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

                $data = [
                    'product_name' => $this->request->getPost('product_name'),
                    'description' => $this->request->getPost('description'),
                    'price' => $this->request->getPost('price'),
                    'stock' => $this->request->getPost('stock'),
                    'variant' => $variantJson,
                    'images' => json_encode($imagePaths)
                ];

                $model->addProduct($data);

                if ($model->affectedRows() > 0) {
                    return $this->respond(['message' => 'Success'], 200);
                } else {
                    return $this->fail('Error! Failed to insert product.', 500);
                }
            }
        }

        return $this->respond('Unauthorized', 401);
    }


    public function getProduct()
    {
        $token = $this->request->getServer('HTTP_AUTHORIZATION');

        if($token)
        {
            $token = str_replace('Bearer ', '', $token);

            $cache = \Config\Services::cache();
            $user = $cache->get('user_' . $token);

            if($user)
            {
                $model = new ProductModel();

                $data = $model->getProd();

                return $this->respond($data, 200);
            }
        }

        return $this->respond('Unauthorized', 401);
    }

}
