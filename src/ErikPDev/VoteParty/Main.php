<?php


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
    public function getInstance(){
      return self;
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
