<?php

namespace App\EventSubscriber;

use ApiPlatform\Symfony\EventListener\EventPriorities;
use App\Entity\Note;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;

class DateSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::VIEW => ['setDate', EventPriorities::PRE_VALIDATE],
        ];
    }


    public function setDate(ViewEvent $event): void{

        $note = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if(!$note instanceof Note || $method !== 'POST') {
            return;
        }

        $note->setCreatedAt(new \DateTimeImmutable());
        $note->setUpdatedAt(new \DateTimeImmutable());

    }
}