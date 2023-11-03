<?php

namespace Testament;

use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\item\VanillaItems;
use pocketmine\player\Player;

class Main extends PluginBase implements Listener{
    public function onEnable() : void{
        $this->saveDefaultConfig();
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function onDeath(PlayerDeathEvent $event){
        $player = $event->getPlayer();

        $cause = $event->getPlayer()->getLastDamageCause();
        if($cause instanceof EntityDamageEvent){
            if($cause instanceof EntityDamageByEntityEvent){
                $killer = $cause->getDamager();
                if($killer instanceof Player){
                    $killerName = $killer->getName();
                }else{
                    $killerName = $killer->getNameTag();
                }
            }else{
                $killerName = $this->getConfig()->get("only");
            }
        }else{
            $killerName = $this->getConfig()->get("only");
        }
        $drops = $event->getDrops();
        array_push($drops, VanillaItems::PAPER()->setCustomName(
            str_replace([
                "{player}",
                "{killer}"
            ],[
                $player->getName(),
                $killerName
            ], $this->getConfig()->get("customname"))
        )->setLore([
            str_replace([
                "{player}",
                "{killer}"
            ],[
                $player->getName(),
                $killerName
            ], $this->getConfig()->get("customlore"))
        ]));
        $event->setDrops($drops);
    }
}