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
 * @OA\Schema(
 *   title="Brief Reference",
 *   type="object",
 *   required={"id","name"}
 * )
 * @since 0.5
 * @author FoxInFlame <burningfoxinflame@gmail.com>
 */
class BriefReferenceModel extends Model
{
  
  /**
   * @OA\Property(
   *   title="ID",
   *   description="The ID of the reference target"
   * )
   * @var Integer
   */
  public $id;
  
  /**
   * @OA\Property(
   *   title="Name",
   *   description="The name of the reference target"
   * )
   * @var String
   */
  public $name;
  
}