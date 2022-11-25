<?php

namespace Drupal\migrate_discography\Plugin\migrate_plus\data_parser;

use Drupal\migrate_plus\Plugin\migrate_plus\data_parser\Json;

/**
 * Builds relations between Albums and Tracks
 * and dedupes Album entities from a flat CSV.
 * Then delegates to the Json data parser for the selectors.
 *
 * @DataParser(
 *   id = "album_parser",
 *   title = @Translation("Album parser")
 * )
 */
class AlbumParser extends Json {

  /**
   * {@inheritdoc}
   */
  protected function getSourceData($url) {
    // Get the CSV.
    $response = $this->getDataFetcherPlugin()->getResponseContent($url);
    // Convert the flat CSV into associative arrays.
    // 0 = Id
    // 1 = Album title
    // 2 = Track title
    // 3 = Track url
    $source_data = [
      'albums' => [],
    ];
    $lines = explode("\n", $response);
    // Exclude the first (header) row. Could be moved in config.
    array_shift($lines);
    $albumDetails = [];
    foreach ($lines as $line) {
      $csvLine = str_getcsv($line);
      if (!empty($csvLine[1])) {
        if (!array_key_exists($csvLine[1], $albumDetails)) {
          $albumDetails[$csvLine[1]] = [
            'album_title' => $csvLine[1],
            'tracks' => [],
          ];
        }
        $albumDetails[$csvLine[1]]['tracks'][] = [
          'id' => $csvLine[0],
        ];
      }
    }
    // In two times, to avoid key indexed results by product id.
    foreach ($albumDetails as $albumDetail) {
      $source_data['albums'][] = $albumDetail;
    }

    // Section from parent class.

    // Backwards-compatibility for depth selection.
    if (is_int($this->itemSelector)) {
      return $this->selectByDepth($source_data);
    }

    // Otherwise, we're using xpath-like selectors.
    $selectors = explode('/', trim($this->itemSelector, '/'));
    foreach ($selectors as $selector) {
      if (!empty($selector)) {
        $source_data = $source_data[$selector];
      }
    }
    return $source_data;
  }

}
