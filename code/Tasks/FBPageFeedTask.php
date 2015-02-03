<?php

use Olliepop\FBPageFeed\FBPageFeedService;


//      Run every 10 minutes. Store in crontab:
//      */10 * * * * /var/www/vhosts/nzse.ac.nz/httpdocs/framework/sake FBPageFeed "flush=1"

/*
 * Class FBPageFeedTask
 */
class FBPageFeedTask extends \CliController
{

    /**
     * @var
     */
    private $fbService;

    /**
     * Initiate the service and copy new posts to our database
     */
    public function process()
    {
        $this->fbService = new FBPageFeedService();

        $storedPosts = $this->fbService->getStoredPosts();
        $posts = $this->fbService->getPostsFromFacebook();
        $inserted = 0;
        foreach($posts as $i=>$post) {
            if($storedPosts{0}->FBID == $post['FBID']) {
                break;
            } else {
                if(isset($post['ImageSource'])) {
                    $imageSource = $post['ImageSource'];
                } else {
                    $imageSource = null;
                }

                $this->fbService->storePost($post['FBID'], $post['Content'], $post['URL'], $post['TimePosted'], $imageSource);
                $inserted++;
            }
        }

        echo 'Stored ' . $inserted . ' new posts.';
    }

}
