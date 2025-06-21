<?php

require_once __DIR__ . '/../models/Book.php';
require_once __DIR__ . '/../models/Review.php';

class RssController
{
  public function generateFeed()
  {
    $bookModel = new Book();
    $reviewModel = new Review();

    $latestBooks = $bookModel->getLatestBooks(10);
    $latestReviews = $reviewModel->getLatestReviews(10);

    $feedItems = [];

    foreach ($latestBooks as $book) {
      $feedItems[] = [
        'type' => 'book',
        'data' => $book,
        'timestamp' => strtotime($book['created_at'])
      ];
    }

    foreach ($latestReviews as $review) {
      $feedItems[] = [
        'type' => 'review',
        'data' => $review,
        'timestamp' => strtotime($review['created_at'])
      ];
    }

    usort($feedItems, function ($a, $b) {
      return $b['timestamp'] - $a['timestamp'];
    });

    $feedItems = array_slice($feedItems, 0, 300);

    $doc = new DOMDocument('1.0', 'UTF-8');
    $doc->formatOutput = true;

    $rss = $doc->createElement('rss');
    $rss->setAttribute('version', '2.0');
    $doc->appendChild($rss);

    $channel = $doc->createElement('channel');
    $channel->appendChild($doc->createElement('title', 'Carti si Recenzii Adaugate Recent'));
    $channel->appendChild($doc->createElement('link', $_ENV['DOMAIN']));
    $channel->appendChild($doc->createElement('description', 'Cartile si recenziile adaugate cel mai recent'));
    $channel->appendChild($doc->createElement('language', 'ro'));
    $rss->appendChild($channel);

    foreach ($feedItems as $itemData) {
      $item = $doc->createElement('item');
      $book = null;
      $review = null;
      $title = '';
      $link = '';
      $description = '';
      $pubDate = '';

      if ($itemData['type'] === 'book') {
        $book = $itemData['data'];
        $title = 'Carte noua adaugata: ' . $book['title'];
        $link = $_ENV['DOMAIN'] . '/book?id=' . $book['id'];
        $description = 'Autor: ' . $book['author'];
        $pubDate = date(DATE_RSS, $itemData['timestamp']);
      } else {
        $review = $itemData['data'];
        $title = $review['username'] . ' a recenzat ' . $review['book_title'];
        $link = $_ENV['DOMAIN'] . '/book?id=' . $review['book_id'];
        $description = $review['comment'];
        $pubDate = date(DATE_RSS, $itemData['timestamp']);
      }

      $item->appendChild($doc->createElement('title', htmlspecialchars($title, ENT_XML1, 'UTF-8')));
      $item->appendChild($doc->createElement('link', htmlspecialchars($link, ENT_XML1, 'UTF-8')));
      $item->appendChild($doc->createElement('description', htmlspecialchars($description, ENT_XML1, 'UTF-8')));
      $item->appendChild($doc->createElement('pubDate', $pubDate));


      $channel->appendChild($item);
    }

    header('Content-Type: application/rss+xml; charset=UTF-8');
    echo $doc->saveXML();
    exit;
  }
}