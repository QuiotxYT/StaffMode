<?php

namespace AdvancedBan\ServerCommands;

use AdvancedBan\Loader;
use AdvancedBan\DataBase\Data;

use pocketmine\command\{CommandSender, PluginCommand};
use pocketmine\utils\{Config, TextFormat as TE};
use pocketmine\Player;

class WarnCommand extends PluginCommand {

    /** @var Loader */
    protected $plugin;
    
    /**
     * WarnCommand Constructor.
     * @param Loader $plugin
     */
    public function __construct(Loader $plugin){
        parent::__construct("warn", $plugin);
        $this->plugin = $plugin;
		$this->setPermission("warn.command.use");
    }

    /**
     * @param CommandSender $sender
     * @param String $commandLabel
     * @param Array $args
     * @return bool|mixed
     */
	public function execute(CommandSender $sender, String $commandLabel, Array $args){
        if(!$sender->hasPermission("warn.command.use")){
            $sender->sendMessage(TE::RED."You have not permissions to use this command");
            return;
        }
        if(!isset($args[0])){
            $sender->sendMessage(TE::RED."Usage: /warn [string: target] [string: reason]");
            return;
        }
        if($args[0] === "remove"||$args[0] === "delete"){
            if($this->plugin->getServer()->getPlayer($args[1]) instanceof Player){
                Data::deleteWarn($this->plugin->getServer()->getPlayer($args[1])->getName(), $sender, true);
                $sender->sendMessage(TE::GRAY."The last warning of ".TE::BOLD.TE::GOLD.$this->plugin->getServer()->getPlayer($args[1])->getName().TE::RESET.TE::GRAY." was removed from the server!");
            }else{
                Data::deleteWarn($args[1], $sender, true);
                $sender->sendMessage(TE::GRAY."The last warning of ".TE::BOLD.TE::GOLD.$args[1].TE::RESET.TE::GRAY." was removed from the server!");
            }
        }else{
            if($this->plugin->getServer()->getPlayer($args[0]) instanceof Player){
                $argument = implode(" ", $args);
                $exploded = explode(" ", $argument);
                //TODO:
                unset($exploded[0]);
                $reason = implode(" ", $exploded);
                Data::registerWarn($this->plugin->getServer()->getPlayer($args[0])->getName(), $sender->getName(), $reason);
                $this->plugin->getServer()->broadcastMessage(Loader::KIDS.TE::BOLD.TE::GOLD.$this->plugin->getServer()->getPlayer($args[0])->getName().TE::RESET.TE::GRAY." was warned by ".TE::BOLD.TE::YELLOW.$sender->getName().TE::RESET.TE::GRAY." reason ".TE::BOLD.TE::GOLD.$reason);
                $sender->sendMessage(Loader::KIDS.TE::GRAY."You correctly warned ".TE::BOLD.TE::GOLD.$this->plugin->getServer()->getPlayer($args[0])->getName().TE::RESET.TE::GRAY." for the reason of ".TE::BOLD.TE::GOLD.$reason);
            }else{
                $argument = implode(" ", $args);
                $exploded = explode(" ", $argument);
                //TODO:
                unset($exploded[0]);
                $reason = implode(" ", $exploded);
                Data::registerWarn($args[0], $sender->getName(), $reason);
                $this->plugin->getServer()->broadcastMessage(Loader::KIDS.TE::BOLD.TE::GOLD.$this->plugin->getServer()->getOfflinePlayer($args[0])->getName().TE::RESET.TE::GRAY." was warned by ".TE::BOLD.TE::YELLOW.$sender->getName().TE::RESET.TE::GRAY." reason ".TE::BOLD.TE::GOLD.$reason);
                $sender->sendMessage(Loader::KIDS.TE::GRAY."You correctly warned ".TE::BOLD.TE::GOLD.$this->plugin->getServer()->getOfflinePlayer($args[0])->getName().TE::RESET.TE::GRAY." for the reason of ".TE::BOLD.TE::GOLD.$reason);
            }
        }
    }
}

?>