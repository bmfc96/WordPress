# Documents

## File(s) responsibility|function

*=WordPress

DIR:

    */...

    */wp-includes

    */wp-content

    */wp-admin
     
    (17 files)

NAV-DIR: */... (17 files)
1. **`index.php`**
    - Front to application, LOAD 'wp-blog-header.php'
    - FLOW:
        1. Define constant WP_USE_THEMES as true
        2. LOAD _DIR_ . '/wp-blog-header.php' (require)

2. **`wp-blog-header.php`**
    - Loads WordPress ENVIRONMENT & TEMPLATE
    - FLOW:
        1. Load WordPress library (wp-load.php - require-once - _DIR_)
        2. Setup WordPress query (wp();)
        3. Load THEME template (ABSPATH . WPINC . '/template-loader.php' - require-once)
            Notes:
            - ABSPATH - a constant defined in 'wp-load.php'
            - WPINC - a constant defined in 'wp-load.php' on the event 'wp-config.php' doesn't exist

3. **`wp-load.php`**
    - (Bootstrap / Self-starting process) file for:-
        1. Setting ABSPATH constant
        2. Loading 'wp-config.php' (which load 'wp-settings.php' which will set up the WordPress environment)
            Notes:
            - If 'wp-config.php' is not found, an error will be displayed ASKING THE VISITOR TO SET UP THE 'wp-config.php'
            - Will also search 'wp-config.php' in WordPress' parent directory
    - FLOW:
        1. Define constant ABSPATH as (_DIR_ . '/')
        2. Check function_exists 'error_reporting'
            - TRUE
                1. Initialize 'error_reporting' to a known set of levels
        3. Check if file_exists (ABSPATH . 'wp-config.php')
            - TRUE
                1. require-once ABSPATH . 'wp-config.php'
        4. Else if @file_exists ( dirname(ABSPATH) . '/wp-config.php' ) && ! @file_exists ( dirname(ABSPATH) . '/wp-settings.php' )
            - TRUE
                1. require-once dirname (ABSPATH) . '/wp-config.php'
        5. Else (wp-config.php doesn't exist)
            - TRUE
                1. Define constant WPINC as 'wp-includes'
                2. require-once ABSPATH . WPINC . '/load.php'
                3. Standardize $_SERVER vars. across setups (wp_fix_server_vars();)
                4. require-once ABSPATH . WPINC . '/functions.php'
                5. Check if $_SERVER['REQUEST_URI'] does not contain 'setup-config'
                    - TRUE
                        1. Send header k = 'location' | v = 'path/to/file/setup-config.php'
                        2. EXIT
                6. Define constant WP_CONTENT_DIR as (ABSPATH . 'wp-content')
                7. require-once ABSPATH . WPINC . '/version.php'
                8. Call wp_check_php_mysql_versions();
                9. Call wp_load_translations_early();
                10. Die with error message... (sprintf())
                11. Call wp_die();

4. **`wp-config-sample.php`**
    - Base for 'wp-config.php' which does the following configurations:
        1. Database settings
        2. Secret keys
        3. Database table prefix
        4. ABSPATH
    - FLOW:
        1. =#= Database settings
        2. Define constant DB_NAME as '...'
        3. Define constant DB_USER as '...'
        4. Define constant DB_PASSWORD as '...'
        5. Define constant DB_HOST as 'localhost'
        6. Define constant DB_CHARSET as 'utf8'
        7. Define constant DB_COLLATE as ''
        8. =#= Authentication unique keys and salts
        9. Define constant AUTH_KEY as '...'
        10. Define constant SECURE_AUTH_KEY as '...'
        11. Define constant LOGGED_IN_KEY as '...'
        12. Define constant NONCE_KEY as '...'
        13. Define constant AUTH_SALT as '...'
        14. Define constant SECURE_AUTH_SALT as '...'
        15. Define constant LOGGED_IN_SALT as '...'
        16. Define constant NONCE_SALT as '...'
        17. Set WordPress database table prefix
        18. Define constant WP_DEBUG as TRUE|FALSE - TRUE to enable display of notices during dev
        19. Define constant ABSPATH as (_DIR_ . '/') if not exist
        20. require-once ABSPATH . 'wp-settings.php' - Setup WordPress vars and included files.

5. **`wp-settings.php`**
    - This module does the followings:
        1. Set up and fix common variables
        2. Include WordPress procedural and class library
    - FLOW:
        1. Define constant WPINC as 'wp-includes'
        2. Get version information for current WP release (globals)
        3. require ABSPATH . WPINC . '/version.php'
        4. require ABSPATH . WPINC . '/load.php'
        5. Check required PHP ver. & MySQL ext. or database drop-in (wp_check_php_mysql_versions();)
        6. =#= Include files required for initialization
        7. require ABSPATH . WPINC . [
            '/class-wp-paused-extensions-storage.php',
            '/class-wp-fatal-error-handler.php',
            '/class-wp-recovery-mode-cookie-service.php',
            '/class-wp-recovery-mode-key-service.php',
            '/class-wp-recovery-mode-link-service.php',
            '/class-wp-recovery-mode-email-service.php',
            '/class-wp-recovery-mode.php',
            '/error-protection.php',
            '/default-constants.php',
        ]
        16. require-once ABSPATH . WPINC . '/plugin.php'
        17. $int_blog_id = 1 if not configured (for single site, multi-site default in ms-settings.php)
        18. Set initial def. constants incl. WP_MEMORY_LIMIT, ... ( wp_initial_constants(); )
        19. Register shutdown handler for fatal errors ASAP ( wp_register_fatal_error_handler(); )
        20. WP calculates offsets from UTC ( date_default_timezone_set('UTC') )
        21. Standardize $_SERVER vars. across setups ( wp_fix_server_vars(); )
        22. Check if site in maintenance mode ( wp_maintenance(); )
        23. Start loading timer ( timer_start(); )
        24. Check if WP_DEBUG mode is enabled ( wp_debug_mode(); )
        25. Check whether to load advanced-cache.php drop-in. If WP_CACHE and apply_filters() ret. TRUE and file_exists( WP_CONTENT_DIR . '/advanced-cache.php' )
            - TRUE
                1. include WP_CONTENT_DIR . '/advanced-cache.php'
                2. Re-initialize hooks added manually by advanced-cache.php ( $wp_filter ? $wp_filter = WP_HOOK::build_preinitialized_hooks( $wp_filter ) )
        26. Define WP_LANG_DIR if not set ( wp_set_lang_dir(); )
        27. Load early WP files ...
        28. Include wpdb class and, if present, db.php database drop-in ( require_wp_db(); )
        29. Set database table prefix & format specifiers for database table columns
            - $GLOBALS['table_prefix'] = $table_prefix
            - wp_set_wpdb_vars();
        30. Start WP object cache, or external object cache if drop-in is present ( wp_start_object_cache(); )
        31. Attach default filters ( require ABSPATH . WPINC . '/default-filters.php' )
        32. =#= Init. multisite if enable
        33. If is_multisite()
            - TRUE
                1. require ABSPATH . WPINC . [
                    '/class-wp-site-query.php', '/class-wp-network-query.php',
                    '/ms-blogs.php', '/ms-settings.php'
                ]
        34. Else if ! defined('MULTISITE')
            - TRUE
                1. define ( 'MULTISITE', false )
        35. Register shutdown func. ( register_shutdown_function('shutdown_action_hook'); )
        36. Stop most of WP from being loaded if SHORTINIT is enabled ( SHORTINIT ? return FALSE )
        37. Load L10n library ( require-once ABSPATH . WPINC . [
            '/l10n.php', '/class-wp-textdomain-registry.php',
            '/class-wp-locale.php', '/class-wp-locale-switcher.php'
        ] )
        38. Run installer if WP not installed ( wp_not_installed(); )
        39. Load most of WP ( require ABSPATH . WPINC . [
            '/class-wp-walker.php',
            '/class-wp-ajax-response.php',
            '/capabilities.php',
            '/class-wp-roles.php',
            '/class-wp-role.php',
            '/class-wp-user.php',
            '/class-wp-query.php',
            '/query.php',
            '/class-wp-date-query.php',
            '/theme.php',
            '/class-wp-theme.php',
            '/class-wp-theme-json-schema.php',
            '/class-wp-theme-json-data.php',
            '/class-wp-theme-json.php',
            '/class-wp-theme-json-resolver.php',
            '/global-styles-and-settings.php',
            '/class-wp-block-template.php',
            '/block-template-utils.php',
            '/block-template.php',
            '/theme-templates.php',
            '/template.php',
            '/https-detection.php',
            '/https-migration.php',
            '/class-wp-user-request.php',
            '/user.php',
            '/class-wp-user-query.php',
            '/class-wp-session-tokens.php',
            '/class-wp-user-meta-session-tokens.php',
            '/class-wp-metadata-lazyloader.php',
            '/general-template.php',
            '/link-template.php',
            '/author-template.php',
            '/robots-template.php',
            '/post.php',
            '/class-walker-page.php',
            '/class-walker-page-dropdown.php',
            '/class-wp-post-type.php',
            '/class-wp-post.php',
            '/post-template.php',
            '/revision.php',
            '/post-formats.php',
            '/post-thumbnail-template.php',
            '/category.php',
            '/class-walker-category.php',
            '/class-walker-category-dropdown.php',
            '/category-template.php',
            '/comment.php',
            '/class-wp-comment.php',
            '/class-wp-comment-query.php',
            '/class-walker-comment.php',
            '/comment-template.php',
            '/rewrite.php',
            '/class-wp-rewrite.php',
            '/feed.php',
            '/bookmark.php',
            '/bookmark-template.php',
            '/kses.php',
            '/cron.php',
            '/deprecated.php',
            '/script-loader.php',
            '/taxonomy.php',
            '/class-wp-taxonomy.php',
            '/class-wp-term.php',
            '/class-wp-term-query.php',
            '/class-wp-tax-query.php',
            '/update.php',
            '/canonical.php',
            '/shortcodes.php',
            '/embed.php',
            '/class-wp-embed.php',
            '/class-wp-oembed.php',
            '/class-wp-oembed-controller.php',
            '/media.php',
            '/http.php',
            '/class-wp-http.php',
            '/class-wp-http-streams.php',
            '/class-wp-http-curl.php',
            '/class-wp-http-cookie.php',
            '/class-wp-http-encoding.php',
            '/class-wp-http-response.php',
            '/class-wp-http-requests-response.php',
            '/class-wp-http-requests-hooks.php',
            '/widgets.php',
            '/class-wp-widget.php',
            '/class-wp-widget-factory.php',
            '/nav-menu-template.php',
            '/nav-menu.php',
            '/admin-bar.php',
            '/class-wp-application-passwords.php',
            '/rest-api.php',
            '/rest-api/class-wp-rest-server.php',
            '/rest-api/class-wp-rest-response.php',
            '/rest-api/class-wp-rest-request.php',
            '/rest-api/endpoints/class-wp-rest-controller.php',
            '/rest-api/endpoints/class-wp-rest-posts-controller.php',
            '/rest-api/endpoints/class-wp-rest-attachments-controller.php',
            '/rest-api/endpoints/class-wp-rest-global-styles-controller.php',
            '/rest-api/endpoints/class-wp-rest-post-types-controller.php',
            '/rest-api/endpoints/class-wp-rest-post-statuses-controller.php',
            '/rest-api/endpoints/class-wp-rest-revisions-controller.php',
            '/rest-api/endpoints/class-wp-rest-autosaves-controller.php',
            '/rest-api/endpoints/class-wp-rest-taxonomies-controller.php',
            '/rest-api/endpoints/class-wp-rest-terms-controller.php',
            '/rest-api/endpoints/class-wp-rest-menu-items-controller.php',
            '/rest-api/endpoints/class-wp-rest-menus-controller.php',
            '/rest-api/endpoints/class-wp-rest-menu-locations-controller.php',
            '/rest-api/endpoints/class-wp-rest-users-controller.php',
            '/rest-api/endpoints/class-wp-rest-comments-controller.php',
            '/rest-api/endpoints/class-wp-rest-search-controller.php',
            '/rest-api/endpoints/class-wp-rest-blocks-controller.php',
            '/rest-api/endpoints/class-wp-rest-block-types-controller.php',
            '/rest-api/endpoints/class-wp-rest-block-renderer-controller.php',
            '/rest-api/endpoints/class-wp-rest-settings-controller.php',
            '/rest-api/endpoints/class-wp-rest-themes-controller.php',
            '/rest-api/endpoints/class-wp-rest-plugins-controller.php',
            '/rest-api/endpoints/class-wp-rest-block-directory-controller.php',
            '/rest-api/endpoints/class-wp-rest-edit-site-export-controller.php',
            '/rest-api/endpoints/class-wp-rest-pattern-directory-controller.php',
            '/rest-api/endpoints/class-wp-rest-block-patterns-controller.php',
            '/rest-api/endpoints/class-wp-rest-block-pattern-categories-controller.php',
            '/rest-api/endpoints/class-wp-rest-application-passwords-controller.php',
            '/rest-api/endpoints/class-wp-rest-site-health-controller.php',
            '/rest-api/endpoints/class-wp-rest-sidebars-controller.php',
            '/rest-api/endpoints/class-wp-rest-widget-types-controller.php',
            '/rest-api/endpoints/class-wp-rest-widgets-controller.php',
            '/rest-api/endpoints/class-wp-rest-templates-controller.php',
            '/rest-api/endpoints/class-wp-rest-url-details-controller.php',
            '/rest-api/fields/class-wp-rest-meta-fields.php',
            '/rest-api/fields/class-wp-rest-comment-meta-fields.php',
            '/rest-api/fields/class-wp-rest-post-meta-fields.php',
            '/rest-api/fields/class-wp-rest-term-meta-fields.php',
            '/rest-api/fields/class-wp-rest-user-meta-fields.php',
            '/rest-api/search/class-wp-rest-search-handler.php',
            '/rest-api/search/class-wp-rest-post-search-handler.php',
            '/rest-api/search/class-wp-rest-term-search-handler.php',
            '/rest-api/search/class-wp-rest-post-format-search-handler.php',
            '/sitemaps.php',
            '/sitemaps/class-wp-sitemaps.php',
            '/sitemaps/class-wp-sitemaps-index.php',
            '/sitemaps/class-wp-sitemaps-provider.php',
            '/sitemaps/class-wp-sitemaps-registry.php',
            '/sitemaps/class-wp-sitemaps-renderer.php',
            '/sitemaps/class-wp-sitemaps-stylesheet.php',
            '/sitemaps/providers/class-wp-sitemaps-posts.php',
            '/sitemaps/providers/class-wp-sitemaps-taxonomies.php',
            '/sitemaps/providers/class-wp-sitemaps-users.php',
            '/class-wp-block-editor-context.php',
            '/class-wp-block-type.php',
            '/class-wp-block-pattern-categories-registry.php',
            '/class-wp-block-patterns-registry.php',
            '/class-wp-block-styles-registry.php',
            '/class-wp-block-type-registry.php',
            '/class-wp-block.php',
            '/class-wp-block-list.php',
            '/class-wp-block-parser.php',
            '/blocks.php',
            '/blocks/index.php',
            '/block-editor.php',
            '/block-patterns.php',
            '/class-wp-block-supports.php',
            '/block-supports/utils.php',
            '/block-supports/align.php',
            '/block-supports/border.php',
            '/block-supports/colors.php',
            '/block-supports/custom-classname.php',
            '/block-supports/dimensions.php',
            '/block-supports/duotone.php',
            '/block-supports/elements.php',
            '/block-supports/generated-classname.php',
            '/block-supports/layout.php',
            '/block-supports/spacing.php',
            '/block-supports/typography.php',
            '/style-engine.php',
            '/style-engine/class-wp-style-engine.php',
            '/style-engine/class-wp-style-engine-css-declarations.php',
            '/style-engine/class-wp-style-engine-css-rule.php',
            '/style-engine/class-wp-style-engine-css-rules-store.php',
            '/style-engine/class-wp-style-engine-processor.php'
        ] )
        40. $GLOBALS['wp_embed'] = new WP_Embed();
        41. Instantiate WP_Textdomain_Registry obj to $GLOBALS['wp_textdomain_registry']
        42. If is_multisite() // Load multisite-specific files
            - TRUE
                1. require ABSPATH . WPINC . [
                    '/ms-functions.php',
                    '/ms-default-filters.php',
                    '/ms-deprecated.php'
                ]
        43. Define constants which rely on API to obtain default value AND define must-use plugin directory constants, which can be overridden in sunrise.php drop-in ( wp_plugin_directory_constants(); $GLOBALS['wp_plugin_paths'] = array(); )
        44. Load must-use plugins
                foreach ( wp_get_mu_plugins() as $mu_plugin )
                    $_wp_plugin_file = $mu_plugin
                    include-once $mu_plugin
                    $mu_plugin = $_wp_plugin_file // Avoid stomping of $mu_plugin var in a plugin
                    
                    // Fires once a single must-use plugin has loaded
                    do_action( 'mu_plugin_loaded', $mu_plugin )
                unset( $mu_plugin, $_wp_plugin_file )
        45. Load network activated plugins
            - If is_multisite()
                - TRUE
                    1. foreach ( wp_get_active_network_plugins() as $network_plugin )
                            wp_register_plugin_realpath( $network_plugin )

                            $_wp_plugin_file = $network_plugin
                            include-once $network_plugin
                            $network_plugin = $_wp_plugin_file // Avoid stomping

                            // Fires once a single network-activated plugin has loaded
                            do_action( 'network_plugin_loaded', $network_plugin )
                        unset( $network_plugin, $_wp_plugin_file )
        46. Fires do_action ( 'muplugins_loaded' ) once all must-use and network-activated plugins have loaded
        47. If is_multisite()
            - TRUE
                1. Call ms_cookie_constants()
        48. Define constants after multisite is loaded - ( wp_cookie_constants(); )
        49. Define and enforce our SSL constants - ( wp_ssl_constants(); )
        50. Create common globals - require ABSPATH . WPINC . '/vars.php'
        51. Make taxonomies and posts available to plugins and themes - ( create_initial_taxonomies(); create_initial_post_types(); wp_strat_scraping_edited_file_errors(); )
        52. Register the default theme directory root - ( register_theme_directory( get_theme_root() ) )
        53. If ! is_multisite()
            - TRUE
                1. Handle users requesting a recovery mode link and initiating recovery mode - ( wp_recovery_mode()->initialize(); )
        54. Load active plugins
                foreach ( wp_get_active_and_valid_plugins() as $plugin )
                    wp_register_plugin_realpath( $plugin )

                    $_wp_plugin_file = $plugin
                    include-once $plugin
                    $plugin = $_wp_plugin_file // Avoid stomping
                    
                    // Fires once a single activated plugin has loaded
                    do_action( 'plugin_loaded', $plugin )
                unset( $plugin, $_wp_plugin_file )
        55. Load pluggable functions - ( require ABSPATH . WPINC . [
            '/pluggable.php',
            '/pluggable-deprecated.php'
        ]; )
        56. Set internal encoding - ( wp_set_internal_encoding(); )
        57. Run wp_cache_postload() if object cache [WP_CACHE] is enabled and the function ['wp_cache_postload'] exist
        58. do_action( 'plugins_loaded' ) - once activated plugins have loaded
        59. Define constants which affect functionality if not defined - ( wp_functionality_constants(); )
        60. Add magic quotes and setup $_REQUEST ( $_GET + $_POST ) - ( wp_magic_quotes(); )
        61. do_action( 'sanitize_comment_cookies' ) - Fires when comment cookies are sanitized
        62. Get WP Query object - ( $GLOBALS['wp_the_query'] = new WP_Query(); )
        63. Hold reference to WP Query object - ( $GLOBALS['wp_query'] = $GLOBALS['wp_the_query']; )
        64. Hold WP Rewrite object for creating pretty URLs - ( $GLOBALS['wp_rewrite'] = new WP_Rewrite(); )
        65. Get WP object - ( $GLOBALS['wp'] = new WP(); )
        66. Get WP Widget Factory object - ( $GLOBALS['wp_widget_factory'] = new WP_Widget_Factory(); )
        67. Get WP Roles (User Roles) - ( $GLOBALS['wp_roles'] = new WP_Roles(); )
        68. do_action( 'setup_theme' ) - Fires before the theme is loaded
        69. Define template related constants - ( wp_templating_constants(); )
        70. Load default text localization domain - ( 
            load_default_textdomain()
            $locale = get_locale()
            $locale_file = WP_LANG_DIR . "/$locale.php"
            IF [ 0 === validate_file( $locale ) ] && is_readable( $locale_file )
                TRUE
                    require $locale_file
            unset( $locale_file )
         )
        71. Get WP Locale object [for loading locale domain date and various strings] - ( $GLOBALS['wp_locale'] = new WP_Locale(); )
        72. Get WP Locale Switcher object for switching locales and init - ( $GLOBALS['wp_locale_switcher'] = new WP_Locale_Switcher(); GLOBALS['wp_locale_switcher']->init(); )
        73. Load functions for active theme, both parent and child theme if applicable
                foreach ( wp_get_active_and_valid_themes() as $theme )
                    file_exists( $theme . '/functions.php' ) ? include $theme . '/functions.php'
                unset( $theme )
        74. do_action( 'after_setup_theme' ) - Fires after theme loaded
        75. Create instance of WP_Site_Health so that Cron events may fire - (
                if ! class_exists( 'WP_Site_Health' ) ? require-once ABSPATH . 'wp-admin/includes/class-wp-site-health.php'
                WP_Site_Health::get_instance()
            )
        76. Set up current user - ( $GLOBALS['wp']->init(); )
        77. do_action( 'init' ) - Fires after WP finished loading before any headers are sent
            Most of WP is loaded at this stage, user is authenticated.
            WP continues to load 'init' hook,  and many plugins instantiate
            themselves on it for all sorts of reasons
            To plug an action once WP is loaded, use 'wp_loaded' hook
        78. Check site status
            if is_multisite()
                TRUE
                    $file = ms_site_check()
                    if true !== $file
                        require $file
                        die()
                    unset( $file )
        79. do_action( 'wp_loaded' ) - Fired once WP, all plugins, and theme are fully loaded
            and instantiated.
            AJAX requests should use wp-admin/admin-ajax.php
            admin-ajax.php can handle requests for users not logged in
END-NAV-DIR: */...

6. **`wp-includes/versions.php`**
    - This module is responsible on:
        1. Contains version information of current WP release
        2. Hold current version number for WP core - (used to bust caches and enable dev. mode for scripts when running from /src dir.) [ $wp_version = '6.2-alpha-54952' ]
        3. Hold WP DB revision, increments when changes made to WP DB schema - ( $wp_db_version = 53496; )
        4. Hold TinyMCE version - ( $tinymce_version = '49110-20201110'; )
        5. Hold required PHP version - ( $required_php_version = '5.6.20'; )
        6. Hold required MySQL version - ( $required_mysql_version = '5.0'; )

7. **`wp-includes/load.php`**
    - This module are needed to load WP
    - FUNCTIONS:
        1. **`wp_get_server_protocol()`**: $protocol - Return HTTP protocol sent by server
        2. **`wp_fix_server_vars()`**: void - Fix `$_SERVER` vars. for various setups
        3. **`wp_populate_basic_auth_from_authorization_header()`**: void - Populates Basic Auth server details from Authorization header
            Some server running in CGI or FastCGI mode don't pass Authorization header on to WP
            If it's been rewritten to `HTTP_AUTHORIZATION` header,
            fill in proper $_SERVER vars. instead
        4. **`wp_check_php_mysql_versions()`** - Check for required PHP version and MySQL extension or database drop-in, dies if requirement not met
        5. **`wp_get_environment_type()`**: str - Get current environment type (return string current env. type)
            Type can be set via `WP_ENVIRONMENT_TYPE` global system variable
            or a constant of same name
            Possible values are 'local', 'development', 'staging', and 'production'
            If not set, 'type' default is 'production'
        6. **`wp_favicon_request()`**: void - Don't load all of WP when handling a favicon.ico request
            Instead, send headers for a zero-length favicon and bail
        7. **`wp_maintenance()`**: void - Die with maintenance message when conditions met
            Default message can be replaced using a drop-in (maintenance.php in wp-content directory)
        8. **`wp_is_maintenance_mode()`**: true|false - Check if maintenance mode is enabled 
            Checks for a file in WP root dir. named '.maintenance'
            This file will contain var. $upgrading,
            set to the time the file was created.
            If the file was created < 10 mins. ago,
            WP is in maintenance mode
        9. **`timer_float()`**: float - Get time elapsed so far during this PHP script
            Uses REQUEST_TIME_FLOAT that appeared in PHP 5.4.0
        10. **`timer_start()`**: bool always - Start WP micro-timer
        11. **`timer_stop()`**: string - Get/display time from the page start to when function is called
        12. **`wp_debug_mode()`**: void - Set PHP error reporting based on WP debug settings
            CONSTANTS (3): ['WP_DEBUG', 'WP_DEBUG_DISPLAY', 'WP_DEBUG_LOG']
            All three (3) can be defined in 'wp-config.php'
            By default, `WP_DEBUG` and `WP_DEBUG_LOG` = false, `WP_DEBUG_DISPLAY` = true
            if `WP_DEBUG`:
                - all PHP notices are reported
                - WP will display internal notices: when a deprecated WP func./func.args./file is used
                - deprecated code may be removed from a later version
                - strongly recommended that plugin and theme devs. use `WP_DEBUG` in dev. envs.
            `WP_DEBUG_DISPLAY` & `WP_DEBUG_LOG` perform no function unless `WP_DEBUG` = true
            if `WP_DEBUG_DISPLAY`:
                - WP force errors to be displayed
                - default is true
                - defining as null prevents WP from changing global conf. setting
                - defining `WP_DEBUG_DISPLAY` = false ill force errors to be hidden
            if `WP_DEBUG_LOG`:
                - if true : errors will be logged to `wp-content/debug.log`
                - if valid path : errors will be logged to the specified file
                - errors are never displayed for:
                    1. XML-RPC
                    2. REST
                    3. `ms-files.php`
                    4. AJAX requests
        13. **`wp_set_lang_dir()`**: void - Set location of language directory
            - define `WP_LANG_DIR` constant in `wp-config.php` to set directory manually
            - language directory exists within `WP_CONTENT_DIR` ? use it : assumed language directory live in `WPINC`
        14. **`require_wp_db()`**: void - Load database class file and instantiate `$wpdb` global
        15. **`wp_set_wpdb_vars()`**: void - Set database table prefix and format specifiers for database table columns
            Columns not listed here default to `%s`
        16. **wp_using_ext_object_cache()**: bool - Toggle `$_wp_using_ext_object_cache` on and off without directly touching global
        17. **wp_start_object_cache()**: void - Start WP object cache
            If an object-cache.php file exists in `wp-content` directory,
            it uses that drop-in as an external object cache
        18. **wp_not_installed()**: void - Redirect to installer if WP not installed
        19. **wp_get_mu_plugins()**: array[string] - Get array of must-use plugin files [absolute paths of files to include]
        20. **wp_get_active_and_valid_plugins()**: array[string] - Get array of active and valid plugin files
            While upgrading or installing WP, no plugins are returned
            Default directory is `wp-content/plugins`
            To change default directory manually, define `WP_PLUGIN_DIR` & `WP_PLUGIN_URL` in `wp-config.php`
        21. **wp_skip_paused_plugins()**: array[string] - Filters given list of plugins, remove any paused plugins from it
        22. **wp_get_active_and_valid_themes()**: string|array[string] - Get an array of active and valid themes.
            While upgrading or installing WP, no themes are returned.
        23. **wp_skip_paused_themes()**: array[string] - Filters given list of themes, remove any paused themes from it.
        24. **wp_is_recovery_mode()**: bool - Is WP in Recovery Mode.
            In this mode, plugins or themes that cause WSODs will be paused.
        25. **is_protected_endpoint()**: bool - Determines whether we are currently on an endpoint that should be protected againsts WSODs.
        26. **is_protected_ajax_action()**: bool - Determines whether we are currently handling an AJAX action that should be protected againsts WSODs.
        27. **wp_set_internal_encoding()**: void - Set internal encoding.
            - In most cases, default internal encoding is = `latin1` which is of no use,
              since we are using `mb_` functions for `utf-8` strings.
        28. **wp_magic_quotes()**: void - Add magic quotes to `$_GET`, `$_POST`, `$_COOKIE`, and `$_SERVER`
            - Also forces `$_REQUESTS` to be `$_GET + $_POST`
            - If `$_SERVER`, `$_COOKIE`, or `$_ENV` are needed, use those superglobals directly.
        29. **shutdown_action_hook()**: void - Runs just before PHP shuts down execution.
        30. **wp_clone()**: cloned obj - Copy an object.
        31. **is_login()**: bool - Determines whether current request is for login screen.
        32. **is_admin()**: bool - Determines whether current request is for an administrative interface page.
        33. **is_blog_admin()**: bool - Determines whether current request is for a site's administrative interface.
            - Does not check if user is an administrator
            - current_user_can() - for checking roles and capabilities
        34. **is_network_admin()**: bool - Determines whether current request is for network administrative interface.
            - Does not check if user is an admin
            - current_user_can() - for checking roles and capabilities
            - is_multisite() - for checking if Multisite is enabled
        35. **is_user_admin()**: bool - Determines whether current req. is for user admin screen.
            - Does not check if user is an admin
            - current_user_can() - for checing roles and capabilities
        36. **is_multisite()**: bool - Return TRUE if Multisite is enabled
        37. **get_current_blog_id()**: int - Get current site ID
        38. **get_current_network_id()**: int - Get current network ID
        39. **wp_load_translations_early()**: void - Attempt an early load of translations.
            - Used for errors encountered during the initial loading process, before the locale has been properly detected and loaded.
            - Designed for unusual load sequences (like setup-config.php) or for when the script will then terminate with an error, otherwise there is a risk that a file can be double-included.
        40. **wp_installing()**: bool - Check or set whether WP is in 'installation' mode.
            - if `WP_INSTALLING` constant is defined during bootstrapping, `wp_installing()` will default to `TRUE``
        41. **is_ssl()**: bool - Determines if SSL is used.
        42. **wp_convert_hr_to_bytes( $value )**: int - Converts a shorthand byte value to an integer byte value.
        43. **wp_is_ini_value_changeable( $setting )**: bool - Determines whether a PHP ini value is changeable at runtime.
        44. **wp_doing_ajax()**: bool - Determines whether current request is a WP AJAX request.
        45. **wp_using_themes()**: bool - Determines whether current request should use themes.
        46. **wp_doing_cron()**: bool - Determines whether current request is WP cron request.
        47. **is_wp_error( $thing )**: bool - Checks whether given var. is a WP Error.
        48. **wp_is_file_mod_allowed( $context )**: bool - Determines whether file modifications are allowed.
        49. **wp_start_scraping_edited_file_errors()**: void - Start scraping edited file errors.
        50. **wp_finalize_scraping_edited_file_errors( $scrape_key )**: void - Finalize scraping for edited file errors.
        51. **wp_is_json_request()**: bool - Checks whether current request is a JSON request, or is expecting a JSON response.
        52. **wp_is_jsonp_request()**: bool - Checks whether current request is a JSONP request, or is expecting a JSONP response.
        53. **wp_is_json_media_type( $media_type )**: bool - Checks whether a string is a valid JSON Media Type.
        54. **wp_is_xml_request()**: bool - Checks whether current request is an XML request, or is expecting an XML response.
        55. **wp_is_site_protected_by_basic_auth( $context = '' )**: bool - Checks if this site is protected by HTTP Basic Auth.
            - At the moment, this merely checks for the present of Basic Auth credentials.
            - Therefore, calling this function with a context different from the current context may give inaccurate results.
            - In a future release, this evaluation may be made more robust.
            - Currently, this is only used by Application Password to prevent a conflict since it also utilizes Basic Auth.

8. **`wp-includes/class-wp-paused-extensions-storage.php`**
    - Error Protection API: WP_Paused_Extensions_Storage class (Core class used for storing paused extensions.)
    - This module contains WP_Paused_Extensions_Storage class definition

9. **`wp-includes/class-wp-fatal-error-handler.php`**
    - Error Protection API: WP_Fatal_Error_Handler class (Core class used as the default shutdown handler for fatal errors.)
    - A drop-in `fatal-error-handler.php` can be used to override the instance of this class and use a custom implementation for the fatal error handler that WP registers.
    - The custom class should extend this class and can override its methods individually as necessary.
    - The file must return the instance of the class that should be registered.
    - This module contains `WP_Fatal_Error_Handler` class definition

10. **`wp-includes/class-wp-recovery-mode-cookie-service.php`**
    - Error Protection API: WP_Recovery_Mode_Cookie_Service class (Core class used to set, validate, and clear cookies that identify a Recovery Mode session.)
    - This module contains final `WP_Recovery_Mode_Cookie_Service` class definition

11. **`wp-includes/class-wp-recovery-mode-key-service.php`**
    - Error Protection API: WP_Recovery_Mode_Key_Service class (Core class used to generate and validate keys used to enter Recovery Mode.)
    - This module contains final `WP_Recovery_Mode_Key_Service` class definition

12. **`wp-includes/class-wp-recovery-mode-link-service.php`**
    - Error Protection API: WP_Recovery_Mode_Link_Handler class (Core class used to generate and handle recovery mode links.)
    - This module contains `WP_Recovery_Mode_Link_Service` class definition

13. **`wp-includes/class-wp-recovery-mode-email-service.php`**
    - Error Protection API: WP_Recovery_Mode_Email_Link class (Core class used to send an email with a link to begin Recovery Mode.)
    - This module contains final `WP_Recovery_Mode_Email_Service` class definition

14. **`wp-includes/class-wp-recovery-mode.php`**
    - Error Protection API: WP_Recovery_Mode class (Core class used to implement Recovery Mode.)
    - This module contains `WP_Recovery_Mode` class definition

15. **`wp-includes/error-protection.php`**
    - Error Protection API: Functions
    - This module contains the following functions:
        1. **wp_paused_plugins()**: WP_Paused_Extensions_Storage - Get the instance for storing paused plugins.
        2. **wp_paused_themes()**: WP_Paused_Extensions_Storage - Get the instance for storing paused extensions.
        3. **wp_get_extension_error_description( $error )**: string - Get a human readable description of an extension's error.
        4. **wp_register_fatal_error_handler()**: void - Registers shutdown handler for fatal errors.
        5. **wp_is_fatal_error_handler_enabled()**: bool - Checks whether fatal error handler is enabled.
        6. **wp_recovery_mode()**: WP_Recovery_Mode - Access WP Recovery Mode instance.

16. **`wp-includes/default-constants.php`**
    - This module defines constants and global variables that can be overridden, generally in `wp-config.php`
    - This module contains the following functions:
        1. **wp_initial_constants()**: void - Defines initial WP constants.
        2. **wp_plugin_directory_constants()**: void - Defines plugin directory WP constants.
        3. **wp_cookie_constants()**: void - Defines cookie-related WP constants.
        4. **wp_ssl_constants()**: void - Defines SSL-related WP constants.
        5. **wp_functionality_constants()**: void - Defines functionality-related WP constants.
        6. **wp_templating_constants()**: void - Defines templating-related WP constants.

17. **`wp-includes/plugin.php`**
    - Notes:
        1. The plugin API is located in this file ( which allows for creating actions & filters and hooking functions & methods )
        2. The functions or methods will then be run when the action or filter is called
        3. The API callback examples reference functions, but can be methods of classes
        4. To hook methods, need to pass an array one of two ways
        5. This file should have no external dependencies
    - FLOW:
        1. Initialize filter globals
                require __DIR__ . '/class-wp-hook.php'
                global $wp_filter
                global $wp_actions
                global $wp_filters
                global $wp_current_filter
                $wp_filter = $wp_filter ? WP_Hook::build_preinitialized_hooks( $wp_filter ) : array()
                $wp_actions = ! isset( $wp_actions ) ? array()
                $wp_filters = ! isset( $wp_filters ) ? array()
                $wp_current_filter = ! isset( $wp_current_filter ) ? array()
    - This module contains the following functions:
        1. **add_filter( $hook_name, $callback, $priority = 10, $accepted_args = 1 )** - Adds a callback function to a filter hook.
            - WP offers filter hooks to allow plugins to modify various types of internal data at runtime.
            - A plugin can modify data by binding a callback to a filter hook.
            - When the filter is later applied, each bound callback is run in order of priority, and given the opportunity to modify a value by returning a new value.
        2. **apply_filters( $hook_name, $value, ...$args )** - Calls the callback functions that have been added to a filter hook.
            - This function invokes all functions attached to filter hook `$hook_name`.
            - It is possible to create new filter hooks by simply calling this function, specifying name of new hook using `$hook_name` parameter.
            - This function allows for multiple additional arguments to be passed to hooks.
        3. **apply_filters_ref_array( $hook_name, $args )** - Calls the callback functions that have been added to a filter hook, specifying arguments in an array.
        4. **has_filter( $hook_name, $callback = false )** - Checks if any filter has been registered for a hook.
            - when using `$callback` argument, this function may return a non-boolean value that evaluate to false (e.g. 0), so use the `===` operator for testing the return value.
        5. **remove_filter( $hook_name, $callback, $priority = 10 )** - Removes a callback function from a filter hook.
            - this can be used to remove default functions attached to a specific filter hook and possibly replace them with a substitute.
            - to remove a hook, the `$callback` and `$priority` arguments must match when the hook was added. This goes for both filters and actions. No warning will be given on removal failure.
        6. **remove_all_filters( $hook_name, $priority = false )** - Removes all callback functions from a filter hook.
        7. **current_filter()** - Get name of current filter hook.
        8. **doing_filter( $hook_name = null )** - Return whether or not a filter hook is currently being processed.
            - only return the most recent filter being executed.
            - `did_filter()` return the number of times a filter has been applied during current request.
            - allows detection for any filter currently being executed to be verified.
        9. **did_filter( $hook_name )** - Get number of times a filter has been applied during current request.
        10. **add_action( $hook_name, $callback, $priority = 10, $accepted_args = 1 )** - Adds a callback function to an action hook.
            - actions are the hooks that WP core launches at specific points during execution, or when specific events occur.
            - plugins can specify that one or more of its PHP functions are executed at these points, using the Action API.
        11. **do_action( $hook_name, ...$arg )** - Calls the callback functions that have been added to an action hook.
            - invokes all functions attached to action hook `$hook_name`.
            - possible to create new action hooks by simple calling this function, specifying name of new hook using `$hook_name` parameter.
            - can pass extra args. to hooks, much like with `apply_filters()`
        12. **do_action_ref_array( $hook_name, $args )** - Calls the callback functions that have been added to an action hook, specifying arguments in an array.
        13. **has_action( $hook_name, $callback = false )** - Checks if any action has been registered for a hook.
            - when using `$callback` argument, this function may return a non-boolean value that evaluates to false (e.g. 0), so use the `===` operator for testing the return value.
        14. **remove_action( $hook_name, $callback, $priority = 10 )** - Removes a callback function from an action hook.
            - can be used to remove default functions attached to a specific action hook and possibly replace them with a substitute.
            - to remove a hook, the `$callback` and `$priority` arguments must match when the hook was added. This goes for both filters and actions. No warning will be given on removal failure.
        15. **remove_all_actions( $hook_name, $priority = false )** - Removes all of the callback functions from an action hook.
        16. **current_action()** - Get name of current action hook.
        17. **doing_action( $hook_name = null )** - Return whether or not an action hook is currently being processed.
            - `current_action()` only return the most recent action being executed.
            - `did_action()` return number of times an action has been fired during current request.
            - allows detection for any action currently being executed [*] to be verified.
                WHERE::( * = regardless of whether it's the most recent action to fire, in case of hooks called from hook callbacks )
        18. **did_action( $hook_name )** - Get number of times an action has been fired during current request.
        19. **apply_filters_deprecated( $hook_name, $args, $version, $replacement = '', $message = '' )** - Fires functions attached to a deprecated filter hook.
            - when a filter hook is deprecated, the `apply_filters()` call is replaced with `apply_filters_deprecated()`, which triggers a deprecation notice and then fires the original filter hook.
            - value and extra arguments paseed to the original `apply_filters()` call must be passed here to `$args` as an array.
        20. **do_action_deprecated( $hook_name, $args, $version, $replacement = '', $message = '' )** - Fires functions attached to a deprecated action hook.
            - when an action hook is deprecated, the `do_action()` call is replaced with `do_action_deprecated()`, which triggers a deprecation notice then fires the original hook.
        21. =#= Functions for handling plugins.
        22. **plugin_basename( $file )** - Get basename of a plugin.
            - extracts the name of a plugin from its filename.
        23. **wp_register_plugin_realpath( $file )** - Register a plugin's real path.
            - used in `plugin_basename()` to resolve symlinked paths.
        24. **plugin_dir_url( $file )** - Get URL directory path (with trailing slash) for the plugin __FILE__ passed in.
        25. **register_activation_hook( $file, $callback )** - Set activation hook for a plugin.
            - when a plugin is activated, action `activate_PLUGINNAME` hook is called.
            - in the name of hook above, `PLUGINNAME` is replaced with name of the plugin, including optional subdirectory.
            (e.g. when the plugin is located in `wp-content/plugins/sampleplugin/sample.php`, then name of this hook will become `activate_sampleplugin/sample.php`)
            - when plugin consists of only one file and is (by default) located at `wp-content/plugins/sample.php` the name of this hook will be `activate_sample.php`.
        26. **register_deactivation_hook( $file, $callback )** - Sets deactivation hook for a plugin.
            - when a plugin is deactivated, action `deactivate_PLUGINNAME` hook is called.
            - in the name of hook above, `PLUGINNAME` is replaced with name of the plugin, including optional subdirectory.
            (e.g. when the plugin is located in `wp-content/plugins/sampleplugin/sample.php`, then the name of this hook will become `deactivate_sampleplugin/sample.php`)`.
            - when plugin consists of only one file and is (by default) located at `wp-content/plugins/sample.php` the name of this hook will be `deactivate_sample.php``.
        27. **register_uninstall_hook( $file, $callback )** - Sets uninstallation hook for a plugin.
            - register uninstall hook that will be called when user clicks on uninstall link that calls for plugin to uninstall itself.
            - link won't be active unless the plugin hooks into the action.
            - plugin should not run arbitrary code outside of functions, when registering uninstall hook.
            - in order to run using the hook, plugin will have to be included
            (e.g. any code laying outside of a function will be run during the uninstallation process.)
            - plugin should not hinder uninstallation process.
            - if the plugin can not be written without running code within the plugin, then the plugin should create a file named 'uninstall.php' in the base plugin folder.
            - this file will be called, if it exists, during the uninstallation process bypassing the uninstall hook.
            - the plugin, when using the `uninstall.php` should always check for the `WP_UNINSTALL_PLUGIN` constant, before executing.
        28. **`_wp_call_all_hook( $args )`** - Calls the `all` hook, which will process the functions hooked into it.
            - `all` hook passes all of the arguments or parameters that were used for the hook, which this function was called for.
            - used internally for `apply_filters()`, `do_action()`, and `do_action_ref_array()` and is not meant to be used from outside those functions.
            - does not check for the existence of the all hook, so it will fail unless the all hook exists prior to this function call.
        29. **_wp_filter_build_unique_id( $hook_name, $callback, $priority )** - Builds Unique ID for storage and retrieval.
            - old way to serialize the callback caused issues and this function is the solution.
            - works by checking for objects and creating a new property in the class to keep track of the object and new objects of the same class that need to be added.
            - allows for the removal of actions and filters for objects after they change class properties.
            - possible to include the property `$wp_filter_id` in your class and set it to `null` or a number to bypass the workaround.
            - however this will prevent you from adding new classes and any new classes will overwrite the previous hook by the same class.
            - functions and static method callbacks are just returned as strings and shouldn't have any speed penalty.

18. **`wp-includes/default-filters.php`**
    - Notes:
        - sets up the default filters and actions for most of the WP hooks.
        - if need to remove a default hook, this file will give the priority to use for removing the hook.
        - not all of the default hook are found in this file.
        (e.g. administration-related hooks are located in `wp-admin/includes/admin-filters.php`)
        - if a hook should only be called from a specific context (admin area, multisite envs. ...), move it to a more appropriate file instead.
    - FLOW:
        1. Strip, trim, kses, special chars for string saves.
        2. Strip, kses, special chars for string display.
        3. Kses only for textarea saves.
        4. Kses only for textarea admin displays.
        5. Email saves.
        6. Email admin display.
        7. Save URL.
        8. Display URL.
        9. Slugs.
        10. Keys.
        11. Mime types.
        12. Meta.
        13. Counts.
        14. Post meta.
        15. Term meta.
        16. Comment meta.
        17. Places to balance tags on input.
        18. Add proper rel values for links with target.
        19. Format strings for display.
        20. Format WordPress.
        21. Format titles.
        22. Format text area for display.
        23. Format for RSS.
        24. Pre save hierarchy.
        25. Display filters.
        26. RSS filters.
        27. Email filters.
        28. Robots filters.
        29. Mark site as no longer fresh.
        30. Misc filters.
        31. REST API filters.
        32. Actions.
        33. Login actions.
        34. Feed generator tags.
        35. Feed Site Icon.
        36. WP Cron.
        37. HTTPS detection.
        38. HTTPS migration.
        39. 2 Actions 2 Furious.
        40. Create a revision whenever a post is updated.
        41. Privacy.
        42. Cron tasks.
        43. Navigation menu actions.
        44. Post Thumbnail CSS class filtering.
        45. Redirect old slugs.
        46. Redirect old dates.
        47. Nonce check for post previews.
        48. Output JS to reset window.name for previews.
        49. Timezone.
        50. If the upgrade hasn't run yet, assume link manager is used.
        51. =#= support auto-embedding
        52. Default settings for heartbeat.
        53. Check if the user is logged out.
        54. Default authentication filters.
        55. Split term updates.
        56. Comment type updates.
        57. Email notifications.
        58. REST API actions.
        59. Sitemaps actions.
        60. =##= Filters formerly mixed into `wp-includes`.
        61. Theme.
        62. Calendar widget cache.
        63. Author.
        64. Post.
        65. Post Formats.
        66. KSES.
        67. Script Loader.
        68. Global styles can be enqueued in both the header and the footer.
        69. Block supports, and other styles parsed and stored in the Style Engine.
        70. SVG filters like duotone have to be loaded at the beginning of the body in both admin and the front-end.
        71. Disable `Post Attributes` for `wp_navigation` post type. The attributes are also conditionally enabled when a site has custom templates. Block Theme templates can be available for every post type.
        72. Taxonomy.
        73. Canonical.
        74. Shortcodes.
        75. Media.
        76. Nav menu.
        77. Widgets.
        78. Admin Bar.
        79. Former admin filters that can also be hooked on the front end.
        80. Embeds.
        81. Capabilities.
        82. Block templates post type and rendering.
        83. Fluid typography.
        84. User preferences.

19. **`wp-includes/class-wp-site-query.php`**
    - Site API: WP_Site_Query class (Core class used for querying sites.)
    - This module contains `WP_Site_Query` class definition

20. **`wp-includes/class-wp-network-query.php`**
    - Network API: WP_Network_Query class (Core class used for querying networks.)
    - This module contains `WP_Network_Query` class definition

21. **`wp-includes/ms-blogs.php`**
    - Site/blog functions that work with the blogs table and related data.
    - FLOW:
        1. require-once ABSPATH . WPINC . '/ms-site.php'
        2. require-once ABSPATH . WPINC . '/ms-network.php'
    - This module contains the following functions:
        1. **`wpmu_update_blogs_date()`** - Update the last_updated field for the current site.
        2. **`get_blogaddress_by_id( $blog_id )`** - Get a full blog URL, given a blog ID.
        3. **`get_blogaddress_by_name( $blogname )`** - Get a full blog URL, given a blog name.
        4. **`get_id_from_blogname( $slug )`** - Get a site's ID given its slug (subdomain or directory).
        5. **`get_blog_details( $fields = null, $get_all = true )`** - Retrieve the details for a blog from the blogs table and blog options.
        6. **`refresh_blog_details( $blog_id = 0 )`** - Clear the blog details cache.
        7. **`update_blog_details( $blog_id, $details = array() )`** - Update the details for a blog. Updates the blogs table for a given blog ID.
        8. **`clean_site_details_cache( $site_id = 0 )`** - Cleans the site details cache for a site.
        9. **`get_blog_option( $id, $option, $default_value = false )`** - Retrieve option value for a given blog id based on name of option.
            - if the option does not exist or does not have a value, then the return value will be false.
            - this is useful to check whether you need to install an option and is commonly used during installation of plugin options and to test whether upgrading is required.
            - if the option was serialized then it will be unserialized when it is returned.
        10. **`add_blog_option( $id, $option, $value )`** - Add a new option for a given blog ID.
            - no need to serialize values.
            - if value needs to be serialized, then it will be serialized before it is inserted into the database.
            - remember, resources can not be serialized or added as an option.
            - can create options without values and then update the values later.
            - existing options will not be updated and checks are performed to ensure that you (me) aren't adding a protected WordPress option.
            - care should be taken to not name options the same as the ones which are protected.
        11. **`delete_blog_option( $id, $option )`** - Removes option by name for a given blog ID. Prevents removal of protected WordPress options.
        12. **`update_blog_option( $id, $option, $value, $deprecated = null )`** - Update an option for a particular blog.
        13. **`switch_to_blog( $new_blog_id, $deprecated = null )`** - Switch the current blog.
            - useful if you need to pull posts, or other information, from other blogs.
            - can switch back afterwards using `restore_current_blog()`
            - `plugins` are not switched.
        14. **`restore_current_blog()`** - Restore the current blog, after calling `switch_to_blog()`.
        15. **`wp_switch_roles_and_user( $new_site_id, $old_site_id )`** - Switches the initialized roles and current user capabilities to another site.
        16. **`ms_is_switched()`** - Determines if `switch_to_blog()` is in effect.
        17. **`is_archived( $id )`** - Check if a particular blog is archived.
        18. **`update_archived( $id, $archived )`** - Update the `archived` status of a particular blog.
        19. **`update_blog_status( $blog_id, $pref, $value, $deprecated = null )`** - Update a blog details field.
        20. **`get_blog_status( $id, $pref )`** - Get a blog details field.
        21. **`get_last_updated( $deprecated = '', $start = 0, $quantity = 40 )`** - Get a list of most recently updated blogs.
        22. `_update_blog_date_on_post_publish( $new_status, $old_status, $post )`** -** Handler for updating the site's last updated date when a post is published or an already published post is changed.
        23. **`_update_blog_date_on_post_delete( $post_id )`** - Handler for updating the current site's last updated date when a published post is deleted.
        24. **`_update_posts_count_on_delete( $post_id )`** - Handler for updating the current site's posts count when a post is deleted.
        25. `_update_posts_count_on_transition_post_status( $new_status, $old_status, **$post = null )`** - Handler for updating the current site's posts count when a post status changes.
        26. **`wp_count_sites( $network_id = null )`** - Count number of sites grouped by site status.

22. **`wp-includes/ms-settings.php`**
    - NOTES:
        - used to set up and fix common variables and include the Multisite procedural and class library.
        - allows for some configuration in `wp-config.php` (see `ms-default-constants.php`)
    - FLOW:
        1. declare global on Objects representing the current network and current site.

                @global WP_Network $current_site The current network.
                @global object     $current_blog The current site.
                @global string     $domain       Deprecated. The domain of the site found on load.
                                                 Use `get_site()->domain` instead.
                @global string     $path         Deprecated. The path of the site found on load.
                                                 Use `get_site()->path` instead.
                @global int        $site_id      Deprecated. The ID of the network found on load.
                                                 Use `get_current_network_id()` instead.
                @global bool       $public       Deprecated. Whether the site found on load is public.
                                                 Use `get_site()->public` instead.
                
                global $current_site, $current_blog, $domain, $path, $site_id, $public;

        2. WP_Network class - ( require-once ABSPATH . WPINC . `/class-wp-network.php` )
        3. WP_Site class - ( require-once ABSPATH . WPINC . `/class-wp-site.php` )
        4. Multisite loader - ( require-once ABSPATH . WPINC . `/ms-load.php` )
        5. Default Multisite constant - ( require-once ABSPATH . WPINC . `/ms-default-constants.php` )
        6. defined( `SUNRISE` ) ? include-once WP_CONTENT_DIR . `/sunrise.php`
        7. Check for and define `SUBDOMAIN_INSTALL` and the deprecated VHOST constants. - ( ms_subdomain_constants(); )
        8. if current network or site objects have not been populated in the global scope through something like `sunrise.php`, run closure block.
        9. Set database prefix - can be set in sunrise.php
        10. Need to init cache agin after `blog_id` is set.

                    wp_start_object_cache();

                    $current_site = ! $current_site instanceof WP_Network ? new WP_Network( $current_site ):;
                    $current_blog = ! $current_blog instanceof WP_Site ? new WP_Site( $current_blog ):;

        11. Define upload directory constants.

                    ms_upload_constants();

        12. do_action( 'ms_loaded' ) - Fires after the current site and network have been detected and loaded in multisite's bootstrap.

23. **`wp-includes/l10n.php`**
    - Core Translation API
    - This module contains the following functions:
        1. **`get_locale()`** - Get the current locale.
            - if locale is set, then it will filter the locale in the filter hook and return the value.
            - if locale is not set, then `WPLANG` constant is used if it is defined.
            - the it is filtered through the filter hook and the value for the locale global set and the locale is returned.
            - the process to get the locale should only be done once, but the locale will always be filtered using the hook.
        2. **`get_user_locale( $user = 0 )`** - Get the locale of a user.
            - if the user has a locale set to a non-empty string, then it will be returned.
            - otherwise it return the locale of `get_locale()`
        3. **`determine_locale()`** - Determines the current locale desired for the request.
        4. **`translate( $text, $domain = 'default' )`** - Get the translation of `$text`.
            - if there is no translation, or the text domain isn't loaded, the original text is returned.
            - don't use `translate()` directly, use `__()` or related functions.
        5. **`before_last_bar( $text )`** - Removes last item on a pipe-delimited string.
            - meant for removing the last item in a string, such as 'Role name|User role'.
            - the original string will be returned if no pipe '|' characters are found in the string.
        6. **`translate_with_gettext_context( $text, $context, $domain = 'default' )`** - Get the translation of `$text` in the context defined in `$context`.
            - if there is no translation, or the text domain isn't loaded, the original text is returned.
            - don't use `translate_with_gettext_context()` directly, use `_x()` or related functions.
        7. **`__( $text, $domain = 'default' )`** - Get the translation of `$text`.
            - if there is no translation, or the text domain isn't loaded, the original text is returned.
        8. **`esc_attr__( $text, $domain = 'default' )`** - Get the translation of `$text` and escapes it for safe use in an attribute.
            - if there is no translation, or the text domain isn't loaded, the original text is returned.
        9. **`esc_html__( $text, $domain = 'default' )`** - Get the translation of `$text` and escapes it for safe use in HTML output.
            - if there is no translation, or the text domain isn't loaded, the original text is escaped and returned.
        10. **`_e( $text, $domain = 'default' )`** - Displays translated text.
        11. **`esc_attr_e( $text, $domain = 'default' )`** - Displays translated text that has been escaped for safe use in an attribute.
            - encodes `< > & " '`.
            - will never double encode entities.
            - use `esc_attr__()` if need value for use in PHP.
        12. **`esc_html_e( $text, $domain = 'default' )`** - Displays translated text that has been escaped for safe use in HTML output.
            - if there is no translation, or the text domain isn't loaded, the original text is escaped and displayed.
            - use `esc_html__()` if need value for use in PHP.
        13. **`_x( $text, $context, $domain = 'default' )`** - Get translated string with gettext context.
            - quite a few times, there will be collisions with similar translatable text found in more than two places, but with different translated context.
            - by including the context in the pot file, translators can translate the two strings differently.
        14. **`_ex( $text, $context, $domain = 'default' )`** - Displays translated string with gettext context.
        15. **`esc_attr_x( $text, $context, $domain = 'default' )`** - Translates string with gettext context, and escapes it for safe use in an attribute.
            - if there is no translation, or the text domain isn't loaded, the original text is escaped and returned.
        16. **`esc_html_x( $text, $context, $domain = 'default' )`** - Translates string with gettext context, and escapes it for safe use in HTML output.
            - if there is no translation, or the text domain isn't loaded, the original text is escaped and returned.
        17. **`_n( $single, $plural, $number, $domain = 'default' )`** - Translates and retrieves the singular or plural form based on the supplied number.
            - used when you want to use the appropriate form of a string based on whether a number is singular or plural.
        18. **`_nx( $single, $plural, $number, $domain = 'default' )`** - Translates and retrieves the singular or plural form based on the supplied number, with gettext context.
            - this is a hybrid of `_n()` and `_x()`.
            - it supports context and plurals.
            - used when you (me) want to use the appropriate form of a string with context based on whether a number is singular or plural.
        19. **`_n_noop( $singular, $plural, $domain = null )`** - Registers plural string in POT file, but does not translate them.
            - used when you (me) want to keep structures with translatable plural strings and use them later when the number is known.
        20. **`_nx_noop( $singular, $plural, $context, $domain = null )`** - Registers plural strings with gettext context in POT file, but does not translate them.
            - used when you (me) want to keep structures with translatable plural strings and use them later when the number is known.
        21. **`translated_nooped_plural( $nooped_plural, $count, $domain = 'default' )`** - Translates and returns the singular or plural form of a string that's been registered with `_n_noop()` or `_nx_noop()`.
            - used when you want to use a translatable plural string once the number is known.
        22. **`load_textdomain( $domain, $mofile, $locale = null )`** - Loads a .mo file into the text domain `$domain`.
            - if the text domain already exists, the translations will be merged.
            - if both sets have the same string, the translation from the original value will be taken.
            - on success, the .mo file will be placed in the `$l10n` global by `$domain` and will be a MO object.
        23. **`unload_textdomain( $domain, $reloadable = false )`** - Unloads translations for a text domain.
        24. **`load_default_textdomain( $locale = null )`** - Loads default translated strings based on locale.
            - loads the .mo file in `WP_LANG_DIR` constant path from WordPress root.
            - the translated (.mo) file is named based on the locale.
        25. **`load_plugin_textdomain( $domain, $deprecated = false, $plugin_rel_path = false )`** - Loads a plugin's translated strings.
            - if the path is not given then it will be the root of the plugin directory.
            - the .mo file should be named based on the text domain with a dash, and then the locale exactly.
        26. **`load_muplugin_textdomain( $domain, $mu_plugin_rel_path = '' )`** - Loads the translated strings for a plugin residing in the mu-plugins directory.
        27. **`load_theme_textdomain( $domain, $path = false )`** - Loads the theme's translated strings.
            - if the current locale exists as a .mo file in the theme's root directory, it will be included in the translated strings by the `$domain`.
            - the .mo files must be named based on the locale exactly.
        28. **`load_child_theme_textdomain( $domain, $path = false )`** - Loads the child theme's translated strings.
            - if the current locale exists as a .mo file in the child theme's root directory, it will be included in the translated strings by the `$domain`.
            - the .mo files must be named based on the locale exactly.
        29. **`load_script_textdomain( $handle, $domain = 'default', $path = '' )`** - Loads the script translated strings.
        30. **`load_script_translations( $file, $handle, $domain )`** - Loads the translation data for the given script handle and text domain.
        31. **`_load_textdomain_just_in_time( $domain )`** - Loads plugin and theme text domains just-in-time.
            - when a textdomain is encountered for the first time, we try to load the translation file from `wp-content/languages`, removing the need to call `load_plugin_textdomain()` or `load_theme_textdomain()`.
        32. **`get_translations_for_domain( $domain )`** - Returns the Translations instance for a text domain.
            - if there isn't one, returns empty Translations instance.
        33. **`is_textdomain_loaded( $domain )`** - Determines whether there are translations for the text domain.
        34. **`translate_user_role( $name, $domain = 'default' )`** - Translates role name.
            - since the role names are in the database and not in the source there are dummy gettext calls to get them into the POT file and this function properly translates them back.
            - `before_last_bar()` call is needed, because older installations keep the roles using the old context format: 'Role name|User role' and just skipping the content after the last bar is easier than fixing them in the DB. New installations won't suffer from that problem.
        35. **`get_available_languages( $dir = null )`** - Gets all available languages based on the presence of *.mo files in a given directory.
            - the default directory is `WP_LANG_DIR`.
        36. **`wp_get_installed_translations( $type )`** - Gets installed translations.
            - looks in the `wp-content/languages` directory for translations of plugins or themes.
        37. **`wp_get_pomo_file_data( $po_file )`** - Extracts headers from a PO file.
        38. **`wp_dropdown_languages( $args = array() )`** - Displays or returns a Language selector.
        39. **`is_rtl()`** - Determines whether the current locale is right-to-left (RTL).
        40. **`switch_to_locale( $locale )`** - Switches the translations according to the given locale.
        41. **`restore_previous_locale()`** - Restores the translations according to the previous locale.
        42. **`restore_current_locale()`** - Restores the translations according to the original locale.
        43. **`is_locale_switched()`** - Determines whether switch_to_locale() is in effect.
        44. **`translate_settings_using_i18n_schema( $i18n_schema, $settings, $textdomain )`** - Translates the provided settings value using its i18n schema.
        45. **`wp_get_list_item_separator()`** - Retrieves the list item separator based on the locale.

24. **`wp-includes/class-wp-textdomain-registry.php`**
    - Locale API: WP_Textdomain_Registry class (Core class used for registering text domains.)
    - this module contains `WP_Textdomain_Registry` class definition

25. **`wp-includes/class-wp-locale.php`**
    - Locale API: WP_Locale class (Core class used to store translated data for a locale.)
    - this module contains `WP_Locale` class definition

26. **`wp-includes/class-wp-locale-switcher.php`**
    - Locale API: WP_Locale_Switcher class (Core class used for switching locales.)
    - this module contains `WP_Locale_Switcher` class definition

27. **`wp-includes/class-wp-walker.php`**
    - this module contains `Walker` class definition
    - a class for displaying various tree-like structures.
    - extend the `Walker` class to use it
    - child classes do not need to implement all of the abstract method in the class
    - child only needs to implement the methods that are needed

28. **`wp-includes/class-wp-ajax-response.php`**
    - this module contains `WP_Ajax_Response` class definition
    - send XML response back to Ajax request

29. **`wp-includes/capabilities.php`**
    - Core User Role & Capabilities API
    - this module contains the following functions:
        1. **`map_meta_cap( $cap, $user_id, ...$args )`** - maps a capability to the primitive capabilities required of the given user to satisfy the capability being checked.
            - accepts and ID of an object to map against if the capability is a meta capability.
            - meta capabilities such as `edit_post` and `edit_user` are capabilities used by this function to map to primitive capabilities that a user or role requires, such as `edit_posts` and `edit_others_posts`.
            - does not check whether the user has the required capabilities.
            - just return what the required capabilities are.
        2. **`current_user_can( $capability, ...$args )`** - return whether current user has the specified capability.
            - accepts an ID of an object to check against if the capability is a meta capability.
            - meta capabilities such as `edit_post` and `edit_user` are capabilities used by the `map_meta_cap()` function to map to primitive capabilities that a user or role has, such as `edit_posts` and `edit_others_posts`.
            - while checking against particular roles in place of a capability is supported in part, this practice is discourage as it may produce unreliable results.
            - will always return true if the current user is a super admin, unless specifically denied.
        3. **`current_user_can_for_blog( $blog_id, $capability, ...$args )`** - return whether the current user has the specified capability for a given site.
            - accepts an ID of an object to check against if the capability is a meta capability.
            - meta capabilities such as `edit_post` and `edit_user` are capabilities used by the `map_meta_cap()` function to map to primitive capabilities that a user or role has, such as `edit_posts` and `edit_others_posts`.
        4. **`author_can( $post, $capability, ...$args )`** - return whether the author of the supplied post has the specified capability.
            - accepts an ID of an object to check against if the capability is a meta capability
            - meta capabilities such as `edit_post` and `edit_user` are capabilities used by the `map_meta_cap()` to map to primitive capabilities that a user or role has, such as `edit_posts` and `edit_others_posts`.
        5. **`user_can( $user, $capability, ...$args )`** - return whether a particular user has the specified capability.
            - accepts an ID of an object to check against if the capability is a meta capability
            - meta capabilities such as `edit_post` and `edit_user` are capabilities used by the `map_meta_cap()` to map to primitive capabilities that a user or role has, such as `edit_posts` and `edit_others_posts`.
        6. **`wp_roles()`** - retrieves the global `WP_Roles` instance and instantiates it if necessary.
        7. **`get_role( $role )`** - retrieves role object.
        8. **`add_role( $role, $display_name, $capabilities = array() )`** - adds a role, if it does note exist.
        9. **`remove_role( $role )`** - removes a role, if it exists.
        10. **`get_super_admins()`** - retrieves a list of super admins.
        11. **`is_super_admin( $user_id = false )`** - determines whether user is a site admin.
        12. **`grant_super_admin( $user_id )`** - grants Super Admin privileges.
        13. **`revoke_super_admin( $user_id )`** - revokes Super Admin privileges.
        14. **`wp_maybe_grant_install_languages_cap( $allcaps )`** - filters the user capabilities to grant the `install_languages` capability as necessary.
            - a user must have at least one out of the `update_core`, `install_plugins`, and `install_themes` capabilities to qualify for `install_languages`.
        15. **`wp_maybe_grant_resume_extensions_caps( $allcaps )`** - filters the user capabilities to grant the `resume_plugins` and `resume_themes` capabilities as necessary.
        16. **`wp_maybe_grant_site_health_caps( $allcaps, $caps, $args, $user )`** - filters the user capabilities to grant the `view_site_health_checks` capabilities as necessary.
    - FLOW:
        1. =##= Above functions definition
        2. return
        3. Dummy gettext calls to get strings in the catalog.

                    /* translators: User role for administrators. */
                    _x( 'Administrator', 'User role' );
                    /* translators: User role for editors. */
                    _x( 'Editor', 'User role' );
                    /* translators: User role for authors. */
                    _x( 'Author', 'User role' );
                    /* translators: User role for contributors. */
                    _x( 'Contributor', 'User role' );
                    /* translators: User role for subscribers. */
                    _x( 'Subscriber', 'User role' );

30. **`wp-includes/class-wp-roles.php`**
    - User API: WP_Roles class (Core class used to implement a user roles API.)
    - the role option is simple
    - the structure is organized by role name that store the name in value of the `name` key
    - capabilities are stored as an array in the value of the `capability` key

            array (
                'rolename' => array (
                    'name' => 'rolename',
                    'capabilities' => array()
                )
            )

    - this module contains `WP_Roles` class definition

31. **`wp-includes/class-wp-role.php`**
    - User API: WP_Role class (Core class used to extend the user roles API.)
    - this module contains `WP_Role` class definition

32. **`wp-includes/class-wp-user.php`**
    - User API: WP_User class (Core class used to implement the `WP_User` object.)
    - this module contains `WP_User` class definition

33. **`wp-includes/class-wp-query.php`**
    - Query API: WP_Query class (WordPress Query class.)
    - this module contains `WP_Query` class definition

34. **`wp-includes/query.php`**
    - WordPress Query API
    - Notes:
        - the query API attempts to get which part of WordPress the user is on
        - it also provides functionality for getting URL query information
    - this module contains the following functions:
        1. **`get_query_var( $var, $default = '' )`** - Retrieves the value of a query variable in the `WP_Query` class.
        2. **`get_queried_object()`** - Retrieves the currently queried object.
            - wrapper for `WP_Query::get_queried_object()`
        3. **`get_queried_object_id()`** - Retrieves the ID of the currently queried object.
            - wrapper for `WP_Query::get_queried_object_id()`
        4. **`set_query_var( $var, $value )`** - Sets the value of a query variable in the `WP_Query` class.
        5. **`query_posts( $query )`** - Sets up The Loop with query parameters.
            - this function will **completely override** the main query and isn't intended for use by plugins or themes.
            - its overly-simplistic approach to modifying the main query can be problematic and should be avoided wherever possible.
            - in most cases, there are better, more performant options for modifying the main query such as via the action within `WP_Query`.
            - this **must not be used** within the **WordPress Loop**.
        6. **`wp_reset_query()`** - Destroys the previous query and sets up a new query.
            - this should be used **after** `query_posts()` and **before another** `query_posts()`.
            - this will remove **obscure bugs** that occur when the previous `WP_Query` object is not destroyed properly before another is set up.
        7. **`wp_reset_postdata()`** - After looping through a separate query, this function restores the `$post` global to the current post in the main query.
        8. =##= **Query type checks**.
        9. **`is_archive()`** - Determines whether the query is for an existing archive page.
            - archive pages include category, tag, author, date, custom post type, and custom taxonomy based archives.
        10. **`is_post_type_archive( $post_types = '' )`** - Determines whether the query is for an existing post type archive page.
        11. **`is_attachment( $attachment = '' )`** - Determines whether the query is for an existing attachment page.
        12. **`is_author( $author = '' )`** - Determines whether the query is for an existing author archive page.
            - if the `$author` parameter is specified, this function will additionally check if the query is for one of the authors specified.
        13. **`is_category( $category = '' )`** - Determines whether the query is for an existing category archive page.
            - if the `$category` parameter is specified, this function will additionally check if the query is for one of the categories specified.
        14. **`is_tag( $tag = '' )`** - Determines whether the query is for an existing tag archive page.
            - if the `$tag` parameter is specified, this function will additionally check if the query is for one of the tags specified.
        15. **`is_tax( $taxonomy = '', $term = '' )`** - Determines whether the query is for an existing custom taxonomy archive page.
            - if the `$taxonomy` parameter is specified, this function will additionally check if the query is for that specific `$taxonomy`.
            - if the `$term` parameter is specified in addition to the `$taxonomy` parameter, this function will additionally check if the query is for one of the terms specified.
        16. **`is_date()`** - Determines whether the query is for an existing date archive.
        17. **`is_day()`** - Determines whether the query is for an existing day archive.
            - a conditional check to test whether the page is a date-based archive page displaying posts for the current day.
        18. **`is_feed( $feeds = '' )`** - Determines whether the query is for a feed.
        19. **`is_comment_feed()`** - Is the query for a comments feed?
        20. **`is_front_page()`** - Determines whether the query is for the front page of the site.
            - this is for what is displayed at your site's main URL.
            - depends on the site's` "Front page displays" Reading Settings 'show_on_front' and 'page_on_front'.
            - if you set a static page for the front page of your site, this function will return true when viewing that page.
            - otherwise the same as `is_home()`.
        21. **`is_home()`** - Determines whether the query is for the blog homepage.
            - the blog homepage is the page that shows the time-based blog content of the site.
            - `is_home()` is dependent on the site's "Front page displays" Reading Settings 'show_on_front' and 'page_for_posts'.
            - if a static page is set for the front page of the site, this function will return true only on the page you set as the "Posts page".
        22. **`is_privacy_policy()`** - Determines whether the query is for the Privacy Policy page.
            - the Privacy Policy page is the page that shows the Privacy Policy content of the site.
            - `is_privacy_policy()` is dependent on the site's "Change your Privacy Policy page" Privacy Settings 'wp_page_for_privacy_policy'.
            - this function will return true only on the page you set as the "Privacy Policy page".
        23. **`is_month()`** - Determines whether the query is for an existing month archive.
        24. **`is_page( $page = '' )`** - Determines whether the query is for an existing single page.
            - if the `$page` parameter is specified, this function will additionally check if the query is for one of the pages specified.
        25. **`is_paged()`** - Determines whether the query is for a paged result and not for the first page.
        26. **`is_preview()`** - Determines whether the query is for a post or page preview.
        27. **`is_robots()`** - Is the query for the robots.txt file?
        28. **`is_favicon()`** - Is the query for the favicon.ico file?
        29. **`is_search()`** - Determines whether the query is for a search.
        30. **`is_single( $post = '' )`** - Determines whether the query is for an existing single post.
            - works for any post type, except attachments and pages
            - if the `$post` parameter is specified, this function will additionally check if the query is for one of the Posts specified.
        31. **`is_singular( $post_types = '' )`** - Determines whether the query is for an existing single post of any post type (post, attachment, page, custom post types).
            - if the `$post_types` parameter is specified, this function will additionally check if the query is for one of the Posts Types specified.
        32. **`is_time()`** - Determines whether the query is for a specific time.
        33. **`is_trackback()`** - Determines whether the query is for a trackback endpoint call.
        34. **`is_year()`** - Determines whether the query is for an existing year archive.
        35. **`is_404()`** - Determines whether the query has resulted in a 404 (returns no results).
        36. **`is_embed()`** - Is the query for an embedded post?
        37. **`is_main_query()`** - Determines whether the query is the main query.
        38. =##= The Loop. Post loop control.
        39. **`have_posts()`** - Determines whether current WordPress query has posts to loop over.
        40. **`in_the_loop()`** - Determines whether the caller is in the Loop.
        41. **`rewind_posts()`** - Rewind the loop posts.
        42. **`the_post()`** - Iterate the post index in the loop.
        43. =##= Comments loop.
        44. **`have_comments()`** - Determines whether current WordPress query has comments to loop over.
        45. **`the_comment()`** - Iterate comment index in the comment loop.
        46. **`wp_old_slug_redirect()`** - Redirect old slugs to the correct permalink.
        47. **`_find_post_by_old_slug( $post_type )`** - Find the post ID for redirecting an old slug.
        48. **`_find_post_by_old_date( $post_type )`** - Find the post ID for redirecting an old date.
        49. **`setup_postdata( $post )`** - Set up global post data.
        50. **`generate_postdata( $post )`** - Generates post data.

35. **`wp-includes/class-wp-date-query.php`**
    - class for generating SQL clauses that filter a primary query according to date.
    - `WP_Date_Query` is a helper that allows primary query classes, such as `WP_Query`, to filter their results by date columns, by generating `WHERE` subclauses to be attached to the primary SQL query string.
    - attempting to filter by an invalid date value (e.g. month = 13) will generate SQL that will return **no results**.
    - in these cases, a `_doing_it_wrong()` error notice is also thrown.
    - See `WP_Date_Query::validate_date_values()`.
    - this module contains `WP_Date_Query` class definition

36. **`wp-includes/theme.php`**
    - Theme, template, and stylesheet functions.
    - @package WordPress @subpackage Theme
    - this module contains the following functions:
        1. **`wp_get_themes( $args = array() )`** - Returns an array of `WP_Theme` objects based on the arguments.
            - despite advances over `get_themes()`, this function is quite expensive, and grows linearly with additional themes.
            - stick to `wp_get_theme()` if possible.
        2. **`wp_get_theme( $stylesheet = '', $theme_root = '' )`** - Gets a `WP_Theme` object for a theme.
        3. **`wp_clean_themes_cache( $clear_update_cache = true )`** - Clears the cache held by `get_theme_roots()` and `WP_Theme`.
        4. **`is_child_theme()`** - Whether a child theme is in use.
        5. **`get_stylesheet()`** - Retrieves name of the current stylesheet.
            - the theme name that is currently set as the front end theme.
            - for all intents and purposes, the template name and the stylesheet name are going to be the same for most cases.
        6. **`get_stylesheet_directory()`** - Retrieves stylesheet directory path for the active theme.
        7. **`get_stylesheet_directory_uri()`** - Retrieves stylesheet directory URI for the active theme.
        8. **`get_stylesheet_uri()`** - Retrieves stylesheet URI for the active theme.
            - the stylesheet file name is `style.css` which is appended to the stylesheet directory URI path.
            - see `get_stylesheet_directory_uri()`.
        9. **`get_locale_stylesheet_uri()`** - Retrieves the localized stylesheet URI.
            - the stylesheet directory for the localized stylesheet files are located, by default, in the base theme directory.
            - the name of the locale file will be the locale followed by '.css'.
            - if that does not exist, then the text direction stylesheet will be checked for existence, for example `ltr.css`.
            - the theme may change the location of the stylesheet directory by either using the `stylesheet_directory_uri` or `locale_stylesheet_uri` filters.
            - if you (me) want to change the location of the stylesheet files for the entire WordPress workflow, then change the former.
            - if you (me) just have the locale in a separate folder, then change the latter.
        10. **`get_template()`** - Retrieves name of the active theme.
        11. **`get_template_directory()`** - Retrieves template directory path for the active theme.
        12. **`get_template_directory_uri()`** - Retrieves template directory URI for the active theme.
        13. **`get_theme_roots()`** - Retrieves theme roots.
        14. **`register_theme_directory( $directory )`** - Registers a directory that contains themes.
        15. **`search_theme_directories( $force = false )`** - Searches all registered theme directories for complete and valid themes.
        16. **`get_theme_root( $stylesheet_or_template = '' )`** - Retrieves path to themes directory.
            - does not have trailing slash.
        17. **`get_theme_root_uri( $stylesheet_or_template = '', $theme_root = '' )`** - Retrieves URI for themes directory.
            - does not have trailing slash.
        18. **`get_raw_theme_root( $stylesheet_or_template, $skip_cache = false )`** - Gets the raw theme root relative to the content directory with no filters applied.
        19. **`locale_stylesheet()`** - Displays localized stylesheet link element.
        20. **`switch_theme( $stylesheet )`** - Switches the theme.
            - accept one (1) argument: `$stylesheet` of the theme.
            - also accepts an additional function signature of two (2) arguments: `$template` then `$stylesheet`.
            - this is for backward compatibility.
        21. **`validate_current_theme()`** - Checks that the active theme has the required files.
            - standalone themes need to have a `templates/index.html` or `index.php` template file.
            - child themes need to have a `Template` header in the `style.css` stylesheet.
            - does not initially check the default theme, which is the fallback and should alwas exists.
            - buf if it doesn't exist, it'll fall back to the latest core default theme that does exist.
            - will switch theme to the fallback theme if active theme does not validate.
            - you (me) can use the `validate_current_theme` filter to return false to disable this functionality.
        22. **`validate_theme_requirements( $stylesheet )`** - Validates the theme requirements for WordPress version and PHP version.
            - uses the information from `Requires at least` and `Requires PHP` headers defined in the theme's `style.css` file.
        23. **`get_theme_mods()`** - Retrieves all theme modifications.
        24. **`get_theme_mod( $name, $default = false )`** - Retrieves theme modification value for the active theme.
            - if the modification name does not exist and `$default` is a string, then the default will be passed through the `sprintf()` PHP function with the template directory URI as the first value and the stylesheet directory URI as the second value.
        25. **`set_theme_mod( $name, $value )`** - Updates theme modification value for the active theme.
        26. **`remove_theme_mod( $name )`** - Removes theme modification name from active theme list.
            - if removing the name also removes all elements, then the entire option will be removed.
        27. **`remove_theme_mods()`** - Removes theme modifications option for the active theme.
        28. **`get_header_textcolor()`** - Retrieves the custom header text color in 3- or 6-digit hexadecimal form.
        29. **`header_textcolor()`** - Displays the custom header text color in 3- or 6-digit hexadecimal form (minus the hash symbol).
        30. **`display_header_text()`** - Whether to display the header text.
        31. **`has_header_image()`** - Checks whether a header image is set or not.
        32. **`get_header_image()`** - Retrieves header image for custom header.
        33. **`get_header_image_tag( $attr = array() )`** - Creates image tag markup for a custom header image.
        34. **`the_header_image_tag( $attr = array() )`** - Displays the image markup for a custom header image.
        35. **`_get_random_header_data()`** - Gets random header image data from registered images in theme.
        36. **`get_random_header_image()`** - Gets random header image URL from registered images in theme.
        37. **`is_random_header_image( $type = 'any' )`** - Checks if random header image is in use.
            - always `TRUE` if user expressly chooses the option in `Appearance > Header`.
            - also `TRUE` if theme has multiple header images registered, no specific header image is chosen, and theme turns on random headers with `add_theme_support()`.
        38. **`header_image()`** - Displays header image URL.
        39. **`get_uploaded_header_images()`** - Gets the header images uploaded for the active theme.
        40. **`get_custom_header()`** - Gets the header image data.
        41. **`register_default_headers( $headers )`** - Registers a selection of default headers to be displayed by the custom header admin UI.
        42. **`unregister_default_headers( $header )`** - Unregisters default headers.
            - this function **must be called** after `register_default_headers()` has already added the header you (me) want to remove.
        43. **`has_header_video()`** - Checks whether a header video is set or not.
        44. **`get_header_video_url()`** - Retrieves header video URL for custom header.
            - uses a local video if present, or falls back to an external video.
        45. **`the_header_video_url()`** - Displays header video URL.
        46. **`get_header_video_settings()`** - Retrieves header video settings.
        47. **`has_custom_header()`** - Checks whether a custom header is set or not.
        48. **`is_header_video_active()`** - Checks whether the custom header video is eligible to show on the current page.
        49. **`get_custom_header_markup()`** - Retrieves the markup for a custom header.
            - the container `DIV` will always be returned in the `Customizer` preview.
        50. **`the_custom_header_markup()`** - Prints the markup for a custom header.
            - a container `DIV` will always be printed in the `Customizer` preview.`
        51. **`get_background_image()`** - Retrieves background image for custom background.
        52. **`background_image()`** - Displays background image path.
        53. **`get_background_color()`** - Retrieves value for custom background color.
        54. **`background_color()`** - Displays background color value.
        55. **`_custom_background_cb()`** - Default custom background callback.

                    ?>
                    <style <?= $type_attr ?> id="custom-backround-css">
                        body.custom-background { <?= trim( $style ) ?> }
                    </style>
                        <?php

        56. **`wp_custom_css_cb()`** - Renders the Custom CSS style element.
        57. **`wp_get_custom_css_post( $stylesheet = '' )`** - Fetches the `custom_css` post for a given theme.
        58. **`wp_get_custom_css( $stylesheet = '' )`** - Fetches the saved Custom CSS content for rendering.
        59. **`wp_update_custom_css_post( $css, $args = array() )`** - Updates the `custom_css` post for a given theme.
            - inserts a `custom_css` post when one doesn't yet exist.
        60. **`add_editor_style( $stylesheet = 'editor-style.css' )`** - Adds callback for custom TinyMCE editor stylesheets.
            - the parameter `$stylesheet` is the name of the stylesheet, relative to the theme root.
            - it also accepts an array of stylesheets.
            - it is optional and defaults to `editor-style.css`
            - this function automatically adds another stylesheet with `-rtl` prefix, e.g. editor-style-rtl.css.
            - if that file doesn't exist, it is removed befored adding the stylesheet(s) to TinyMCE.
            - if an array of stylesheets is passed to `add_editor_style()`, RTL is only added for the first stylesheet.
            - since version 3.4 the TinyMCE body has .rtl CSS class.
            - it is a better option to use that class and add any RTL styles to the main stylesheet.
        61. **`remove_editor_styles()`** - Removes all visual editor stylesheets.
        62. **`get_editor_stylesheets()`** - Retrieves any registered editor stylesheet URLs.
        63. **`get_theme_starter_content()`** - Expands a theme's starter content configuration using core-provided data.
        64. **`add_theme_support( $feature, ...$args )`** - Registers theme support for a given feature.
            - must be called in the theme's `functions.php` file to work.
            - if attached to a hook, it must be `after_setup_theme`.
            - the `init` hook may be too late for some features.
        65. **`_custom_header_background_just_in_time()`** - Registers the internal custom header and background routines.
        66. **`_custom_logo_header_styles()`** - Adds CSS to hide header text for custom logo, based on `Customizer` setting.
        67. **`get_theme_support( $feature, ...$args )`** - Gets the theme support arguments passed when registering that support.
        68. **`remove_theme_support( $feature )`** - Allows a theme to de-register its support of a certain feature.
            - should be called in the theme's `functions.php` file.
            - see `add_theme_support()` for the list of possible values.
        69. **`_remove_theme_support( $feature )`** - Do not use. Removes theme support internally without knowledge of those not used by themes directly.
        70. **`current_theme_supports( $feature, ...$args )`** - Checks a theme's support for a given feature.
        71. **`require_if_theme_supports( $feature, $include )`** - Checks a theme's support for a given feature before loading the functions which implement it.
        72. **`register_theme_feature( $feature, $args = array() )`** - Registers a theme feature for use in `add_theme_support()`
            - this does not indicate that the active theme supports the feature, it only describes the feature's supported options.
        73. **`get_registered_theme_features()`** - Gets the list of registered theme features.
        74. **`get_registered_theme_feature( $feature )`** - Gets the the registration config for a theme feature.
        75. **`_delete_attachment_theme_mod( $id )`** - Checks an attachment being deleted to see if it's a header or background image.
            - if `TRUE` it removes the theme modification which would be pointing at the deleted attachment.
        76. **`check_theme_switched()`** - Checks if a theme has been changed and runs `after_switch_theme` hook on the next WP load.
        77. **`_wp_customize_include()`** - Includes and instantiates the `WP_Customize_Manager` class.
            - loads the `Customizer` at `plugins_loaded` when accessing the `customize.php` admin page or when any request includes a `wp_customize=on` param or a `customize_changeset` param (a UUID).
            - this param is a signal for whether to bootstrap the `Customizer` when WordPress is loading, especially in the `Customizer` preview or when making `Customizer Ajax` requests for widgets or menus.
        78. **`_wp_customize_publish_changeset( $new_status, $old_status, $changeset_post )`** - Publishes a snapshot's changes.
        79. **`_wp_customize_changeset_filter_insert_post_data( $post_data, $supplied_post_data )`** - Filters changeset post data upon insert to ensure `post_name` is intact.
            - this is needed to prevent the `post_name` from being dropped when the post is transitioned into pending status by a contributor.
        80. **`_wp_customize_loader_settings()`** - Adds settings for the customize-loader script.
        81. **`wp_customize_url( $stylesheet = '' )`** - Returns a URL to load the `Customizer`.
        82. **`wp_customize_support_script()`** - Prints a script to check whether or not the `Customizer` is supported, and apply either the no-customize-support or customize-support class to the body.
            - this function MUST be called inside the body tag.
            - ideally, call this function immediately after the body tag is opened.
            - this prevents a flash of unstyled content.
            - it is also recommended that you (me) add the 'no-customize-support' class to the body tag by default.
        83. **`is_customize_preview()`** - Whether the site is being previewed in the `Customizer`.
        84. **`_wp_keep_alive_customize_changeset_dependent_auto_drafts( $new_status, $old_status, $post )`** - Makes sure that auto-draft posts get their `post_date` bumped or status changed to draft to prevent premature garbage-collection.
            - when a changeset is updated but remains an auto-draft, ensure the `post_date` for the auto-draft posts remains the same so that it will be garbage-collected at the same time by `wp_delete_auto_drafts()`.
            - otherwise, if the changeset is updated to be a draft then update the posts to have a far-future `post_date` so that they will never be garbage collected unless the changeset post itself is deleted.
            - when a changeset is updated to be a persistent draft or to be scheduled for publishing, then transition any dependent auto-drafts to a draft status so that they likewise will not be garbage-collected but also so that they can be edited in the admin before publishing since there is not yet a post/page editing flow in the `Customizer`.
        85. **`create_initial_theme_features()`** - Creates the initial theme features when the `setup_theme` action is fired.
        86. **`wp_is_block_theme()`** - Returns whether the active theme is a block-based theme or not.
        87. **`wp_theme_get_element_class_name( $element )`** - Given an element name, returns a class name.
            - alias of `WP_Theme_JSON::get_element_class_name`.
        88. **`_add_default_theme_supports()`** - Adds default theme supports for block themes when the `setup_theme` action fires.

37. **`wp-includes/class-wp-theme.php`**
    - WP_Theme Class
    - this module contains final class `WP_Theme` implements `ArrayAccess` definition

38. ...
NAV-DIR: */wp-includes

END-NAV-DIR: */wp-includes