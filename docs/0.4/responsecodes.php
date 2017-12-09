<!--Make responsecodes page easier to read and navigate and maintain -->
<h1 class="ui header">Response Codes</h1>
<div id="code-200" class="ui segment">
  <h2 class="ui header">200</h2>
  <div id="all-ok">
    <h3 class="ui green header">All OK</h3>
    <table class="ui fixed celled table">
      <thead>
        <tr>
          <th>Description</th>
          <th>Displayed When</th>
          <th>Response</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>
            You sent the right request, MyAnimeList responded well, and the server was able to process it.
          </td>
          <td>
            When no other errors were thrown until the very end of the processing.
          </td>
          <td>
            JSON with the content you requested.
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</div>

<div id="code-400" class="ui segment">
  <h2 class="ui header">400</h2>
  <div id="invalid-id-format">
    <h3 class="ui red header">Invalid ID format</h3>
    <table class="ui fixed celled table">
      <thead>
        <tr>
          <th>Description</th>
          <th>Displayed When</th>
          <th>Response</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>
            The request you sent contains an ID that does not fit the criteria (e.g. not numeric) so the server was not able to process it.
          </td>
          <td>
            When the check for numeric ID or the check for empty ID returns false.
          </td>
          <td>
            JSON with 'message' key explaining what's wrong with the ID.
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</div>

<div id="code-404" class="ui segment">
  <h2 class="ui header">404</h2>
  <div id="id-doesnt-exist">
    <h3 class="ui red header">Specified ID doesn't exist</h3>
    <table class="ui fixed celled table">
      <thead>
        <tr>
          <th>Description</th>
          <th>Displayed When</th>
          <th>Response</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>
            You sent the right request, but MyAnimeList could not find an entry with the ID provided so the server was not able to process it.
          </td>
          <td>
            When the cURL to the MyAnimeList request failed with a 404 error.
          </td>
          <td>
            JSON with the 'message' key containing this error.
          </td>
        </tr>
      </tbody>
    </table>
  </div>
  <div class="ui divider"></div>
  <div id="myanimelist-is-offline">
    <h3 class="ui red header">MyAnimeList is offline</h3>
    <table class="ui fixed celled table">
      <thead>
        <tr>
          <th>Description</th>
          <th>Displayed When</th>
          <th>Response</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>
            You sent the right request, but MyAnimeList was not online so the server was not able to process it.
          </td>
          <td>
            When the cURL to the MyAnimeList request failed with a 404 error.
          </td>
          <td>
            JSON with the 'message' key containing this error.
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</div>

<div id="code-429" class="ui segment">
  <h2 class="ui header">429</h2>
  <div id="too-many-requests">
    <h3 class="ui teal header">Too Many Requests</h3>
    <table class="ui fixed celled table">
      <thead>
        <tr>
          <th>Description</th>
          <th>Displayed When</th>
          <th>Response</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>
            You sent the right request but MyAnimeList got stressed out by your quick requests, and the server was not able to process it.
          </td>
          <td>
            When requests to MyAnimeList fail with a response code of 429.
          </td>
          <td>
            JSON with the 'message' key containing this error.
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</div>

<div id="code-500" class="ui segment">
  <h2 class="ui header">500</h2>
  <div id="bad-markup">
    <h3 class="ui purple header">The code for MAL is not valid HTML markup</h3>
    <table class="ui fixed celled table">
      <thead>
        <tr>
          <th>Description</th>
          <th>Displayed When</th>
          <th>Response</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>
            You sent the right request and MyAnimeList responded well, however the HTML markup was not valid and the server was not able to process it.
          </td>
          <td>
            When str_get_html() function with the response content is not an object - it should be an object containing elements if it were a valid markup.
          </td>
          <td>
            JSON with the 'message' key containing this error.
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</div>
<style>
  /* CSS to scroll correctly (fixed header issue) when URL contains an ID to.*/
  div[id^='code-'] > div:before {
    display: block;
    content: "";
    margin-top: -150px;
    height: 150px;
    visibility: hidden;
  }
</style>