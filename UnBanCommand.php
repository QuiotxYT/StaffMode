<?php

namespace AdvancedBan\ServerCommands;

use AdvancedBan\Loader;
use AdvancedBan\DataBase\Data;

use pocketmine\command\{CommandSender, PluginCommand};
use pocketmine\utils\{Config, TextFormat as TE};
use pocketmine\Player;

class UnBanCommand extends PluginCommand {
	
	/** @var Loader */
	protected $plugin;
	
	/**
	 * Ban Constructor.
	 * @param Loader $plugin
	 */
	public function __construct(Loader $plugin){
		$this->plugin = $plugin;
		parent::__construct("unban", $plugin);
		$this->setPermission("unban.command.use");
	}
	
	/**
	 * @param CommandSender $sender
	 * @param String $commandLabel
	 * @param Array $args
	 * @return bool|mixed
	 */
	public function execute(CommandSender $sender, String $commandLabel, Array $args){
		if(!$sender->hasPermission("unban.command.use")){
			$sender->sendMessage(TE::RED."You have not permissions to use this command");
			return;
		}
		if(!isset($args[0])){
			$sender->sendMessage(TE::RED."/unban [string: target]");
			return;
		}
		if(Data::isPermanentlyBanned($args[0])){
			Data::deleteBan($args[0], true);
			$this->plugin->getServer()->broadcastMessage(Loader::KIDS.TE::BOLD.TE::GOLD.$args[0].TE::RESET.TE::GRAY." was unbanned from the network, by the staff ".TE::BOLD.TE::YELLOW.$sender->getName());
		}
		elseif(Data::isTemporarilyBanned($args[0])){
			Data::deleteBan($args[0], false);
			$this->plugin->getServer()->broadcastMessage(Loader::KIDS.TE::BOLD.TE::GOLD.$args[0].TE::RESET.TE::GRAY." was unbanned from the network, by the staff ".TE::BOLD.TE::YELLOW.$sender->getName());
		}else{
			$sender->sendMessage(TE::RED.$args[0]." It was never banned from the server");
		}
	}
}

?>