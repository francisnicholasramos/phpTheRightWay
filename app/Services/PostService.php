<?php 

namespace App\Services;

use App\Models\Post;

class PostService {
    private Post $postModel;

    public function __construct() {
        $this->postModel = new Post();
    }

    public function attachPhotos(string $userId, string $postId, array $files): void {
        $allowedFormat = [
            'image/jpeg', 
            'image/jpg', 
            'image/png', 
            'image/webp', 
            'image/heic', 
            'image/heif'
        ];
        $maxSize = 10 * 1024 * 1024;
        $maxCount = 4;
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $cloudinary = new CloudinaryService();
        $photoModel = new \App\Models\UserPhoto();

        $count = 0;
        foreach ($files['tmp_name'] as $i => $tmpName) {
            if ($count >= $maxCount) break;
            if ($files['error'][$i] !== UPLOAD_ERR_OK) continue;
            if ($files['size'][$i] > $maxSize) continue;
            if (!in_array($finfo->file($tmpName), $allowedFormat)) continue;

            $url = $cloudinary->upload($tmpName, 'posts');
            $photoModel->insertPhoto($userId, $url, 'post', $postId);
            $count++;
        }
    }

    /** 
     * @return bool
     */
    public function post(
      string $user_id, 
      string $content, 
      string $visibility,
      array $files=[]
    ): bool {
        $postId = $this->postModel->createPost([ 
            'user_id' => $user_id, 
            'content' => $content, 
            'visibility' => $visibility
        ]);

        if (!$postId) return false;

        if (!empty($files)) {
          $this->attachPhotos($user_id, $postId, $files);
        }

        return true;
    }

    /**
     * @return bool
     */
    public function editPost(string $currentUser, \App\Dto\EditPostDto $data): bool {
        $user = $this->postModel->getOwnerId($data->id);

        if (!$user || $user->user_id !== $currentUser) return false;

        return $this->postModel->updatePost($data);
    }

    public function deletePost(string $id, string $user_id): bool {
        $owner = $this->postModel->getOwnerId($id);

        if (!$owner || $owner->user_id !== $user_id) return false;

        $photos = (new \App\Models\UserPhoto())->getByPostId($id);

        foreach ($photos as $photo) {
            (new CloudinaryService())->delete($photo['url']);
        }

        return $this->postModel->deletePost($id, $user_id);
    }

}

