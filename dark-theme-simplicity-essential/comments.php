<?php
if (post_password_required()) {
    return;
}
?>

<div id="comments" class="comments-area mt-8">
    <?php if (have_comments()) : ?>
        <h2 class="comments-title text-2xl font-bold mb-6">
            <?php
            $comments_number = get_comments_number();
            if ($comments_number === 1) {
                printf(
                    esc_html__('One comment on &ldquo;%1$s&rdquo;', 'dark-theme-simplicity'),
                    get_the_title()
                );
            } else {
                printf(
                    esc_html(_n(
                        '%1$s comment on &ldquo;%2$s&rdquo;',
                        '%1$s comments on &ldquo;%2$s&rdquo;',
                        $comments_number,
                        'dark-theme-simplicity'
                    )),
                    number_format_i18n($comments_number),
                    get_the_title()
                );
            }
            ?>
        </h2>

        <ol class="comment-list space-y-6">
            <?php
            wp_list_comments(array(
                'style' => 'ol',
                'short_ping' => true,
                'avatar_size' => 60,
                'callback' => function($comment, $args, $depth) {
                    $GLOBALS['comment'] = $comment;
                    ?>
                    <li <?php comment_class('comment'); ?> id="comment-<?php comment_ID(); ?>">
                        <article class="comment-body bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm">
                            <footer class="comment-meta flex items-start gap-4">
                                <div class="comment-author vcard">
                                    <?php echo get_avatar($comment, 60, '', '', array('class' => 'rounded-full')); ?>
                                </div>

                                <div class="comment-metadata">
                                    <?php
                                    printf(
                                        '<b class="fn">%s</b>',
                                        get_comment_author_link()
                                    );
                                    ?>
                                    <a href="<?php echo esc_url(get_comment_link($comment->comment_ID)); ?>" class="text-sm text-gray-600 dark:text-gray-400">
                                        <time datetime="<?php comment_time('c'); ?>">
                                            <?php
                                            printf(
                                                esc_html__('%1$s at %2$s', 'dark-theme-simplicity'),
                                                get_comment_date(),
                                                get_comment_time()
                                            );
                                            ?>
                                        </time>
                                    </a>
                                </div>
                            </footer>

                            <div class="comment-content prose dark:prose-invert mt-4">
                                <?php comment_text(); ?>
                            </div>

                            <?php if ('0' == $comment->comment_approved) : ?>
                                <p class="comment-awaiting-moderation text-sm text-yellow-600 dark:text-yellow-400 mt-2">
                                    <?php esc_html_e('Your comment is awaiting moderation.', 'dark-theme-simplicity'); ?>
                                </p>
                            <?php endif; ?>

                            <div class="reply mt-4">
                                <?php
                                comment_reply_link(array_merge($args, array(
                                    'reply_text' => __('Reply', 'dark-theme-simplicity'),
                                    'depth' => $depth,
                                    'max_depth' => $args['max_depth'],
                                    'before' => '<span class="text-sm text-primary hover:text-primary-dark">',
                                    'after' => '</span>'
                                )));
                                ?>
                            </div>
                        </article>
                    </li>
                    <?php
                }
            ));
            ?>
        </ol>

        <?php if (get_comment_pages_count() > 1 && get_option('page_comments')) : ?>
            <nav class="comment-navigation flex justify-between mt-8">
                <div class="nav-previous">
                    <?php previous_comments_link(__('Older Comments', 'dark-theme-simplicity')); ?>
                </div>
                <div class="nav-next">
                    <?php next_comments_link(__('Newer Comments', 'dark-theme-simplicity')); ?>
                </div>
            </nav>
        <?php endif; ?>
    <?php endif; ?>

    <?php if (!comments_open() && get_comments_number() && post_type_supports(get_post_type(), 'comments')) : ?>
        <p class="no-comments text-center text-gray-600 dark:text-gray-400 mt-8">
            <?php esc_html_e('Comments are closed.', 'dark-theme-simplicity'); ?>
        </p>
    <?php endif; ?>

    <?php
    comment_form(array(
        'class_form' => 'comment-form mt-8',
        'title_reply' => __('Leave a Comment', 'dark-theme-simplicity'),
        'title_reply_before' => '<h3 id="reply-title" class="comment-reply-title text-xl font-bold mb-6">',
        'title_reply_after' => '</h3>',
        'comment_notes_before' => '<p class="comment-notes text-sm text-gray-600 dark:text-gray-400 mb-4">' . __('Your email address will not be published. Required fields are marked *', 'dark-theme-simplicity') . '</p>',
        'comment_field' => '<p class="comment-form-comment mb-4"><label for="comment" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">' . _x('Comment', 'noun', 'dark-theme-simplicity') . '</label><textarea id="comment" name="comment" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100" rows="8" required></textarea></p>',
        'fields' => array(
            'author' => '<p class="comment-form-author mb-4"><label for="author" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">' . __('Name', 'dark-theme-simplicity') . ' <span class="required">*</span></label><input id="author" name="author" type="text" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100" value="' . esc_attr($commenter['comment_author']) . '" size="30" required /></p>',
            'email' => '<p class="comment-form-email mb-4"><label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">' . __('Email', 'dark-theme-simplicity') . ' <span class="required">*</span></label><input id="email" name="email" type="email" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100" value="' . esc_attr($commenter['comment_author_email']) . '" size="30" required /></p>',
            'url' => '<p class="comment-form-url mb-4"><label for="url" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">' . __('Website', 'dark-theme-simplicity') . '</label><input id="url" name="url" type="url" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100" value="' . esc_attr($commenter['comment_author_url']) . '" size="30" /></p>',
        ),
        'submit_button' => '<button type="submit" class="submit px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark">%4$s</button>',
        'submit_field' => '<p class="form-submit">%1$s %2$s</p>',
    ));
    ?>
</div> 