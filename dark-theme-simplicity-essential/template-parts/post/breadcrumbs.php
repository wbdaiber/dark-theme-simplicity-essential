<?php
/**
 * Breadcrumbs Navigation
 * 
 * @package Dark_Theme_Simplicity
 */
?>

<nav class="breadcrumbs-nav flex items-center gap-2 text-sm mb-5 text-light-100/70" aria-label="<?php esc_attr_e( 'Breadcrumb navigation', 'dark-theme-simplicity' ); ?>" role="navigation">
    <ol class="breadcrumbs-list flex items-center gap-2" itemscope itemtype="https://schema.org/BreadcrumbList">
        <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" 
               class="hover:text-blue-400 transition-colors" 
               itemprop="item"
               aria-label="<?php esc_attr_e( 'Go to homepage', 'dark-theme-simplicity' ); ?>">
                <span itemprop="name"><?php esc_html_e( 'Home', 'dark-theme-simplicity' ); ?></span>
            </a>
            <meta itemprop="position" content="1" />
        </li>
        <li class="text-light-100/50" aria-hidden="true">›</li>
        <?php
        // Get the page ID that's set as the "Posts page" in Settings > Reading
        $posts_page_id = get_option( 'page_for_posts' );
        
        if ( $posts_page_id ) {
            // If a static page is set as the posts page
            $posts_page_title = get_the_title( $posts_page_id );
            $posts_page_url = get_permalink( $posts_page_id );
            ?>
            <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                <a href="<?php echo esc_url( $posts_page_url ); ?>" 
                   class="hover:text-blue-400 transition-colors" 
                   itemprop="item"
                   aria-label="<?php echo esc_attr( sprintf( __( 'Go to %s', 'dark-theme-simplicity' ), $posts_page_title ) ); ?>">
                    <span itemprop="name"><?php echo esc_html( $posts_page_title ); ?></span>
                </a>
                <meta itemprop="position" content="2" />
            </li>
            <?php
        } else {
            // If no static page is set (default behavior)
            ?>
            <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" 
                   class="hover:text-blue-400 transition-colors" 
                   itemprop="item"
                   aria-label="<?php esc_attr_e( 'Go to blog', 'dark-theme-simplicity' ); ?>">
                    <span itemprop="name"><?php esc_html_e( 'Blog', 'dark-theme-simplicity' ); ?></span>
                </a>
                <meta itemprop="position" content="2" />
            </li>
            <?php
        }
        ?>
    </ol>
</nav>