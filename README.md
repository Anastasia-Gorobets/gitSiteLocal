To start need to perform the following items:
1.Open db folder in the root folder, find and run db.sql  file to create the database and its tables.
2.Open db folder in the root folder, find database_config.php  file and change your host, username, password to access the database.
3.Configure a virtual host, for example XAMPP Server: go to the file xampp\apache\conf\extra\httpd-vhosts.conf and and write the following:

<VirtualHost *:80>
DocumentRoot "Path to Root Folder"
ServerName Your server name
ServerAlias Your server alias
<Directory "Path to Root Folder">
    # AllowOverride All      # Deprecated
        # Order Allow,Deny       # Deprecated
        # Allow from all         # Deprecated
        # --New way of doing it
        Require all granted
  </Directory>
</VirtualHost>
4.Open HOST file and write:
127.0.0.1             Your server alias
127.0.0.1             Your server name


5.Open Your server name in browser - it is home  page.
6.Click Registration button, write your login,password and click Sign up button.
7.Now you can sign in.Write your login and password, click Sign in - you are on main page with features.
8.Choose a feature, for example Emplyees.If you are admin you can click Admin Panel and add a new employee.
9.You can choose records number for printing, choose department,position,payment.
10.For logout click Logout in header panel.

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Anastasia-Gorobets/gitSiteLocal/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Anastasia-Gorobets/gitSiteLocal/?branch=master)
