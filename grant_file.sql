-- Grant FILE privilege to sqli_user for Lab 9 (LOAD_FILE/INTO OUTFILE)
GRANT FILE ON *.* TO 'sqli_user'@'%';
FLUSH PRIVILEGES;
