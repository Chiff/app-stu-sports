<?php


namespace App\Http\Controllers;

use App\Dto\Event\EventDTO;
use App\Http\Services\EventService;
use App\Http\Utils\DateUtil;
use App\Models\Event;
use App\Models\EventTeam;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use JsonMapper\JsonMapper;
use Laravel\Lumen\Routing\Controller;


class EventsController extends Controller
{
    private EventService $eventService;

    // tento kod zaruci ze auth middleware nebude obmedzovat showAllEvents
    private JsonMapper $jsonMapper;

    public function __construct(EventService $event, JsonMapper $jsonMapper, array $attributes = [])
    {
        $this->eventService = $event;
        $this->jsonMapper = $jsonMapper;

        $this->middleware('auth', ['except' => ['showAllEvents', 'showOneEventByIdUnsecured']]);
    }

    /**
     * Returns all events in the system, where registration is still possible
     */
    public function showAllEvents(): JsonResponse
    {
        $events = $this->eventService->getPublicEvents();
        return response()->json($events, 200);
    }

    /**
     * Get all events of logged user
     */
    public function showUserEvents(): JsonResponse
    {
        $events = $this->eventService->getMyEvents(true);
        return response()->json($events, 200);
    }

    /**
     * Find event by event id
     * @param $id
     * @return JsonResponse
     */
    public function showOneEventById($id): JsonResponse
    {
        $event = $this->eventService->getFullEventById($id);
        return response()->json($event);
    }

    public function showOneEventByIdUnsecured($id): JsonResponse
    {
        return $this->showOneEventById($id);
    }

    /**
     * Find event by event name
     * @param $event_name
     * @return JsonResponse
     */
    public function showOneEventsByEventName($name): JsonResponse
    {
        $events = Event::where('name', $name)->get();
        return response()->json($events, 200);
    }

    /**
     * Delete one event, by event id
     * @param $event_id
     * @return JsonResponse
     */
    public function deleteOneEvent($event_id): JsonResponse
    {
        //TODO kontrolovat, ci je aj vlastnikom eventu?
        Event::findOrFail($event_id)->delete();
        return response()->json('', 200);
    }

    /**
     * Create one event
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function createOneEvent(Request $request): JsonResponse
    {
        $this->validate($request, EventDTO::$validationRules);
        $e = $this->eventService->createOneEvent($request);
        return response()->json($e);
    }


    public function signTeamById(Request $request)
    {
        $this->validate($request, [
            'event_id' => 'required',
            'team_id' => 'required'
        ]);

        $event_id = $request->get('event_id');
        $team_id = $request->get('team_id');

        $taskId = $request->get('task_id');
        if (!$taskId) {
            throw new \Exception("Unexpected error (netgrif)");
        }


        $team = Team::whereId($team_id)->first();
        $event = Event::whereId($event_id)->first();

        if (!$event) {
            throw new \Exception("event not found");
        }

        $todayDate = DateUtil::now();

        if ($event->registration_start > $todayDate) throw new \Exception("Registrácia na toto podujatie ešte nebola spustená");
        if ($event->registration_end < $todayDate) throw new \Exception("Registrácia na toto podujatie vypršala");

        // TODO - 13/05/2021 - NA TOTO POZOR!
        return app('db')->transaction(function () use ($event, $team, $taskId) {
            // Ak sa jedna o nejaky public event, kde sa prihlasuju ludia samy za seba
            // v takom pripade pocitame s tym, ze v evente je nastavene max_team_members 1
            if ($event->max_team_members == 1 && !$team) {
                $user_id = auth()->id();
                $user = User::findOrFail($user_id);
                $user_name = $user->firstname . ' ' . $user->surname;

                /*
                 * Check if user already have team of himself
                 */
                $exists = $user->ownTeams()->where('team_name', $user_name)->where('disabled', '=', '0')->get();
                if (sizeof($exists) == 0) {
                    $team = new Team(array('team_name' => $user_name));
                    $user->ownTeams()->save($team);
                    $team->save();

                    $user->teams()->attach($team);
                    $user->save();
                }

                if ($team->disabled) {
                    throw new \Exception("Tento tím je deaktivovaný");
                }

