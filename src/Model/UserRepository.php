<?php

namespace PWBox\Model;

interface UserRepository {

    public function save(User $user);

}