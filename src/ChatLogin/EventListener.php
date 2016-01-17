<?php

/*
 * ChatLogin (v1.4) by EvolSoft
 * Developer: EvolSoft (Flavius12)
 * Website: http://www.evolsoft.tk
 * Date: 17/01/2015 11:37 AM (UTC)
 * Copyright & License: (C) 2015-2016 EvolSoft
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
		if(isset($this->plugin->confirm_users[strtolower($event->getPlayer()->getName())])){
			unset($this->plugin->confirm_users[strtolower($event->getPlayer()->getName())]);
		}
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
				//Check if confirmation is required
				if($cfg["password-confirm-required"]){
					if(!isset($this->plugin->confirm_users[strtolower($player->getName())])){
						$this->plugin->confirm_users[strtolower($player->getName())] = $event->getMessage();
						$player->sendMessage($this->plugin->translateColors("&", $prefix . $cfg["register-confirm-message"]));
					}else{
						//Check passwords
						if($this->plugin->confirm_users[strtolower($player->getName())] == $event->getMessage()){
							unset($this->plugin->confirm_users[strtolower($player->getName())]);
							$status = ServerAuth::getAPI()->registerPlayer($player, $event->getMessage());
							if($status == ServerAuth::SUCCESS){
								ServerAuth::getAPI()->authenticatePlayer($player, $event->getMessage());
								$player->sendMessage($this->plugin->translateColors("&", $prefix . ServerAuth::getAPI()->chlang["register"]["register-success"]));
							}elseif($status == ServerAuth::ERR_USER_ALREADY_REGISTERED){
								$player->sendMessage($this->plugin->translateColors("&", $prefix . ServerAuth::getAPI()->chlang["register"]["already-registered"]));
							}elseif($status == ServerAuth::ERR_PASSWORD_TOO_SHORT){
								$player->sendMessage($this->plugin->translateColors("&", $prefix . ServerAuth::getAPI()->chlang["errors"]["password-too-short"]));
								$player->sendMessage($this->plugin->translateColors("&", $prefix . $cfg["register-message"]));
							}elseif($status == ServerAuth::ERR_PASSWORD_TOO_LONG){
								$player->sendMessage($this->plugin->translateColors("&", $prefix . ServerAuth::getAPI()->chlang["errors"]["password-too-long"]));
								$player->sendMessage($this->plugin->translateColors("&", $prefix . $cfg["register-message"]));
							}elseif($status == ServerAuth::ERR_MAX_IP_REACHED){
								$player->sendMessage($this->plugin->translateColors("&", $prefix . ServerAuth::getAPI()->chlang["errors"]["max-ip-reached"]));
							}elseif($status == ServerAuth::CANCELLED){
								$player->sendMessage($this->plugin->translateColors("&", $prefix . ServerAuth::getAPI()->getCancelledMessage()));
							}else{
								$player->sendMessage($this->plugin->translateColors("&", $prefix . ServerAuth::getAPI()->chlang["errors"]["generic"]));
								$player->sendMessage($this->plugin->translateColors("&", $prefix . $cfg["register-message"]));
							}
						}else{
							unset($this->plugin->confirm_users[strtolower($player->getName())]);
							$player->sendMessage($this->plugin->translateColors("&", $prefix .  ServerAuth::getAPI()->chlang["errors"]["password-no-match"]));
							$player->sendMessage($this->plugin->translateColors("&", $prefix . $cfg["register-message"]));
						}
					}
				}else{
					$status = ServerAuth::getAPI()->registerPlayer($player, $event->getMessage());
					if($status == ServerAuth::SUCCESS){
						ServerAuth::getAPI()->authenticatePlayer($player, $event->getMessage());
						$player->sendMessage($this->plugin->translateColors("&", $prefix . ServerAuth::getAPI()->chlang["register"]["register-success"]));
					}elseif($status == ServerAuth::ERR_USER_ALREADY_REGISTERED){
						$player->sendMessage($this->plugin->translateColors("&", $prefix . ServerAuth::getAPI()->chlang["register"]["already-registered"]));
					}elseif($status == ServerAuth::ERR_PASSWORD_TOO_SHORT){
						$player->sendMessage($this->plugin->translateColors("&", $prefix . ServerAuth::getAPI()->chlang["errors"]["password-too-short"]));
						$player->sendMessage($this->plugin->translateColors("&", $prefix . $cfg["register-message"]));
					}elseif($status == ServerAuth::ERR_PASSWORD_TOO_LONG){
						$player->sendMessage($this->plugin->translateColors("&", $prefix . ServerAuth::getAPI()->chlang["errors"]["password-too-long"]));
						$player->sendMessage($this->plugin->translateColors("&", $prefix . $cfg["register-message"]));
					}elseif($status == ServerAuth::ERR_MAX_IP_REACHED){
						$player->sendMessage($this->plugin->translateColors("&", $prefix . ServerAuth::getAPI()->chlang["errors"]["max-ip-reached"]));
					}elseif($status == ServerAuth::CANCELLED){
						$player->sendMessage($this->plugin->translateColors("&", $prefix . ServerAuth::getAPI()->getCancelledMessage()));
					}else{
						$player->sendMessage($this->plugin->translateColors("&", $prefix . ServerAuth::getAPI()->chlang["errors"]["generic"]));
						$player->sendMessage($this->plugin->translateColors("&", $prefix . $cfg["register-message"]));
					}
				}
			}else{
				$player->sendMessage($this->plugin->translateColors("&", $prefix . $cfg['no-register-permissions']));
			}
			$event->setMessage("");
			$event->setCancelled(true);
		}elseif(!ServerAuth::getAPI()->isPlayerAuthenticated($player)){
			if($player->hasPermission("chatlogin.login")){
				$status = ServerAuth::getAPI()->authenticatePlayer($player, $event->getMessage());
				if($status == ServerAuth::SUCCESS){
					$player->sendMessage($this->plugin->translateColors("&", $prefix . ServerAuth::getAPI()->chlang["login"]["login-success"]));
				}elseif($status == ServerAuth::ERR_WRONG_PASSWORD){
					$player->sendMessage($this->plugin->translateColors("&", $prefix . ServerAuth::getAPI()->chlang["errors"]["wrong-password"]));
					$player->sendMessage($this->plugin->translateColors("&", $prefix . $cfg["login-message"]));
				}elseif($status == ServerAuth::ERR_USER_ALREADY_AUTHENTICATED){
					$player->sendMessage($this->plugin->translateColors("&", $prefix . ServerAuth::getAPI()->chlang["login"]["already-login"]));
				}elseif($status == ServerAuth::ERR_USER_NOT_REGISTERED){
					$player->sendMessage($this->plugin->translateColors("&", $prefix . ServerAuth::getAPI()->chlang["errors"]["user-not-registered"]));
				}elseif($status == ServerAuth::CANCELLED){
    				$player->sendMessage($this->plugin->translateColors("&", $prefix . ServerAuth::getAPI()->getCancelledMessage()));
    			}else{
					$player->sendMessage($this->plugin->translateColors("&", $prefix . ServerAuth::getAPI()->chlang["errors"]["generic"]));
					$player->sendMessage($this->plugin->translateColors("&", $prefix . $cfg["login-message"]));
				}
			}else{
				$player->sendMessage($this->plugin->translateColors("&", $prefix . $cfg['no-login-permissions']));
			}
			$event->setMessage("");
			$event->setCancelled(true);
		}
	}
}
?>