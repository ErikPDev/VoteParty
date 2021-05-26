<?php
/**
 * @name VotePartyAddon
 * @version 1.0.0
 * @main JackMD\ScoreHud\Addons\VoteParty
 * @depend VoteParty
 */
namespace JackMD\ScoreHud\Addons{
	use JackMD\ScoreHud\addon\AddonBase;
	use ErikPDev\VoteParty\Main;
	use pocketmine\Player;

	class VoteParty extends AddonBase{

		/** @var VoteParty */
		private $VoteParty;

		public function onEnable(): void{
			$this->VoteParty = $this->getServer()->getPluginManager()->getPlugin("VoteParty");
		}

		/**
		 * @param Player $player
		 * @return array
		 */
		public function getProcessedTags(Player $player): array{
			return [
				"{voteparty.totalVotes}" => $this->VoteParty->serverData->getTotalVotes(),
                "{voteparty.maxVotes}" => $this->VoteParty->getConfig()->get("VotestoVoteParty")
			];
		}
	}
}