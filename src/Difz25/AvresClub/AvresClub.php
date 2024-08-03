<?php

namespace Difz25\AvresClub;

use Difz25\AvresClub\club\ClubManager;
use JsonException;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\Config;

class AvresClub extends PluginBase {

    protected array $cfg = [];
    
    protected array $playerConfig = [];
    private Config $configData;
    public ClubManager $clubManager;

    protected function onEnable(): void {
        $this->clubManager = new ClubManager($this);
        $this->cfg[] = $this->getConfigData()->get("Club", []);
        $this->playerConfig[] = $this->getConfigData()->get("Player", []);
        $this->configData = new Config($this->getDataFolder() . "config.yml" . Config::YAML);
    }

    /**
     * @throws JsonException
     */
    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool {
        if($command->getName() === "club"){
            if($sender instanceof Player){
                if(count($args) < 1){
                    $sender->sendMessage("Usage: /club <create|delete|rename|info|description>");
                }

                switch($args){
                    case "create":
                        if(count($args) < 1){
                            $sender->sendMessage("Usage: /club create <name> <description>");
                        }
                            $this->getClubManager()->createClub($sender->getName(), $args[1], $args[2]);
                            $sender->sendMessage("Successfully created club with name: " . $args[1]);
                        break;
                    case "delete":
                        if($this->getClubManager()->checkClub($args[1]) !== null){
                            $this->getClubManager()->deleteClub($sender->getName(), $args[1]);
                            $sender->sendMessage("Succesfully deleted club with name: " . $args[1]);
                        }
                        break;
                    case "rename":
                        $this->getClubManager()->renameClub($sender->getName(), $args[1], $args[2]);
                        $sender->sendMessage("Succesfully rename clan to: " . $args[1]);
                        break;
                    case "info":
                        $keys = $this->getClubManager()->keysPlayerClub($sender->getName());
                        $sender->sendMessage($keys['club']);
                        $sender->sendMessage($keys['desc']);
                        $sender->sendMessage($keys['leader']);
                        break;
                    case "description":
                        $keys = $this->getClubManager()->keysPlayerClub($sender->getName());
                        $this->getClubManager()->descriptionClub($sender->getName(), $keys['club'], $args[2]);
                        $sender->sendMessage("Succesfully created clan with name: " . $args[1]);
                        break;
                }
            }
        }
        return true;
    }

    public function  getClubManager(): ClubManager {
            return $this->clubManager;
    }

    public function getConfigData(): Config {
            return $this->configData;
    }
}