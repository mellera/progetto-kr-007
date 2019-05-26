<?php

namespace Sys\Listener;

class Permission
{

    public function onKernelRequest(\Symfony\Component\HttpKernel\Event\GetResponseEvent $event)
    {
        $request = $event->getRequest();

        $userPermissions = $request->attributes->get('user_permissions');

        foreach ($request->attributes->get('_required_permissions') as $requiredPermission) {
            if (!in_array($requiredPermission, $userPermissions)) {
                throw new \Sys\Exception\Unauthorized();
            }
        }
    }

}
