<?php

namespace Hj\Handler;

use Hj\Message\MessageSleeping;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

/**
 * Class HandlerForMessageSleeping
 * @package Hj\Handler
 */
class HandlerForMessageSleeping implements MessageHandlerInterface
{
    public function __invoke(MessageSleeping $messageSleeping)
    {
        sleep(30);

        echo "message sleeped 30 sec\n";
    }

}