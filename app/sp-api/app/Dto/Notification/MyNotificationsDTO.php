<?php


namespace App\Dto\Notification;


use App\Models\AppSerializable;

class MyNotificationsDTO extends AppSerializable
{
    /**
     * @var NotificationDTO[]|null
     */
    public ?array $notifications = [];
}
