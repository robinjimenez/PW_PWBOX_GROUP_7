<?php

namespace PWBox\Model;

class File {

    private $name;
    private $owner;
    private $parent;

    public function __construct($name, $user, $folder) {
        $this->name = $name;
        $this->owner = $user;
        $this->parent = $folder;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * @param string $owner
     */
    public function setOwner($owner): void
    {
        $this->owner = $owner;
    }

    /**
     * @return int
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param int $parent
     */
    public function setParent($parent): void
    {
        $this->parent = $parent;
    }

}