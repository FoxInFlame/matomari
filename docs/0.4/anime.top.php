anime/top is a method to get the top anime on MyAnimeList.<br>
<table class="ui celled green table">
  <thead>
    <tr>
      <th colspan="2">GET anime/top</th>
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
              <td><span class="inline-code">sort</span> [Optional]</td>
              <td>all, airing, upcoming, tv, movie, ova, special, bypopularity, byfavorites<br>(default: "all")</td>
              <td>Sort the top anime.</td>
            </tr>
            <tr>
              <td><span class="inline-code">page</span> [Optional]</td>
              <td><i>Any natural number.</i><br>(default: "1")</td>
              <td>Page number for the top anime. If the page doesn't exist, it will become 1.</td>
            </tr>
          </tbody>
        </table>
      </td>
    </tr>
    <tr>
      <td>Status Codes</td>
      <td>
        <b>200</b> (<a href="responsecodes#all-ok">OK</a>)<br>
        <b>404</b> (<a href="responsecodes#myanimelist-is-offline">MAL is offline</a>)<br>
        <b>500</b> (<a href="responsecodes#bad-markup">The code for MAL is not valid HTML markup</a>)<br>
      </td>
    </tr>
  </tbody>
</table>
<div class="ui icon message">
  <i class="file outline icon"></i>
  <div class="content">
    <p>Responses to this method are <a href="cache.html">cached</a>.</p>
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
  "items" => [
    "type" => "Array",
    "description" => "List of anime, 50 items per page",
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
      "score" => [
        "type" => "Integer",
        "description" => "Community score to 2 decimal places"
      ],
      "rank" => [
        "type" => "Integer",
        "description" => "Anime rank in the chosen sort (NOT the overall MAL rank!)"
      ],
      "type" => [
        "type" => "String",
        "description" => "Anime media type"
      ],
      "episodes" => [
        "type" => "Integer",
        "description" => "Total number of episodes; null if unknown"
      ],
      "members_inlist" => [
        "type" => "Integer",
        "description" => "Number of people who have it in their list on MAL (null if sorted by favorites)"
      ],
      "members_favorited" => [
        "type" => "Integer",
        "description" => "Number of people who have it in their favorites on MAL (null unless sorted by favorites)"
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
     "https://www.matomari.tk/api/0.4/anime/top?sort=byfavorites"
    </code>
  </pre>
</div>
<div class="ui bottom attached tab segment" data-tab="response">
  <pre style="background: #f0f0f0">
    <code>
HTTP/1.1 200 OK
Date: Sat, 09 Dec 2017 09:24:14 GMT
Content-Type: application/json
Connection: keep-alive
Access-Control-Allow-Origin: *
Cache-Control: max-age=86400, public

{
  "page": 1,
  "items": [
    {
      "id": 5114,
      "title": "Fullmetal Alchemist: Brotherhood",
      "mal_url": "https:\/\/myanimelist.net\/anime\/5114\/Fullmetal_Alchemist__Brotherhood",
      "image_url": "https:\/\/myanimelist.cdn-dena.com\/images\/anime\/5\/47421.jpg",
      "score": 9.25,
      "rank": 1,
      "type": "TV",
      "episodes": 64,
      "members_inlist": null,
      "members_favorited": 96368
    },
    {
      "id": 9253,
      "title": "Steins;Gate",
      "mal_url": "https:\/\/myanimelist.net\/anime\/9253\/Steins_Gate",
      "image_url": "https:\/\/myanimelist.cdn-dena.com\/images\/anime\/5\/73199.jpg",
      "score": 9.14,
      "rank": 2,
      "type": "TV",
      "episodes": 24,
      "members_inlist": null,
      "members_favorited": 83325
    },
    {
      "id": 1535,
      "title": "Death Note",
      "mal_url": "https:\/\/myanimelist.net\/anime\/1535\/Death_Note",
      "image_url": "https:\/\/myanimelist.cdn-dena.com\/images\/anime\/9\/9453.jpg",
      "score": 8.68,
      "rank": 3,
      "type": "TV",
      "episodes": 37,
      "members_inlist": null,
      "members_favorited": 82819
    },
    ...
  ]
}
    </code>
  </pre>
</div>