<?php return array(

    /**
     * Basic site settings
     */
    'site' => [
        'name' => 'Coaster CMS', // used in email subjects & admin meta title, also can be referenced as %site_name% in metas
        'email' => 'info@example.com', // used as email sender/recipient
        'version' => 'v8.0.0', // the coaster version!
        'pages' => '0', // pages limit
        'groups' => '0', // enable/disable site groups
        'secure_folders' => 'secure', // list of secure folders (behind login controller rather than public direct access)
        'storage_path' => 'app/coaster' // used for install state
    ],

    /**
     * Admin settings
     */
    'admin' => [
        'url' => 'admin', // admin url (make sure to cache routes if changing)
        'help_link' => 'https://www.coastercms.org/documentation/user-documentation', // help url in admin
        'view' => coaster_base_path('resources/views/admin'), // path to default admin views
        'public' => '/coaster', // path inside public to store admin assets
        'bootstrap_version' => '3', // for pagination view in admin (supports 3 or 4)
        'tinymce' => '', // can set to compressed
        'title_block' => 'title', // auto populate block with page name
        'default_template' => '1', // the default template id when creating a new page
        'publishing' => '0', // enables/disables publishing
        'advanced_permissions' => '0', // enables/disables per page permissions
        'undo_time' => 3600, // limit for which deleted items can be restored for
    ],

    /**
     * Frontend settings
     */
    'frontend' => [
        'view' => coaster_base_path('resources/views/frontend'), // path to default frontend, non theme specific, views
        'bootstrap_version' => '4', // for rendering pagination (supports 3 or 4)
        'strong_tags' => '0', // add strong tags to meta_keywords on page
        'form_error_class' => 'has-error', // error class used in FormMessage
        'external_form_input' => 'coaster', // prefix for form inputs on external templates
        'language_fallback' => '0', // load default language content if no content for selected lanugage in block
        'theme' => '1', // default theme id
        'language' => '1', // default language id
        'canonicals' => '1', // affects group page urls
        'enabled_feed_extensions' => 'rss,xml,json', // alternate view when .[extension] is appended to url (feed/[extension]/[template])
        'cache' => '0' // cms page cache in minutes (0 = off)
    ],

    /**
     * API keys
     */
    'key' => [
        'bitly' => '', // bilty api, used for shortening beacon urls
        'kontakt' => '', // beacon api
        'estimote_id' => '', // beacon api
        'estimote_key' => '', // beacon api
        'yt_server' => 'AIzaSyDAr_iWux0RaqLwfYsnzHkMUe5bZy_31Eo', // youtube api key
        'yt_browser' => 'AIzaSyCnaqD7R08rOUBq2PUusxASAAOjRgREqBI', // youtube api key
    ],

    /**
     * WordPress blog settings
     */
    'blog' => [
        'url' => '', // relative url ie. /blog/
        'prefix' => 'wp_', // db prefix used to query blog data in $pb->search() method
        'connection' => '', // alternative db connection with the $pb->blogPosts() method
        'username' => '', // db user for alternative connection
        'password' => '' // db user for alternative connection
    ],

    /**
     * Dateime block formats
     */
    'format' => [
        'jq_date' => 'dd/mm/yy', // admin block datetime picker format (not php format)
        'jq_time' => 'h:mm TT', // admin block time picker format (not php format)
        'jq_php' => 'g:i A d/m/Y', // datetime picker format (php format)
        'long' => 'g:i A d/m/Y', // datetime format on group list pages
    ],

    /**
     * Overrides other config files in CmsServiceProvider->register()
     * Set invidual values to null to disable override
     */
    'overrides' => [
        'auth.guards.web' => ['driver' => 'coaster-guard', 'provider' => 'coaster-user'],
        'croppa.src_dir' => public_path(),
        'croppa.crops_dir' => public_path().'/cache',
        'croppa.path' => 'cache/(coaster.*|uploads.*|themes.*)',
        'croppa.url_replace' => ['/^(coaster.*|uploads.*|themes.*)\//' => 'cache/$1/'],
    ],

);
