<?php
class Gui {
	public function __construct() {
		$this->sqlite = new PDO('apidb.sqlite3');
		$this->sqlite->enableExceptions(true);
		$dbstructure=file_get_contents('../db.sql');
		$this->sqlite->exec($dbstructure);
	}

	public function __destruct() {
		$this->sqlite->close();
	}

	public function signIn($apikey) {
		$query=$this->sqlite->prepare("SELECT `iduser` FROM `users` WHERE `apikey` = :apikey");
		$query->execute(array("apikey" => $apikey));
		$iduser=$query->fetchColumn();
	    return($iduser);
	}

	public function getLastRecord($uid) {
		$query=$this->sqlite->prepare("SELECT * FROM DATA WHERE `user` = :user ORDER BY `timestamp` DESC LIMIT 1");
		$query->execute(array("user" => $uid));
		return($query->fetch(PDO::FETCH_OBJ));
	}

	public function getSettings($uid) {
		$query=$this->sqlite->prepare("SELECT `timezone`, `notifications` FROM `users` WHERE `iduser` = :user");
		$query->execute(array("user" => $uid));
		return($query->fetch(PDO::FETCH_OBJ));
	}

	public function setSettings($uid, $timezone, $notifications) {
		$query=$this->sqlite->prepare("UPDATE `users` SET `timezone` = :tz, `notifications` = :notifications WHERE `iduser` = :user");
		$ret=$query->execute(array(":tz" => $timezone, "notifications" => $notifications, "user" => $uid));
		return($ret);
	}
}
?>