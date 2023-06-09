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
use pocketmine\utils\Config;
use TheNote\core\BaseAPI;
use TheNote\core\formapi\SimpleForm;
use TheNote\core\Main;

class ClanCommand extends Command
{
    private $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $api = new BaseAPI();
        parent::__construct("clan", $api->getSetting("prefix") . $api->getLang("clanprefix"), "/clan");
        $this->setPermission(Main::$defaultperm);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        $api = new BaseAPI();
        $pf = new Config($this->plugin->getDataFolder() . Main::$gruppefile . $sender->getName() . ".json", Config::JSON);
        $clan = new Config($this->plugin->getDataFolder() . Main::$clanfile . $pf->get("Clan") . ".json", Config::JSON);

        if (!$sender instanceof Player) {
            $sender->sendMessage($api->getSetting("error") . $api->getLang("commandingame"));
            return false;
        }
        if (isset($args[0])) {
            if (strtolower($args[0]) === "make") {
                if (isset($args[1])) {
					if($pf->get("ClanStatus") === true){
						$sender->sendMessage($api->getSetting("error") . "§cDu bist bereits in einem Clan daher kannst du keinen neuen erstellen! Lösche deinen clan erst bevor du ein neuen erstellst!");
						return true;
					} else {
						if (file_exists($this->plugin->getDataFolder() . Main::$clanfile . $args[1] . ".json")) {
							$sender->sendMessage($api->getSetting("error") . str_replace("{clan}", $args[1], $api->getLang("clanalreadyexist")));
						} else {
							$clan = new Config($this->plugin->getDataFolder() . Main::$clanfile . $args[1] . ".json", Config::JSON);
							$clan->set("Owner1", $sender->getName());
							$clan->set("Owner2", "");
							$clan->set("Owner3", "");
							$clan->set("player1", $sender->getName());
							$clan->set("player2", "");
							$clan->set("player3", "");
							$clan->set("player4", "");
							$clan->set("player5", "");
							$clan->set("player6", "");
							$clan->set("player7", "");
							$clan->set("player8", "");
							$clan->set("player9", "");
							$clan->set("player10", "");
							$clan->set("player11", "");
							$clan->set("player12", "");
							$clan->set("player13", "");
							$clan->set("player14", "");
							$clan->set("player15", "");
							$clan->set("Member", 1);
							$clan->save();
							$pf->set("Clan", $args[1]);
							$pf->set("ClanStatus", true);
							$pf->save();
							$sender->sendMessage($api->getSetting("clans") . str_replace("{clan}", $pf->get("Clan"), $api->getLang("clancreatet")));
						}
					}
                }
            } else if (strtolower($args[0]) === "list") {
                if ($pf->get("ClanStatus") === false) {
                    $sender->sendMessage($api->getSetting("error") . $api->getLang("clannoclan"));
                    return true;
                }
                if (isset($args[1])) {
                    $clan = new Config($this->plugin->getDataFolder() . Main::$clanfile . $args[1] . ".json");
                    if (file_exists($this->plugin->getDataFolder() . Main::$clanfile . $args[1] . ".json")) {
                        $sender->sendMessage($api->getSetting("error") . str_replace("{clan}", $args[1], $api->getLang("clannotexist")));
                        return false;
                    }
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
                    $form->setTitle($api->getLang("clanlistuititle"));
                    $form->setContent(
                        "§aClanname : " . $args[1] . "\n" .
                        "§4ClanOwner : " . $clan->get("Owner1") . "\n" .
                        "§cLeader1 : " . $clan->get("Owner2") . "\n" .
                        "§cLeader2 : " . $clan->get("Owner3") . "\n" .
                        "§eMember1 : " . $clan->get("player2") . "\n" .
                        "§eMember2 : " . $clan->get("player3") . "\n" .
                        "§eMember3 : " . $clan->get("player4") . "\n" .
                        "§eMember4 : " . $clan->get("player5") . "\n" .
                        "§eMember5 : " . $clan->get("player6") . "\n" .
                        "§eMember6 : " . $clan->get("player7") . "\n" .
                        "§eMember7 : " . $clan->get("player8") . "\n" .
                        "§eMember8 : " . $clan->get("player9") . "\n" .
                        "§eMember9 : " . $clan->get("player10") . "\n" .
                        "§eMember10 : " . $clan->get("player11") . "\n" .
                        "§eMember11 : " . $clan->get("player12") . "\n" .
                        "§eMember12 : " . $clan->get("player13") . "\n" .
                        "§eMember13 : " . $clan->get("player14") . "\n" .
                        "§eMember14 : " . $clan->get("player15"));
                    $form->addButton($api->getLang("clanlistuibutton"));
                    $form->sendToPlayer($sender);
                }
                $clanname = $pf->get("Clan");
                $clan = new Config($this->plugin->getDataFolder() . Main::$clanfile . $clanname . ".json");
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
                $form->setTitle($api->getLang("clanlistuititle"));
                $form->setContent(
                    "§aClanname : " . $clanname . "\n" .
                    "§4ClanOwner : " . $clan->get("Owner1") . "\n" .
                    "§cLeader1 : " . $clan->get("Owner2") . "\n" .
                    "§cLeader2 : " . $clan->get("Owner3") . "\n" .
                    "§eMember1 : " . $clan->get("player2") . "\n" .
                    "§eMember2 : " . $clan->get("player3") . "\n" .
                    "§eMember3 : " . $clan->get("player4") . "\n" .
                    "§eMember4 : " . $clan->get("player5") . "\n" .
                    "§eMember5 : " . $clan->get("player6") . "\n" .
                    "§eMember6 : " . $clan->get("player7") . "\n" .
                    "§eMember7 : " . $clan->get("player8") . "\n" .
                    "§eMember8 : " . $clan->get("player9") . "\n" .
                    "§eMember9 : " . $clan->get("player10") . "\n" .
                    "§eMember10 : " . $clan->get("player11") . "\n" .
                    "§eMember11 : " . $clan->get("player12") . "\n" .
                    "§eMember12 : " . $clan->get("player13") . "\n" .
                    "§eMember13 : " . $clan->get("player14") . "\n" .
                    "§eMember14 : " . $clan->get("player15"));
                $form->addButton($api->getLang("clanlistuibutton"));
                $form->sendToPlayer($sender);
            } else if (strtolower($args[0]) === "accept") {
                if ($pf->get("ClanAnfrage") === "") {
                    $sender->sendMessage($api->getSetting("error") . $api->getLang("clannotivitet"));
                } else {
                    $clan = new Config($this->plugin->getDataFolder() . Main::$clanfile . $pf->get("ClanAnfrage") . ".json", Config::JSON);
                    if ($clan->get("player2") === "") {
                        $clan->set("player2", $sender->getName());
                        $clan->set("Member", $clan->get("Member") + 1);
                        $clan->save();
                        $pf->set("Clan", $pf->get("ClanAnfrage"));
                        $pf->set("ClanStatus", true);
                        $pf->set("ClanAnfrage", "");
                        $pf->save();
                        $sender->sendMessage($api->getSetting("clans") . str_replace("{clan}", $pf->get("ClanAnfrage"), $api->getLang("claninvite")));
                    } else {
                        if ($clan->get("player3") === "") {
                            $clan->set("player3", $sender->getName());
                            $clan->set("Member", $clan->get("Member") + 1);
                            $clan->save();
                            $pf->set("Clan", $pf->get("ClanAnfrage"));
                            $pf->set("ClanStatus", true);
                            $pf->set("ClanAnfrage", "");
                            $pf->save();
                            $sender->sendMessage($api->getSetting("clans") . str_replace("{clan}", $pf->get("ClanAnfrage"), $api->getLang("claninvite")));
                        } else {
                            if ($clan->get("player4") === "") {
                                $clan->set("player4", $sender->getName());
                                $clan->set("Member", $clan->get("Member") + 1);
                                $clan->save();
                                $pf->set("Clan", $pf->get("ClanAnfrage"));
                                $pf->set("ClanStatus", true);
                                $pf->set("ClanAnfrage", "");
                                $pf->save();
                                $sender->sendMessage($api->getSetting("clans") . str_replace("{clan}", $pf->get("ClanAnfrage"), $api->getLang("claninvite")));
                            } else {
                                if ($clan->get("player5") === "") {
                                    $clan->set("player5", $sender->getName());
                                    $clan->set("Member", $clan->get("Member") + 1);
                                    $clan->save();
                                    $pf->set("Clan", $pf->get("ClanAnfrage"));
                                    $pf->set("ClanStatus", true);
                                    $pf->set("ClanAnfrage", "");
                                    $pf->save();
                                    $sender->sendMessage($api->getSetting("clans") . str_replace("{clan}", $pf->get("ClanAnfrage"), $api->getLang("claninvite")));
                                } else {
                                    if ($clan->get("player6") === "") {
                                        $clan->set("player6", $sender->getName());
                                        $clan->set("Member", $clan->get("Member") + 1);
                                        $clan->save();
                                        $pf->set("Clan", $pf->get("ClanAnfrage"));
                                        $pf->set("ClanStatus", true);
                                        $pf->set("ClanAnfrage", "");
                                        $pf->save();
                                        $sender->sendMessage($api->getSetting("clans") . str_replace("{clan}", $pf->get("ClanAnfrage"), $api->getLang("claninvite")));
                                    } else {
                                        if ($clan->get("player7") === "") {
                                            $clan->set("player7", $sender->getName());
                                            $clan->set("Member", $clan->get("Member") + 1);
                                            $clan->save();
                                            $pf->set("Clan", $pf->get("ClanAnfrage"));
                                            $pf->set("ClanStatus", true);
                                            $pf->set("ClanAnfrage", "");
                                            $pf->save();
                                            $sender->sendMessage($api->getSetting("clans") . str_replace("{clan}", $pf->get("ClanAnfrage"), $api->getLang("claninvite")));
                                        } else {
                                            if ($clan->get("player8") === "") {
                                                $clan->set("player8", $sender->getName());
                                                $clan->set("Member", $clan->get("Member") + 1);
                                                $clan->save();
                                                $pf->set("Clan", $pf->get("ClanAnfrage"));
                                                $pf->set("ClanStatus", true);
                                                $pf->set("ClanAnfrage", "");
                                                $pf->save();
                                                $sender->sendMessage($api->getSetting("clans") . str_replace("{clan}", $pf->get("ClanAnfrage"), $api->getLang("claninvite")));
                                            } else {
                                                if ($clan->get("player9") === "") {
                                                    $clan->set("player9", $sender->getName());
                                                    $clan->set("Member", $clan->get("Member") + 1);
                                                    $clan->save();
                                                    $pf->set("Clan", $pf->get("ClanAnfrage"));
                                                    $pf->set("ClanStatus", true);
                                                    $pf->set("ClanAnfrage", "");
                                                    $pf->save();
                                                    $sender->sendMessage($api->getSetting("clans") . str_replace("{clan}", $pf->get("ClanAnfrage"), $api->getLang("claninvite")));
                                                } else {
                                                    if ($clan->get("player10") === "") {
                                                        $clan->set("player10", $sender->getName());
                                                        $clan->set("Member", $clan->get("Member") + 1);
                                                        $clan->save();
                                                        $pf->set("Clan", $pf->get("ClanAnfrage"));
                                                        $pf->set("ClanStatus", true);
                                                        $pf->set("ClanAnfrage", "");
                                                        $pf->save();
                                                        $sender->sendMessage($api->getSetting("clans") . str_replace("{clan}", $pf->get("ClanAnfrage"), $api->getLang("claninvite")));
                                                    } else {
                                                        if ($clan->get("player11") === "") {
                                                            $clan->set("player11", $sender->getName());
                                                            $clan->set("Member", $clan->get("Member") + 1);
                                                            $clan->save();
                                                            $pf->set("Clan", $pf->get("ClanAnfrage"));
                                                            $pf->set("ClanStatus", true);
                                                            $pf->set("ClanAnfrage", "");
                                                            $pf->save();
                                                            $sender->sendMessage($api->getSetting("clans") . str_replace("{clan}", $pf->get("ClanAnfrage"), $api->getLang("claninvite")));
                                                        } else {
                                                            if ($clan->get("player12") === "") {
                                                                $clan->set("player12", $sender->getName());
                                                                $clan->set("Member", $clan->get("Member") + 1);
                                                                $clan->save();
                                                                $pf->set("Clan", $pf->get("ClanAnfrage"));
                                                                $pf->set("ClanStatus", true);
                                                                $pf->set("ClanAnfrage", "");
                                                                $pf->save();
                                                                $sender->sendMessage($api->getSetting("clans") . str_replace("{clan}", $pf->get("ClanAnfrage"), $api->getLang("claninvite")));
                                                            } else {
                                                                if ($clan->get("player13") === "") {
                                                                    $clan->set("player13", $sender->getName());
                                                                    $clan->set("Member", $clan->get("Member") + 1);
                                                                    $clan->save();
                                                                    $pf->set("Clan", $pf->get("ClanAnfrage"));
                                                                    $pf->set("ClanStatus", true);
                                                                    $pf->set("ClanAnfrage", "");
                                                                    $pf->save();
                                                                    $sender->sendMessage($api->getSetting("clans") . str_replace("{clan}", $pf->get("ClanAnfrage"), $api->getLang("claninvite")));
                                                                } else {
                                                                    if ($clan->get("player14") === "") {
                                                                        $clan->set("player14", $sender->getName());
                                                                        $clan->set("Member", $clan->get("Member") + 1);
                                                                        $clan->save();
                                                                        $pf->set("Clan", $pf->get("ClanAnfrage"));
                                                                        $pf->set("ClanStatus", true);
                                                                        $pf->set("ClanAnfrage", "");
                                                                        $pf->save();
                                                                        $sender->sendMessage($api->getSetting("clans") . str_replace("{clan}", $pf->get("ClanAnfrage"), $api->getLang("claninvite")));
                                                                    } else {
                                                                        if ($clan->get("player15") === "") {
                                                                            $clan->set("player15", $sender->getName());
                                                                            $clan->set("Member", $clan->get("Member") + 1);
                                                                            $clan->save();
                                                                            $pf->set("Clan", $pf->get("ClanAnfrage"));
                                                                            $pf->set("ClanStatus", true);
                                                                            $pf->set("ClanAnfrage", "");
                                                                            $pf->save();
                                                                            $sender->sendMessage($api->getSetting("clans") . str_replace("{clan}", $pf->get("ClanAnfrage"), $api->getLang("claninvite")));
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            } else if (strtolower($args[0]) === "delete") {
                if (empty($args[1])) {
                    $sender->sendMessage($api->getSetting("clans") . str_replace("{clan}", $pf->get("Clan"), $api->getLang("clandelete")));
                }
                if (isset($args[1]) and $args[1] == "confirm") {
                    if ($pf->get("ClanStatus") === false) {
                        $sender->sendMessage($api->getSetting("error") . $api->getLang("clannoclan"));
                        return true;
                    }
                    if ($pf->get("ClanStatus") === true) {
                        if ($clan->get("Owner1") === $sender->getName()) {
                            $deleteclan = $pf->get("Clan");
                            $members = array("player1", "player2", "player3", "player4", "player5", "player6", "player7", "player8", "player9", "player10", "player11", "player12", "player13", "player14", "player15");
                            foreach($members as $member){
                                if($clan->get($member) !== ""){
                                    $pf = new Config($this->plugin->getDataFolder() . Main::$gruppefile . $clan->get($member) . ".json", Config::JSON);
                                    $pf->set("Clan", "No Clan");
                                    $pf->set("ClanStatus", false);
                                    $pf->save();
                                }
                            }
                            $sender->sendMessage($api->getSetting("clans") . str_replace("{clan}", $deleteclan, $api->getLang("clandeleteconfirm")));
                            unlink($this->plugin->getDataFolder() . Main::$clanfile . $deleteclan . ".json");
                        } else {
                            $sender->sendMessage($api->getSetting("error") . $api->getLang("clancantdelete"));
                        }
                    }
                }
            } else if (strtolower($args[0]) === "leave") {
                if (empty($args[1])) {
                    $sender->sendMessage($api->getSetting("clans") . str_replace("{clan}", $pf->get("Clan"), $api->getLang("clanleave")));
                }
                if (isset($args[1]) and $args[1] == "confirm") {
                    if ($pf->get("ClanStatus") === false) {
                        $sender->sendMessage($api->getSetting("clans") . $api->getLang("clannoclan"));
                        return false;
                    }
                    if ($clan->get("Owner1") === $sender->getName()) {
                        $sender->sendMessage($api->getSetting("error") . str_replace("{clan}", $pf->get("Clan"), $api->getLang("clanleaveowner")));
                    } else {
                        if ($clan->get("Owner2") === $sender->getName()) {
                            $clan->set("Owner2", "");
                            $clan->set("Member", $clan->get("Member") - 1);
                            $clan->save();
                            $pf->set("Clan", "No Clan");
                            $pf->set("ClanStatus", false);
                            $pf->save();
                            $sender->sendMessage($api->getSetting("clans") . str_replace("{clan}", $pf->get("Clan"), $api->getLang("clanleaveconfirm")));
                        } else {
                            if ($clan->get("Owner3") === $sender->getName()) {
                                $clan->set("Owner3", "");
                                $clan->set("Member", $clan->get("Member") - 1);
                                $clan->save();
                                $pf->set("Clan", "No Clan");
                                $pf->set("ClanStatus", false);
                                $pf->save();
                                $sender->sendMessage($api->getSetting("clans") . str_replace("{clan}", $pf->get("Clan"), $api->getLang("clanleaveconfirm")));
                            } else {
                                if ($clan->get("player1") === $sender->getName()) {
                                    $clan->set("player1", "");
                                    $clan->set("Member", $clan->get("Member") - 1);
                                    $clan->save();
                                    $pf->set("Clan", "No Clan");
                                    $pf->set("ClanStatus", false);
                                    $pf->save();
                                    $sender->sendMessage($api->getSetting("clans") . str_replace("{clan}", $pf->get("Clan"), $api->getLang("clanleaveconfirm")));
                                } else {
                                    if ($clan->get("player2") === $sender->getName()) {
                                        $clan->set("player2", "");
                                        $clan->set("Member", $clan->get("Member") - 1);
                                        $clan->save();
                                        $pf->set("Clan", "No Clan");
                                        $pf->set("ClanStatus", false);
                                        $pf->save();
                                        $sender->sendMessage($api->getSetting("clans") . str_replace("{clan}", $pf->get("Clan"), $api->getLang("clanleaveconfirm")));
                                    } else {
                                        if ($clan->get("player3") === $sender->getName()) {
                                            $clan->set("player3", "");
                                            $clan->set("Member", $clan->get("Member") - 1);
                                            $clan->save();
                                            $pf->set("Clan", "No Clan");
                                            $pf->set("ClanStatus", false);
                                            $pf->save();
                                            $sender->sendMessage($api->getSetting("clans") . str_replace("{clan}", $pf->get("Clan"), $api->getLang("clanleaveconfirm")));
                                        } else {
                                            if ($clan->get("player4") === $sender->getName()) {
                                                $clan->set("player4", "");
                                                $clan->set("Member", $clan->get("Member") - 1);
                                                $clan->save();
                                                $pf->set("Clan", "No Clan");
                                                $pf->set("ClanStatus", false);
                                                $pf->save();
                                                $sender->sendMessage($api->getSetting("clans") . str_replace("{clan}", $pf->get("Clan"), $api->getLang("clanleaveconfirm")));
                                            } else {
                                                if ($clan->get("player5") === $sender->getName()) {
                                                    $clan->set("player5", "");
                                                    $clan->set("Member", $clan->get("Member") - 1);
                                                    $clan->save();
                                                    $pf->set("Clan", "No Clan");
                                                    $pf->set("ClanStatus", false);
                                                    $pf->save();
                                                    $sender->sendMessage($api->getSetting("clans") . str_replace("{clan}", $pf->get("Clan"), $api->getLang("clanleaveconfirm")));
                                                } else {
                                                    if ($clan->get("player6") === $sender->getName()) {
                                                        $clan->set("player6", "");
                                                        $clan->set("Member", $clan->get("Member") - 1);
                                                        $clan->save();
                                                        $pf->set("Clan", "No Clan");
                                                        $pf->set("ClanStatus", false);
                                                        $pf->save();
                                                        $sender->sendMessage($api->getSetting("clans") . str_replace("{clan}", $pf->get("Clan"), $api->getLang("clanleaveconfirm")));
                                                    } else {
                                                        if ($clan->get("player7") === $sender->getName()) {
                                                            $clan->set("player7", "");
                                                            $clan->set("Member", $clan->get("Member") - 1);
                                                            $clan->save();
                                                            $pf->set("Clan", "No Clan");
                                                            $pf->set("ClanStatus", false);
                                                            $pf->save();
                                                            $sender->sendMessage($api->getSetting("clans") . str_replace("{clan}", $pf->get("Clan"), $api->getLang("clanleaveconfirm")));
                                                        } else {
                                                            if ($clan->get("player8") === $sender->getName()) {
                                                                $clan->set("player8", "");
                                                                $clan->set("Member", $clan->get("Member") - 1);
                                                                $clan->save();
                                                                $pf->set("Clan", "No Clan");
                                                                $pf->set("ClanStatus", false);
                                                                $pf->save();
                                                                $sender->sendMessage($api->getSetting("clans") . str_replace("{clan}", $pf->get("Clan"), $api->getLang("clanleaveconfirm")));
                                                            } else {
                                                                if ($clan->get("player9") === $sender->getName()) {
                                                                    $clan->set("player9", "");
                                                                    $clan->set("Member", $clan->get("Member") - 1);
                                                                    $clan->save();
                                                                    $pf->set("Clan", "No Clan");
                                                                    $pf->set("ClanStatus", false);
                                                                    $pf->save();
                                                                    $sender->sendMessage($api->getSetting("clans") . str_replace("{clan}", $pf->get("Clan"), $api->getLang("clanleaveconfirm")));
                                                                } else {
                                                                    if ($clan->get("player10") === $sender->getName()) {
                                                                        $clan->set("player10", "");
                                                                        $clan->set("Member", $clan->get("Member") - 1);
                                                                        $clan->save();
                                                                        $pf->set("Clan", "No Clan");
                                                                        $pf->set("ClanStatus", false);
                                                                        $pf->save();
                                                                        $sender->sendMessage($api->getSetting("clans") . str_replace("{clan}", $pf->get("Clan"), $api->getLang("clanleaveconfirm")));
                                                                    } else {
                                                                        if ($clan->get("player11") === $sender->getName()) {
                                                                            $clan->set("player11", "");
                                                                            $clan->set("Member", $clan->get("Member") - 1);
                                                                            $clan->save();
                                                                            $pf->set("Clan", "No Clan");
                                                                            $pf->set("ClanStatus", false);
                                                                            $pf->save();
                                                                            $sender->sendMessage($api->getSetting("clans") . str_replace("{clan}", $pf->get("Clan"), $api->getLang("clanleaveconfirm")));
                                                                        } else {
                                                                            if ($clan->get("player12") === $sender->getName()) {
                                                                                $clan->set("player12", "");
                                                                                $clan->set("Member", $clan->get("Member") - 1);
                                                                                $clan->save();
                                                                                $pf->set("Clan", "No Clan");
                                                                                $pf->set("ClanStatus", false);
                                                                                $pf->save();
                                                                                $sender->sendMessage($api->getSetting("clans") . str_replace("{clan}", $pf->get("Clan"), $api->getLang("clanleaveconfirm")));
                                                                            }
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            } else if (strtolower($args[0]) === "leader1") {
                if (isset($args[1])) {
                    if ($pf->get("ClanStatus") === false) {
                        $sender->sendMessage($api->getSetting("error") . $api->getLang("clannoclan"));
                    } else {
                        if (file_exists($this->plugin->getDataFolder() . Main::$gruppefile . $args[1] . ".json")) {
                            if ($sender->getName() === $clan->get("Owner1")) {
                                $sf = new Config($this->plugin->getDataFolder() . Main::$gruppefile . $args[1] . ".json", Config::JSON);
                                if ($sf->get("Clan") === $pf->get("Clan")) {
                                    $clan->set("Owner2", $api->findPlayer($sender, $args[1]));
                                    $clan->save();
                                    $sender->sendMessage($api->getSetting("clans") . str_replace("{player}", $args[1], $api->getLang("clanleaderadd")));
                                } else {
                                    $sender->sendMessage($api->getSetting("error") . str_replace("{player}", $args[1], $api->getLang("clanpmoinclan")));
                                }
                            } else {
                                $sender->sendMessage($api->getSetting("error") . $api->getLang("clannoowner"));
                            }
                        } else {
                            $sender->sendMessage($api->getSetting("error") . str_replace("{player}", $args[1], $api->getLang("clanpnoexist")));
                        }
                    }
                }
            } else if (strtolower($args[0]) === "leader2") {
                if (isset($args[1])) {
                    if ($pf->get("ClanStatus") === false) {
                        $sender->sendMessage($api->getSetting("error") . $api->getLang("clannoclan"));
                    } else {
                        if (file_exists($this->plugin->getDataFolder() . Main::$gruppefile . $args[1] . ".yml")) {
                            if ($sender->getName() === $clan->get("Owner1")) {
                                $sf = new Config($this->plugin->getDataFolder() . Main::$gruppefile . $args[1] . ".json", Config::JSON);
                                if ($sf->get("Clan") === $pf->get("Clan")) {
                                    $clan->set("Owner3", $api->findPlayer($sender, $args[1]));
                                    $clan->save();
                                    $sender->sendMessage($api->getSetting("clans") . str_replace("{player}", $args[1], $api->getLang("clanleaderadd")));
                                } else {
                                    $sender->sendMessage($api->getSetting("error") . str_replace("{player}", $args[1], $api->getLang("clanpmoinclan")));
                                }
                            } else {
                                $sender->sendMessage($api->getSetting("error") . $api->getLang("clannoowner"));
                            }
                        } else {
                            $sender->sendMessage($api->getSetting("error") . str_replace("{player}", $args[1], $api->getLang("clanpnoexist")));
                        }
                    }
                }
            } else if (strtolower($args[0]) === "add") {
                if (isset($args[1])) {
                    if ($pf->get("ClanStatus") === false) {
                        $sender->sendMessage($api->getSetting("error") . $api->getLang("clannoclan"));
                    } else {
                        //$v = $api->findPlayer($sender, $args[1]);
                        //if (file_exists($this->plugin->getDataFolder() . Main::$gruppefile . $args[1] . ".json")) {
                            if ($sender->getName() === $clan->get("Owner1")) {
                                if ($clan->get("Member") === 15) {
                                    $sender->sendMessage($api->getSetting("error") . $api->getLang("clanfull"));
                                } else {
                                    $sf = new Config($this->plugin->getDataFolder() . Main::$gruppefile . $args[1] . ".json", Config::JSON);
                                    if ($sf->get("ClanStatus") === false) {
                                        $v = $api->findPlayer($sender, $args[1]);

                                        if (!$v == null) {
                                            $sf->set("ClanAnfrage", $pf->get("Clan"));
                                            $sf->save();
                                            $v->sendMessage($api->getSetting("clans") . str_replace("{clan}", $sf->get("ClanAnfrage"), $api->getLang("claninviteaccept")));
                                            $sender->sendMessage($api->getSetting("clans") . str_replace("{player}", $v->getName(), $api->getLang("clanaddplayer")));
                                        } else {
                                            $sender->sendMessage($api->getSetting("error") . $api->getLang("playernotonline"));
                                        }
                                    } else {
                                        $sender->sendMessage($api->getSetting("error") . str_replace("{player}", $args[1], $api->getLang("clanpisinclan")));
                                    }
                                }
                            } else {
                                $sender->sendMessage($api->getSetting("error") .  $api->getLang("clannoowner"));
                            }
                        //} else {
                          //  $sender->sendMessage($api->getSetting("error") . str_replace("{player}", $args[1], $api->getLang("clanpnoexist")));
                        //}
                    }
                }
            }
        } else {
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
            $form->setTitle($api->getLang("clanlistuititle"));
            $form->setContent("§e/clan make (ClanName)\n" .
                "§e/clan delete confirm » Lösche deinen Clan\n" .
                "§e/clan leave confirm » Verlasse einen Clan\n" .
                "§e/clan add (player) » Füge einen Spieler deinen Clan Hinzu\n" .
                "§e/clan accept (ClanName) » Bestätige die Clananfrage\n" .
                "§e/clan list » Liste den Clan auf\n" .
                "§e/clan list (Clanname) » Liste ein Clan auf\n" .
                "§e/clan leader1 (player) » Befördere einen Spieler zum Ersten Officier\n" .
                "§e/clan leader2 (player) » Befördere einen Spieler zum Zweiten Officier");
            $form->addButton($api->getLang("clanlistuibutton"));
            $form->sendToPlayer($sender);
            return true;
        }
        return true;
    }
}
//last edit by Rudolf2000 : 15.11.2021 15:02