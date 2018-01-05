anime/reviews/:id is a method that allows you to get reviews for a specific anime using the anime ID.<br>
<table class="ui celled green table">
  <thead>
    <tr>
      <th colspan="2">GET anime/reviews/:id</th>
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
              <td>helpful_weighted, helpful, recent<br>(default: "helpful_weighted")</td>
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
        <b>400</b> (<a href="responsecodes#invalid-id-format">Invalid anime ID</a>)<br>
        <b>404</b> (<a href="responsecodes#id-doesnt-exist">Anime with specified ID doesn't exist</a> &middot; <a href="responsecodes#myanimelist-is-offline">MAL is offline</a>)<br>
        <b>429</b> (<a href="responsecodes#too-many-requests">Too Many Requests</a>)<br>
        <b>500</b> (<a href="responsecodes#bad-markup">The code for MAL is not valid HTML markup</a>)<br>
      </td>
    </tr>
  </tbody>
</table>
<div class="ui icon message">
  <i class="file outline icon"></i>
  <div class="content">
    <p>Responses to this method are <a href="cache.html">cached</a> for <b>a day</b>.</p>
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
    "description" => "The review id on MAL"
  ],
  "mal_url" => [
    "type" => "String",
    "description" => "Browser URL for the review on MAL"
  ],
  "target" => [
    "type" => "Object",
    "description" => "The anime the review is directed at",
    "children" => [
      "id" => [
        "type" => "Integer",
        "description" => "The anime id on MAL"
      ],
      "title" => [
        "type" => "String",
        "description" => "The anime title on MAL"
      ]
    ]
  ],
  "episodes_seen" => [
    "type" => "Integer",
    "description" => "Number of episodes the author has watched of the anime"
  ],
  "helpful_count" => [
    "type" => "Integer",
    "description" => "Number of people who marked this review as helpful"
  ],
  "scores" => [
    "type" => "Object",
    "description" => "The scores the author gave to the anime out of 10",
    "children" => [
      "overall" => [
        "type" => "Integer",
        "description" => "The overall score out of 10"
      ],
      "story" => [
        "type" => "Integer",
        "description" => "The story score out of 10"
      ],
      "animation" => [
        "type" => "Integer",
        "description" => "The animation score out of 10"
      ],
      "sound" => [
        "type" => "Integer",
        "description" => "The sound score out of 10"
      ],
      "character" => [
        "type" => "Integer",
        "description" => "The character score out of 10"
      ],
      "enjoyment" => [
        "type" => "Integer",
        "description" => "The enjoyment score out of 10"
      ],
    ]
  ],
  "review" => [
    "type" => "String",
    "description" => "HTML formatted full review"
  ],
  "author" => [
    "type" => "Object",
    "description" => "The user who submitted the review",
    "children" => [
      "username" => [
        "type" => "String",
        "description" => "The username of the user on MAL"
      ],
      "mal_url" => [
        "type" => "String",
        "description" => "Browser URL for the user on MAL"
      ],
      "image_url" => [
        "type" => "String",
        "description" => "Direct URL of the anime cover image on MAL"
      ]
    ]
  ],
  "timestamp" => [
    "type" => "String",
    "description" => "Full ISO 8601 date and time in GMT when the review was posted"
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
     "https://www.matomari.tk/api/0.4/anime/reviews/25835"
    </code>
  </pre>
</div>
<div class="ui bottom attached tab segment" data-tab="response">
  <pre style="background: #f0f0f0">
    <code>
HTTP/1.1 200 OK
Date: Fri, 05 Jan 2018 00:41:53 GMT
Content-Type: application/json
Connection: keep-alive
Access-Control-Allow-Origin: *
Cache-Control: max-age=86400, public

{
  "items": [
    {
      "id": 183786,
      "mal_url": "https:\/\/myanimelist.net\/reviews.php?id=183786",
      "target": {
        "id": 25835,
        "title": "Shirobako"
      },
      "episodes_seen": 24,
      "helpful_count": 373,
      "scores": {
        "overall": 10,
        "story": 8,
        "animation": 9,
        "sound": 0,
        "character": 10,
        "enjoyment": 10
      },
      "review":"One phrase that would perfectly describe Shirobako is simply ingenious. Surprisingly, the series has cleverly put together a lot of elements into one stand-out show. Aside from being an exposition of how anime series are made, it also tells us a cute and charming story all while boasting a splendid cast of characters and vibrant, dynamic designs.&lt;br&gt; &lt;br&gt; The art in Shirobako is lovely. Although vibrant and dynamic, it is never flashy nor exaggerating. It is clean-cut and simple but more than enough to bring the story into fruition and to distinguish one character from the other. The sound is also kept simple, evoking the right feeling at the right moment. Shirobako couldn\u2019t ask for anything more fitting. The theme songs fit perfectly with the story too: inspiring yet also fun and relevant. All in all, Shirobako exercised its liberty pretty well regarding its production.&lt;br&gt; &lt;br&gt; The giant cast of characters actually does not pose a problem for character development and, in general, for the series. During their respective screen times (no matter how little they had), they are well flesh out. The realism they portray is an exceptionally rare feat. They are not archetypal and overblown. All of the characters, especially the five girls, possessed and displayed certain realistic qualities that break free from the confines of typical slice of life anime. The series was careful to not be intimidated by the size of the cast and to handle it with finesse.&lt;br&gt; &lt;br&gt; We follow the lives of five girls as they struggle to live their dreams in the anime industry and an unlikely animation studio fighting against all odds to produce quality anime. It is a tale of of the creative process, professionalism, teamwork, and finding one\u2019s motivation. It is amazing to point out that Shirobako\u2018s core story is incredibly simple yet satisfying. The side stories are also quite enjoyable.&lt;br&gt; &lt;br&gt; But what makes Shirobako stand out is how it is able to masterfully and effortlessly incorporate the core story, multiple side stories, and a brief but informative look into what goes down in the anime industry into one seamless and fluid narrative without ever losing focus. It is never overblown with the unnecessary. All these elements are treated with careful balance \u2013 something not all anime series have \u2013 that underlies the show\u2019s ingenuity.&lt;br&gt; &lt;br&gt; Shirobako is an anime that is \u201cjust right\u201d. It breaks one\u2019s expectations without betraying them. You just have to enjoy it as it is as you learn countless things about life, careers, and, of course, anime. The series is a force to be reckoned with and I could easily recommend it to anyone, especially to those in need of a surprise.",
      "author":{
        "username": "chesudesu",
        "mal_url": "https:\/\/myanimelist.net\/profile\/chesudesu",
        "image_url": "https:\/\/myanimelist.cdn-dena.com\/images\/userimages\/3643923_thumb.jpg"
      },
      "timestamp":"2015-03-28T23:38:00+00:00"
    },
    ...
  ]
}
    </code>
  </pre>
</div>