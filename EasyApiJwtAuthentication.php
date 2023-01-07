<?php

namespace EasyApiJwtAuthentication;

use EasyApiJwtAuthentication\DependencyInjection\EasyApiJwtExtension;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class EasyApiJwtAuthentication extends Bundle
{
    public function getContainerExtension(): ?ExtensionInterface
    {
        return new EasyApiJwtExtension();
    }
}
