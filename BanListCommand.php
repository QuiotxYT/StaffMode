<?php

namespace AdvancedBan\ServerCommands;

use AdvancedBan\Loader;

use pocketmine\utils\{Config, TextFormat as TE};
use pocketmine\command\{CommandSender, PluginCommand};
use pocketmine\Player;

class BanListCommand extends PluginCommand {
	
	/** @var Loader */
	protected $plugin;
	
	/**
	 * BanListCommand Constructor.
	 * @param Loader $plugin
	 */
	public function __construct(Loader $plugin){
		parent::__construct("banlist", $plugin);
		$this->plugin = $plugin;
		$this->setPermission("banlist.command.use");
	}
	
	/**
	 * @param CommandSender $sender
	 * @param String $commandLabel
	 * @param Array $args
	 * @return bool|mixed
	 */
	public function execute(CommandSender $sender, String $commandLabel, Array $args){
		if(!$sender->hasPermission("banlist.command.use")){
			$sender->sendMessage(TE::RED."You have not permission to use this command!");
			return;
		}
		if(!isset($args[0])){
			return;
		}
	}
}

?>