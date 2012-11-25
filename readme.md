Zonerama PHP API
================

Simple PHP class for viewing public albums and photos.


Usage
-----

```php
include "./zonerama.api.php";
$zonerama = new Zonerama;
$zonerama->userName = "[[YOUR ZONERAMA USERNAME]]";
```

Customize
---------

You can change dimensions of thumbnails. Default is 140&times;140

```php
$zonerama->thumbWidth = 140;
$zonerama->thumbHeight = 140;
```

List of public albums
---------------------

```php
$publicAlbums = $zonerama->publicAlbums();
```

```
Array (
  [id] => Array (
    [url]
    [title]
    [date]
    [count]
    [thumb]
  )
)
```

List of public albums
---------------------

```php
$albumPhotos = $zonerama->albumPhotos([[ALBUM ID]]);
```

```
Array (
  [id] => Array (
    [url]
    [title]
    [thumb]
  )
)
```