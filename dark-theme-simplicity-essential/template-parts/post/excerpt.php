<?php
/**
 * Post Excerpt
 * 
 * @package Dark_Theme_Simplicity
 */

if ( has_excerpt() || get_the_content() ) : ?>
    <div class="text-lg text-light-100/90 mb-4 md:mb-6 leading-relaxed drop-shadow-md">
        <?php
        if ( has_excerpt() ) {
            the_excerpt();
        } else {
            echo wp_trim_words( get_the_content(), 25, '...' );
        }
        ?>
    </div>
<?php endif;