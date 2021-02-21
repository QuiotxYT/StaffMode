<?php

namespace AdvancedBan\ServerCommands;

use AdvancedBan\Loader;
use AdvancedBan\DataBase\{Data, Discord};
use AdvancedBan\provider\MysqlProvider;

use pocketmine\command\{CommandSender, PluginCommand};
use pocketmine\utils\{Config, TextFormat as TE};
use pocketmine\Player;

class HistoryCommand extends PluginCommand {
	
	/** @var Loader */
	protected $plugin;
	
	/**
	 * HistoryCommand Constructor.
	 * @param Loader $plugin
	 */
	public function __construct(Loader $plugin){
		parent::__construct("history", $plugin);
		$this->plugin = $plugin;
		$this->setPermission("history.command.use");
	}
	
	/**
     * @param CommandSender $sender
     * @param String $commandLabel
     * @param Array $args
     * @return bool|mixed
     */
	public function execute(CommandSender $sender, String $commandLabel, Array $args){
        if(!$sender->hasPermission("history.command.use")){
        	$sender->sendMessage(TE::RED."You have not permissions to use this command");
        	return;
        }
        if(!isset($args[0])){
        	$sender->sendMessage(TE::RED."There are not enough arguments!");
        	return;
       }
       if($args[0] === "checkban"){	
			if(!$sender->hasPermission("history.command.use")){
				$sender->sendMessage(TE::RED."You have not permissions to use this command");
				return;
			}
			if(!isset($args[1])){
				$sender->sendMessage(TE::RED."Not enough arguments!");
				return;
			}
			if($this->plugin->getServer()->getPlayer($args[1]) instanceof Player){
				if(!Data::isPermanentlyBanned($this->plugin->getServer()->getPlayer($args[1])->getName())){
					$sender->sendMessage(TE::RED.$this->plugin->getServer()->getPlayer($args[1])->getName()." does not exist in our database!");
					return;
				}
				$config = new Config(Loader::getInstance()->getDataFolder()."players_banneds.yml", Config::YAML);
				$result = $config->get($this->plugin->getServer()->getPlayer($args[1])->getName());
				$sender->sendMessage(Loader::KIDS.TE::BLUE."[".$result["date"]."]".TE::RESET." ".TE::GOLD.$this->plugin->getServer()->getPlayer($args[1])->getName().TE::GRAY." was banned from the server for the reason: ".TE::GOLD.$result["reason_of_ban"].TE::GRAY." Banned By: ".TE::GOLD.$result["sender_name"]);
			}else{
				if(!Data::isPermanentlyBanned($args[1])){
					$sender->sendMessage(TE::RED.$args[1]." does not exist in our database!");
					return;
				}
				$config = new Config(Loader::getInstance()->getDataFolder()."players_banneds.yml", Config::YAML);
				$result = $config->get($args[1]);
				$sender->sendMessage(Loader::KIDS.TE::BLUE."[".$result["date"]."]".TE::RESET." ".TE::GOLD.$args[1].TE::GRAY." was banned from the server for the reason: ".TE::GOLD.$result["reason_of_ban"].TE::GRAY." Banned By: ".TE::GOLD.$result["sender_name"]);
			}
		}
		elseif($args[0] === "checktban"){	
			if(!$sender->hasPermission("history.command.use")){
				$sender->sendMessage(TE::RED."You have not permissions to use this command");
				return;
			}
			if($this->plugin->getServer()->getPlayer($args[1]) instanceof Player){
				if(!Data::isTemporarilyBanned($this->plugin->getServer()->getPlayer($args[1])->getName())){
					$sender->sendMessage(TE::RED.$this->plugin->getServer()->getPlayer($args[1])->getName()." does not exist in our database!");
					return;
				}
				$config = new Config(Loader::getInstance()->getDataFolder()."players_timebanneds.yml", Config::YAML);
				$result = $config->get($this->plugin->getServer()->getPlayer($args[1])->getName());
				$sender->sendMessage(Loader::KIDS.TE::BLUE."[".$result["date"]."]".TE::RESET." ".TE::GOLD.$this->plugin->getServer()->getPlayer($args[1])->getName().TE::GRAY." was banned from the server for the reason: ".TE::GOLD.$result["reason_of_ban"].TE::GRAY." Banned By: ".TE::GOLD.$result["sender_name"].TE::GRAY." time left: ".TE::GOLD.Loader::getTime($result["time_ban"]));
			}else{
				if(!Data::isTemporarilyBanned($args[1])){
					$sender->sendMessage(TE::RED.$args[1]." does not exist in our database!");
					return;
				}
				$config = new Config(Loader::getInstance()->getDataFolder()."players_timebanneds.yml", Config::YAML);
				$result = $config->get($args[1]);
				$sender->sendMessage(Loader::KIDS.TE::BLUE."[".$result["date"]."]".TE::RESET." ".TE::GOLD.$args[1].TE::GRAY." was banned from the server for the reason: ".TE::GOLD.$result["reason_of_ban"].TE::GRAY." Banned By: ".TE::GOLD.$result["sender_name"].TE::GRAY." time left: ".TE::GOLD.Loader::getTime($result["time_ban"]));
			}
		}
		elseif($args[0] === "checkmute"){
			if(!$sender->hasPermission("history.command.use")){
				$sender->sendMessage(TE::RED."You have not permissions to use this command");
				return;
			}
			if($this->plugin->getServer()->getPlayer($args[1]) instanceof Player){
				if(!Data::isPermanentlyMuted($this->plugin->getServer()->getPlayer($args[1])->getName())){
					$sender->sendMessage(TE::RED.$this->plugin->getServer()->getPlayer($args[1])->getName()." does not exist in our database!");
					return;
				}
				$connection = MysqlProvider::getDataBase()->query("SELECT * FROM players_data_mute WHERE player_name = '{$this->plugin->getServer()->getPlayer($args[1])->getName()}';");
		    	$result = $connection->fetch_array(MYSQLI_ASSOC);
				$sender->sendMessage(Loader::KIDS.TE::BLUE."[".$result["date"]."]".TE::RESET." ".TE::GOLD.$this->plugin->getServer()->getPlayer($args[1])->getName().TE::GRAY." was silenced from the server for the reason: ".TE::GOLD.$result["reason_of_mute"].TE::GRAY." Silenced By: ".TE::GOLD.$result["sender_name"]);
				$connection->close();
			}else{
				if(!Data::isPermanentlyMuted($this->plugin->getServer()->getOfflinePlayer($args[1])->getName())){
					$sender->sendMessage(TE::RED.$this->plugin->getServer()->getOfflinePlayer($args[1])->getName()." does not exist in our database!");
					return;
				}
				$connection = MysqlProvider::getDataBase()->query("SELECT * FROM players_data_mute WHERE player_name = '{$this->plugin->getServer()->getOfflinePlayer($args[1])->getName()}';");
		    	$result = $connection->fetch_array(MYSQLI_ASSOC);
				$sender->sendMessage(Loader::KIDS.TE::BLUE."[".$result["date"]."]".TE::RESET." ".TE::GOLD.$this->plugin->getServer()->getOfflinePlayer($args[1])->getName().TE::GRAY." was silenced from the server for the reason: ".TE::GOLD.$result["reason_of_mute"].TE::GRAY." Silenced By: ".TE::GOLD.$result["sender_name"]);
				$connection->close();

			}
		}
		elseif($args[0] === "checktmute"){	
			if(!$sender->hasPermission("history.command.use")){
				$sender->sendMessage(TE::RED."You have not permissions to use this command");
				return;
			}
			if($this->plugin->getServer()->getPlayer($args[1]) instanceof Player){
				if(!Data::isTemporarilyBanned($this->plugin->getServer()->getPlayer($args[1])->getName())){
					$sender->sendMessage(TE::RED.$this->plugin->getServer()->getPlayer($args[1])->getName()." does not exist in our database!");
					return;
				}
				$connection = MysqlProvider::getDataBase()->query("SELECT * FROM players_data_timemute WHERE player_name = '{$this->plugin->getServer()->getPlayer($args[1])->getName()}';");
		    	$result = $connection->fetch_array(MYSQLI_ASSOC);
				$sender->sendMessage(Loader::KIDS.TE::BLUE."[".$result["date"]."]".TE::RESET." ".TE::GOLD.$this->plugin->getServer()->getPlayer($args[1])->getName().TE::GRAY." was banned from the server for the reason: ".TE::GOLD.$result["reason_of_ban"].TE::GRAY." Silenced By: ".TE::GOLD.$result["sender_name"].TE::GRAY." time left: ".TE::GOLD.Loader::getTime($result["time_mute"]));
				$connection->close();
			}else{
				if(!Data::isTemporarilyBanned($this->plugin->getServer()->getOfflinePlayer($args[1])->getName())){
					$sender->sendMessage(TE::RED.$this->plugin->getServer()->getOfflinePlayer($args[1])->getName()." does not exist in our database!");
					return;
				}
				$connection = MysqlProvider::getDataBase()->query("SELECT * FROM players_data_timemute WHERE player_name = '{$this->plugin->getServer()->getOfflinePlayer($args[1])->getName()}';");
		    	$result = $connection->fetch_array(MYSQLI_ASSOC);
				$sender->sendMessage(Loader::KIDS.TE::BLUE."[".$result["date"]."]".TE::RESET." ".TE::GOLD.$this->plugin->getServer()->getOfflinePlayer($args[1])->getName().TE::GRAY." was banned from the server for the reason: ".TE::GOLD.$result["reason_of_ban"].TE::GRAY." Silenced By: ".TE::GOLD.$result["sender_name"].TE::GRAY." time left: ".TE::GOLD.Loader::getTime($result["time_mute"]));
				$connection->close();

			}
		}
		elseif($args[0] === "checkwarn"){
			if(!$sender->hasPermission("history.command.use")){
				$sender->sendMessage(TE::RED."You have not permissions to use this command");
				return;
			}
			$config = new Config($this->plugin->getDataFolder()."WarnsData.yml", Config::YAML);
			if($this->plugin->getServer()->getPlayer($args[1]) instanceof Player){
				$count = 0;
				if(!$config->exists($this->plugin->getServer()->getPlayer($args[1])->getName())){
					$sender->sendMessage(TE::RED.$this->plugin->getServer()->getPlayer($args[1])->getName()." does not exist in our database!");
					return;
				}
				if(count($config->get($this->plugin->getServer()->getPlayer($args[1])->getName())) < 1){
					$sender->sendMessage(TE::RED.$this->plugin->getServer()->getPlayer($args[1])->getName()." Not have no recent warnings");
					return;
				}
				$tempData = $config->get($this->plugin->getServer()->getPlayer($args[1])->getName());
				foreach($tempData as $result){
					if($count !== 0){
						$sender->sendMessage(Loader::KIDS.TE::BLUE."[".$result["date"]."]".TE::RESET." ".TE::GOLD.$this->plugin->getServer()->getPlayer($args[1])->getName().TE::GRAY." was warned from the server for the reason: ".TE::GOLD.$result["reason"].TE::GRAY." Warned By: ".TE::GOLD.$result["warned_by"].TE::GRAY." Number of this warning: ".TE::GOLD.$count);
					}
					$count++;
				}
			}else{
				$count = 0;
				if(!$config->exists($args[1])){
					$sender->sendMessage(TE::RED.$args[1]." does not exist in our database!");
					return;
				}
				if(count($config->get($args[1])) < 1){
					$sender->sendMessage(TE::RED.$args[1]." not have no recent warnings");
					return;
				}
				$tempData = $config->get($args[1]);
				foreach($tempData as $result){
					if($count !== 0){
						$sender->sendMessage(Loader::KIDS.TE::BLUE."[".$result["date"]."]".TE::RESET." ".TE::GOLD.$args[1].TE::GRAY." was warned from the server for the reason: ".TE::GOLD.$result["reason"].TE::GRAY." Warned By: ".TE::GOLD.$result["warned_by"].TE::GRAY." Number of this warning: ".TE::GOLD.$count);
					}
					$count++;
				}
			}
	    }else{
			$sender->sendMessage(TE::RED."Arguments for commands you enter do not exist!");
		}
    }
}

?>