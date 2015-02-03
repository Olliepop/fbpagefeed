<?php

namespace Olliepop\FBPageFeed;

/**
 * Class FacebookPost
 * @package Olliepop\FBPageFeed
 */
class FacebookPost extends \DataObject
{

    /**
     * @var array
     */
    private static $db = array(
        'FBID' => 'Varchar(100)',
        'Content' => 'Text',
        'ImageSource' => 'Varchar(255)',
        'URL' => 'Varchar(255)',
        'TimePosted' => 'SS_Datetime' // The time it was posted to the Page Timeline
    );

    /**
     * @var string
     */
    private static $default_sort = 'TimePosted DESC';

}