general/malappinfo.php is a method that allows you to view the original <b>"malappinfo.php"</b> with the <b>"Access-Control-Allow-Origin"</b> header.
<table class="ui celled green table">
  <thead>
    <tr>
      <th colspan="2">GET general/malappinfo.php</th>
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
              <td><span class="inline-code">u</span></td>
              <td><i>MAL Username</i></td>
              <td>The database name of the user.</td>
            </tr>

            <tr>
              <td><span class="inline-code">type</span>[Optional]</td>
              <td><i>Anime,Manga</i></td>
              <td>Define with list will be displayed. (Anime or Manga)</td>
            </tr>

          </tbody>
        </table>
      </td>
    </tr>
    <tr>
      <td>Status Codes</td>
      <td>
        <b>200</b> (<a href="responsecodes#all-ok">OK</a>)<br>
        <b>404</b> (<a href="responsecodes#id-doesnt-exist"><a href="responsecodes#myanimelist-is-offline">MAL is offline</a>)<br>
      </td>
    </tr>
  </tbody>
</table>
<div class="ui icon message">
  <i class="file outline icon"></i>
  <div class="content">
    <p>Responses to this method are <b>not</b> <a href="cache.html">cached</a>.</p>
  </div>
</div>

<div class="ui info icon message">
  <i class="announcement icon"></i>
  <div class="content">
    <div class="header">Please</div>
    <p>It is highly recommended to use <b><a href="user.list.anime.USERNAME">user/list/anime/:username</a></b> instead of malappinfo.php to browse user's lists, because that is parsed and contains more workable responses, contains more fields, and is generally more stable.</p>
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
  "myanimelist" => [
    "type" => "Root Element",
    "description" => "The data returned from MyAnimeList",
    "children" => [
      "myinfo" => [
        "type" => "Element",
        "description" => "",
        "children" => [
          "user_id" => [
            "type" => "Element",
            "description" => "The user id on MAL"
          ],
          "user_name" => [
            "type" => "Element",
            "description" => "The visible username on MAL"
          ],
          "user_watching" => [
            "type" => "Element",
            "description" => "Amount of anime in the \"watching\" section (includes rewatching)"
          ],
          "user_completed" => [
            "type" => "Element",
            "description" => "Amount of anime in the \"completed\" section"
          ],
          "user_onhold" => [
            "type" => "Element",
            "description" => "Amount of anime in the \"on hold\" section"
          ],
          "user_dropped" => [
            "type" => "Element",
            "description" => "Amount of anime in the \"dropped\" section"
          ],
          "user_plantowatch" => [
            "type" => "Element",
            "description" => "Amount of anime in the \"plan to watch\" section"
          ],
          "user_days_spent_watching" => [
            "type" => "Element",
            "description" => "The total number of episodes watched divided by 60 (<a href=\"https://myanimelist.net/forum/?topicid=702163\">View more</a>)"
          ]
        ]
      ],
      "anime" => [
        "type" => "Element",
        "description" => "Each anime in the response is in its own <anime> tag",
        "children" => [
          "series_animedb_id" => [
            "type" => "Element",
            "description" => "The anime id on MAL"
          ],
          "series_title" => [
            "type" => "Element",
            "description" => "The anime title"
          ],
          "series_synonyms" => [
            "type" => "Element",
            "description" => "Synonymous titles on MAL (with a semi-colon in the beginning for some)"
          ],
          "series_type" => [
            "type" => "Element",
            "description" => "Numerical anime media type"
          ],
          "series_episodes" => [
            "type" => "Element",
            "description" => "Total number of episodes; 0 if unknown"
          ],
          "series_status" => [
            "type" => "Element",
            "description" => "Numerical airing status of the anime"
          ],
          "series_start" => [
            "type" => "Element",
            "description" => "Air start date in YYYY-MM-DD format (with 0 as unknown)"
          ],
          "series_end" => [
            "type" => "Element",
            "description" => "Air end date in YYYY-MM-DD format (with 0 as unknown)"
          ],
          "series_image" => [
            "type" => "Element",
            "description" => "Direct URL of the anime cover image on MAL"
          ],
          "my_id" => [
            "type" => "Element",
            "description" => "0. Provides no real purpose as of 2017.12.24"
          ],
          "my_watched_episodes" => [
            "type" => "Element",
            "description" => "The number of episodes the user has watched"
          ],
          "my_start_date" => [
            "type" => "Element",
            "description" => "Watch start date in YYYY-MM-DD format (with 0 as unknown)"
          ],
          "my_finish_date" => [
            "type" => "Element",
            "description" => "Watch end date in YYYY-MM-DD format (with 0 as unknown)"
          ],
          "my_score" => [
            "type" => "Element",
            "description" => "The score the user gave to the anime"
          ],
          "my_status" => [
            "type" => "Element",
            "description" => "Numerical status in the user's list"
          ],
          "my_rewatching" => [
            "type" => "Element",
            "description" => "If the user is currently rewatching the anime (0 if false, 1 if true)"
          ],
          "my_rewatching_ep" => [
            "type" => "Element",
            "description" => "0. Provides no real purpose as of 2017.12.24"
          ],
          "my_last_updated" => [
            "type" => "Element",
            "description" => "Unix (epoch) time when the user last changed my_status or my_watched_episodes"
          ],
          "my_tags" => [
            "type" => "Element",
            "description" => "Comma separated tags the user set"
          ]
        ]
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
     -H "Accept: application/xml"
     -X GET
     "https://www.matomari.tk/api/0.4/general/malappinfo.php?u=PolyMagic"
    </code>
  </pre>
</div>
<div class="ui bottom attached tab segment" data-tab="response">
  <pre style="background: #f0f0f0">
    <code>
HTTP/1.1 200 OK
Date: Sun, 24 Dec 2017 12:48:41 GMT
Content-Type: application/xml
Connection: keep-alive
Access-Control-Allow-Origin: *
Cache-Control: no-cache, must-revalidate

<?php
// HTML cannot output raw XML/HTML tags (xmp tag is now deprecated in HTML5) so we have to use PHP to replace special characters.
echo htmlspecialchars(<<<EOT
<myanimelist>
  <myinfo>
    <user_id>5230667</user_id>
    <user_name>PolyMagic</user_name>
    <user_watching>13</user_watching>
    <user_completed>114</user_completed>
    <user_onhold>5</user_onhold>
    <user_dropped>13</user_dropped>
    <user_plantowatch>23</user_plantowatch>
    <user_days_spent_watching>27.20</user_days_spent_watching>
  </myinfo>
  <anime>
    <series_animedb_id>19</series_animedb_id>
    <series_title>Monster</series_title>
    <series_synonyms>; Monster</series_synonyms>
    <series_type>1</series_type>
    <series_episodes>74</series_episodes>
    <series_status>2</series_status>
    <series_start>2004-04-07</series_start>
    <series_end>2005-09-28</series_end>
    <series_image>https://myanimelist.cdn-dena.com/images/anime/10/18793.jpg</series_image>
    <my_id>0</my_id>
    <my_watched_episodes>0</my_watched_episodes>
    <my_start_date>0000-00-00</my_start_date>
    <my_finish_date>0000-00-00</my_finish_date>
    <my_score>0</my_score>
    <my_status>6</my_status>
    <my_rewatching>0</my_rewatching>
    <my_rewatching_ep>0</my_rewatching_ep>
    <my_last_updated>1488746787</my_last_updated>
    <my_tags></my_tags>
  </anime>
  ...
</myanimelist>
EOT
); // <<< is heredoc syntax multiline string
?>
    </code>
  </pre>
</div>