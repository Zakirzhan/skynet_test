<?php 
/**
/**
 * Class MyDB_Helper
 */
/**
 * @method public $this get_tarifs(?int $user_id, ?int $service_id)
 * @method public $this update_tarif(?int $tarif_id, ?int $service_id)
 * @method private $this init_new_paydays(?array $tarifs)
 * @method private $this generate_new_payday(?string $pay_period)
 */
class MyDB_Helper extends MyDB {
	 /**
     * Get Tarifs - получает тарифы для конкретного сервиса
     *
     * @param int|$user_id 
     * @param int|$service_id 
     *
     * @return array| \PDOStatement::fetchAll()
     */
	public function get_tarifs(?int $user_id, ?int $service_id){

		$stmt = $this->pdo->prepare("SELECT * FROM `tarifs` WHERE tarif_group_id = (SELECT `tarifs`.`tarif_group_id` FROM `services` LEFT JOIN `tarifs` ON (`tarifs`.`ID` = `services`.`tarif_id`) WHERE `services`.`user_id` = :user_id AND `services`.`ID` = :service_id)");
	 
		$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
		$stmt->bindParam(':service_id', $service_id, PDO::PARAM_INT);
		$stmt->execute();

		$all_tarifs = $stmt->fetchAll(PDO::FETCH_ASSOC);

		if(empty($all_tarifs)) {
			return ["result"=>"error"];
		} else {
			
			$tarifs = ["title" => $all_tarifs[0]["title"],
					   "link" => $all_tarifs[0]["link"], 
					   "speed" => $all_tarifs[0]["speed"],
					   "tarifs" => $this->init_new_paydays($all_tarifs)];

			return ["result"=>"OK", "tarifs"=>$tarifs];
		} 

	}


	 /**
     * Update Service PayDay - Запрос на выставление тарифа проставляет tarif_id и payday
     *
     * @param int|$user_id 
     * @param int|$service_id 
     *
     * @return array| result
     */
	public function update_service_payday(?int $tarif_id, ?int $service_id){

		$stmt = $this->pdo->prepare("UPDATE `services` SET `tarif_id` = :tarif_id, `payday` = CURDATE() WHERE `ID` = :service_id");
	 
		$stmt->bindParam(':tarif_id', $tarif_id, PDO::PARAM_INT);

		$stmt->bindParam(':service_id', $service_id, PDO::PARAM_INT);

         if ($stmt->execute()) {
              return ["result"=>"OK"];
          } else {
          	return ["result"=>"error"];
        }

	}

    /**
     * New Pay Day - Рассчитывается  как текущая дата полночь + pay_period
     * @param  array | $tarifs
     * @return array
     */
    private function init_new_paydays(?array $tarifs){

        foreach ($tarifs as $key => $tarif) {
            $tarifs[$key]["new_payday"] = $this->generate_new_payday($tarif["pay_period"]);
        }

        return $tarifs;
    }

    /**
     * Create New Pay Day - timestamp даты следующего списания и таймзона.
     *
     * @param  string | $pay_period
     * @return string
     */
    private function generate_new_payday(?string $pay_period){
        $newPayDate = new DateTime(date('Y-m-d 00:00:00'),
            					   new DateTimeZone('Europe/Moscow'));

        $newPayDate->add(new DateInterval('P'.$pay_period.'M'));

    	return $newPayDate->format('UO');
    }
}
?>