<?php

class Notification {
  
  private $id;
  private $type;
  private $type_display;
  private $time;
  private $time_display;
  private $read;
  private $relatedURL;
  private $deleted;
  
  private $anime;
  private $senderUsername;
  private $senderProfileURL;
  private $commentUsername;
  private $commentProfileURL;
  private $commentImageURL;
  private $text;
  private $date; // todo: find a notification with this key
  private $approved;
  private $friendUsername;
  private $friendProfileURL;
  private $friendImageURL;
  private $posterUsername;
  private $posterProfileURL;
  private $topicURL;
  private $topicID;
  private $topicTitle;
  private $message; // todo: find a notification with this key
  private $animes;
  
  public function loadJSON($obj) {
    $this->id = $obj->id;
    $this->time = date_create_from_format("U", $obj->createdAt, new DateTimeZone("Etc/GMT+8"))->setTimeZone(new DateTimeZone("Etc/GMT"))->format("c");
    $this->read = $obj->isRead;
    $this->deleted = $obj->isDeleted;
    $this->relatedURL = $obj->url;
    switch($obj->typeIdentifier) {
      case "user_mention_in_forum_message": // When a user @mentions you in a forum message.
        $this->type = "user_mention_forum";
        $this->type_display = "New mention on forum";
        if($this->deleted) {
          return true;
        }
        $this->senderProfileURL = $obj->senderProfileUrl;
        $this->senderUsername = $obj->senderName;
        $this->topicURL = $obj->pageUrl;
        $this->topicID = explode("topicid=", $obj->pageUrl)[1];
        $this->topicTitle = $obj->pageTitle;
        break;
      case "related_anime_add": // When a new anime that's related to your list is added to the MAL database.
        $this->type = "related_anime";
        $this->type_display = "New related anime";
        if($this->deleted) {
          return true;
        }
        $this->anime = array(
          "title" => $obj->anime->title,
          "type" => $obj->anime->mediaType,
          "id" => explode("/", $obj->url)[4]
        );
        break;
      case "friend_request": // When a user requests to be your friend.
        $this->type = "friend_request";
        $this->type_display = "New friend request";
        if($this->deleted) {
          return true;
        }
        $this->isApproved = $obj->isApproved;
        $this->friendProfileURL = $obj->friendProfileUrl;
        $this->friendUsername = $obj->friendName;
        $this->friendImageURL = $obj->friendImageUrl;
        break;
      case "watched_topic_message": // When a new message is posted on a watched forum topic.
        $this->type = "watched_topic_message";
        $this->type_display = "New watched topic message";
        if($this->deleted) {
          return true;
        }
        $this->posterProfileURL = $obj->postedUserProfileUrl;
        $this->posterUsername = $obj->postedUserName;
        $this->topicURL = $obj->topicUrl;
        $this->topicID = explode("topicid=", $obj->topicUrl)[1];
        $this->topicTitle = $obj->topicTitle;
        break;
      case "profile_comment": // When a user comments on your profile.
        $this->type = "profile_comment";
        $this->type_display = "New profile comment";
        if($this->deleted) {
          return true;
        }
        $this->commentProfileURL = $obj->commentUserProfileUrl;
        $this->commentUsername = $obj->commentUserName;
        $this->commentImageURL = $obj->commentUserImageUrl;
        $this->text = $obj->text;
        break;
      case "club_mass_message_in_forum": // When a user sends out a message to all club members.
        $this->type = "club_mass_message";
        $this->type_display = "New message to all club members";
        if($this->deleted) {
          return true;
        }
        $this->posterProfileURL = $obj->sharedUserProfileUrl;
        $this->posterUsername = $obj->sharedUserName;
        $this->topicURL = $obj->topicUrl;
        $this->topicID = explode("topicid=", $obj->topicUrl)[1];
        $this->topicTitle = $obj->topicTitle;
        $this->clubURL = $obj->clubUrl;
        $this->clubID = explode("cid=", $obj->clubUrl)[1];
        $this->clubTitle = $obj->clubName;
        break;
      case "user_mention_in_club_comment": // When a user @mentions you in a club comment.
        $this->type = "user_mention_club";
        $this->type_display = "New mention in club comment";
        if($this->deleted) {
          return true;
        }
        $this->posterProfileURL = $obj->senderProfileUrl;
        $this->posterUsername = $obj->senderName;
        $this->clubURL = $obj->pageUrl;
        $this->clubID = explode("cid=", $obj->pageUrl)[1];
        $this->clubTitle = $obj->pageTitle;
        break;
      case "on_air": // When an anime in your list starts airing.
        $this->type = "airing_anime";
        $this->type_display = "Anime has started airing";
        if($this->deleted) {
          return true;
        }
        $this->animes = $obj->animes;
        foreach($obj->animes as $obj_anime) {
          $obj_anime->id = explode("/", $obj_anime->url)[4];
        }
        break;
      case "forum_quote": // When a user [quote]s you in a forum message.
        $this->type = "forum_quote";
        $this->type_display = "New quote on forum";
        if($this->deleted) {
          return true;
        }
        $this->senderProfileUrl = $obj->quoteUserProfileUrl;
        $this->senderUsername = $obj->quoteUserName;
        $this->topicURL = $obj->topicUrl;
        $this->topicID = explode("topicid=", $obj->topicUrl)[1];
        $this->topicTitle = $obj->topicTitle;
        break;
      default:
        break;
    }
    return true;
  }
  public function saveJSON() {
    $details = array();
    if(!$this->deleted) {
      switch($this->type) {
        case "user_mention_forum": // When a user @mentions you in a forum message.
          $details = array(
            "related_url" => $this->relatedURL, // link to forum message
            "poster" => array(
               "url" => $this->senderProfileURL,
               "username" => $this->senderUsername
            ),
            "forum_topic" => array(
              "url" => $this->topicURL,
              "id" => $this->topicID,
              "title" => $this->topicTitle
            )
          );
          break;
        case "related_anime": // When a new anime that's related to your list is added to the MAL database.
          $details = array(
            "related_url" => $this->relatedURL, // link to anime info
            "anime" => array(array(
              "url" => $this->relatedURL,
              "title" => $this->anime["title"],
              "type" => $this->anime["type"],
              "id" => $this->anime["id"]
            ))
          );
          break;
        case "friend_request": // New friend request
          $details = array(
            "related_url" => $this->relatedURL, // link to user info
            "isApproved" => $this->isApproved,
            "friend" => array(
              "url" => $this->friendProfileURL,
              "username" => $this->friendUsername,
              "image_url" => $this->friendImageURL
            )
          );
          break;
        case "watched_topic_message": // When a new message is posted on a watched forum topic.
          $details = array(
            "related_url" => $this->relatedURL, // link to forum message
            "poster" => array(
              "url" => $this->posterProfileURL,
              "username" => $this->posterUsername
            ),
            "forum_topic" => array(
              "url" => $this->topicURL,
              "id" => $this->topicID,
              "title" => $this->topicTitle
            )
          );
          break;
        case "profile_comment": // When a user comments on your profile.
          $details = array(
            "related_url" => $this->relatedURL, // link to com2com
            "sender" => array(
              "url" => $this->commentProfileURL,
              "username" => $this->commentUsername,
              "image_url" => $this->commentImageURL
            ),
            "text" => $this->text
          );
          break;
        case "club_mass_message": // When a user sends out a message to all club members.
          $details = array(
            "related_url" => $this->relatedURL, // link to topic
            "poster" => array(
              "url" => $this->posterProfileURL,
              "username" => $this->posterUsername
            ),
            "forum_topic" => array(
              "url" => $this->topicURL,
              "id" => $this->topicID,
              "title" => $this->topicTitle
            ),
            "club" => array(
              "url" => $this->clubURL,
              "id" => $this->clubID,
              "name" => $this->clubTitle
            )
          );
          break;
        case "user_mention_club": // When a user @mentions you in a club comment section.
          $details = array(
            "related_url" => $this->relatedURL, // link to club info
            "poster" => array(
              "url" => $this->posterProfileURL,
              "username" => $this->posterUsername
            ),
            "club" => array(
              "url" => $this->clubURL,
              "id" => $this->clubID,
              "name" => $this->clubTitle
            )
          );
          break;
        case "airing_anime": // When an anime in your list starts airing.
          $details = array(
            "related_url" => $this->relatedURL,
            "anime" => $this->animes
          );
          break;
        case "forum_quote": // When someone quotes you on a forum message using [quote][/quote]
          $details = array(
            "related_url" => $this->relatedURL, // link to forum message
            "poster" => array(
              "url" => $this->senderProfileURL,
              "username" => $this->senderUsername
            ),
            "forum_topic" => array(
              "url" => $this->topicURL,
              "title" => $this->topicTitle
            )
          );
          break;
      }
    }
    return array(
      "id" => $this->id,
      "type" => $this->type,
      "type_display" => $this->type_display,
      "time" => $this->time,
      "read" => $this->read,
      "deleted" => $this->deleted,
      "details" => $details
    );
  }
}
?>