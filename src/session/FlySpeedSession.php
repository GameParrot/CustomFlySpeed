<?php

declare(strict_types=1);

namespace GameParrot\CustomFlySpeed\session;

use pocketmine\network\mcpe\NetworkSession;
use WeakReference;

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

	/** @var WeakReference<NetworkSession> */
	private WeakReference $ns;
	private ?float $flySpeed = null;
	public function __construct(NetworkSession $session) {
		$this->ns = WeakReference::create($session);
	}

	public function getFlySpeed() : ?float {
		return $this->flySpeed ?? null;
	}

	public function setFlySpeed(?float $flySpeed) : void {
		$session = $this->ns->get();
		if ($session === null || $session->getPlayer() === null) {
			return;
		}
		$this->flySpeed = $flySpeed;
		$session->syncAbilities($session->getPlayer());
	}
}
