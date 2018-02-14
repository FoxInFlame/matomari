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
   * Contains the GET variables.
   * @var Array
   */
  private $get_variables;

  /**
   * Contains the POST variables.
   * @var Array
   */
  private $post_variables;
  
  /**
   * Constructor to build Request.
   * 
   * @param String $type The format of response data the client wants.
   * @param String $controller The name of the recognised controller.
   * @param String $specifier The specifier - which is the function name in the controller.
   * @param Array $path_variables The matched path variables for the request.
   * @param Array $get_variables The direct dump of $_GET.
   * @param Array $post_variables The parsed array dump of the POSTed JSON. 
   * @since 0.5
   */
  public function __construct(
    String $type,
    String $controller,
    String $specifier,
    Array $path_variables,
    Array $get_variables,
    Array $post_variables
  ) {
    $this->type = $type;
    $this->controller_name = $controller;
    $this->specifier = $specifier;
    $this->path_variables = $path_variables;
    $this->get_variables = $get_variables;
    $this->post_variables = $post_variables;
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
   * @return Array
   * @since 0.5
   */
  public function getPathVariables() {
    return $this->path_variables;
  }

  /** 
   * @return Array
   * @since 0.5
   */
  public function getGetVariables() {
    return $this->get_variables;
  }

  /** 
   * @return Array
   * @since 0.5
   */
  public function getPostVariables() {
    return $this->post_variables;
  }

}