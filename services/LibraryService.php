<?php
class LibraryService
{
  public function getNearbyLibraries(float $latitude, float $longitude, int $limit = 5)
  {
    $url = "https://nominatim.openstreetmap.org/search?" . http_build_query([
      'format' => 'json',
      'limit' => $limit,
      'q' => 'library',
      'viewbox' => ($longitude - 0.05) . "," . ($latitude + 0.05) . "," . ($longitude + 0.05) . "," . ($latitude - 0.05),
      'bounded' => 1
    ]);

    $context = stream_context_create([
      'http' => [
        'header' => "User-Agent: BoW/1.0 (Fetch)",
        'method' => 'GET',
        'timeout' => 20,
        'ignore_errors' => true
      ],
    ]);

    $response = file_get_contents($url, false, $context);
    if ($response === false) {
      return ['error' => 'Failed to fetch data'];
    }

    $data = json_decode($response, true); // associative array

    $libraries = [];
    foreach ($data as $place) {
      $libraries[] = [
        'name' => $place['name'] ?: 'Unnamed Library',
        'osm_id' => $place['osm_id'],
        'osm_type' => $place['osm_type'],
        'lat' => (float) $place['lat'],
        'lon' => (float) $place['lon'],
        'address' => $place['display_name']
      ];
    }

    return $libraries;
  }
}
?>