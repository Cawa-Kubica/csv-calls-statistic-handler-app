<?php

define('ROOT', dirname(__DIR__) . DIRECTORY_SEPARATOR);
define('APP', ROOT . 'app' . DIRECTORY_SEPARATOR);
define('CONFIG', APP . 'config' . DIRECTORY_SEPARATOR);
define('RESOURCES', APP . 'resources' . DIRECTORY_SEPARATOR);

require CONFIG . 'config.php';
require CONFIG . 'autoload.php';

use App\Controller\MainController;

require APP . 'view/layouts/header.php';

new MainController();

require APP . 'view/layouts/footer.php';