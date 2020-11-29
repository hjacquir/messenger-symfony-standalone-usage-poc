<?php

require_once 'vendor/autoload.php';

use Doctrine\DBAL\DriverManager;
use Hj\Message\MessageNotSleeping;
use Hj\Message\MessageSleeping;
use Symfony\Component\Messenger\Bridge\Doctrine\Transport\Connection;
use Symfony\Component\Messenger\Bridge\Doctrine\Transport\DoctrineTransport;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Transport\Serialization\PhpSerializer;

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