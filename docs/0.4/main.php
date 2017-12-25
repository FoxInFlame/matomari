<?php require_once(dirname(__FILE__) . "/../../api/0.4/src/classes/class.cache.php"); ?>
<h1 class="ui header">Hi There!</h1>
This is the documentation for version 0.4 of the matomari API.<br>
<br>
<h2 class="ui header">Lost?</h2>
<div class="ui grid">
  <div class="column sixteen wide mobile sixteen wide tablet sixteen wide computer">
    <div class="ui category search fluid" id="search_api">
      <div class="ui icon input fluid">
        <input class="prompt" type="text" placeholder="Search the documentation...">
        <i class="search icon"></i>
      </div>
      <div class="results"></div>
    </div>
  </div>
</div>
<br>
<div class="ui divider"></div>
<br>
<div class="ui grid">
  <div class="column sixteen wide mobile sixteen wide tablet eight wide computer">
    <h2 class="ui header">API Progress</h2>
    <div class="ui indicating progress" id="progress_overall">
      <div class="bar">
        <div class="progress">...</div>
      </div>
      <div class="label">Loading...</div>
    </div>
    <h2 class="ui header">Docs Progress</h2>
    <div class="ui indicating progress" id="progress_docs">
      <div class="bar">
        <div class="progress">...</div>
      </div>
      <div class="label">Loading...</div>
    </div>
  </div>
  <div class="column sixteen wide mobile sixteen wide tablet eight wide computer">
    <h2 class="ui header">Latest Commits</h2>
    <div class="ui feed">
      <?php

      function time_elapsed_string($datetime, $level = 7) {
        $now = new DateTime;
        $ago = new DateTime($datetime);
        $diff = $now->diff($ago);

        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;

        $string = array(
          'y' => 'year',
          'm' => 'month',
          'w' => 'week',
          'd' => 'day',
          'h' => 'hour',
          'i' => 'minute',
          's' => 'second',
        );
        foreach ($string as $k => &$v) {
          if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
          } else {
            unset($string[$k]);
          }
        }

        $string = array_slice($string, 0, $level);
        return $string ? implode(', ', $string) . ' ago' : 'just now';
      }

      $url = "https://api.github.com/repos/FoxInFlame/matomari/commits?sha=0.4";
      $data = new Data();
      if($data->getCache($url, 3600, ".json")) {
        // Use cache if there is one
        $commits_raw = $data->data;
      } else {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
          'User-Agent: FoxInFlame-matomariAPI'
        ));
        $commits_raw = curl_exec($ch);
        curl_close($ch);
        $data->saveCache($url, $commits_raw, ".json");
      }

      $commits = json_decode($commits_raw, true);

      $output = "";

      foreach($commits as $key => $commit) {
        if($key > 4) break;

        $data = new Data();
        if($data->getCache($commit["url"], 999999999, ".json")) { // Eternity woooo! because commits don't change anyway.
          // Use cache if there is one
          $commitinfo_raw = $data->data;
        } else {
          $commit_ch = curl_init();
          curl_setopt($commit_ch, CURLOPT_URL, $commit["url"]);
          curl_setopt($commit_ch, CURLOPT_RETURNTRANSFER, true);
          curl_setopt($commit_ch, CURLOPT_HTTPHEADER, array(
            'User-Agent: FoxInFlame-matomariAPI'
          ));
          $commitinfo_raw = curl_exec($commit_ch);
          curl_close($commit_ch);
          $data->saveCache($commit["url"], $commitinfo_raw, ".json");
        }

        $commitinfo = json_decode($commitinfo_raw, true);

        $files_changed = count($commitinfo["files"]);

        $output .= "
        <div class=\"event\">
          <div class=\"label\">
            <img src=\"" . $commit["author"]["avatar_url"] . "\">
          </div>
          <div class=\"content\">
            <div class=\"summary\">
              <a href=\"" . $commit["author"]["html_url"] . "\">" . $commit["author"]["login"] . "</a> commited " . $files_changed . " file" . ($files_changed > 1 ? "s" : "") . ".
              <div class=\"date\">
                " . time_elapsed_string($commit["commit"]["author"]["date"], 1) . "
              </div>
            </div>
            <div class=\"extra text\">
              " . $commit["commit"]["message"] . "
            </div>
            <div class=\"meta\">\n";
        foreach($commitinfo["files"] as $file) {
          $output .= "<a href=\"" . $file["blob_url"] . "\"><i class=\"file outline icon\"></i><span class=\"ui green header\">+" . $file["additions"] . "</span> <span class=\"ui red header\">-" . $file["deletions"] . "</span> | " . $file["filename"] . "</a><br>";
        }
        $output .= "
            </div>
          </div>
        </div>
        ";
      }
      echo $output;

      ?>
    </div>
  </div>
</div>

<script src="../js/0.4/index.js"></script>