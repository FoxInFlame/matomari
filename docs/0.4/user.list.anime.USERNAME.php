user/list/anime/:username is a method that allows you to view the anime list of a user.<br>
<table class="ui celled green table">
  <thead>
    <tr>
      <th colspan="2">GET user/list/anime/:username</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td class="four wide">Method</td>
      <td class="twelve wide">GET</td>
    </tr>
    <tr>
      <td>Authentication</td>
      <td>None Required</td>
    </tr>
    <tr>
      <td>Parameters</td>
      <td>
        <table class="ui celled table">
          <thead>
            <tr>
              <th class="four wide">Name</th>
              <th class="four wide">Possible Values</th>
              <th class="eight wide">Description</th>
            </tr>
          </thead>
          <tbody class="top aligned">
            <tr>
              <td><span class="inline-code">:username</span></td>
              <td><i>The database name of the user.</i></td>
              <td>The username of the user on MAL.</td>
            </tr>
            <tr>
              <td><span class="inline-code">status</span> [Optional]</td>
              <td>1, 2, 3, 4, 6, 7<br>(default: 7)</td>
              <td>1 through 6 excluding 5 are MAL list statuses that everyone should know, and 7 is just everything</td>
            </tr>
            <tr>
              <td><span class="inline-code">page</span> [Optional]</td>
              <td><i>Any natural number.</i><br>(default: "1")</td>
              <td>Page number for the animelists.</td>
            </tr>
          </tbody>
        </table>
      </td>
    </tr>
    <tr>
      <td>Status Codes</td>
      <td>
        <b>200</b> (<a href="responsecodes#all-ok">OK</a>)<br>
        <b>400</b> (<a href="responsecodes#invalid-id-format">Invalid parameters</a>)<br>
        <b>404</b> (<a href="responsecodes#id-doesnt-exist">The provided user could not be found</a> &middot; <a href="responsecodes#myanimelist-is-offline">MAL is offline</a>)<br>
        <b>429</b> (<a href="responsecodes#too-many-requests">Too Many Requests</a>)<br>
      </td>
    </tr>
  </tbody>
</table>
<div class="ui icon message">
  <i class="file outline icon"></i>
  <div class="content">
    <p>Responses to this method are <a href="cache.html">cached</a> for <b>an hour</b>.</p>
  </div>
</div>
<div class="ui info icon message">
  <i class="announcement icon"></i>
  <div class="content">
    <div class="header">Please</div>
    <p>It is highly recommended to use the <span class="inline-code">Link</span> header to navigate through the list pages instead of trying to construct your own URL.</p>
  </div>
</div>
<h2 class="ui header">Call</h2>
<div class="ui top attached tabular menu example-code">
  <a class="item active" data-tab="model">Model</a>
  <a class="item" data-tab="request">Example Request</a>
  <a class="item" data-tab="response">Example Response</a>
</div>
<div class="ui bottom attached tab segment active" data-tab="model">
<?php
// Model of the result. Ignore all double brackets - [{}]
$model = [
  "stats" => [
    "type" => "Object",
    "description" => "Basic list stats",
    "children" => [
      "watching" => [
        "type" => "Integer",
        "description" => "Amount of anime in the \"watching\" section (includes rewatching)"
      ],
      "completed" => [
        "type" => "Integer",
        "description" => "Amount of anime in the \"completed\" section"
      ],
      "on_hold" => [
        "type" => "Integer",
        "description" => "Amount of anime in the \"on hold\" section"
      ],
      "dropped" => [
        "type" => "Integer",
        "description" => "Amount of anime in the \"dropped\" section"
      ],
      "plan_to_watch" => [
        "type" => "Integer",
        "description" => "Amount of anime in the \"plan to watch\" section"
      ]
    ]
  ],
  "items" => [
    "type" => "Array",
    "description" => "List of anime, 300 items per page",
    "children" => [
      "id" => [
        "type" => "Integer",
        "description" => "The anime id on MAL"
      ],
      "title" => [
        "type" => "String",
        "description" => "The anime title"
      ],
      "mal_url" => [
        "type" => "String",
        "description" => "Browser URL for the anime on MAL"
      ],
      "image_url" => [
        "type" => "String",
        "description" => "Direct URL of the anime cover image on MAL"
      ],
      "other_titles" => [
        "type" => "Object",
        "description" => "Other titles for the anime on MAL",
        "children" => [
          "synonyms" => [
            "type" => "Array",
            "description" => "Synonymous titles on MAL"
          ]
        ]
      ],
      "type" => [
        "type" => "String",
        "description" => "Anime media type"
      ],
      "episodes" => [
        "type" => "Integer",
        "description" => "Total number of episodes; null if unknown"
      ],
      "air_status" => [
        "type" => "String",
        "description" => "The airing status of the anime"
      ],
      "air_dates" => [
        "type" => "Object",
        "description" => "The air dates of the anime",
        "children" => [
          "from" => [
            "type" => "String",
            "description" => "Air start date in YYYY-MM-DD format (with - as unknown)"
          ],
          "to" => [
            "type" => "String",
            "description" => "Air end date in YYYY-MM-DD format (with - as unknown)"
          ]
        ]
      ],
      "rating" => [
        "type" => "String",
        "description" => "Unparsed rating of the anime as it appears on MAL"
      ],
      "watch_status" => [
        "type" => "String",
        "description" => "The status in the user's list"
      ],
      "watched_episodes" => [
        "type" => "Integer",
        "description" => "The number of episodes the user has watched"
      ],
      "watch_score" => [
        "type" => "Integer",
        "description" => "The score the user gave to the anime"
      ],
      "watch_dates" => [
        "type" => "Object",
        "description" => "The watch dates of the anime",
        "children" => [
          "from" => [
            "type" => "String",
            "description" => "Watch start date in YYYY-MM-DD format (with - as unknown)"
          ],
          "to" => [
            "type" => "String",
            "description" => "Watch end date in YYYY-MM-DD format (with - as unknown)"
          ]
        ]
      ],
      "tags" => [
        "type" => "String",
        "description" => "Comma separated tags the user set"
      ],
      "priority" => [
        "type" => "String",
        "description" => "The three-step priority that the user assigned"
      ],
      "storage" => [
        "type" => "Integer",
        "description" => "The storage type id - Not ported to string yet"
      ], // TODO: Port to string 
      "storage_amount" => [
        "type" => "Integer",
        "description" => "The amount of storage the type above has"
      ],
      "rewatching" => [
        "type" => "Boolean",
        "description" => "If the user is currently rewatching the anime"
      ],
      "last_updated" => [
        "type" => "String",
        "description" => "Full ISO 8601 date and time in GMT"
      ],
      "days_spent_watching" => [
        "type" => "Integer",
        "description" => "Days elapsed from the watch start to watch end (now if watch end is not specified)"
      ]
    ]
  ]
];

