<?php

namespace AdvancedBan;

use AdvancedBan\DataBase\Data;

use pocketmine\event\Listener;
use pocketmine\Player;
use pocketmine\math\Vector3;
use pocketmine\level\Position;
use pocketmine\utils\{Config, TextFormat as TE};
use pocketmine\item\{Item, ItemIds};

use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\network\mcpe\protocol\LoginPacket;

use pocketmine\event\inventory\InventoryPickupItemEvent;
use pocketmine\event\player\{PlayerPreLoginEvent, PlayerInteractEvent, PlayerMoveEvent, PlayerChatEvent, PlayerCommandPreprocessEvent};
use pocketmine\event\entity\{EntityDamageEvent, EntityDamageByEntityEvent};
use pocketmine\event\block\{BlockPlaceEvent, BlockBreakEvent};

class EventListener implements Listener {

    /** @var Loader */
    protected $plugin;

    /**
     * EventListener Constructor.
     * @param Loader $plugin
     */
    public function __construct(Loader $plugin){
        $this->plugin = $plugin;
    }

    /**
     * @param BlockBreakEvent $event
     * @return void
     */
    public function onBlockBreak(BlockBreakEvent $event) : void {
        $direction = $event->getPlayer()->getDirectionVector()->multiply(4);
        if($event->getPlayer()->getInventory()->getItemInHand()->getId() === ItemIds::COMPASS and isset(Loader::$staffmode[$event->getPlayer()->getName()])){
            $event->getPlayer()->teleport(Position::fromObject($event->getPlayer()->add($direction->getX(), $direction->getY(), $direction->getZ()), $event->getPlayer()->getLevel()));
            $event->setCancelled(true);
        }
    }

    /**
     * @param BlockPlaceEvent $event
     * @return void
     */
    public function onBlockPlace(BlockPlaceEvent $event) : void {
        if($event->getPlayer()->getInventory()->getItemInHand()->getId() === ItemIds::PACKED_ICE and isset(Loader::$staffmode[$event->getPlayer()->getName()])){
            $event->setCancelled(true);
        }
    }

    /**
     * @param PlayerPreLoginEvent $event
     * @return void
     */
    public function onPlayerPreLoginEvent(PlayerPreLoginEvent $event) : void {
        $playerName = $event->getPlayer()->getName();
        if(Data::isPermanentlyBanned($playerName)){
            $config = new Config(Loader::getInstance()->getDataFolder()."players_banneds.yml", Config::YAML);
			$result = $config->get($playerName);
            $event->getPlayer()->close("", TE::BOLD.TE::RED."You were banned from the network permanently".TE::RESET."\n".TE::GRAY."You were banned by: ".TE::AQUA.$result["sender_name"].TE::RESET."\n".TE::GRAY."Reason: ".TE::AQUA.$result["reason_of_ban"].TE::RESET."\n".TE::BLUE.TE::BOLD."Discord: ".TE::RESET.TE::AQUA."https://discord.gg/S5N6YaY%22");
        }
        if(Data::isTemporarilyBanned($playerName)){
            $config = new Config(Loader::getInstance()->getDataFolder()."players_timebanneds.yml", Config::YAML);
			$result = $config->get($playerName);
            if($result["time_ban"] > time()){
                $event->getPlayer()->close("", TE::BOLD.TE::RED."You were banned from the network temporarily".TE::RESET."\n".TE::GRAY."You were banned by: ".TE::AQUA.$result["sender_name"].TE::RESET."\n".TE::GRAY."Reason: ".TE::AQUA.$result["reason_of_ban"].TE::RESET."\n".TE::GRAY."Time left: ".TE::GREEN.Loader::getTime($result["time_ban"]).TE::RESET."\n".TE::BLUE.TE::BOLD."Discord: ".TE::RESET.TE::AQUA."https://discord.gg/S5N6YaY%22");
            }else{
                Data::deleteBan($playerName, false);
            }
        }
    }

