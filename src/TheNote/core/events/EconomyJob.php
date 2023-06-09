<?php

namespace TheNote\core\events;

use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\Listener;
use pocketmine\item\LegacyStringToItemParser;
use pocketmine\item\StringToItemParser;
use pocketmine\item\VanillaItems;
use pocketmine\utils\Config;
use pocketmine\utils\SingletonTrait;
use TheNote\core\BaseAPI;
use TheNote\core\Main;

class EconomyJob implements Listener
{
    use SingletonTrait;

    private $jobs;
    private $player;
    private $api;
    private $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;

    }

    public function onBlockBreak(BlockBreakEvent $event)
    {
        $player = $event->getPlayer();
        $block = $event->getBlock();
        $api = new BaseAPI();
        $jobs = new Config($this->plugin->getDataFolder() . Main::$setup . "Jobs.yml", Config::YAML);
        $pjobs = new Config($this->plugin->getDataFolder() . Main::$cloud . "jobsplayer.yml", Config::YAML);
        $job = $pjobs->get($player->getName());
        $bm = StringToItemParser::getInstance()->parse($block);
        $bi = $block->getTypeId();
        $bid = $jobs->getNested("$job." . "$bi:$bm:break");
        if (!$job === false or null) {
            $money = $bid;
            if ($money > 0) {
                $api->addMoney($player, $money);
            } else {
                $api->removeMoney($player, $money);
            }
        }
    }


    public function onBlockPlace(BlockPlaceEvent $event)
    {
        $player = $event->getPlayer();
        $block = $event->getItem()->getBlock();
        $api = new BaseAPI();
        $jobs = new Config($this->plugin->getDataFolder() . Main::$setup . "Jobs.yml", Config::YAML);
        $pjobs = new Config($this->plugin->getDataFolder() . Main::$cloud . "jobsplayer.yml", Config::YAML);
        $job = $pjobs->get($player->getName());
        $bm = StringToItemParser::getInstance()->parse($block);
        $bi = StringToItemParser::getInstance()->parse($block);
        $bid = $jobs->getNested("$job." . "$bi:place");
        if (!$job === false or null) {
            $money = $bid;
            if ($money > 0) {
                $api->addMoney($player, $money);
            } else {
                $api->removeMoney($player, $money);
            }
        }
    }
}