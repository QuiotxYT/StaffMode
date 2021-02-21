<?php

namespace AdvancedBan\ServerCommands;

use AdvancedBan\Loader;

use pocketmine\utils\TextFormat as TE;
use pocketmine\command\{CommandSender, PluginCommand};
use pocketmine\Player;

class Kick extends PluginCommand {
	
	/** @var Loader */
	protected $plugin;
	
	/**
	 * Kick Constructor.
	 * @param Loader $plugin
	 */
	public function __construct(Loader $plugin){
		parent::__construct("kick", $plugin);
		$this->plugin = $plugin;
		$this->setPermission("kick.command.use");
	}
	
	/**
	 * @param CommandSender $sender
	 * @param String $commandLabel
	 * @param Array $args
	 * @return bool|mixed
	 */
	public function execute(CommandSender $sender, String $commandLabel, Array $args){
		if(!$sender->hasPermission("kick.command.use")){
			$sender->sendMessage(TE::RED."You have not permissions to use this command");
			return;
		}
		if(!isset($args[0])||!isset($args[1])){
			$sender->sendMessage(TE::RED."Usage: /kick [string: target] [string: reason]");
			return;
		}
		$player = $this->plugin->getServer()->getPlayer($args[0]);
		if($player === null){
			$sender->sendMessage(TE::RED."The player you are looking for is not connected!");
   			return;
		}
		unset($args[0]);
		$reason = implode(" ", $args);
		$player->close("", TE::BOLD.TE::DARK_RED."You were kicked from our network".TE::RESET."\n".TE::LIGHT_PURPLE."Kicked By: ".TE::AQUA.$sender->getName()."\n".TE::LIGHT_PURPLE."Reason: ".TE::AQUA.$reason);
		$this->plugin->getServer()->broadcastMessage(Loader::KIDS.TE::BOLD.TE::GOLD.$player->getName().TE::RESET.TE::GRAY." was kicked from the server by ".TE::BOLD.TE::YELLOW.$sender->getName().TE::RESET.TE::GRAY." for the reason of ".TE::BOLD.TE::GOLD.$reason);
	}
}

?>