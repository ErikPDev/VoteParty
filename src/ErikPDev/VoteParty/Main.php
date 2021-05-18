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

use pocketmine\plugin\PluginBase;

use pocketmine\Player;
use pocketmine\Server;
use pocketmine\event\Listener;
use pocketmine\command\Command;
use pocketmine\item\Item;
use pocketmine\command\CommandSender;
use pocketmine\command\ConsoleCommandSender;
use ErikPDev\VoteParty\ServerData;
use ErikPDev\VoteParty\Listeners\BetterVotingListener;
use ErikPDev\VoteParty\Listeners\PocketVoteListener;
class Main extends PluginBase implements Listener {
    private $serverData;
    private $prefix;
    private $BetterVotingSupport = false;
    private $PocketVoteSupport = false;
    public function onEnable() {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->serverData = $data = new ServerData($this);
        $this->saveDefaultConfig();
        $this->reloadConfig();
        if($this->getConfig()->get("config-verison") != 1){
          $this->getLogger()->critical("Your config.yml file for VoteParty is outdated. Please use the new config.yml. To get it, delete the the old one.");
          $this->getServer()->getPluginManager()->disablePlugin($this);
          return;
        }
        $this->prefix = "§r§l[§eVote§cParty§f]§r ";
        if($this->getConfig()->get("BetterVotingSupport") == true && $this->getConfig()->get("PocketVoteSupport") == true){
          $this->getLogger()->critical("BetterVoting and PocketVote support are both setted to `true`, this will cause errors therefore, the plugin is disabling.");
          $this->getServer()->getPluginManager()->disablePlugin($this);
          return;
        }
        if($this->getServer()->getPluginManager()->getPlugin("BetterVoting") != null && $this->getConfig()->get("BetterVotingSupport") == true){
          $this->getServer()->getPluginManager()->registerEvents(new BetterVotingListener($this), $this);
          $this->BetterVotingSupport = true;
        }
        if($this->getServer()->getPluginManager()->getPlugin("PocketVote") != null && $this->getConfig()->get("PocketVoteSupport") == true){
          $this->getServer()->getPluginManager()->registerEvents(new PocketVoteListener($this), $this);
          $this->PocketVoteSupport = true;
        }
        
        
    }

    public function onDisable() {
      if(isset($this->serverData)){
          $data = $this->serverData;
          $data->save();
          unset($this->serverData);
      }
    }

    public function PlayerVoted() {
      if(isset($this->serverData)){
        $data = $this->serverData;
        if ($data->getVotes() < 1){
          $data->setVotes($this->getConfig()->get("VotestoVoteParty"));
          $this->getServer()->broadcastMessage($this->prefix.$this->getConfig()->get("WhenReached"));
          foreach ($this->getConfig()->get("commandtoRun") as &$value) {
            if(strpos($value, "@a") !== false){
              foreach($this->getServer()->getOnlinePlayers() as &$OPlayer){
                try {
                  $OPlayer->getName();
                  $this->getServer()->dispatchCommand(new ConsoleCommandSender(), str_replace("@a",$OPlayer->getName(),$value));
                } catch (\Throwable $th) {
                  throw $th;
                }
              } 
            }else{
              try {
                $this->getServer()->dispatchCommand(new ConsoleCommandSender(), $value);
              } catch (\Throwable $th) {
                throw $th;
              }
            }
            
          }
        }else{

          $this->getServer()->broadcastMessage($this->prefix.str_replace("{number}", $data->getVotes(), $this->getConfig()->get("CountdownMSG")));
          $data->decrementVotes();
          $data->save();
        }
      }
    }


    
    public function onCommand(CommandSender $player, Command $cmd, string $label, array $args) : bool{
      switch (strtolower($cmd->getName())) {
        case "voteparty":
          if($player instanceof Player){ $player->sendMessage($this->prefix.$this->getConfig()->get("ErrorRunning"));return true; }
          if($this->BetterVotingSupport == true){ $player->sendMessage($this->prefix."BetterVoting is enabled, please don't use this command");return true; }
          if($this->PocketVoteSupport == true){ $player->sendMessage($this->prefix."PocketVote is enabled, please don't use this command");return true; }
          $this->PlayerVoted();
      }
      return true;
    }



}
