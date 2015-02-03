<?php

namespace Olliepop\FBPageFeed;

class PageControllerExtension extends \DataExtension
{

    public function getFBPageFeed()
    {
        $fbService = new FBPageFeedService();
        return $fbService->getStoredPosts();
    }

}