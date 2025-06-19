<?php
require_once __DIR__ . '/../models/Book.php';
require_once __DIR__ . '/../config/Template.php';

class StatisticsController
{
  private $bookModel;

  public function __construct()
  {
    $this->bookModel = new Book();
  }

  public function index(): string
  {
    $bookStatistics = $this->bookModel->getAllBooks();
    $template = new Template('views/statistics_view.php');
    $template->bookStatistics = $bookStatistics;
    return $template->render();
  }

  public function exportCsv()
  {
    $data = $this->bookModel->getAllBooks();

    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="book_statistics.csv"');

    $output = fopen('php://output', 'w');

    fputcsv($output, ['Title', 'Author', 'Genre', 'Review Count', 'Average Rating']);

    foreach ($data as $row) {
      fputcsv($output, [
        $row['title'],
        $row['author'],
        $row['genre'],
        $row['review_count'],
        number_format($row['avg_rating'], 2)
      ]);
    }

    fclose($output);
    exit;
  }

  public function exportDocbook()
  {
    $data = $this->bookModel->getAllBooks();

    $doc = new DOMDocument('1.0', 'UTF-8');
    $doc->formatOutput = true;

    $docbook_ns = 'http://docbook.org/ns/docbook';

    $book = $doc->createElementNS($docbook_ns, 'book');
    $book->setAttribute('xml:id', 'book_statistics');
    $book->setAttribute('version', '5.0');
    $doc->appendChild($book);

    $title = $doc->createElementNS($docbook_ns, 'title', 'Book Review Statistics');
    $book->appendChild($title);

    $chapter = $doc->createElementNS($docbook_ns, 'chapter');
    $chapter->setAttribute('xml:id', 'statistics_chapter');
    $book->appendChild($chapter);

    $chapterTitle = $doc->createElementNS($docbook_ns, 'title', 'Book Statistics');
    $chapter->appendChild($chapterTitle);

    $para = $doc->createElementNS($docbook_ns, 'para');
    $chapter->appendChild($para);

    $table = $doc->createElementNS($docbook_ns, 'table');
    $para->appendChild($table);

    $tableTitle = $doc->createElementNS($docbook_ns, 'title', 'Reviews and Ratings');
    $table->appendChild($tableTitle);

    $tgroup = $doc->createElementNS($docbook_ns, 'tgroup');
    $tgroup->setAttribute('cols', '5');
    $table->appendChild($tgroup);

    $thead = $doc->createElementNS($docbook_ns, 'thead');
    $tgroup->appendChild($thead);

    $headerRow = $doc->createElementNS($docbook_ns, 'row');
    $thead->appendChild($headerRow);

    $headerRow->appendChild($doc->createElementNS($docbook_ns, 'entry', 'Title'));
    $headerRow->appendChild($doc->createElementNS($docbook_ns, 'entry', 'Author'));
    $headerRow->appendChild($doc->createElementNS($docbook_ns, 'entry', 'Genre'));
    $headerRow->appendChild($doc->createElementNS($docbook_ns, 'entry', 'Review Count'));
    $headerRow->appendChild($doc->createElementNS($docbook_ns, 'entry', 'Average Rating'));

    $tbody = $doc->createElementNS($docbook_ns, 'tbody');
    $tgroup->appendChild($tbody);

    foreach ($data as $stat) {
      $row = $doc->createElementNS($docbook_ns, 'row');
      $row->appendChild($doc->createElementNS($docbook_ns, 'entry', htmlspecialchars($stat['title'])));
      $row->appendChild($doc->createElementNS($docbook_ns, 'entry', htmlspecialchars($stat['author'])));
      $row->appendChild($doc->createElementNS($docbook_ns, 'entry', htmlspecialchars($stat['genre'])));
      $row->appendChild($doc->createElementNS($docbook_ns, 'entry', $stat['review_count']));
      $row->appendChild($doc->createElementNS($docbook_ns, 'entry', number_format($stat['avg_rating'], 2)));
      $tbody->appendChild($row);
    }

    header('Content-Type: application/xml; charset=UTF-8');
    header('Content-Disposition: attachment; filename="book_statistics.xml"');

    echo $doc->saveXML();
    exit;
  }
}