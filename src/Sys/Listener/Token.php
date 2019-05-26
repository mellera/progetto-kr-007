<?php

namespace Sys\Listener;

use Sys\Context as Context;

class Token
{

    public function onKernelRequest(\Symfony\Component\HttpKernel\Event\GetResponseEvent $event)
    {
        $request = $event->getRequest();

        if (!$request->attributes->get('_check_auth')) {
            return;
        }

        $parser = new \Lcobucci\JWT\Parser();

        $authorization = str_replace('Bearer ', '', $request->headers->get('Authorization') ?: $request->headers->get('Authorization-header-custom'));

        try {
            $token = $parser->parse($authorization);
        } catch (\Exception $ex) {
            throw new \Sys\Exception\InvalidToken('', \Symfony\Component\HttpFoundation\Response::HTTP_FORBIDDEN, $ex);
        }

        if (!$token->verify(new \Lcobucci\JWT\Signer\Hmac\Sha256(), Context::getAppKey())) {
            throw new \Sys\Exception\InvalidToken();
        }

        try {
            $userId = $token->getClaim('id');

            try {
                $user = \Model\User::findOrFail($userId);
            } catch (\Exception $ex) {
                throw new \Sys\Exception\InvalidUser();
            }

            $permissions = array();
            foreach ($user->getGroups() as $group) {
                foreach ($group->getPermissions() as $permission) {
                    $permissions[] = $permission->getId();
                }
            }

            $request->attributes->set('user', $user);
            $request->attributes->set('user_permissions', $permissions);

            Context::logger()->setUser($user);

            \Protector\Protector::setUser($user);
        } catch (\OutOfBoundsException $ex) {
            throw new \Sys\Exception\InvalidToken();
        }
    }

}
