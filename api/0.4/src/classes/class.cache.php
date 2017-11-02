<?php
/*

Tried making my own cache script. Don't know if it's working or not...

*/

class Data {
  
  private $dir; // Default cache directory
  private $expire; // Default expiration of cache (probably in minutes)
  private $extension; // Default cache extension (don't know why it's neccessary, but the tutorial had this)
  private $ignore_pages = array(); // Array of pages to ignore
  private $url; // URL
  public $data;
  
  public function getCache($url, $curl_options = array()) {
    $this->dir = dirname(__FILE__) . "/../cache/";
    /*$this->expire = 1440; // 24 hours*/
    $this->expire = 21900; // 1 year - Cache will be purged from another script every day anyway
    $this->extension = ".html";
    $this->ignore_pages = array(
      "/mymessages.php",
      "/notification"
    );
    
    if(isset($_GET['nocache']) && $_GET['nocache'] == "true") {
      return false;
    }
    
    $cache_file = $this->dir . md5($url) . $this->extension;
    
    $ignore = false;
    
    foreach($this->ignore_pages as $ignore_page) {
      if(strpos($url, $ignore_page) !== false) {
        $ignore = true;
        break;
      }
    }
    
    if(!$ignore && file_exists($cache_file)  && (filemtime($cache_file) > (time() - 60 * $this->expire))) {
      // ob_start(); // Optionally ob_start("ob_gzhandler") to GZip (compress) before sending
      // readfile($cache_file);
      // ob_end_flush();
      // die();
      $this->data = file_get_contents($cache_file);
      return true;
    }
    
    return false;
  }
  
  public function saveCache($url, $data, $expire = 21900) {
    $this->dir = dirname(__FILE__) . "/../cache/";
    $this->expire = $expire; // One Year
    $this->extension = ".html";
    $this->ignore_pages = array(
      "/mymessages.php",
      "/notification"
    );
    
    $cache_file = $this->dir . md5($url) . $this->extension;
    
    $ignore = false;
    
    foreach($this->ignore_pages as $ignore_page) {
      if(strpos($url, $ignore_page) !== false) {
        $ignore = true;
        break;
      }
    }
    
    // Please create a folder called "cache" with 0777 permission, underneath directory 0.4
    // I did this manually through FTP and not through GitHub
    
    if(!$ignore) {
      $file = @fopen($cache_file, "w");
      if(!$file) return false;
      fwrite($file, $data);
      fclose($file);
      chmod($cache_file, 0755);
    }
    return true;
  }
  
  public function purgeCache($url = false) {
    $this->dir = dirname(__FILE__) . "/../cache/";
    $this->extension = ".html";
    
    if($url) {
      $cache_file = $this->dir . md5($url) . $this->extension;
      
      $cache_file_realpath = realpath($cache_file);
      if(is_writable($cache_file_realpath)) {
        unlink($cache_file_realpath);
        http_response_code(200);
      } else {
        http_response_code(500);
      }
    } else {
      $files = glob(dirname(__FILE__) . "/../cache/*");
      foreach($files as $file) {
        if(is_file($file)) {
          unlink($file);
        }
      }
      http_response_code(200);
    }
  }
}
?>