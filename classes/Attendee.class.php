<?php
class Attendee {
    private $idattendee;
    private $name;
    private $idevent;
    private $event;
    private $role;  
    private $datestart;	
    private $dateend;
    private $venue;

    public function getIDAttendee(){
        return $this->idattendee;
    }

    public function getName(){
        return $this->name;
    }

    public function getIDEvent(){
        return $this->idevent;
    }

    public function getEvent(){
        return $this->event;
    }

    public function getRole(){
        return $this->role;
    }

    public function getDateStart(){
        return $this->datestart;
    }
    public function getDateEnd(){
        return $this->dateend;
    }
    public function getVenue(){
        return $this->venue;
    }
}
?>