#* Update aptitude repository info
apt-get update

#**Install Apache/MySQL/PHP
apt install apache2 mysql-client mysql-server php libapache2-mod-php

#**Install Additional Software
apt install -y graphviz aspell ghostscript clamav php7.2-pspell php7.2-curl php7.2-gd php7.2-intl php7.2-mysql php7.2-xml php7.2-xmlrpc php7.2-ldap php7.2-zip php7.2-soap php7.2-mbstring

#**Restart apache to load modules correctly
service apache2 restart

#**Install git
apt install -y git

#**Download moodle

cd /opt
#< download moodle code index
git clone git://git.moodle.org/moodle.git

cd moodle

#< retrieve a list of each branch available
git branch -a

#< tell git to track latest stable version
MOODLE_STABLE_VERSION="MOODLE_38_STABLE"
git branch --track $MOODLE_STABLE_VERSION origin/$MOODLE_STABLE_VERSION

#< checkout the specified version
git checkout $MOODLE_STABLE_VERSION

#**Copy local repo to /var/www/html/
cp -R /opt/moodle /var/www/html/
mkdir /var/moodledata
chown -R www-data /var/moodledata
chmod -R 777 /var/moodledata
#*after installation
#chmod -R 0755 /var/www/html/moodle
