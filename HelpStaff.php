<?php

namespace AdvancedBan\ServerCommands;

use AdvancedBan\Loader;
use AdvancedBan\DataBase\Discord;

use pocketmine\utils\{Config, TextFormat as TE};
use pocketmine\command\{CommandSender, PluginCommand};
use pocketmine\Player;

class HelpStaff extends PluginCommand {
	
	/** @var Loader */
	protected $plugin;
	
	/**
	 * HelpStaff Constructor.
	 * @param Loader $plugin
	 */
	public function __construct(Loader $plugin){
		parent::__construct("request", $plugin);
		$this->plugin = $plugin;
		$this->setDescription("/helpop:request [string: args]");
        $this->setAliases(["helpop"]);
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
            $sender->sendMessage(TE::RED."Usage: /request <what you need> or /helpop <what you need>");
            return;
        }
        $reason = implode(" ", $args);
        $sender->sendMessage(TE::GREEN."Request help correctly, wait for the staffs!");
        foreach($this->plugin->getServer()->getOnlinePlayers() as $player){
			if($player->hasPermission("report.command.use")){
                $player->sendMessage(TE::BOLD.TE::DARK_PURPLE.$sender->getName().TE::RESET.TE::AQUA." is requesting help for the reason: ".TE::LIGHT_PURPLE.$reason);
            }
        }
    }
}