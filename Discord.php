<?php

namespace AdvancedBan\DataBase;

use AdvancedBan\Loader;
use pocketmine\Server;

class Discord {
	
	/** @var string */
	public $webhook;
	/** @var string */
	public $username;
	
	/**
	 * @param String $hook
	 * @param String $title
	 * @param String $message
	 */
	public static function sendToDiscord(String $hook, String $title, String $message){
		$discord = curl_init();
		curl_setopt($discord, CURLOPT_URL, $hook);
		curl_setopt($discord, CURLOPT_POSTFIELDS, json_encode(["content" => $message, "username" => $title]));
		curl_setopt($discord, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
		curl_setopt($discord, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($discord, CURLOPT_SSL_VERIFYPEER, false);
        curl_exec($discord);
        curl_error($discord);
	}
}