require_once("model2list.php"); // This reads $model and echoes a model.
?>
</div>
<div class="ui bottom attached tab segment" data-tab="request">
  <pre style="background: #f0f0f0">
    <code>
curl -i
     -H "Accept: application/json"
     -X GET
     "https://www.matomari.tk/api/0.4/user/list/anime/RafaelDeJongh?status=2&amp;page=2"
    </code>
  </pre>
</div>
<div class="ui bottom attached tab segment" data-tab="response">
  <pre style="background: #f0f0f0">
    <code>
HTTP/1.1 200 OK
Date: Mon, 11 Dec 2017 15:47:17 GMT
Content-Type: application/json
Connection: keep-alive
Access-Control-Allow-Origin: *
Cache-Control: max-age=3600, public
matomari-Total-Count: 4284
Link: &lt;https://www.matomari.tk/api/0.4/user/list/anime/RafaelDeJongh?status=2&amp;page=1>; rel="first", &lt;https://www.matomari.tk/api/0.4/user/list/anime/RafaelDeJongh?status=2&amp;page=1>; rel="prev", &lt;https://www.matomari.tk/api/0.4/user/list/anime/RafaelDeJongh?status=2&amp;page=3>; rel="next", &lt;https://www.matomari.tk/api/0.4/user/list/anime/RafaelDeJongh?status=2&amp;page=15>; rel="last"

{
  "stats": {
    "watching": 264,
    "completed": 3975,
    "on_hold": 45,
    "dropped": 0,
    "plan_to_watch": 0,
    "total": 4284
  },
  "items": [
    {
      "id": 57,
      "title": "Beck",
      "mal_url": "https:\/\/myanimelist.net\/anime\/57\/Beck",
      "image_url": "https:\/\/myanimelist.cdn-dena.com\/images\/anime\/11\/11636.jpg",
      "other_titles": {
        "synonyms": [
          "BECK",
          "Beck: Mongolian Chop Squad"
        ]
      },
      "type": "TV",
      "episodes": 26,
      "air_status": "finished_airing",
      "air_dates": {
        "from": "2004-07-10",
        "to": "2005-31-03"
      },
      "rating": "R",
      "watch_status": "completed",
      "watched_episodes": 26,
      "watch_score": 6,
      "watch_dates": {
        "from": "2014-04-08",
        "to": "2014-04-08"
      },
      "tags": "",
      "priority": "low",
      "storage": null,
      "storage_amount": null,
      "rewatching": false,
      "last_updated": "2014-08-04T21:27:59+00:00",
      "days_spent_watching": 1
    },
    {
      "id": 59,
      "title": "Chobits",
      "mal_url": "https:\/\/myanimelist.net\/anime\/59\/Chobits",
      "image_url": "https:\/\/myanimelist.cdn-dena.com\/images\/anime\/4\/24648.jpg",
      "other_titles": {
        "synonyms": [
          "Chobits"
        ]
      },
      "type": "TV",
      "episodes": 26,
      "air_status": "finished_airing",
      "air_dates": {
        "from": "2002-03-04",
        "to": "2002-25-09"
      },
      "rating": "PG-13",
      "watch_status": "completed",
      "watched_episodes": 26,
      "watch_score": 9,
      "watch_dates": {
        "from": "2012-27-12",
        "to":"2012-28-12"
      },
      "tags": "",
      "priority": "low",
      "storage": null,
      "storage_amount": null,
      "rewatching": false,
      "last_updated": "2012-12-28T02:31:19+00:00",
      "days_spent_watching": 2
    },
    ...
  ]
}
    </code>
  </pre>
</div>