    /**
     * @param PlayerInteractEvent $event
     * @return void
     */
    public function onPlayerInteractEvent(PlayerInteractEvent $event) : void {
        if($event->getAction() === PlayerInteractEvent::RIGHT_CLICK_AIR and isset(Loader::$staffmode[$event->getPlayer()->getName()])){
            if($event->getItem()->getId() === ItemIds::CLOCK){
                $players = [];
                foreach($this->plugin->getServer()->getOnlinePlayers() as $player){
                    $players[] = $player;
                }
                $event->getPlayer()->teleport($players[array_rand($players)]);
            }
            if($event->getItem()->getId() === ItemIds::DYE and $event->getItem()->getDamage() === 10){
                $event->getPlayer()->getInventory()->setItemInHand(Item::get(ItemIds::DYE, 1, 1)->setCustomName(TE::RED."Disable Vanish"));
                $event->getPlayer()->sendMessage(TE::GREEN."Vanish was activated!");
                foreach($this->plugin->getServer()->getOnlinePlayers() as $player){
                    $player->hidePlayer($event->getPlayer());
                }
            }
            if($event->getItem()->getId() === ItemIds::DYE and $event->getItem()->getDamage() === 1){
                $event->getPlayer()->getInventory()->setItemInHand(Item::get(ItemIds::DYE, 10, 1)->setCustomName(TE::GREEN."Enable Vanish"));
                $event->getPlayer()->sendMessage(TE::RED."Vanish was desactivated!");
                foreach($this->plugin->getServer()->getOnlinePlayers() as $player){
                    $player->showPlayer($event->getPlayer());
                }
            }
        }
    }

    /**
     * @param PlayerChatEvent $event
     * @return void
     */
    public function onPlayerChatEvent(PlayerChatEvent $event) : void {
        $playerName = $event->getPlayer()->getName();
        if($event->getPlayer()->hasPermission("expire.chat.command")) return;
        if(isset($this->spam[$playerName])){
        	if((time() - $this->spam[$playerName]) < 10){
        		$time = time() - $this->spam[$playerName];
        		$event->getPlayer()->sendMessage(TE::RED."You have to wait ".Loader::getTimeToString(10 - $time)." to write in the chat again!");
        		$event->setCancelled(true);
        	}else{
        		$this->spam[$playerName] = time();
        	}
        }else{
        	$this->spam[$playerName] = time();
        }
    }

    /**
     * @param PlayerMoveEvent $event
     * @return void
     */
    public function onPlayerMoveEvent(PlayerMoveEvent $event) : void {
        if(isset(Loader::$freeze[$event->getPlayer()->getName()])){
            $event->getPlayer()->addTitle(TE::RED."YOU ARE FROZEN");
            $event->setCancelled(true);
        }
    }
    
    /**
     * @param PlayerCommandPreprocessEvent $event
     * @return void
     */
    public function onPlayerCommandPreprocessEvent(PlayerCommandPreprocessEvent $event) : void {
    	$player = $event->getPlayer();
    	$command = explode(" ", $event->getMessage());
    	foreach(Loader::getDataConfig("commands_block") as $block){
    		if($command[0] === "/".$block||$command[0] === "./".$block){
    			$event->setCancelled(true);
    		}
    	}
    }
    
    /**
     * @param DataPacketReceiveEvent $event
     * @return void
     */
    public function onDataPacketReceiveEvent(DataPacketReceiveEvent $event) : void {
    	$player = $event->getPlayer();
    	$packet = $event->getPacket();
    	if($packet instanceof LoginPacket && $player instanceof Player){
    		Loader::$device[$packet->username] = $packet->clientData["DeviceOS"];
    	}
    }

    /**
     * @param EntityDamageEvent $event
     * @return void
     */
    public function onEntityDamageEvent(EntityDamageEvent $event) : void {
        if($event instanceof EntityDamageByEntityEvent){
            if($event->getCause() === EntityDamageEvent::CAUSE_ENTITY_ATTACK and $event->getDamager()->getInventory()->getItemInHand()->getId() === ItemIds::PACKED_ICE and isset(Loader::$staffmode[$event->getDamager()->getName()])){
                $event->setCancelled(true);
                if(!isset(Loader::$freeze[$event->getEntity()->getName()])){
                    Loader::$freeze[$event->getEntity()->getName()] = $event->getEntity();
                    $this->plugin->getServer()->broadcastMessage(Loader::PREFIX.TE::BOLD.TE::GOLD.$event->getEntity()->getName().TE::RESET.TE::GRAY." was frozen by ".TE::BOLD.TE::YELLOW.$event->getDamager()->getName());
                }else{
                    unset(Loader::$freeze[$event->getEntity()->getName()]);
                    $this->plugin->getServer()->broadcastMessage(Loader::PREFIX.TE::BOLD.TE::GOLD.$event->getEntity()->getName().TE::RESET.TE::GRAY." was unfrozen by ".TE::BOLD.TE::YELLOW.$event->getDamager()->getName());
                }
            }
        }
    }
}

?>