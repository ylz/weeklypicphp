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
* Show table of existing/required/new EXIF parameters
* Storing the parameters *weekly-pic-username*, *creator* and *license* in a cookie for convenience, if requested
* Choose between *weekly* and *monthly* pic
* Preset week and month numbers for filename (but changeable)
* Automatic generation of valid weekly-pic filename
* Direct upload of picture to upload.weeklypic.de
* Mobile-friendly layout
* Call up a map with the GPS coordinates of the image (if available)

# Roadmap

* Fixing incorrect EXIF data after exporting from Darktable (see "Known Problems")
* Check the date, on which the picture was taken, against the week or month

# Hint

Not only EXIF tags are stored in Pictures, but also IPTC, GPS and other tags.
Whenever we say/write EXIF we generally mean also all the other tags.

So, for example, if you want to change the artist, the following tags are affected:
* EXIF:Artist
* IPTC:by-line
* XMP:creator

# Setup

1. Prerequisites
    * PHP 7.*
    * imagemagick
    * EXIFtool
    * curl
1. Copy this repo to your http folder.
2. Check src/config.php and adapt to your needs.
3. In `_log` directory copy `htaccess` file to `.htaccess`.
3. In `src` directory copy `htaccess` file to `.htaccess`.
5. Create `src/config.config` file and fill it at least with *server* and *login* for upload functionality (see src/config.php for more information).


# Known Problems

* Darktable EXIF data probably wrong.
  * It seems, that data exported from Darktable will result in an `Error = Bad format (0) for IFD0 entry 0` when processed with exiftool. In this case the picture can't be processed and the program stops. I'm working on a solution, that in this and similar cases the EXIF data will be rewritten correctly.
* It may be incorrectly displayed that GPS data is present even though it has been deleted because a GPS version ID is still present. However, geodata has been deleted.
