general/wallpaper is a method that shows anime wallpaper from /r/AnimeWallpaper.<br>
<table class="ui celled green table">
  <thead>
    <tr>
      <th colspan="2">GET general/wallpaper</th>
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
              <td><span class="inline-code">nsfw</span>[Optional]</td>
              <td><i>true,false,only</i></td>
              <td>"true" to include NSFW pictures, "false" to exclude, "only" to return only NSFW.</td>
            </tr>
            <tr>
              <td><span class="inline-code">sort</span>[Optional]</td>
              <td><i>new,hot</i></td>
              <td>"new" to show new pictures, "hot" to show hot pictures.</td>
            </tr>
          </tbody>
        </table>
      </td>
    </tr>
    <tr>
      <td>Status Codes</td>
      <td>
        <b>200</b> (OK)<br>
        <!-- <b>400</b> (Invalid anime ID format)<br>
        <b>404</b> (Anime with specified ID doesn't exist, or MyAnimeList is offline)<br>
        <b>500</b> (MyAnimeList is returning bad markup)<br> -->
        <br>
        See the message section in the error response for more details about an error.
      </td>
    </tr>
  </tbody>
</table>

<h2 class="ui header">Response</h2>
Request to <span class="inline-code">https://www.matomari.tk/api/0.4/general/wallpaper.php?nsfw=false</span>
<pre style="background: #F0F0F0">
    <!-- No Redirects -->
    <img style="width: 100%;" src="https://www.matomari.tk/api/0.4/src/methods/general.wallpaper.php?nsfw=false" alt="">

    <!-- With Redirects -->
    <!--<img style="width: 100%;" src="https://www.matomari.tk/api/0.4/general/wallpaper.php?nsfw=false" alt=""> -->
</pre>
