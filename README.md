# matomari

A couple of MAL related scrapers and stuff like that.

Please, always remember that scrapers are often not 100% stable for production, because when the layout of MAL changes, it won't work anymore. This can be used for small or private projects.

---

You can use matomari live from the base URL of ```http://www.foxinflame.tk/dev/matomari/api```

- ```malappinfo.php``` Just added a Access-Control-Allow-Origin header to the original malappinfo.php
  - The same parameters can be used as the original malappinfo, which are ```u```, ```type``` and ```status```.
  - Used in:
    - [nextMAL](https://myanimelist.net/forum/?topicid=1572798)

- ```animeWallpaper.php``` Grabs the latest wallpapers from reddit /r/animeWallpaper
  - The parameters available are:
    - ```nsfw``` - Can be set to "only", "true", or "false". Only returns only NSFW results, true includes both normal and NSFW, and false returns only normal results. ***[Optional] Defaults to true.***
    - ```sort``` - Can be set to "new" or "hot". Basically self-explanatory. ***[Optional] Defaults to new.***
  - Used in:
    - [wallpaperCycler](https://github.com/FoxInFlame/WallpaperCycler)

- ```userInfo.php``` Grabs detailed user information from a username.
  - The parameters available are:
    - ```username``` - Self-explanatory. ***[Required]***
  - Used in:
    - *nothing so far*
    
- ```animeInfo.php``` Grabs detailed anime information from id.
  - The parameters available are:
    - ```id``` - Self-explanatory. ***[Required]***
  - Used in:
    - [QuickMyAnimeList](https://myanimelist.net/forum/?topicid=1552137)

- ```clubInfo.php``` Grabs detailed club information from id.
  - The parameters available are:
      - ```clubid``` - Self-explanatory. ***[Required]***
  - Used in:
      - *nothing so far*
      
- ```forumTopic.php``` Grabs basic information and content from a forum topic from id. (Extremely Slow, so use with caution)
  - The parameters available are:
    - ```id``` - Topic id. ***[Required]***
    - ```page``` - ***[Optional] Anything over the actual page number will become 1.***
  - Used in:
    - *nothing so far*

- ```userHistory.php``` Grabs the history of a user for the past 3 weeks. Note: It returns GMT on my server, but it probably changes if you run it on a server on the other side of the world. Fiddle around with the timezones if you are going to host it on your own.
  - The parameters available are:
    - ```type``` - Anime or Manga ***[Optional] Defaults to anime***
    - ```username``` - Self-explanatory. ***[Required]***
  - Used in:
    - [My profile page!](http://www.foxinflame.tk)
