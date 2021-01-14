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

    public function getAvailability(int $equipment_id, int $quantity, DateTime $start, DateTime $end) : bool
    {   
        
        $start_date     = $this->format_obj->convertDateToString($start);
        $end_date       = $this->format_obj->convertDateToString($end);
        $db             = Database::getInstance();
        $mysqli   = $db->getConnection(); 
        $result         = $mysqli->query("CALL is_equipment_available('".$start_date."', '".$end_date."', $equipment_id, $quantity)");
        $result_arr     = $result->fetch_array();
        return $result_arr['available'] ? true : false;
    }

    /**
     * Get shortage of a equipment for given period
     */
    public function getShortageOfEquipment(int $equipment_id, DateTime $start, DateTime $end) 
    {   
        $start_date     = $this->format_obj->convertDateToString($start);
        $end_date       = $this->format_obj->convertDateToString($end);

        $db            = Database::getInstance();
        $mysqli        = $db->getConnection(); 

        $result         = $mysqli->query("CALL get_shortage_of_equipments('".$start_date."', '".$end_date."', $equipment_id)");

       // $result         = $mysqli->query("CALL is_equipment_available('".$start_date."', '".$end_date."', $equipment_id, 1)");
        $result_arr     = $result->fetch_array();

        $result->close();
        $mysqli->next_result(); //
        return $result_arr['max_shotage'] ? $result_arr['max_shotage'] : 0;
    }
    

    /**
     * Get all equipments array 
     */
    public function getAllEquipments()
    {   
        $result = $this->mysqli->query("SELECT id,stock from equipment");
        return $result;
    }

}

