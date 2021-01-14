DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_available_stock`(IN `equipment_id` INT, OUT `equipment_count` INT)
BEGIN

SELECT stock INTO equipment_count FROM equipment WHERE id = equipment_id;

END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_reserved_item_count_per_date`(IN `check_date` DATE, IN `equipment_id` INT, OUT `reserved_quantity` INT)
BEGIN

SELECT SUM(quantity) INTO reserved_quantity FROM planning
WHERE equipment = equipment_id AND `start` <= check_date AND `end` >= check_date;

END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `is_equipment_available`(IN `from_date` DATE, IN `to_date` DATE, IN `equipment_id` INT, IN `check_quantity` INT)
BEGIN
/* Availability of the equipment*/
DECLARE available BOOLEAN;
/* Temp date to loop through date period*/
DECLARE check_date_from DATE;
/* Reserved quantity per considering date */
DECLARE reserved_quantity INT;
/* Stock count */
DECLARE stock_count INT;
/* Required stock to release requested equipment*/
DECLARE required_stock INT;

SET check_date_from = from_date;
SET available = true;

check_availability : WHILE check_date_from <= to_date DO
    
	SET check_date_from = DATE_ADD(check_date_from,INTERVAL 1 day);
    /* Get reserved item count per date*/
    call get_reserved_item_count_per_date(check_date_from, equipment_id, reserved_quantity);
    /* Get available stock of requested equipment*/
    call get_available_stock(equipment_id, stock_count);
    
    SET required_stock = reserved_quantity + check_quantity;
    
    IF required_stock > stock_count THEN
    	/* This equipment request cannot be accomadate */
        SET available = false;
        LEAVE check_availability;
    END IF;
END WHILE;

SELECT available;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_shortage_of_equipments`(IN `from_date` DATE, IN `to_date` DATE, IN `equipment_id` INT)
    NO SQL
BEGIN

/* Temp date to loop through date period*/
DECLARE check_date_from DATE;
/* Reserved quantity per considering date */
DECLARE reserved_quantity INT;
/* Stock count */
DECLARE stock_count INT;
/* Required stock to release requested equipment*/
DECLARE required_stock INT;
/* Shortage amount - number of item shotage*/
DECLARE shotage_amount INT;

/* Create Temp table to add shortage & drop if already exist*/
DROP TEMPORARY TABLE IF EXISTS `shortage`;
CREATE TEMPORARY TABLE `shortage` ( equipment_id INT,date_string VARCHAR(255),shortage_count INT, reserved_count INT );

SET check_date_from = from_date;

check_availability : WHILE check_date_from <= to_date DO
    
	SET check_date_from = DATE_ADD(check_date_from,INTERVAL 1 day);
    /* Get reserved item count per date*/
    call get_reserved_item_count_per_date(check_date_from, equipment_id, reserved_quantity);
    /* Get available stock of requested equipment*/
    call get_available_stock(equipment_id, stock_count);
    
    /* Required item count to accomadate the request */
    SET required_stock 	= reserved_quantity;
  
    IF required_stock > stock_count THEN
		/* Shortage amount - minus value*/
    	SET shotage_amount = required_stock - stock_count;
    
    	/* This equipment request cannot be accomadate - Add as shortage */
        INSERT INTO shortage (equipment_id, date_string, shortage_count, reserved_count) VALUES (equipment_id, check_date_from, shotage_amount, reserved_quantity);
    END IF;
END WHILE;

/* Get max shotage per considering period*/
SELECT MAX(shotage_amount) as max_shotage FROM shortage;
DROP TEMPORARY TABLE shortage;

   
END$$
DELIMITER ;
