<?php

declare(strict_types=1);

namespace GameParrot\CustomFlySpeed\config;

use pocketmine\utils\Config;

class FlySpeedConfig {
	public const DEFAULT_FLY_SPEED = 0.05;

	private float $defaultSpeed;
	public function __construct(Config $config) {
		$this->defaultSpeed = (float) $config->get("default-fly-speed", self::DEFAULT_FLY_SPEED);
	}

	public function getDefaultSpeed() : float {
		return $this->defaultSpeed;
	}
}
