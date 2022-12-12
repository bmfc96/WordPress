# Documents

## File(s) responsibility|function

*=WordPress

### DIR:

    */...

    */wp-includes

    */wp-content

    */wp-admin
     
    (17 files)

#### NAV-DIR: */... (17 files)

<details>
<summary>1. <b><i>index.php</i></b> - Front to application, load <code>wp-blog-header.php</code></summary>

- **NOTES**
    - ...

    <details>
    <summary><h4>FLOW</h4></summary>

    1. Define constant `WP_USE_THEMES` as `TRUE`
    ```php
    define( 'WP_USE_THEMES', true );
    ```

    2. Load `/wp-blog-header.php` (require)
    ```php
    require __DIR__ . '/wp-blog-header.php';
    ```
    </details>
</details>

<details>
<summary>2. <b><i>wp-blog-header.php</i></b> - Loads WordPress ENVIRONMENT & TEMPLATE</summary>

- **NOTES**
    - `ABSPATH` = a constant defined in `wp-load.php`
    - `WPINC` = a constant defined in `wp-load.php` on the event `wp-config.php` doesn't exist
    
    <details>
    <summary><h4>FLOW</h4></summary>

    1. Load WordPress library (wp-load.php - require-once - _DIR_)
    ```php
    require_once( __DIR__ . 'wp-load.php' );
    ```
    2. Setup WordPress query
    ```php
    wp();
    ```
    3. Load THEME template
    ```php
    require_once( ABSPATH . WPINC . '/template-loader.php' );
    ```    
    </details>
</details>

<details>
<summary>3. <b><i>wp-load.php</i></b> - Bootstrapping/Self-starting process file</summary>

- **NOTES**
    - set `ABSPATH` constant
    - load `wp-config.php` __(which load `wp-settings.php` which will set up the **WordPress environment**)__
    - if `wp-config.php` is not found, an error will be displayed **asking the visitor to set up the** `wp-config.php`
    - will also search `wp-config.php` in **WordPress**' parent directory
    
    <details>
    <summary><h4>FLOW</h4></summary>

    1. Define constant `ABSPATH` as `__DIR__ . '/'`
        ```php
        define( 'ABSPATH', __DIR__ . '/' );
        ```

    2. Check function exist `error_reporting()`, if `TRUE` initialize `error_reporting` to a known set of levels
    ```php
    if (function_exists( 'error_reporting' )) {
        error_reporting();
    }
    ```

    3. Check if file exist `ABSPATH . 'wp-config.php'`, if `TRUE` require-once `ABSPATH . 'wp-config.php'`
        ```php
        if (file_exists(ABSPATH . 'wp-config.php')) {
            require_once ABSPATH . 'wp-config.php';
        }
        ```

    4. Else...
        ```php
        else if (
                @file_exists( dirname(ABSPATH) . '/wp-config.php' )
          and ! @file_exists( dirname(ABSPATH) . '/wp-settings.php' )
        ) {

            require_once dirname(ABSPATH) . '/wp-config.php'

        } else {
            
            // 'wp-config.php' doesn't exist
            define( 'WPINC', 'wp-includes' );
            
            require_once ABSPATH . WPINC . '/load.php';

            // Standardize $_SERVER variables across setups
            wp_fix_server_vars();

            require_once ABSPATH . WPINC . '/functions.php';

            // Check if $_SERVER['REQUEST_URI'] does not contain 'setup-config'
            // TODO:: ...

            define( 'WP_CONTENT_DIR', ABSPATH . 'wp-content' );

            require_once ABSPATH . WPINC . '/version.php';

            wp_check_php_mysql_versions();
            wp_load_translations_early();

            // Die with error message... (sprintf())
            // TODO:: ...

            wp_die();
        } // END-COND. else
        ```
    </details>
</details>

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
    - WP_Theme class
    - this module contains final class `WP_Theme` implements `ArrayAccess` definition

38. **`wp-includes/class-wp-theme-json-schema.php`**
    - WP_Theme_JSON_Schema class
    - class that migrates a given `theme.json` structure to the latest schema.
    - this class is for internal core usage and is not supposed to be used by extenders (plugins and/or themes).
    - this is a low-level API that may need to do breaking changes
    - please, use `get_global_settings`, `get_global_styles`, and `get_global_stylesheet` instead.
    - this module contains `wP_Theme_JSON_Schema` class definition

39. **`wp-includes/class-wp-theme-json-data.php`**
    - WP_Theme_JSON_Data class
    - class to provide access to update a `theme.json` structure.
    - this module contains `WP_Theme_JSON_Data` class definition

40. **`wp-includes/class-wp-theme-json.php`**
    - WP_Theme_JSON class
    - class that encapsulates the processing of structures that adhere to the `theme.json` spec.
    - this class is for internal core usage and is not supposed to be used by extenders (plugins and/or themes).
    - this is a low-level API that may need to do breaking changes.
    - please, use `get_global_settings`, `get_global_styles`, and `get_global_stylesheet` instead.
    - this module contains `WP_Theme_JSON` class definition

41. **`wp-includes/class-wp-theme-json-resolver.php`**
    - WP_Theme_JSON_Resolver class
    - class that abstracts the processing of the different data sources for site-level config and offers an API to work with them.
    - this class is for internal core usage and is not supposed to be used by extenders (plugins and/or themes).
    - this is a low-level API that may need to do breaking changes.
    - please, use `get_global_settings`, `get_global_styles`, and `get_global_stylesheet` instead.
    - this module contains `WP_Theme_JSON_Resolver` class definition

42. **`wp-includes/global-styles-and-settings.php`**
    - APIs to interact with global settings & styles.
    - @package WordPress
    - this module contains the following functions:
        1. **`wp_get_global_settings( $path = array(), $context = array() )`** - Gets the settings resulting of merging core, theme, and user data.
        2. **`wp_get_global_styles( $path = array(), $context = array() )`** - Gets the styles resulting of merging core, theme, and user data.
        3. **`wp_get_global_stylesheet( $types = array() )`** - Returns the stylesheet resulting of merging core, theme, and user data.
        4. **`wp_get_global_styles_svg_filters()`** - Returns a string containing the SVGs to be referenced as filters (duotone).
        5. **`wp_get_global_styles_for_blocks()`** - Adds global style rules to the inline style for each block.

43. **`wp-includes/class-wp-block-template.php`**
    - Blocks API: WP_Block_Template class
    - @package WordPress
    - @since 5.8.0
    - class representing a block template.
    - this module contains `WP_Block_Template` class definition

44. **`wp-includes/block-template-utils.php`**
    - Utilities used to fetch and create templates and template parts.
    - @package WordPress
    - @since 5.8.0
    - FLOW:
        1. Define constants for supported `wp_template_part_area` taxonomy.

                if ( ! defined('WP_TEMPLATE_PART_AREA_HEADER') ) {
                    define('WP_TEMPLATE_PART_AREA_HEADER', 'header');
                }
                if ( ! defined('WP_TEMPLATE_PART_AREA_FOOTER') ) {
                    define('WP_TEMPLATE_PART_AREA_FOOTER', 'footer');
                }
                if ( ! defined('WP_TEMPLATE_PART_AREA_SIDEBAR') ) {
                    define('WP_TEMPLATE_PART_AREA_SIDEBAR', 'sidebar');
                }
                if ( ! defined('WP_TEMPLATE_PART_AREA_UNCATEGORIZED') ) {
                    define('WP_TEMPLATE_PART_AREA_UNCATEGORIZED', 'uncategorized');
                }
    - this module contains the following functions:
        1. **`get_block_theme_folders( $theme_stylesheet = null )`** - For backward compatibility reasons, block themes might be using block-templates or block-template-parts, this function ensures we fallback to these folders properly.
        2. **`get_allowed_block_template_part_areas()`** - Returns a filtered list of allowed area values for template parts.
        3. **`get_default_block_template_types()`** - Returns a filtered list of default template types, containing their localized titles and descriptions.
        4. **`_filter_block_template_part_area( $type )`** - Checks whether the input `area` is a supported value.
            - returns the input if supported, otherwise returns the `uncategorized` value.
        5. **`_get_block_templates_paths( $base_directory )`** - Finds all nested template part file paths in a theme's directory.
        6. **`_get_block_template_file( $template_type, $slug )`** - Retrieves the template file from the theme for a given slug.
        7. **`_get_block_templates_files( $template_type )`** - Retrieves the template files from the theme.
        8. **`_add_block_template_info( $template_item )`** - Attempts to add custom template information to the template item.
        9. **`_add_block_template_part_area_info( $template_info )`** - Attempts to add the template part's area information to the input template.
        10. **`_flatten_blocks( &$blocks )`** - Returns an array containing the references of the passed blocks and their inner blocks.
        11. **`_inject_theme_attribute_in_block_template_content( $template_content )`** - Parses `wp_template` content and injects the active theme's stylesheet as a theme attribute into each `wp_template_part`.
        12. **`_remove_theme_attribute_in_block_template_content( $template_content )`** - Parses a block template and removes the theme attribute from each template part.
        13. **`_build_block_template_result_from_file( $template_file, $template_type )`** - Builds a unified template object based on a theme file.
        14. **`_wp_build_title_and_description_for_single_post_type_block_template( $post_type, $slug, WP_Block_Template $template )`** - Builds the title and description of a post-specific template based on the underlying referenced post.
            - mutates the underlying template object.
        15. **`_wp_build_title_and_description_for_taxonomy_block_template( $taxonomy, $slug, WP_Block_Template $template )`** - Builds the title and description of a taxonomy-specific template based on the underlying entity referenced.
            - mutates the underlying template object.
        16. **`_build_block_template_result_from_post( $post )`** - Builds a unified template object based on a post Object.
        17. **`get_block_templates( $query = array(), $template_type = 'wp_template' )`** - Retrieves a list of unified template objects based on a query.
        18. **`get_block_template( $id, $template_type = 'wp_template' )`** - Retrieves a single unified template object using its id.
        19. **`get_block_file_template( $id, $template_type = 'wp_template' )`** - Retrieves a unified template object based on a theme file.
        20. **`block_template_part( $part )`** - Prints a block template part.
        21. **`block_header_area()`** - Prints the header block template part.
        22. **`block_footer_area()`** - Prints the footer block template part.
        23. **`wp_is_theme_directory_ignored( $path )`** - Determines whether a theme directory should be ignored during export.
        24. **`wp_generate_block_templates_export_file()`** - Creates an export of the current templates and template parts from the site editor at the specified path in a ZIP file.
        25. **`get_template_hierarchy( $slug, $is_custom = false, $template_prefix = '' )`** - Gets the template hierarchy for the given template slug to be created.
            - always add `index` as the last fallback template.

