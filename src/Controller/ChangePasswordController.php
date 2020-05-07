<?php

declare(strict_types=1);

namespace App\Controller;

use App\Bundle\{Html, Key};
use App\Core\{Config, Email, Token};
use App\Model\ChangePasswordModel;
use App\Service\ChangePasswordService;
use App\Validator\ChangePasswordValidator;

class ChangePasswordController
{
    public function changePasswordAction(
        string $newPassword,
        string $repeatPassword,
        bool $submit,
        string $token,
        string $user,
        string $code
    ): array {
        $config = new Config();
        $mail = new Email();
        $html = new Html();
        $key = new Key();
        $csrfToken = new Token();
        $changePasswordModel = new ChangePasswordModel();
        $changePasswordValidator = new ChangePasswordValidator($csrfToken);

        $changePasswordModel->dbConnect();

        $changePasswordService = new ChangePasswordService(
            $config,
            $mail,
            $html,
            $key,
            $csrfToken,
            $changePasswordModel,
            $changePasswordValidator
        );
        $array = $changePasswordService->variableAction(
            $newPassword,
            $repeatPassword,
            $submit,
            $token,
            $user,
            $code
        );

        $changePasswordModel->dbClose();

        return $array;
    }
}
