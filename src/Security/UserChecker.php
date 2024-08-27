<?php
namespace App\Security;

use App\Entity\User;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user): void
    {
        if (!$user instanceof User) {
            return;
        }
        if (!$user->isActive()) {
            throw new CustomUserMessageAuthenticationException(
                'Votre compte est inactif. Vous ne pouvez pas vous connecter. Merci de contacter l\'administrateur.'
            );
        }
    }
    public function checkPostAuth(UserInterface $user): void
    {
        $this->checkPreAuth($user);
    }
}