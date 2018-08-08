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
 *   nullable=true,
 *   required={"iso8601"},
 *   @OAS\Property(
 *     property="year",
 *     type="integer",
 *     nullable=true,
 *     title="year",
 *     description="4 digit year representation",
 *     example=2017
 *   ),
 *   @OAS\Property(
 *     property="month",
 *     type="integer",
 *     nullable=true,
 *     title="month",
 *     description="1 or 2 digit month representation (no leading zeroes)",
 *     example=2
 *   ),
 *   @OAS\Property(
 *     property="day",
 *     type="integer",
 *     nullable=true,
 *     title="day",
 *     description="1 or 2 digit day representation (no leading zeroes)",
 *     example=31
 *   ),
 *   @OAS\Property(
 *     property="hour",
 *     type="integer",
 *     nullable=true,
 *     title="hour",
 *     description="1 or 2 digit hour representation (no leading zeroes, 24 hour format)",
 *     example=14
 *   ),
 *   @OAS\Property(
 *     property="minute",
 *     type="integer",
 *     nullable=true,
 *     title="minute",
 *     description="1 or 2 digit minute representation (no leading zeroes)",
 *     example=34
 *   ),
 *   @OAS\Property(
 *     property="second",
 *     type="integer",
 *     nullable=true,
 *     title="second",
 *     description="1 or 2 digit second representation (no leading zeroes)",
 *     example=2
 *   ),
 *   @OAS\Property(
 *     property="offset",
 *     type="string",
 *     nullable=true,
 *     title="offset",
 *     description="Time offset compared to UTC",
 *     example="+03:00"
 *   ),
 *   @OAS\Property(
 *     property="iso8601",
 *     type="string",
 *     title="iso8601",
 *     description="The ISO 8601 compatible date (might omit some information to avoid partial dates)",
 *     example="2017-04-05T12:30+03:00"
 *   )
 * )
 * @since 0.5
 * @author FoxInFlame <burningfoxinflame@gmail.com>
 */