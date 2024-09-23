<?php

declare(strict_types=1);

namespace GameParrot\FlySpeed;

use GameParrot\FlySpeed\command\FlySpeedCommand;
use GameParrot\FlySpeed\config\FlySpeedConfig;
use GameParrot\FlySpeed\listener\FlySpeedListener;
use GameParrot\FlySpeed\session\FlySpeedSession;
use pocketmine\permission\DefaultPermissions;
use pocketmine\permission\Permission;
use pocketmine\permission\PermissionManager;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;

class FlySpeed extends PluginBase {
	private FlySpeedConfig $conf;
	public function onEnable() : void {
		$this->saveDefaultConfig();
		$this->conf = new FlySpeedConfig($this->getConfig());
		$this->getServer()->getPluginManager()->registerEvents(new FlySpeedListener($this->conf), $this);
		$opRoot = PermissionManager::getInstance()->getPermission(DefaultPermissions::ROOT_OPERATOR);
		PermissionManager::getInstance()->addPermission(new Permission($this->conf->getSelfPerm(), "Change your own fly speed"));
		PermissionManager::getInstance()->addPermission(new Permission($this->conf->getOtherPerm(), "Change others fly speed"));
		$opRoot->addChild($this->conf->getSelfPerm(), true);
		$opRoot->addChild($this->conf->getOtherPerm(), true);
		$this->getServer()->getCommandMap()->register("flyspeed", new FlySpeedCommand($this));
	}

	public function getFlySpeedConfig() : FlySpeedConfig {
		return $this->conf;
	}

	public static function setSpeed(Player $player, ?float $speed) : void {
		FlySpeedSession::get($player->getNetworkSession())->setFlySpeed($speed);
	}
	public static function getSpeed(Player $player) : ?float {
		return FlySpeedSession::get($player->getNetworkSession())->getFlySpeed();
	}
}
