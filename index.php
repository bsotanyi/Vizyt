<?php

require_once('config.php');
require_once('functions.php');

$DB = new DB(DB_HOST, DB_NAME, DB_USERNAME, DB_PASSWORD, DB_CHARSET);

view('elements', [
    'title' => 'Hellobello',
]);