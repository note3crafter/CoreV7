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

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\utils\Config;
use TheNote\core\BaseAPI;
use TheNote\core\formapi\SimpleForm;
use TheNote\core\Main;

class FriendCommand extends Command
{
    private $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $api = new BaseAPI();
        parent::__construct("friend", $api->getSetting("prefix") . "Sehe die Freundes Befehle!", "/friend", ["freund", "freunde"]);
        $this->setPermission(Main::$defaultperm);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        $playerfile = new Config($this->plugin->getDataFolder() . Main::$freundefile . $sender->getName() . ".json", Config::JSON);
        $api = new BaseAPI();
        if (!$sender instanceof Player) {
            $sender->sendMessage($api->getSetting("error") . "§cDiesen Command kannst du nur Ingame benutzen");
            return false;
        }
        if (empty($args[0])) {
            $form = new SimpleForm(function (Player $player, int $data = null) {

                $result = $data;
                if ($result === null) {
                    return true;
                }
                switch ($result) {
                    case 0:
                        break;
                }
            });
            $form->setTitle("§f======[§aFreundeSystem Hilfe§f]======");
            $form->setContent("§e/friend accept (player) » Aktzeptiere eine Anfrage\n" .
                "§e/friend deny » Lehne eine Anfrage ab\n" .
                "§e/friend add (player) » Lade ein Freund/in ein\n" .
                "§e/friend list » Zeigt Deine Freunde an\n" .
                "§e/friend remove (player) » fEntferne einen Freund/in\n" .
                "§e/friend block (player) » Deaktiviere Freundschaftsanfragen");
            $form->addButton("verlassen");
            $form->sendToPlayer($sender);
            return true;
        }
        if ($args[0] == "list") {
            if (empty($playerfile->get("Friend"))) {
                $sender->sendMessage($api->getSetting("friend") . "§aDu hast keine Freunde!");
                return false;
            } else {
                $sender->sendMessage("§f=======[§aDeine Freunde§f]=======");
                foreach ($playerfile->get("Friend") as $f) {
                    if ($this->plugin->getServer()->getPlayerExact($f) == null) {
                        $sender->sendMessage("§b" . $f . " » §7(§cOffline§7)");
                    } else {
                        $sender->sendMessage("§b" . $f . " » §7(§aOnline§7)");
                    }
                }
                return true;
            }
        }
        if ($args[0] == "block") {
            if ($playerfile->get("blocked") === false) {
                $playerfile->set("blocked", true);
                $playerfile->save();
                $sender->sendMessage($api->getSetting("friend") . "§aDu wirst nun keine Freundschaftsanfrage mehr bekommen!");
            } else {
                $sender->sendMessage($api->getSetting("friend") . "§aDu wirst nun wieder Freundschaftsanfragen bekommen!");
                $playerfile->set("blocked", false);
                $playerfile->save();
            }
        }

