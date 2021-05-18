<?php
/**
 *__      __   _       _____           _         
 *\ \    / /  | |     |  __ \         | |        
 * \ \  / /__ | |_ ___| |__) |_ _ _ __| |_ _   _ 
 *  \ \/ / _ \| __/ _ \  ___/ _` | '__| __| | | |
 *   \  / (_) | ||  __/ |  | (_| | |  | |_| |_| |
 *    \/ \___/ \__\___|_|   \__,_|_|   \__|\__, |
 *                                          __/ |
 *                                         |___/ By @ErikPDev for PMMP
 *
 * VoteParty, a VoteRewardSystem plugin for PocketMine-MP
 * Copyright (c) 2021 ErikPDev  < https://github.com/ErikPDev >
 *
 *
 * This software is distributed under "GNU General Public License v3.0".
 * This license allows you to use it and/or modify it but you are not at
 * all allowed to sell this plugin at any cost. If found doing so the
 * necessary action required would be taken.
 *
 * VoteParty is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License v3.0 for more details.
 *
 * You should have received a copy of the GNU General Public License v3.0
 * along with this program. If not, see
 * <https://opensource.org/licenses/GPL-3.0>.
 * ------------------------------------------------------------------------
 */

namespace ErikPDev\VoteParty;

class ServerData{
    private $main;

    private $votes = 0;
    public function __construct(Main $main){
        $this->main = $main;
        $path = $this->getPath();
        if(!is_file($path)){
            return;
        }

        $data = yaml_parse_file($path);
        $this->votes = $data["votes"];

    }

    public function save(){
        yaml_emit_file($this->getPath(), [
            "votes" => $this->getVotes(),
        ]);
    }

    public function getPath() : string{
        // Hint: remember to strtolower(), because some filesystems are case-sensitive!
        return $this->main->getDataFolder() . "serverdata.yml";
    }

    public function getVotes() : int{
        return $this->votes;
    }


    public function incrementVotes() {
        $this->votes++;
    }

    public function decrementVotes() {
        $this->votes--;
    }
    public function setVotes(int $number) {
      $this->votes = $number;
    }
}
