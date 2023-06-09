<?php

//   ╔═════╗╔═╗ ╔═╗╔═════╗╔═╗    ╔═╗╔═════╗╔═════╗╔═════╗
//   ╚═╗ ╔═╝║ ║ ║ ║║ ╔═══╝║ ╚═╗  ║ ║║ ╔═╗ ║╚═╗ ╔═╝║ ╔═══╝
//     ║ ║  ║ ╚═╝ ║║ ╚══╗ ║   ╚══╣ ║║ ║ ║ ║  ║ ║  ║ ╚══╗
//     ║ ║  ║ ╔═╗ ║║ ╔══╝ ║ ╠══╗   ║║ ║ ║ ║  ║ ║  ║ ╔══╝
//     ║ ║  ║ ║ ║ ║║ ╚═══╗║ ║  ╚═╗ ║║ ╚═╝ ║  ║ ║  ║ ╚═══╗
//     ╚═╝  ╚═╝ ╚═╝╚═════╝╚═╝    ╚═╝╚═════╝  ╚═╝  ╚═════╝
//   Copyright by TheNote! Not for Resale! Not for others
//

namespace TheNote\core\command;

use pocketmine\player\Player;
use pocketmine\utils\Config;
use TheNote\core\BaseAPI;
use TheNote\core\Main;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class UnnickCommand extends Command
{

    private Main $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $api = new BaseAPI();
        parent::__construct("unnick", $api->getSetting("prefix") . $api->getLang("unnickprefix"), "/unick");
        $this->setPermission("core.command.nick");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        $api = new BaseAPI();
        $playerdata = new Config($this->plugin->getDataFolder() . Main::$cloud . "players.yml", Config::YAML);
        $groups = new Config($this->plugin->getDataFolder(). Main::$cloud . "groups.yml", Config::YAML);
        if (!$sender instanceof Player) {
            $sender->sendMessage($api->getSetting("error") . $api->getLang("commandingame"));
            return false;
        }
        if (!$this->testPermission($sender)) {
            $sender->sendMessage($api->getSetting("error") . $api->getLang("nopermission"));
            return false;
        }
        $pf = new Config($this->plugin->getDataFolder() . Main::$gruppefile . $sender->getName() . ".json", Config::JSON);
        if ($pf->get("Nick") === false) {
            $sender->sendMessage($api->getSetting("error") . $api->getLang("unnickerror"));
            return true;
        }
        if ($pf->get("Nick") === true) {
            $sender->sendMessage($api->getSetting("prefix") . $api->getLang("unnicksucces"));
            $pf->set("Nick", false);
            $pf->set("Nickplayer", false);
            $pf->set("nicket". false);
            $pf->set("Nickname", $sender->getName());
            $pf->save();
            $name = $sender->getName();
            $playergroup = $playerdata->getNested($name . ".group");
            $nametag = str_replace("{name}", $pf->get("Nickname"), $groups->getNested("Groups.{$playergroup}.nametag"));
            $displayname = str_replace("{name}", $pf->get("Nickname"), $groups->getNested("Groups.{$playerdata->getNested($name.".group")}.displayname"));
            $sender->setDisplayName($displayname);
            $sender->setNameTag($nametag);
        }
        return true;
    }
}