        if ($args[0] == "add") {
            if (empty($args[1])) {
                $sender->sendMessage($api->getSetting("friend") . "§7Benutze: §c/friend add [name]");
                return false;
            }
            if ($sender->getName() == $args[1]) {
                $sender->sendMessage($api->getSetting("friend") . "§cDu kannst dich nicht selbst befreunden!");
                return false;
            }
            $target = $api->findPlayer($sender, $args[1]);
            if ($target == null) {
                $sender->sendMessage($api->getSetting("error") . "§cDer Spieler ist nicht Online!");
                return false;
            } else {
                if (file_exists($this->plugin->getDataFolder() . Main::$freundefile . $args[1] . ".json")) {
                    $vplayerfile = new Config($this->plugin->getDataFolder() . Main::$freundefile . $args[1] . ".json", Config::JSON);
                    if ($vplayerfile->get("blocked") === false) {
                        $einladungen = $vplayerfile->get("Invitations");
                        $einladungen[] = $sender->getName();
                        $vplayerfile->set("Invitations", $einladungen);
                        $vplayerfile->save();

                        $sender->sendMessage($api->getSetting("friend") . "§aDeine Freundschaftsanfrage wurde gesendet zu  " . $args[1]);
                        $v = $this->plugin->getServer()->getPlayerExact($args[1]);

                        if (!$v == null) {
                            $v->sendMessage($api->getSetting("friend") . "§a" . $sender->getName() . " hat Dir eine Freundschafts Anfrage gesendet akzeptier sie mit §e/friend accept " . $sender->getName() . "§a oder lehne sie ab mit §e /friend deny " . $sender->getName() . "§a!");
                        }
                    } else {
                        $sender->sendMessage($api->getSetting("friend") . "§aDieser Spieler hat Deine Freundschaftsanfrage nicht angenommen!");
                        return true;
                    }
                }
            }
        }
        if ($args[0] == "accept") {
            if (empty($args[1])) {
                $sender->sendMessage($api->getSetting("friend") . "§7Benutze: §c/friend accept [name]");
                return false;
            } else {
                if (file_exists($this->plugin->getDataFolder() . Main::$freundefile . $args[1] . ".json")) {
                    if (in_array($args[1], $playerfile->get("Invitations"))) {
                        $old = $playerfile->get("Invitations");
                        unset($old[array_search($args[1], $old)]);
                        $playerfile->set("Invitations", $old);
                        $newfriend = $playerfile->get("Friend");
                        $newfriend[] = $args[1];
                        $playerfile->set("Friend", $newfriend);
                        $playerfile->save();
                        $vplayerfile = new Config($this->plugin->getDataFolder() . Main::$freundefile . $args[1] . ".json", Config::JSON);
                        $newfriend = $vplayerfile->get("Friend");
                        $newfriend[] = $sender->getName();
                        $vplayerfile->set("Friend", $newfriend);
                        $vplayerfile->get("friends", $vplayerfile->set("friends") + 1);
                        $vplayerfile->save();
                        if (!$this->plugin->getServer()->getPlayerExact($args[1]) == null) {
                            $this->plugin->getServer()->getPlayerExact($args[1])->sendMessage($api->getSetting("friend") . "§a" . $sender->getName() . " hat Deine Freundschaffts Anfrage angenommen!");
                        }
                        $sender->sendMessage($api->getSetting("friend") . "§a" . $args[1] . " ist jetzt Dein Freund!");
                    } else {
                        $sender->sendMessage($api->getSetting("friend") . "§aDieser Spieler hat Dir keine Freundschafts Anfrage gesendet!");
                    }
                } else {
                    $sender->sendMessage($api->getSetting("friend") . "§aDiesen Spieler gibt es nicht!");
                }
            }
        }
        if ($args[0] == "deny") {
            if (empty($args[1])) {
                $sender->sendMessage($api->getSetting("friend") . "§7Benutze: §c/friend deny [name]");
                return false;
            } else {
                if (file_exists($this->plugin->getDataFolder() . Main::$freundefile . $args[1] . ".json")) {
                    if (in_array($args[1], $playerfile->get("Invitations"))) {
                        $old = $playerfile->get("Invitations");
                        unset($old[array_search($args[1], $old)]);
                        $playerfile->set("Invitations", $old);
                        $playerfile->save();
                        $sender->sendMessage($api->getSetting("friend") . "§aDie Anfrage von " . $args[1] . " wurde abgelehnt!");
                    } else {
                        $sender->sendMessage($api->getSetting("friend") . "§aDieser Spieler hat Dir keine Freundschafts Anfrage gesendet!");
                    }
                } else {
                    $sender->sendMessage($api->getSetting("friend") . "§aDiesen Spieler gibt es nicht!");
                }
            }
        }
        if ($args[0] == "remove") {
            if (empty($args[1])) {
                $sender->sendMessage($api->getSetting("friend") . "§7Benutze: §c/friend remove [name]");
                return false;
            } else {
                if (file_exists($this->plugin->getDataFolder() . Main::$freundefile . $args[1] . ".json")) {
                    if (in_array($args[1], $playerfile->get("Friend"))) {
                        $old = $playerfile->get("Friend");
                        unset($old[array_search($args[1], $old)]);
                        $playerfile->set("Friend", $old);
                        $playerfile->save();
                        $vplayerfile = new Config($this->plugin->getDataFolder() . Main::$freundefile . $args[1] . ".json", Config::JSON);
                        $old = $vplayerfile->get("Friend");
                        unset($old[array_search($sender->getName(), $old)]);
                        $vplayerfile->set("Friend", $old);
                        $vplayerfile->get("friends", $vplayerfile->set("friends") - 1);
                        $vplayerfile->save();
                        $sender->sendMessage($api->getSetting("friend") . "§a" . $args[1] . " ist nicht mehr Dein Freund!");
                    } else {
                        $sender->sendMessage($api->getSetting("friend") . "§aDieser Spieler ist nicht Dein Freund!");
                    }
                } else {
                    $sender->sendMessage($api->getSetting("friend") . "§aDiesen Spieler gibt es nicht!");
                    return false;
                }
            }
        } else {
            $this->plugin->getLogger()->info($api->getSetting("friend") . "§aDie Console hat keine Freunde!");
            return false;
        }

        return true;
    }
}

