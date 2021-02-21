<?php

namespace AdvancedBan\ServerCommands;

use AdvancedBan\Loader;
use AdvancedBan\DataBase\Discord;

use pocketmine\utils\{Config, TextFormat as TE};
use pocketmine\command\{CommandSender, PluginCommand};
use pocketmine\Player;

class Report extends PluginCommand {
	
	/** @var Loader */
	protected $plugin;
	
	/**
	 * Report Constructor.
	 * @param Main $plugin
	 */
	public function __construct(Loader $plugin){
		parent::__construct("report", $plugin);
		$this->plugin = $plugin;
		$this->setDescription("/report [string: target] [string: reason]");
		$this->setPermission("report.command.use");
	}
	
	/**
	 * @param CommandSender $sender
	 * @param String $cmd
	 * @param Array $args
	 * @return bool|mixed
	 */
	public function execute(CommandSender $sender, String $cmd, Array $args){
		if(!$sender instanceof Player){
			$sender->sendMessage(TE::RED."Use this command in the game!");
			return;
		}
		if(!isset($args[0])||!isset($args[1])){
			$sender->sendMessage(TE::RED."Usage: /report [string: target] [string: reason]");
			return;
		}
		$player = $this->plugin->getServer()->getPlayer($args[0]);
		$date = date("d/m/Y H:i:s");
		unset($args[0]);
		$reason = implode(" ", $args);
		if($player != null){
			$this->sendReport($player, $sender, $reason);
			Discord::sendToDiscord("https://discordapp.com/api/webhooks/716458243022520432/KKM9zcATAFWyo7CDBxsuvR_25iQWfT1dWBQzMIaOF7DsHc5zxrrroOoxw1UsfwoOMDE0", "VitalHCF",
			"=========================="."\n".
			"Accused: ".$player->getName()."\n".
			"Accuser: ".$sender->getName()."\n".
			"Connection: ".$player->getPing()."\n".
			"Reason: ".$reason."\n".
			"Date: ".$date."\n".
			"=========================="."\n"
			);
		}else{
			$sender->sendMessage(TE::RED."The player you are looking for is not connected!");
		}
		return true;
	}
		
	/**
	 * @param Player $player
	 * @param Player $sender
	 * @param String $args
	 */
	private function sendReport($player, $sender, $args){
		$sender->sendMessage(TE::GRAY."You reported to the player ".TE::DARK_RED.$player->getName());
		foreach($this->plugin->getServer()->getOnlinePlayers() as $pl){
			if($pl->hasPermission("report.command.use")){
				$pl->sendMessage(
				TE::GRAY."=========================="."\n".
				TE::YELLOW."Accused: ".TE::WHITE.$player->getName()."\n".
				TE::YELLOW."Accuser: ".TE::WHITE.$sender->getName()."\n".
				TE::YELLOW."Reason: ".TE::WHITE.$args."\n".
				TE::YELLOW."Connection: ".TE::WHITE.$player->getPing()."\n".
				TE::YELLOW."World: ".TE::WHITE.$player->getLevel()->getFolderName()."\n".
				TE::GRAY."=========================="
				);
			}
		}
	}
}