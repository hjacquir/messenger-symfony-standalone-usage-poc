<?php

require_once 'vendor/autoload.php';

use Doctrine\DBAL\DriverManager;
use Symfony\Component\Messenger\Bridge\Doctrine\Transport\Connection;
use Symfony\Component\Messenger\Bridge\Doctrine\Transport\DoctrineTransport;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Handler\HandlersLocator;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBus;
use Symfony\Component\Messenger\Middleware\HandleMessageMiddleware;
use Symfony\Component\Messenger\Transport\Serialization\PhpSerializer;

class MessageSleeping
{

}

class MessageNotSleeping
{

}

class HandlerForMessageNotSleeping implements MessageHandlerInterface
{
    public function __invoke(MessageNotSleeping $messageNotSleeping)
    {
        echo "message not sleeping\n";
    }
}

class HandlerForMessageSleeping implements MessageHandlerInterface
{
    public function __invoke(MessageSleeping $messageSleeping)
    {
        sleep(1);

        echo "message sleeped 30 sec\n";
    }
}

$bus = new MessageBus([
        new HandleMessageMiddleware(new HandlersLocator(
                [
                    MessageNotSleeping::class => [
                        new HandlerForMessageNotSleeping(),
                    ],
                    MessageSleeping::class => [
                        new HandlerForMessageSleeping(),
                    ],
                ]
            )
        ),
    ]
);

$serializer = new PhpSerializer();

$doctrineDbalConnection = DriverManager::getConnection([
    'url' => 'sqlite:///db/test.sqlite',
]);
$transportDoctrineConnection = new Connection(
    [
        'connection' => 'doctrine://default',
    ],
    $doctrineDbalConnection
);

$doctrineTransport = new DoctrineTransport($transportDoctrineConnection, $serializer);

$messageSleeping = new MessageSleeping();
$messageNotSleeping = new MessageNotSleeping();

$doctrineTransport->send(new Envelope($messageSleeping));
$doctrineTransport->send(new Envelope($messageNotSleeping));

/*
$bus->dispatch($messageSleeping);
$bus->dispatch($messageNotSleeping);
*/

echo "finished";