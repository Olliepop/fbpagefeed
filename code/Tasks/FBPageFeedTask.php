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
            if(!isset($post['FBID'])) break;

            if($storedPosts{0}->FBID == $post['FBID']) {
                break;
            } else {
                if(isset($post['source'])) {
                    $imageSource = $post['source'];
                } else {
                    $imageSource = null;
                }

                $this->fbService->storePost($post['FBID'], $post['Content'], $post['URL'], $post['source'], $imageSource);
                $inserted++;
            }
        }

        echo 'Stored ' . $inserted . ' new posts.';
    }

}
