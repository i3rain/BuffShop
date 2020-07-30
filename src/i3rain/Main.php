<?php

namespace i3rain;

use pocketmine\plugin\PluginBase;
use pcoketmine\Server;
use pocketmine\Player;
use pocketmine\event\Listener;
use pocketmine\entity\Entity;
use pocketmine\entity\Effect;
use pocketmine\entity\EffectInstance;
use pocketmine\event\PlayerJoinEvent;
use pocketmine\event\PlayerQuitEvent;
use pocketmine\event\PlayerChatEvent;
use pocketmine\network\mcpe\protocol\ModalFormRequestPacket;
use pocketmine\network\mcpe\protocol\ModalFormResponsePacket;
use pocketmine\network\mcpe\protocol\RemoveObjectivePacket;
use pocketmine\network\mcpe\protocol\SetDisplayObjetivePacket;
use pocketmine\network\mcpe\protocol\SetScorePacket;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat as text;

class Main extends PluginBase implements Listener{

    const PREFIX = "§0[§bBuffs§0] §f: ";

    public $effects;
    public $costs;
    public $messages;

    public function onEnable(){
        $this->getServer()->getLogger()->info(self::PREFIX . " wurde Erfolgreich aktiviert!");
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->effects = new Config($this->getDataFolder() . 'effects.yml', Config::YAML);
        $this->effects->save();
        $this->costs = new Config($this->getDataFolder() . 'costs.yml', Config::YAML);
        $this->costs->save();
        $this->messages = new Config($this->getDataFolder() . 'messages.yml', Config::YAML);
        $this->messages->save();
    }

    public function onLoad(){
		$this->CheckConfig(2.0);
	}

