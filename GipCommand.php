<?php

namespace AdvancedBan\ServerCommands;

use AdvancedBan\Loader;
use AdvancedBan\DataBase\Country;

use pocketmine\utils\{Config, TextFormat as TE};
use pocketmine\command\{CommandSender, PluginCommand};
use pocketmine\Player;

class GipCommand extends PluginCommand {
	
	/** @var Loader */
	protected $plugin;
	
	/**
	 * GipCommand Constructor.
	 * @param Main $plugin
	 */
	public function __construct(Loader $plugin){
		parent::__construct("gip", $plugin);
		$this->plugin = $plugin;
		$this->setPermission("gip.command.use");
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
		if(!isset($args[0])){
			$sender->sendMessage(TE::RED."Usage: /gip [string: target]");
			return;
		}
		if(!$sender->hasPermission("gip.command.use")){
			$sender->sendMessage(TE::RED."You have not permissions to use this command!");
			return;
        }
        $player = $this->plugin->getServer()->getPlayer($args[0]);
		if($player != null){
			$sender->sendMessage(TE::GRAY."The players ".TE::LIGHT_PURPLE.$player->getName().TE::GRAY." is playing from the country of ".TE::LIGHT_PURPLE.Country::getCountry($player));
		}
		else{
			$sender->sendMessage(TE::RED."The player you are logged in is not connected!");
		}
	}
}