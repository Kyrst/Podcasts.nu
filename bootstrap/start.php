<?php
$app = new Illuminate\Foundation\Application;

$app->redirectIfTrailingSlash();

$env = $app->detectEnvironment(array(
	'local' => array('podcasts.loc'),
	'dev' => array('www.myrberginnovation.se'),
	'live' => array('podcasts.nu')
));

$app->bindInstallPaths(require __DIR__.'/paths.php');

$framework = $app['path.base'].'/vendor/laravel/framework/src';

require $framework.'/Illuminate/Foundation/start.php';

include app_path() . '/constants.php';

return $app;