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
              <td>Search query. It must be at least 3 letters long if specified. If unspecified, a filter parameter needs to be specified instead.</td>
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
        <b>400</b> (<a href="responsecodes#query-at-least-3-letters">Query must be at least 3 letters long</a>)<br>
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
<h2 class="ui header">Response</h2>
Request to <span class="inline-code">https://www.matomari.tk/api/0.4/anime/search/love?filter=type:movie</span>
<pre style="background: #f0f0f0;">
  <code class="code json">
    {
      <span class="property" data-title="String" data-content="The query string that matomari parsed and sent to MAL; invalid filters don't show up here">"parameter"</span>: <span class="type-string">"q=love&amp;type=3"</span>,
      <span class="property" data-title="Array" data-content="List of anime, up to 50 items per page">"results"</span>: [
        {
          <span class="property" data-title="Integer" data-content="The anime id on MAL">"id"</span>: <span class="type-int">1006</span>,
          <span class="property" data-title="String" data-content="The anime title on MAL">"title"</span>: <span class="type-string">"Tenchi Muyou! in Love"</span>,
          <span class="property" data-title="String" data-content="Direct URL of the anime cover image">"image"</span>: <span class="type-string">"https://myanimelist.cdn-dena.com/images/anime/11/21054.jpg"</span>,
          <span class="property" data-title="String" data-content="Browser link for the anime">"url"</span>: <span class="type-string">"https://myanimelist.net/anime/1006/Tenchi_Muyou_in_Love"</span>,
          <span class="property" data-title="String" data-content="Anime media type">"type"</span>: <span class="type-string">"Movie"</span>,
          <span class="property" data-title="Integer" data-content="Total number of episodes; null if unknown">"episodes"</span>: <span class="type-int">1</span>,
          <span class="property" data-title="Integer" data-content="Community Score to 2 decimal places; null if not yet aired">"score"</span>: <span class="type-int">7.52</span>,
          <span class="property" data-title="String" data-content="Airing start date; hyphens for unknown parts">"startdate"</span>: <span class="type-string">"19960420"</span>,
          <span class="property" data-title="String" data-content="Airing end date; hyphens for unknown parts">"enddate"</span>: <span class="type-string">"199604--"</span>,
          <span class="property" data-title="Integer" data-content="Number of people who have it in their list">"members_count"</span>: <span class="type-int">14468</span>,
          <span class="property" data-title="Integer" data-content="Rating according to the MAL rating system">"rating"</span>: <span class="type-string">"PG-13"</span>,
          <span class="property" data-title="String" data-content="A truncated synopsis of the anime with an ellipsis at the end if it doesn't fit">"synopsis_snippet"</span>: <span class="type-string">"The demonic space criminal Kain has escaped from prison and destroyed the Galaxy Police headquarters. To ensure that the Jurai will not stop him, Kain travels back to 1970 to eliminate Tenchi&#039;s mother..."</span>
        },
        ...
      ]
    }
  </code>
</pre>