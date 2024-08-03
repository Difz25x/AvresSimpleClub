<?php

namespace Difz25\AvresClub\utils;

use Difz25\AvresClub\AvresClub;

class Roles {
    
    public AvresClub $plugin;
    
    const LEADER = "LEADER";
    const OFFICER = "OFFICER";
    const MEMBER = "MEMBER";

    const ALL = [
        Roles::MEMBER => 1,
        Roles::OFFICER => 2,
        Roles::LEADER => 3
    ];
    public array $playerConfig = [];

    public function __construct(AvresClub $plugin){
        $this->plugin = $plugin;
        $this->playerConfig[] = $this->plugin->getConfigData()->get("Player", []);
    }
    
    public function getRole(string $playerName): null|array {
        return $this->playerConfig[$playerName]['role'];
    }
    
    public function setRole(string $playerName, string $club, string $role): void {
        if(isset($this->playerConfig[$playerName])){
            $this->playerConfig[$playerName] = [
                'club' => $club,
                'role' => $role
            ];
        }
        $this->plugin->getConfigData()->save();
    }
}