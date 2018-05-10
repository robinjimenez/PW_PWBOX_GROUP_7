<?php

namespace PWBox\Model;

interface UserRepository {

    public function save(User $user);
    public function login(User $user);

}