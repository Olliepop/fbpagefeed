<?php

namespace Olliepop\FBPageFeed;

class SiteConfigExtension extends \DataExtension
{
    private static $db = array(
        'FBAppID' => 'Varchar(255)',
        'FBAppSecret' => 'Varchar(255)',
        'FBAccessToken' => 'Varchar(255)',
        'FBPageID' => 'Varchar(255)',
    );

    public function updateCMSFields(FieldList $fields) {
        $fields->addFieldToTab("Root.FacebookFeed", new \TextField("FBAppID", "Facebook App ID"));
        $fields->addFieldToTab("Root.FacebookFeed", new \TextField("FBAppSecret", "Facebook App Secret"));
        $fields->addFieldToTab("Root.FacebookFeed", new \TextField("FBAccessToken", "Facebook Access Token"));
        $fields->addFieldToTab("Root.FacebookFeed", new \TextField("FBPageID", "Facebook Page ID"));
    }

}
