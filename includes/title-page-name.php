<?php 
$file = basename($_SERVER['SCRIPT_FILENAME'], '.php');
$cwd = basename(getcwd());

switch($cwd) {
	case 'about-us':
        $title_page_name = 'About Us';
        break;
    case 'contact-us':
    	switch($file) {
    		case 'thank-you':
    			$title_page_name = 'Thank You';
    			break;
    		case 'index':
    			$title_page_name = 'Contact Us';
    			break;
    	}
    break;
    case 'register':
    	$title_page_name = 'Register';
    	break;
    case 'missing':
        $title_page_name = 'Error';
        break;
    case 'admin':
    	switch($file) {
    		case 'login':
    			$title_page_name = 'Login';
    			break;
    		case 'new':
    			$title_page_name = 'New Post';
    			break;
    		case 'edit':
    			$title_page_name = 'Edit Post';
    			break;
    		case 'delete':
    			$title_page_name = 'Delete Post';
    			break;
    		case 'index':
    			$title_page_name = 'Blog List';
    			break;
    		case 'about';
    			$title_page_name = 'Edit About Page';
    			break;
    	}
    break;
    case 'blog42.briankallie.com':
    	switch($file) {
    		case 'detail':
    			$title_page_name = 'Detail';
    			break;
    		case 'index':
    			$title_page_name = 'Home';
    			break;
    	}
    break;
    default:
    	$title_page_name = 'Unknown';
    	break;
}