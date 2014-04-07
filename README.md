# tumblr client by @kennydude

This is a tumblr client. It's written in PHP because I have Apache running all the time on my laptop.

I use it all of the time, so it's fairly stable :)

## How to setup

1. Clone this repo onto your computer
2. Create a [Tumblr API key](http://www.tumblr.com/oauth/apps)
3. Get your OAuth details by pressing "Explore API" and copy them by pressing "Show keys" at the top of the page
4. Copy them into a file that looks like this:
       
       <?php
       // my configuration
       $consumerKey = 'my consumer key';
       $consumerSecret = 'my consumer secret';
       $token = 'my token';
       $tokenSecret = 'my token secret';

       define('DEBUG', false);
       define('OFFICIAL_API', true);
   
   and save the file as `config.php`
5. Run `bower install` and `php composer.phar install` to install all of the libraries
6. Open the location on the web server
7. Reblog reblog reblog reblog