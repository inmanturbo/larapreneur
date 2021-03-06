<?php

namespace App;

use App\Contracts\UpdatesTeamDomains;
use App\Contracts\UpdatesTeamLogo;
use App\Models\Team;
use Illuminate\Http\Request;

class Charter
{
    /**
     * Register a class / callback that should be used to update team logo.
     *
     * @param  string  $callback
     * @return void
     */
    public static function updateTeamLogosUsing(string $callback)
    {
        app()->singleton(UpdatesTeamLogo::class, $callback);
    }

    /**
     * Register a class / callback that should be used to update team logo.
     *
     * @param  string  $callback
     * @return void
     */
    public static function updateTeamDomainsUsing(string $callback)
    {
        app()->singleton(UpdatesTeamDomains::class, $callback);
    }

    public static function currentTeam()
    {
        if (! session()->has('current_team_uuid')) {
            return false;
        }

        $currentTeamUuid = session()->get('current_team_uuid');

        return Team::whereUuid($currentTeamUuid)->first();
    }

    public static function ensureTeamForUser(Request $request)
    {
        if (! $request->user() || ! $request->user()->currentTeam) {
            return;
        }

        $team = Team::where('uuid', $request->user()->currentTeam->uuid)->first();

        if (isset($team->uuid)) {
            session()->put('current_team_uuid', $team->uuid);
        }
    }

    public static function ensureTeamForDomain(Request $request)
    {
        $host = $request->getHost();

        if ($team = Team::where('domain', $host)->first()) {
            session()->put('current_team_uuid', $team->uuid);
        }
    }
}
