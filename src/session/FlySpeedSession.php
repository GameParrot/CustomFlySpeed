<?php

declare(strict_types=1);

namespace GameParrot\CustomFlySpeed\session;

use pocketmine\network\mcpe\NetworkSession;

class FlySpeedSession {
	/** @var \WeakMap<NetworkSession, FlySpeedSession> */
	private static \WeakMap $sessions;

	public static function get(NetworkSession $session) : FlySpeedSession {
		if (!isset(self::$sessions)) {
			/** @var \WeakMap<NetworkSession, FlySpeedSession> */
			$map = new \WeakMap();
			self::$sessions = $map;
		}
		return self::$sessions[$session] ??= new self($session);
	}

	private NetworkSession $ns;
	private ?float $flySpeed = null;
	public function __construct(NetworkSession $session) {
		$this->ns = $session;
	}

	public function getFlySpeed() : ?float {
		return $this->flySpeed ?? null;
	}

	public function setFlySpeed(?float $flySpeed) : void {
		if ($this->ns->getPlayer() === null) {
			return;
		}
		$this->flySpeed = $flySpeed;
		$this->ns->syncAbilities($this->ns->getPlayer());
	}
}
