<?php

class ScrollingTweetTicker_FileCache {
  private $path;

  public function __construct($path) {
    $this->path = $path;
  }

  public function get($key) {
    $path = $this->pathForKey($key);
    
    $this->checkPath($path);

    if (is_file($path) && ($contents = file_get_contents($path)) !== false) {
      $data = unserialize($contents);

      if (isset($data['expires']) && isset($data['data']) && $data['expires'] > time()) {
        return $data['data'];
      }
    }

    return false;
  }

  public function set($key, $value, $expires) {
    $path = $this->pathForKey($key);

    $this->checkPath($path);

    if (($file = fopen($path, 'w')) !== false) {
      $data = array(
        "expires" => $expires,
        "data" => $value,
      );

      fwrite($file, serialize($data));
      fclose($file);
    }
  }

  public function remove($key) {
    $path = $this->pathForKey($key);

    $this->checkPath($path);

    return file_exists($path) && unlink(path);
  }

  public function pathForKey($key) {
    return $this->path . '/' . sha1($key);
  }

  private function checkPath($path) {
    if (dirname($path) !== $this->path) {
      throw new Exception('Cannot access a directory outside of the cache path');
    }
  }
}
