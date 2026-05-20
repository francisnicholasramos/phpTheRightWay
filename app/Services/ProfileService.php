<?php 

namespace App\Services;

use App\Models\User;
use App\Models\UserProfile;
use App\Models\UserTimestamp;
use App\Dto\ChangeNameDto;
use App\Dto\EditPersonalDetailsDto;
use App\Dto\AboutMeDto;
use App\Dto\EducationDto;
use App\Dto\WorkDto;

class ProfileService {
    private User $userModel;
    private UserTimestamp $userTimeStampModel;

    public function __construct() {
        $this->userModel = new User();
        $this->userTimeStampModel = new UserTimestamp();
    }

    public function changeName(ChangeNameDto $data): bool {
        $nameChangedAt = $this->userTimeStampModel->getNameChangedAt($data->id);

        if ($nameChangedAt) {
            $diff = (new \DateTime())->diff(new \DateTime($nameChangedAt))->days;

            if ($diff < 30) return false;
        }

        $changed = $this->userModel->changeName($data);

        if ($changed) {
            $this->userTimeStampModel->upsertNameChanged($data->id);
        }

        return $changed;
    }

    public function editPersonalDetails(EditPersonalDetailsDto $data): ?string {
        $existing = $this->userModel->findByUsername($data->username);
        if ($existing && $existing->id !== $data->id) {
            return 'Username is already taken.';
        }

        $birthday = \DateTime::createFromFormat('Y-m-d', $data->birthday);
        if (!$birthday || $birthday > new \DateTime()) {
            return 'Birthday cannot be in the future';
        }

        if (!in_array($data->gender, ['male', 'female'])) {
            return 'Invalid gender.';
        }

        return $this->userModel->updatePersonalDetails($data) ? null : 'Something went wrong.';
    }

    public function editAboutMe(AboutMeDto $data): bool {
        return (new UserProfile())->upsert($data->id, $data);
    }

    public function editEducation(EducationDto $data): bool {
        return (new UserProfile())->upsertEducation($data->id, $data);
    }

    public function editWork(WorkDto $data): bool {
        return (new UserProfile())->upsertWork($data->id, $data);
    }

    /**
     * @param string $userId
     * @param array $file
     * @return string|null
     */
    public function changeAvatar(string $userId, array $file): ?string {
        $format = [
            'image/jpeg', 
            'image/jpg', 
            'image/png', 
            'image/webp', 
            'image/heic', 
            'image/heif'
        ];

         // reads the actual file bytes instead of trusting the browser-supplied type, which can be spoofed
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mimetype = $finfo->file($file['tmp_name']);

        if (!in_array($mimetype, $format)) {
            return 'Only JPEG, PNG, WebP, HEIC, HEIF images are allowed.';
        }

        if ($file['size'] > 10 * 1024 * 1024) {
            return 'Image must be under 10MB.';
        }

        $url = (new CloudinaryService())->upload($file['tmp_name'], 'avatars');

        (new \App\Models\UserPhoto())->insertPhoto($userId, $url, 'avatar');
        $this->userModel->updateAvatar($userId, $url);

        return null;
    }
}
