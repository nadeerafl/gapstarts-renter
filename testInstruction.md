# Setup
- Use storedProcedure.sql file to import stored procedures.

## URL to test application
index.php?equipment_id=1&quantity=1&start=2019-05-30&end=2019-06-04

### Hint 1 
- Makesure to use date format as Y-m-d `2019-05-30`
- Run bellow command to add indexing to the table
```ALTER TABLE `assessment`.`planning` 
ADD INDEX `equipment_index`(`equipment`),
ADD INDEX `start_index`(`start`),
ADD INDEX `end_index`(`end`)```






