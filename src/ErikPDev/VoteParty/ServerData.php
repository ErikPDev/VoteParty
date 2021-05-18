<?php

namespace ErikPDev\VoteParty;

class ServerData{
    private $main;

    private $votes = 0;
    public function __construct(Main $main){
        $this->main = $main;
        $path = $this->getPath();
        if(!is_file($path)){
            return;
        }

        $data = yaml_parse_file($path);
        $this->votes = $data["votes"];

    }

    public function save(){
        yaml_emit_file($this->getPath(), [
            "votes" => $this->getVotes(),
        ]);
    }

    public function getPath() : string{
        // Hint: remember to strtolower(), because some filesystems are case-sensitive!
        return $this->main->getDataFolder() . "serverdata.yml";
    }

    public function getVotes() : int{
        return $this->votes;
    }


    public function incrementVotes() {
        $this->votes++;
    }

    public function decrementVotes() {
        $this->votes--;
    }
    public function setVotes(int $number) {
      $this->votes = $number;
    }
}
