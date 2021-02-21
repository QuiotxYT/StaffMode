<?php

namespace AdvancedBan\ServerCommands;

use AdvancedBan\Loader;

use pocketmine\utils\TextFormat as TE;
use pocketmine\command\{CommandSender, PluginCommand};
use pocketmine\Player;

class TellCommand extends PluginCommand {
	
	/** @var Loader */
	protected $plugin;
	
	/**
	 * TellCommand Constructor.
	 * @param Loader $plugin
	 */
	public function __construct(Loader $plugin){
		parent::__construct("tell", $plugin);
		$this->plugin = $plugin;
	}
	
	/**
	 * @param CommandSender $sender
	 * @param String $commandLabel
	 * @param Array $args
	 * @return bool|mixed
	 */
	public function execute(CommandSender $sender, String $commandLabel, Array $args){
		if(!isset($args[0])||!isset($args[1])){
			$sender->sendMessage(TE::RED."Usage: /tell <playerName> <message>");
			return;
		}
		$player = $this->plugin->getServer()->getPlayer($args[0]);
		if($player === null){
			$sender->sendMessage(TE::RED."The player you are looking for does not exist");
			return;
		}
		unset($args[0]);
		$message = implode(" ", $args);
		if($player->getName() === $sender->getName()){
			$sender->sendMessage(TE::RED."You can't send me a message yourself");
			return;
		}
		$sender->sendMessage(TE::GRAY."(To ".$player->getName().TE::GRAY.")".TE::RESET." ".TE::WHITE.$message);
		$player->sendMessage(TE::GRAY."(From ".$sender->getName().TE::GRAY.")".TE::RESET." ".TE::WHITE.$message);
		foreach($this->plugin->getServer()->getOnlinePlayers() as $players){
			if($players->hasPermission("viewprivate.command.use")){
				$players->sendMessage(TE::GRAY."From ".$sender->getName()." To ".$player->getName().TE::RESET." ".TE::WHITE.$message);
			}
		}
	}
}

?>