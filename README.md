neo-backend
===========

Basic Backend Developer Interview Solution

This project implemented in php symfony.

I used PostgreSQL database because the problem is nice fit in SQL and that make it easy to fetch and maintain the data.

Assumptions
- I'm fetching data for last 3 days.
- To fetch data you have to run the command 'php bin/console app:loaddata' inside the project directory.
- Data will be stored in PostgreSQL table name neo_table under scheme public.
- My table structure

Database name : neo_data
Host : localhost
Port : 5432

CREATE TABLE NEO_TABLE (
  NEO_REFERENCE_ID VARCHAR(50),
  NAME VARCHAR(50),
  NEO_DATE DATE,
  NEO_SPEED DECIMAL,
  IS_NEO_HAZARDOUS BOOL);

- In the command 'LoadCommand' I fetch the data by GET request then parse the response and store the data into NEO_TABLE table.
- The output of this command is number of Elements that have been fetched.
- For every route I have one Controller.
- The home page managed by controller DefaultController and just print hello world in json format.
- HazardousController print all elements with hazardous = true.
- FastesthazardousController print element with hazardous = given value in the GET request and this value must be {true or false}, with the max speed (km per hour speed).
- BestyearController print the year having max number of elements with hazardous = given value in the GET request {true or false}.
- BestmonthController print the month having max number of elements with hazardous = given value in the GET request {true or false}.

- This app developed in PHP 5.6.31. For any information or problems in setup the env please contact me.
