<?php

namespace AdvancedBan;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\utils\{Config, TextFormat as TE};
use pocketmine\entity\Entity;

use AdvancedBan\DataBase\{
	Country, Discord, Data, Time,
};
use AdvancedBan\ServerCommands\{
	CommandsManager,
};
use AdvancedBan\provider\{
	YamlProvider,
};

class Loader extends PluginBase {
	
	/** @var PREFIX\String */
	const PREFIX = TE::BOLD.TE::YELLOW."Vital".TE::RED."HCF".TE::RESET.TE::GRAY." » ".TE::RESET;
	
	/** @var KIDS\String */
	const KIDS = TE::GRAY."[".TE::DARK_RED."AntiCheat".TE::GRAY."]".TE::GRAY." » ".TE::RESET;
	
	/** @var Loader */
	protected static $pluginLogger = null, $dataLogger = null, $provider = null, $mysql = null;
	
	/** @var array */
	public static $warns = [], $device = [], $staffmode = [], $freeze = [];
	
	/**
	 * @return void
	 */
	public static function init(){
		$commands = array("kick", "ban", "unban", "op", "deop", "tell", "msg", "w", "banlist");
		for($i = 0; $i < count($commands); $i++){
			self::removeCommand($commands[$i]);
		}
		self::getInstance()->getServer()->getPluginManager()->registerEvents(new EventListener(self::getInstance()), self::getInstance());
	}
	
	/**
	 * @return void
	 */
	public function onLoad(){
		//TODO:
		self::$pluginLogger = $this;
		self::$dataLogger = new Data($this);
	}
	
	/**
	 * @return void
	 */
	public function onEnable(){
		//TODO:
		self::init();

		YamlProvider::init();
		CommandsManager::init();

		$this->getLogger()->info(TE::GREEN."The plugin was turned on correctly!");
	}

	public function onDisable(){
		//TODO:
	}
	
	/**
	 * @param String $playerName
	 *@return bool
	 */
	public function isStaffMode(String $playerName) : bool {
		if(isset(self::$staffmode[$playerName])){
			return true;
		}else{
			return false;
		}
		return false;
	}
	
	/**
	 * @return Loader[] 
	 */
	public static function getInstance() : Loader {
		if(self::$pluginLogger === null){
			throw new \RuntimeException("Loader > Could not create instance of variable!");
		}
		return self::$pluginLogger;
	}

	/**
	 * @param String $configuration
	 */
	public static function getDataConfig($configuration){
		return self::getInstance()->getConfig()->get($configuration);
	}
	
	/**
	 * @param String $commamd
	 */
	public static function removeCommand(String $command){
		$commandMap = self::getInstance()->getServer()->getCommandMap();
		$cmd = $commandMap->getCommand($command);
		if($cmd === null){
			return;
		}
		$cmd->setLabel("");
		$cmd->unregister($commandMap);
	}
	
	/**
	 * @param Int $time
	 * @return String
	 */
	public static function getTimeToString(Int $time) : String {
		return gmdate("i:s", $time);
	}
	
	/**
	 * @param Int $time
	 * @return String
	 */
	public static function getTime(Int $time) : ?String {
		$remaning = $time - time();
		$h = $remaning % 86400;
		$m = $remaning % 3600;
		$s = $remaning % 60;
		/** @var float */
		$days = floor($remaning / 86400);
		$hours = floor($h / 3600);
		$minutes = floor($m / 60);
		$seconds = ceil($s);
		return "Days: ".$days.": "."hours: ".$hours.": "."minutes: ".$minutes.": "."seconds: ".$seconds;
	}
}

?>