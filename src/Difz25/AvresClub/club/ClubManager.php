<?php

namespace Difz25\AvresClub\club;

use Difz25\AvresClub\AvresClub;
use Difz25\AvresClub\utils\Roles;

class ClubManager {

    public array $cfg;
    protected static ClubManager $instance;
    protected AvresClub $plugin;
    public array $playerConfig;

    public function __construct(AvresClub $plugin) {
        $this->plugin = $plugin;
        $this->cfg[] = $this->plugin->getConfigData()->get("Club", []);
        $this->playerConfig[] = $this->plugin->getConfigData()->get("Player", []);
    }

    public static function getInstance(): ClubManager {
        return self::$instance;
    }
    
    public function createClub(string $leader, string $clubName, ?string $description = "Club"): array {
        if(!isset($this->cfg[$clubName])){
            $this->cfg[$clubName] = [
                'name' => $clubName,
                'description' => $description,
                'leader' => $leader
            ];
            $this->playerConfig[$leader] = [
                'club' => $clubName,
                'role' => Roles::LEADER
            ];
        }
        $this->plugin->getConfigData()->save();
        $nameTaken = "This club name has been taken / already used";
        
        return [
            'nameTaken' => $nameTaken
        ];
    }
    
    public function checkClub(string $club): array {
        return $this->cfg[$club];
    }
    
    public function deleteClub(string $playerName, string $clubName): array {
        if(Roles::class->getRole($playerName) == Roles::LEADER){
            if(isset($this->cfg[$clubName])){
                unset($this->cfg[$clubName]);
            }
        }
        $this->plugin->getConfigData()->save();
        
        return [
            'notFound' => "The club is not exists just enter correct club name"
        ];
    }
    
    public function renameClub(string $playerName, string $clubName, string $newName): void {
        if(Roles::class->getRole($playerName) == Roles::LEADER){
            if($this->cfg[$clubName] !== null){
                $this->cfg[$clubName]['name'] = $newName;
            }
        }
        $this->plugin->getConfigData()->save();
    }
    
    public function descriptionClub(string $playerName, string $clubName, string $newDesc): void {
        if(Roles::class->getRole($playerName) == Roles::LEADER){
            if($this->cfg[$clubName] !== null){
                $this->cfg[$clubName]['desc'] = $newDesc;
            }
        }
        $this->plugin->getConfigData()->save();
    }

    public function  keysPlayerClub(string $playerName): array {
        $club = $this->playerConfig[$playerName]['club'];
        $role = $this->playerConfig[$playerName]['role'];
        $desc = $this->cfg[$this->playerConfig[$playerName]['club']]['desc'];
        $leader = $this->cfg[$this->playerConfig[$playerName]['club']]['leader'];
        
        return [
            'club' => $club,
            'desc' => $desc,
            'leader' => $leader,
            'role' => $role
        ];
    }
    
    public function  keysClubConfig(string $club): array {
        $club = $this->cfg[$club]['club'];
        $desc = $this->cfg[$club]['desc'];
        $leader = $this->cfg[$club]['leader'];
        
        return [
            'club' => $club,
            'desc' => $desc,
            'leader' => $leader
        ];
    }
}