    public function CheckConfig($version){
		$costspath = $this->getDataFolder() . "costs.yml";
        $effectspath = $this->getDataFolder() . "effects.yml";
        $messagespath = $this->getDataFolder() . "messages.yml";
		if (file_exists($costspath & $effectspath & $messagespath)) {
			$cfgs = $this->getConfig()->get("version");
			if($cfgs !== $version){
				$this->saveResource("costs.yml");
                $this->saveResource("effects.yml");
                $this->saveResource("messages.yml");
                $this->saveResource("tutorial.yml");
			}
		}else {
			$this->saveResource("costs.yml");
            $this->saveResource("effects.yml");
            $this->saveResource("messages.yml");
            $this->saveResource("tutorial.yml");
		}
	}

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args) : bool{
        switch($command->getName()){
            case "buffs":
                if($sender instanceof Player){
                    $this->BuffMenu($sender);
            }
            return true;
        }
    }

    public function BuffMenu(Player $player){
        $api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $api->createSimpleForm(function (Player $player, int $data = null){
            $result = $data;
            if ($result === null){
                return true;
            }else {
                switch ($result) {
                    case 0:
                    if ($player->getXpLevel() === $this->costs->get("BuffOneXPCost") | $player->getXpLevel() > $this->costs->get("BuffOneXPCost")){
                        $this->BuffOne($player);
                    }else{
                        $player->sendMessage(self::PREFIX . $this->messages->get("NoXP"));
                    }
                break;
                    case 1:
                    if ($player->getXpLevel() === $this->costs->get("BuffTwoXPCost") | $player->getXpLevel() > $this->costs->get("BuffTwoXPCost")){
                        $this->BuffTwo($player);
                    }else{
                        $player->sendMessage(self::PREFIX . $this->messages->get("NoXP"));
                    }
                break;
                    case 2:
                    if ($player->getXpLevel() === $this->costs->get("BuffThreeXPCost") | $player->getXpLevel() > $this->costs->get("BuffThreeXPCost")){
                        $this->BuffThree($player);
                    }else{
                        $player->sendMessage(self::PREFIX . $this->messages->get("NoXP"));
                    }
                break;
                    case 3:
                    if ($player->getXpLevel() === $this->costs->get("BuffFourXPCost") | $player->getXpLevel() > $this->costs->get("BuffFourXPCost")){
                        $this->BuffFour($player);
                    }else{
                        $player->sendMessage(self::PREFIX . $this->messages->get("NoXP"));
                    }
                break;
                    case 4:
                    if ($player->getXpLevel() === $this->costs->get("BuffFiveXPCost") | $player->getXpLevel() > $this->costs->get("BuffFiveXPCost")){
                        $this->BuffFive($player);
                    }else{
                        $player->sendMessage(self::PREFIX . $this->messages->get("NoXP"));
                    }
                break;
                    case 5:
                    if ($player->getXpLevel() === $this->costs->get("BuffSixXPCost") | $player->getXpLevel() > $this->costs->get("BuffSixXPCost")){
                        $this->BuffSix($player);
                    }else{
                        $player->sendMessage(self::PREFIX . $this->messages->get("NoXP"));
                    }
                break;
                    case 6:
                    if ($player->getXpLevel() === $this->costs->get("BuffSevenXPCost") | $player->getXpLevel() > $this->costs->get("BuffSevenXPCost")){
                        $this->BuffSeven($player);
                    }else{
                        $player->sendMessage(self::PREFIX . $this->messages->get("NoXP"));
                    }
                break;
                    case 7:
                    if ($player->getXpLevel() === $this->costs->get("BuffEightXPCost") | $player->getXpLevel() > $this->costs->get("BuffEightXPCost")){
                        $this->BuffEight($player);
                    }else{
                        $player->sendMessage(self::PREFIX . $this->messages->get("NoXP"));
                    }
                break;
                    case 8:
                        $player->sendMessage(self::PREFIX . $this->messages->get("QuitShop"));
                break;
                }    
            }
        });
        $this->effects = new Config($this->getDataFolder() . 'effects.yml', Config::YAML);
        $form->setTitle("§l§bBuff §fShop");
        $form->setContent($this->messages->get("Description"));
        if ($this->effects->get("BuffOne")){
        $form->addButton($this->messages->get("Button1"));
        }
        if ($this->effects->get("BuffTwo")){
        $form->addButton($this->messages->get("Button2"));
        }
        if ($this->effects->get("BuffThree")){
        $form->addButton($this->messages->get("Button3"));
        }
        if ($this->effects->get("BuffFour")){
        $form->addButton($this->messages->get("Button4"));
        }
        if ($this->effects->get("BuffFive")){
        $form->addButton($this->messages->get("Button5"));
        }
        if ($this->effects->get("BuffSix")){
        $form->addButton($this->messages->get("Button6"));
        }
        if ($this->effects->get("BuffSeven")){
        $form->addButton($this->messages->get("Button7"));
        }
        if ($this->effects->get("BuffEight")){
        $form->addButton($this->messages->get("Button8"));
        }
        $form->addButton($this->messages->get("ExitButton")); 
        $form->sendToPlayer($player); 
    }

    public function BuffOne(Player $player){
        $api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $api->createSimpleForm(function (Player $player, int $data = null){
            $result = $data;
            if ($result === null){
                return true;
            }else{
                switch ($result){
                    case 0:
                    $effect = Effect::getEffect($this->effects->get("BuffOne"));
                    $time = $this->effects->get("BuffTimeOne") * 20;
                    $level = $this->effects->get("BuffLevelOne");
                    $effectInstance = new EffectInstance($effect, $time, $level);
                    $player->addEffect($effectInstance);
                    $player->setXpLevel($player->getXpLevel() - $this->costs->get("BuffOneXPCost"));
                    $player->sendMessage(self::PREFIX . $this->messages->get("BuyBuffOne"));
                    break;
                }
            }
        });
        $form->setTitle("Buff Shop");
        $form->setContent("");
        $form->addButton($this->messages->get("BuyButton"));
        $form->addButton($this->messages->get("ExitButton"));
        $form->sendToPlayer($player);        
    }

    public function BuffTwo(Player $player){
        $api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $api->createSimpleForm(function (Player $player, int $data = null){
            $result = $data;
            if ($result === null){
                return true;
            }else{
                switch ($result){
                    case 0:
                    $effect = Effect::getEffect($this->effects->get("BuffTwo"));
                    $time = $this->effects->get("BuffTimeTwo") * 20;
                    $level = $this->effects->get("BuffLevelTwo");
                    $effectInstance = new EffectInstance($effect, $time, $level);
                    $player->addEffect($effectInstance);
                    $player->setXpLevel($player->getXpLevel() - $this->costs->get("BuffTwoXPCost"));
                    $player->sendMessage(self::PREFIX . $this->messages->get("BuyBuffTwo"));
                    break;
                }
            }
        });
        $form->setTitle("Buff Shop");
        $form->setContent("");
        $form->addButton($this->messages->get("BuyButton"));
        $form->addButton($this->messages->get("ExitButton"));
        $form->sendToPlayer($player);        
    }

    public function BuffThree(Player $player){
        $api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $api->createSimpleForm(function (Player $player, int $data = null){
            $result = $data;
            if ($result === null){
                return true;
            }else{
                switch ($result){
                    case 0:
                    $effect = Effect::getEffect($this->effects->get("BuffThree"));
                    $time = $this->effects->get("BuffTimeThree") * 20;
                    $level = $this->effects->get("BuffLevelThree");
                    $effectInstance = new EffectInstance($effect, $time, $level);
                    $player->addEffect($effectInstance);    
                    $player->setXpLevel($player->getXpLevel() - $this->costs->get("BuffThreeXPCost"));
                    $player->sendMessage(self::PREFIX . $this->messages->get("BuyBuffThree"));
                    break;
                }
            }
        });
        $form->setTitle("Buff Shop");
        $form->setContent("");
        $form->addButton($this->messages->get("BuyButton"));
        $form->addButton($this->messages->get("ExitButton"));
        $form->sendToPlayer($player);        
    }

    public function BuffFour(Player $player){
        $api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $api->createSimpleForm(function (Player $player, int $data = null){
            $result = $data;
            if ($result === null){
                return true;
            }else{
                switch ($result){
                    case 0:
                    $effect = Effect::getEffect($this->effects->get("BuffFour"));
                    $time = $this->effects->get("BuffTimeFour") * 20;
                    $level = $this->effects->get("BuffLevelFour");
                    $effectInstance = new EffectInstance($effect, $time, $level);
                    $player->addEffect($effectInstance); 
                    $player->setXpLevel($player->getXpLevel() - $this->costs->get("BuffFourXPCost"));
                    $player->sendMessage(self::PREFIX . $this->messages->get("BuyBuffFour"));
                    break;
                }
            }
        });
        $form->setTitle("Buff Shop");
        $form->setContent("");
        $form->addButton($this->messages->get("BuyButton"));
        $form->addButton($this->messages->get("ExitButton"));
        $form->sendToPlayer($player);        
    }

    public function BuffFive(Player $player){
        $api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $api->createSimpleForm(function (Player $player, int $data = null){
            $result = $data;
            if ($result === null){
                return true;
            }else{
                switch ($result){
                    case 0:
                    $effect = Effect::getEffect($this->effects->get("BuffFive"));
                    $time = $this->effects->get("BuffTimeFive") * 20;
                    $level = $this->effects->get("BuffLevelFive");
                    $effectInstance = new EffectInstance($effect, $time, $level);
                    $player->addEffect($effectInstance); 
                    $player->setXpLevel($player->getXpLevel() - $this->costs->get("BuffFiveXPCost"));
                    $player->sendMessage(self::PREFIX . $this->messages->get("BuyBuffFive"));
                    break;
                }
            }
        });
        $form->setTitle("Buff Shop");
        $form->setContent("");
        $form->addButton($this->messages->get("BuyButton"));
        $form->addButton($this->messages->get("ExitButton"));
        $form->sendToPlayer($player);        
    }

    public function BuffSix(Player $player){
        $api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $api->createSimpleForm(function (Player $player, int $data = null){
            $result = $data;
            if ($result === null){
                return true;
            }else{
                switch ($result){
                    case 0:
                    $effect = Effect::getEffect($this->effects->get("BuffSix"));
                    $time = $this->effects->get("BuffTimeSix") * 20;
                    $level = $this->effects->get("BuffLevelSix");
                    $effectInstance = new EffectInstance($effect, $time, $level);
                    $player->addEffect($effectInstance); 
                    $player->setXpLevel($player->getXpLevel() - $this->costs->get("BuffSixXPCost"));
                    $player->sendMessage(self::PREFIX . $this->messages->get("BuyBuffSix"));
                    break;
                }
            }
        });
        $form->setTitle("Buff Shop");
        $form->setContent("");
        $form->addButton($this->messages->get("BuyButton"));
        $form->addButton($this->messages->get("ExitButton"));
        $form->sendToPlayer($player);        
    }

    public function BuffSeven(Player $player){
        $api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $api->createSimpleForm(function (Player $player, int $data = null){
            $result = $data;
            if ($result === null){
                return true;
            }else{
                switch ($result){
                    case 0:
                    $effect = Effect::getEffect($this->effects->get("BuffSeven"));
                    $time = $this->effects->get("BuffTimeSeven") * 20;
                    $level = $this->effects->get("BuffLevelSeven");
                    $effectInstance = new EffectInstance($effect, $time, $level);
                    $player->addEffect($effectInstance); 
                    $player->setXpLevel($player->getXpLevel() - $this->costs->get("BuffSevenXPCost"));
                    $player->sendMessage(self::PREFIX . $this->messages->get("BuyBuffSeven"));
                    break;
                }
            }
        });
        $form->setTitle("Buff Shop");
        $form->setContent("");
        $form->addButton($this->messages->get("BuyButton"));
        $form->addButton($this->messages->get("ExitButton"));
        $form->sendToPlayer($player);        
    }

    public function BuffEight(Player $player){
        $api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $api->createSimpleForm(function (Player $player, int $data = null){
            $result = $data;
            if ($result === null){
                return true;
            }else{
                switch ($result){
                    case 0:
                    $effect = Effect::getEffect($this->effects->get("BuffEight"));
                    $time = $this->effects->get("BuffTimeEight") * 20;
                    $level = $this->effects->get("BuffLevelEight");
                    $effectInstance = new EffectInstance($effect, $time, $level);
                    $player->addEffect($effectInstance); 
                    $player->setXpLevel($player->getXpLevel() - $this->costs->get("BuffEightXPCost"));
                    $player->sendMessage(self::PREFIX . $this->messages->get("BuyBuffEight"));
                    break;
                }
            }
        });
        $form->setTitle("Buff Shop");
        $form->setContent("");
        $form->addButton($this->messages->get("BuyButton"));
        $form->addButton($this->messages->get("ExitButton"));
        $form->sendToPlayer($player);        
    }

    public function onDisable(){
        $this->getLogger()->info(self::PREFIX . " wurde deaktiviert!");
    }
}
