<?php
include 'EquimentAvailabilityHelper.php';
include 'Validation.php';
require_once ('Format.php');

//$mysqli->next_result();

// Prepare validation array
$validation_array = array (
    'equipment_id'  =>  array(
                            'required'  => 'yes',
                            'type'      => 'int',
                        ),
    'quantity'      =>  array(
                            'required'  => 'yes',
                            'type'      => 'int'
                        ),
    'start'         => array(
                            'required'  => 'yes',
                            'type'      => 'date'
                        ),
    'end'           => array(
                            'required'  => 'yes',
                            'type'      => 'date'
                        ),
);

$query_param = $_GET;

// Validate Parameters
$validate       = new Validation();
$errors         = $validate->validateParam($query_param, $validation_array);


// Format parameters
$format             = new Format();
$formatted_array    = $format->formatParam($query_param, $validation_array);
//var_dump($formatted_array);

// $planing = new Planing();
// $planing->getAvailability();

$equpment_availability_helper = new EquimentAvailabilityHelper();
// Get availability
$is_available = $equpment_availability_helper->isAvailable( 
                                                            $formatted_array['equipment_id'], 
                                                            $formatted_array['quantity'], 
                                                            $formatted_array['start'], 
                                                            $formatted_array['end']);
var_dump($is_available);
?>