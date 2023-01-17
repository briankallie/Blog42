<!doctype html>
<html lang="zxx">
<head>
    <meta charset="utf-8">
    <title><?php echo $title_page_name; ?> - Blog42</title>
    <meta name="description" content="A blog for Life, the Universe, and EVERYTHING!">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Stylesheets -->
    <link rel="stylesheet" href="/css/normalize.css" media="screen">
    <link rel="stylesheet" href="/css/screen.css" media="screen">

    <?php if(isset($js_head)) echo $js_head; ?>
</head>

<body>
    <div class="container">
        <header class="column full">
            <h1>Blog42 | The <em>EVERYTHING</em> Blog</h1>
        </header>

        <div class="row clearfix">
            <nav class="column full">
                <input type="checkbox" id="hamburger">
                <label class="menuicon" for="hamburger"><span></span></label>
                <ul class="menu">
                    <li><a href="/">Home</a></li>
                    <li><a href="/about-us/">About Us</a></li>
                    <li><a href="/contact-us/">Contact Us</a></li>
                    <li>
                        <form action="https://google.com/search">
                            <input id="nav-search" type="search" name="q" placeholder="Search Blog42&hellip;">
                            <input type="hidden" name="as_sitesearch" value="blog42.briankallie.com">
                        </form>
                    </li>
                </ul>
            </nav>
        </div><!--  /row -->