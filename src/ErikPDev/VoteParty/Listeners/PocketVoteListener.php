<?php

namespace ErikPDev\VoteParty\Listeners;

use pocketmine\event\Listener;
use pocketmine\Player;
use pocketmine\utils\TextFormat;
use ProjectInfinity\PocketVote\event\VoteEvent;
use ErikPDev\VoteParty\Main;
class PocketVoteListener implements Listener{

    private $plugin;


    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
    }

    public function PlayerVoteEvent(VoteEvent $event) {
        $this->plugin->PlayerVoted();
    }
}