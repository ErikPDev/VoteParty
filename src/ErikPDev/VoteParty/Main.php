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

use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\event\Listener;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\console\ConsoleCommandSender;
use ErikPDev\VoteParty\Listeners\BetterVotingListener;
use ErikPDev\VoteParty\Listeners\PocketVoteListener;
use ErikPDev\VoteParty\Listeners\ScoreHUDListener;
use Throwable;

class Main extends PluginBase implements Listener {
    public $serverData,$versionManager;
    private $prefix;
    private $scoreHud;
    private $BetterVotingSupport = false;
    private $PocketVoteSupport = false;
    private $ScoreHudSupport = false;
	private bool $ScoreboardSupport;

	public function onEnable() : void{
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->serverData = $data = new ServerData($this);
        $this->versionManager = new VersionManager($this);
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
          if(!$this->versionManager->isLatest("BetterVoting", 2.0)){
            $this->getLogger()->critical("This verison of BetterVoting isn't supported, the plugin is disabling.");
            $this->getServer()->getPluginManager()->disablePlugin($this);
            return;
          }
          $this->getServer()->getPluginManager()->registerEvents(new BetterVotingListener($this), $this);
          $this->BetterVotingSupport = true;
          $this->getLogger()->debug("BetterVoting support is enabled.");
        }
        if($this->getServer()->getPluginManager()->getPlugin("PocketVote") != null && $this->getConfig()->get("PocketVoteSupport") == true){
          // PocketVote doesn't need a version checker since it's supported with all versions, and I should really clean this code.
          $this->getServer()->getPluginManager()->registerEvents(new PocketVoteListener($this), $this);
          $this->PocketVoteSupport = true;
          $this->getLogger()->debug("PocketVote support is enabled.");
        }

        if($this->getServer()->getPluginManager()->getPlugin("ScoreHud") != null){
          if(is_dir($this->getServer()->getPluginManager()->getPlugin("ScoreHud")->getDataFolder()."addons")){
            if( !file_exists( $this->getServer()->getPluginManager()->getPlugin("ScoreHud")->getDataFolder()."addons\VoteParty.php" ) ){
              file_put_contents( $this->getServer()->getPluginManager()->getPlugin("ScoreHud")->getDataFolder()."addons\VoteParty.php", $this->getResource('/addon/VoteParty.php'));
              $this->getLogger()->debug("Added addon to ScoreHUD");
            }
          }else{
            $this->scoreHud = new ScoreHUDListener($this);
            $this->getServer()->getPluginManager()->registerEvents($this->scoreHud, $this);
            $this->ScoreHudSupport = true;
            $this->getLogger()->debug("ScoreHud support is enabled.");
          }
        }

        if($this->getServer()->getPluginManager()->getPlugin("Scoreboard") != null){
          if(!$this->versionManager->isLatest("Scoreboard", 1.2)){
            $this->getLogger()->critical("This version of Scoreboard isn't supported, Update Scoreboard to the latest version.");
          }else{
            $this->ScoreboardSupport = true;
            $this->getLogger()->debug("Scoreboard support is enabled.");
          }
        }

        if($this->getConfig()->get("PocketVoteSupport") == false && $this->getConfig()->get("BetterVotingSupport") == false){
          $this->getLogger()->debug("VoteParty command is enabled.");
        }
        Server::getInstance()->getAsyncPool()->submitTask(new Update("VoteParty", "1.4"));
        
        
    }

    public function onDisable() : void{
      if(!isset($this->serverData)){return;}
      $data = $this->serverData;
      $data->save();
      unset($this->serverData);
    }

    public function PlayerVoted() {
      if(!isset($this->serverData)){ return; }
        $data = $this->serverData;
        if ($data->getVotes() < 1){
          $data->setVotes($this->getConfig()->get("VotestoVoteParty"));
          $this->getServer()->broadcastMessage($this->prefix.$this->getConfig()->get("WhenReached"));
          foreach ($this->getConfig()->get("commandtoRun") as $value) {
            if(str_contains($value, "@a")){
              foreach($this->getServer()->getOnlinePlayers() as $OPlayer){
                try {
	                $this->getServer()->dispatchCommand(new ConsoleCommandSender($this->getServer(), $this->getServer()->getLanguage()), str_replace("@a",$OPlayer->getName(),$value));
                } catch (Throwable $th) {
                  $this->getLogger()->critical($th->getMessage());
                }
              } 
            }else{
              try {
                $this->getServer()->dispatchCommand(new ConsoleCommandSender($this->getServer(), $this->getServer()->getLanguage()), $value);
              } catch (Throwable $th) {
	              $this->getLogger()->critical($th->getMessage());
              }
            }
            
          }
        }else{
          if($this->ScoreHudSupport == true){$this->scoreHud->update();}
          $this->getServer()->broadcastMessage($this->prefix.str_replace("{number}", $data->getVotes(), $this->getConfig()->get("CountdownMSG")));
          $data->decrementVotes();
          $data->save();
        }
      
    }


    
    public function onCommand(CommandSender $player, Command $cmd, string $label, array $args) : bool{
      switch (strtolower($cmd->getName())) {
        case "voteparty":
          if($player instanceof Player){ $player->sendMessage($this->prefix.$this->getConfig()->get("ErrorRunning"));return true; }
          if($this->BetterVotingSupport == true){ $player->sendMessage($this->prefix."BetterVoting is enabled, please don't use this command.");return true; }
          if($this->PocketVoteSupport == true){ $player->sendMessage($this->prefix."PocketVote is enabled, please don't use this command.");return true; }
          $this->PlayerVoted();
          break;
        case "votepartyreset":
          $this->serverData->setVotes($this->getConfig()->get("VotestoVoteParty"));
          if($this->ScoreHudSupport == true){$this->scoreHud->update();}
          $player->sendMessage($this->prefix."Resseted VoteParty Counter.");
          break;
      }
      return true;
    }



}
