<?php
/**
 * Template Name: WBG Books Details
 *
 */


$user = wp_get_current_user();
$book_lenders = WBG_Admin::wbg_book_lenders($post->ID);

 // Librarian action.
if ( isset( $_POST['update_lenders'] ) && current_user_can( 'manage_wbg_lenders' ) ) {
    $book_lenders = (!empty($_POST['wbg_lenders'])) ? $_POST['wbg_lenders'] : '';
    update_post_meta( $post->ID, 'wbg_lenders', $book_lenders );
}

// Lender action.
if ( current_user_can( 'lend_wbg_books' ) ) {
    if ( isset( $_POST['add_me_as_lender'] ) && !in_array( $user->ID, $book_lenders ) ) {
        array_push( $book_lenders, strval( $user->ID ) );
        update_post_meta( $post->ID, 'wbg_lenders', serialize($book_lenders) );
    } elseif ( isset( $_POST['remove_me_as_lender'] ) && in_array( $user->ID, $book_lenders ) ) {
        $key = array_search( strval( $user->ID ), $book_lenders );
        if ( $key !== false ) {
            array_splice($book_lenders, $key, 1);
        }
        update_post_meta( $post->ID, 'wbg_lenders', serialize($book_lenders) );
    }
}

get_header();

$wbgGeneralSettings         = stripslashes_deep( unserialize( get_option('wbg_general_settings') ) );
$wbgDetailSettings          = stripslashes_deep( unserialize( get_option('wbg_detail_settings') ) );

$wbgAuthorInfo               = isset( $wbgDetailSettings['wbg_author_info'] ) ? $wbgDetailSettings['wbg_author_info'] : 1;
$wbgAuthorLabel              = isset( $wbgGeneralSettings['wbg_author_label_txt'] ) && ($wbgDetailSettings['wbg_author_label'] != '') ? $wbgDetailSettings['wbg_author_label'] : 'Author';
$wbgDisplayCategory          = isset( $wbgDetailSettings['wbg_display_category'] ) ? $wbgDetailSettings['wbg_display_category'] : 1;
$wbgCategoryLabel            = isset( $wbgGeneralSettings['wbg_category_label'] ) && ($wbgDetailSettings['wbg_category_label'] != '') ? $wbgDetailSettings['wbg_category_label'] : 'Category';
$wbgDisplayPublisher         = isset( $wbgDetailSettings['wbg_display_publisher'] ) ? $wbgDetailSettings['wbg_display_publisher'] : 1;
$wbgPublisherLabel           = isset( $wbgGeneralSettings['wbg_publisher_label'] ) && ($wbgDetailSettings['wbg_publisher_label'] != '') ? $wbgDetailSettings['wbg_publisher_label'] : 'Publisher';
$wbg_display_publish_date    = isset( $wbgDetailSettings['wbg_display_publish_date'] ) ? $wbgDetailSettings['wbg_display_publish_date'] : 1;
$wbg_publish_date_label      = isset( $wbgGeneralSettings['wbg_publish_date_label'] ) && ($wbgDetailSettings['wbg_publish_date_label'] != '') ? $wbgDetailSettings['wbg_publish_date_label'] : 'Publish';
$wbg_publish_date_format     = isset( $wbgDetailSettings['wbg_publish_date_format'] ) ? $wbgDetailSettings['wbg_publish_date_format'] : 'full';
$wbg_display_isbn            = isset( $wbgDetailSettings['wbg_display_isbn'] ) ? $wbgDetailSettings['wbg_display_isbn'] : '1';
$wbg_isbn_label              = isset( $wbgDetailSettings['wbg_isbn_label'] ) ? $wbgDetailSettings['wbg_isbn_label'] : 'ISBN';
$wbg_display_page            = isset( $wbgDetailSettings['wbg_display_page'] ) ? $wbgDetailSettings['wbg_display_page'] : '1';
$wbg_page_label              = isset( $wbgDetailSettings['wbg_page_label'] ) ? $wbgDetailSettings['wbg_page_label'] : 'Pages';
$wbg_display_country         = isset( $wbgDetailSettings['wbg_display_country'] ) ? $wbgDetailSettings['wbg_display_country'] : '1';
$wbg_country_label           = isset( $wbgDetailSettings['wbg_country_label'] ) ? $wbgDetailSettings['wbg_country_label'] : 'Country';
$wbg_display_language        = isset( $wbgDetailSettings['wbg_display_language'] ) ? $wbgDetailSettings['wbg_display_language'] : '1';
$wbg_language_label          = isset( $wbgDetailSettings['wbg_language_label'] ) ? $wbgDetailSettings['wbg_language_label'] : 'Language';
$wbg_display_dimension       = isset( $wbgDetailSettings['wbg_display_dimension'] ) ? $wbgDetailSettings['wbg_display_dimension'] : '1';
$wbg_dimension_label         = isset( $wbgDetailSettings['wbg_dimension_label'] ) ? $wbgDetailSettings['wbg_dimension_label'] : 'Dimension';
$wbg_display_filesize        = isset( $wbgDetailSettings['wbg_display_filesize'] ) ? $wbgDetailSettings['wbg_display_filesize'] : '1';
$wbg_filesize_label          = isset( $wbgDetailSettings['wbg_filesize_label'] ) ? $wbgDetailSettings['wbg_filesize_label'] : 'File Size';
$wbg_display_download_button = isset( $wbgDetailSettings['wbg_display_download_button'] ) ? $wbgDetailSettings['wbg_display_download_button'] : '1';
$wbg_download_button_label   = isset( $wbgGeneralSettings['wbg_buynow_btn_txt'] ) ? $wbgGeneralSettings['wbg_buynow_btn_txt'] : 'Download';
$wbg_display_description     = isset( $wbgDetailSettings['wbg_display_description'] ) ? $wbgDetailSettings['wbg_display_description'] : '1';
$wbg_description_label       = isset( $wbgDetailSettings['wbg_description_label'] ) ? $wbgDetailSettings['wbg_description_label'] : 'Description';
$wbg_display_lenders         = isset( $wbgDetailSettings['wbg_display_lenders'] ) ? $wbgDetailSettings['wbg_display_lenders'] : '1';
$wbg_lenders_label           = isset( $wbgDetailSettings['wbg_lenders_label'] ) ? $wbgDetailSettings['wbg_lenders_label'] : 'Lenders';
?>

