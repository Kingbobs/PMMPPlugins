<?php
namespace friscowz\hc\enchants;

use friscowz\hc\MDPlayer;
use pocketmine\Player;

use pocketmine\event\Listener;

use pocketmine\item\enchantment\Enchantment;

use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityShootBowEvent;
use pocketmine\event\player\PlayerInteractEvent;

use friscowz\hc\Myriad;

class Unbreaking extends VanillaEnchant implements Listener{

    public function __construct(Myriad $plugin){
        $plugin->getServer()->getPluginManager()->registerEvents($this, $plugin);
    }
	
	/*
	 * @void onBlockBreak
	 * @param BlockBreakEvent $event
	 * @priority MEDIUM
	 * ignoreCancelled false
	 */
	
	public function onBlockBreak(BlockBreakEvent $event): void{
	    $player = $event->getPlayer();
	    $item = $player->getInventory()->getItemInHand();
	    if(!$event->isCancelled() and $item->hasEnchantment(Enchantment::UNBREAKING) and rand(1, 5) == 2){
	      $fix = $item->getEnchantment(Enchantment::UNBREAKING)->getLevel() + 1;
	      $meta = ($item->getDamage() - $fix) >= 0 ? $item->getDamage() - $fix : 0;
	      $item->setDamage($meta);
	      $player->getInventory()->setItemInHand($item);
	   }
	}
	
	/*
	 * @void onInteract
	 * @param PlayerInteractEvent $event
	 * @priority MEDIUM
	 * ignoreCancelled false
	 */
	
	public function onInteract(PlayerInteractEvent $event): void{
	    $player = $event->getPlayer();
	    $item = $player->getInventory()->getItemInHand();
	    $block = $event->getBlock();
	    if($event->isCancelled() == false){
		   if($item->isHoe() and in_array($block->getId(), [2, 3])){
			  if($item->hasEnchantment(Enchantment::UNBREAKING) and rand(0, 5) == 3){
				 $fix = $item->getEnchantment(Enchantment::UNBREAKING)->getLevel() + 1;
			    $meta = ($item->getDamage() - $fix) >= 0 ? $item->getDamage() - $fix : 0;
			    $item->setDamage($meta);
			    $player->getInventory()->setItemInHand($item);
				}
			}
		}
	}
	
	/*
	 * @void onDamage
	 * @param EntityDamageEvent $event
	 * @priority MEDIUM
	 * ignoreCancelled false
	 */
	
	public function onDamage(EntityDamageEvent $event): void{
	    $player = $event->getEntity();
	    if($player instanceof MDPlayer and !$event->isCancelled()) {
            $this->useArmors($player);
        }
	    if($player instanceof Player and !$event->isCancelled()){
		   $inv = $player->getInventory();
		   $helmet = $inv->getHelmet();
		   $chest = $inv->getChestplate();
		   $leg = $inv->getLeggings();
		   $boots = $inv->getBoots();
		    if($helmet->hasEnchantment(Enchantment::UNBREAKING) and rand(0, 5) == 3){
			  $lvl = $this->getEnchantmentLevel($helmet, Enchantment::UNBREAKING) + 1;
			  $this->addHelmetDurability($player, $lvl);
            }
			if($chest->hasEnchantment(Enchantment::UNBREAKING) and rand(0, 5) == 3){
			  $lvl = $this->getEnchantmentLevel($chest, Enchantment::UNBREAKING) + 1;
			  $this->addChestplateDurability($player, $lvl);
			}
			if($leg->hasEnchantment(Enchantment::UNBREAKING) and rand(0, 5) == 3){
			  $lvl = $this->getEnchantmentLevel($leg, Enchantment::UNBREAKING) + 1;
			  $this->addLeggingsDurability($player, $lvl);
			}
			if($boots->hasEnchantment(Enchantment::UNBREAKING) and rand(0, 5) == 3){
			  $lvl = $this->getEnchantmentLevel($boots, Enchantment::UNBREAKING) + 1;
			  $this->addBootsDurability($player, $lvl);
			}
		}
	}
	
	/*
	 * @void onItemDamage
	 * @param EntityDamageEvent $event
	 * @priority MEDIUM
	 * ignoreCancelled false
	 */
	
	public function onItemDamage(EntityDamageEvent $event): void{
	    if($event instanceof EntityDamageByEntityEvent){
		   $player = $event->getEntity();
		   $damager = $event->getDamager();
		   if(!$damager instanceof Player){
			  return;
			}
			$item = $damager->getInventory()->getItemInHand();
			if($item->hasEnchantment(Enchantment::UNBREAKING) and rand(0, 5) == 3){
			  $fix = $item->getEnchantment(Enchantment::UNBREAKING)->getLevel() + 1;
			  $meta = ($item->getDamage() - $fix) >= 0 ? $item->getDamage() - $fix : 0;
			  $item->setDamage($meta);
			  $damager->getInventory()->setItemInHand($item);
			}
	   }
	}
	
	/*
	 * @void onShoot
	 * @param EntityShootBowEvent $event
	 * @priority HIGH
	 * ignoreCancelled false
	 */

   public function onShoot(EntityShootBowEvent $event): void{
        $player = $event->getEntity();
        $bow = $event->getBow();
        if(!$event->isCancelled() and $bow->hasEnchantment(Enchantment::UNBREAKING) and rand(0, 5) == 2 and $player instanceof Player){
	       $fix = $bow->getEnchantment(Enchantment::UNBREAKING)->getLevel() + 1;
	       $meta = ($bow->getDamage() - $fix) >= 0 ? $bow->getDamage() - $fix : 0;
	       $bow->setDamage($meta);
	       $player->getInventory()->setItemInHand($bow);
	   }
   }
}