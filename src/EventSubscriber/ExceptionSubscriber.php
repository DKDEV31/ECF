<?php

namespace App\EventSubscriber;

use Doctrine\DBAL\Exception\ConnectionException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\ORMInvalidArgumentException;
use PDOException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class ExceptionSubscriber implements EventSubscriberInterface
{
    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();
        if($exception instanceof ORMException){
            $event->getRequest()->getSession()->getFlashBag()
                ->add('danger', 'Une erreur est survenue avec la base de données');
            $response = new RedirectResponse('/');
            $event->setResponse($response);
        }else if ($exception instanceof ORMInvalidArgumentException){
            $event->getRequest()->getSession()->getFlashBag()
                ->add('danger', 'Une erreur est survenue avec la base de données');
            $response = new RedirectResponse('/');
            $event->setResponse($response);
        } else if($exception instanceof PDOException){
            $event->getRequest()->getSession()->getFlashBag()
                ->add('danger', 'Une erreur est survenue avec la base de données');
            $response = new RedirectResponse('/');
            $event->setResponse($response);
        } else if($exception instanceof ConnectionException){
            $event->getRequest()->getSession()->getFlashBag()
                ->add('danger', 'Une erreur est survenue avec la base de données');
            $response = new RedirectResponse('/');
            $event->setResponse($response);
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            'kernel.exception' => 'onKernelException',
        ];
    }
}
