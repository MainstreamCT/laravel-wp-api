<?php namespace MainstreamCT\WordPressAPI\Facades;

use Illuminate\Support\Facades\Facade;
use MainstreamCT\WordPressAPI\WordPressAPI as WpAPI;

class WordPressAPI extends Facade {

    protected static function getFacadeAccessor() { return WpAPI::class; }

}
