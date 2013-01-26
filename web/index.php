<?php

require_once __DIR__ . '/../vendor/autoload.php';


$app = new Silex\Application();
$app['debug'] = true;

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__ . '/../views',
));

$app->get('/', function () use ($app)
{

    $data = \Symfony\Component\Yaml\Yaml::parse(__DIR__.'/../config/data.yml');

    ksort($data['speakers']);

    $data['talks'] = array();
    $i = 0;
    foreach($data['speakers'] as $name => $sdata) {
        foreach($sdata['talks'] as $t) {
            $t['speaker'] = $name;
            $t['avatar'] = $sdata['avatar'];
            $data['talks'][$t['date'].'.'.$t['time'].'.'.$i++] = $t;
        }
    }

    ksort($data['talks']);

    return $app['twig']->render('index.html.twig', array(
        'speakers' => $data['speakers'],
        'talks' => $data['talks'],
    ));
});

$app->run();