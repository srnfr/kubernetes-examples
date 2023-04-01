<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require './vendor/predis/predis/autoload.php';

Predis\Autoloader::register();

if (isset($_GET['cmd']) === true) {
  $host = 'redis-sentinel';
  if (getenv('GET_HOSTS_FROM') == 'env') {
    $host = getenv('REDIS_SENTINEL_SERVICE_HOST');
  }
  
  if (getenv('REDIS_PWD')) {
    $pwd = getenv('REDIS_PWD');
  } else {
    $pwd='redis-password';
  }
  
  header('Content-Type: application/json');
  
  /* predis bug : https://github.com/predis/predis/issues/658 */
  $sentinels = ['tcp://'.$host.':26379?password=redis-password'];
  /* 6379=RO ; 26379=RW */
  $options = [ 
      'replication' => 'sentinel', 
      'service' => 'mymaster' , 
      'parameters'  => ['database' => 0, 'password' => $pwd],
  ];
  
  $client = new Predis\Client($sentinels,$options);
  
  if ($_GET['cmd'] == 'set') {

    $client->set($_GET['key'], $_GET['value']);
    print('{"message": "Updated"}');
    
  } else {
    
    $value = $client->get($_GET['key']);
    print('{"data": "' . $value . '"}');
  }
  
} else {
  phpinfo();
} ?>