<div class="wbg-details-wrapper">
    <?php
    if (have_posts()) {
        the_post(); ?>

        <div class="wbg-details-image">
            <?php
            if ( has_post_thumbnail() ) {
                the_post_thumbnail('full');
            } else {
            ?>
                <img src="<?php echo esc_attr( WBG_ASSETS . 'img/noimage.jpg' ); ?>" alt="No Image Available">
            <?php } ?>
        </div>
        <div class="wbg-details-summary">
            <h5 class="wbg-details-book-title"><?php the_title(); ?></h5>
            <?php
            if( 1 == $wbgAuthorInfo ) {
                ?>
                <span>
                    <b><?php echo esc_html( $wbgAuthorLabel ); ?>:</b>
                    <?php
                    $wbgAuthor = get_post_meta($post->ID, 'wbg_author', true);
                    if (!empty($wbgAuthor)) {
                        echo $wbgAuthor;
                    }
                    ?>
                </span>
                <?php
            }

            if( 1 == $wbgDisplayCategory ) {
                ?>
                <span>
                    <b><?php echo esc_html( $wbgCategoryLabel ); ?>:</b>
                    <?php
                    $wbgCatArray = array();
                    $wbgCategory = wp_get_post_terms( $post->ID, 'book_category', array('fields' => 'all') );
                    foreach( $wbgCategory as $cat) {
                        $wbgCatArray[] = $cat->name . '';
                    }
                    echo implode( ', ', $wbgCatArray );
                    ?>
                </span>
                <?php
            } ?>
            <?php if (1 == $wbgDisplayPublisher) { ?>
            <span>
                <b><?php echo esc_html($wbgPublisherLabel); ?>:</b>
                <?php
                            $wbgPublisher = get_post_meta($post->ID, 'wbg_publisher', true);
                            if (!empty($wbgPublisher)) {
                                echo $wbgPublisher;
                            }
                            ?>
            </span>
            <?php } ?>
            <?php if ( 1 == intval( $wbg_display_publish_date ) ) { ?>
            <span>
                <b><?php echo esc_html($wbg_publish_date_label); ?>:</b>
                <?php
                    $wbgPublished = get_post_meta($post->ID, 'wbg_published_on', true);
                    if( ! empty( $wbgPublished ) ) {
                        if( 'full' === $wbg_publish_date_format ) {
                            echo date('d M, Y', strtotime( $wbgPublished ) );
                        } else {
                            echo date('Y', strtotime( $wbgPublished ) );
                        }
                    }
                ?>
            </span>
            <?php } ?>
            <?php if ( '1' == $wbg_display_isbn ) { ?>
                <span>
                    <b><?php echo esc_html( $wbg_isbn_label ); ?>:</b>
                    <?php
                            $wbgIsbn = get_post_meta($post->ID, 'wbg_isbn', true);
                            if (!empty($wbgIsbn)) {
                                echo $wbgIsbn;
                            }
                            ?>
                </span>
            <?php } ?>
            <?php if ( '1' == $wbg_display_page ) { ?>
                <span>
                    <b><?php echo esc_html( $wbg_page_label ); ?>:</b>
                    <?php
                            $wbgPages = get_post_meta($post->ID, 'wbg_pages', true);
                            if (!empty($wbgPages)) {
                                echo $wbgPages . ' Pages';
                            }
                            ?>
                </span>
            <?php } ?>
            <?php if ( '1' == $wbg_display_country ) { ?>
                <span>
                    <b><?php echo esc_html( $wbg_country_label ); ?>:</b>
                    <?php
                            $wbgCountry = get_post_meta($post->ID, 'wbg_country', true);
                            if (!empty($wbgCountry)) {
                                echo $wbgCountry;
                            }
                            ?>
                </span>
            <?php } ?>
            <?php if ( '1' == $wbg_display_language ) { ?>
                <span>
                    <b><?php echo esc_html( $wbg_language_label ); ?>:</b>
                    <?php
                            $wbgLanguage = get_post_meta($post->ID, 'wbg_language', true);
                            if (!empty($wbgLanguage)) {
                                echo $wbgLanguage;
                            }
                            ?>
                </span>
            <?php } ?>
            <?php if ( '1' == $wbg_display_dimension ) { ?>
                <span>
                    <b><?php echo esc_html( $wbg_dimension_label ); ?>:</b>
                    <?php
                            $wbgDimension = get_post_meta($post->ID, 'wbg_dimension', true);
                            if (!empty($wbgDimension)) {
                                echo $wbgDimension;
                            }
                            ?>
                </span>
            <?php } ?>
            <?php if ( '1' == $wbg_display_filesize ) { ?>
                <span>
                    <b><?php echo esc_html( $wbg_filesize_label ); ?>:</b>
                    <?php
                            $wbgFilesize = get_post_meta($post->ID, 'wbg_filesize', true);
                            if (!empty($wbgFilesize)) {
                                echo $wbgFilesize;
                            }
                            ?>
                </span>
            <?php } ?>
            <?php
            if ( '1' == $wbg_display_download_button ) { ?>
                <?php
                $wbgLink = get_post_meta($post->ID, 'wbg_download_link', true);
                if ( $wbgLink !== '' ) {
                    if ( $wbg_download_button_label !== '' ) {
                    ?>
                    <span>
                        <a href="<?php echo esc_url( $wbgLink ); ?>" target="blank" class="button wbg-btn"><?php echo esc_html( $wbg_download_button_label ); ?></a>
                    </span>
                    <?php
                    }
                }
            }
            ?>
        </div>

        <div class="wbg-details-description">
            <?php if ( '1' == $wbg_display_description ) { ?>
                <?php if( ! empty( get_the_content() ) ) { ?>
                    <div class="wbg-details-description-title">
                        <b><?php echo esc_html( $wbg_description_label ); ?>:</b>
                        <hr>
                    </div>
                    <div class="wbg-details-description-content">
                        <?php the_content(); ?>
                    </div>
                <?php } ?>
            <?php } ?>
        </div>

        <?php if ( '1' == $wbg_display_lenders && current_user_can( 'read_wbg_lenders' ) ) { ?>
        <div class="wbg-details-lenders">
            <div class="wbg-details-lenders-title">
                <b><?php echo esc_html( $wbg_lenders_label ); ?>:</b>
                <hr>
            </div>
            <div class="wbg-details-lenders-content">
                <span>
                <form action="" method="POST" id="wbg-lender-form">
                <?php
                    $lenders = WBG_Admin::wbg_book_club_lenders($book_lenders);
                    if ( current_user_can( 'manage_wbg_lenders' ) ) {
                        $lenders = WBG_Admin::wbg_book_club_lenders();
                    }

                    // TODO: Move all this logic to a helper method.
                    foreach ( $lenders as $lender ) :
                        $id = $lender->data->ID;
                        $email = $lender->data->user_email;
                        $first_name = get_user_meta( $id, 'first_name', true );
                        $last_name = get_user_meta( $id, 'last_name', true );
                        $name = $first_name.' '.$last_name;
                        if ( empty($name) ) {
                            $name = $lender->data->display_name;
                        }

                        $lender_text = $name;
                        $checked = '';
                        if ( current_user_can( 'borrow_wbg_books' ) && in_array( $id, $book_lenders ) ) {
                            $lender_text = '<a href="mailto:'.$email.'">'.$name.'</a>';
                            // When BuddyPress is installed and enabled the link points to the user profile.
                            if ( function_exists( 'bp_core_get_userlink' ) ) {
                                $lender_text = bp_core_get_userlink( $id );
                            }
                            $checked = 'checked="checked"';
                        }

                        echo '<p>'."\n";
                        if ( current_user_can( 'manage_wbg_lenders' ) ) {
                            echo '  <input type="checkbox" name="wbg_lenders[]" value="'.$id.'" '.$checked.'>'."\n";
                        }
                        echo '  <span>'.$lender_text.'</span>'."\n";
                        echo '</p>'."\n";
                    endforeach;
                    if ( current_user_can( 'manage_wbg_lenders' ) ) {
                        echo '<div><input type="submit" name="update_lenders" class="button submit-btn" value="'.esc_attr( "Update lenders for this book" ).'"></div>';
                    } elseif ( current_user_can( 'lend_wbg_books' ) ) {
                        if ( in_array( $user->ID, $book_lenders ) ) {
                            echo '<div><input type="submit" name="remove_me_as_lender" class="button submit-btn" value="'.esc_attr( "Remove me as lender for this book" ).'"></div>';
                        } else {
                            echo '<div><input type="submit" name="add_me_as_lender" class="button submit-btn" value="'.esc_attr( "Add me as a lended of this book" ).'"></div>';
                        }
                    }
                ?>
                </form>
                </span>
            </div>
        </div>
        <?php } ?>

    <?php } ?>

</div>

<?php get_footer(); ?>
