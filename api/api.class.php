<?php
class Api {

  public function __construct() {
    $this->sqlite = new PDO("sqlite:apidb.sqlite3");
    $dbstructure=file_get_contents('../db.sql');
    $this->sqlite->exec($dbstructure);
  }

  public function __destruct() {
  }

  public function register() {
    $apikey = substr(uniqid(), 0, 12);
    $query=$this->sqlite->prepare("INSERT INTO `users` (`apikey`, `IP`) VALUES (:apikey, :ip)");
    if($query->execute(array("apikey"=>$apikey, "ip" => $_SERVER['REMOTE_ADDR']))) {
      return $apikey;
    } else {
      return false;
    }
  }

  public function getApiKeyUser($apikey) {
    $query=$this->sqlite->prepare("SELECT `iduser` FROM `users` WHERE `apikey` =  :apikey");
    if($query->execute(array("apikey" => $apikey))) {
      $result=$query->fetchColumn();
      return($result);
    }
    return false;
  }

  public function pushVals($json) {
    if(!$iduser = $this->getApiKeyUser($json->apikey)) {
      return false; 
    }
    $query = $this->sqlite->prepare("INSERT INTO `data` (`user`, `IP`, `carType`, `ignitionOn`, `chargingOn`, `socPerc`, `sohPerc`, `batPowerKw`, `batPowerAmp`, 
                                    `batVoltage`, `auxVoltage`, `auxAmp`, `batMinC`, `batMaxC`, `batInletC`, `batFanStatus`, `speedKmh`, `odoKm`, 
                                    `cumulativeEnergyChargedKWh`, `cumulativeEnergyDischargedKWh`, `gpsLat`, `gpsLon`, `gpsAlt`) 
                                    VALUES (:user, :ip, :cartype, :ignitionon, :chargingon, :socperc, :sohperc, :batpowerkw, :batpoweramp, 
                                    :batvoltage, :auxvoltage, :auxamp, :batminc, :batmaxc, :batinletc, :batfanstatus, :speedkmh, :odokm,
                                    :cumulativeenergychargedkwh, :cumulativeenergydischargedkwh, :gpslat, :gpslon, :gpsalt)");
    $val=$query->execute(array("user" => $iduser, "ip" => $_SERVER['REMOTE_ADDR'], "cartype" => $json->carType, "ignitionon" => $json->ignitionOn, "chargingon" => $json->chargingOn,
                          "socperc" => $json->socPerc, "sohperc" => $json->sohPerc, "batpowerkw" => $json->batPowerKw, "batpoweramp" => $json->batPowerAmp, 
                          "batvoltage" => $json->batVoltage, "auxvoltage" => $json->auxVoltage, "auxamp" => $json->auxAmp, "batminc" => $json->batMinC, 
                          "batmaxc" => $json->batMaxC, "batinletc" => $json->batInletC, "batfanstatus" => $json->batFanStatus, "speedkmh" => $json->speedKmh, 
                          "odokmh" => $json->odoKm, "cumulativeenergychargedkwh" => $json->cumulativeEnergyChargedKWh, "cumulativeenergydischargedkwh" => $json->cumulativeEnergyDischargedKWh,
                          "gpslat" => $json->gpsLat, "gpslon" => $json->gpsLon, "gpsalt" => $json->gpsAlt));
    return($val);
  }

  public function getVals($json) {
    if(!$iduser = $this->getApiKeyUser($json->apikey)) {
      return false;
    }
    if (!isset($json->timestampTo)) $json->timestampTo=date("Y-m-d H:i:s");
    if (isset($json->timestampFrom)) {
      $query=$this->sqlite->prepare("SELECT * FROM `data` WHERE `user` = :user AND `timestamp` >= :timestampfrom AND `timestamp` <= :timestampto ORDER BY `timestamp`");
      $query->execute(array("timestampfrom" => $json->timestampFrom, "timestampto" => $json->timestampTo));
    } else {
      $query=$this->sqlite->prepare("SELECT * FROM `data` WHERE `user` = :user ORDER BY `timestamp` DESC LIMIT 1");
      $query->execute(array(":user" => $iduser));
    }
    $return=array();
    while ($row=$query->fetch(PDO::FETCH_OBJ)) $return[]=$row;
    return($return);
  }
}
?>
