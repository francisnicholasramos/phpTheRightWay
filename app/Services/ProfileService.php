<?php 

namespace App\Services;

use App\Models\User;
use App\Models\UserTimestamp;
use App\Dto\ChangeNameDto;

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
}