45. **`wp-includes/block-template.php`**
    - Block template loader functions
    - @package WordPress
    - this module contains the following functions:
        1. **`_add_template_loader_filters()`** - Adds necessary filters to use `wp_template` posts instead of theme template files.
        2. **`locate_block_template( $template, $type, array $templates )`** - Finds a block template with equal or higher specificity than a given PHP template file.
            - internally, this communicates the block content that needs to be used by the template canvas through a global variable.
        3. **`resolve_block_template( $template_type, $template_hierarchy, $fallback_template )`** - Returns the correct `wp_template` to render for the request template type.
        4. **`_block_template_render_title_tag()`** - Displays title tag with content, regardless of whether theme has title-tag support.
        5. **`get_the_block_template_html()`** - Returns the markup for the current template.
        6. **`_block_template_viewport_meta_tag()`** - Renders a `viewport` meta tag.
            - this is hooked into `wp_head` to decouple its output from the default template canvas.
        7. **`_strip_template_file_suffix( $template_file )`** - Strips .php or .html suffix from template file names.
        8. **`_block_template_render_without_post_block_context( $context )`** - Removes post details from block context when rendering a block template.
        9. **`_resolve_template_for_new_post( $wp_query )`** - Sets the current `WP_Query` to return auto-draft posts.
            - the auto-draft status indicates a new post, so allow the `WP_Query` instance to return an auto-draft post for template resolution when editing a new post.
        10. **`_resolve_home_block_template()`** - Returns the correct template for the site's home page.

46. **abc**
    - ...

<details>
<summary>46. <b><i>wp-includes/theme-templates.php</i></b></summary>

- **NOTES**
    - this module contains the following functions:
        1. **`wp_set_unique_slug_on_create_template_part( $post_id )`** - Sets a custom slug when creating auto-draft template parts.
            - this is only needed for auto-drafts created by the regular WP editor.
            - if this page is to be removed, this will not be necessary.
        2. **`wp_filter_wp_template_unique_post_slug( $override_slug, $slug, $post_ID, $post_status, $post_type )`** - Generates a unique slug for templates.
        3. **`the_block_template_skip_link()`** - Prints the skip-link script & styles.
        4. **`wp_enable_block_templates()`** - Enables the block templates (editor mode) for themes with `theme.json` by default.

    <details>
    <summary><h4>FLOW</h4></summary>
    </details>
</details>

<details>
<summary>47. <b><i>wp-includes/template.php</i></b></summary>

