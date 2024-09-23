<?php

declare(strict_types=1);

namespace GameParrot\FlySpeed\listener;

use GameParrot\FlySpeed\config\FlySpeedConfig;
use GameParrot\FlySpeed\session\FlySpeedSession;
use pocketmine\event\Listener;
use pocketmine\event\server\DataPacketSendEvent;
use pocketmine\network\mcpe\protocol\types\AbilitiesLayer;
use pocketmine\network\mcpe\protocol\UpdateAbilitiesPacket;

class FlySpeedListener implements Listener {
	private ?\ReflectionProperty $flySpeedRefl = null;
	public function __construct(private FlySpeedConfig $config) {
	}
	public function onPacketSend(DataPacketSendEvent $event) : void {
		foreach ($event->getPackets() as $packet) {
			if ($packet->pid() === UpdateAbilitiesPacket::NETWORK_ID) {
				/** @var UpdateAbilitiesPacket $packet */
				if (!isset($event->getTargets()[0])) {
					continue;
				}
				$ns = $event->getTargets()[0];
				if ($this->flySpeedRefl === null) {
					$this->flySpeedRefl = (new \ReflectionClass(AbilitiesLayer::class))->getProperty("flySpeed");
				}
				$speed = FlySpeedSession::get($ns)?->getFlySpeed() ?? $this->config->getDefaultSpeed();
				foreach ($packet->getData()->getAbilityLayers() as $layer) {
					if ($layer->getFlySpeed() !== null) {
						$this->flySpeedRefl->setValue($layer, $speed);
					}
				}
			}
		}
	}
}
