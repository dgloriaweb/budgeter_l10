<?php

namespace Tests\Feature;

use App\Http\Controllers\PatreonController;
use App\Models\User;
use App\Services\PatreonService;
use Tests\TestCase;


class testResettingPatreonCounter extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_patreon_counter_resets(): void
    {
        // find all users in the list that has the patreon counter on 5
        $users =  User::whereNotNull('patreon_code')
            ->where('patreon_code', '!=', '')
            ->where('patreon_daily_counter', 5);

        $patreonService = new PatreonService();
        $patreonController = new PatreonController($patreonService);
        $patreonController->resetPatreonCounter();

        // assert that the list is now empty
        $users =  User::whereNotNull('patreon_code')
            ->where('patreon_code', '!=', '')
            ->where('patreon_daily_counter', 5);
        $this->assertEmpty($users);
    }
}
