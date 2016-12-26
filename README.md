# matomari

A couple of MAL related scrapers and stuff like that, put together in a RESTful API.

Please, always remember that these scrapers are often not 100% stable for production, because when the layout of MAL changes, it won't work anymore. This can be used for small or private projects.

---

You can use matomari live from the base URL of ```http://matomari.tk/api```

View example kind of thing : [![Run in Postman](https://run.pstmn.io/button.svg)](https://app.getpostman.com/run-collection/f9a68f114b10fc4f6ee0)

Each file contains information and parameters required at the top of the file.

Available Methods

General

- ```general/malappinfo.php``` 
- ```general/wallpaper.php``` 
  - Used in:
    - [WallpaperCycler](https://github.com/FoxInFlame/WallpaperCycler)
    
Anime

- ```anime/info/ANIMEID.(json|xml)```
  - Used in:
    - [QuickMyAnimeList](https://myanimelist.net/forum/?topicid=1552137)
- ```anime/search/QUERY.(json|xml)```

Club

- ```club/info/CLUBID.(json|xml)``` 

Forum

- ```forum/topic/TOPICID.(json|xml)```

User

- ```user/info/USERNAME.(json|xml)```
  - Used in:
    - [AniChrome](https://github.com/FoxInFlame/AniChrome)
- ```user/history/USERNAME.(json|xml)```
  - Used in:
    - [My profile page!](http://www.foxinflame.tk)
- ```user/notifications/USERNAME.(json|xml)```
- ```user/messages/USERNAME.(json|xml)```
- ```user/message/send/.(json|xml)```
