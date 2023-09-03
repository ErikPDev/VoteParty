<?php

namespace ErikPDev\VoteParty\Listeners;

use pocketmine\event\Listener;
use pocketmine\Player;
use pocketmine\utils\TextFormat;
use kingofturkey38\voting38\events\PlayerVoteEvent;

use ErikPDev\VoteParty\Main;
class Voting38Listener implements Listener{

    private $plugin;


    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
    }

    public function PlayerVoteEvent(PlayerVoteEvent $event) {
        $this->plugin->PlayerVoted();
    }
}