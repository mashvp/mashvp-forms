<?php

namespace Mashvp\Forms\Notifications;

abstract class GenericNotification
{
    abstract public static function handle($submission, $form);
}
