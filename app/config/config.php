<?php

define('URL_PUBLIC_FOLDER', 'public');
define('URL_SUB_FOLDER', str_replace(URL_PUBLIC_FOLDER, '', dirname($_SERVER['SCRIPT_NAME'])));

define('URL', 'http://' . $_SERVER['HTTP_HOST'] . URL_SUB_FOLDER);

define('IPSTACK_API_KEY', '6d9399aa82e880f33c125b5a2887c813');

define('APP_TITLE', 'CSV-Calls loader');