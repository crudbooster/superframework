<?php

namespace System\Helpers;


class Auth
{

    /**
     * @param $email
     * @param $password
     * @return bool
     * @throws \Exception
     */
    public function attempt($email, $password) {
        $query = DB("users")->where("email = '$email'")->find();

        if($query && password_verify(config("password_salt").$password, $query['password'])) {
            session(["users_id"=>$query['id']]);
            return true;
        } else {
            return false;
        }
    }

    public function guest() {
        return (!session("users_id"))?true:false;
    }

    public function id() {
        return session("users_id");
    }

    /**
     * @return array|mixed
     * @throws \Exception
     */
    public function user() {
        return DB("users")->find($this->id());
    }

    public function password($password) {
        return password_hash(config("password_salt").$password, PASSWORD_BCRYPT);
    }

    public function logout() {
        session_forget("users_id");
    }

}