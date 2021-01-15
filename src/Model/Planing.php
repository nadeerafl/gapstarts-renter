<?php

require_once './src/DB/Database.php';
require_once ('./src/Helpers/Format.php');

// execute the stored Procedure

class Planing
{

    private $format_obj;

    public function __construct() {

        $this->format_obj   = new Format();
    }

    /**
     * Get availability of requested equipment
     * @param int       $equipment_id Id of the equipment item
	 * @param int       $quantity How much should be available
	 * @param DateTime  $start Start of time window
	 * @param DateTime  $end End of time window
	 * @return bool     True if available, false otherwise
     */
    public function getAvailability(int $equipment_id, int $quantity, DateTime $start, DateTime $end) : bool
    {   
        
        $start_date     = $this->format_obj->convertDateToString($start);
        $end_date       = $this->format_obj->convertDateToString($end);
        $db             = Database::getInstance();
        $mysqli         = $db->getConnection(); 
        $result         = $mysqli->query("CALL is_equipment_available('".$start_date."', '".$end_date."', $equipment_id, $quantity)");
        $result_arr     = $result->fetch_array();
        $result->close();
        $mysqli->next_result(); //
        return $result_arr['available'] ? true : false;
    }

    /**
     * Get shortage of a equipment for given period
     * @param int       $equipment_id Id of the equipment item
	 * @param DateTime  $start Start of time window
	 * @param DateTime  $end End of time window
	 * @return bool     max shortage of equipment in given period
     */
    public function getShortageOfEquipment(int $equipment_id, DateTime $start, DateTime $end) : INT
    {   
        $start_date     = $this->format_obj->convertDateToString($start);
        $end_date       = $this->format_obj->convertDateToString($end);
        $db             = Database::getInstance();
        $mysqli         = $db->getConnection(); 
        $result         = $mysqli->query("CALL get_shortage_of_equipments('".$start_date."', '".$end_date."', $equipment_id)");
        $result_arr     = $result->fetch_array();

        $result->close();
        $mysqli->next_result(); //
        return $result_arr['max_shotage'] ? $result_arr['max_shotage'] : 0;
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

}

