<?php
class Format
{
    public $formatted_feilds = array();

    public function formatParam(array $param_array,array $validation_fields) : array 
    {
        foreach($validation_fields as $param_key => $rule)
        {
            if ( isset($param_array[$param_key]) && $param_array[$param_key] != '') {
                $this->formatValue($param_array[$param_key], $rule['type'], $param_key);
            } 
        }

        return $this->formatted_feilds;
    }

    public function formatValue($value, $type, $param_name)
    {
        if ($type == 'int') {
            $casted_value = (int) $value;
            $this->setFormatedArray($param_name, $casted_value);
        }

        if ($type == 'date') {
            $date = new DateTime($value);
            $this->setFormatedArray($param_name, $date);
        }

    }

    private function setFormatedArray(string $field_name, $value)
    {
        $this->formatted_feilds[$field_name] = $value;
    }


    public function convertDateToString(DateTime $date) : string
    {
        return date_format($date, 'Y-m-d');
    }
}