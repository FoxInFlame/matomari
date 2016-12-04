# matomari

A couple of MAL related scrapers and stuff like that.

---

You can use matomari live from the base URL of ```http://www.foxinflame.tk/dev/matomari/api```

- ```malappinfo.php``` Just added a Access-Control-Allow-Origin header to the original malappinfo.php
  - The same parameters can be used as the original malappinfo, which are ```u```, ```type``` and ```status```.
  - Used in:
    - [nextMAL](https://myanimelist.net/forum/?topicid=1572798)

- ```animeWallpaper.php``` Grabs the latest wallpapers from reddit /r/animeWallpaper
  - The parameters available are:
    - ```nsfw``` - Can be set to "only", "true", or "false". Only returns only NSFW results, true includes both normal and NSFW, and false returns only normal results.
    - ```sort``` - Can be set to "new" or "hot". Basically self-explanatory.
  - Used in:
    - [wallpaperCycler](https://github.com/FoxInFlame/WallpaperCycler)

- ```userInfo.php``` Grabs detailed user information from a username.
  - The parameters available are:
    - ```username``` - Self-explanatory.
  - Used in:
    - *nothing*
    
- ```animeInfo.php``` Grabs detailed anime information from id.
  - The parameters available are:
    - ```id``` - Self-explanatory
  - Used in:
    - [QuickMyAnimeList](https://myanimelist.net/forum/?topicid=1552137)
