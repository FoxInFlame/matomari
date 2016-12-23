# matomari

A couple of MAL related scrapers and stuff like that, put together in a RESTful API.

Please, always remember that these scrapers are often not 100% stable for production, because when the layout of MAL changes, it won't work anymore. This can be used for small or private projects.

---

You can use matomari live from the base URL of ```http://www.foxinflame.tk/dev/matomari/api```

Each file contains information and parameters required at the top of the file.

Available Methods

- ```general/malappinfo.php``` 
- ```general/wallpaper.php``` 
  - Used in:
    - [WallpaperCycler](https://github.com/FoxInFlame/WallpaperCycler)

- ```anime/info/ANIMEID.(json|xml)```
  - Used in:
    - [QuickMyAnimeList](https://myanimelist.net/forum/?topicid=1552137)

- ```club/info/CLUBID.(json|xml)``` 
  - Used in:
    - *nothing so far*

- ```forum/topic/TOPICID.(json|xml)```
  - Used in:
    - *nothing so far*

- ```user/info/USERNAME.(json|xml)```
  - Used in:
    - [AniChrome](https://github.com/FoxInFlame/AniChrome)
- ```user/history/USERNAME.(json|xml)```
  - Used in:
    - [My profile page!](http://www.foxinflame.tk)
- ```user/notifications/USERNAME.(json|xml)```
  - Used in:
    - *nothing so far*
