<?php
class Attendee {
    private $idattendee;
    private $name;
    private $password;
    private $role;  

    public function getIDAttendee(){
        return $this->idattendee;
    }

    public function getName(){
        return $this->name;
    }

    public function getPassword(){
        return $this->password;
    }

    public function getRole(){
        return $this->role;
    }
}



?>