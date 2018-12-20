#IGB People Database

##Installation
1.  Clone the code from github into the web directory 
        `git clone https://github.com/IGB-UIUC/IGBPeopleDatabase.git`
2.  Create alias in the appropriate .conf file on the server.
3.  Create mysql database and import sql/IGBPeopleDatabase.sql into it,
    or import a saved .sql file from a previous mysqldump
4.  Change LDAP and MySql configuration in the conf/config.php file to match your environment
5.  Run 'composer install' in the main directory
6.  Use the following commands to copy folders to html/vendor or html/vendor/components;
    * `cp vendor/components/jquery html/vendor/components`
    * `cp vendor/etdsolutions/jquery.maskedinput html/vendor/components`
    * `cp vendor/datatables/datatables html/vendor`
    
5.  Repeat the process for test database, IGBPeopleDatabase-dev

##Using IGB People Database
The IGB People Database was written to provide a central official location for 
the IGB and individual Themes to keep correct, up-to-date information on personnel.

