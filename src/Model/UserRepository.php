<?php

namespace PWBox\Model;

interface UserRepository {

    public function save(User $user);
    public function login(User $user);
    public function getUser(User $user);
    public function updateEmail(User $user, String $newEmail);
    public function updatePassword(User $user);
    public function deleteUser(User $user);
}