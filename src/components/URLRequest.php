<?php

/**
 * A part of the matomari API.
 * 
 * @since 0.0.1
 * @author FoxInFlame <burningfoxinflame@gmail.com>
 * @version 0.0.1
 */

namespace Matomari\Components;

/**
 * Class URLRequest
 * 
 * @since 0.0.1
 * @author FoxInFlame <burningfoxinflame@gmail.com>
 */
class URLRequest
{

  /**
   * Contains the entire URL.
   * @var String
   */
  private $url;

  /**
   * Contains the scheme.
   * @var String
   */
  private $scheme;

  /**
   * Contains the host.
   * @var String
   */
  private $host;
  
  /**
   * Contains the path.
   * @var String
   */
  private $path;

  /**
   * Contains the query.
   * @var String
   */
  private $query;

  /**
   * Constructor to build URLRequest.
   * 
   * @param String $url The raw dump of the server request URL 
   * @since 0.0.1
   */
  public function __construct(String $url) {
    $parsed_url = parse_url($url);

    if(!$parsed_url) {
      echo 'not correct format';
    }

    $this->url = $url;
    $this->scheme = $parsed_url['scheme'];
    $this->host = $parsed_url['host'];
    $this->path = str_replace('/api/0.4', '', $parsed_url['path']);
    $this->query = $parsed_url['query'] ?? '';

  }

  /** 
   * @return String
   * @since 0.0.1
   */
  public function getURL() {
    return $this->url;
  }

  /** 
   * @return String
   * @since 0.0.1
   */
  public function getScheme() {
    return $this->scheme;
  }

  /** 
   * @return String
   * @since 0.0.1
   */
  public function getHost() {
    return $this->host;
  }

  /** 
   * @return String
   * @since 0.0.1
   */
  public function getPath() {
    return $this->path;
  }

  /** 
   * @return String
   * @since 0.0.1
   */
  public function getQuery() {
    return $this->query;
  }

}