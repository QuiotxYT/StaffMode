<?php

namespace AdvancedBan\ServerCommands;

use AdvancedBan\Loader;
use AdvancedBan\DataBase\{Data, Discord};

use pocketmine\utils\{Config, TextFormat as TE};
use pocketmine\command\{CommandSender, PluginCommand};
use pocketmine\Player;

class Mute extends PluginCommand {
	
	/** @var Loader */
	protected $plugin;
	
	/**
	 * Mute Constructor.
	 * @param Loader $plugin
	 */
	public function __construct(Loader $plugin){
		parent::__construct("mute", $plugin);
		$this->plugin = $plugin;
		$this->setPermission("mute.command.use");
	}   
	
	/**
     * @param CommandSender $sender
     * @param String $commandLabel
     * @param Array $args
     * @return bool|mixed
     */
	public function execute(CommandSender $sender, String $commandLabel, Array $args){
        if(!$sender->hasPermission("mute.command.use")){
        	$sender->sendMessage(TE::RED."You have not permissions to use this command");
        	return;
        }
        if(!isset($args[0])||!isset($args[1])){
        	$sender->sendMessage(TE::RED."Usage: /mute [string: target] [string: reason]");
        	return;
		}
		if($this->plugin->getServer()->getPlayer($args[0]) instanceof Player){
			if(Data::isPermanentlyMuted($this->plugin->getServer()->getPlayer($args[0])->getName())){
				$sender->sendMessage(TE::RED."{$this->plugin->getServer()->getPlayer($args[0])->getName()} is already muted from the network!");
				return;
			}
			$argument = implode(" ", $args);
			$exploded = explode(" ", $argument);
			//TODO:
			unset($exploded[0]);
			$reason = implode(" ", $exploded);
			
			Data::addMute($this->plugin->getServer()->getPlayer($args[0])->getName(), $reason, $sender->getName(), true);
			$this->plugin->getServer()->broadcastMessage(Loader::PREFIX.TE::BOLD.TE::GOLD.$this->plugin->getServer()->getPlayer($args[0])->getName().TE::RESET.TE::GRAY." was silenced from the network by ".TE::BOLD.TE::YELLOW.$sender->getName().TE::RESET.TE::GRAY." for the reason of ".TE::BOLD.TE::GOLD.$reason.TE::RESET);
		}else{
			if(Data::isPermanentlyMuted($this->plugin->getServer()->getOfflinePlayer($args[0])->getName())){
				$sender->sendMessage(TE::RED."{$this->plugin->getServer()->getOfflinePlayer($args[0])->getName()} is already muted from the network!");
				return;
			}
			$argument = implode(" ", $args);
			$exploded = explode(" ", $argument);
			//TODO:
			unset($exploded[0]);
			$reason = implode(" ", $exploded);

			Data::addMute($this->plugin->getServer()->getOfflinePlayer($args[0])->getName(), $reason, $sender->getName(), true);
			$this->plugin->getServer()->broadcastMessage(Loader::PREFIX.TE::BOLD.TE::GOLD.$this->plugin->getServer()->getOfflinePlayer($args[0])->getName().TE::RESET.TE::GRAY." was silenced from the network by ".TE::BOLD.TE::YELLOW.$sender->getName().TE::RESET.TE::GRAY." for the reason of ".TE::BOLD.TE::GOLD.$reason.TE::RESET);
		}
	}
}