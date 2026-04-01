<?php
/**
 * Category Badge
 * 
 * @package Dark_Theme_Simplicity
 */

$categories = get_the_category();
if ( ! empty( $categories ) ) :
    $first_category = $categories[0];
    ?>
    <a href="<?php echo esc_url( get_category_link( $first_category->term_id ) ); ?>" 
       class="inline-flex items-center rounded-full border px-3 py-1 text-sm font-semibold mb-3 bg-blue-300/10 text-blue-300 hover:bg-blue-300/20 border-blue-300/20">
        <?php echo esc_html( $first_category->name ); ?>
    </a>
    <?php
endif;