- **NOTES**
    - Template loading functions.

            @package WordPress
            @subpackage Template

    <details>
    <summary>FUNCTIONS</summary>

    1. `get_query_template( $type, $templates = array() )` - Retrieves path to a template.

        ```php
        /**
         * - used to quickly retrieve the path of a template without including the file extension.
         * - will also check the parent theme, if the file exists, with the use of `locate_template()`.
         * - allows for more generic template location without the use of the other `get_*_template()` functions.
         * 
         * @since 1.5.0
         * 
         * @param string    $type       Filename without extension.
         * @param string[]  $templates  An optional list of template candidates.
         * @return string Full path to template file.
         */
        ```

    2. `get_index_template()` - Retrieves path of index template in current or parent template.
        ```php
        /**
         * - the template hierarchy and template path are filterable via the
         * {@see '$type_template_hierarchy'} and {@see '$type_template'}
         * dynamic hooks, where `$type` is 'index'.
         * 
         * @since 3.0.0
         * 
         * @see get_query_template()
         * 
         * @return string Full path to index template file.
         */
        ```
    3. `get_404_template()` - Retrieves path of 404 template in current or parent template.
        ```php
        /**
         * - the template hierarchy and template path are filterable via the
         *   {@see '$type_template_hierarchy'} and {@see '$type_template'}
         *   dynamic hooks, where `$type` is '404'.
         * 
         * @since 1.5.0
         * 
         * @see get_query_template()
         * 
         * @return string Full path to 404 template file.
         */
        ```
    4. `get_archive_template()` - Retrieves path of archive template in current or parent template.
        ```php
        /**
         * - the template hierarchy and template path are filterable via the
         *   {@see '$type_template_hierarchy'} and {@see '$type_template'}
         *   dynamic hooks, where `$type` is 'archive'.
         * 
         * @since 1.5.0
         * 
         * @see get_query_template()
         * 
         * @return string Full path to archive template file.
         */
        ```
    5. `get_post_type_archive_template()` - Retrieves path of post type archive template in current or parent template.
        ```php
        /**
         * - the template hierarchy and template path are filterable via the
         *   {@see '$type_template_hierarchy'} and {@see '$type_template'}
         *   dynamic hooks, where `$type` is 'archive'.
         * 
         * @since 3.7.0
         * 
         * @see get_archive_template()
         * 
         * @return string Full path to archive template file.
         */
        ```
    6. `get_author_template()` - Retrieves path of author template in current or parent template.
        ```php
        /**
         * The hierarchy for this template looks like:
         * 
         * 1. author-{nicename}.php
         * 2. author-1.php
         * 3. author.php
         * 
         * An example of this is:
         * 
         * 1. author-john.php
         * 2. author-1.php
         * 3. author.php
         * 
         * - the template hierarchy and path are filterable via the
         *   {@see '$type_template_hierarchy'} and {@see '$type_template'}
         *   dynamic hooks, where `$type` is 'author'.
         * 
         * @since 1.5.0
         * 
         * @see get_query_template()
         * 
         * @return string Full path to author template file.
         */
        ```
    7. `get_category_template()` - Retrieves path of category template in current or parent template.
        ```php
        /**
         * The hierarchy for this template looks like:
         * 
         * 1. category-{slug}.php
         * 2. category-{id}.php
         * 3. category.php
         * 
         * An example of this is:
         * 
         * 1. category-news.php
         * 2. category-2.php
         * 3. category.php
         * 
         * - the template hierarchy and template path are filterable via the
         *   {@see '$type_template_hierarchy'} and {@see '$type_template'}
         *   dynamic hooks, where `$type` is 'category'.
         * 
         * @since 1.5.0
         * @since 4.7.0 The decoded form of `category-{slug}.php` was added to the top of the
         *              template hierarchy when the category slug contains multibyte characters.
         * 
         * @see get_query_template()
         * 
         * @return string Full path to category template file.
         */
        ```
    8. `get_tag_template()` - Retrieves path of tag template in current or parent template.
        ```php
        /**
         * The hierarchy for this template looks like:
         * 
         * 1. tag-{slug}.php
         * 2. tag-{id}.php
         * 3. tag.php
         * 
         * An example of this is:
         * 
         * 1. tag-wordpress.php
         * 2. tag-3.php
         * 3. tag.php
         * 
         * - the template hierarchy and template path are filterable via the
         *   {@see '$type_template_hierarchy'} and {@see '$type_template'}
         *   dynamic hooks, where `$type` is 'tag'.
         * 
         * @since 2.3.0
         * @since 4.7.0 The decoded form of `tag-{slug}.php` was added to the top of the
         *              template hierarchy when the tag slug contains multibyte characters.
         * 
         * @see get_query_template()
         * 
         * @return string Full path to tag template file.
         */
        ```
    9. `get_taxonomy_template()` - Retrieves path of custom taxonomy term template in current or parent template.
        ```php
        /**
         * The hierarchy for this template looks like:
         * 
         * 1. taxonomy-{taxonomy_slug}-{term_slug}.php
         * 2. taxonomy-{taxonomy_slug}.php
         * 3. taxonomy.php
         * 
         * An example of this is:
         * 
         * 1. taxonomy-location-texas.php
         * 2. taxonomy-location.php
         * 3. taxonomy.php
         * 
         * - the template hierarchy and template path are filterable via the
         *   {@see '$type_template_hierarchy'} and {@see '$type_template'}
         * dynamic hooks, where `$type` is 'taxonomy'.
         * 
         * @since 2.5.0
         * @since 4.7.0 The decoded form of `taxonomy-{taxonomy_slug}-{term_slug}.php` was added
         *              to the top of the template hierarchy when the term slug contains
         *              multibyte characters.
         * 
         * @see get_query_template()
         * 
         * @return string Full path to custom taxonomy term template file.
         */
        ```
    10. `get_date_template()` - Retrieves path of date template in current or parent template.
        ```php
        /**
         * - the template hierarchy and template path are filterable via the
         *   {@see '$type_template_hierarchy'} and {@see '$type_template'}
         *   dynamic hooks, where `$type` is 'date'.
         * 
         * @since 1.5.0
         * 
         * @see get_query_template()
         * 
         * @return string Full path to date template file.
         */
        ```
    11. `get_home_template()` - Retrieves path of home template in current or parent template.
        ```php
        /**
         * - the template hierarchy and template path are filterable via the
         *   {@see '$type_template_hierarchy'} and {@see '$type_template'}
         *   dynamic hooks, where `$type` is 'home'.
         * 
         * @since 1.5.0
         * 
         * @see get_query_template()
         * 
         * @return string Full path to home template file.
         */
        ```
    12. `get_front_page_template()` - Retrieves path of front page template in current or parent template.
        ```php
        /**
         * - the template hierarchy and template path are filterable via the
         *   {@see '$type_template_hierarchy'} and {@see '$type_template'}
         *   dynamic hooks, where `$type` is 'frontpage'.
         * 
         * @since 3.0.0
         * 
         * @see get_query_template()
         * 
         * @return string Full path to front page template file.
         */
        ```
    13. `get_privacy_policy_template()` - Retrieves path of Privacy Policy page template in current or parent template.
        ```php
        /**
         * - the template hierarchy and template path are filterable via the
         *   {@see '$type_template_hierarchy'} and {@see '$type_template'}
         *   dynamic hooks, where `$type` is 'privacypolicy'.
         * 
         * @since 5.2.0
         * 
         * @see get_query_template()
         * 
         * @return string Full path to privacy policy template file.
         */
        ```
    14. `get_page_template()` - Retrieves path of page template in current or parent template.
        ```php
        /**
         * Note: For block themes, use `locate_block_template()` function instead.
         * 
         * The hierarchy for this template looks like:
         * 
         * 1. {Page Template}.php
         * 2. page-{page_name}.php
         * 3. page-{id}.php
         * 4. page.php
         * 
         * An example of this is:
         * 
         * 1. page-templates/full-width.php
         * 2. page-about.php
         * 3. page-4.php
         * 4. page.php
         * 
         * - the template hierarchy and template path are filterable via the
         *   {@see '$type_template_hierarchy'} and {@see '$type_template'}
         *   dynamic hooks, where `$type` is 'page'.
         * 
         * @since 1.5.0
         * @since 4.7.0 The decoded form of `page-{page_name}.php` was added to the top of the
         *              template hierarchy when the page name contains multibyte characters.
         * 
         * @see get_query_template()
         * 
         * @return string Full path to page template file.
         */
        ```
    15. `get_search_template()` - Retrieves path of search template in current or parent template.
        ```php
        /**
         * - the template hierarchy and template path are filterable via the
         *   {@see '$type_template_hierarchy'} and {@see '$type_template'}
         *   dynamic hooks, where `$type` is 'search'.
         * 
         * @since 1.5.0
         * 
         * @see get_query_template()
         * 
         * @return string Full path to search template file.
         */
        ```
    16. `get_single_template()` - Retrieves path of single template in current or parent template. Applies to single Posts, single Attachments, and single custom post types.
        ```php
        /**
         * The hierarchy for this template looks like:
         * 
         * 1. {Post Type Template}.php
         * 2. single-{post_type}-{post_name}.php
         * 3. single-{post_type}.php
         * 4. single.php
         * 
         * An example of this is:
         * 
         * 1. templates/full-width.php
         * 2. single-post-hello-world.php
         * 3. single-post.php
         * 4. single.php
         * 
         * - the template hierarchy and template path are filterable via the
         *   {@see '$type_template_hierarchy'} and {@see '$type_template'}
         *   dynamic hooks, where `$type` is 'single'.
         * 
         * @since 1.5.0
         * @since 4.4.0 `single-{post_type}-{post_name}.php` was added to the top of the template hierarchy.
         * @since 4.7.0 The decoded form of `single-{post_type}-{post_name}.php` was added to the top of
         *              the template hierarchy when the post name contains multibyte characters.
         * @since 4.7.0 `{Post Type Template}.php` was added to the top of the template hierarchy.
         * 
         * @see get_query_template()
         * 
         * @return string Full path to single template file.
         */
        ```
    17. `get_embed_template()` - Retrieves an embed template path in the current or parent template.
        ```php
        /**
         * The hierarchy for this template looks like:
         * 
         * 1. embed-{post_type}-{post_format}.php
         * 2. embed-{post_type}.php
         * 3. embed.php
         * 
         * An example of this is:
         * 
         * 1. embed-post-audio.php
         * 2. embed-post.php
         * 3. embed.php
         * 
         * - the template hierarchy and template path are filterable via the
         *   {@see '$type_template_hierarchy'} and {@see '$type_template'}
         *   dynamic hooks, where `$type` is 'embed'.
         * 
         * @since 4.5.0
         * 
         * @see get_query_template()
         * 
         * @return string Full path to embed template file.
         */
        ```
    18. `get_singular_template()` - Retrieves the path of the singular template in current or parent template.
        ```php
        /**
         * - the template hierarchy and template path are filterable via the
         *   {@see '$type_template_hierarchy'} and {@see '$type_template'}
         *   dynamic hooks, where `$type` is 'singular'.
         * 
         * @since 4.3.0
         * 
         * @see get_query_template()
         * 
         * @return string Full path to singular template file.
         */
        ```
    19. `get_attachment_template()` - Retrieves path of attachment template in current or parent template.
        ```php
        /**
         * The hierarchy for this template looks like:
         * 
         * 1. {mime_type}-{sub_type}.php
         * 2. {sub_type}.php
         * 3. {mime_type}.php
         * 4. attachment.php
         * 
         * An example of this is:
         * 
         * 1. image-jpeg.php
         * 2. jpeg.php
         * 3. image.php
         * 4. attachment.php
         * 
         * - the template hierarchy and template path are filterable via the
         *   {@see '$type_template_hierarchy'} and {@see '$type_template'}
         *   dynamic hooks, where `$type` is 'attachment'.
         * 
         * @since 2.0.0
         * @since 4.3.0 The order of the mime type logic was reversed so the hierarchy
         *              is more logical.
         * 
         * @see get_query_template()
         * 
         * @global array @posts
         * 
         * @return string Full path to attachment template file.
         */
        ```
    20. `locate_template( $template_names, $load = false, $require_once = true, $args = array() )` - Retrieves the name of the highest priority template file that exists.
        ```php
        /**
         * - searches in the STYLESHEETPATH before TEMPLATEPATH and `wp-includes/theme-compat`
         *   so that themes which inherit from a parent theme can just overload one file.
         * 
         * @since 2.7.0
         * @since 5.5.0 The `$args` parameter was added
         * 
         * @param string|array $template_names Template file(s) to search for, in order.
         * @param bool         $load           If true the template file will be loaded if it is found.
         * @param bool         $require_once   Whether to require_once or require.
         *                                     Has no effect if `$load` is false.
         *                                     Default true.
         * @param array        $args           Optional. Additional arguments passed to the template.
         *                                     Default empty array.
         * @return string The template filename if one is located.
         */
        ```
    21. `load_template( $_template_file, $require_once = true, $args = array() )` - Requires the template file with WordPress environment.
        ```php
        /**
         * - the globals are set up for the template file to ensure that the WordPress environment
         *   is available from within the function.
         * - the query variables are also available.
         * 
         * @since 1.5.0
         * @since 5.5.0 The `$args` parameter was added.
         * 
         * @global array        $posts
         * @global WP_Post      $post           Global post object.
         * @global bool         $wp_did_header
         * @global WP_Query     $wp_query       WordPress Query object.
         * @global WP_Rewrite   $wp_rewrite     WordPress rewrite component.
         * @global wpdb         $wpdb           WordPress database abstraction object.
         * @global string       $wp_version
         * @global WP           $wp             Current WordPress environment instance.
         * @global int          $id
         * @global WP_Comment   $comment        Global comment object.
         * @global int          $user_ID
         * 
         * @param string $_template_file Path to template file.
         * @param bool   $require_once   Whether to require_once or require. Default true.
         * @param array  $args           Optional. Additional arguments passed to the template.
         *                               Default empty array.
         */
        ```
    </details>
</details>

<details>
    <summary>48. <b><i>wp-includes/https-detection.php</i></b></summary>

