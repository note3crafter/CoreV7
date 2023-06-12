<?php

namespace TheNote\core\events;

use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\block\SignChangeEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\math\Vector2;
use pocketmine\math\Vector3;
use pocketmine\player\Player;
use pocketmine\world\World;

class SpawnProtection implements Listener
{
    private $radiusSquared;

    public function __construct(int $radius)
    {
        $this->radiusSquared = $radius ** 2;
    }
    private function checkSpawnProtection(World $world, Player $player, Vector3 $vector): bool
    {
        if (!$player->hasPermission("core.events.spawn.bypass")) {
            $t = new Vector2($vector->x, $vector->z);

            $spawnLocation = $world->getSpawnLocation();
            $s = new Vector2($spawnLocation->x, $spawnLocation->z);
            if ($t->distanceSquared($s) <= $this->radiusSquared) {
                return true;
            }
        }

        return false;
    }

    public function onInteract(PlayerInteractEvent $event): void
    {
        if ($this->checkSpawnProtection($event->getPlayer()->getWorld(), $event->getPlayer(), $event->getBlock()->getPosition())) {
            //This prevents opening doors. Perhaps not desired...
            $event->cancel();
        }
    }

    public function onBlockPlace(BlockPlaceEvent $event): void
    {
        $player = $event->getPlayer();
        $world = $player->getWorld();
        foreach ($event->getTransaction()->getBlocks() as [$x, $y, $z, $block]) {
            if ($this->checkSpawnProtection($world, $player, new Vector3($x, $y, $z))) {
                $event->cancel();
                return;
            }
        }
    }

    public function onBlockBreak(BlockBreakEvent $event): void
    {
        if ($this->checkSpawnProtection($event->getPlayer()->getWorld(), $event->getPlayer(), $event->getBlock()->getPosition())) {
            $event->cancel();
        }
    }

    public function onSignChange(SignChangeEvent $event): void
    {
        if ($this->checkSpawnProtection($event->getPlayer()->getWorld(), $event->getPlayer(), $event->getBlock()->getPosition())) {
            $event->cancel();
        }
    }

}