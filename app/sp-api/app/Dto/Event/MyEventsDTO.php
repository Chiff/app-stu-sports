<?php


namespace App\Dto\Event;


use App\Models\AppSerializable;

/** @typescript */
class MyEventsDTO extends AppSerializable
{
    /**
     * @var EventDTO[]|null
     */
    public ?array $owned = [];

    /**
     * @var EventDTO[]|null
     */
    public ?array $upcoming = [];
    /**
     * @var EventDTO[]|null
     */
    public ?array $ended = [];
}
