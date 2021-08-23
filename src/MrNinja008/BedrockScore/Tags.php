<?php
declare (strict_types = 1);

namespace MrNinja008\BedrockScore;


use Ifera\ScoreHud\scoreboard\ScoreTag;
use Ifera\ScoreHud\event\PlayerTagUpdateEvent;
use MrNinja008\BedrockScore\listeners\TagResolveListener;
use pocketmine\plugin\PluginBase;
use pocketmine\scheduler\ClosureTask;
use pocketmine\utils\Process;
use pocketmine\Player;
use function strval;

class Tags extends PluginBase {
      /** @var BedrockClans  */
     Public $BedrockClans;
	   
public function onEnable()
  {
  $this->BedrockClans = $this->getServer()->getPluginManager()->getPlugin("BedrockClans");
  $this->getServer()->getPluginManager()->registerEvents(new TagResolveListener($this), $this);
  $this->getScheduler()->scheduleRepeatingTask(
      new ClosureTask(
        function(int $_):void {
          foreach ($this->getServer()->getOnlinePlayers() as $player) {
            if (!$player->isOnline()) {
              continue;
            }
  
            (new PlayerTagUpdateEvent($player, new ScoreTag("clan.name", strval($this->getPlayerClan($player)))))->call();
           (new PlayerTagUpdateEvent($player, new ScoreTag("clan.rank", strval($this->getPlayerClanRank($player)))))->call();
           (new PlayerTagUpdateEvent($player, new ScoreTag("clan.members", strval($this->getPlayerClanMembers($player)))))->call();
          }
        }
      ),
      20
    );
  }
         /**
         * @param Player $player
         * @return string
         */
public function getPlayerClan(Player $player): string
        {
 $player = $this->BedrockClans->getPlayer($player);
            if ($player->isInClan()) {
                return $player->getClan()->getName();
            } else {
                return "N/A";
            }
        }
        /**
         * @param Player $player
         * @return string
         */
public function getPlayerClanRank(Player $player)
        {
  $player = $this->BedrockClans->getPlayer($player);
            if ($player->isInClan()) {
                return $player->getClan()->getRank($player->getPlayer());
            } else {
                return "N/A";
            }
        }

        /**
         * @param Player $player
         * @return string
         */
public function getPlayerClanMembers(Player $player)
        {
  $player = $this->BedrockClans->getPlayer($player);
            if ($player->isInClan()) {
                return (string)count($player->getClan()->getMembers());
            } else {
                return "N/A";
            }
        }
}
