<?php
$public_path = public_path();
$public_path = !empty($public_path) ? $public_path . '/' : '';

define('BASE_URL', URL::route('home', array(), false));

define('NEWS_IMAGES_DIR', 'images/news/');
define('NEWS_IMAGES_DIR_ABSOLUTE', $public_path . NEWS_IMAGES_DIR);

define('BLOG_IMAGES_DIR', 'images/blog/');
define('BLOG_IMAGES_DIR_ABSOLUTE', $public_path . BLOG_IMAGES_DIR);

define('PODCAST_IMAGES_DIR', 'images/podcasts/');
define('PODCAST_IMAGES_DIR_ABSOLUTE', $public_path . PODCAST_IMAGES_DIR);