                /*
                 * Check if team already signed in event
                 */
                $team = $user->ownTeams()->where('team_name', $user_name)->first();
                if ($event->teams()->find($team->id)) {
                    throw new \Exception("Tím je už na udalosť prihlásený");
                } else {
                    if ($event->max_teams > sizeof($event->teams()->get())) {
                        $event->teams()->save($team);
                        $this->eventService->runTask($taskId);
                        return response()->json('Done', 200);
                    } else throw new \Exception("Kapacita udalosti je už naplnená");

                }

            }

            if (!$team) {
                throw new \Exception("team not found");
            }

            $team_members_size = $team->team_members()->get();

            if ($event->min_team_members > sizeof($team_members_size)) {
                throw new \Exception("Team má málo členov");
            }

            if ($event->max_team_members < sizeof($team_members_size)) {
                throw new \Exception("Team má vela členov");
            }


            if ($team->user_id != auth()->id()) {
                throw new \Exception("Nie si vlastníkom tímu!");
            }

            if ($event->teams()->find($team->id)) {
                throw new \Exception("Tím je už na udalosť prihlásený");
            }

            if ($event->max_teams > sizeof($event->teams()->get())) {
                $event->teams()->save($team);
            } else {
                throw new \Exception("Kapacita udalosti je už naplnená");
            }

            $this->eventService->runTask($taskId);

            $this->eventService->notificationService->createNotificationForEvent(
                "Tím <b>". $team->team_name ."</b> sa prihlásil na podujatie.",
                $event->id
            );

            $this->eventService->notificationService->createNotificationForTeam(
                "Váš tím bol prihlásený na podujatie <b>". $event->name ."</b>. Držíme Vám palce!",
                $team->id
            );

            return response()->json('Done');
        });
    }

    /**
     * Add one participant to event, by participants email
     * @param Request $request
     * @throws ValidationException
     */
    public function addOneParticipantToEventByEmail(Request $request)
    {
        throw new \Exception('not implemented');
        //  $this->validate($request, [
        //      'event_id' => 'required',
        //      'user_mail' => 'required|email'
        //  ]);
        //
        //  $event_id = $request->get('event_id');
        //  $usermail = $request->get('user_mail');
        //
        //  $event = Event::findOrFail($event_id);
        //  $user = User::where('email', $usermail);
        //
        //  $user_id = $user->id();
        //
        //  $exist = $event->participants->contains(auth()->id());
        //  if ($exist == null) {
        //      $event->participants()->attach($user_id);
        //      $event->save();
        //  }
        //
        //  return response()->json('', 200);
    }

    /**
     * Update event info
     * @param $id
     * @param Request $request
     * @return JsonResponse
     */
    public function update(int $id, Request $request): JsonResponse
    {
        $eventDTO = $this->eventService->update( $id,  $request);
        return response()->json($eventDTO, 200);
    }

    public function disableEventById(int $event_id): JsonResponse
    {
        $user_id = auth()->id();
        $event = Event::whereId($event_id)->first();
        $dto = new EventDTO();

        if (!$event) throw new \Exception("event not found");

        $this->jsonMapper->mapObjectFromString($event->toJson(), $dto);

        $taskId = request()->get("task_id");
        return app('db')->transaction(function () use ($taskId, $event, $user_id,) {

            if ($event->user_id == $user_id) {
                $event->disabled = 1;
                $event->update();

                $this->eventService->runTask($taskId);

                //oznamenie pre podujatie
                $this->eventService->notificationService->createNotificationForEvent(
                    "Podujatie <b>". $event->name ."</b> bolo zrušené.",
                    $event->id
                );

                //oznamenie vsetkym prihlasenym timom
                foreach ($event->teams as $team) {
                    $this->eventService->notificationService->createNotificationForTeam(
                        "Podujatie s názvom <b>". $event->name ."</b>, na ktoré bol tím ". $team->team_name ." prihlásený, bolo práve zrušené.",
                        $team->id
                    );
                }
                // oznamenie vlastnikovi
                $this->eventService->notificationService->createNotificationForUser(
                    "Tvoje podujatie <b>". $event->name ."</b> bolo zrušené!",
                    $user_id
                );

                return response()->json('Podujatie bolo zrušené', 200);
            }

            throw new \Exception("Nie si vlastníkom podujatia");
        });
    }

    public function deleteTeamByIdFromEvent(int $event_id, int $team_id): JsonResponse
    {
        $user_id = auth()->id();

        // event & team
        $event = Event::whereId($event_id)->first();
        $team = Team::whereId($team_id)->first();

        if (!$team) throw new \Exception("team not found");
        if (!$event) throw new \Exception("event not found");

        $dto = new EventDTO();
        $this->jsonMapper->mapObjectFromString($event->toJson(), $dto);

        $todayDate = DateUtil::now();

        if (($todayDate < $dto->event_end) && ($todayDate > $dto->event_start)) {
            throw new \Exception("Podujatie aktuálne prebieha a nie je možné odhlásiť tím");
        }

        if (($todayDate > $dto->event_end)) {
            throw new \Exception("Podujatie už skončilo");
        }

        $taskId = request()->get("task_id");
        return app('db')->transaction(function () use ($taskId, $event, $user_id, $team) {
            // ak je prihlaseny user vlastnikom eventu, moze odhlasit team
            if ($event->user_id == $user_id) {
                $event->teams()->detach($team->id);
                $this->eventService->runTask($taskId);

                //notifikacia pre odhlaseny tim
                $this->eventService->notificationService->createNotificationForTeam(
                    "Tím bol odhlásený z podujatia <b>". $event->name ."</b>.",
                    $team->id
                );

                //notifikacia pre event
                $this->eventService->notificationService->createNotificationForEvent(
                    "Tím <b>". $team->team_name ."</b> sa odhlásil z podujatia.",
                    $event->id
                );


                return response()->json('Tím bol úspešne odhlásený z podujatia vlastníkom podujatia', 200);
            }

            // teamy na evente, kde je user kapitan
            $teams_on_event_owned_by_user = $event->teams->where('user_id', $user_id);

            $exists = $teams_on_event_owned_by_user->where('id', $team->id);

            if (sizeof($exists) > 0) {
                $event->teams()->detach($team->id);
                $this->eventService->runTask($taskId);
                return response()->json('Tím bol úspešne odhlásený z podujatia kapitánom tímu', 200);
            }

            $this->eventService->runTask($taskId);
            return response()->json('Done');
        });
    }

    public function runTask(string $stringId): JsonResponse
    {
        $result = $this->eventService->runTask($stringId);
        return response()->json($result, 200);
    }

    /**
     * Finish event and declare winner
     * @param $id
     * @param Request $request
     * @throws ValidationException
     */
    public function finishEventById($id, Request $request): JsonResponse
    {
        $event_teams = EventTeam::whereEventId($id)->get();
        $this->validate($request, [
            'winner_id' => 'required',
        ]);

        $winner_id = $request->get('winner_id');

        $taskId = request()->get("task_id");
        return app('db')->transaction(function () use ($taskId, $winner_id, $event_teams, $id) {
            EventTeam::where('team_id', $winner_id)->where('event_id', $id)->update(array('is_winner' => true));

            foreach ($event_teams as $event_team) {
                $team = Team::findOrFail($event_team->team_id);
                $team->increment('points', $event_team->points);

                $team->increment('events_total');
                if ($team->id == $winner_id) {
                    $team->increment('wins');

                    //notofikacia pri vitazny tim
                    $this->notificationService->createNotificationForTeam(
                        "Stali ste sa víťazným tímom na podujatí. Gratulujeme!",
                        $team->id
                    );
                }

                $team->save();
            }

            $event = Event::whereId($id)->first();
            $event->disabled = 1;
            $event->save();

            $this->eventService->notificationService->createNotificationForEvent(
                "Podujatie sa skončilo.",
                $event->id
            );

            $this->eventService->runTask($taskId);
            return response()->json('Podujatie bolo úspešne dokončené', 200);
        });
    }

    public function addPointsById($id, Request $request): JsonResponse
    {
        $this->validate($request, [
            'team_id' => 'required',
            'points' => 'required',
        ]);

        $team_id = $request->get('team_id');
        $points = $request->get('points');

        $event_team = EventTeam::where('team_id', $team_id)->where('event_id', $id)->first();
        if ($event_team) {
            EventTeam::where('team_id', $team_id)
                ->where('event_id', $id)
                ->update(array('points' => ($event_team->points + $points)));
            return response()->json('Body úspešne pridané', 200);
        }

        throw new \Exception("Tím nie je prihlásený na toto podujatie");
    }
    // TODO: hodit to na detail EventDTO, nie takto -> kuk mapEventWithTeams
//    public function showTeamsOnEvent(int $event_id): JsonResponse
//    {
//
//        // event
//        $event = Event::whereId($event_id)->first();
//
//        if (!$event){
//            throw new \Exception("event not found");
//        }
//
//        $teams = [];
//        foreach ($event->teams as $team){
//
//            $teamDTO = new TeamDTO();
//            $this->jsonMapper->mapObjectFromString($team->toJson(), $teamDTO);
//
//            $user = new UserDTO();
//            $userModel = User::whereId($team->user_id)->first();
//            $this->jsonMapper->mapObjectFromString($userModel->toJson(), $user);
//
//            $teamDTO->owner = $user;
//            array_push($teams, $teamDTO);
//        }
//
//
//        return response()->json($teams, 301);
//    }

}
