<?php

namespace AdvancedBan\DataBase;

use AdvancedBan\Loader;
use pocketmine\Player;

class Country {
	
	/** 
	 * @param Player $player
	 * @return String
	 */
	public static function getCountry(Player $player) : ?String {
		$ip = $player->getAddress();
		$http = file_get_contents('http://www.geoplugin.net/json.gp?ip='.$ip);
		$handle = json_decode($http);
		return $handle->geoplugin_countryName;
	}
}