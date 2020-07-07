# ExamStats

ExamStats is a basic tool that I wrote in 2016 to generate exam statistics. It uses an unformatted
list of grades and points achieved by individual students and tries to interpret the list and create
an statistical dashboard based on the given data.

## Setup Info

Change the mysql connection information in the file "server/mysql_connection_info.php", then
run "server/mysql_setup.php" to create the required mysql tables.

You can then serve the files via an apache server.