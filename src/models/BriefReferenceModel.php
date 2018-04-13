<?php

/**
 * A part of the matomari API.
 * 
 * @since 0.5
 * @author FoxInFlame <burningfoxinflame@gmail.com>
 * @version 0.5
 */

namespace Matomari\Models;

use Matomari\Models\Model;

/** 
 * @OAS\Schema(
 *   title="Brief Reference",
 *   required={"id","name"}
 * )
 * @since 0.5
 * @author FoxInFlame <burningfoxinflame@gmail.com>
 */
class BriefReferenceModel extends Model
{
  
  /**
   * @OAS\Property(
   *   title="ID",
   *   description="The reference ID"
   * )
   * @var Integer
   */
  public $id;
  
  /**
   * @OAS\Property(
   *   title="Name",
   *   description="The reference name"
   * )
   * @var String
   */
  public $name;
  
}