<?php

namespace AdvancedBan\ServerCommands;

use AdvancedBan\Loader;

use pocketmine\utils\TextFormat as TE;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Player;

class Clear extends PluginCommand { 
	
	/** @var Loader */
	protected $plugin;
	
	/**
	 * Clear Constructor.
	 * @parm Loader $plugin
	 */
	public function __construct(Loader $plugin){
		parent::__construct("clear", $plugin);
		$this->plugin = $plugin;
		$this->setDescription("Clean a user's inventory.!");
		$this->setPermission("clear.command.use");
	}
	
	/**
	 * @param CommandSender $sender
	 * @param String $commandLabel
	 * @param Array $args
	 * @return bool|mixed
	 */
	public function execute(CommandSender $sender, String $commandLabel, Array $args){ 
		if(!$sender instanceof Player){
			$sender->sendMessage(TE::RED."Use this command in the game!");
			return;
		}
		if(!$sender->hasPermission("clear.command.use")){
			$sender->sendMessage(TE::RED."You have not permissions to use this command!");
			return;
        }
		if(isset($args[0])){
        	$player = $this->plugin->getServer()->getPlayer($args[0]);
        	if($player != null){
        		$player->getInventory()->clearAll();
        		$player->getArmorInventory()->clearAll();
				$sender->removeAllEffects();
				$sender->sendMessage(TE::GRAY."You successfully emptied the inventory of: ".TE::AQUA.$player->getName());
        	}else{
        		$sender->sendMessage(TE::RED."The player you are looking for is not connected!");
        	}
        }else{
			$sender->getArmorInventory()->clearAll();
			$sender->getInventory()->clearAll();
			$sender->removeAllEffects();
			$sender->sendMessage(TE::GRAY."You successfully cleaned your inventory");
		}
	}
}