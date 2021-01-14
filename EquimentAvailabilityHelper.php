<?php
include "Planing.php";

class EquimentAvailabilityHelper {

	/**
	 * This function checks if a given quantity is available in the passed time frame
	 * @param int      $equipment_id Id of the equipment item
	 * @param int      $quantity How much should be available
	 * @param DateTime $start Start of time window
	 * @param DateTime $end End of time window
	 * @return bool True if available, false otherwise
	 */
	public function isAvailable(int $equipment_id, int $quantity, DateTime $start, DateTime $end) : bool {
        //todo
        $planing = new Planing();
        $is_available = $planing->getAvailability($equipment_id, $quantity, $start, $end);
        return $is_available;
	}

	/**
	 * Calculate all items that are short in the given period
	 * @param DateTime $start Start of time window
	 * @param DateTime $end End of time window
	 * @return array Key/valyue array with as indices the equipment id's and as values the shortages
	 */
	public function getShortages(DateTime $start, DateTime $end) : array {

		//example return value, this means that between $start en $end equipment with id's 4 and 5 have shortages of -3 and -1
		return array(
			'4' => -3,
			'5' => -1,
		);
	}

}
