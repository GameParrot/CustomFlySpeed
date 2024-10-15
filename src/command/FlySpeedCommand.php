<?php

declare(strict_types=1);

namespace GameParrot\CustomFlySpeed\command;

use GameParrot\CustomFlySpeed\FlySpeed;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\utils\InvalidCommandSyntaxException;
use pocketmine\lang\KnownTranslationFactory;
use pocketmine\player\Player;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginOwned;
use pocketmine\utils\TextFormat;
use function count;
use function floatval;

class FlySpeedCommand extends Command implements PluginOwned {
	public function __construct(private FlySpeed $plugin) {
		parent::__construct("flyspeed", "Set fly speed", "/setflyspeed <speed: float> [player: player]");
		$this->setPermissions(["customflyspeed.command.self", "customflyspeed.command.others"]);
	}

	public function getOwningPlugin() : Plugin {
		return $this->plugin;
	}

	public function execute(CommandSender $sender, string $label, array $args) : bool {
		if (count($args) === 0) {
			throw new InvalidCommandSyntaxException();
		}

		$flySpeed = floatval($args[0]);
		if (isset($args[1])) {
			$player = $sender->getServer()->getPlayerExact($args[1]);
		} elseif ($sender instanceof Player) {
			$player = $sender;
		} else {
			throw new InvalidCommandSyntaxException();
		}

		if ($player === null) {
			$sender->sendMessage(KnownTranslationFactory::commands_generic_player_notFound()->prefix(TextFormat::RED));
			return true;
		}

		if (
			($player === $sender && !$this->testPermission($sender, "customflyspeed.command.self")) ||
			($player !== $sender && !$this->testPermission($sender, "customflyspeed.command.others"))
		) {
			return true;
		}

		FlySpeed::setSpeed($player, $flySpeed);

		Command::broadcastCommandMessage($sender, "Set " . $player->getName() . "'s fly speed to $flySpeed");

		return true;
	}
}
