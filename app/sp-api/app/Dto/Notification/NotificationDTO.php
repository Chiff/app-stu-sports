<?php

namespace App\Dto\Notification;

use App\Models\AppSerializable;

class NotificationDTO extends AppSerializable
{
    public ?string $entity_id = null;
    public ?string $html_content = null;
    public ?string $entity_type = null;
    public ?string $created_at = null;
}
