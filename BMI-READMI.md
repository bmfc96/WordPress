# Documents

## File(s) responsibility|function

*=WordPress

DIR: */...

     */wp-includes

     */wp-content

     */wp-admin
     
    (17 files)

NAV-DIR: */... (17 files)
1. **index.php**
    - Front to application, LOAD 'wp-blog-header.php'
    - FLOW:
        1. Define constant WP_USE_THEMES as true
        2. LOAD _DIR_ . '/wp-blog-header.php' (require)

2. **wp-blog-header.php**
    - Loads WordPress ENVIRONMENT & TEMPLATE
    - FLOW:
        1. Load WordPress library (wp-load.php - require-once - _DIR_)
        2. Setup WordPress query (wp();)
        3. Load THEME template (ABSPATH . WPINC . '/template-loader.php' - require-once)
            Notes:
            - ABSPATH - a constant defined in 'wp-load.php'
            - WPINC - a constant defined in 'wp-load.php' on the event 'wp-config.php' doesn't exist

3. **wp-load.php**
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

4. **wp-config-sample.php**
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

5. **wp-settings.php**
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
            '/class-wp-walker.php', '/class-wp-ajax-response.php', '/capabilities.php',
            '/class-wp-roles.php', '/class-wp-role.php', '/class-wp-user.php',
            '/class-wp-query.php', '/query.php', '/class-wp-date-query.php',
            '/theme.php', '/class-wp-theme.php', '/class-wp-theme-json-schema.php',
            '/class-wp-theme-json-data.php', '/class-wp-theme-json.php',
            '/class-wp-theme-json-resolver.php', '/global-styles-and-settings.php',
            '/class-wp-block-template.php', '/block-template-utils.php',
            '/block-template.php', '/theme-templates.php', '/template.php',
            '/https-detection.php', '/https-migration.php', '/class-wp-user-request.php',
            '/user.php', '/class-wp-user-query.php', '/class-wp-session-tokens.php',
            '/class-wp-user-meta-session-tokens.php', '/class-wp-metadata-lazyloader.php',
            '/general-template.php', '/link-template.php', '/author-template.php',
            '/robots-template.php', '/post.php', '/class-walker-page.php',
            '/class-walker-page-dropdown.php', '/class-wp-post-type.php',
            '/class-wp-post.php', '/post-template.php', '/revision.php',
            '/post-formats.php', '/post-thumbnail-template.php', '/category.php',
            '/class-walker-category.php', '/class-walker-category-dropdown.php',
            '/category-template.php', '/comment.php', '/class-wp-comment.php',
            '/class-wp-comment-query.php', '/class-walker-comment.php',
            '/comment-template.php', '/rewrite.php', '/class-wp-rewrite.php',
            '/feed.php', '/bookmark.php', '/bookmark-template.php', '/kses.php',
            '/cron.php', '/deprecated.php', '/script-loader.php', '/taxonomy.php',
            '/class-wp-taxonomy.php', '/class-wp-term.php', '/class-wp-term-query.php',
            '/class-wp-tax-query.php', '/update.php', '/canonical.php', '/shortcodes.php',
            '/embed.php', '/class-wp-embed.php', '/class-wp-oembed.php',
            '/class-wp-oembed-controller.php', '/media.php', '/http.php',
            '/class-wp-http.php', '/class-wp-http-streams.php', '/class-wp-http-curl.php',
            '/class-wp-http-cookie.php', '/class-wp-http-encoding.php',
            '/class-wp-http-response.php', '/class-wp-http-requests-response.php',
            '/class-wp-http-requests-hooks.php', '/widgets.php', '/class-wp-widget.php',
            '/class-wp-widget-factory.php', '/nav-menu-template.php', '/nav-menu.php',
            '/admin-bar.php', '/class-wp-application-passwords.php', '/rest-api.php',
            '/rest-api/class-wp-rest-server.php', '/rest-api/class-wp-rest-response.php',
            '/rest-api/class-wp-rest-request.php', '/rest-api/endpoints/class-wp-rest-controller.php',
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

6. **wp-includes/versions.php**
    - This module is responsible on:
        1. Contains version information of current WP release
        2. Hold current version number for WP core - (used to bust caches and enable dev. mode for scripts when running from /src dir.) [ $wp_version = '6.2-alpha-54952' ]
        3. Hold WP DB revision, increments when changes made to WP DB schema - ( $wp_db_version = 53496; )
        4. Hold TinyMCE version - ( $tinymce_version = '49110-20201110'; )
        5. Hold required PHP version - ( $required_php_version = '5.6.20'; )
        6. Hold required MySQL version - ( $required_mysql_version = '5.0'; )

