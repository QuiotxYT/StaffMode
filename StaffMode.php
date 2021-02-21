<?php

namespace AdvancedBan\ServerCommands;

use AdvancedBan\Loader;

use pocketmine\command\{CommandSender, PluginCommand};
use pocketmine\utils\TextFormat as TE;
use pocketmine\Player;
use pocketmine\item\{Item, ItemIds};

class StaffMode extends PluginCommand {

    /** @var Loader */
    protected $plugin;

    /**
	 * StaffMode Constructor.
	 * @param Loader $plugin
	 */
	public function __construct(Loader $plugin){
        parent::__construct("mod", $plugin);
		$this->plugin = $plugin;
		$this->setPermission("mod.command.use");
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
        if(!$sender->hasPermission("mod.command.use")){
        	$sender->sendMessage(TE::RED."You have not permissions to use this command");
        	return;
        }
        if(!isset(Loader::$staffmode[$sender->getName()])){
            Loader::$staffmode[$sender->getName()] = $sender;
            $sender->setGamemode(Player::CREATIVE);
            $sender->getInventory()->clearAll();
            $sender->getArmorInventory()->clearAll();
            $sender->getInventory()->setItem(0, Item::get(ItemIds::PACKED_ICE, 0, 1)->setCustomName(TE::AQUA."Freeze"));
            $sender->getInventory()->setItem(1, Item::get(ItemIds::COMPASS, 0, 1)->setCustomName(TE::YELLOW."Teleporter"));
            $sender->getInventory()->setItem(2, Item::get(ItemIds::CLOCK, 0, 1)->setCustomName(TE::AQUA."Random player"));
            $sender->getInventory()->setItem(7, Item::get(ItemIds::DYE, 1, 1)->setCustomName(TE::RED."Disable Vanish"));
            foreach($this->plugin->getServer()->getOnlinePlayers() as $player){
                $player->hidePlayer($sender);
            }
            foreach($this->plugin->getServer()->getOnlinePlayers() as $player){
                if($player->hasPermission("mod.command.use")){
                    $player->sendMessage(TE::BLUE."[Staff]".TE::RESET." ".TE::DARK_AQUA."[".$sender->getLevel()->getName()."]".TE::RESET." ".TE::AQUA.$sender->getName().TE::GRAY.": ".TE::GREEN."active staff mode!");
                }
            }
            $sender->sendMessage(TE::GREEN."You activated staff mode correctly!");
        }else{
            unset(Loader::$staffmode[$sender->getName()]);
            $sender->setGamemode(Player::SURVIVAL);
            $sender->getInventory()->clearAll();
            $sender->getArmorInventory()->clearAll();
            foreach($this->plugin->getServer()->getOnlinePlayers() as $player){
                $player->showPlayer($sender);
            }
            foreach($this->plugin->getServer()->getOnlinePlayers() as $player){
                if($player->hasPermission("mod.command.use")){
                    $player->sendMessage(TE::BLUE."[Staff]".TE::RESET." ".TE::DARK_AQUA."[".$sender->getLevel()->getName()."]".TE::RESET." ".TE::AQUA.$sender->getName().TE::GRAY.": ".TE::RED."desactive staff mode!");
                }
            }
            $sender->sendMessage(TE::RED."You deactivated staff mode correctly!");
        }
    }
}

?>