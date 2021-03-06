<?php

namespace App\Providers;

use App\Actions\AddTeamMember as ActionsAddTeamMember;
use App\Actions\CreateTeam as ActionsCreateTeam;
use App\Actions\DeleteTeam as ActionsDeleteTeam;
use App\Actions\DeleteUser as ActionsDeleteUser;
use App\Actions\InviteTeamMember as ActionsInviteTeamMember;
use App\Actions\RemoveTeamMember as ActionsRemoveTeamMember;
use App\Actions\UpdateTeamName as ActionsUpdateTeamName;
use Illuminate\Support\ServiceProvider;
use Laravel\Jetstream\Jetstream;

class JetstreamServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Jetstream::ignoreRoutes();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->configurePermissions();

        Jetstream::createTeamsUsing(ActionsCreateTeam::class);
        Jetstream::updateTeamNamesUsing(ActionsUpdateTeamName::class);
        Jetstream::addTeamMembersUsing(ActionsAddTeamMember::class);
        Jetstream::inviteTeamMembersUsing(ActionsInviteTeamMember::class);
        Jetstream::removeTeamMembersUsing(ActionsRemoveTeamMember::class);
        Jetstream::deleteTeamsUsing(ActionsDeleteTeam::class);
        Jetstream::deleteUsersUsing(ActionsDeleteUser::class);
    }

    /**
     * Configure the roles and permissions that are available within the application.
     *
     * @return void
     */
    protected function configurePermissions()
    {
        Jetstream::defaultApiTokenPermissions(['own:read']);

        foreach (config('roles') as $role => $options) {
            Jetstream::role($role, $options['name'], array_keys($options['permissions']))->description($options['description']);
        }
    }
}
