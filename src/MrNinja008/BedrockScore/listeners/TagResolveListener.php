<?php
declare(strict_types = 1);

namespace MrNinja008\BedrockScore\listeners;

use Ifera\ScoreHud\event\TagsResolveEvent;
use MrNinja008\BedrockScore\Tags;
use pocketmine\event\Listener;
use function count;
use function explode;
use function strval;

class TagResolveListener implements Listener{

	/** @var Tags */
	private $plugin;
	
	public function __construct(Tags $plugin){
		$this->plugin = $plugin;
	}
	
	public function onTagResolve(TagsResolveEvent $event){
		$tag = $event->getTag();
		$tags = explode('.', $tag->getName(), 3);
		$value = "";
		if($tags[0] !== 'clan' || count($tags) < 2){
			return;
		}
		
		switch($tags[1]){
			case "name":
				$value = $this->plugin->getPlayerClan($event->getPlayer());
			break;

			case "rank":
				$value = $this->plugin->getPlayerClanRank($event->getPlayer());
			break;
			
			case "members":
				$value = $this->plugin->getPlayerClanMembers($event->getPlayer());
			break;
		}

		$tag->setValue(strval($value));
	}
}