- **NOTES**

    - HTTPS detection functions.

            @package WordPress
            @since 5.7.0

    <details>
    <summary>FUNCTIONS</summary>

    1. `wp_is_using_https()` - Checks whether the website is using HTTPS.
        <details>
        <summary>Detail</summary>

        ```php
        /**
         * - this is based on whether both the home and site URL are using HTTPS.
         * 
         * @since 5.7.0
         * @see wp_is_home_url_using_https()
         * @see wp_is_site_url_using_https()
         * 
         * @return bool True if using HTTPS, false otherwise.
         */
        function wp_is_using_https() {}
        ```
        </details>
    2. `wp_is_home_url_using_https()` - Checks whether the current site URL is using HTTPS.
        <details>
        <summary>Detail</summary>

        ```php
        /**
         * @since 5.7.0
         * @see home_url()
         * 
         * @return bool True if using HTTPS, false otherwise.
         */
        function wp_is_home_url_using_https() {}
        ```
        </details>
    3. `wp_is_site_url_using_https()` - Checks whether the current site's URL where WordPress is stored is using HTTPS.
        <details>
        <summary>Detail</summary>

        ```php
        /**
         * - this checks the URL where WordPress application files are accessible
         *   (e.g. `wp-blog-header.php` or the `wp-admin/` folder)
         * 
         * @since 5.7.0
         * @see site_url()
         * 
         * @return bool True if using HTTPS, false otherwise.
         */
        function wp_is_site_url_using_https() {}
        ```
        </details>
        
        ---
        
    - #### Checks whether HTTPS is supported for the server and domain.
        ```php
        function wp_is_https_supported() {}
        ```
        <details>
        <summary>Detail</summary>

        ```php
        /**
         * @since 5.7.0
         * 
         * @return bool True if HTTPS is supported, false otherwise.
         */
        ```
        </details>

        ---

    - #### Runs a remote HTTPS request to detect whether HTTPS supported, and stores potential errors.
        ```php
        function wp_update_https_detection_errors() {}
        ```
        <details>
        <summary>Detail</summary>

        ```php
        /**
         * - this internal function is called by a regular Cron hook to ensure HTTPS support
         *   is detected and maintained.
         * 
         * @since 5.7.0
         * @access private
         */
        ```
        </details>

        ---

    - #### Schedules the Cron hook for detecting HTTPS support.
        ```php
        function wp_schedule_https_detection() {}
        ```
        <details>
        <summary>Detail</summary>

        ```php
        /**
         * @since 5.7.0
         * @access private
         */
        ```
        </details>

        ---

    - #### Disables SSL verification if the 'cron_request' arguments include an HTTPS URL.
        ```php
        function wp_cron_conditionally_prevent_sslverify( $request ) {}
        ```
        <details>
        <summary>Detail</summary>

        ```php
        /**
         * - this prevents an issue if HTTPS breaks, where there would be a failed attempt
         *   to verify HTTPS.
         * 
         * @since 5.7.0
         * @access private
         * 
         * @param array $request The cron request arguments.
         * @return array The filtered cron request arguments.
         */
        ```
        </details>

        ---

    - #### Checks whether a given HTML string is likely an output from this WordPress site.
        ```php
        function wp_is_local_html_output( $html ) {}
        ```
        <details>
        <summary>Detail</summary>

        ```php
        /**
         * - this function attempts to check for various common WordPress patterns whether
         *   they are included in the HTML string.
         * - since any of these actions may be disabled through third-party code, this
         *   function may also return null to indicate that it was not possible to
         *   determine ownership.
         * 
         * @since 5.7.0
         * @access private
         * 
         * @param string $html Full HTML output string, e.g. from a HTTP response.
         * @return bool|null True/False for whether HTML was generated by this site,
         *                   null if unable to determine.
         */
        ```
        </details>

        ---

    - ...

    </details>
</details>

<details>
<summary>49. wp-includes/<b>https-migration.php</b></summary>

- **NOTES**
    - HTTPS migration functions.

        ```php
        /**
         * @package WordPress
         * @since 5.7.0
         */
        ```
    
    <details>
    <summary>FUNCTIONS - 4</summary>
    
    1.  #### Checks whether WordPress should replace old HTTP URLs to the site with their HTTPS counterpart.
        ```php
        function wp_should_replace_insecure_home_url() {}
        ```
        <details>
        <summary>Detail</summary>

        ```php
        /**
        * - if a WordPress site had its URL changed from HTTP to HTTPS,
        *   by default this will return `true`, causing WordPress to
        *   add frontend filters to replace insecure site URLs that may be present
        *   in older database content.
        * - the {@see 'wp_should_replace_insecure_home_url'} filter can be used to modify
        *   that behavior.
        * 
        * @since 5.7.0
        * 
        * @return bool True if insecure URLs should replaced, false otherwise.
        */
        ```
          
        ---

        </details>

    2.  #### Replaces insecure HTTP URLs to the site in the given content, if configured to do so.
        ```php
        function wp_replace_insecure_home_url( $content ) {}
        ```
        <details>
        <summary>Detail</summary>

        ```php
        /**
         * - this function replaces all occurences of the HTTP version of the site's URL
         *   with its HTTPS counterpart, if determined via {@see wp_should_replace_insecure_home_url()}
         * 
         * @since 5.7.0
         * 
         * @param string $content Content to replace URLs in.
         * @return string Filtered content.
         */
        ```

        ---

        </details>

    3.  #### Update the 'home' and 'siteurl' option to use the HTTPS variant of their URL.
        ```php
        function wp_update_urls_to_https() {}
        ```
        <details>
        <summary>Detail</summary>

        ```php
        /**
         * - if this update does not result in WordPress recognizing that the site is now
         *   using HTTPS (e.g. due to constants overriding the URLs used), the changes will
         *   be reverted.
         * - in such a case, the function will return false.
         * 
         * @since 5.7.0
         * 
         * @return bool True on success, false on failure.
         */
        ```

        ---

        </details>

    4.  #### Updates the 'https_migration_required' option if needed when the given URL has been updated from HTTP to HTTPS.
        ```php
        function wp_update_https_migration_required( $old_url, $new_url ) {}
        ```
        <details>
        <summary>Detail</summary>

        ```php
        /**
         * - if this is a fresh site, a migration will not be required, so the option will
         *   be set as `false`.
         * 
         * - this is hooked into the {@see 'update_option_home'} action.
         * 
         * @since 5.7.0
         * @access private
         * 
         * @param mixed $old_url Previous value of the URL option.
         * @param mixed $new_url New value of the URL option.
         */
        ```

        ---

        </details>

    5.  #### --==--==--
    </details>

---

</details>

<details>
<summary>50. wp-includes/<code>class-wp-user-request.php</code></summary>

- **NOTES**
    - `WP_User_Request` class.
    - represents user request data loaded from a `WP_Post` object.
    - this module contains final class `WP_User_Request` definition.

        ```php
        /**
         * WP_User_Request class.
         * ...
         * @since 4.9.6
         */
        #[AllowDynamicProperties]
        final class WP_User_Request {}
        ```

    <details>
    <summary>FUNCTIONS - -</summary>

    -   ...
    </details>

---

</details>

<details>
<summary>51. wp-includes/<code>user.php</code></summary>

