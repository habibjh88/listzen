<?php
/**
 * The template for displaying comments
 *
 * This is the template that displays the area of the page that contains both the current comments
 * and the comment form.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package listzen
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( post_password_required() ) :
    return;
endif;

if ( 'open' !== get_option( 'default_comment_status' ) ) {
    return;
}
?>

<div id="comments" class="comments-area">

    <?php if ( have_comments() ) : ?>
        <div class="comment-list-wrapper">
            <h3 class="comments-title">
                <?php
                printf(
                /* translators: 1: Comments count. */
                        esc_html( _n( '%d Comment', '%d Comments', get_comments_number(), 'listzen' ) ),
                        absint( get_comments_number() )
                );
                ?>
            </h3><!-- .comments-title -->

            <ol class="comment-list">
                <?php
                wp_list_comments(
                        [
                                'style'       => 'ol',
                                'avatar_size' => 100,
                                'short_ping'  => true,
                                'callback'    => 'listzen_comments_cbf',
                        ]
                );
                ?>
            </ol><!-- .comment-list -->

            <?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // Are there comments to navigate through? ?>
                <nav id="comment-nav-below" class="navigation comment-navigation" role="navigation">
                    <h2 class="screen-reader-text"><?php esc_html_e( 'Comment navigation', 'listzen' ); ?></h2>
                    <div class="nav-links">
                        <?php
                        $listzen_arrow_next = "<i class='rt-icon-arrow-right'></i>";
                        $listzen_arrow_prev = "<i class='rt-icon-arrow-left'></i>";
                        ?>
                        <div class="nav-previous"><?php previous_comments_link( $listzen_arrow_prev . esc_html__( 'Older Comments', 'listzen' ) ); ?></div>
                        <div class="nav-next"><?php next_comments_link( esc_html__( 'Newer Comments', 'listzen' ) . $listzen_arrow_next ); ?></div>

                    </div><!-- .nav-links -->
                </nav><!-- #comment-nav-below -->
            <?php
            endif; // Check for comment navigation.?>
        </div>
    <?php

    endif; // Check for have_comments().


    // If comments are closed and there are comments, let's leave a little note, shall we?
    if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) : ?>
        <p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'listzen' ); ?></p>
    <?php
    endif;

    comment_form();
    ?>

</div><!-- #comments -->
