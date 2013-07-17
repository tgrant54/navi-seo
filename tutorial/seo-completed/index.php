<?php
    
    include 'navi.php';
    include 'simple_html_dom.php';
    
    $crawlerStr = "_escaped_fragment_";
    $indexPage = "index.html";
    $menuHtml = "menu.html";
    $contentId = "#content";
    $menuId = "#navi";
    $menuActiveClass = ".active";
    $basePageTitle = "Navi.js + SEO";
    $footerHtml = "footer.html";
    
    
    // Explode the query string; 
    //  Ex - ?_escaped_fragment_=/home --> ['_escaped_fragment_' , '/home' ]
    
    $qstrArray = explode("=",$_SERVER['QUERY_STRING']);
    
    if ($qstrArray[0] == $crawlerStr) {
        $page = $qstrArray[1];
        $contentPage = "pages".$page.".html";
        $activeMenuLink = "#!".$page;
        
        // get page title
        $titleArray = explode("/",$page);
        $title = $basePageTitle." - ".ucwords($titleArray[1]);
        
        $navi = new Navi($menuHtml, $indexPage, $contentPage, $contentId, $menuActiveClass, $menuId);
        
        $snapShot = $navi->createSnapShot(null,null,null,null,$activeMenuLink,$title);
        
        $newHtml = $navi->injectInDom($snapShot, $navi->createCrawlerMenu($footerHtml, $menuActiveClass, $activeMenuLink), "#footer-links");
        
        $finalHtml = $navi->replaceLinks($snapShot);
        
        echo $finalHtml;
        
    }
    else {
        include "index.html";
    }
    
?>