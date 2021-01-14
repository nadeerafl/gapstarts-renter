<?php

include 'Database.php';
require_once ('Format.php');

// execute the stored Procedure

class Planing
{
    public function getAvailability(int $equipment_id, int $quantity, DateTime $start, DateTime $end) : bool
    {
       // print_r($quantity);
        
        $format_obj     = new Format();
        $start_date     = $format_obj->convertDateToString($start);
        $end_date       = $format_obj->convertDateToString($end);

        $db     = Database::getInstance();
        $mysqli = $db->getConnection(); 
        $result     = $mysqli->query("CALL 	is_equipment_available('".$start_date."', '".$end_date."', $equipment_id, $quantity)");
        $result_arr = $result->fetch_array();
        return $result_arr['available'] ? true : false;
    }
}

