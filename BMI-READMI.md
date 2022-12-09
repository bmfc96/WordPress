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
        20. require-once ABSPATH . 'wp-settings.php'

END-NAV-DIR: */...


NAV-DIR: */wp-includes

END-NAV-DIR: */wp-includes