<?php

namespace App\EventSubscriber;

use ApiPlatform\Symfony\EventListener\EventPriorities;
use App\Entity\Note;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class BinaryDecodeSubscriber implements  EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['decodeBinaryFields',EventPriorities::PRE_WRITE],

        ];
    }


    public function decodeBinaryFields(ViewEvent $event) :void
    {
        $note = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if(!$note instanceof Note || $method !== 'POST') {
            return;
        }

        $data = json_decode($event->getRequest()->getContent(), true);

        $note->setCipherText(self::decodeBase64ToStream($data['cipherText'] ?? ''));
        $note->setIv(self::decodeBase64ToStream($data['iv'] ?? ''));

    }


    private static function decodeBase64ToStream(string $data)
    {
        $resource = fopen('php://memory', 'rb+');
        fwrite($resource, base64_decode($data));
        rewind($resource);
        return $resource;
    }

}