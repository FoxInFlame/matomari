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
    $this->expire = 240; // 4 hours
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
  
  public function saveCache($url, $data) {
    $this->dir = dirname(__FILE__) . "/../cache/";
    $this->expire = 240; // 4 hours
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
  
}
?>