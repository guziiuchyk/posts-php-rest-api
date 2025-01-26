<?php

require_once 'vendor/autoload.php';

require_once 'utils/config.php';

require_once 'utils/connect.php';
require_once 'utils/functions.php';

require_once 'controllers/postController.php';
require_once 'controllers/authController.php';
require_once 'controllers/userController.php';

require_once 'modules/postModel.php';
require_once 'modules/userModel.php';
require_once 'modules/tokenModel.php';

require_once 'router.php';

header('Content-type: json/application');

$router = new Router();

require_once 'routers/rootRouter.php';
require_once 'routers/userRouter.php';
require_once 'routers/postRouter.php';
require_once 'routers/authRouter.php';

$method = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

$router->resolve($method, $uri);