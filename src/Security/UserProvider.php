<?php

namespace App\Security;

use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserProvider implements UserProviderInterface
{
    protected UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function authenticate(array $credentials, UserPasswordEncoder $passwordEncoder): ?User
    {
        $username = $credentials['username'];
        $password = $credentials['password'];
        $userEntity = $this->userRepository->loadUserByUsername($username);

        if($userEntity && $passwordEncoder->isPasswordValid($userEntity, $password))
        {
            $user = new User();
            $user->setUsername($username);
            $user->setPassword($passwordEncoder->encodePassword($user, $password));

            return $user;
        }

        return null;
    }

    public function loadUserByUsername(string $username)
    {
        $user = new User();
        $user->setUsername($username);

        return $user;
    }

    public function refreshUser(UserInterface $user)
    {
        return $user;
    }

    public function supportsClass(string $class)
    {
        return true;
    }
}
