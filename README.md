# weeklypicphp

[German Version of this README.](README_DE.md)

A simple PHP script to scale a JPG image to the right proportions and set EXIF data for our Weeklypic.de community.

# Features

* Scale picture to 2000px (longest side)
* Setting EXIF data for
  * *title*
  * *description* (= weekliy-pic-username / title)
  * *creator*
  * *license*
* Show table of existing/required/new EXIF data
* Storing the parameters *weekliy-pic-username*, *creator* and *license* in a cookie for convenience, if requested.
* Choose between *weekly* and *monthly* pic
* Preset week and month numbers for filename (but changeable)
* Automatic generation of valid weekly-pic filename
* Direct upload of picture to upload.weeklypic.de

# Roadmap

* Call up a map with the GPS coordinates of the image (if available)
* Check the date, on which the picture was taken, against the week or month.

# Hint

There are not only EXIF tags stored in Pictures, but also IPTC, GPS and other tags.
Whenever we say/write EXIF we generally mean also all the other tags.

So, for example, when the artist should be changed, the following tags are affected:
* EXIF:Artist
* IPTC:by-line
* XMP:creator
