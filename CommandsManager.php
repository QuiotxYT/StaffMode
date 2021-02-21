<?php

namespace AdvancedBan\ServerCommands;

use AdvancedBan\Loader;

class CommandsManager {

    /**
     * @return void
     */
    public static function init() : void {
        Loader::getInstance()->getServer()->getCommandMap()->register("/ban", new BanCommand(Loader::getInstance()));
		Loader::getInstance()->getServer()->getCommandMap()->register("/unban", new UnBanCommand(Loader::getInstance()));
		Loader::getInstance()->getServer()->getCommandMap()->register("/op", new OpCommand(Loader::getInstance()));
		Loader::getInstance()->getServer()->getCommandMap()->register("/gip", new GipCommand(Loader::getInstance()));
		Loader::getInstance()->getServer()->getCommandMap()->register("/report", new Report(Loader::getInstance()));
		Loader::getInstance()->getServer()->getCommandMap()->register("/clear", new Clear(Loader::getInstance()));
		Loader::getInstance()->getServer()->getCommandMap()->register("/kick", new Kick(Loader::getInstance()));
		Loader::getInstance()->getServer()->getCommandMap()->register("/sc", new StaffChat(Loader::getInstance()));
		Loader::getInstance()->getServer()->getCommandMap()->register("/ping", new PingCommand(Loader::getInstance()));
		//Loader::getInstance()->getServer()->getCommandMap()->register("/mute", new Mute(Loader::getInstance()));
		//Loader::getInstance()->getServer()->getCommandMap()->register("/unmute", new UnMute(Loader::getInstance()));
		Loader::getInstance()->getServer()->getCommandMap()->register("/history", new HistoryCommand(Loader::getInstance()));
		Loader::getInstance()->getServer()->getCommandMap()->register("/tban", new TimeBan(Loader::getInstance()));
		//Loader::getInstance()->getServer()->getCommandMap()->register("/tmute", new TimeMute(Loader::getInstance()));
		Loader::getInstance()->getServer()->getCommandMap()->register("/mod", new StaffMode(Loader::getInstance()));
		Loader::getInstance()->getServer()->getCommandMap()->register("/banlist", new BanListCommand(Loader::getInstance()));
		Loader::getInstance()->getServer()->getCommandMap()->register("/tell", new TellCommand(Loader::getInstance()));
		Loader::getInstance()->getServer()->getCommandMap()->register("/msg", new MessageCommand(Loader::getInstance()));
		Loader::getInstance()->getServer()->getCommandMap()->register("/w", new WCommand(Loader::getInstance()));
		Loader::getInstance()->getServer()->getCommandMap()->register("/warn", new WarnCommand(Loader::getInstance()));
		Loader::getInstance()->getServer()->getCommandMap()->register("/co", new InfoPlayerCommand(Loader::getInstance()));
		Loader::getInstance()->getServer()->getCommandMap()->register("/request", new HelpStaff(Loader::getInstance()));
    }

}

?>