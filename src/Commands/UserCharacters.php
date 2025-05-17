<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015 to present Leon Jacobs
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
 */

namespace Seat\UNSC1419\SkynetAuth\Commands;

use Illuminate\Console\Command;
use Seat\Eveapi\Bus\Character;
// use Seat\Eveapi\Models\RefreshToken;
use Seat\Web\Models\User;

/**
 * Class Characters.
 *
 * @package Seat\Eveapi\Commands\Esi\Update
 */
class UserCharacters extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'esi:update:UserCharacters {user_id : ID from UserCharacter to update}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Schedule updater jobs for user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        

        $user = User::find($this->argument('user_id'));
        if (! $user) {
            $this->error('The provided UserID is invalid or not registered in SeAT.');

            return $this::INVALID;
        }

        foreach ($user->refresh_tokens as $token){

            // Fire the class that handles the collection of jobs to run.
            (new Character($token->character_id, $token))->fire();

            $this->info(sprintf('Processing character update %d - %s',
                $token->character_id, $token->character->name ?? trans('web::seat.unknown')));
        }

        

        return $this::SUCCESS;
    }
}
