<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require './vendor/predis/predis/autoload.php';

Predis\Autoloader::register();

if (isset($_GET['cmd']) === true) {
  $host = 'redis-sentinel';
  if (getenv('GET_HOSTS_FROM') == 'env') {
    $host = getenv('REDIS_MASTER_SERVICE_HOST');
  }
  header('Content-Type: application/json');
  if ($_GET['cmd'] == 'set') {
    $sentinels = ['tcp://'.$host];
    $options = [ 
        'replication' => 'sentinel', 
        'service' => 'mymaster' , 
        'parameters'  => ['database' => 0, 'password' => 'redis-password'],
      ]
    print_r($sentinels);
    print_r($options);
    $client = new Predis\Client($sentinels,$options);

    $client->set($_GET['key'], $_GET['value']);
    print('{"message": "Updated"}');
  } else {
    $host = 'redis-sentinel';
    if (getenv('GET_HOSTS_FROM') == 'env') {
      $host = getenv('REDIS_SLAVE_SERVICE_HOST');
    }
        $sentinels = ['tcp://'.$host];
    $options = [ 
        'replication' => 'sentinel', 
        'parameters'  => ['database' => 0, 'password' => 'redis-password'],
      ]
    print_r($sentinels);
    print_r($options);
    $client = new Predis\Client($sentinels,$options);

    $value = $client->get($_GET['key']);
    print('{"data": "' . $value . '"}');
  }
} else {
  phpinfo();
} ?>
