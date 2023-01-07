<?php

namespace EasyApiJwtAuthentication\EventListener;

use EasyApiBundle\Entity\User\AbstractUser as User;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;

class JWTAuthenticatedListener
{
    /**
     * @param AuthenticationSuccessEvent $event
     */
    public function onAuthenticationSuccessResponse(AuthenticationSuccessEvent $event): void
    {
        /** @var User $user */
        $user = $event->getUser();

        if (!$user instanceof User) {
            return;
        }

        // deny multiple connections on the same login
//        $manager = $this->container->get('gesdinet.jwtrefreshtoken.refresh_token_manager');
//        $refreshToken = $manager->getLastFromUsername($user->getUsername());
//        if ($refreshToken) {
//            $manager->delete($refreshToken);
//        }

    }
}
