#Navi-SEO
Getting Navi.js to play nice with search engines.

##Overview
This repo and the provided code is a basis to help you with getting sites made with Navi.js crawled and indexed by search 
engines. Navi.php is a php class which will help you create snapshots to return to the search engine. This class is written
(to the best of my knowledge) to follow [Google's ajax crawling scheme](https://developers.google.com/webmasters/ajax-crawling/).
 
The included site has a commented index.php file, so that you can see how I integrated my class to create a snapshot
if the request contained the Google's '_escaped_fragment_' query. 

##Tutorials
* [Navi.js SEO Tutorial](https://github.com/tgrant54/navi-seo/wiki/Tutorial)

##Whats Included?
* <b>navi.php</b> - PHP class that will help with alot of tasks. (The Meat)
* <b>simple_html_dom.php</b> - Dependency for navi.php, http://simplehtmldom.sourceforge.net
* <b>site</b> - The site for Navi.js that is crawlable by Google.
* <b>php</b> - Contains navi.php and simple_html_dom.php
* <b>tutorial</b> - Contains files for use with the tutorial
  - template - blank template that inludes foundation + navi.js and required structure
  - completed - Has all of the markup used in the tutorial
  - seo-completed - All of the markup + result after fully following tutorial

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
* <b>createCrawlerMenu</b> - Takes in the markup for a menu, finds the link in the markup and rewrites "#!" -> "?_escaped_fragment_=", adds a class to the parent element. Returns markup.
 - <h5>Parameters <small> - <b>(menuMarkup, activeClass, activeLink)</b></small></h5>
     + <b>menuMarkup</b> - The file for you menu.
     + <b>activeClass</b> - The class that is added to a link's parent if the href is equal to 'activeLink'.
     + <b>activeLink</b> - What will be compared for each link's href.
 - <b>Ex:</b><code>$myMenu = $navi->createCrawlerMenu("menu.html",".active","#!/home");</code>
 
* <b>createSnapShot</b> - Creates a full HTML snapshot for a particular page. Adds in the crawlable menu, and tries to remove any Navi.js script. 
 - <h5>Parameters <small>- <b>(layout, menuId, contentId, view, menuLink, title, description, keywords)</b></small></h5>
     + <b>layout</b> - The main layout that content will be loaded into (ex: "index.html").
     + <b>menuId</b> - The selector of where in the layout the generated menu will be placed.
     + <b>contentId</b> - The selector of where in the layout the view will be placed inside of.
     + <b>view</b> - The 'partial' page that will get loaded into the layout.
     + <b>menuLink</b> - Same thing as "createCrawlerMenu"'s parameter 'activeLink'.
     + <b>title</b> - The title for the page.
     + <b>description</b> - The description to be used for SEO.
     + <b>keywords</b> - The keywords to be used for SEO.
 - <b>Ex:</b><code>$html = $navi->createSnapShot("index.html","#navi","#content","pages/home.html","Mysite - Home");</code>
 
* <b>createAjax</b> - Returns the html just for the 'partial' page.
 - <h5>Parameters <small> - <b>(view)</b></small></h5>
     + <b>view</b> - See class parameters.
 - <b>Ex:</b><code>$ajaxView = $navi->createAjax("pages/home.html");</code>
 
* <b>injectInDom</b> - Injects markup into a specific selector in some html (replaces innertext).
 - <h5>Parameters <small> - <b>(html, markup, selector)</b></small></h5>
     + <b>html</b> - The html of where to look.
     + <b>markup</b> - The markup you wish to be placed in the selector.
     + <b>selector</b> - The selector of where your markup will be placed in the html.
 - <b>Ex:</b><code>$newHtml = $navi->injectInDom($html, "&lt;h1>Hello, World&lt;/h1>", "#content");</code>
 
* <b>appendInDom</b> - Appends markup to a specific selector in some html (appends to innertext).
 - <h5>Parameters <small> - <b>(html, markup, selector)</b></small></h5>
     + <b>html</b> - The html of where to look.
     + <b>markup</b> - The markup you wish to be appended in the selector.
     + <b>selector</b> - The selector of where your markup will be appended in the html.
 - <b>Ex:</b><code>$charset = $navi->appendInDom($html, "&lt;meta charset='utf-8' />", "head");</code>
 
* <b>metaDescription</b> - Returns the html for description.
 - <h5>Parameters <small> - <b>(content)</b></small></h5>
     + <b>content</b> - Description that will be placed in the returned tag.
 - <b>Ex:</b><code>$description = $navi->metaDescription("This is my home page");</code>
 
* <b>metaKeywords</b> - Returns the html for keywords.
 - <h5>Parameters <small> - <b>(keywords)</b></small></h5>
     + <b>keywords</b> - Comma-separated string of keywords.
 - <b>Ex:</b><code>$keywords = $navi->metaKeywords("Home, page, hello, world");</code>
 
* <b>loadFile</b> - Returns the html of a file.
 - <h5>Parameters <small> - <b>(file)</b></small></h5>
     + <b>file</b> - The file that is to be loaded and returned.
 - <b>Ex:</b><code>$anotherPartial = $navi->loadFile("pages/home/part1.html");</code>
 
* <b>addScript</b> - Returns the markup for a script. If src is null, wrap the code in script tags.
 - <h5>Parameters <small> - <b>(src, code)</b></small></h5>
     + <b>src</b> - The location of the source. Set to null if you want the code wrapped.
     + <b>code</b> - The source of what to wrap the tags around.
 - <b>Ex:</b><code>$jsCode = $navi->addScript(null, "alert('hello world!');");</code>
