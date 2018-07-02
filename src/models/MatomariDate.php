<?php

/**
 * A part of the matomari API.
 * 
 * @since 0.5
 * @author FoxInFlame <burningfoxinflame@gmail.com>
 * @version 0.5
 */

namespace Matomari\Models;

/** 
 * @OAS\Schema(
 *   title="Matomari Date",
 *   schema="MatomariDate",
 *   type="object",
 *   required={"iso8601"},
 *   @OAS\Property(
 *     property="year",
 *     type="integer",
 *     nullable=true,
 *     title="year",
 *     description="4 digit year representation"
 *   ),
 *   @OAS\Property(
 *     property="month",
 *     type="integer",
 *     nullable=true,
 *     title="month",
 *     description="2 digit month representation"
 *   ),
 *   @OAS\Property(
 *     property="day",
 *     type="integer",
 *     nullable=true,
 *     title="day",
 *     description="2 digit day representation"
 *   ),
 *   @OAS\Property(
 *     property="hour",
 *     type="integer",
 *     nullable=true,
 *     title="hour",
 *     description="2 digit hour representation"
 *   ),
 *   @OAS\Property(
 *     property="minute",
 *     type="integer",
 *     nullable=true,
 *     title="minute",
 *     description="2 digit minute representation"
 *   ),
 *   @OAS\Property(
 *     property="second",
 *     type="integer",
 *     nullable=true,
 *     title="second",
 *     description="2 digit second representation"
 *   ),
 *   @OAS\Property(
 *     property="offset",
 *     type="string",
 *     nullable=true,
 *     title="offset",
 *     description="Time offset compared to UTC"
 *   ),
 *   @OAS\Property(
 *     property="iso8601",
 *     type="string",
 *     title="iso8601",
 *     description="The ISO 8601 compatible date (might omit some information to avoid partial dates)"
 *   )
 * )
 * @since 0.5
 * @author FoxInFlame <burningfoxinflame@gmail.com>
 */