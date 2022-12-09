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

END-NAV-DIR: */...


NAV-DIR: */wp-includes

END-NAV-DIR: */wp-includes