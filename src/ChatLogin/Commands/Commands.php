<?php

/*
 * ChatLogin (v1.3) by EvolSoft
 * Developer: EvolSoft (Flavius12)
 * Website: http://www.evolsoft.tk
 * Date: 14/05/2015 05:59 PM (UTC)
 * Copyright & License: (C) 2015 EvolSoft
 * Licensed under MIT (https://github.com/EvolSoft/ChatLogin/blob/master/LICENSE)
 */

namespace ChatLogin\Commands;

use pocketmine\command\Command;
use pocketmine\command\CommandExecutor;
use pocketmine\command\CommandSender;
use pocketmine\permission\Permission;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\utils\Config;

use ChatLogin\Main;

class Commands extends PluginBase implements CommandExecutor {
	
	public function __construct(Main $plugin){
        $this->plugin = $plugin;
    }
    
    public function onCommand(CommandSender $sender, Command $cmd, $label, array $args) {
    	$fcmd = strtolower($cmd->getName());
    	switch($fcmd){
    			case "chatlogin":
    				if(isset($args[0])){
    			   		$args[0] = strtolower($args[0]);
    			   		if($args[0]=="reload"){
    			   			if($sender->hasPermission("chatlogin.reload")) {
    			   				$this->plugin->reloadConfig();
    			   				$sender->sendMessage($this->plugin->translateColors("&", Main::PREFIX . "&aConfiguration Reloaded."));
    			   				return true;
    			   			}else{
    			   				$sender->sendMessage($this->plugin->translateColors("&", "&cYou don't have permissions to use this command"));
    			   				return true;
    			   			}
    			   		}elseif($args[0]=="info"){
    			   			if($sender->hasPermission("chatlogin.info")) {
    			   				$sender->sendMessage($this->plugin->translateColors("&", Main::PREFIX . "&7ChatLogin &bv" . Main::VERSION . " &7developed by&b " . Main::PRODUCER));
    			   				$sender->sendMessage($this->plugin->translateColors("&", Main::PREFIX . "&7Website &b" . Main::MAIN_WEBSITE));
    			   				return true;
    			   			}else{
    			   				$sender->sendMessage($this->plugin->translateColors("&", "&cYou don't have permissions to use this command"));
    			   				return true;
    			   			}
    			   		}else{
    			   			if($sender->hasPermission("chatlogin")){
    			   				$sender->sendMessage($this->plugin->translateColors("&", Main::PREFIX . "&cSubcommand &9" . $args[0] . "&c not found. Use &9/chlogin &cto show available commands"));
    			   				break;
    			   			}else{
    			   				$sender->sendMessage($this->plugin->translateColors("&", "&cYou don't have permissions to use this command"));
    			   				break;
    			   			}
    			   			return true;
    			   		}
    			   	}else{
    			   		if($sender->hasPermission("chatlogin")){
    			   			$sender->sendMessage($this->plugin->translateColors("&", "&7// &bAvailable Commands &7\\\\"));
    			   			$sender->sendMessage($this->plugin->translateColors("&", "&9/chatlogin help &b-> &7Show help about this plugin"));
    			   			$sender->sendMessage($this->plugin->translateColors("&", "&9/chatlogin info &b-> &7Show info about this plugin"));
    			   			$sender->sendMessage($this->plugin->translateColors("&", "&9/chatlogin reload &b-> &7Reload the config"));
    			   			break;
    			   		}else{
    			   			$sender->sendMessage($this->plugin->translateColors("&", "&cYou don't have permissions to use this command"));
    			   			break;
    			   			}
    			   		return true;
    			   	}
    		}
    	return true;
    }
    
}
?>