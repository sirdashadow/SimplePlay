# SimplePlay
Compress any audio stream (SoundCloud, YouTube, Podcasts, Internet Radio) into an Opus stream at a low bitrate.
Chances are that you have a cellphone with a limited data plan. But you love to listen to Internet Radio, Podcasts, etc. Even let YouTube play a song you like. However this eats up a LOT of mobile data. There was a free service called "yourmuze.fm" that allowed you to organize Internet Radio stations and rebroadcast them using the AAC format at bitrates as low as 12kbps (that's 5.4Megabytes an hour). This service came to an end in earlier 2017 and there have been no other free services available like it. After the demise of the  service, I decided to implement something similar to it. 

SimplePlay supports up to 8 streams (plus 1 private one). Youtube streams (including mobile), Soundcloud and any Internet Radio links that can play in a browser should work with SimplePlay. It uses the free Opus codec and the built-in support in modern browsers (sorry Apple fans, I tried to implement AAC support and turned out to be a hassle...maybe in the future).

It is currently running on a Raspberry Pi 2 OC to 1GHZ (don't worry, temperatures do not get high, and I provide a way to monitor temperature in the browser) with lighttpd, php, vlc and avconv (ffmpeg) installed. This should also work on any LAMP based computer.


# Features

  Opus Codec compression from 96kbps (43MB/hr) all the way down to 6kbps (2.7MB/hr) and still sounds pretty good considering the situation. Default is 32kbps (14.4MB/hr).

  Realtime Display of savings of data when possible. (VLC is not giving me stats but I am working on it)
  
  Stream up to 8 streams to share + 1 "private channel" reserved.
  
  Save your favorite radio stations in the database using a web based management page.
    
  More to come!
  
# Requirements

  Raspberry PI 2 or 3 (You can try this on a Zero, however the processor might be too slow to transcode more than one stream at a time)
  
  Raspbian OS.
  
  PHP 5.x with SqlLite enabled.
  
  VLC
  
  avconv (ffmpeg)
  
  
  
  
  
