<?php


declare(strict_types = 1);

namespace friscowz\hc\entity;

use pocketmine\item\enchantment\Enchantment;
use pocketmine\entity\Monster;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\item\Item;
use pocketmine\Player;

class PigZombie extends Monster {
	const NETWORK_ID = self::ZOMBIE_PIGMAN;

	public $width = 0.6;
	public $length = 0.6;
	public $height = 1.8;

	public function getName(): string{
		return "Zombie Pigman";
	}

	public function getDrops(): array{
		$cause = $this->lastDamageCause;
		if($cause instanceof EntityDamageByEntityEvent){
			$damager = $cause->getDamager();
			if($damager instanceof Player){
				$looting = $damager->getInventory()->getItemInHand()->getEnchantment(Enchantment::LOOTING);
				if($looting !== null){
					$lootingL = $looting->getLevel();
				}else{
					$lootingL = 0;
				}
				$drops = [
					Item::get(Item::GOLD_NUGGET, 0, mt_rand(0, 1 + $lootingL)),
					Item::get(Item::ROTTEN_FLESH, 0, mt_rand(0, 1 + $lootingL)),
				];

				if(mt_rand(1, 200) <= (5 + 2 * $lootingL)){
					$drops[] = Item::get(Item::GOLD_INGOT, 0, 1);
				}

				return $drops;
			}
		}

		return [];
	}
}