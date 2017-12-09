anime/stats/:id is a method that allows you to view the stats about a specific anime using the anime ID.<br>
<table class="ui celled green table">
  <thead>
    <tr>
      <th colspan="2">GET anime/stats/:id</th>
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
        <b>200</b> (OK)<br>
        <b>400</b> (Invalid anime ID)<br>
        <b>404</b> (Anime with specified ID doesn't exist, MyAnimeList is offline, or their code changed)<br>
        <b>500</b> (MyAnimeList is returning bad markup, or the total doesn't add up to the amount specified)<br>
        <br>
        See the message section in the error response for more details about an error.
      </td>
    </tr>
  </tbody>
</table>
<h2 class="ui header">Response</h2>
Request to <span class="inline-code">https://www.matomari.tk/api/0.4/anime/stats/21</span>
<pre style="background: #F0F0F0;">
  <code class="code json">
    {
      <span class="property">"summary"</span>: {
        <span class="property">"watching"</span>: <span class="type-int">342533</span>, <span class="type-comment">// Integer of the amount of users who have this anime set as 'Watching'</span>
        <span class="property">"completed"</span>: <span class="type-int">14</span>, <span class="type-comment">// Integer of the amount of users who have this anime set as 'Completed'</span>
        <span class="property">"onhold"</span>: <span class="type-int">79517</span>, <span class="type-comment">// Integer of the amount of users who have this anime set as 'On Hold'</span>
        <span class="property">"dropped"</span>: <span class="type-int">58141</span>, <span class="type-comment">// Integer of the amount of users who have this anime set as 'Dropped'</span>
        <span class="property">"plantowatch"</span>: <span class="type-int">41034</span>, <span class="type-comment">// Integer of the amount of users who have this anime set as 'Plan to Watch'</span>
        <span class="property">"total"</span>: <span class="type-int">521239</span> <span class="type-comment">// Integer of the total amount of users who have this anime in their list</span>
      },
      <span class="property">"score"</span>: [
        {
          <span class="property">"score"</span>: <span class="type-int">1</span>, <span class="type-comment">// Integer of the score</span>
          <span class="property">"percentage"</span>: <span class="type-int">0.7</span>, <span class="type-comment">// Integer of the percentage of the total who has this anime as 1</span>
          <span class="property">"count"</span>: <span class="type-int">2165</span> <span class="type-comment">// Integer of the amount of users with this score</span>
        },
        {
          <span class="property">"score"</span>: <span class="type-int">2</span>,
          <span class="property">"percentage"</span>: <span class="type-int">0.4</span>,
          <span class="property">"count"</span>: <span class="type-int">1256</span>
        },
        ...
      ]
    }
  </code>
</pre>