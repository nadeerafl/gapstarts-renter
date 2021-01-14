<?php
include 'src/EquimentAvailabilityHelper.php';
include 'src/Helpers/Validation.php';
require_once ('src/Helpers/Format.php');
require_once ('src/Helpers/Response.php');
include 'vendor/autoload.php';

// Format parameters object
$format         = new Format();
// Response parameters object
$response       = new Response();

$query_param    = $_GET;

// Validate Parameters
$validate       = new Validation();
// Check if a valid route
$is_valid_route = $validate->isValidRoute($query_param);

// Prepare validation array
$validation_array = array (
    'start'         => array(
                            'required'  => 'yes',
                            'type'      => 'date'
                        ),
    'end'           => array(
                            'required'  => 'yes',
                            'type'      => 'date'
                        ),
    'route'         => array(
                            'required'  => 'yes',
                            'type'      => 'string'
                        ),
);

// Additional param for checkavailability
if ($is_valid_route == true && $_GET['route'] == 'availability') {
    $validation_array['equipment_id']   =   array(
                                                'required'  => 'yes',
                                                'type'      => 'int',
                                            );

    $validation_array['quantity']       =   array(
                                                'required'  => 'yes',
                                                'type'      => 'int',
                                            );
}

// Validate param
$errors         = $validate->validateParam($query_param, $validation_array);

// If error found, return error and exit from the stript
if (!empty($errors)) {
    $response->errorResponse($errors);
}

// Format Param
$formatted_array    = $format->formatParam($query_param, $validation_array);

$equpment_availability_helper = new EquimentAvailabilityHelper();

if ($_GET['route'] == 'availability') {
    // Get availability
    $is_available = $equpment_availability_helper->isAvailable  ( 
                                                                    $formatted_array['equipment_id'], 
                                                                    $formatted_array['quantity'], 
                                                                    $formatted_array['start'], 
                                                                    $formatted_array['end']
                                                                );
    
    if ($is_available == true) {
        // Prepare result
        $result_arr = array('availabiliy' => true);
        $response->successResponse($result_arr);
    } else {
        // Prepare result
        $result_arr = array('availabiliy' => false);
        $response->successResponse($result_arr);
    }

} else if ($_GET['route'] == 'shortage') {
    // Get availability
    $shortage_arr   = $equpment_availability_helper->getShortages(  
                                                                    $formatted_array['start'], 
                                                                    $formatted_array['end']
                                                                );

    $response->successResponse($shortage_arr);
} else {
    // INvalid route
    $errors = array('message' => 'Invalid route');
    $response->errorResponse($errors);
}


?>