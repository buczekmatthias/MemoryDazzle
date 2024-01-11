<?php

namespace App\Services;

class AppServices
{
    public static function getHomepageData(): array
    {
        return array_merge(
            PostServices::getNewPostData(),
            [
                'posts' => PostServices::getHomepageFeed()
            ]
        );
    }
}
