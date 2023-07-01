<?php

namespace App\Controllers;

use App\Models\BannerModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\RESTful\ResourceController;

class Banner extends ResourceController
{
    use ResponseTrait;

    public function createBanner()
    {
        $token = $this->request->getServer('HTTP_AUTHORIZATION');

        if ($token) {
            $token = str_replace('Bearer ', '', $token);

            $cache = \Config\Services::cache();
            $user = $cache->get('user_' . $token);

            if ($user) {
                $model = new BannerModel();

                $title = $this->request->getPost('title');
                $image = $this->request->getFile('image');

                $filename = str_replace(' ', '-', $title);

                if ($image->isValid() && !$image->hasMoved()) {
                    $newName = $filename . '.' . $image->getExtension();
                    $image->move(ROOTPATH . 'public/assets/uploads/banner', $newName);
                } else {
                    return $this->response->setJSON(['message' => 'Failed to upload image'])->setStatusCode(500);
                }

                $data = [
                    'title' => $title,
                    'filename' => $newName,
                    'created_by' => $user['name']
                ];

                $model->addBanner($data);

                if ($model->affectedRows() > 0) {
                    return $this->respond(['message' => 'Success'], 200);
                } else {
                    return $this->fail('Error! Failed to delete post.', 500);
                }
            }
        }

        return $this->respond('Unauthorized', 401);
    }

    public function getBanner()
    {
        $token = $this->request->getServer('HTTP_AUTHORIZATION');

        if ($token) {
            $token = str_replace('Bearer ', '', $token);

            $cache = \Config\Services::cache();
            $user = $cache->get('user_' . $token);

            if ($user)
            {
                $model = new BannerModel();

                $data = $model->getBanner();

                return $this->respond($data, 200);
            }
        }

        return $this->respond('Unauthorized', 401);
    }

    public function getBannerId($id)
    {
        $token = $this->request->getServer('HTTP_AUTHORIZATION');

        if ($token) {
            $token = str_replace('Bearer ', '', $token);

            $cache = \Config\Services::cache();
            $user = $cache->get('user_' . $token);

            if ($user)
            {
                $model = new BannerModel();

                $data = $model->getBanId($id);

                if ($data) {
                    return $this->respond($data, 200);
                } else {
                    return $this->fail('Post not found.', 404);
                }
            }
        }

        return $this->respond('Unauthorized', 401);

    }

    public function deleteBanner($id)
    {
        $token = $this->request->getServer('HTTP_AUTHORIZATION');

        if ($token) {
            $token = str_replace('Bearer ', '', $token);

            $cache = \Config\Services::cache();
            $user = $cache->get('user_' . $token);

            if ($user)
            {
                $model = new BannerModel();

                $model->deleteBan($id);

                if ($model->affectedRows() > 0) {
                    return $this->respond(['message' => 'Success'], 200);
                } else {
                    return $this->fail('Error! Failed to delete post.', 500);
                }

            }
        }

        return $this->respond('Unauthorized', 401);
    }

    public function bannerFe()
    {
        $model = new BannerModel();

        $data = $model->getBanner();

        return $this->respond($data, 200);
    }

}
