<?php
namespace App\Service;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class PasswordValidator
{
    private $passwordEncoder;

    /**
     * Verifica si la contraseña proporcionada es válida.
     *
     * @param UserInterface $user El usuario con la contraseña cifrada.
     * @param string $plainPassword La contraseña proporcionada por el usuario.
     *
     * @return bool Verdadero si la contraseña es válida, falso si no lo es.
     */
    public function isPasswordValid(UserInterface $user, string $plainPassword): bool
    {
        return $this->passwordEncoder->isPasswordValid($user, $plainPassword);
    }
}
