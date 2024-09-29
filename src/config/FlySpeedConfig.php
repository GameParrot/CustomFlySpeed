<?php

declare(strict_types=1);

namespace GameParrot\CustomFlySpeed\config;

use pocketmine\utils\Config;

class FlySpeedConfig {
	public const DEFAULT_FLY_SPEED = 0.05;

	private string $selfPerm;
	private string $otherPerm;
	private float $defaultSpeed;
	public function __construct(Config $config) {
		$this->selfPerm = (string) $config->get("self-perm", "customflyspeed.command.self");
		$this->otherPerm = (string) $config->get("other-perm", "customflyspeed.command.others");
		$this->defaultSpeed = (float) $config->get("default-fly-speed", self::DEFAULT_FLY_SPEED);
	}

	public function getSelfPerm() : string {
		return $this->selfPerm;
	}

	public function getOtherPerm() : string {
		return $this->otherPerm;
	}

	public function getDefaultSpeed() : float {
		return $this->defaultSpeed;
	}
}
