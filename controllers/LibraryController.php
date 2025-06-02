<?php
require_once __DIR__ . '/../services/LibraryService.php';

class LibraryController
{
  private $libraryService;

  public function __construct()
  {
    $this->libraryService = new LibraryService();
  }

  public function findNearby()
  {
    header('Content-Type: application/json');

    $latitude = $_GET['lat'] ?? null;
    $longitude = $_GET['lon'] ?? null;
    $limit = $_GET['limit'] ?? 10;

    if (!$latitude || !$longitude || !is_numeric($latitude) || !is_numeric($longitude)) {
      http_response_code(400); // Bad Request
      echo json_encode(['error' => 'Invalid coordinates']);
      exit;
    }

    $limit = max(1, min(50, (int) $limit));

    $result = $this->libraryService->getNearbyLibraries((float) $latitude, (float) $longitude, $limit);

    if (isset($result['error'])) {
      http_response_code(404);
      echo json_encode(['error' => $result['error']]);
      exit;
    }

    echo json_encode(['libraries' => $result]);
    exit;
  }
}
?>