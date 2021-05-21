<?php


namespace App\Http\Controllers;

use App\Http\Services\Netgrif\TaskService;
use App\Http\Services\NotificationService;
use App\Http\Services\TeamService;
use App\Models\Event;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller;

class TeamController extends Controller
{
    private TeamService $teamService;
    private NotificationService $notificationService;
    private TaskService $taskService;

    public function __construct(
        TeamService $teamService,
        NotificationService $notificationService,
        TaskService $taskService
    )
    {
        $this->middleware('auth');
        $this->teamService = $teamService;
        $this->notificationService = $notificationService;
        $this->taskService = $taskService;
    }


    /*
     * Create a team
     */
    public function createTeam(Request $request): JsonResponse
    {
        $this->validate($request, [
            'team_name' => 'required'
        ]);
        $team = null;
        $user_id = auth()->id();
        $user = User::whereId($user_id)->first();

        $team_name = $request->get('team_name');
        $exists = Team::whereTeamName($team_name)->first();

        if ($exists) {
            throw new \Exception("Tím s rovnakým názvom už existuje. Zvoľ prosím iný názov tímu.", 403);
        }

        return app('db')->transaction(function () use ($team, $request, $user) {

            $team = new Team(array('team_name' => $request->get('team_name')));
            $user->ownTeams()->save($team);
            $team->team_members()->save($user);

            //notifikacia pre tim
            $this->notificationService->createNotificationForTeam(
                "Tím <b>" . $request->get('team_name') . "</b> bol úspešne vytvorený! Prajeme Vám veľa športových úspechov.",
                $team->id
            );

            //notifikacia pre tim
            $this->notificationService->createNotificationForUser(
                "Tím <b>" . $request->get('team_name') . "</b> bol úspešne vytvorený! Prajeme Vám veľa športových úspechov.",
                $user->id
            );

            $this->taskService->runTask($request['task_id']);

            return response()->json($team, 200);
        });
    }

    public function getTeamById(int $id): JsonResponse
    {
        $team = $this->teamService->getTeamById($id);
        return response()->json($team, 200);
    }


    public function updateTeam($team_id, Request $request): JsonResponse
    {
        $team = Event::findOrFail($team_id);
        $team->update($request->all());
        return response()->json($team, 200);
    }

    public function showAllUserTeams(): JsonResponse
    {
        $teams = $this->teamService->getAllteamsWhereIsUser();
        return response()->json($teams);
    }


    // TODO:: zatial nefunkcne
    public function showAllTeams(): JsonResponse
    {
        $teams = $this->teamService->getAllTeams();
        return response()->json($teams, 200);
    }

    public function addOneMemberToTeamByEmail(int $id, Request $request)
    {
        $team = Team::whereId($id)->first();
        if (!$team) {
            throw new \Exception("Tím nebol nájdený");
        }

        $loggedUserId = auth()->id();
        if ($team->user_id != $loggedUserId) {
            throw new \Exception("Nie ste vlastníkom tímu");
        }

        $this->validate($request, ['user_mail' => 'required|email']);
        $userEmail = $request->get('user_mail');

        $user = User::whereEmail($userEmail)->first();
        if (!$user) {
            throw new \Exception("Používateľ nebol nájdený");
        }

        $hasEvent = $user->teams()->get()->contains('id', '=', $team->id);
        if ($hasEvent) {
            throw new \Exception("Používateľ už sa nachádza v tomto tíme");
        }

        $user->teams()->save($team);

        $this->notificationService->createNotificationForUser(
            "<b>" . $user->firstname . "</b> informujeme ťa, že si bol pridaný do tímu <b>" .
            $team->team_name . "</b> Prajeme ti veľa úspechov!",
            $user->id
        );

        $this->notificationService->createNotificationForTeam(
            "Do tímu sa pripojil nový člen <b>$user->firstname $user->surname</b>!",
            $team->id
        );

        return response()->json();
    }


    public function deleteTeamById(int $team_id): JsonResponse
    {
        $user_id = auth()->id();
        $team = Team::whereId($team_id)->first();

        if (!$team) {
            throw new \Exception("Takýto tím neexistuje");
        }

        if ($team->user_id == $user_id) {
            $team->disabled = 1;
            $team->save();

            //notifikacia pre tim
            $this->notificationService->createNotificationForTeam(
                "Tím <b>" . $team->team_name . "</b> bol zrušený",
                $team->id
            );

            //notifikacia pre uzivatelov v time
            foreach ($team->team_members as $member) {
                $this->notificationService->createNotificationForUser(
                    "<b>" . $member->firstname . "</b> informujeme ťa, že tím <b>" . $team->team_name .
                    "</b>, do ktorého si patril, bol zrušený",
                    $member->id
                );
            }

            return response()->json('Tím vymazaný', 200);
        }

        return response()->json('"Nie ste vlastníkom tímu', 301);
    }

}
