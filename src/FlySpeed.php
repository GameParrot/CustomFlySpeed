<?php

declare(strict_types=1);

namespace GameParrot\CustomFlySpeed;

use GameParrot\CustomFlySpeed\command\FlySpeedCommand;
use GameParrot\CustomFlySpeed\config\FlySpeedConfig;
use GameParrot\CustomFlySpeed\listener\FlySpeedListener;
use GameParrot\CustomFlySpeed\session\FlySpeedSession;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;

class FlySpeed extends PluginBase {
	private FlySpeedConfig $conf;
	public function onEnable() : void {
		$this->saveDefaultConfig();
		$this->conf = new FlySpeedConfig($this->getConfig());
		$this->getServer()->getPluginManager()->registerEvents(new FlySpeedListener($this->conf), $this);
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
