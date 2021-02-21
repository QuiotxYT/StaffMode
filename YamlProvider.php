<?php

namespace AdvancedBan\provider;

use AdvancedBan\Loader;

use pocketmine\utils\{Config, TextFormat as TE};

class YamlProvider {

    /**
     * @param bool $config
     * @return void
     */
    public static function init(){
        if(Loader::getInstance()->getConfig()->get("configuration") === "true"){
            if(file_exists(Loader::getInstance()->getDataFolder()."config.yml")){
                unlink(Loader::getInstance()->getDataFolder()."config.yml");
            }
            @mkdir(Loader::getInstance()->getDataFolder()."players");
            Loader::getInstance()->saveResource("config.yml");
            Loader::$warns = (new Config(Loader::getInstance()->getDataFolder()."WarnsData.yml", Config::YAML))->getAll();
            Loader::getInstance()->getLogger()->info(TE::GREEN."All plugin configuration was loaded correctly");
        }else{
            Loader::getInstance()->getLogger()->alert(TE::RED."The plugin provider was not loaded, check the configuration!");
        }
    }
}

?>