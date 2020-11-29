<?php

use Doctrine\DBAL\DriverManager;
use Hj\Handler\HandlerForMessageNotSleeping;
use Hj\Handler\HandlerForMessageSleeping;
use Hj\Message\MessageNotSleeping;
use Hj\Message\MessageSleeping;
use Symfony\Component\Messenger\Bridge\Doctrine\Transport\Connection;
use Symfony\Component\Messenger\Bridge\Doctrine\Transport\DoctrineReceiver;
use Symfony\Component\Messenger\Handler\HandlersLocator;
use Symfony\Component\Messenger\MessageBus;
use Symfony\Component\Messenger\Middleware\HandleMessageMiddleware;
use Symfony\Component\Messenger\Worker;

require_once "vendor/autoload.php";

$doctrineDbalConnection = DriverManager::getConnection([
    'url' => 'sqlite:///db/test.sqlite',
]);
$transportDoctrineConnection = new Connection(
    [
        'connection' => 'doctrine://default',
    ],
    $doctrineDbalConnection
);

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

$receiver = new DoctrineReceiver($transportDoctrineConnection);
$worker = new Worker([$receiver], $bus, null);
$worker->run();