- **NOTES**
    ```php
    /**
     * Core User API
     * 
     * @package WordPress
     * @subpackage Users
     */
    ```

    <details>
    <summary><h3>FUNCTIONS - Core User API :: user.php</h3></summary>

    -   <h4>1. Authenticates and logs a user in with 'remember' capability.</h4>

        ```php
        function wp_signon( $credentials = array(), $secure_cookie = '' ) {}
        ```

        <details>
        <summary>Detail</summary>

        ```php
        /**
         * - the credentials is an array that has 'user_login', 'user_password',
         *   and 'remember' indices.
         * 
         * - if the credentials is not given, then the log in form will be assumed and used if set.
         * 
         * - the various authentication cookies will be set by this function and will be set for a longer
         *   period depending on if the 'remember' credential is set to true.
         * 
         * - Note: `wp_signon()` doesn't handle setting the current user.
         *         This means that if the function is called before the
         *         {@see 'init'} hook is fired, `is_user_logged_in()` will
         *         evaluate as false until that point.
         *         If `is_user_logged_in()` is needed in conjunction with
         *         `wp_signon()`, `wp_set_current_user()` should be called explicitly.
         * 
         * @since 2.5.0
         * 
         * @global string $auth_secure_cookie
         * 
         * @param array         $credentials    Optional. User info in order to sign in.
         * @param string|bool   $secure_cookie  Optional. Whether to use secure cookie.
         * @return WP_User|WP_Error WP_User on success, WP_Error on failure.
         */
        ```

        ---

        </details>

    -   <h4>2. Authenticates a user, confirming the username and password are valid.</h4>

        ```php
        function wp_authenticate_username_password( $user, $username, $password ) {}
        ```

        <details>
        <summary>Detail</summary>

        ```php
        /**
         * @since 2.8.0
         * 
         * @param WP_User|WP_Error|null $user       WP_User or WP_Error object from a previous callback.
         *                                          Default null.
         * @param string                $username   Username for authentication.
         * @param string                $password   Password for authentication.
         * @return WP_User|WP_Error WP_User on success, WP_Error on failure.
         */
        ```

        ---

        </details>

        [//]: # (End f.2)

    -   <h4>3. Authenticates a user using the email and password.</h4>

        ```php
        function wp_authenticate_email_password( $user, $email, $password ) {}
        ```

        <details>
        <summary>Detail</summary>

        ```php
        /**
         * @since 4.5.0
         * 
         * @param WP_User|WP_Error|null $user       WP_User or WP_Error object if a previous
         *                                          callback failed authentication.
         * @param string                $email      Email address for authentication.
         * @param string                $password   Password for authentication.
         * @return WP_User|WP_Error WP_User on success, WP_Error on failure.
         */
        ```

        ---

        </details>

        [//]: # (End f.3)

    -   <h4>4. Authenticates the user using the WordPress auth cookie.</h4>

        ```php
        function wp_authenticate_cookie( $user, $username, $password ) {}
        ```

        <details>
        <summary>Detail</summary>

        ```php
        /**
         * @since 2.8.0
         * 
         * @global string $auth_secure_cookie
         * 
         * @param WP_User|WP_Error|null $user       WP_User or WP_Error object from a previous callback.
         *                                          Default null.
         * @param string                $username   Username. If not empty, cancels the
         *                                          cookie authentication.
         * @param string                $password   Password. If not empty, cancels the
         *                                          cookie authentication.
         * @return WP_User|WP_Error WP_User on success, WP_Error on failure.
         */
        ```

        ---

        </details>

        [//]: # (End f.4)

    -   <h4>5. Authenticates the user using an application password.</h4>

        ```php
        function wp_authenticate_application_password( $input_user, $username, $password ) {}
        ```

        <details>
        <summary>Detail</summary>

        ```php
        /**
         * @since 5.6.0
         * 
         * @param WP_User|WP_Error|null $input_user WP_User or WP_Error object if a previous
         *                                          callback failed authentication.
         * @param string                $username   Username for authentication.
         * @param string                $passord    Password for authentication.
         * @return WP_User|WP_Error|null WP_User on success, WP_Error on failure, null if
         *                               null is passed in and this isn't an API request.
         */
        ```

        ---

        </details>

        [//]: # (End f.5)

    -   <h4>6. Validates the application password credentials passed via Basic Authentication.</h4>

        ```php
        function wp_validate_application_password( $input_user ) {}
        ```

        <details>
        <summary>Detail</summary>

        ```php
        /**
         * @since 5.6.0
         * 
         * @param int|false $input_user User ID if one has been determined, false otherwise.
         * @return int|false The authenticated user ID if successfull, false otherwise.
         */
        ```

        ---

        </details>

        [//]: # (End f.6)

    -   <h4>7. For Multisite blogs, checks if the authenticated user has been marked as a spammer, or if the user's primary blog has been marked as spam.</h4>

        ```php
        function wp_authenticate_spam_check( $user ) {}
        ```

        <details>
        <summary>Detail</summary>

        ```php
        /**
         * @since 3.7.0
         * 
         * @param WP_User|WP_Error|null $user WP_User or WP_Error object from a previous callback.
         *                                    Default null.
         * @return WP_User|WP_Error WP_User on success, WP_Error if the user is considered a spammer.
         */
        ```

        ---

        </details>

        [//]: # (End f.7)
    
    -   <h4>8. Validates the logged-in cookie.</h4>

        ```php
        function wp_validate_logged_in_cookie( $user_id ) {}
        ```

        <details>
        <summary>Detail</summary>

        ```php
        /**
         * - checks the logged-in cookie if the previous auth cookie could not be
         *   validated and parsed.
         * 
         * - this is a callback for the {@see 'determine_current_user'} filter,
         *   rather than API.
         * 
         * @since 3.9.0
         * 
         * @param int|false $user_id The user ID (or false) as received from
         *                           the `determine_current_user` filter.
         * @return int|false User ID if validated, false otherwise.
         *                   If a user ID from an earlier filter callback is received,
         *                   that value is returned.
         */
        ```

        ---

        </details>

        [//]: # (End f.8)

    -   <h4>9. Gets the number of posts a user has written.</h4>

        ```php
        function count_user_posts( $userid, $post_type = 'post', $public_only = false ) {}
        ```

        <details>
        <summary>Detail</summary>

        ```php
        /**
         * @since 3.0.0
         * @since 4.1.0 Added `$post_type` argument.
         * @since 4.3.0 Added `$public_only` argument. Added the ability to pass an array
         *              of post types to `$post_type`.
         * 
         * @global wpdb $wpdb WordPress database abstraction object.
         * 
         * @param int          $userid      User ID.
         * @param array|string $post_type   Optional. Single post type or array of post types
         *                                  to count the number of posts for. Default 'post'.
         * @param bool         $public_only Optional. Whether to only return counts for public
         *                                  posts. Default false.
         * @return string Number of posts the user has written in this post type.
         */
        ```

        ---

        </details>

        [//]: # (End f.9)

    -   <h4>10. Gets the number of posts written by a list of users.</h4>

        ```php
        function count_many_users_posts( $users, $post_type = 'post', $public_only = false ) {}
        ```

        <details>
        <summary>Detail</summary>

        ```php
        /**
         * @since 3.0.0
         * 
         * @global wpdb $wpdb WordPress database abstraction object.
         * 
         * @param int[]             $users          Array of user IDs.
         * @param string|string[]   $post_type      Optional. Single post type or array of post types
         *                                          to check. Defaults to 'post'.
         * @param bool              $public_only    Optional. Only return counts for public posts.
         * @return string[] Amount of posts each user has written, as strings,
         *                  keyed by user ID.
         */
        ```

        ---

        </details>

        [//]: # (End f.10)

    <br>
    <blockquote><h3>User option functions.</h3></blockquote>

    -   <h4>11. Gets the current user's ID.</h4>

        ```php
        function get_current_user_id() {}
        ```

        <details>
        <summary>Detail</summary>

        ```php
        /**
         * @since MU (3.0.0)
         * 
         * @return int The current user's ID, or 0 if no user is logged in.
         */
        ```

        ---

        </details>

        [//]: # (End f.11)

    -   <h4>12. Retrieves user option that can be either per Site or per Network.</h4>

        ```php
        function get_user_option( $option, $user = 0, $deprecated = '' ) {}
        ```

        <details>
        <summary>Detail</summary>

        ```php
        /**
         * - if the user ID is not given, then the current user will be used instead.
         * 
         * - if the user ID is given, then the user data will be retrieved.
         * 
         * - the filter for the results, will also pass the original option name and finally,
         *   the user data object as the third parameter.
         * 
         * - the option will first check for the per site name and then the per Network name.
         * 
         * @since 2.0.0
         * 
         * @global wpdb $wpdb WordPress Database abstraction object.
         * 
         * @param string $option     User option name.
         * @param int    $user       Optional. User ID.
         * @param string $deprecated Use get_option() to check for an option in the options table.
         * @return mixed User option value on success, false on failure.
         */
        ```

        ---

        </details>

        [//]: # (End f.12)

    -   <h4>13. Updates user option with global blog capability.</h4>

        ```php
        function update_user_option( $user_id, $option_name, $newvalue, $global = false ) {}
        ```

        <details>
        <summary>Detail</summary>

        ```php
        /**
         * - user options are just like user metadata except that they have support for
         *   global blog options.
         * 
         * - if the 'global' parameter is false, which it is by default,
         *   it will prepend the WordPress table prefix to the option name.
         * 
         * - deletes the user option if `$newvalue` is empty
         * 
         * @since 2.0.0
         * 
         * @global wpdb $wpdb WordPress database abstraction object.
         * 
         * @param int    $user_id     User ID.
         * @param string $option_name User option name.
         * @param mixed  $newvalue    User option value.
         * @param bool   $global      Optional. Whether option name is global or blog specific.
         *                            Default false (blog specific).
         * @return int|bool User meta ID if the option didn't exist, true on successfull update
         *                  false on failure.
         */
        ```

        ---

        </details>

        [//]: # (End f.13)

    -   <h4>14. Deletes user option with global blog capability.</h4>

        ```php
        function delete_user_option( $user_id, $option_name, $global = false ) {}
        ```

        <details>
        <summary>Detail</summary>

        ```php
        /**
         * - user options are just like user metadata except that they have support for
         *   global blog options.
         * 
         * - if the 'global' parameter is false, which it is by default,
         *   it will prepend the WordPress table prefix to the option name.
         * 
         * @since 3.0.0
         * 
         * @global wpdb $wpdb WordPress database abstraction object.
         * 
         * @param int    $user_id     User ID.
         * @param string $option_name User option name.
         * @param bool   $global      Optional. Whether option name is global or blog specific.
         *                            Default false (blog specific).
         * @return bool True on success, false on failure.
         */
        ```

        ---

        </details>

        [//]: # (End f.14)

    -   <h4>15. Retrieves list of users matching criteria.</h4>

        ```php
        function get_users( $args = array() ): array {}
        ```

        <details>
        <summary>Detail</summary>

        ```php
        /**
         * @since 3.1.0
         * 
         * @see WP_User_Query
         * 
         * @param array $args Optional. Arguments to retrieve users.
         *                    {@see 'WP_User_Query::prepare_query()'} for more
         *                    information on accepted arguments.
         * @return array List of users.
         */
        ```

        ---

        </details>

        [//]: # (End f.15)

    -   <h4>16. Lists all the users of the site, with several options available.</h4>

        ```php
        function wp_list_users( $args = array() ): string|null {}
        ```

        <details>
        <summary>Detail</summary>

        ```php
        /**
         * @since 5.9.0
         * 
         * @param string|array $args {
         *      Optional. Array or string of default arguments.
         * 
         *      @type string $orderby       How to sort the users. Default 'name'.
         *                                  Accept:
         *                                   -'nicename', 'email', 'url', 'registered',
         *                                   -'user_nicename', 'user_email', 'user_url',
         *                                   -'user_registered', 'name', 'display_name',
         *                                   -'post_count', 'ID', 'meta_value', 'user_login'.
         *      @type string $order         Sorting direction for `$orderby`.
         *                                  Accept: 'ASC', 'DESC'. Default 'ASC'.
         *      @type int    $number        Maximum users to return or display.
         *                                  Default empty (all users).
         *      @type bool   $exclude_admin Whether to exclude the 'admin' account, if it exists.
         *                                  Default false.
         *      @type bool   $show_fullname Whether to show the user's full name.
         *                                  Default false.
         *      @type string $feed          If not empty, show a link to the user's feed
         *                                  and use this text as the alt parameter of the link.
         *                                  Default empty.
         *      @type string $feed_image    If not empty, show a link to the user's feed
         *                                  and use this image URL as clickable anchor.
         *                                  Default empty.
         *      @type string $feed_type     The feed type to link to, such as 'rss2'.
         *                                  Defaults to default feed type.
         *      @type bool   $echo          Whether to output the result or instead return it.
         *                                  Default true.
         *      @type string $style         If 'list', each user is wrapped in an `<li>` element,
         *                                  otherwise the users will be separated by commas.
         *      @type bool   $html          Whether to list the items in HTML form or plaintext.
         *                                  Default true.
         *      @type string $exclude       An array, comma-, or space-separated list of user IDs to exclude.
         *                                  Default empty.
         *      @type string $include       An array, comma-, or space-separated list of user IDs to include.
         *                                  Default empty.
         * }
         * @return string|null The output if echo is false. Otherwise null.
         */
        ```

        ---

        </details>

        [//]: # (End f.16)

    -   <h4>17. Gets the sites a user belongs to.</h4>

        ```php
        function get_blogs_of_user( $user_id, $all = false ): object[] {}
        ```

        <details>
        <summary>Detail</summary>

        ```php
        /**
         * @since 3.0.0
         * @since 4.7.0 Converted to use `get_sites()`.
         * 
         * @global wpdb $wpdb WordPress database abstraction object.
         * 
         * @param int  $user_id User ID.
         * @param bool $all     Whether to retrieve all sites, or only sites
         *                      that are not marked as deleted, archived, or spam.
         * @return object[] A list of the user's sites.
         *                  An empty array if the user doesn't exist
         *                  or belongs to no sites.
         */
        ```

        ---

        </details>

        [//]: # (End f.17)

    -   <h4>18. Finds out whether a user is a member of a given blog.</h4>

        ```php
        function is_user_member_of_blog( $user_id = 0, $blog_id = 0 ): bool {}
        ```

        <details>
        <summary>Detail</summary>

        ```php
        /**
         * @since MU (3.0.0)
         * 
         * @global wpdb $wpdb WordPress database abstraction object.
         * 
         * @param int $user_id Optional. The unique ID of the user.
         *                     Defaults to the current user.
         * @param int $blog_id Optional. ID of the blog to check.
         *                     Defaults to the current site.
         * @return bool
         */
        ```

        ---

        </details>

        [//]: # (End f.18)

    -   <h4>19. Adds meta data to a user.</h4>

        ```php
        function add_user_meta( $user_id, $meta_key, $meta_value, $unique = false ): int|false {}
        ```

        <details>
        <summary>Detail</summary>

        ```php
        /**
         * @since 3.0.0
         * @param int    $user_id    User ID.
         * @param string $meta_key   Metadata name.
         * @param mixed  $meta_value Metadata value. Must be serializable if non-scalar.
         * @param bool   $unique     Optional. Whether the same key should not be added.
         *                           Default false.
         * @return int|false Meta ID on success, false on failure.
         */
        ```

        ---

        </details>

        [//]: # (End f.19)

    -   <h4>20. Removes metadata matching criteria from a user.</h4>

        ```php
        function delete_user_meta( $user_id, $meta_key, $meta_value = '' ): bool {}
        ```

        <details>
        <summary>Detail</summary>

        ```php
        /**
         * - you can match based on the key, or key and value.
         * 
         * - removing based on key and value, will keep from removing duplicate metadata
         *   with the same key.
         * 
         * - it also allows removing all metadata matching key, if needed.
         * 
         * @since 3.0.0
         * 
         * @link https://developer.wordpress.org/reference/functions/delete_user_meta/
         * 
         * @param int    $user_id    User ID
         * @param string $meta_key   Metadata name.
         * @param mixed  $meta_value Optional. Metadata value.
         *                           If provided, rows will only be removed that match the value.
         *                           Must be serializable if non-scalar.
         *                           Default empty.
         * @return bool True on success, false on failure.
         */
        ```

        ---

        </details>

        [//]: # (End f.20)

    -   <h4>21. Retrieves user meta field for a user.</h4>

        ```php
        function get_user_meta( $user_id, $key = '', $single = false ): mixed {}
        ```

        <details>
        <summary>Detail</summary>

        ```php
        /**
         * @since 3.0.0
         * 
         * @link https://developer.wordpress.org/reference/functions/get_user_meta/
         * 
         * @param int    $user_id User ID.
         * @param string $key     Optional. The meta key to retrieve.
         *                        By default, returns data for all keys.
         * @param bool   $single  Optional. Whether to return a single value.
         *                        This parameter has no effect if `$key` is not specified.
         *                        Default false.
         * @return mixed An array of values if `$single` is false.
         *               The value of meta data field if `$single` is true.
         *               False for an invalid `$user_id` (non-numeric, zero, or negative value).
         *               An empty string if a valid but non-existing user ID is passed.
         */
        ```

        ---

        </details>

        [//]: # (End f.21)

    -   <h4>22. Updates user meta field based on user ID.</h4>

        ```php
        function update_user_meta( $user_id, $meta_key, $meta_value, $prev_value = '' ): int|bool {}
        ```

        <details>
        <summary>Detail</summary>

        ```php
        /**
         * - use the `$prev_value` parameter to differentiate between meta fields with
         *   the same key and user ID.
         * 
         * - if the meta field for the user does not exist, it will be added.
         * 
         * @since 3.0.0
         * 
         * @link https://developer.wordpress.org/reference/functions/update_user_meta/
         * 
         * @param int    $user_id    User ID.
         * @param string $meta_key   Metadata key.
         * @param mixed  $meta_value Metadata value.
         *                           Must be serializable if non-scalar.
         * @param mixed  $prev_value Optional. Previous value to check before updating.
         *                           If specified, only update existing metadata entries with
         *                           this value. Otherwise, update all entries.
         *                           Default empty.
         * @return int|bool Meta ID if the key didn't exist,
         *                  TRUE on successfull update,
         *                  FALSE on failure or if the value passed to the function
         *                  is the same as the one that is already in the database.
         */
        ```

        ---

        </details>

        [//]: # (End f.22)

    -   <h4>23. Counts number of users who have each of the user roles.</h4>

        ```php
        function count_users( $strategy = 'time', $site_id = null ): array {}
        ```

        <details>
        <summary>Detail</summary>

        ```php
        /**
         * - assumes there are neither duplicated nor orphaned capabilities meta_values.
         * - assumes role names are unique phrases.
         * - same assumption made by WP_User_Query::prepare_query()
         * - using `$strategy = 'time'` this is CPU-intensive and should handle around
         *   10^7 users.
         * - using `$strategy = 'memory'` this is memory-intensive and should handle around
         *   10^5 users, but see WP Bug #12257
         * 
         * @since 3.0.0
         * @since 4.4.0 The number of users with no role is now included in the `none` element.
         * @since 4.9.0 The `$site_id` parameter was added to support multisite.
         * 
         * @global wpdb $wpdb WordPress database abstraction object.
         * 
         * @param string $strategy Optional. The computational strategy to use when counting the users.
         *                         Accepts either 'time' or 'memory'.
         *                         Default 'time'.
         * @param int|null $site_id Optional. The site ID to count users for.
         *                          Defaults to the current site.
         * @return array {
         *      User counts.
         * 
         *      @type int   $total_users Total number of users on the site.
         *      @type int[] $avail_roles Array of user counts keyed by user role.
         * }
         */
        ```

        ---

        </details>

        [//]: # (End f.23)

    -   <h4>24. Returns the number of active users in your installation.</h4>

        ```php
        function get_user_count( $network_id = null ): int {}
        ```

        <details>
        <summary>Detail</summary>

        ```php
        /**
         * - note that on a large site the count may be cached and only updated twice daily.
         * 
         * @since MU (3.0.0)
         * @since 4.8.0 The `$network_id` parameter has been added.
         * @since 6.0.0 Moved to wp-includes/user.php.
         * 
         * @param int|null $network_id ID of the network.
         *                             Defaults to the current network.
         * @return int Number of active users on the network.
         */
        ```

        ---

        </details>

        [//]: # (End f.24)

    -   <h4>25. Updates the total count of users on the site if live user counting is enabled.</h4>

        ```php
        function wp_maybe_update_user_counts( $network_id = null ): bool {}
        ```

        <details>
        <summary>Detail</summary>

        ```php
        /**
         * @since 6.0.0
         * 
         * @param int|null $network_id ID of the network.
         *                             Defaults to the current network.
         * @return bool Whether the update was successful.
         */
        ```

        ---
        
        </details>

        [//]: # (End f.25)

    -   <h4>26. Updates the total count of users on the site.</h4>

        ```php
        function wp_update_user_counts( $network_id = null ): bool {}
        ```

        <details>
        <summary>Detail</summary>

        ```php
        /**
         * @global wpdb $wpdb WordPress database abstraction object.
         * @since 6.0.0
         * 
         * @param int|null $network_id ID of the network.
         *                             Defaults to the current network.
         * @return bool Whether the update was successful.
         */
        ```

        ---
        
        </details>

        [//]: # (End f.26)

    -   <h4>27. Schedules a recurring recalculation of the total count of users.</h4>

        ```php
        function wp_schedule_update_user_counts(): void {}
        ```

        <details>
        <summary>Detail</summary>

        ```php
        /**
         * @since 6.0.0
         */
        ```

        ---
        
        </details>

        [//]: # (End f.27)

    -   <h4>28. Determines whether the site has a large number of users.</h4>

        ```php
        function wp_is_large_user_count( $network_id = null ): bool {}
        ```

        <details>
        <summary>Detail</summary>

        ```php
        /**
         * - the default criteria for a large site is more than 10,000 users.
         * 
         * @since 6.0.0
         * 
         * @param int|null $network_id ID of the network.
         *                             Defaults to the current network.
         * @return bool Whether the site has a large number of users.
         */
        ```

        ---
        
        </details>

        [//]: # (End f.28)

    <br>
    <blockquote><h3>Private helper functions.</h3></blockquote>

    -   <h4>29. Sets up global user vars.</h4>

        ```php
        function setup_userdata( $for_user_id = 0 ): void {}
        ```

        <details>
        <summary>Detail</summary>

        ```php
        /**
         * - used by `wp_set_current_user()` for back compat.
         * - might be deprecated in the future.
         * 
         * @since 2.0.4
         * 
         * @global string  $user_login    The user username for logging in.
         * @global WP_User $userdata      User data.
         * @global int     $user_level    The level of the user.
         * @global int     $user_ID       The ID of the user.
         * @global string  $user_email    The email address of the user.
         * @global string  $user_url      The url in the user's profile.
         * @global string  $user_identity The display name of the user.
         * 
         * @param int      $for_user_id   Optional. User ID to set up global data.
         *                                Default 0.
         */
        ```

        ---

        </details>

        [//]: # (End f.29)

    -   <h4>30. Creates dropdown HTML content of users.</h4>

        ```php
        function wp_dropdown_users( $args = '' ): string {}
        ```

        <details>
        <summary>Detail</summary>

        ```php
        /**
         * - the content can either be displayed, which it is by default
         *   or retrieved by setting the 'echo' argument.
         * 
         * - the 'include' and 'exclude' arguments do not need to be used
         *   ~all users will be displayed in that case.
         * 
         * - only one can be used, either 'include' or 'exclude', but not both.
         * 
         * The available arguments are as follows:
         * 
         * @since 2.3.0
         * @since 4.5.0 Added the 'display_name_with_login' value for 'show'.
         * @since 4.7.0 Added the `$role`, `$role__in`, and `$role__not_in` parameters.
         * 
         * @param array|string $args {
         *      Optional. Array or string of arguments to generate a drop-down of users.
         *      {@see 'WP_User_Query::prepare_query()'} for additional available arguments.
         * 
         *      @type string       $show_option_all         Text to show as the drop-down default (all).
         *                                                  Default empty.
         *      @type string       $show_option_none        Text to show as the drop-down default when no
         *                                                  users were found.
         *                                                  Default empty.
         *      @type int|string   $option_none_value       Value to use for `$show_option_none` when no
         *                                                  users were found.
         *                                                  Default -1.
         *      @type string       $hide_if_only_one_author Whether to skip generating the drop-down
         *                                                  if only one user was found.
         *                                                  Default empty.
         *      @type string       $orderby                 Field to order found users by.
         *                                                  Accepts user fields.
         *                                                  Default 'display_name'.
         *      @type string       $order                   Whether to order users in ascending or descending order.
         *                                                  Accepts 'ASC' or 'DESC'.
         *                                                  Default 'ASC'.
         *      @type int[]|string $include                 Array or comma-separated list of user IDs to include.
         *                                                  Default empty.
         *      @type int[]|string $exclude                 Array or comma-separated list of user IDs to exclude.
         *                                                  Default empty.
         *      @type bool|int     $multi                   Whether to skip the ID attribute on the 'select' element.
         *                                                  Accepts 1|true or 0|false.
         *                                                  Default 0|false.
         *      @type string       $show                    User data to display.
         *                                                  If the selected item is empty then the `user_login`
         *                                                  will be displayed in parentheses.
         *                                                  Accepts any user field, or `display_name_with_login` to
         *                                                  show the display name with user_login in parentheses.
         *                                                  Default 'display_name'.
         *      @type int|bool     $echo                    Whether to echo or return the drop-down.
         *                                                  Accepts 1|true (echo) or 0|false (return).
         *                                                  Default 1|true.
         *      @type int          $selected                Which user ID should be selected.
         *                                                  Default 0.
         *      @type bool         $include_selected        Whether to always include the selected user ID
         *                                                  in the drop-down.
         *                                                  Default false.
         *      @type string       $name                    Name attribute of select element.
         *                                                  Default 'user'.
         *      @type string       $id                      ID attribute of select element.
         *                                                  Default is the value of `$name`.
         *      @type string       $class                   Class attribute of select element.
         *                                                  Default empty.
         *      @type int          $blog_id                 ID of blog (Multisite only).
         *                                                  Default is ID of the current blog.
         *      @type string       $who                     Which type of users to query.
         *                                                  Accepts only an empty string or 'authors'.
         *                                                  Default empty.
         *      @type string|array $role                    An array or a comma-separated list of role names
         *                                                  that users must match to be included in results.
         *                                                  Note that this is an inclusive list:
         *                                                  users must match *each* role.
         *                                                  Default empty.
         *      @type string[]     $role__in                An array of role names.
         *                                                  Matched users must have at least one of these roles.
         *                                                  Default empty array.
         *      @type string[]     $role__not_in            An array of role names to be exclude.
         *                                                  Users matching one or more of these roles
         *                                                  will not be included in results.
         *                                                  Default empty array.
         * }
         * @return string HTML dropdown list of users.
         */
        ```

        ---

        </details>

        [//]: # (End f.30)

    -   <h4>31. Sanitizes user field based on context.</h4>

        ```php
        function sanitize_user_field( $field, $value, $user_id, $context ): mixed {}
        ```

        <details>
        <summary>Detail</summary>

        ```php
        /**
         * - possible context values are:
         *   'raw', 'edit', 'db', 'display', 'attribute', 'js'.
         * 
         * - the 'display' context is used by default.
         * 
         * - 'attribute' and 'js' contexts are treated like 'display'
         *   when calling filters.
         * 
         * @since 2.3.0
         * 
         * @param string $field   The user Object field name.
         * @param mixed  $value   The user Object value.
         * @param int    $user_id User ID.
         * @param string $context How to sanitize user fields.
         *                        Looks for 'raw', 'edit', 'db', 'display', 'attribute' and 'js'.
         * @return mixed Sanitized value.
         */
        ```

        ---

        </details>

        [//]: # (End f.31)

    -   <h4>32. Updates all user caches.</h4>

        ```php
        function update_user_caches( $user ): void|false {}
        ```

        <details>
        <summary>Detail</summary>

        ```php
        /**
         * @since 3.0.0
         * 
         * @param object|WP_User $user User object or database row to be cached
         * @return void|false Void on success, false on failure.
         */
        ```

        ---

        </details>

        [//]: # (End f.32)

    -   <h4>33. Cleans all user caches.</h4>

        ```php
        function clean_user_cache( $user ): void {}
        ```

        <details>
        <summary>Detail</summary>

        ```php
        /**
         * @since 3.0.0
         * @since 4.4.0 'clean_user_cache' action was added.
         * @since 6.2.0 User metadata caches are now cleared.
         * 
         * @param WP_User|int $user User object or ID to be cleaned from the cache
         */
        ```

        ---

        </details>

        [//]: # (End f.33)

    -   <h4>34. Determines whether the given username exists.</h4>

        ```php
        function username_exists( $username ): int|false {}
        ```

        <details>
        <summary>Detail</summary>

        ```php
        /**
         * - for more information on this and similar theme functions, check out the
         *   {@link https://developer.wordpress.org/themes/basics/conditional-tags/ Conditional Tags}
         *   article in the Theme Developer Handbook.
         * 
         * @since 2.0.0
         * 
         * @param string $username The username to check for existence.
         * @return int|false The user ID on success, false on failure.
         */
        ```

        ---

        </details>

        [//]: # (End f.34)

    -   <h4>35. Determines whether the given email exists.</h4>

        ```php
        function email_exists( $email ): int|false {}
        ```

        <details>
        <summary>Detail</summary>

        ```php
        /**
         * - for more information on this and similar theme functions, check out the
         *   {@link https://developer.wordpress.org/themes/basics/conditional-tags/ Conditional Tags}
         *   article in the Theme Developer Handbook.
         * 
         * @since 2.1.0
         * 
         * @param string $email The email to check for existence.
         * @return int|false The user ID on success, false on failure.
         */
        ```

        ---
        
        </details>

        [//]: # (End f.35)

    -   <h4>36. Checks whether a username is valid.</h4>

        ```php
        function validate_username( $username ): bool {}
        ```

        <details>
        <summary>Detail</summary>

        ```php
        /**
         * @since 2.0.1
         * @since 4.4.0 Empty sanitized usernames are now considered invalid.
         * 
         * @param string $username Username.
         * @return bool Whether username given is valid.
         */
        ```

        ---
        
        </details>

        [//]: # (End f.36)

    -   <h4>37. Inserts a user into the database.</h4>

        ```php
        function wp_insert_user( $userdata ): int|WP_Error {}
        ```

        <details>
        <summary>Detail</summary>

        ```php
        /**
         * - most of the `$userdata` array fields have filters associated with the values.
         * 
         * - exceptions are: 'ID', 'rich_editing', 'syntax_highlighting', 'comment_shortcuts',
         *                   'admin_color', 'use_ssl', 'user_registered', 'user_activation_key',
         *                   'spam', and 'role'.
         * 
         * - the filters have the prefix 'pre_user_' followed by the field name.
         * 
         * - an example using 'description' would have the filter called 'pre_user_description'
         *   that can be hooked into.
         * 
         * @since 2.0.0
         * @since 3.6.0 The `aim`, `jabber`, and `yim` fields were removed as default user contact
         *              methods for new installations.
         *              {@see wp_get_user_contact_methods()}.
         * @since 4.7.0 The `locale` field can be passed to `$userdata`.
         * @since 5.3.0 The `user_activation_key` field can be passed to `$userdata`.
         * @since 5.3.0 The `spam` field can be passed to `$userdata` (Multisite only).
         * @since 5.9.0 The `meta_input` field can be passed to `$userdata` to allow
         *              addition of user meta data.
         * 
         * @global wpdb $wpdb WordPress database abstraction object.
         * 
         * @param array|object|WP_User $userdata {
         *      An array, object, or WP_User object of user data arguments.
         * 
         *      @type int    $ID                    User ID. If supplied, the user will be updated.
         *      @type string $user_pass             The plain-text user password.
         *      @type string $user_login            The user's login username.
         *      @type string $user_nicename         The URL-friendly user name.
         *      @type string $user_url              The user URL.
         *      @type string $user_email            The user email address.
         *      @type string $display_name          The user's display name.
         *                                          Default is the user's username.
         *      @type string $nickname              The user's nickname.
         *                                          Default is the user's username.
         *      @type string $first_name            The user's first name. For new users, will be used to
         *                                          build the first part of the user's display name
         *                                          if `$display_name` is not specified.
         *      @type string $last_name             The user's last name. For new users, will be used to
         *                                          build the second part of the user's display name
         *                                          if `$display_name` is not specified.
         *      @type string $description           The user's biographical description.
         *      @type string $rich_editing          Whether to enable the rich-editor for the user.
         *                                          Accepts 'true' or 'false' as a string literal, not boolean.
         *                                          Default 'true'.
         *      @type string $syntax_highlighting   Whether to enable the rich code editor for the user.
         *                                          Accepts 'true' or 'false' as a string literal, not boolean.
         *                                          Default 'true'.
         *      @type string $comment_shortcuts     Whether to enable comment moderation keyboard shortcuts
         *                                          for the user.
         *                                          Accepts 'true' or 'false' as a string literal, not boolean.
         *                                          Default 'false'.
         *      @type string $admin_color           Admin color scheme for the user.
         *                                          Default 'fresh'.
         *      @type bool   $use_ssl               Whether the user should always access the admin over https.
         *                                          Default false.
         *      @type string $user_registered       Date the user registered in UTC.
         *                                          Format is 'Y-m-d H:i:s'.
         *      @type string $user_activation_key   Password reset key.
         *                                          Default empty.
         *      @type bool   $spam                  Multisite only. Whether the user is marked as spam.
         *                                          Default false.
         *      @type string $show_admin_bar_front  Whether to display the Admin Bar for the user on the site's
         *                                          front end.
         *                                          Accepts 'true' or 'false' as a string literal, not boolean.
         *                                          Default 'true'.
         *      @type string $role                  User's role.
         *      @type string $locale                User's locale. Default empty.
         *      @type array  $meta_input            Array of custom user meta values keyed by meta key.
         *                                          Default empty.
         * }
         * @return int|WP_Error The newly created user's ID or a WP_Error object if
         *                      the user could not be created.
         */
        ```

        ---
        
        </details>

        [//]: # (End f.37)

    -   <h4>38. Updates a user in the database.</h4>

        ```php
        function wp_update_user( $userdata ): int|WP_Error {}
        ```

        <details>
        <summary>Detail</summary>

        ```php
        /**
         * - it is possible to update a user's password by specifying the 'user_pass'
         *   value in the `$userdata` parameter array.
         * 
         * - if current user's password is being updated, then the cookies will be cleared.
         * 
         * @since 2.0.0
         * 
         * @see wp_insert_user() For what fields can be set in $userdata
         * 
         * @param array|object|WP_User $userdata An array of user data or a user object
         *                                       of type stdClass or WP_User.
         * @return int|WP_Error The updated user's ID or a WP_Error object if
         *                      the user could not be updated.
         */
        ```

        ---
        
        </details>

        [//]: # (End f.38)

    -   <h4>39. Provides a simpler way of inserting a user into the database.</h4>

        ```php
        function wp_create_user( $username, $password, $email = '' ): int|WP_Error {}
        ```

        <details>
        <summary>Detail</summary>

        ```php
        /**
         * - creates a new user with just the username, password, and email.
         * 
         * - for more complex user creation use `wp_insert_user()` to specify more information.
         * 
         * @since 2.0.0
         * 
         * @see wp_insert_user() More complete way to create a new user.
         * 
         * @param string $username The user's username.
         * @param string $password The user's password.
         * @param string $email    Optional. The user's email. Default empty.
         * @return int|WP_Error The newly created user's ID or a WP_Error object if
         *                      the user could not be created.
         */
        ```

        ---
        
        </details>

        [//]: # (End f.39)

    -   <h4>40. Returns a list of meta keys to be (maybe) populated in <code>wp_update_user()</code>.</h4>

        ```php
        function _get_additional_user_keys( $user ): string[] {}
        ```

        <details>
        <summary>Detail</summary>

        ```php
        /**
         * - the list of keys returned via this function are dependent on the presence
         *   of those keys in the user meta data to be set.
         * 
         * @since 3.3.0
         * @access private
         * 
         * @param WP_User $user WP_User instance.
         * @return string[] List of user keys to be populated in wp_update_user().
         */
        ```

        ---
        
        </details>

        [//]: # (End f.40)

    -   <h4>41. Sets up the user contact methods.</h4>

        ```php
        function wp_get_user_contact_methods( $user = null ): string[] {}
        ```

        <details>
        <summary>Detail</summary>

        ```php
        /**
         * - default contact methods were removed in 3.6.
         * 
         * - a filter dictates contact methods.
         * 
         * @since 3.7.0
         * 
         * @param WP_User|null $user Optional. WP_User object.
         * @return string[] Array of contact method labels keyed by contact method.
         */
        ```

        ---

        </details>

        [//]: # (End f.41)

    -   <h4>42. The old private function for setting up user contact methods.</h4>

        ```php
        function _wp_get_user_contactmethods( $user = null ) {
            return wp_get_user_contact_methods( $user );
        }
        ```

        <details>
        <summary>Detail</summary>

        ```php
        /**
         * - use {@see wp_get_user_contact_methods()} instead.
         * 
         * @since 2.9.0
         * @access private
         * 
         * @param WP_User|null $user Optional. WP_User object. Default null.
         * @return string[] Array of contact method labels keyed by contact method.
         */
        ```

        ---

        </details>

        [//]: # (End f.42)

    -   <h4>43. Gets the text suggesting how to create strong password.</h4>

        ```php
        function wp_get_password_hint(): string {}
        ```

        <details>
        <summary>Detail</summary>

        ```php
        /**
         * @since 4.1.0
         * 
         * @return string The password hint text.
         */
        ```

        ---

        </details>

        [//]: # (End f.43)

    -   ...
    -   <h4>x. ...</h4>

        ```php
        ```

        <details>
        <summary>Detail</summary>

        ```php
        ```

        ---

        </details>

        [//]: # (End f.x)

    -   ...
    -   <h4>x. ...</h4>

        ```php
        ```

        <details>
        <summary>Detail</summary>

        ```php
        ```

        ---

        </details>

        [//]: # (End f.x)

    -   ...
    -   <h4>x. ...</h4>

        ```php
        ```

        <details>
        <summary>Detail</summary>

        ```php
        ```

        ---

        </details>

        [//]: # (End f.x)

    </details>

    [//]: # (End of FUNCTIONS)

---

</details>