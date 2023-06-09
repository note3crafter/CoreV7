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

class CreditsCommand extends Command
{
    private $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        parent::__construct("credits", "§f[§4Core§eV7§f] §6Credits", "/credits");
        $this->setPermission(Main::$defaultperm);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        $api = new BaseAPI();
        if (!$sender instanceof Player) {
            $sender->sendMessage($api->getSetting("error") . $api->getLang("commandingame"));
            return false;
        }
        $form = new SimpleForm(function (Player $sender, $data) {
            $result = $data;
            if ($result === null) {
                return true;
            }
            switch ($result) {
                case 0:
                    break;
            }
        });
        $form->setTitle("§0===§f[§6Credits§f]§0==§f[§eCore§4V6§f]§0===");
        $form->setContent("§e- TheNote/Rudolf2000/note3crafter #Inhaber der Core\n" .
                    "§e- tim03we #BanSystem usw\n" .
                    "§e- xxflow #Heiraten,Payall\n" .
                    "§e- Aenoxic #Grundgerüst\n" .
                    "§e- FleekRush #Booster\n" .
                    "§e- JackMD #Discord, FormAPI\n" .
                    "§e- LookItsAku #Hilfe\n" .
                    "§e- Hagnbrain #homesystem\n" .
                    "§e- EnderDirt #füraltecodes\n" .
                    "§e- Crasher508 #Fixxer\n" .
                    "§e- MDevPmmP #GroupSystem/EconomySystem\n" .
                    "§e- jojoe77777 #FormAPI\n" .
                    "§e- muqsit #InvMenü\n");
        $form->addButton("§0OK", 0);
        $form->sendToPlayer($sender);
        return true;
    }
}