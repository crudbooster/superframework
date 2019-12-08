<?php

namespace App\Models;


use System\Models\Model;

class Users extends Model
{
	private $id;
	private $created_at;
	private $updated_at;
	private $name;
	private $email;
	private $password;


	public function setId($value) {
		$this->id = $value;
	}

	public function getId() {
		return $this->id;
	}

	public function setCreatedAt($value) {
		$this->created_at = $value;
	}

	public function getCreatedAt() {
		return $this->created_at;
	}

	public function setUpdatedAt($value) {
		$this->updated_at = $value;
	}

	public function getUpdatedAt() {
		return $this->updated_at;
	}

	public function setName($value) {
		$this->name = $value;
	}

	public function getName() {
		return $this->name;
	}

	public function setEmail($value) {
		$this->email = $value;
	}

	public function getEmail() {
		return $this->email;
	}

	public function setPassword($value) {
		$this->password = $value;
	}

	public function getPassword() {
		return $this->password;
	}


}