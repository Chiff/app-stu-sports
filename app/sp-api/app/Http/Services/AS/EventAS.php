<?php


namespace App\Http\Services\AS;


use App\Dto\Event\EventDTO;
use App\Http\Services\CiselnikService;
use App\Models\Event;
use App\Models\Netgrif\CaseResource;
use JsonMapper\JsonMapper;

class EventAS
{
    private CiselnikService $ciselnikService;

    public function __construct(
        JsonMapper $mapper,
        CiselnikService $ciselnikService,
    )
    {
        $this->mapper = $mapper;
        $this->ciselnikService = $ciselnikService;
    }

    public function save(EventDTO $dto, CaseResource $netgrifEvent): bool
    {
        $event = new Event;
        $event->ext_id = $netgrifEvent->stringId;

        $event->user_id = $dto->user_id;
        $event->name = $dto->name;
        $event->description = $dto->description;
        $event->type = $this->ciselnikService->getOrCreateCiselnik($dto->type, 'EVENT_TYPE')->id;

        $event->min_teams = $dto->min_teams;
        $event->max_teams = $dto->max_teams;
        $event->min_team_members = $dto->min_team_members;
        $event->max_team_members = $dto->max_team_members;

        $event->registration_start = $dto->registration_start;
        $event->registration_end = $dto->registration_end;
        $event->event_start = $dto->event_start;
        $event->event_end = $dto->event_end;

        return $event->save();
    }


}
