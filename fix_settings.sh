#!/bin/bash

# Fix the syntax error in the LocalSettings.php file
ssh -i /Users/tusharkaw/Downloads/gnospedia.pem ec2-user@13.233.126.84 "sudo sed -i 's/\'dbname\' => {\$wgDBname}_jobqueue/\'dbname\' => \$wgDBname . \"_jobqueue\"/' /var/www/html/LocalSettings.php"

# Restart Apache to apply changes
ssh -i /Users/tusharkaw/Downloads/gnospedia.pem ec2-user@13.233.126.84 "sudo systemctl restart httpd"
