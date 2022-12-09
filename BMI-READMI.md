# Documents

## File(s) responsibility|function

*=WordPress

DIR: */...
     */wp-includes
     */wp-content
     */wp-admin
    (17 files)

NAV-DIR: */... (17 files)
1. index.php
    - Front to application, LOADS 'wp-blog-header.php'
2. wp-blog-header.php
    - Loads WordPress ENVIRONMENT & TEMPLATE
    - FLOW:
        1. Load WordPress library (wp-load.php - require-once)
        2. Setup WordPress query (wp();)
        3. Load THEME template (ABSPATH . WPINC . '/template-loader.php' - require-once)
END-NAV-DIR: */...


NAV-DIR: */wp-includes

END-NAV-DIR: */wp-includes