<?php

namespace AdvancedBan\ServerCommands;

use AdvancedBan\Loader;

use pocketmine\utils\{Config, TextFormat as TE};
use pocketmine\command\{CommandSender, PluginCommand};
use pocketmine\Player;

class StaffChat extends PluginCommand {
	
	/** @var Loader */
	protected $plugin;
	
	/**
	 * Mute Constructor.
	 * @param Loader $plugin
	 */
	public function __construct(Loader $plugin){
		parent::__construct("sc", $plugin);
		$this->plugin = $plugin;
		$this->setDescription("/sc [string: message]");
		$this->setPermission("sc.command.use");
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
        if(!isset($args[0])){
        	$sender->sendMessage(TE::RED."Usage: /sc [string: message]");
        	return;
        }
        if(!$sender->hasPermission("sc.command.use")){
			$sender->sendMessage(TE::RED."You have not permissions to use this command");
			return;
        }
        foreach($this->plugin->getServer()->getOnlinePlayers() as $player){
        	if($player->hasPermission("sc.command.use")){
        		$player->sendMessage(TE::BLUE."[StaffChat]".TE::RESET." ".TE::DARK_AQUA."[".$player->getLevel()->getName()."]".TE::RESET." ".TE::AQUA.$sender->getName().TE::GRAY.": ".TE::YELLOW.implode(" ", $args));
        	}
        }
    }
}