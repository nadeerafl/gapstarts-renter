# Setup
- Use storedProcedure.sql file to import stored procedures.

Add index to the table
- Command to add indexing to the table

```ALTER TABLE `assessment`.`planning` 
ADD INDEX `equipment_index`(`equipment`),
ADD INDEX `start_index`(`start`),
ADD INDEX `end_index`(`end`)```
- Unit test is located in `tests` folder

## URL to test application
- Test Availability
`index.php?route=availability&equipment_id=2&quantity=1&start=2019-05-30&end=2019-06-04`

- Test Shortage
`index.php?route=shortage&start=2019-05-01&end=2019-05-30`


### Hint
- Make sure to use date format as Y-m-d `2019-05-30`
- All responses are formatted as JSON to use in a API 







