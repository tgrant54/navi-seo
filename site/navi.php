<?php
    /* Class Navi
    **  
    ** Author: Tyler Grant (2013)
    ** Dependencies: Simple HTML Dom Parser (http://simplehtmldom.sourceforge.net/) 
    ** Parameters : (menuLayout, layout, view, contentId, menuActiveClass, menuId) 
    **      menuLayout - The file for your menu.
    **      layout - The main layout that content will be loaded into.
    **      view - The 'partial' page that will get loaded into the layout.
    **      contentId - The selector of where in the layout the view will be placed inside of.
    **      menuActiveClass - The class (including '.') of what will be added to a links parent elements.
    **      menuId - The selector of where in the layout the generated menu will be placed.
    ** Methods : (createMenu, createCrawlerMenu, createSnapShot, createAjax, injectInDom, appendInDom, metaDescription, metaKeywords, addScript)
    **      createMenu - Takes in the markup for a menu, finds the link in the markup, adds a class to the parent element. Returns markup.
    **          Parameters : (menuLayout, activeClass, activeLink)
    **              menuLayout - The file for you menu.
    **              activeClass - The class that is added to a link's parent if the href is equal to 'activeLink'
    **              activeLink - What will be compared for each link's href.
    **      createCrawlerMenu - Same basic function as 'createMenu', except it changes the hrefs include '_escaped_fragment_'.
    **      createSnapShot - Creates a full HTML snapshot for a particular page. Adds in the crawlable menu.
    **          Parameters : (layout, menuId, contentId, view, menuLink, title, description, keywords)
    **              layout - See class parameters.
    **              menuId - See class parameters.
    **              contentId - See class parameters.
    **              view - See class parameters.
    **              menuLink - Same thing as "createMenu"'s parameter 'activeLink'.
    **              title - The title for the webpage.
    **              description - The Description to be used for SEO
    **              keywords - The Keywords to be used for SEO.
    **      createAjax - Returns the html just for the 'partial' page.
    **          Parameters : (view)
    **              view - See class parameters.
    **      injectInDom - Injects markup into a specific selector in some html (replaces innertext)
    **          Parameters : (html, markup, selector)
    **              html - The html of where to look.
    **              markup - The markup you wish to be placed in the selector
    **              selector - The selector of where your markup will be placed in the html
    **      appendInDom - Appends markup to a specific selector in some html (appends to innertext)
    **          Parameters : (html, markup, selector)
    **              html - The html of where to look.
    **              markup - The markup you wish to be appended in the selector
    **              selector - The selector of where your markup will be appended in the html
    **      metaDescription - Returns the html for description
    **          Parameters : (content)
    **              content - Description that will be placed in the returned tag
    **      metaKeywords - Returns the html for keywords
    **          Parameters : (keywords)
    **              keywords - Comma-separated list of keywords
    **      loadFile - Returns the html of a file
    **          Parameters : (file)
    **              file - the file that is to be loaded and returned.
    **      addScript - Returns the markup for a script. If src is null, wrap the code in script tags. 
    **          Parameters : (src, code)
    **              src - The location of the source. Set to null if you want the code wrapped.
    **              code - The source of what to wrap the tags around.
	**		replaceLinks - Finds and changes links that contain "#!" to "?_escaped_fragment_=".
	**			Parameters : (html)
	**				html - Where to find and replace links. 
    */
    class Navi {
        
        public $menuLayout;
        public $menuActiveClass;
        public $menuId;
        public $layout;
        public $view;
        public $contentId;

        
        public function __construct($menuLayout, $layout, $view, $contentId, $menuActiveClass, $menuId) {
            $this->menuLayout = $menuLayout;
            $this->menuActiveClass = $menuActiveClass;
            $this->menuId = $menuId;
            $this->layout = $layout;
            $this->view = $view;
            $this->contentId = $contentId;
        }
        public function createMenu($menuLay, $activeClass, $activeLink) {
            $linkStr = "a[href=".$activeLink."]";
            
            if (!$menuLay) {
                $menuLay = $this->menuLayout;
            }
                        
            $menuHtml = $this->loadFile($menuLay);
            
            //remove old act
            $old = $menuHtml->find($activeClass,0);
            if ($old) {
                $old->class = null;
            }
            // add active class
            $menuLinkActive = $menuHtml->find($linkStr,0);
            $parent = $menuLinkActive->parent();
            $parent->class = "active";
            
            $rtn = $menuHtml;
            return $rtn;
            
        }
        public function createCrawlerMenu($menuLay, $activeClass, $activeLink) {
            $linkStr = "a[href=".$activeLink."]";
            $activeStr = explode(".", $activeClass);
            $classStr = $activeStr[1];
            
            if (!$menuLay) {
                $menuLay = $this->menuLayout;
            }
            $crawlerMenuHtml = $this->loadFile($menuLay);
            
        
            // remove old active
            $oldAct = $crawlerMenuHtml->find($activeClass,0);
            if ($oldAct) {
                $oldAct->class = null;
            }
        
            // Add active class
            $activeStr2 = $crawlerMenuHtml->find($linkStr, 0);
            
            if ($activeStr2) {
                $activeParent = $activeStr2->parent();
                $activeParent->class = $classStr;
                $this->replaceLinks($crawlerMenuHtml);
            }
            else {
                return false;
            }
            
            // Rewrite hrefs to include '?_escaped_fragment_='        
            $this->replaceLinks($crawlerMenuHtml);
            return $crawlerMenuHtml;
        }
        public function createSnapShot($layout, $menuId, $contentId, $view, $menuLink, $title, $description=null, $keywords=null) {
            if (!$layout) {
                $layout = $this->layout;
            }
            if (!$menuId) {
                $menuId = $this->menuId;
            }
            if (!$contentId) {
                $contentId = $this->contentId;
            }
            if (!$view) {
                $view = $this->view;
            }
            
            $html = $this->loadFile($layout);
            
            
            $titleSel = $html->find("title",0);
            $titleSel->innertext = $title;
            
            if ($description) {
                $this->appendInDom($html, $this->metaDescription($description), "head");
            }
            if ($keywords) {
                $this->appendInDom($html, $this->metaKeywords($keywords), "head");
            }
            
            $menuLoc = $html->find($menuId, 0);
            $content = $html->find($contentId, 0);
            
            $menuHtml = $this->createCrawlerMenu(null, $this->menuActiveClass, $menuLink);
            
            if ($menuHtml) {
                $menuLoc->innertext = $menuHtml;
                $content->innertext = $this->createAjax($view);
            }
            else {
                $titleSel->innertext = "404";
                $menuLi = $menuLoc->find("li");
                foreach($menuLi as $li ){
                    $li->class = null;
                }
                $this->replaceLinks($menuLoc);
                $content->innertext = $this->createAjax("404.html");
            }
                    
            
            $this->removeFromDom($html, "script[src*=navi]");
            
            return $html;
        }
        public function replaceLinks($html) {
            $links = $html->find("a");
            foreach($links as $link) {
                $rStr = str_replace("#!", "?_escaped_fragment_=", $link->href);
                $link->href = $rStr;
            }
            return $html;
        }
        public function injectInDom($html, $markup, $selector) {
            $matched = $html->find($selector, 0);
            $matched->innertext = $markup;
            return $html;
        }
        public function appendInDom($html, $markup, $selector) {
            $matched = $html->find($selector,0);
            $appendTo = $matched->innertext;
            $newMarkup = $appendTo.$markup;
            $matched->innertext = $newMarkup;
            
            return $html;
        }
        public function removeFromDom($html, $selector) {
            $matched = $html->find($selector,0);
            $matched->outertext = '';
            
            return $html;
        }
        public function metaDescription($content) {
            $metaStr = "<meta name='description' content='".$content."'>";
            return $metaStr;
        }
        public function metaKeywords($keywords) {
            $metaStr = "<meta name='keywords' content='".$keywords."'>";
            return $metaStr;
        }
        public function addScript($src, $code) {
            $rtnScriptHtml;
            
            if (!$code) {
                $rtnScriptHtml = "<script src='".$src."'></script>";
            }
            else {
                $rtnScriptHtml = "<script type='text/javascript'>".$code."</script>";
            }
            
            return $rtnScriptHtml;
        }
        public function loadFile($file) {
            $html = new simple_html_dom();
            $html->load_file($file);
            
            return $html;
        }
        public function createAjax($view) {
            $ajaxHtml = $this->loadFile($view);
            $this->replaceLinks($ajaxHtml);
            
            return $ajaxHtml;
        }
    }
?>