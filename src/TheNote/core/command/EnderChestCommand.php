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

use muqsit\invmenu\InvMenu;
use muqsit\invmenu\transaction\InvMenuTransaction;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\inventory\Inventory;
use pocketmine\player\Player;
use TheNote\core\BaseAPI;
use TheNote\core\Main;

class EnderChestCommand extends Command
{
    private $plugin;
    private $tName;
    private $inv;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $api = new BaseAPI();
        parent::__construct("ec", $api->getSetting("prefix") . $api->getLang("ecprefix"), "/ec", ["enderchest"]);
        $this->setPermission("core.command.enderchest");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        $api = new BaseAPI();
        if (!$sender instanceof Player) {
            $sender->sendMessage($api->getSetting("error") . $api->getLang("commandingame"));
            return false;
        }
        if (!$this->testPermission($sender)) {
            $sender->sendMessage($api->getSetting("error") . $api->getLang("nopermission"));
            return false;
        }
        $this->tName = "";
        $tName = $sender->getName();
        $this->tName = "$tName";
        $sender->sendMessage($api->getSetting("prefix") . $api->getLang("ecopen"));
        $this->send($sender);
        return true;
    }

    public function send($sender)
    {
        $menu = InvMenu::create(InvMenu::TYPE_CHEST);
        $inv = $menu->getInventory();
        $menu->setName($this->tName . "'s Enderchest");
        $target = $this->plugin->getServer()->getPlayerExact($this->tName);
        $content = $target->getEnderInventory()->getContents();
        $this->inv = $menu;
        $inv->setContents($content);
        $menu->setListener(function (InvMenuTransaction $transaction) use ($sender): InvMenuTransactionResult {
            $inv = $this->inv->getInventory();
            $target = $this->plugin->getServer()->getPlayerExact($this->tName);
            if ($target->getName() !== $sender->getName()) {
                return $transaction->discard();
            } else {
                $nContents = $inv->getContents();
                $sender->getEnderInventory()->setContents($nContents);
                return $transaction->continue();
            }
        });
        $menu->setInventoryCloseListener(function (Player $sender, Inventory $inventory): void {
            if ($this->tName == $sender->getName()) {
                $nContents = $inventory->getContents();
                $sender->getEnderInventory()->setContents($nContents);
            }
        });
        $menu->send($sender);
    }
}