anime/characters/:id is a method that allows you to view the characters in a specific anime using the anime ID.<br>
<table class="ui celled green table">
  <thead>
    <tr>
      <th colspan="2">GET anime/characters/:id</th>
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
        <b>400</b> (Invalid anime ID format)<br>
        <b>404</b> (Anime with specified ID doesn't exist, or MyAnimeList is offline)<br>
        <b>500</b> (MyAnimeList is returning bad markup)<br>
        <br>
        See the message section in the error response for more details about an error.
      </td>
    </tr>
  </tbody>
</table>
<h2 class="ui header">Response</h2>
Request to <span class="inline-code">https://www.matomari.tk/api/0.4/anime/characters/21</span>
<pre style="background: #F0F0F0;">
  <code class="code json">
    {
      <span class="property">"characters"</span>: [
        {
          <span class="property">"name"</span>: <span class="type-string">"Brook"</span>, <span class="type-comment">// String of the name of the character; not formatted (comma order)</span>
          <span class="property">"id"</span>: <span class="type-int">5627</span>, <span class="type-comment">// Integer of the id of the character</span>
          <span class="property">"image_auto"</span>: <span class="type-string">"https://myanimelist.cdn-dena.com/images/characters/10/161005.jpg"</span>, <span class="type-comment">// String of the full image source, only an assumption. Most of the time correct, but if this returns 404, use image_2x (lower quality) or image_1x (even lower quality) below</span>
          <span class="property">"image_1x"</span>: <span class="type-string">"https://myanimelist.cdn-dena.com/r/23x32/images/characters/10/161005.jpg?s=1d215f696c6ec67c7316ec3547af0735"</span>, <span class="type-comment">// String of the image (23x32px) source URL, or null if no image</span>
          <span class="property">"image_2x"</span>: <span class="type-string">"https://myanimelist.cdn-dena.com/r/46x64/images/characters/10/161005.jpg?s=1d215f696c6ec67c7316ec3547af0735"</span>, <span class="type-comment">// String of the image (46x64px) source URL, or null if no image</span>
          <span class="property">"role"</span>: <span class="type-int">"main"</span>, <span class="type-comment">// String of lowercase role in the anime, or null if no role</span>
          <span class="property">"voice_actors"</span>: { <span class="type-comment">// I know making this an object instead of an array makes looping complicated; it's just easier for me</span>
            <span class="property">"japanese"</span>: [ <span class="type-comment">There can be multiple for the same language, for example id 501</span>
              {
                <span class="property">"name"</span>: <span class="type-string">"Cho"</span>, <span class="type-comment">// String of the name of the voice actor; not formmated (comma order)</span>
                <span class="property">"id"</span>: <span class="type-int">898</span>, <span class="type-comment">// Integer of the id of the voice actor</span>
                <span class="property">"image_auto"</span>: <span class="type-string">"https://myanimelist.cdn-dena.com/voiceactors/3/10479.jpg"</span>, <span class="type-comment">// String of the full image source, only an assumption. Most of the time correct, but if this returns 404, use image_2x (lower quality) or image_1x (even lower quality) below</span>
                <span class="property">"image_1x"</span>: <span class="type-string">"https://myanimelist.cdn-dena.com/r/23x32/images/voiceactors/3/10479.jpg?s=810d728cecf985776ac8c8f87f6d1ce6"</span>, <span class="type-comment">// String of the image (23x32px) source URL, or null if no image</span>
                <span class="property">"image_2x"</span>: <span class="type-string">"https://myanimelist.cdn-dena.com/r/46x64/images/voiceactors/3/10479.jpg?s=810d728cecf985776ac8c8f87f6d1ce6"</span> <span class="type-comment">// String of the image (46x64px) source URL, or null if no image</span>
              }
            ],
            <span class="property">"english"</span>: [
              {
                <span class="property">"name"</span>: <span class="type-string">"Sinclair, Ian"</span>,
                <span class="property">"id"</span>: <span class="type-int">9087</span>,
                
                <span class="property">"image_auto"</span>: <span class="type-string">"https://myanimelist.cdn-dena.com/voiceactors/2/38035.jpg"</span>, <span class="type-comment">// String of the full image source, only an assumption. Most of the time correct, but if this returns 404, use image_2x (lower quality) or image_1x (even lower quality) below</span>
                <span class="property">"image_1x"</span>: <span class="type-string">"https://myanimelist.cdn-dena.com/r/23x32/images/voiceactors/2/38035.jpg?s=6abdb901ed8cc7d0c73a6c20934e577a"</span>, <span class="type-comment">// String of the image (23x32px) source URL, or null if no image</span>
                <span class="property">"image_2x"</span>: <span class="type-string">"https://myanimelist.cdn-dena.com/r/46x64/images/voiceactors/2/38035.jpg?s=6abdb901ed8cc7d0c73a6c20934e577a"</span> <span class="type-comment">// String of the image (46x64px) source URL, or null if no image</span>
              }
            ],
            ...
          }
        },
        {
          <span class="property">"name"</span>: <span class="type-string">"Achino, Salcho"</span>, <span class="type-comment">// See, here's an example of what I mean</span>
          <span class="property">"id"</span>: <span class="type-int">55361</span>,
          <span class="property">"image_auto"</span>: <span class="type-string">"https://myanimelist.cdn-dena.com/r/46x64/images/characters/13/152029.jpg?s=b17f890168f56841d771e4c80141591c"</span>, <span class="type-comment">// String of the full image source, only an assumption. Most of the time correct, but if this returns 404, use image_2x (lower quality) or image_1x (even lower quality) below</span>
          <span class="property">"image_1x"</span>: <span class="type-string"></span>
          <span class="property">"role"</span>: <span class="type-string">"supporting"</span>,
          <span class="property">"voice_actors"</span>: {
            ...
          }
        },
        ...
      ]
    }
  </code>
</pre>