7. **wp-includes/load.php**
    - This module are needed to load WP
    - FUNCTIONS:
        1. **wp_get_server_protocol()**: $protocol - Return HTTP protocol sent by server
        2. **wp_fix_server_vars()**: void - Fix `$_SERVER` vars. for various setups
        3. **wp_populate_basic_auth_from_authorization_header()**: void - Populates Basic Auth server details from Authorization header
            Some server running in CGI or FastCGI mode don't pass Authorization header on to WP
            If it's been rewritten to `HTTP_AUTHORIZATION` header,
            fill in proper $_SERVER vars. instead
        4. **wp_check_php_mysql_versions()** - Check for required PHP version and MySQL extension or database drop-in, dies if requirement not met
        5. **wp_get_environment_type()**: str - Get current environment type (return string current env. type)
            Type can be set via `WP_ENVIRONMENT_TYPE` global system variable
            or a constant of same name
            Possible values are 'local', 'development', 'staging', and 'production'
            If not set, 'type' default is 'production'
        6. **wp_favicon_request()**: void - Don't load all of WP when handling a favicon.ico request
            Instead, send headers for a zero-length favicon and bail
        7. **wp_maintenance()**: void - Die with maintenance message when conditions met
            Default message can be replaced using a drop-in (maintenance.php in wp-content directory)
        8. **wp_is_maintenance_mode()**: true|false - Check if maintenance mode is enabled 
            Checks for a file in WP root dir. named '.maintenance'
            This file will contain var. $upgrading,
            set to the time the file was created.
            If the file was created < 10 mins. ago,
            WP is in maintenance mode
        9. **timer_float()**: float - Get time elapsed so far during this PHP script
            Uses REQUEST_TIME_FLOAT that appeared in PHP 5.4.0
        10. **timer_start()**: bool always - Start WP micro-timer
        11. **timer_stop()**: string - Get/display time from the page start to when function is called
        12. **wp_debug_mode()**: void - Set PHP error reporting based on WP debug settings
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
        13. **wp_set_lang_dir()**: void - Set location of language directory
            - define `WP_LANG_DIR` constant in `wp-config.php` to set directory manually
            - language directory exists within `WP_CONTENT_DIR` ? use it : assumed language directory live in `WPINC`
        14. **require_wp_db()**: void - Load database class file and instantiate `$wpdb` global
        15. **wp_set_wpdb_vars()**: void - Set database table prefix and format specifiers for database table columns
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

8. **wp-includes/class-wp-paused-extensions-storage.php**
    - Error Protection API: WP_Paused_Extensions_Storage class (Core class used for storing paused extensions.)
    - This module contains WP_Paused_Extensions_Storage class definition

9. **wp-includes/class-wp-fatal-error-handler.php**
    - Error Protection API: WP_Fatal_Error_Handler class (Core class used as the default shutdown handler for fatal errors.)
    - A drop-in `fatal-error-handler.php` can be used to override the instance of this class and use a custom implementation for the fatal error handler that WP registers.
    - The custom class should extend this class and can override its methods individually as necessary.
    - The file must return the instance of the class that should be registered.
    - This module contains `WP_Fatal_Error_Handler` class definition

10. **wp-includes/class-wp-recovery-mode-cookie-service.php**
    - Error Protection API: WP_Recovery_Mode_Cookie_Service class (Core class used to set, validate, and clear cookies that identify a Recovery Mode session.)
    - This module contains final `WP_Recovery_Mode_Cookie_Service` class definition

11. **wp-includes/class-wp-recovery-mode-key-service.php**
    - Error Protection API: WP_Recovery_Mode_Key_Service class (Core class used to generate and validate keys used to enter Recovery Mode.)
    - This module contains final `WP_Recovery_Mode_Key_Service` class definition

12. **wp-includes/class-wp-recovery-mode-link-service.php**
    - Error Protection API: WP_Recovery_Mode_Link_Handler class (Core class used to generate and handle recovery mode links.)
    - This module contains `WP_Recovery_Mode_Link_Service` class definition

13. **wp-includes/class-wp-recovery-mode-email-service.php**
    - Error Protection API: WP_Recovery_Mode_Email_Link class (Core class used to send an email with a link to begin Recovery Mode.)
    - This module contains final `WP_Recovery_Mode_Email_Service` class definition

14. **wp-includes/class-wp-recovery-mode.php**
    - Error Protection API: WP_Recovery_Mode class (Core class used to implement Recovery Mode.)
    - This module contains `WP_Recovery_Mode` class definition

15. **wp-includes/error-protection.php**
    - Error Protection API: Functions
    - This module contains the following functions:
        1. **wp_paused_plugins()**: WP_Paused_Extensions_Storage - Get the instance for storing paused plugins.
        2. **wp_paused_themes()**: WP_Paused_Extensions_Storage - Get the instance for storing paused extensions.
        3. **wp_get_extension_error_description( $error )**: string - Get a human readable description of an extension's error.
        4. **wp_register_fatal_error_handler()**: void - Registers shutdown handler for fatal errors.
        5. **wp_is_fatal_error_handler_enabled()**: bool - Checks whether fatal error handler is enabled.
        6. **wp_recovery_mode()**: WP_Recovery_Mode - Access WP Recovery Mode instance.

16. **wp-includes/default-constants.php**
    - This module defines constants and global variables that can be overridden, generally in `wp-config.php`
    - This module contains the following functions:
        1. **wp_initial_constants()**: void - Defines initial WP constants.
        2. **wp_plugin_directory_constants()**: void - Defines plugin directory WP constants.
        3. **wp_cookie_constants()**: void - Defines cookie-related WP constants.
        4. **wp_ssl_constants()**: void - Defines SSL-related WP constants.
        5. **wp_functionality_constants()**: void - Defines functionality-related WP constants.
        6. **wp_templating_constants()**: void - Defines templating-related WP constants.

17. **wp-includes/plugin.php**
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

NAV-DIR: */wp-includes

END-NAV-DIR: */wp-includes