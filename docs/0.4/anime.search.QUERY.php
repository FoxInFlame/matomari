anime/search/:query is a method to get anime search results of a query on MAL.<br>
<table class="ui celled green table">
  <thead>
    <tr>
      <th colspan="2">GET anime/search/:query</th>
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
              <td><span class="inline-code">:query</span> [Optional]</td>
              <td>Search query. If unspecified, a filter parameter needs to be specified instead.</td>
              <td>Show anime that's related to the specified query.</td>
            </tr>
            <tr>
              <td><span class="inline-code">page</span> [Optional]</td>
              <td><i>Any natural number.</i><br>(default: "1")</td>
              <td>Page number for the top anime. If the page doesn't exist, it will become 1.</td>
            </tr>
            <tr>
              <td><span class="inline-code">filter</span> [Optional]</td>
              <td>Filters, separated by a comma<br>(default: none)</td>
              <td>Filters will limit the amount of anime and show only what you need.</td>
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
    <p>Responses to this method are <a href="cache.html">cached</a> for <b>a week</b>.</p>
  </div>
</div>
<div class="ui info icon message">
  <i class="announcement icon"></i>
  <div class="content">
    <div class="header">Help Needed</div>
    <p>I couldn't figure out what the <span class="inline-code">o</span> parameter does on the <a href="https://myanimelist.net/anime.php">MAL Search page</a>. I would greatly appreciate it if someone could find out that, so that I can include that if it's neccessary.</p>
  </div>
</div>
<h3 class="ui header">Filters</h3>
Filters allow you to limit the amount of anime that will be returned. They should be in the query parameter, and each filter should be separated by a comma.<br>
The available filters are:
<table class="ui table">
  <thead>
    <tr>
      <th class="collapsing">Filter</th>
      <th>Possible Values</th>
      <th>Description</th>
    </tr>
  </thead>
  <tbody class="top aligned">
    <tr>
      <td><span class="inline-code">type</span></td>
      <td>TV, OVA, Movie, Special, ONA, Music</td>
      <td>Fairly obvious. Limits your search result to the specific type. The last specified one is used when it is declared multiple times in the same query.</td>
    </tr>
    <tr>
      <td><span class="inline-code">score</span></td>
      <td>1 - 10</td>
      <td>Limtis your search result to anime with a community score starting with the specific number. It can be declared multiple times in the same query.</td>
    </tr>
    <tr>
      <td><span class="inline-code">status</span></td>
      <td>finishedairing, currentlyairing, notyetaired</td>
      <td>Limits your search result to anime with the specific status. They're pretty much self-explanatory. The last specified one is used when it is declared multiple times in the same query.</td>
    </tr>
    <tr>
      <td><span class="inline-code">producer</span></td>
      <td><i>TBC</i></td>
      <td><div class="ui info message">
        Too much to handle right now, since I would have to list and sort all the hundreds of producers on MyAnimeList.
      </div></td>
    </tr>
    <tr>
      <td><span class="inline-code">rating</span></td>
      <td>G, PG, PG-13, R, R+, RX</td>
      <td>Obvious here as well. Remember to encode the plus sign if you're going to filter with R+ (+ becomes %2B). The last specified one is used when it is declared multiple times in the same query.</td>
    </tr>
    <tr>
      <td><span class="inline-code">startdate</span></td>
      <td><i>YYYYMMDD format number. Use hyphens to leave a part blank.</i></td>
      <td>Limits your search result to anime with specific starting date, month, year, or a combination. It can be declared multiple times in the same query.</td>
    </tr>
    <tr>
      <td><span class="inline-code">enddate</span></td>
      <td><i>YYYYMMDD format number. Use hyphens to leave a part blank.</i></td>
      <td>Limits your search result to anime with specific ending date, month, year, or a combination. It can be declared multiple times in the same query.</td>
    </tr>
    <tr>
      <td><span class="inline-code">startswithletter</span></td>
      <td><i>Anything in the English alphabet, excluding numbers and special characters.</i></td>
      <td>Limits your search result to anime that has a title starting with a specific letter. The last specified one is used when it is declared multiple times in the same query.</td>
    </tr>
    <tr>
      <td><span class="inline-code">inc-genre</span></td>
      <td><i>Any genre on MAL.</i></td>
      <td>Limits your search result to anime with a specific genre. It can be declared multiple times in the same query.</td>
    </tr>
    <tr>
      <td><span class="inline-code">exc-genre</span></td>
      <td><i>Any genre on MAL</i></td>
      <td>Limits your search result to anime without the specific genre. It can be declared multiple times in the same query.</td>
    </tr>
  </tbody>
  <tfoot class="full-width">
    <tr>
      <th colspan="3">
        <b>Example Filters:</b><br>
        /search/Pokemon?filter=inc-genre:action,score:9<br>
        /search/Naruto?filter=type:tv,rating:pg<br>
        /search/?filter=inc-genre:action
      </th>
    </tr>
  </tfoot>
</table>

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
  "parameter" => [
    "type" => "String",
    "description" => "The URL parameters that was constructed (together with filters) and sent to MAL"
  ],
  "page" => [
    "type" => "Integer",
    "description" => "The page"
  ],
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
        "type" => "Float",
        "description" => "Community score to 2 decimal places"
      ],
      "type" => [
        "type" => "String",
        "description" => "Anime media type"
      ],
      "episodes" => [
        "type" => "Integer",
        "description" => "Total number of episodes; null if unknown"
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
      "rating" => [
        "type" => "String",
        "description" => "The rating on MAL (without the details)"
      ],
      "members_inlist" => [
        "type" => "Integer",
        "description" => "Number of people who have it in their list on MAL"
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
     "https://www.matomari.tk/api/0.4/anime/search/love?filter=type:movie"
    </code>
  </pre>
</div>
<div class="ui bottom attached tab segment" data-tab="response">
  <pre style="background: #f0f0f0">
    <code>
HTTP/1.1 200 OK
Date: Sat, 09 Dec 2017 15:00:42 GMT
Content-Type: application/json
Connection: keep-alive
Access-Control-Allow-Origin: *
Cache-Control: max-age=604800, public

{
  "parameter": "q=love&amp;type=3",
  "page": 1,
  "items": [
    {
      "id": 1006,
      "title": "Tenchi Muyou! in Love",
      "mal_url": "https://myanimelist.net/anime/1006/Tenchi_Muyou_in_Love",
      "image_url": "https://myanimelist.cdn-dena.com/images/anime/11/21054.jpg",
      "score": 7.51,
      "type": "Movie",
      "episodes": 1,
      "air_dates": {
        "from": "1996-04-20",
        "to": "1996-04-20"
      },
      "rating": "PG-13",
      "members_inlist": 15629
    },
    {
      "id": 6535,
      "title": "Ai",
      "mal_url": "https://myanimelist.net/anime/6535/Ai",
      "image_url": "https://myanimelist.cdn-dena.com/images/anime/13/64699.jpg",
      "score": 4.42,
      "type": "Movie",
      "episodes": 1,
      "air_dates": {
        "from": "1963-xx-xx",
        "to": "1963-xx-xx"
      },
      "rating": "PG-13",
      "members_inlist": 2349
    },
    ...
  ]
}
    </code>
  </pre>
</div>