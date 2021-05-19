<?php


namespace App\Dto;


use App\Models\AppSerializable;

class CiselnikDTO extends AppSerializable
{
    // TODO - 13/05/2021 - tu treba doplnat vsetky existujuce typy
    public static $types = [
        "EVENT_TYPE",
        "ENTITY_TYPE"
    ];

    public ?int $id = null;
    public ?string $label;
    public ?string $group;
    public ?string $type;
}

