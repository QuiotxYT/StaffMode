<?php

namespace AdvancedBan\ServerCommands;

use AdvancedBan\Loader;

use pocketmine\utils\{Config, TextFormat as TE};
use pocketmine\command\{CommandSender, PluginCommand};
use pocketmine\Player;

class PingCommand extends PluginCommand {
	
	/** @var Loader */
	protected $plugin;
	
	/**
	 * PingCommand Constructor.
	 * @param Loader $plugin
	 */
	public function __construct(Loader $plugin){
		parent::__construct("ping", $plugin);
		$this->setDescription("/ping [string: target]");
		$this->plugin = $plugin;
	}
	
	public function execute(CommandSender $sender, string $label, array $args){
		if(!$sender instanceof Player){
			$sender->sendMessage(TE::RED."Use this command in the game!");
			return;
		}
		if(isset($args[0])){
			$jug = $sender->getServer()->getPlayer($args[0]);
			if($jug != null){
				unset($args[0]);
				$sender->sendMessage(TE::GRAY."Ping of the player ".TE::AQUA.$jug->getName().TE::GRAY." is of ".TE::AQUA.$jug->getPing().TE::GRAY." ms..!");
			}else{
				$sender->sendMessage(TE::RED."The player you are entering is not connected!");
			}
		}else{
			$sender->sendMessage(TE::GRAY."You ping player ".TE::AQUA.$sender->getName().TE::GRAY." is of ".TE::AQUA.$sender->getPing().TE::GRAY." ms..!");
		}
	}
}