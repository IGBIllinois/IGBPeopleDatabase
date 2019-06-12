#IGB People Database

## URL 
https://www-app2.igb.illinois.edu/IGBPeopleDatabase/

##Using IGB People Database
The IGB People Database was written to provide a central official location for 
the IGB and individual Themes to keep correct, up-to-date information on personnel.

## Installation 

1. Download the data from Github into the directory on the server
```
cd /var/www/IGBPeopleDatabase/html
git clone https://github.com/IGB-UIUC/IGBPeopleDatabase.git
```

2.  Create an alias in apache config file that points to the html folder, and restart Apache.  
```
Alias /IGBPeopleDatabase /var/www/IGBPeopleDatabase/html
```

3.  In mysql, create the database, and then run sql/IGBPeopleDatabase.sql on the mysql server to create the database tables.
From mysql:
```
CREATE TABLE TABLE_NAME;
```
From command prompt:
```
mysql -u root -p TABLE_NAME < sql/IGBPeopleDatabase.sql
```
4.  Create a user/password on the mysql server which has select/insert/delete/update permissions on the people database.
```
CREATE USER 'peopledb'@'localhost' IDENTIFIED BY 'STRONG_PASSWORD';
GRANT SELECT,INSERT,DELETE,UPDATE ON people.* to 'peopledb'@'localhost';
```
5.  Edit /conf/config.php to reflect your settings.
6.  Run composer to install php dependencies
```
composer install
```
7. Copy necessary javascript files to html folder
```
cp vendor/components/jquery html/vendor/components
cp vendor/etdsolutions/jquery.maskedinput html/vendor/components
cp vendor/datatables/datatables html/vendor
```