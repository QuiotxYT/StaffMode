<?php

namespace AdvancedBan\ServerCommands;

use AdvancedBan\Loader;
use AdvancedBan\DataBase\Data;

use pocketmine\utils\{Config, TextFormat as TE};
use pocketmine\command\{CommandSender, PluginCommand};
use pocketmine\Player;

class UnMute extends PluginCommand {
	
	/** @var Loader */
	protected $plugin;
	
	/**
	 * Mute Constructor.
	 * @param Loader $plugin
	 */
	public function __construct(Loader $plugin){
		parent::__construct("unmute", $plugin);
		$this->plugin = $plugin;
		$this->setPermission("unmute.command.use");
	}
	
	/**
     * @param CommandSender $sender
     * @param String $commandLabel
     * @param Array $args
     * @return bool|mixed
     */
	public function execute(CommandSender $sender, String $commandLabel, Array $args){
		if(!$sender->hasPermission("unmute.command.use")){
			$sender->sendMessage(TE::RED."You have not permissions to use this command");
			return;
		}
		if(!isset($args[0])){
			$sender->sendMessage(TE::RED."/unmute [string: target]");
			return;
		}
		if(Data::isPermanentlyMuted($this->plugin->getServer()->getOfflinePlayer($args[0])->getName())){
			Data::deleteMute($this->plugin->getServer()->getOfflinePlayer($args[0])->getName(), true);
			$this->plugin->getServer()->broadcastMessage(Loader::KIDS.TE::BOLD.TE::GOLD.$this->plugin->getServer()->getOfflinePlayer($args[0])->getName().TE::RESET.TE::GRAY." was unmuted from the network, by the staff ".TE::BOLD.TE::YELLOW.$sender->getName());
		}
		elseif(Data::isTemporarilyMuted($this->plugin->getServer()->getOfflinePlayer($args[0])->getName())){
			Data::deleteMute($this->plugin->getServer()->getOfflinePlayer($args[0])->getName(), false);
			$this->plugin->getServer()->broadcastMessage(Loader::KIDS.TE::BOLD.TE::GOLD.$this->plugin->getServer()->getOfflinePlayer($args[0])->getName().TE::RESET.TE::GRAY." was unmuted from the network, by the staff ".TE::BOLD.TE::YELLOW.$sender->getName());
		}else{
			$sender->sendMessage(TE::RED.$this->plugin->getServer()->getOfflinePlayer($args[0])->getName()." It was never muted from the server");
		}
	}
}