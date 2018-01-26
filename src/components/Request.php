<?php

/**
 * A part of the matomari API.
 * 
 * @since 0.5
 * @author FoxInFlame <burningfoxinflame@gmail.com>
 * @version 0.5
 */

namespace Matomari\Components;

/**
 * Class Request
 * 
 * @since 0.5
 * @author FoxInFlame <burningfoxinflame@gmail.com>
 */
class Request
{
  
  /**
   * Contains the requested response type.
   * @var String
   */
  private $type;


  /**
   * Contains the name of controller.
   * @var String
   */
  private $controller_name;

  /**
   * Contains the specifier for the controller.
   * @var String
   */
  private $specifier;

  /**
   * Contains the path variables.
   * @var Array
   */
  private $path_variables;
  
  /**
   * Constructor to build Request.
   * 
   * @param String $type The type of response data the client wants.
   * @param String $controller The name of the recognised controller.
   * @param String $specifier The specifier for the controller.
   * @param Arary $path_variables The path variable matches 
   * @since 0.5
   */
  public function __construct(String $type, String $controller, String $specifier, Array $path_variables) {
    $this->type = $type;
    $this->controller_name = $controller;
    $this->specifier = $specifier;
    $this->path_variables = $path_variables;

  }

  /** 
   * @return String
   * @since 0.5
   */
  public function getType() {
    return $this->type;
  }

  /** 
   * @return String
   * @since 0.5
   */
  public function getControllerName() {
    return $this->controller_name;
  }

  /** 
   * @return String
   * @since 0.5
   */
  public function getSpecifier() {
    return $this->specifier;
  }

  /** 
   * @return String
   * @since 0.5
   */
  public function getPathVariables() {
    return $this->path_variables;
  }

}