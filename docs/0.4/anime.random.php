anime/random is a method to get a random anime on MyAnimeList.<br>
<table class="ui celled green table">
  <thead>
    <tr>
      <th colspan="2">GET anime/random</th>
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
              <td><span class="inline-code">not</span> [Optional]</td>
              <td><i>Comma separated natural numbers that's in the MAL database</i></td>
              <td>Exclude the specified anime ids from the randomiser.</td>
            </tr>
          </tbody>
        </table>
      </td>
    </tr>
    <tr>
      <td>Status Codes</td>
      <td>
        <b>302</b> (Redirect to anime/info/:id)<br>
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

<h2 class="ui header">Call</h2>
<div class="ui top attached tabular menu example-code">
  <a class="item active" data-tab="request">Example Request</a>
  <a class="item" data-tab="response">Example Response</a>
</div>
<div class="ui bottom attached tab segment" data-tab="request">
  <pre style="background: #f0f0f0">
    <code>
curl -i
     -H "Accept: application/json"
     -X GET
     "https://www.matomari.tk/api/0.4/anime/random"
    </code>
  </pre>
</div>
<div class="ui bottom attached tab segment" data-tab="response">
  <pre style="background: #f0f0f0">
    <code>
HTTP/1.1 302 Found
Date: Sat, 09 Dec 2017 14:46:45 GMT
Content-Type: application/json
Content-Length: 0
Connection: keep-alive
Access-Control-Allow-Origin: *
Cache-Control: no-cache, must-revalidate
Location: /api/0.4/anime/info/18451
    </code>
  </pre>
</div>