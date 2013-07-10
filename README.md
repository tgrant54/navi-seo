#Navi-SEO
Getting Navi.js to play nice with search engines.

##Overview
This repo and the provided code is a basis to help you with getting sites made with Navi.js crawled and indexed by search 
engines. Navi.php is a php class which will help you create snapshots to return to the search engine. This class is written
(to the best of my knowledge) to follow [Google's ajax crawling scheme](https://developers.google.com/webmasters/ajax-crawling/).
 
The included site has a commented index.php file, so that you can see how I integrated my class to create a snapshot
if the request contained the Google's '_escaped_fragment_' query. Also this repo will soon contain a small tutorial
that explains this a little more in depth.

##Whats Included?
* <b>navi.php</b> - PHP class that will help with alot of tasks. (The Meat)
* <b>simple_html_dom.php</b> - Dependency for navi.php, http://simplehtmldom.sourceforge.net
* <b>site</b> - The site for Navi.js that is crawlable by Google.
* <b>php</b> - Contains navi.php and simple_html_dom.php

##Navi.php
###Parameters
<b>EX:</b><code>$navi = new Navi("menu.html","index.html","pages/home.html", "#content", ".active", "#navi");</code>
* <b>menuLayout</b> - The file that contains the markup for your menu.
* <b>layout</b> - The main layout that content will be loaded into (ex: "index.html").
* <b>view</b> - The 'partial' page that will get loaded into the layout.
* <b>contentId</b> - The selector of where in the layout the view will be placed inside of.
* <b>menuActiveClass</b> - The class (including '.') of what will be added to a links parent elements.
* <b>menuId</b> - The selector of where in the layout the generated menu will be placed.

###Methods
