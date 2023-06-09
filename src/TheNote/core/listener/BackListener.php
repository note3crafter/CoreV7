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
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\utils\Config;
use TheNote\core\BaseAPI;
use TheNote\core\Main;

class BackListener implements Listener {

    private Main $plugin;

    public function __construct(Main $plugin){
        $this->plugin = $plugin;
    }
    public function playerDeath(PlayerDeathEvent $event){
    $player = $event->getPlayer();
    $config = new Config($this->plugin->getDataFolder() . Main::$backfile . "Back.json", Config::JSON);
    $config->set($player->getName(), "{$player->getPosition()->getX()} {$player->getPosition()->getY()} {$player->getPosition()->getZ()} {$player->getWorld()->getFolderName()}");
    $config->save();
    }
    public function playerQuit(PlayerQuitEvent $event){
        $player = $event->getPlayer();
        $api = new BaseAPI();
        $config = new Config($this->plugin->getDataFolder() . Main::$backfile . "Back.json", Config::JSON);
        if ($api->getBackExist($player->getName())){
            $config->remove($player->getName());
        }
    }
}