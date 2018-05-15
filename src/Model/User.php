<?php

namespace PWBox\Model;

use \DateTime;

class User {

    private $username;
    private $email;
    private $password;
    private $birthdate;
    private $space;

    public function __construct($username, $email, $password, $birthdate, $space) {
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
        $this->birthdate = $birthdate;
        $this->space = $space;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername($username): void
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email): void
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword($password): void
    {
        $this->password = $password;
    }

    /**
     * @return String
     */
    public function getBirthdate(): string
    {
        return $this->birthdate;
    }

    /**
     * @param String $birthdate
     */
    public function setBirthdate($birthdate): void
    {
        $this->birthdate = $birthdate;
    }

    public function getSpace(): float
    {
        return $this->space;
    }

    public function setSpace($space): void
    {
        $this->space = $space;
    }

}