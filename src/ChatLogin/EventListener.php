<?php

/*
 * ChatLogin (v1.3) by EvolSoft
 * Developer: EvolSoft (Flavius12)
 * Website: http://www.evolsoft.tk
 * Date: 31/08/2015 03:19 PM (UTC)
 * Copyright & License: (C) 2015 EvolSoft
 * Licensed under MIT (https://github.com/EvolSoft/ChatLogin/blob/master/LICENSE)
 */

namespace ChatLogin;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerLoginEvent;
use pocketmine\Player;

use ServerAuth\ServerAuth;

class EventListener implements Listener {

	public function __construct(Main $plugin){
		$this->plugin = $plugin;
	}
	
	public function onPlayerLogin(PlayerLoginEvent $event){
		ServerAuth::getAPI()->enableLoginMessages(false);
		ServerAuth::getAPI()->enableRegisterMessages(false);
	}
	
	/**
	 * @param PlayerJoinEvent $event
	 * 
	 * @priority HIGHEST
	 */
	public function onPlayerJoin(PlayerJoinEvent $event){
		$player = $event->getPlayer();
		$cfg = $this->plugin->getConfig()->getAll();	
		$prefix = "";
		if($cfg["show-prefix"]){
			$prefix = Main::PREFIX;
		}
		if(!ServerAuth::getAPI()->isPlayerRegistered($player->getName())){
			$player->sendMessage($this->plugin->translateColors("&", $prefix . $cfg["register-message"]));
		}elseif(!ServerAuth::getAPI()->isPlayerAuthenticated($player)){
			$player->sendMessage($this->plugin->translateColors("&", $prefix . $cfg["login-message"]));
		}
	}
	
	public function onPlayerChat(PlayerChatEvent $event){
		$player = $event->getPlayer();
		$cfg = $this->plugin->getConfig()->getAll();
		$prefix = "";
		if($cfg["show-prefix"]){
			$prefix = Main::PREFIX;
		}
		if(!ServerAuth::getAPI()->isPlayerRegistered($player->getName())){
			if($player->hasPermission("chatlogin.register")){
				$status = ServerAuth::getAPI()->registerPlayer($player, $event->getMessage());
				if($status == ServerAuth::SUCCESS){
					ServerAuth::getAPI()->authenticatePlayer($player, $event->getMessage());
					$player->sendMessage($this->plugin->translateColors("&", $prefix . ServerAuth::getAPI()->getConfigLanguage()->getAll()["register"]["register-success"]));
				}elseif($status == ServerAuth::ERR_USER_ALREADY_REGISTERED){
					$player->sendMessage($this->plugin->translateColors("&", $prefix . ServerAuth::getAPI()->getConfigLanguage()->getAll()["register"]["already-registered"]));
				}elseif($status == ServerAuth::ERR_PASSWORD_TOO_SHORT){
					$player->sendMessage($this->plugin->translateColors("&", $prefix . ServerAuth::getAPI()->getConfigLanguage()->getAll()["errors"]["password-too-short"]));
				}elseif($status == ServerAuth::ERR_PASSWORD_TOO_LONG){
					$player->sendMessage($this->plugin->translateColors("&", $prefix . ServerAuth::getAPI()->getConfigLanguage()->getAll()["errors"]["password-too-long"]));
				}elseif($status == ServerAuth::ERR_MAX_IP_REACHED){
					$player->sendMessage($this->plugin->translateColors("&", $prefix . ServerAuth::getAPI()->getConfigLanguage()->getAll()["errors"]["max-ip-reached"]));
				}elseif($status == ServerAuth::CANCELLED){
    				$player->sendMessage($this->plugin->translateColors("&", $prefix . ServerAuth::getAPI()->getConfigLanguage()->getAll()["operation-cancelled"]));
    			}else{
					$player->sendMessage($this->plugin->translateColors("&", $prefix . ServerAuth::getAPI()->getConfigLanguage()->getAll()["errors"]["generic"]));
				}
			}else{
				$player->sendMessage($this->plugin->translateColors("&", $prefix . "&cYou don't have permissions to register"));
			}
			$event->setMessage("");
			$event->setCancelled(true);
		}elseif(!ServerAuth::getAPI()->isPlayerAuthenticated($player)){
			if($player->hasPermission("chatlogin.login")){
				$status = ServerAuth::getAPI()->authenticatePlayer($player, $event->getMessage());
				if($status == ServerAuth::SUCCESS){
					$player->sendMessage($this->plugin->translateColors("&", $prefix . ServerAuth::getAPI()->getConfigLanguage()->getAll()["login"]["login-success"]));
				}elseif($status == ServerAuth::ERR_WRONG_PASSWORD){
					$player->sendMessage($this->plugin->translateColors("&", $prefix . ServerAuth::getAPI()->getConfigLanguage()->getAll()["errors"]["wrong-password"]));
				}elseif($status == ServerAuth::ERR_USER_ALREADY_AUTHENTICATED){
					$player->sendMessage($this->plugin->translateColors("&", $prefix . ServerAuth::getAPI()->getConfigLanguage()->getAll()["login"]["already-login"]));
				}elseif($status == ServerAuth::ERR_USER_NOT_REGISTERED){
					$player->sendMessage($this->plugin->translateColors("&", $prefix . ServerAuth::getAPI()->getConfigLanguage()->getAll()["errors"]["user-not-registered"]));
				}elseif($status == ServerAuth::CANCELLED){
    				$player->sendMessage($this->plugin->translateColors("&", $prefix . ServerAuth::getAPI()->getConfigLanguage()->getAll()["operation-cancelled"]));
    			}else{
					$player->sendMessage($this->plugin->translateColors("&", $prefix . ServerAuth::getAPI()->getConfigLanguage()->getAll()["errors"]["generic"]));
				}
			}else{
				$player->sendMessage($this->plugin->translateColors("&", $prefix . "&cYou don't have permissions to login"));
			}
			$event->setMessage("");
			$event->setCancelled(true);
		}
	}
}
?>