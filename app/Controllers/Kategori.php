<?php

namespace App\Controllers;

use App\Models\KategoriModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\RESTful\ResourceController;

class Kategori extends ResourceController
{
    use ResponseTrait;

    // Crud

    public function createKategori()
    {
        $token = $this->request->getServer('HTTP_AUTHORIZATION');

        if($token)
        {
            $token = str_replace('Bearer ', '', $token);

            $cache = \Config\Services::cache();
            $user = $cache->get('user_' . $token);

            if($user)
            {
                $model = new KategoriModel();

                $data = [
                    'name' => $this->request->getPost('name'),
                    'created_by' => $user['name']
                ];

                $model->addKategori($data);

                if ($model->affectedRows() > 0) {
                    return $this->respondDeleted(['message' => 'Success'], 200);
                } else {
                    return $this->fail('Error! Failed to delete post.', 500);
                }
            }
        }

        return $this->respond('Unauthorized', 401);
        
    }

    public function allKategori()
    {
        $token = $this->request->getServer('HTTP_AUTHORIZATION');

        if($token)
        {
            $token = str_replace('Bearer ', '', $token);

            $cache = \Config\Services::cache();
            $user = $cache->get('user_' . $token);

            if($user)
            {
                $model = new KategoriModel();

                $data = $model->allK();

                return $this->respond($data, 200);
            }
        }

        return $this->respond('Unauthorized', 401);
    }

    public function idKategori($id)
    {
        $token = $this->request->getServer('HTTP_AUTHORIZATION');

        if($token)
        {
            $token = str_replace('Bearer ', '', $token);

            $cache = \Config\Services::cache();
            $user = $cache->get('user_' . $token);

            if($user)
            {
                $model = new KategoriModel();

                $data = $model->idK($id);

                if ($data) {
                    return $this->respond($data, 200);
                } else {
                    return $this->fail('Post not found.', 404);
                }
            }
        }

        return $this->respond('Unauthorized', 401);
        
    }

    public function updateKategori($id = null)
    {
        $token = $this->request->getServer('HTTP_AUTHORIZATION');

        if($token)
        {
            $token = str_replace('Bearer ', '', $token);

            $cache = \Config\Services::cache();
            $user = $cache->get('user_' . $token);
            if($user){
                $model = new KategoriModel();

                $data = [
                    'name' => $this->request->getVar('name'),
                    'updated_by' => $user['name']
                ];

                $data = array_filter($data, function ($value) {
                    return $value !== null;
                });

                if (empty($data)) {
                    return $this->fail('No data provided for update', 400);
                }

                $model->updateK($id, $data);

                if ($model->affectedRows() > 0) {
                    return $this->respond(['message' => 'Data updated successfully'], 200);
                } else {
                    return $this->fail('Failed to update data', 500);
                }
            }
        }

        return $this->respond('Unauthorized', 401);
        
    }

    public function deleteKategori($id)
    {
        $token = $this->request->getServer('HTTP_AUTHORIZATION');

        if($token)
        {
            $token = str_replace('Bearer ', '', $token);

            $cache = \Config\Services::cache();
            $user = $cache->get('user_' . $token);

            if($user)
            {
                $model = new KategoriModel();

                $model->deleteK($id);

                if ($model->affectedRows() > 0) {
                    return $this->respondDeleted(['message' => 'Success'], 200);
                } else {
                    return $this->fail('Error! Failed to delete post.', 500);
                }
            }
        }

        return $this->respond('Unauthorized', 401);

    }


}
