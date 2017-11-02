# matomari

A couple of MAL related scrapers and stuff like that, put together in a RESTful API.

Please, always remember that these scrapers are often not 100% stable for production, because when the layout of MAL changes, it won't work anymore. 

---

**I really want a custom-made unique logo just for matomari!**

It should consist of:
- A PNG or JPEG picture with the size of at least 256x256 (with optional transparency).
- Optionally a banner or some sort to put on the GitHub README and on the documentation.

---

You can test matomari Version 0.4 live from the base URL of ```https://www.matomari.tk/api/0.4/src```

Since the redirects in the htaccess files are not active yet, the methods are not available RESTfully. You need to type out the full file name for now. The documentation documents everything assuming it redirects, so do not use it in the current state.

The documentation is being created steadily at https://www.matomari.tk/docs/0.4
You can contribute as well, it's not that hard to make documentations once you know that layout, it's just tedious.

Each method contains information and parameters required at the top of the file.

The planned and completed methods are in requests.txt for now.


---

## Tests

You can run the tests using PHPUnit. Clone this repository, cd to 0.4, then run ```phpunit tests```.
