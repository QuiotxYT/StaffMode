<?php

namespace AdvancedBan\ServerCommands;

use AdvancedBan\Loader;

use pocketmine\utils\TextFormat as TE;
use pocketmine\Player;
use pocketmine\command\{ConsoleCommandSender, CommandSender, PluginCommand};

class OpCommand extends PluginCommand {
	
	/** @var Loader */
	protected $plugin;
	
	/**
	 * OpCommand Constructor.
	 * @param Loader $plugin
	 */
	public function __construct(Loader $plugin){
		parent::__construct("op", $plugin);
		$this->plugin = $plugin;
	}
	
	/**
	 * @param CommandSender $sender
	 * @param String $commandLabel
	 * @param Array $args
	 * @return bool|mixed
	 */
	public function execute(CommandSender $sender, String $commandLabel, Array $args){
		if(!$sender->isOp()){
			$sender->sendMessage(TE::RED."You don't have access to this type of command!");
			return;
		}
		if(!isset($args[0])){
			$sender->sendMessage(TE::RED."There are not enough arguments!");
			return;
		}
		if($args[0] == "add"){
			$string = implode(" ", $args);
			$name = explode(" ", $string);
			unset($name[0]);
			$op = implode(" ", $name);
			
			$player = $this->plugin->getServer()->getOfflinePlayer($op);
			$player->setOp(true);
			
			$playerName = $player->getName();
			$sender->sendMessage(TE::GREEN."You gave operator permissions to {$playerName}");
			if($player instanceof Player){
				$player->sendMessage(TE::GREEN."You are a new op member of the server!");
			}
		}
		elseif($args[0] == "remove"){
			$string = implode(" ", $args);
			$name = explode(" ", $string);
			unset($name[0]);
			$op = implode(" ", $name);
			
			$player = $this->plugin->getServer()->getOfflinePlayer($op);
			$player->setOp(false);
			
			$playerName = $player->getName();
			$sender->sendMessage(TE::GREEN."You removed the operator range to {$playerName}");
			if($player instanceof Player){
				$player->sendMessage(TE::GREEN."It seems that the operator rank was removed!");
			}
		}
	}
}

?>