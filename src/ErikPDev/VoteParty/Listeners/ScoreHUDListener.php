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

namespace ErikPDev\VoteParty\Listeners;

use pocketmine\event\Listener;
use pocketmine\Player;
use pocketmine\utils\TextFormat;
use Ifera\ScoreHud\event\TagsResolveEvent;
use Ifera\ScoreHud\event\ServerTagUpdateEvent;
use Ifera\ScoreHud\scoreboard\ScoreTag;
use ErikPDev\VoteParty\Main;
class ScoreHUDListener implements Listener{

    private $plugin;


    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
    }

    public function onTagResolve(TagsResolveEvent $event){
        $player = $event->getPlayer();
        $tag = $event->getTag();
    
        switch($tag->getName()){
            case "voteparty.totalVotes":
                $tag->setValue((string)$this->plugin->serverData->getTotalVotes());
            break;
            case "voteparty.maxVotes":
                $tag->setValue((string)$this->plugin->getConfig()->get("VotestoVoteParty"));
            break;
    
        }
    }
    public function update(){
        $ev = new ServerTagUpdateEvent(new ScoreTag(
			"voteparty.totalVotes",
		        (string)$this->plugin->serverData->getTotalVotes())
	);
	$ev->call();
    }
}