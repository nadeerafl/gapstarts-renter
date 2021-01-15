<?php

require_once './src/DB/Database.php';
require_once ('./src/Helpers/Format.php');

// execute the stored Procedure

class Equipment
{
    private $mysqli;
    private $format_obj;

    public function __construct() {
        $db                 = Database::getInstance();
        $this->mysqli       = $db->getConnection(); 
        $this->format_obj   = new Format();
    }

    /**
     * Get all equipments array 
     * @return equipment object
     */
    public function getAllEquipments()
    {   
        $result = $this->mysqli->query("SELECT id,stock from equipment");
        return $result;
    }

    /**
     * Get shortage of a equipment for given period
     * @param int      $equipment_id Id of the equipment item
	 * @param DateTime $start Start of time window
	 * @param DateTime $end End of time window
	 * @return INT     shotage if available, 0 otherwise
     */
    public function getShortageOfEquipment(int $equipment_id, DateTime $start, DateTime $end) : INT
    {   
        $start_date     = $this->format_obj->convertDateToString($start);
        $end_date       = $this->format_obj->convertDateToString($end);

        $result         = $this->mysqli->query("CALL get_shortage_of_equipments('".$start_date."', '".$end_date."', $equipment_id)");
        $result_arr     = $result->fetch_array();
        return $result_arr['max_shortage'] ? $result_arr['max_shortage'] : 0;
    }
}

