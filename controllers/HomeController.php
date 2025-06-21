<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/Template.php';

class HomeController
{
  public function index(): string
  {
    $feed = new SimplePie\SimplePie();
    $feed->set_feed_url($_ENV['DOMAIN'] . '/rss');
    $feed->enable_cache(false);
    $feed->init();
    $feed->handle_content_type();

    $template = new Template('views/home.tpl');
    $template->feedItems = $feed->get_items();
    $template->feedError = $feed->error();
    return $template->render();
  }
}