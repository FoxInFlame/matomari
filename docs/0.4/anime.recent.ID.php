anime/recent/:id is a method that allows you to view the stats about a specific anime using the anime ID.<br>
<table class="ui celled green table">
  <thead>
    <tr>
      <th colspan="2">GET anime/recent/:id</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td class="four wide">Method</td>
      <td class="twelve wide">GET</td>
    </tr>
    <tr>
      <td>Authentication</td>
      <td>Optional</td>
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
            <tr>
              <td><span class="inline-code">filter</span></td>
              <td>friends, all</td>
              <td>It will default to all (public updates), regardless of authentication, however, you can only set it to friends (your friend's updates) if you are authenticated.</td>
            </tr>
          </tbody>
        </table>
      </td>
    </tr>
    <tr>
      <td>Status Codes</td>
      <td>
        <b>200</b> (<a href="responsecodes#all-ok">All OK</a>)<br>
        <b>400</b> (Invalid anime ID format)<br>
        <b>404</b> (Anime with specified ID doesn't exist, or MyAnimeList is offline)<br>
        <b>500</b> (MyAnimeList is returning bad markup)<br>
        <br>
        See the message section in the error response for more details about an error.
      </td>
    </tr>
  </tbody>
</table>
<div class="ui info icon message">
  <i class="announcement icon"></i>
  <div class="content">
    <div class="header">Help Needed</div>
    <p>I don't know if I should just give up on MAL style numbering (status: 1,2,3,4,6, etc). It does seem a little useless because you can check from status_text. Tell me if you have a reason why one of them should not be included.</p>
  </div>
</div>
<h2 class="ui header">Response</h2>
Request to <span class="inline-code">https://www.matomari.tk/api/0.4/anime/recent/21</span> without Authentication
<pre style="background: #F0F0F0;">
  <code class="code json">
    {
      <span class="property">items</span>: [
        {
          <span class="property">username</span>: <span class="type-int">Sparris69</span>, <span class="type-comment">// String of the username.</span>
          <span class="property">image_url</span>: <span class="type-int">null</span>, <span class="type-comment">// String of the profile image URL of the user. null if no profile image.</span>
          <span class="property">score</span>: <span class="type-int">null</span>, <span class="type-comment">// Integer of the score of anime. null if no score is set.</span>
          <span class="property">status</span>: <span class="type-int">1</span>, <span class="type-comment">// Integer of the status of the anime, same as MAL.</span>
          <span class="property">status_text</span>: <span class="type-int">Watching</span> <span class="type-comment">// String of 'status'.</span>
        },
        ...
      ]
    }
  </code>
</pre>