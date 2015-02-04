# FBPageFeed for SilverStripe

## Introduction

FBPageFeed is a simple module for SilverStripe which provides a simple feed of your latest Facebook Page posts for your SilverStripe site.

## Configuration

There are 4 settings, which can be found in the Settings menu in the CMS under "Facebook Feed". They are

- Facebook App ID
- Facebook App Secret
- Facebook Access Token
- Facebook Page ID

### Obtaining the Access Token & Page ID
 1. Go to the [Graph API Explorer](http://developers.facebook.com/tools/explorer/)
 2. Choose your app from the dropdown menu
 3. Click "Get Access Token"
 4. Choose the `manage_pages` permission
 5. Enter `me/accounts` as the endpoint (after `/vX.X/`) and copy your page's `access_token`

## Installation

`"olliepop/fbpagefeed": "dev-master"`