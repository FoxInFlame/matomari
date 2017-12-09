anime/recommendations is a method that allows you to get the most recent anime recommendations on MAL.<br>
<table class="ui celled green table">
  <thead>
    <tr>
      <th colspan="2">GET anime/recommendations</th>
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
    <p>Responses to this method are <a href="cache.html">cached</a> for <b>an hour</b>.</p>
  </div>
</div>
<h2 class="ui header">Response</h2>
Request to <span class="inline-code">https://www.matomari.tk/api/0.4/anime/recommendations</span>
<pre style="background: #F0F0F0;">
  <code class="code json">
    {
      <span class="property">"items"</span>: [
        {
          <span class="property">"from"</span>: {
            <span class="property" data-title="Integer" data-content="The anime id on MAL">"id"</span>: <span class="type-int">2923</span>,
            <span class="property" data-title="String" data-content="The anime title on MAL">"title"</span>: <span class="type-string">"Shugo Chara!"</span>,
            <span class="property" data-title="String" data-content="Direct URL of the anime cover image">"image"</span>: <span class="type-string">"https://myanimelist.cdn-dena.com/images/anime/7/5061.jpg"</span>
          },
          <span class="property">"to"</span>: {
            <span class="property" data-title="Integer" data-content="The anime id on MAL">"id"</span>: <span class="type-int">342900</span>,
            <span class="property" data-title="String" data-content="The anime title on MAL">"title"</span>: <span class="type-string">"Kirakira☆Precure A La Mode"</span>,
            <span class="property" data-title="String" data-content="Direct URL of the anime cover image">"image"</span>: <span class="type-string">"https://myanimelist.cdn-dena.com/images/anime/12/83504.jpg"</span>
          },
          <span class="property" data-title="String" data-content="Reason for recommending one to another">"reason"</span>: <span class="type-string">"While I watched episodes of kirakira I thought "it looks like Shugo Chara" &lt;br /&gt; Why?&lt;br /&gt; -Mahou Shoujo anime<br /> -A group of persons who are doing your daily activities with magical pets (KPALM - Fairy SC-Guardians Chara)&lt;br /&gt;"</span>,
          <span class="property" data-title="String" data-content="Author of the recommendation">"author"</span>: <span class="type-string">"Sailor_Cherry"</span>,
          <span class="property" data-title="String" data-content="ISO 8601 date">"time"</span>: <span class="type-string">"2017-03-29T20:02:21+00:00"</span>
        },
        ...
      ]
    }
  </code>
</pre>