<?php

/**
 * Extract detail from page: specified game (Not list)
 * 
 */

require_once('_common.php');

$site = 'https://gamedistribution.com';
$arr_page = array(
    "/games/rabbids-volcano-panic", "/games/might-and-magic-armies", "/games/ubisoft-all-star-blast!"
);

ob_implicit_flush(true); // Support sleep()/usleep()
ob_end_flush(); // Support sleep()/usleep()
for ($p = 0; $p < sizeof($arr_page); $p++) {
    $page = $arr_page[$p];
    myParseContent($conn, $site.$page);
    usleep(500);
}
//myParseContent($conn, $site.$arr_page[0]);
$conn->close();

function myParseContent($conn, $page) {
    $xpath = myDOMXPath($page);

    $el_title = $xpath->query('//div[@class="game-title"]//h1');
        $title = $el_title->item(0) ? $el_title->item(0)->textContent : '';
    $s_title = slugify($title);

    $el_link = $xpath->query('//div[@class="input-container input-location"]/a');
        $link = $el_link->item(0) ? $el_link->item(0)->getAttribute('href') : '';

    $el_type = $xpath->query('//span[@class="meta-type"]');
        $type = $el_type->item(0) ? $el_type->item(0)->textContent : '';

    $el_mode = $xpath->query('//div[span/text() = "Mobile Mode"]/span');
        $mode = $el_mode->item(0) ? $el_mode->item(0)->textContent : '';

    $el_cat = $xpath->query('//div[span/text() = "Categories"]/span/a');
        $cat1 = $el_cat->item(0) ? $el_cat->item(0)->textContent : '';
        $cat2 = $el_cat->item(1) ? $el_cat->item(1)->textContent : '';
        $cat3 = $el_cat->item(2) ? $el_cat->item(2)->textContent : '';
        $cat4 = $el_cat->item(3) ? $el_cat->item(3)->textContent : '';
        $cat5 = $el_cat->item(4) ? $el_cat->item(4)->textContent : '';

    $el_tags = $xpath->query('//div[span/text() = "Tags"]/span/div/a');
        $arr_tags=array();
        for ($i=0; $i<$el_tags->length; $i++) {
            array_push($arr_tags, $el_tags->item($i) ? $el_tags->item($i)->textContent : '');
        }
    
    $el_desc = $xpath->query('//div[span/text() = "Description"]/span');
        $desc = $el_desc->item(1) ? $el_desc->item(1)->textContent : '';

    $el_guide = $xpath->query('//div[span/text() = "Instruction"]/span');
        $guide = $el_guide->item(1) ? $el_guide->item(1)->textContent : '';

    $el_thumb = $xpath->query('//div[span/text() = "Thumbnails & Icons"]/div/div/span/img');
        $thumb = $el_thumb->item(0) ? $el_thumb->item(0)->getAttribute("src") : '';
    echo $thumb.'<br />';
    //file_put_contents('../res/thumb/'.$s_title.'.jpeg', file_get_contents($thumb));

    //saveDB($conn, $title, $s_title, $type, $mode, $link, 'https://gamedistribution.com', $cat1, $cat2, $cat3, $cat4, $cat5, implode(',',$arr_tags), $desc, $guide, $s_title.'.jpeg');

}

?>