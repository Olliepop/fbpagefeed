<?php

namespace Olliepop\FBPageFeed;

use Facebook\FacebookSession;
use Facebook\FacebookRequest;
use Facebook\FacebookRequestException;

/**
 * Class FBPageFeedService
 * @package Olliepop\FBPageFeed
 */
class FBPageFeedService
{

    /**
     * @var \Facebook\FacebookSession
     */
    private $fbSession;
    /**
     * @var mixed
     */
    private $appID;
    /**
     * @var mixed
     */
    private $appSecret;
    /**
     * @var mixed|null
     */
    private $pageID;
    /**
     * @var mixed
     */
    private $accessToken;

    /**
     * @param null $pageID The Facebook ID of the page, can be obtained at http://findmyfacebookid.com
     */
    public function __construct($pageID = null)
    {
        $siteConfig = \SiteConfig::current_site_config();

        if (!$pageID) {
            $pageID = $siteConfig->FBPageID;
        }

        $this->pageID = $pageID;
        $this->appID = $siteConfig->FBAppID;
        $this->appSecret = $siteConfig->FBAppSecret;
        $this->accessToken = $siteConfig->FBAccessToken;

        FacebookSession::setDefaultApplication($this->appID, $this->appSecret);
        $this->fbSession = new FacebookSession($this->accessToken);
    }

    /**
     * Get our local copies of the Facebook Page posts
     *
     * @param int $limit
     * @return \DataList|\SS_Limitable
     */
    public function getStoredPosts($limit = 4)
    {
        return FacebookPost::get()->limit($limit);
    }

    /**
     * Store a Facebook Page post into our database
     *
     * @param $fb_id
     * @param $content
     * @param $url
     * @param $timePosted
     * @param null $imageSource
     * @return FacebookPost
     */
    public function storePost($fb_id, $content, $url, $timePosted, $imageSource = null)
    {
        $fbPost = new FacebookPost();
        $fbPost->FBID = $fb_id;
        $fbPost->Content = $content;
        $fbPost->TimePosted = $timePosted;
        $fbPost->URL = $url;
        if ($imageSource) {
            $fbPost->ImageSource = $imageSource;
        }
        $fbPost->write();

        return $fbPost;
    }

    /**
     * Retrieve Facebook Page posts using the Facebook RESTful API
     *
     * @param int $limit
     * @return array|bool
     */
    public function getPostsFromFacebook($limit = 4)
    {
        $posts = array();

        try {
            $request = new FacebookRequest(
                $this->fbSession,
                'GET',
                '/' . $this->pageID . '/feed'
            );
            $response = $request->execute();
            $pagefeed = $response->getResponse();

            foreach ($pagefeed->data as $iteration=>$responseData) {
                if ($iteration==$limit) {
                    break;
                }

                if (isset($responseData->message)) {
                    $posts[$iteration]['Content'] = $responseData->message;
                    $posts[$iteration]['FBID'] = $responseData->object_id;
                    $posts[$iteration]['URL'] = $responseData->link;
                    $posts[$iteration]['TimePosted'] = $responseData->created_time;
                }

                if ($responseData->type == "photo") {
                    if (isset($responseData->object_id)) {
                        $subRequest = new FacebookRequest(
                            $this->fbSession,
                            'GET',
                            '/' . $responseData->object_id . '?fields=images'
                        );
                        $subResponse = $subRequest->execute()->getResponse();

                        // Get the largest image for best quality
                        $images = $subResponse->images;
                        $largestWidth = 0;
                        $largestIndex = 0;
                        // Loop through each supplied image object, remembering the largest
                        foreach ($images as $index=>$image) {
                            if ($image->width > $largestWidth) {
                                $largestIndex = $index;
                                $largestWidth = $image->width;
                            }
                        }
                        // Cherry-pick the source of the largest image asset
                        $posts[$iteration]['source'] = $images{$largestIndex}
                        ->source;
                    }
                }
            }
            return $posts;
        } catch (FacebookRequestException $e) {
            // The Graph API returned an error
            error_log('Olliepop\LGPageFeed SilverStripe Module Exception #1: ' . $e);
        } catch (\Exception $e) {
            // Some other error occurred
            error_log('Olliepop\LGPageFeed SilverStripe Module Exception #2: ' . $e);
        }

        return false;
    }
}
