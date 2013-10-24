<?php
$public_path = public_path();
$public_path = !empty($public_path) ? $public_path . '/' : '';

define('BASE_URL', URL::route('home', array(), false));

define('PODCAST_IMAGES_DIR', 'images/podcasts/');
define('PODCAST_IMAGES_DIR_ABSOLUTE', $public_path . PODCAST_IMAGES_DIR);