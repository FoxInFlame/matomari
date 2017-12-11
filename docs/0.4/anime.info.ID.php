anime/info/:id is a method that allows you to view the detailed information about a specific anime using the anime ID.<br>
<table class="ui celled green table">
  <thead>
    <tr>
      <th colspan="2">GET anime/info/:id</th>
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
              <td><span class="inline-code">:id</span></td>
              <td><i>Any natural number that's in the MAL database.</i></td>
              <td>The database ID of the anime. This is the ID that is displayed in the URL when you visit the anime page.</td>
            </tr>
          </tbody>
        </table>
      </td>
    </tr>
    <tr>
      <td>Status Codes</td>
      <td>
        <b>200</b> (<a href="responsecodes#all-ok">OK</a>)<br>
        <b>400</b> (<a href="responsecodes#invalid-id-format">Invalid anime ID</a>)<br>
        <b>404</b> (<a href="responsecodes#id-doesnt-exist">Anime with specified ID doesn't exist</a> &middot; <a href="responsecodes#myanimelist-is-offline">MAL is offline</a>)<br>
        <b>429</b> (<a href="responsecodes#too-many-requests">Too Many Requests</a>)<br>
      </td>
    </tr>
  </tbody>
</table>
<div class="ui icon message">
  <i class="file outline icon"></i>
  <div class="content">
    <p>Responses to this method are <a href="cache.html">cached</a> for <b>a week</b>.</p>
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
  "score" => [
    "type" => "Float",
    "description" => "Community score to 2 decimal places"
  ],
  "rank" => [
    "type" => "Integer",
    "description" => "Overall anime rank on MAL"
  ],
  "popularity" => [
    "type" => "Integer",
    "description" => "Anime popularity rank on MAL"
  ],
  "synopsis" => [
    "type" => "String",
    "description" => "HTML formatted full synopsis"
  ],
  "other_titles" => [
    "type" => "Object",
    "description" => "Other titles for the anime on MAL",
    "children" => [
      "english" => [
        "type" => "Array",
        "description" => "Alternative English titles on MAL"
      ],
      "japanese" => [
        "type" => "Array",
        "description" => "Alternative Japanese titles on MAL"
      ],
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
        "description" => "Air start date in YYYY-MM-DD format (with x as unknown)"
      ],
      "to" => [
        "type" => "String",
        "description" => "Air end date in YYYY-MM-DD format (with x as unknown)"
      ]
    ]
  ],
  "season" => [
    "type" => "String",
    "description" => "The season and year of the release (null if movie)"
  ],
  "air_time" => [
    "type" => "String",
    "description" => "Unparsed air time and day as it appears on MAL (null if movie)"
  ],
  "premier_date" => [
    "type" => "String",
    "description" => "The premier date of the anime (null unless movie)"
  ],
  "producers" => [
    "type" => "Array",
    "description" => "Producers for the anime"
  ],
  "licensors" => [
    "type" => "Array",
    "description" => "Licensors for the anime"
  ],
  "studios" => [
    "type" => "Array",
    "description" => "Studios for the anime"
  ],
  "source" => [
    "type" => "String",
    "description" => "The original source for the anime"
  ],
  "genres" => [
    "type" => "Array",
    "description" => "The genres of the anime on MAL"
  ],
  "duration" => [
    "type" => "Object",
    "description" => "The durations for the anime",
    "children" => [
      "total" => [
        "type" => "Integer",
        "description" => "The estimated total length of the anime in minutes"
      ],
      "per_episode" => [
        "type" => "Integer",
        "description" => "The length of one episode of the anime in minutes"
      ]
    ]
  ],
  "rating" => [
    "type" => "String",
    "description" => "Unparsed rating of the anime as it appears on MAL"
  ],
  "members_scored" => [
    "type" => "Integer",
    "description" => "Number of people who set a score"
  ],
  "members_inlist" => [
    "type" => "Integer",
    "description" => "Number of people who have it in their list on MAL (null if sorted by favorites)"
  ],
  "members_favorited" => [
    "type" => "Integer",
    "description" => "Number of people who have it in their favorites on MAL (null unless sorted by favorites)"
  ],
  "background" => [
    "type" => "String",
    "description" => "The background info for the anime on MAL"
  ],
  "related" => [
    "type" => "Array",
    "description" => "Not done."
  ],
  "theme_songs" => [
    "type" => "Array",
    "description" => "Not done."
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
     "https://www.matomari.tk/api/0.4/anime/info/25835"
    </code>
  </pre>
</div>
<div class="ui bottom attached tab segment" data-tab="response">
  <pre style="background: #f0f0f0">
    <code>
HTTP/1.1 200 OK
Date: Sat, 09 Dec 2017 13:58:26 GMT
Content-Type: application/json
Connection: keep-alive
Access-Control-Allow-Origin: *
Cache-Control: max-age=604800, public

{
  "id": 25835,
  "title": "Shirobako",
  "mal_url": "https://myanimelist.net/anime/25835/Shirobako",
  "image_url": "https://myanimelist.cdn-dena.com/images/anime/6/68021.jpg",
  "score": 8.46,
  "rank": 133,
  "popularity": 320,
  "synopsis": "It all started in Kaminoyama High School, when five best friends—Aoi Miyamori, Ema Yasuhara, Midori Imai, Shizuka Sakaki, and Misa Toudou—discovered their collective love for all things anime and formed the animation club. After making their first amateur anime together and showcasing it at the culture festival, the group vow to pursue careers in the industry, aiming to one day work together and create their own mainstream show. &lt;br /&gt; &lt;br /&gt; Two and a half years later, Aoi and Ema have managed to land jobs at the illustrious Musashino Animation production company. The others, however, are finding it difficult to get their dream jobs. Shizuka is feeling the weight of not being recognized as a capable voice actor, Misa has a secure yet unsatisfying career designing 3D models for a car company, and Midori is a university student intent on pursuing her dream as a story writer. These five girls will learn that the path to success is one with many diversions, but dreams can still be achieved through perseverance and a touch of eccentric creativity.&lt;br /&gt; &lt;br /&gt; [Written by MAL Rewrite]",
  "other_titles": {
    "english": [
      "Shirobako"
    ],
    "japanese": [
      "SHIROBAKO"
    ],
    "synonyms": [
      "White Box"
    ]
  },
  "type": "TV",
  "episodes": 24,
  "air_status": "Finished Airing",
  "air_dates": {
    "from": "2014-10-09",
    "to": "2015-03-26"
  },
  "season": "Fall 2014",
  "air_time": "Thursdays at 23:30 (JST)",
  "premier_date": null,
  "producers": [
    "Sotsu",
    "Movic",
    "Warner Bros.",
    "KlockWorx",
    "Showgate",
    "Infinite"
  ],
  "licensors": [
    "Sentai Filmworks"
  ],
  "studios": [
    "P.A. Works"
  ],
  "source": "Original",
  "genres": [
    "Comedy",
    "Drama"
  ],
  "duration": {
    "total": 576,
    "per_episode": 24
  },
  "rating": "PG-13 - Teens 13 or older",
  "members_scored": 67773,
  "members_inlist": 200193,
  "members_favorited": 4321,
  "background": "&lt;i&gt;Shirobako&lt;/i&gt; won the Animation Kobe Television Award in 2015.",
  "related": [ ],
  "theme_songs": [ ]
}
    </code>
  </pre>
</div>