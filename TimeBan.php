<?php

namespace AdvancedBan\ServerCommands;

use AdvancedBan\Loader;
use AdvancedBan\DataBase\{Data, Time, Discord};

use pocketmine\item\{Item, ItemIds};
use pocketmine\command\{CommandSender, PluginCommand};
use pocketmine\utils\TextFormat as TE;
use pocketmine\Player;

class TimeBan extends PluginCommand {
	
	/** @var Loader */
	protected $plugin;
	
	/**
	 * TimeBan Constructor.
	 * @param Loader $plugin
	 */
	public function __construct(Loader $plugin){
		parent::__construct("tban", $plugin);
		$this->plugin = $plugin;
		$this->setPermission("tban.command.use");
	}
	
	/**
	 * @param CommandSender $sender
	 * @param String $commandLabel
	 * @param Array $args
	 * @return bool|mixed
	 */
	public function execute(CommandSender $sender, String $commandLabel, Array $args){
		if(!$sender->hasPermission("tban.command.use")){
			$sender->sendMessage(TE::RED."You have not permissions to use this command");
			return;
		}
		if(!isset($args[0])||!isset($args[1])){
			$sender->sendMessage(TE::RED."Usage: /tban [string: target] [int: time] [string: reason]");
			return;
		}
		if(!in_array(Time::intToString($args[1]), Time::VALID_FORMATS)){
			$sender->sendMessage(TE::RED."The time format you enter is invalid!");
			return;
		}
		if($this->plugin->getServer()->getPlayer($args[0]) instanceof Player){
			if(Data::isTemporarilyBanned($this->plugin->getServer()->getPlayer($args[0])->getName())){
				$sender->sendMessage(TE::RED."{$this->plugin->getServer()->getPlayer($args[0])->getName()} already banned from the network!");
				return;
			}
			$argument = implode(" ", $args);
			$exploded = explode(" ", $argument);
			//TODO:
			unset($exploded[0]);
			unset($exploded[1]);
			$reason = implode(" ", $exploded);
			
			Data::addBan($this->plugin->getServer()->getPlayer($args[0])->getName(), $reason, $sender->getName(), false, Time::getFormatTime(Time::stringToInt($args[1]), $args[1]));
			$this->plugin->getServer()->broadcastMessage(Loader::PREFIX.TE::BOLD.TE::GOLD.$this->plugin->getServer()->getPlayer($args[0])->getName().TE::RESET.TE::GRAY." was temporarily banned of the network by ".TE::BOLD.TE::YELLOW.$sender->getName().TE::RESET.TE::GRAY." for the reason of ".TE::BOLD.TE::GOLD.$reason.TE::RESET);
			$this->plugin->getServer()->getPlayer($args[0])->close("", TE::BOLD.TE::RED."You were banned from the server temporarily".TE::RESET."\n".TE::GRAY."You were banned by: ".TE::AQUA.$sender->getName().TE::RESET."\n".TE::GRAY."Reason: ".TE::AQUA.$reason.TE::RESET."\n".TE::GRAY."Date: ".TE::AQUA.date("d/m/y H:i:s").TE::RESET."\n".TE::BLUE."Discord: ".TE::AQUA."https://discord.gg/S5N6YaY");
		}else{
			if(Data::isTemporarilyBanned($args[0])){
				$sender->sendMessage(TE::RED."{$args[0]} already banned from the network!");
				return;
			}
			$argument = implode(" ", $args);
			$exploded = explode(" ", $argument);
			//TODO:
			unset($exploded[0]);
			unset($exploded[1]);
			$reason = implode(" ", $exploded);

			Data::addBan($args[0], $reason, $sender->getName(), false, Time::getFormatTime(Time::stringToInt($args[1]), $args[1]));
			$this->plugin->getServer()->broadcastMessage(Loader::PREFIX.TE::BOLD.TE::GOLD.$args[0].TE::RESET.TE::GRAY." was temporarily banned of the network by ".TE::BOLD.TE::YELLOW.$sender->getName().TE::RESET.TE::GRAY." for the reason of ".TE::BOLD.TE::GOLD.$reason.TE::RESET);
		}
	}
}


?>