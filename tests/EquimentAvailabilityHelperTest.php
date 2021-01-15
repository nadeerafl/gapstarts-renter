<?php declare(strict_types=1);
require_once ('./src/Helpers/Format.php');
require './src/EquimentAvailabilityHelper.php';
use PHPUnit\Framework\TestCase;

final class EquimentAvailabilityHelperTest extends TestCase
{
    public function testIsAvailableForTrue(): void
    {
        // Format param object
        $format_obj     = new Format;
        $aval           = new EquimentAvailabilityHelper;
        $equipment_id   = 1;
        $quantity       = 5;
        $start          = $format_obj->formatForDateObject('2019-05-28');
        $end            = $format_obj->formatForDateObject('2019-06-04');

        $this->assertTrue($aval->isAvailable($equipment_id, $quantity, $start, $end));
    }

    public function testIsAvailableForFalse(): void
    {
        // Format param object
        $format_obj     = new Format;
        $aval           = new EquimentAvailabilityHelper;
        $equipment_id   = 1;
        $quantity       = 100;
        $start          = $format_obj->formatForDateObject('2019-05-28');
        $end            = $format_obj->formatForDateObject('2019-06-04');

        $this->assertNotTrue($aval->isAvailable($equipment_id, $quantity, $start, $end));
    }

    public function testGetShortages(): void
    {
        // Format param object
        $format_obj     = new Format;
        $aval           = new EquimentAvailabilityHelper;
        $start          = $format_obj->formatForDateObject('2019-05-28');
        $end            = $format_obj->formatForDateObject('2019-06-04');

        $this->assertIsArray($aval->getShortages($start, $end));
    }

    public function testGetShortagesArrayFormat(): void
    {
        // Format param object
        $format_obj     = new Format;
        $aval           = new EquimentAvailabilityHelper;
        $start          = $format_obj->formatForDateObject('2019-05-28');
        $end            = $format_obj->formatForDateObject('2019-06-04');

        $this->assertIsArray($aval->getShortages($start, $end));
    }
}