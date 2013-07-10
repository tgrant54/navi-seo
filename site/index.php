<?php
    
    // Include simple html dom parser
    include 'simple_html_dom.php';
    include 'navi.php';
    
    // Variables for generateLoadFile Function
    $fileToPages = "pages/";
    $fileExtension = ".html";
    
    
    // General function that returns a string that will be used to point to our 'Partial' pages
    function generateLoadFile($file) {
        global $fileToPages, $fileExtension;
        return $fileToPages.$file.$fileExtension;
    }
    
    
    // This is what google sends to the server when trying to crawl an ajax site;
    /*  Ex
    **  Ajax Url (Pretty Url) -> #!/home
    **  Crawler Url (Ugly Url) -> #!/home --> ?_escaped_fragment_=/home
    */
    
    $crawlerStr = "_escaped_fragment_";
    
    // Explode the query string; 
    //  Ex - ?_escaped_fragment_=/home --> ['_escaped_fragment_' , '/home' ]
    
    $qstrArray = explode("=",$_SERVER['QUERY_STRING']);
    
    
   
    // Main Logic for creating snapshot
    //  Check to see if $qstrArray[0] is google's '_escaped_fragment_'     
    
    if ($qstrArray[0] == $crawlerStr) {
        // Google is trying to crawl this site by using their ajax crawling scheme
        
        /*
        **  We need to provide a HTML SnapShot of our site if we wish to have it indexed properly!  
        */
        
        $queryValue = $qstrArray[1];
        
        // Check to see if $qstrArray[1] (Value of _escaped_fragment_) contains a "/" - Used to equal "/home" -> "home"
        if (stristr($queryValue, "/")) {
            $checkSlash = explode("/", $queryValue); // - If so, explode it
            $queryValue = $checkSlash[1]; // - Set $qstrArray[1] to the value without the leading "/"
        }
        
        
        
        // The 'Partial' File that we will be loading
        $loadPage = generateLoadFile($queryValue); // - Generates a string like ('pages/home.html') if passed 'home';
        
        // This loads my menu.html file that I will be using for navigation, then loads into a string
        
        

        /* Calling Navi
            Params
                1: Your Menu's HTML markup
                2: The main layout (in my case its 'index.html')
                3: The 'Partial' page that needs loaded into your main layout. ('home.html' needs loaded into 'index.html')
                4: The selector for where the 'Partial' page will be loaded into located in the layout 
                5: The class to add/remove from the menu's links.
                6: The selector for where the generated menu will be placed in your layout.
        */
        
        $navi = new Navi("menu.html", "index.html", $loadPage, "#content", ".active", "#navi");
        
        // Create the HTML SnapShot so google will be able to properly crawl your site
        
        
        // Set our page title to be "Navi.js - Pagename"
        $pageTitle = "Navi.js - ".ucwords($queryValue);
        
        
        // Use the Navi class to create the main snapshot's html - Setting some params to null because they were defined when we called Navi
        
        /* Creating Basic SnapShot
        **  Here, I am calling the 'createSnapShot' with 4 null parameters. These are defined by calling Navi.  
        **  The 5th parameter is the active link of our menu. 
        **  The 6th parameter is the page title.
        **  
        **  There are 2 other parameters I have not used. 
        **      1. Description
        **      2. Keywords
        **
        **  If I would have defined these, the proper meta tags will be appended in the head. (SEO)
        */
        
        
        $hashMenuSelector = "#!/".$queryValue; // <-- This is what link should be found in menu.
        
        $nhtml = $navi->createSnapShot(null, null, null, null, $hashMenuSelector, $pageTitle);
        
        // Load up our breadcrumbs.html file that contains markup that I wish to be injected into the snapshot before its echoed.
        
       
        
        // Set this var to the html returned after we inject our breadcrumb menu. 
        $nhtmlInjec = $navi->injectInDom($nhtml, $navi->createCrawlerMenu("breadcrumbs.html", ".active", $hashMenuSelector), "#breadcrumbs");
        
        // Last step for our snapshot
        // We are removing the "script.js" so that Navi.js is not active. We are making a snapshot for a crawler, we do not intend for hashchange navigation.
        
        $nlast = $navi->removeFromDom($nhtmlInjec, "#stoggle");
      
        
        // Echo the html after we have injected our breadcrumbs menu and made it so Navi is not loaded.
        echo $nlast;
    }
    else {
        // The page was not loaded by a crawler load the page. Navi will take over.
        include 'index.html';
    }
    
?>