<?php

namespace Hj\Handler;

use Hj\Message\MessageNotSleeping;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

/**
 * Class HandlerForMessageNotSleeping
 * @package Hj\Handler
 */
class HandlerForMessageNotSleeping implements MessageHandlerInterface
{
    public function __invoke(MessageNotSleeping $messageNotSleeping)
    {
        echo "message not sleeping\n";
    }

}