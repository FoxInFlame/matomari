general/quickSearch/:query is a method to get 10 search results of a query on MAL.<br>
<table class="ui celled green table">
  <thead>
    <tr>
      <th colspan="2">GET general/quickSearch/:query</th>
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
              <td><span class="inline-code">:query</span></td>
              <td>Search query.</td>
              <td>The query will search through all of MyAnimeList's database.</td>
            </tr>
            <tr>
              <td><span class="inline-code">filter</span> [Optional]</td>
              <td>all, anime, manga, person, character, news, featured, forum, club, user<br>(default: all)</td>
              <td>Only one can be used.</td>
            </tr>
          </tbody>
        </table>
      </td>
    </tr>
    <tr>
      <td>Status Codes</td>
      <td>
        <b>200</b> (<a href="responsecodes#all-ok">OK</a>)<br>
        <b>400</b> (<a href="responsecodes#missing-parameter">Paramter q is not supplied</a> &middot; <a href="responsecodes#bad-markup">Bad Markup</a>)<br> <!-- TODO: Change this to Error 500 in Source-->
        <b>404</b> (<a href="responsecodes#myanimelist-is-offline">MAL is offline</a>)<br>
      </td>
    </tr>
  </tbody>
</table>
<!-- <div class="ui icon message">
  <i class="file outline icon"></i>
  <div class="content">
    <p>Responses to this method are <a href="cache.html">cached</a>.</p>
  </div>
</div> -->

</table>
<h2 class="ui header">Response</h2>
Request to <span class="inline-code">https://www.matomari.tk/api/0.4/general/quickSearch/love?filter=anime</span>
<pre style="background: #f0f0f0;">
  <code class="code json">
  <span class="property" data-title="Array" data-content="">"results"</span>: [
    <span class="property" data-title="Array" data-content="List of anime, up to 10 items">"anime"</span>: [
      {
        <span class="property" data-title="Integer" data-content="Id on MAL">"id"</span>: <span class="type-int">149</span>,
        <span class="property" data-title="String" data-content="Type">"type"</span>: <span class="type-string">"anime"</span>,
        <span class="property" data-title="String" data-content="The title on MAL">"title"</span>: <span class="type-string">"Loveless"</span>,
        <span class="property" data-title="String" data-content="Browser link for the anime">"url"</span>: <span class="type-string">"https://myanimelist.net/anime/149/Loveless"</span>,
        <span class="property" data-title="String" data-content="Direct URL of the cover image">"image_url"</span>: <span class="type-string">"https://myanimelist.cdn-dena.com/r/116x180/images/anime/2/22947.jpg?s=ee68a824da82397424e59fec24e35058"</span>,
        <span class="property" data-title="String" data-content="Direct URL of the thumbnail image">"image_thumbnail_url"</span>: <span class="type-string">"https://myanimelist.cdn-dena.com/r/116x76/images/anime/2/22947.jpg?s=a7ac2b1ede906fb8b74e0b42444a7db4"</span>,
        <span class="property" data-title="Object" data-content="Anime Details">"details"</span></span>: {
          <span class="property" data-title="String" data-content="Media type">"media_type"</span>: <span class="type-string">"TV"</span>,
          <span class="property" data-title="String" data-content="Start year">"start_year"</span>: <span class="type-int">2005</span>,
          <span class="property" data-title="String" data-content="Aired date">"aired"</span>: <span class="type-string">"Apr 7, 2005 to Jun 30, 2005"</span>,
          <span class="property" data-title="String" data-content="Score on MAL">"score"</span>: <span class="type-int">7.13</span>,
          <span class="property" data-title="String" data-content="Status on MAL">"status"</span>: <span class="type-string">"Finished Airing"</span>,
        }
        <span class="property" data-title="String" data-content="-">"es_score"</span>: <span class="type-int">4.0266156</span>
      },
      ...
      <!--
  ],
  <span class="property" data-title="Array" data-content="List of manga, up to 10 items">"manga"</span>: [
  {
    <span class="property" data-title="Integer" data-content="Id on MAL">"id"</span>: <span class="type-int">90</span>,
    <span class="property" data-title="String" data-content="Type">"type"</span>: <span class="type-string">"manga"</span>,
    <span class="property" data-title="String" data-content="The title on MAL">"title"</span>: <span class="type-string">"Loveless"</span>,
    <span class="property" data-title="String" data-content="Browser link for the anime">"url"</span>: <span class="type-string">"https://myanimelist.net/manga/90/Loveless"</span>,
    <span class="property" data-title="String" data-content="Direct URL of the cover image">"image_url"</span>: <span class="type-string">"https://myanimelist.cdn-dena.com/r/116x180/images/manga/2/171051.jpg?s=4f2afa1875df423aec36440b5fc8a6e6"</span>,
    <span class="property" data-title="String" data-content="Direct URL of the thumbnail image">"image_thumbnail_url"</span>: <span class="type-string">"https://myanimelist.cdn-dena.com/r/116x76/images/manga/2/171051.jpg?s=91bf289d5020f2c82e2559fc1c235cf0"</span>,
    <span class="property" data-title="Object" data-content="Anime Details">"details"</span></span>:
    {
      <span class="property" data-title="String" data-content="Media type">"media_type"</span>: <span class="type-string">"Manga"</span>,
      <span class="property" data-title="String" data-content="Start year">"start_year"</span>: <span class="type-int">2001</span>,
      <span class="property" data-title="String" data-content="Published date">"published"</span>: <span class="type-string">"Nov 17, 2001 to ?"</span>,
      <span class="property" data-title="String" data-content="Score on MAL">"score"</span>: <span class="type-int">7.87</span>,
      <span class="property" data-title="String" data-content="Status on MAL">"status"</span>: <span class="type-string">"Publishing"</span>,
    }
    <span class="property" data-title="String" data-content="-">"es_score"</span>: <span class="type-int">2.9737427</span>
  },
  ...-->
    ]
  ]
  </code>
</pre>
