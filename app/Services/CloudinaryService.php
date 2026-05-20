<?php

namespace App\Services;

use Cloudinary\Cloudinary;
use Cloudinary\Configuration\Configuration;

class CloudinaryService {
    private Cloudinary $cloudinary;

    public function __construct() {
        $this->cloudinary = new Cloudinary(
            Configuration::instance([
                'cloud' => [
                    'cloud_name' => $_ENV['CLOUDINARY_CLOUD_NAME'],
                    'api_key'    => $_ENV['CLOUDINARY_API_KEY'],
                    'api_secret' => $_ENV['CLOUDINARY_API_SECRET'],
                ],
                'url' => ['secure' => true]
            ])
        );

    }

    public function upload(string $filePath, string $folder): string {
        $result = $this->cloudinary->uploadApi()->upload($filePath, [
            'folder' => 'socialnetwork/' . $folder,
            'format' => 'webp'
        ]);

        return $result['secure_url'];
    }
}
