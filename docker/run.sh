#!/bin/bash -e

if [ -n "${username}" ] && [ -n "${password}" ] && [ -n "${sensor_id}" ]; then
    cat <<EOF > /var/www/air-quality-info/config.php
<?php
define('CONFIG', array(
'devices' => array(
    array(
        'user'        => '${username}',
        'password'    => '${password}',
        'esp8266id'   => '${sensor_id}',
        'name'        => 'main',          # this will be used in URLs
        'description' => 'Main location', # user-friendly location name, will be used in navbar
    ),
),
# Whether to store the last received JSON dump.
'store_json_payload' => true,
# Google Analytics ID
'ga_id' => ''
));
?>
EOF
elif [ ! -f "/var/www/air-quality-info/config.php" ]; then
    >&2 echo "Configuration under /var/www/air-quality-info/config.php doesn't exist."
    >&2 echo "Please mount the config file or use env variables:"
    >&2 echo "username, password & sensor_id"
    exit 1
fi

/etc/init.d/php7.0-fpm start

nginx -g "daemon off;"