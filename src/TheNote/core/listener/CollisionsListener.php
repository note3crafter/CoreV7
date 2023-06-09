<?php

//   ╔═════╗╔═╗ ╔═╗╔═════╗╔═╗    ╔═╗╔═════╗╔═════╗╔═════╗
//   ╚═╗ ╔═╝║ ║ ║ ║║ ╔═══╝║ ╚═╗  ║ ║║ ╔═╗ ║╚═╗ ╔═╝║ ╔═══╝
//     ║ ║  ║ ╚═╝ ║║ ╚══╗ ║   ╚══╣ ║║ ║ ║ ║  ║ ║  ║ ╚══╗
//     ║ ║  ║ ╔═╗ ║║ ╔══╝ ║ ╠══╗   ║║ ║ ║ ║  ║ ║  ║ ╔══╝
//     ║ ║  ║ ║ ║ ║║ ╚═══╗║ ║  ╚═╗ ║║ ╚═╝ ║  ║ ║  ║ ╚═══╗
//     ╚═╝  ╚═╝ ╚═╝╚═════╝╚═╝    ╚═╝╚═════╝  ╚═╝  ╚═════╝
//   Copyright by TheNote! Not for Resale! Not for others
//

namespace TheNote\core\listener;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\utils\Config;
use TheNote\core\BaseAPI;
use TheNote\core\Main;

class  CollisionsListener implements Listener
{
    private Main $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
    }

    public function onMove(PlayerMoveEvent $ev)
    {
        $api = new BaseAPI();
        if ($api->getConfig("collision") === true) {
            $player = $ev->getPlayer();
            foreach ($player->getViewers() as $viewer) {
                if ((abs($viewer->getPosition()->getX() - $player->getPosition()->getX()) + abs($viewer->getPosition()->getZ() - $player->getPosition()->getZ())) > 0.8) continue;
                $from = $ev->getFrom();
                $to = $ev->getTo();
                $DistanceFromViewer = $viewer->getPosition()->distanceSquared($from);
                $DistanceToViewer = $viewer->getPosition()->distanceSquared($to);
                if ($DistanceFromViewer > $DistanceToViewer) {
                    $speed = round($from->distanceSquared($to), 3);
                    if ($speed > 0.15) {
                        $knockvalue = $speed / 1.4;
                        $viewer->knockBack($viewer->getPosition()->getX() - $player->getPosition()->getX(), $viewer->getPosition()->getZ() - $player->getPosition()->getZ(), $knockvalue, 0);
                        $player->knockBack($player->getPosition()->getX() - $viewer->getPosition()->getX(), $player->getPosition()->getZ() - $viewer->getPosition()->getZ(), $knockvalue, 0);
                    } else $player->knockBack($player->getPosition()->getX() - $viewer->getPosition()->getX(), $player->getPosition()->getZ() - $viewer->getPosition()->getZ(), 0.1, 0);
                }
            }
        }
    }
}