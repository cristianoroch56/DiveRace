<?php

namespace WP_Business_Reviews_Bundle\Includes;

class View {

    public function render($collection_id, $businesses, $reviews, $options) {
        ob_start();

        $max_width = $options->max_width;
        if (is_numeric($max_width)) {
            $max_width = $max_width . 'px';
        }

        $max_height = $options->max_height;
        if (is_numeric($max_height)) {
            $max_height = $max_height . 'px';
        }

        ?>
        <div class="rplg"<?php if (strlen($options->schema_rating) > 0) { ?> itemscope="" itemtype="http://schema.org/LocalBusiness"<?php } ?> style="<?php if (strlen($max_width) > 0) { ?>width:<?php echo $max_width;?>!important;<?php } ?><?php if (strlen($max_height) > 0) { ?>height:<?php echo $max_height;?>!important;overflow-y:auto!important;<?php } ?><?php if ($options->centred) { ?>margin:0 auto!important;<?php } ?>" data-id="<?php echo $collection_id; ?>">

        <?php
            switch ($options->view_mode) {
                case 'flash':
                    $this->render_flash($reviews, $options);
                    break;
                case 'list':
                    $this->render_list($businesses, $reviews, $options);
                    break;
                case 'list_thin':
                    $this->render_list_thin($businesses, $reviews, $options);
                    break;
                case 'grid4':
                    $this->render_grid($businesses, $reviews, $options, 4);
                    break;
                case 'grid3':
                    $this->render_grid($businesses, $reviews, $options, 3);
                    break;
                case 'grid2':
                    $this->render_grid($businesses, $reviews, $options, 2);
                    break;
                case 'slider':
                    $this->render_slider($businesses, $reviews, $options);
                    break;
                case 'temp':
                    $this->rating_temp($businesses, $reviews, $options);
                    break;
                default:
                    $this->render_badge2($businesses, $reviews, $options);
            }
        ?>
        </div>
        <?php
        return preg_replace('/[\n\r]|(>)\s+(<)/', '$1$2', ob_get_clean());
    }

    private function render_flash($reviews, $options) {
        ?>
        <div class="rplg-flash">
            <div class="rplg-flash-wrap<?php if ($options->flash_pos == 'right') { ?> rplg-flash-right<?php } ?>">
                <div class="rplg-flash-content">
                    <div class="rplg-flash-card">
                        <div class="rplg-flash-story"></div>
                        <div class="rplg-flash-form">
                            <div class="rplg-reviews">
                                <?php
                                $hide_review = false;
                                if (count($reviews) > 0) {
                                    $i = 0;
                                    foreach ($reviews as $review) {
                                        if ($options->pagination > 0 && $options->pagination <= $i) {
                                            $hide_review = true;
                                        }
                                        ?>
                                        <div class="rplg-form-review<?php if ($hide_review) {?> rplg-hide<?php } ?>" data-idx="<?php echo $i++; ?>">
                                        <?php
                                        $this->review_thin($review, $options, true);
                                        ?>
                                        </div>
                                        <?php
                                    }
                                }
                                ?>
                            </div>
                            <?php
                            if ($options->pagination > 0 && $hide_review) {
                                $this->anchor('#', 'rplg-url', __('Next Reviews', 'brb'), false, false, 'return rplg_next_reviews.call(this, ' . $options->pagination . ');');
                            }
                            ?>
                        </div>
                    </div>
                    <div class="rplg-flash-x">×</div>
                </div>
            </div>
        </div>
        <?php
        $this->js_loader('rplg_init_flash_theme', json_encode(
            array(
                'flash_start'         => $options->flash_start     ? $options->flash_start     : 3,
                'flash_visible'       => $options->flash_visible   ? $options->flash_visible   : 5,
                'flash_invisible'     => $options->flash_invisible ? $options->flash_invisible : 5,
                'flash_user_photo'    => $options->flash_user_photo,
                'flash_hide_logo'    => $options->flash_hide_logo,
                'hide_avatar'         => $options->hide_avatar,
                'hide_name'           => $options->hide_name,
                'disable_review_time' => $options->disable_review_time,
                'time_format'         => $options->time_format,
                'text' => array(
                    'm1' => __('just left us a %s star review', 'brb'),
                    'm2' => __('on', 'brb')
                )
            )
        ));
    }

    private function render_list($businesses, $reviews, $options) {
        ?>
        <div class="rplg-list2<?php if ($options->dark_theme) { ?> rplg-dark<?php } ?>">
            <div class="rplg-businesses">
                <?php
                foreach ($businesses as $business) {
                    $this->business($business, $options);
                }
                ?>
            </div>
            <div class="rplg-reviews">
                <?php
                $hide_review = false;
                if (count($reviews) > 0) {
                    $i = 0;
                    foreach ($reviews as $review) {
                        if ($options->pagination > 0 && $options->pagination <= $i++) {
                            $hide_review = true;
                        }
                        $this->review($review, $options, true, $hide_review);
                    }
                }
                ?>
            </div>
            <?php
            if ($options->pagination > 0 && $hide_review) {
                $this->anchor('#', 'rplg-url', __('Next Reviews', 'brb'), false, false, 'return rplg_next_reviews.call(this, ' . $options->pagination . ');');
            }
            ?>
        </div>
        <?php
        $this->js_loader('rplg_init_list_theme');
    }

    private function render_list_thin($businesses, $reviews, $options) {
        ?>
        <div class="rplg-list<?php if ($options->dark_theme) { ?> rplg-dark<?php } ?>">
            <div class="rplg-businesses">
                <?php
                foreach ($businesses as $business) {
                    $this->business_thin($business, $options);
                }
                ?>
            </div>
            <?php if (count($businesses) > 0) { ?><div class="rplg-hr2"></div><?php } ?>
            <div class="rplg-reviews">
                <?php
                $hide_review = false;
                if (count($reviews) > 0) {
                    $i = 0;
                    foreach ($reviews as $review) {
                        if ($options->pagination > 0 && $options->pagination <= $i++) {
                            $hide_review = true;
                        }
                        $this->review_thin($review, $options, true, $hide_review);
                    }
                }
                ?>
            </div>
            <?php
            if ($options->pagination > 0 && $hide_review) {
                $this->anchor('#', 'rplg-url', __('Next Reviews', 'brb'), false, false, 'return rplg_next_reviews.call(this, ' . $options->pagination . ');');
            }
            ?>
        </div>
        <?php
        $this->js_loader('rplg_init_list_theme');
    }

    private function render_grid($businesses, $reviews, $options, $column_default = 4) {
        ?>
        <div class="rplg-grid<?php if ($options->dark_theme) { ?> rplg-dark<?php } ?>">
            <?php
            $count = count($businesses);
            if ($count > 0 && $businesses[0]->provider == 'summary') {
                ?>
                <div class="rplg-grid-row rplg-businesses">
                    <div class="rplg-col rplg-col-12">
                        <?php
                        $this->business($businesses[0], $options);
                        array_shift($businesses);
                        ?>
                    </div>
                </div>
                <?php
            }
            ?>
            <div class="rplg-grid-row rplg-businesses">
                <?php
                $count_biz = $options->summary_rating ? $count - 1 : $count;
                switch ($count_biz) {
                    case 1:
                        $col = 12;
                        break;
                    case 2:
                    case 4:
                        $col = 6;
                        break;
                    case 3:
                        $col = 3;
                        break;
                    default:
                        $col = $column_default;
                }
                if ($count_biz > 0) {
                    foreach ($businesses as $business) {
                        $col_class = 'rplg-col-' . $col;
                        ?><div class="rplg-col <?php echo $col_class; ?>"><?php
                        $this->business($business, $options);
                        ?></div><?php
                    }
                }
                ?>
            </div>
            <div class="rplg-grid-row rplg-reviews">
                <?php
                $count = count($reviews);
                switch ($count) {
                    case 1:
                        $col = 12;
                        break;
                    case 2:
                        $col = 6;
                        break;
                    default:
                        $col = $column_default;
                }
                $hide_review = false;
                if ($count > 0) {
                    $i = 0;
                    foreach ($reviews as $review) {
                        $col_class = 'rplg-col-' . $col;
                        if ($options->pagination > 0 && $options->pagination <= $i++) {
                            $hide_review = true;
                        }
                        ?><div class="rplg-col <?php echo $col_class; if ($hide_review) { ?> rplg-hide<?php } ?>"><?php
                        $this->review($review, $options);
                        ?></div><?php
                    }
                }
                ?>
            </div>
        </div>
        <?php
        if ($options->pagination > 0 && $hide_review) {
            $this->anchor('#', 'rplg-url', __('Next Reviews', 'brb'), false, false, 'return rplg_next_reviews.call(this, ' . $options->pagination . ');');
        }
        $this->js_loader('rplg_init_grid_theme');
    }

    private function render_slider($businesses, $reviews, $options) {
        ?>
        <?php if (count($businesses)) { ?>
        <div class="rplg-grid<?php if ($options->dark_theme) { ?> rplg-dark<?php } ?>">
            <?php
            if ($businesses[0]->provider == 'summary') {
                ?>
                <div class="rplg-grid-row rplg-businesses">
                    <div class="rplg-col rplg-col-12">
                        <?php
                        $this->business($businesses[0], $options);
                        array_shift($businesses);
                        ?>
                    </div>
                </div>
                <?php
            }
            ?>
            <div class="rplg-grid-row rplg-businesses">
                <?php
                $count = count($businesses);
                switch ($count) {
                    case 1:
                        $col = 12;
                        break;
                    case 2:
                    case 4:
                        $col = 6;
                        break;
                    case 3:
                        $col = 3;
                        break;
                    default:
                        $col = 3;
                }
                if ($count > 0) {
                    foreach ($businesses as $business) {
                        $col_class = 'rplg-col-' . $col;
                        ?><div class="rplg-col <?php echo $col_class; ?>"><?php
                        $this->business($business, $options);
                        ?></div><?php
                    }
                }
                ?>
            </div>
        </div>
        <?php } ?>
        <div class="rplg-slider<?php if ($options->dark_theme) { ?> rplg-dark<?php } ?>">
            <div class="rplgsw-container">
                <div class="rplgsw-wrapper">
                    <?php foreach ($reviews as $review) { ?>
                    <div class="rplgsw-slide">
                        <?php $this->slider_review($review, $options); ?>
                    </div>
                    <?php } ?>
                </div>
                <?php if (!$options->slider_hide_pagin) { ?>
                <div class="rplgsw-pagination"></div>
                <?php } ?>
            </div>
            <?php if (!$options->slider_hide_nextprev) { ?>
            <div class="rplg-slider-prev"><span>&lsaquo;</span></div>
            <div class="rplg-slider-next"><span>&rsaquo;</span></div>
            <?php } ?>
        </div>
        <?php
        $this->js_loader('rplg_init_slider_theme', json_encode(
            array(
                'speed'             => ($options->slider_speed             ? $options->slider_speed              : 5) * 1000,
                'effect'            => $options->slider_effect             ? $options->slider_effect             : 'slide',
                'count'             => $options->slider_count              ? $options->slider_count              : 3,
                'space'             => $options->slider_space_between      ? $options->slider_space_between      : 40,
                'pagin'             => !$options->slider_hide_pagin        || true,
                'nextprev'          => !$options->slider_hide_nextprev     || true,
                'mobileBreakpoint'  => $options->slider_mobile_breakpoint  ? $options->slider_mobile_breakpoint  : 500,
                'mobileCount'       => $options->slider_mobile_count       ? $options->slider_mobile_count       : 1,
                'tabletBreakpoint'  => $options->slider_tablet_breakpoint  ? $options->slider_tablet_breakpoint  : 800,
                'tabletCount'       => $options->slider_tablet_count       ? $options->slider_tablet_count       : 2,
                'desktopBreakpoint' => $options->slider_desktop_breakpoint ? $options->slider_desktop_breakpoint : 1024,
                'desktopCount'      => $options->slider_desktop_count      ? $options->slider_desktop_count      : 3
            )
        ));
    }

    private function render_badge($businesses, $reviews, $options) {
        ?>
        <div class="rplg-badge-cnt
                    <?php if ($options->view_mode != 'badge_inner') { ?> rplg-<?php echo $options->view_mode; ?>-fixed<?php } ?>
                    <?php if ($options->badge_center) { ?> rplg-badge-center<?php } ?>
                    <?php if ($options->hide_float_badge) { ?> rplg-badge-hide<?php } ?>
        ">
            <?php foreach ($businesses as $business) { ?>
            <div
                class="rplg-badge
                    <?php if ($options->badge_display_block) { ?>rplg-badge-block<?php } ?>
                    <?php if ($options->badge_click) { ?>rplg-badge-clickable<?php } ?>
                "
                <?php
                    if (strlen($options->badge_space_between) > 0) {
                        $space = $options->badge_space_between;
                        if (is_numeric($space)) {
                            $space = $space . 'px';
                        }
                ?>style="margin:0 <?php echo $space . ' ' . $space; ?> 0!important;"<?php
                    }
                    if ($business->provider != 'summary') {
                        if ($options->badge_click == 'reviews') {
                ?>onclick="window.open('<?php echo $this->get_allreview_url($business, $options->google_def_rev_link); ?>', '_blank');"<?php
                        }
                        if ($options->badge_click == 'writereview') {
                ?>onclick="_rplg_popup('<?php echo $this->get_writereview_url($business); ?>', 800, 600)"<?php
                        }
                    }
                ?>
                data-provider="<?php echo $business->provider; ?>"
            >
                <?php
                $rich_snippets = false;
                $business_name = $business->name;
                $business_photo = '';
                if ($options->schema_rating && $options->schema_rating == $business->id) {
                    $this->render_schema_fields($options);
                    $rich_snippets = true;
                    $business_name = '<span itemprop="name">' . $business->name . '</span>';
                    $business_photo = '<meta itemprop="image" content="' . ($this->correct_url_proto($business->photo)) . '" name="' . $business->name . '"/>';
                }
                ?>
                <div class="rplg-badge-btn">
                    <div class="rplg-row">
                        <?php if (!$options->header_hide_photo) { ?>
                        <div class="rplg-row-left">
                            <div class="rplg-badge-logo">
                            <?php if ($business->provider == 'summary') {
                                $this->image($business->photo, $business->name, $options->lazy_load_img, '44', '44');
                                echo $business_photo;
                            } ?>
                            </div>
                        </div>
                        <?php } ?>
                        <div class="rplg-row-right rplg-trim">
                            <?php
                            if (!$options->header_hide_name) { echo $business_name; }
                            $this->render_rating($business, $options, $rich_snippets);
                            ?>
                        </div>
                    </div>
                    <?php if ($business->provider != 'summary' &&  (!$options->header_hide_seeall || !$options->header_hide_write)) { ?>
                    <button class="rplg-badge-menu" onclick="this.nextSibling.style.display=(this.nextSibling.style.display=='none'?'block':'none');return false;">
                        <svg viewBox="0 0 12 12"><use xlink:href="#dots"/></svg>
                    </button>
                    <div class="rplg-badge-actions" style="display:none">
                        <?php $this->render_action_links($business, $options, true); ?>
                    </div>
                    <?php } ?>
                </div>
            </div>
            <?php } ?>
        </div>
        <?php
        $this->js_loader('rplg_init_badge_theme');
    }

    private function render_badge2($businesses, $reviews, $options) {
        ?>
        <div class="rplg-badge-cnt
                    <?php if ($options->view_mode != 'badge_inner') { ?> rplg-<?php echo $options->view_mode; ?>-fixed<?php } ?>
                    <?php if ($options->badge_center) { ?> rplg-badge-center<?php } ?>
                    <?php if ($options->hide_float_badge) { ?> rplg-badge-hide<?php } ?>
        ">
            <?php foreach ($businesses as $business) { ?>
            <div class="rplg-badge2<?php if ($options->badge_display_block) { ?> rplg-badge-block<?php } ?>"
                <?php
                if (strlen($options->badge_space_between) > 0) {
                    $space = $options->badge_space_between;
                    if (is_numeric($space)) {
                        $space = $space . 'px';
                    }
                ?>style="margin:0 <?php echo $space . ' ' . $space; ?> 0!important;"<?php
                }
                ?>
                data-provider="<?php echo $business->provider; ?>"
            >
                <div class="rplg-badge2-border"></div>
                <?php
                $rich_snippets = false;
                if ($options->schema_rating && $options->schema_rating == $business->id) {
                    echo '<meta itemprop="name" content="' . $business->name . '">' .
                         '<meta itemprop="image" content="' . ($this->correct_url_proto($business->photo)) . '" name="' . $business->name . '"/>';
                    $this->render_schema_fields($options);
                    $rich_snippets = true;
                }
                ?>
                <div class="rplg-badge2-btn<?php if ($options->badge_click != 'disable') {?> rplg-badge2-clickable<?php } ?>"
                    <?php
                    if ($business->provider != 'summary') {
                        if ($options->badge_click == 'reviews') {
                ?>onclick="window.open('<?php echo $this->get_allreview_url($business, $options->google_def_rev_link); ?>', '_blank');return false;"<?php
                        }
                        if ($options->badge_click == 'writereview') {
                ?>onclick="_rplg_popup('<?php echo $this->get_writereview_url($business); ?>', 800, 600);return false;"<?php
                        }
                    }
                    ?>
                >
                    <span class="rplg-badge-logo">
                        <?php
                        $provider_name = ucfirst($business->provider);
                        if ($business->provider == 'summary') {
                            $this->image($business->photo, $business->name, $options->lazy_load_img, '44', '44');
                            $provider_name = 'Social';
                        }
                        ?>
                    </span>
                    <div class="rplg-badge2-score">
                        <div><?php printf(esc_html__('%s Rating', 'brb'), $provider_name); ?></div>
                        <?php $this->render_rating($business, $options, $rich_snippets); ?>
                    </div>
                </div>
                <?php if ($options->badge_click == 'sidebar') { ?>
                <div class="rplg-form <?php if ($options->view_mode == 'badge_left') { ?>rplg-form-left<?php } ?>" style="display:none">
                    <div class="rplg-form-head">
                        <div class="rplg-form-head-inner">
                            <div class="rplg-row">
                                <?php if (!$options->header_hide_photo) { ?>
                                <div class="rplg-row-left">
                                    <?php $this->image($business->photo, $business->name, $options->lazy_load_img, '50', '50'); ?>
                                </div>
                                <?php } ?>
                                <div class="rplg-row-right rplg-trim">
                                    <?php
                                    echo $business->name;
                                    $this->render_rating($business, $options, false, false);
                                    ?>
                                </div>
                            </div>
                        </div>
                        <button class="rplg-form-close" type="button" onclick="_rplg_get_parent(this, 'rplg-form').style.display='none'">×</button>
                    </div>
                    <div class="rplg-form-body"></div>
                    <div class="rplg-form-content">
                        <div class="rplg-form-content-inner">
                            <?php
                            $hide_review = false;
                            $i = 0;
                            foreach ($reviews as $review) {
                                if ($business->provider == 'summary'
                                    || ($options->header_merge_social && $review->provider == $business->provider)
                                    || $review->biz_id == $business->id) {

                                    if ($options->pagination > 0 && $options->pagination <= $i++) {
                                        $hide_review = true;
                                    }
                            ?>
                            <div class="rplg-form-review <?php if ($hide_review) { ?> rplg-hide<?php } ?>">
                                <div class="rplg-row rplg-row-start">
                                    <?php if (!$options->hide_avatar) { ?>
                                    <div class="rplg-row-left">
                                        <?php $this->author_avatar($review, $options, '50', '50'); ?>
                                    </div>
                                    <?php } ?>
                                    <div class="rplg-row-right">
                                        <?php
                                        $this->author_name($review, $options);
                                        $this->review_time($review, $options);
                                        ?>
                                        <div class="rplg-box-content">
                                            <?php $this->stars($review->rating, $review->provider); ?>
                                            <span class="rplg-review-text"><?php if (isset($review->text)) { $this->trim_text($review->text, $options->text_size); } ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                                }
                            }
                            if ($options->pagination > 0 && $hide_review) {
                                $this->anchor('#', 'rplg-url', __('Next Reviews', 'brb'), false, false, 'return rplg_next_reviews.call(this, ' . $options->pagination . ');');
                            }
                            $this->render_action_links($business, $options);
                            ?>
                        </div>
                    </div>
                    <div class="rplg-form-footer">

                        <?php
                        switch ($business->provider) {
                            case 'summary':
                                ?><div class="rplg-powered"></div><?php
                                break;
                            case 'google':
                                ?><img src="<?php echo BRB_ASSETS_URL; ?>img/powered_by_google_on_white.png" alt="powered by Google" width="144" height="18" title="powered by Google"><?php
                                break;
                            case 'facebook':
                                ?><div class="rplg-powered rplg-facebook-powered">powered by <span>Facebook</span></div><?php
                                break;
                            case 'yelp':
                                ?><div class="rplg-powered rplg-yelp-logo">powered by <?php echo $this->anchor($business->url, '', '<img src="' . BRB_ASSETS_URL . 'img/yelp-logo.png" alt="Yelp logo" width="60" height="31" title="Yelp logo">', $options->open_link, $options->nofollow_link); ?></div><?php
                                break;
                        }
                        ?>
                    </div>
                </div>
                <?php } if ($options->view_mode != 'badge_inner' && $options->badge_close) { echo '<div class="rplg-badge2-close">×</div>'; } ?>
            </div>
            <?php } ?>
        </div>
        <?php
        $this->js_loader('rplg_init_badge_theme');
    }

    private function rating_temp($businesses, $reviews, $options) {

        if ($options->rating_temp_on) {
        ?><div class="rplg-reviews"><?php

            $temp_rating = isset($options->rating_temp) && strlen($options->rating_temp) > 0 ?

                           urldecode($options->rating_temp) :

                           '<div class="rplg-rating">' .
                             '{{photo}} <a href="javascript:_rplg_popup(\'{{writereview_url}}\',620,580)">{{name}}</a>' .
                             '<span{{aggr}}>' .
                               '{{stars}}' .
                               '<span class="rplg-rating-info">' .
                                 '{{rating}} Stars - <a href="{{reviews_url}}" target="_blank" rel="noopener">{{count}} Reviews</a>' .
                               '</span>' .
                             '</span>' .
                           '</div>';

            foreach ($businesses as $business) {
                $aggregate_rating = '';
                $business_name    = '<span class="rplg-rating-name">' . $business->name . '</span>';
                $rating_value     = '<span class="rplg-rating-value">' . $business->rating . '</span>';
                $review_count     = isset($business->review_count_manual) && $business->review_count_manual > 0 ?
                                    $business->review_count_manual : $business->review_count;
                $rating_count     = '<span class="rplg-rating-count">' . $review_count . '</span>';
                if ($options->schema_rating && $options->schema_rating == $business->id) {
                    $this->render_schema_fields($options);
                    echo '<meta itemprop="image" content="' . ($this->correct_url_proto($business->photo)) . '" name="' . $business->name . '"/>';
                    $business_name = '<span class="rplg-rating-name" itemprop="name">' . $business->name . '</span>';
                    $aggregate_rating  = ' itemprop="aggregateRating" itemscope="" itemtype="http://schema.org/AggregateRating"';
                    $rating_value = '<span class="rplg-rating-value" itemprop="ratingValue">' . $business->rating . '</span>';
                    $rating_count = '<span class="rplg-rating-count" itemprop="ratingCount">' . $review_count . '</span>' .
                                    '<meta itemprop="bestRating" content="5"/>';
                }

                ob_start();
                $this->image($business->photo, $business->name, $options->lazy_load_img);
                $business_photo = ob_get_contents();
                ob_end_clean();

                ob_start();
                $this->stars($business->rating, $business->provider, '#0caa41');
                $stars = ob_get_contents();
                ob_end_clean();

                $temp = $temp_rating;
                $temp = str_replace('{{name}}',            $business_name,                                                     $temp);
                $temp = str_replace('{{photo}}',           $business_photo,                                                    $temp);
                $temp = str_replace('{{aggr}}',            $aggregate_rating,                                                  $temp);
                $temp = str_replace('{{stars}}',           $stars,                                                             $temp);
                $temp = str_replace('{{rating}}',          $rating_value,                                                      $temp);
                $temp = str_replace('{{count}}',           $rating_count,                                                      $temp);
                $temp = str_replace('{{reviews_url}}',     $this->get_allreview_url($business, $options->google_def_rev_link), $temp);
                $temp = str_replace('{{writereview_url}}', $this->get_writereview_url($business),                              $temp);
                echo $temp;
            }
        ?></div><?php
        }

        if ($options->review_temp_on) {
        ?><div class="rplg-reviews"><?php

            $temp_review = isset($options->review_temp) && strlen($options->review_temp) > 0 ?

                           urldecode($options->review_temp) :

                           '<br>' .
                           '<div>' .
                             '{{review_photo}} ' .
                             '{{review_name_url}} ' .
                             '{{review_time}} {{review_badge}}' .
                           '</div>' .
                           '<div>' .
                             '{{review_stars}} {{review_text}}' .
                           '</div>';

            $hide_review = false;
            if (count($reviews) > 0) {
                $i = 0;
                foreach ($reviews as $review) {
                    if ($options->pagination > 0 && $options->pagination <= $i++) {
                        $hide_review = true;
                    }

                    ob_start();
                    $this->author_avatar($review, $options);
                    $review_photo = ob_get_contents();
                    ob_end_clean();

                    $review_provider = $review->provider;
                    $review_badge    = '<span data-badge="' . $review_provider. '"></span>';

                    $review_url      = strlen($review->author_url) > 0 ? $review->author_url : '';
                    $review_name     = $this->short_last_name($review, $options->short_last_name);

                    if (strlen($review_url) > 0) {
                        ob_start();
                        $this->anchor($review_url, '', $review_name, $options->open_link, $options->nofollow_link);
                        $review_name_url = ob_get_contents();
                        ob_end_clean();
                    } else {
                        $review_name_url = $review_name;
                    }

                    if (strlen($options->time_format) > 0) {
                        $review_time = gmdate($options->time_format, $review->time);
                    } else {
                        $review_time = '<span data-time="' . $review->time . '">' . gmdate("H:i m/d/y", $review->time) . '</span>';
                    }

                    ob_start();
                    $this->stars($review->rating, $review->provider);
                    $review_stars = ob_get_contents();
                    ob_end_clean();

                    ob_start();
                    if (isset($review->text)) {
                        $this->trim_text($review->text, $options->text_size);
                        $review_text = ob_get_contents();
                    } else {
                        $review_text = '';
                    }
                    ob_end_clean();

                    $temp = $temp_review;
                    $temp = str_replace('{{review_photo}}',    $review_photo,    $temp);
                    $temp = str_replace('{{review_provider}}', $review_provider, $temp);
                    $temp = str_replace('{{review_badge}}',    $review_badge,    $temp);
                    $temp = str_replace('{{review_url}}',      $review_url,      $temp);
                    $temp = str_replace('{{review_name}}',     $review_name,     $temp);
                    $temp = str_replace('{{review_name_url}}', $review_name_url, $temp);
                    $temp = str_replace('{{review_time}}',     $review_time,     $temp);
                    $temp = str_replace('{{review_stars}}',    $review_stars,    $temp);
                    $temp = str_replace('{{review_text}}',     $review_text,     $temp);
                    echo '<div class="rplg-review' . ($hide_review ? ' rplg-hide' : '') . '">' . $temp . '</div>';
                }
            }
        ?></div><?php

            if ($options->pagination > 0 && $hide_review) {
                $this->anchor('#', 'rplg-url', __('Next Reviews', 'brb'), false, false, 'return rplg_next_reviews.call(this, ' . $options->pagination . ');');
            }
        }

        $this->js_loader('rplg_init_temp_theme');
    }

    private function business($business, $options) {
        $hide_photo    = $options->header_hide_photo;
        $hide_name     = $options->header_hide_name;
        $hide_count    = $options->header_hide_count;
        $open_link     = $options->open_link;
        $nofollow_link = $options->nofollow_link;
        $lazy_load_img = $options->lazy_load_img;

        $rich_snippets = false;
        $business_name = $business->name;
        $business_photo = '';
        if ($options->schema_rating && $options->schema_rating == $business->id) {
            $this->render_schema_fields($options);
            $rich_snippets = true;
            $business_name = '<span itemprop="name">' . $business->name . '</span>';
            $business_photo = '<meta itemprop="image" content="' . ($this->correct_url_proto($business->photo)) . '" name="' . $business->name . '"/>';
        }
        ?>
        <div class="rplg-box">
            <div class="rplg-row">
                <?php if (!$hide_photo) { ?>
                <div class="rplg-row-left">
                    <?php $this->image($business->photo, $business->name, $lazy_load_img); echo $business_photo; ?>
                </div>
                <?php } ?>
                <div class="rplg-row-right">
                    <?php if (!$hide_name) { ?>
                    <div class="rplg-biz-name rplg-trim">
                        <?php $this->anchor($business->url, '', $business_name, $open_link, $nofollow_link); ?>
                    </div>
                    <?php
                    }
                    $this->render_rating($business, $options, $rich_snippets);
                    $this->render_action_links($business, $options);
                    ?>
                </div>
                <span class="rplg-review-badge" data-badge="<?php echo $business->provider; ?>"></span>
            </div>
        </div>
        <?php
    }

    private function business_thin($business, $options) {
        $hide_photo    = $options->header_hide_photo;
        $hide_name     = $options->header_hide_name;
        $hide_count    = $options->header_hide_count;
        $open_link     = $options->open_link;
        $nofollow_link = $options->nofollow_link;
        $lazy_load_img = $options->lazy_load_img;

        $rich_snippets = false;
        $business_name = $business->name;
        $business_photo = '';
        if ($options->schema_rating && $options->schema_rating == $business->id) {
            $this->render_schema_fields($options);
            $rich_snippets = true;
            $business_name = '<span itemprop="name">' . $business->name . '</span>';
            $business_photo = '<meta itemprop="image" content="' . ($this->correct_url_proto($business->photo)) . '" name="' . $business->name . '"/>';
        }
        ?>
        <div class="rplg-list-header">
            <div class="rplg-row rplg-row-start">
                <?php if (!$hide_photo) { ?>
                <div class="rplg-row-left">
                    <?php $this->image($business->photo, $business->name, $lazy_load_img); echo $business_photo; ?>
                    <span class="rplg-review-badge" data-badge="<?php echo $business->provider; ?>"></span>
                </div>
                <?php } ?>
                <div class="rplg-row-right">
                    <?php if (!$hide_name) { ?>
                    <div class="rplg-biz-name rplg-trim">
                        <?php $this->anchor($business->url, '', $business_name, $open_link, $nofollow_link); ?>
                    </div>
                    <?php
                    }
                    $this->render_rating($business, $options, $rich_snippets);
                    $this->render_action_links($business, $options);
                    ?>
                </div>
            </div>
        </div>
        <?php
    }

    private function render_schema_fields($options) {
        ?>
        <span itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
            <meta itemprop="streetAddress" content="<?php echo $options->schema_address_street; ?>"/>
            <meta itemprop="addressLocality" content="<?php echo $options->schema_address_locality; ?>"/>
            <meta itemprop="addressRegion" content="<?php echo $options->schema_address_region; ?>"/>
            <meta itemprop="postalCode" content="<?php echo $options->schema_address_zip; ?>"/>
            <meta itemprop="addressCountry" content="<?php echo $options->schema_address_country; ?>"/>
        </span>
        <meta itemprop="priceRange" content="<?php echo $options->schema_price_range; ?>"/>
        <meta itemprop="telephone" content="<?php echo $options->schema_phone; ?>"/>
        <?php
    }

    private function render_rating($business, $options, $rich_snippets = false, $reviews_count = true) {
        $aggregate_rating = '';
        $rating_value = '';
        if ($rich_snippets) {
            $aggregate_rating  = 'itemprop="aggregateRating" itemscope="" itemtype="http://schema.org/AggregateRating"';
            $rating_value = 'itemprop="ratingValue"';
        }
        if ($business->rating > 0) {
        ?>
        <div <?php echo $aggregate_rating; ?>>
            <div class="rplg-biz-rating rplg-trim rplg-biz-<?php echo $business->provider; ?>">
                <div class="rplg-biz-score" <?php echo $rating_value; ?>><?php echo $business->rating; ?></div>
                <?php $this->stars($business->rating, $business->provider, '#0caa41'); ?>
            </div>
            <?php
            if (!$options->header_hide_count && $reviews_count) {
                $this->render_based_on_reviews($business, $rich_snippets);
            }
            ?>
        </div>
        <?php
        } else {
        ?>
        <div>
            <div class="rplg-biz-rating rplg-trim rplg-biz-<?php echo $business->provider; ?>">
                <?php $this->stars($business->rating, $business->provider, '#0caa41'); ?>
            </div>
        </div>
        <?php
        }
    }

    private function render_based_on_reviews($business, $rich_snippets = false) {
        $review_count = isset($business->review_count_manual) && $business->review_count_manual > 0
            ? $business->review_count_manual : $business->review_count;

        if ($rich_snippets) {
            $review_count = '<span itemprop="ratingCount">' . $review_count . '</span>';
        }
        ?>
        <div class="rplg-biz-based rplg-trim">
            <span class="rplg-biz-based-text"><?php printf(esc_html__('Based on %s reviews', 'brb'), $review_count); ?></span>
            <?php if ($rich_snippets) { ?>
            <meta itemprop="bestRating" content="5"/>
            <?php } ?>
        </div>
        <?php
    }

    private function render_action_links($business, $options, $in_menu = false) {
        if ($business->provider != 'summary') {
        ?><div class="rplg-links"><?php
            if (!$options->header_hide_seeall) {
                $this->get_allreview_link($business, $options->google_def_rev_link, $in_menu);
            }
            if (!$options->header_hide_write) {
                $this->get_writereview_link($business, $in_menu);
            }
        ?></div><?php
        }
    }

    private function get_allreview_link($business, $google_def_rev_link, $in_menu = false) {
        ?><a href="<?php echo $this->get_allreview_url($business, $google_def_rev_link); ?>" target="_blank" rel="noopener" onclick="<?php if ($in_menu) { ?>this.parentNode.parentNode.style.display='none';<?php } ?>return true;"><?php echo __('See all reviews', 'brb'); ?></a><?php
    }

    private function get_allreview_url($business, $google_def_rev_link) {
        switch ($business->provider) {
            case 'google':
                return $google_def_rev_link ? $business->url : 'https://search.google.com/local/reviews?placeid=' . $business->id;
            case 'facebook':
                return 'https://facebook.com/' . $business->id . '/reviews';
            case 'yelp':
                return $business->url;
        }
    }

    private function get_writereview_link($business, $in_menu = false) {
        ?><a href="javascript:void(0)" onclick="<?php if ($in_menu) { ?>this.parentNode.parentNode.style.display='none';<?php } ?>_rplg_popup('<?php echo $this->get_writereview_url($business); ?>', 800, 600)"><?php echo __('Write a review', 'brb'); ?></a><?php
    }

    private function get_writereview_url($business) {
        switch ($business->provider) {
            case 'google':
                return 'https://search.google.com/local/writereview?placeid=' . $business->id;
            case 'facebook':
                return 'https://facebook.com/' . $business->id . '/reviews';
            case 'yelp':
                return 'https://www.yelp.com/writeareview/biz/' . $business->id;
        }
    }

    private function review($review, $options, $stars_in_body=false, $hide_review=false) {
        ?>
        <div class="rplg-box<?php if ($hide_review) { ?> rplg-hide<?php } ?>">
            <div class="rplg-row">
                <?php if (!$options->hide_avatar) { ?>
                <div class="rplg-row-left">
                    <?php $this->author_avatar($review, $options); ?>
                </div>
                <?php } ?>
                <div class="rplg-row-right">
                    <?php
                    $this->author_name($review, $options);
                    if (!$stars_in_body) {
                        $this->stars($review->rating, $review->provider);
                    }
                    $this->review_time($review, $options);
                    ?>
                </div>
            </div>
            <div class="rplg-box-content">
                <?php if ($stars_in_body) {
                    $this->stars($review->rating, $review->provider);
                } ?>
                <span class="rplg-review-text"><?php if (isset($review->text)) { $this->trim_text($review->text, $options->text_size); } ?></span>
                <span class="rplg-review-badge" data-badge="<?php echo $review->provider; ?>"></span>
            </div>
        </div>
        <?php
    }

    private function review_thin($review, $options, $stars_in_body=false, $hide_review=false) {
        ?>
        <div class="rplg-list-review<?php if ($hide_review) { ?> rplg-hide<?php } ?>">
            <div class="rplg-row rplg-row-start">
                <?php if (!$options->hide_avatar) { ?>
                <div class="rplg-row-left">
                    <?php $this->author_avatar($review, $options); ?>
                    <span class="rplg-review-badge" data-badge="<?php echo $review->provider; ?>"></span>
                </div>
                <?php } ?>
                <div class="rplg-row-right">
                    <?php
                    $this->author_name($review, $options);
                    if (!$stars_in_body) {
                        $this->stars($review->rating, $review->provider);
                    }
                    $this->review_time($review, $options);
                    ?>
                    <?php if ($stars_in_body) {
                        $this->stars($review->rating, $review->provider);
                    } ?>
                    <span class="rplg-review-text"><?php if (isset($review->text)) { $this->trim_text($review->text, $options->text_size); } ?></span>
                </div>
            </div>
        </div>
        <?php
    }

    private function slider_review($review, $options) {
        ?>
        <div class="rplg-slider-review">
            <div class="rplg-box">
                <div class="rplg-box-content" <?php if (strlen($options->slider_review_height) > 0) { echo 'style="height:'. $options->slider_review_height .'!important"'; } ?>>
                    <?php $this->stars($review->rating, $review->provider); ?>
                    <span class="rplg-review-text"><?php if (isset($review->text)) { $this->trim_text($review->text, $options->text_size); } ?></span>
                    <span class="rplg-review-badge" data-badge="<?php echo $review->provider; ?>"></span>
                </div>
            </div>
            <div class="rplg-row">
                <?php if (!$options->hide_avatar) { ?>
                <div class="rplg-row-left">
                    <?php $this->author_avatar($review, $options); ?>
                </div>
                <?php } ?>
                <div class="rplg-row-right">
                    <?php
                    $this->author_name($review, $options);
                    $this->review_time($review, $options);
                    ?>
                </div>
            </div>
        </div>
        <?php
    }

    private function author_avatar($review, $opts, $img_width='56', $img_height='56') {
        switch ($review->provider) {
            case 'google':
                $regexp = '/googleusercontent\.com\/([^\/]+)\/([^\/]+)\/([^\/]+)\/([^\/]+)\/photo\.jpg/';
                $matches = array();
                preg_match($regexp, $review->author_avatar, $matches, PREG_OFFSET_CAPTURE);
                if (count($matches) > 4 && $matches[3][0] == 'AAAAAAAAAAA') {
                    $review->author_avatar = str_replace('/photo.jpg', '/s128-c0x00000000-cc-rp-mo/photo.jpg', $review->author_avatar);
                }
                if (strlen($review->author_avatar) > 0) {
                    if (strpos($review->author_avatar, "s128") != false) {
                        $review->author_avatar = str_replace('s128', 's' . $opts->reviewer_avatar_size, $review->author_avatar);
                    } elseif (strpos($review->author_avatar, "-mo") != false) {
                        $review->author_avatar = str_replace('-mo', '-mo-s' . $opts->reviewer_avatar_size, $review->author_avatar);
                    } else {
                        $review->author_avatar = str_replace('-rp', '-rp-s' . $opts->reviewer_avatar_size, $review->author_avatar);
                    }
                }
                $default_avatar = BRB_GOOGLE_AVATAR;
                break;
            case 'facebook':
                $default_avatar = BRB_FACEBOOK_AVATAR;
                break;
            case 'yelp':
                if (strlen($review->author_avatar) > 0) {
                    $avatar_size = '';
                    if ($opts->reviewer_avatar_size <= 128) {
                        $avatar_size = 'ms';
                    } else {
                        $avatar_size = 'o';
                    }
                    $review->author_avatar = str_replace('ms.jpg', $avatar_size . '.jpg', $review->author_avatar);
                }
                $default_avatar = BRB_YELP_AVATAR;
                break;
        }
        $author_avatar = strlen($review->author_avatar) > 0 ? $review->author_avatar : $default_avatar;
        $author_name = $opts->short_last_name ? $this->get_short_last_name($review->author_name) : $review->author_name;
        $this->image($author_avatar, $author_name, $opts->lazy_load_img, $img_width, $img_height, $default_avatar);
    }

    private function author_name($review, $opts) {
        if ($opts->hide_name) {
            return;
        }

        $author_name = $this->short_last_name($review, $opts->short_last_name);

        if (strlen($review->author_url) > 0 && !$opts->disable_user_link) {
            $this->anchor($review->author_url, 'rplg-review-name rplg-trim', $author_name, $opts->open_link, $opts->nofollow_link, '', $author_name);
        } else {
            echo '<div class="rplg-review-name rplg-trim" title="' . $author_name . '">' . $author_name . '</div>';
        }
    }

    private function short_last_name($review, $short_last_name) {
        if ($this->_strlen($review->author_name) > 0) {
            return $short_last_name ? $this->get_short_last_name($review->author_name) : $review->author_name;
        } else {
            return __(ucfirst($review->provider) . ' User', 'brb');
        }
    }

    private function review_time($review, $opts) {
        if (!$opts->disable_review_time) {
            if (strlen($opts->time_format) > 0) {
                $attr = '';
                $format = $opts->time_format;
            } else {
                $attr = 'data-time="' . $review->time . '"';
                $format = "H:i d M y";
            }
            ?><div class="rplg-review-time rplg-trim" <?php echo $attr; ?>><?php echo gmdate($format, $review->time); ?></div><?php
        }
    }

    private function stars($rating, $provider = '', $color = '#777') {
        ?><div class="rplg-stars" data-info="<?php echo $rating . ',' . $provider . ',' . $color ?>"></div><?php
    }

    private function anchor($url, $class, $text, $open_link, $nofollow_link, $onclick = '', $title = '') {
        ?><a href="<?php echo $url; ?>" class="<?php echo $class; ?>" <?php if ($open_link) { ?>target="_blank" rel="noopener"<?php } ?> <?php if ($nofollow_link) { ?>rel="nofollow"<?php } ?> <?php if (strlen($onclick) > 0) { ?>onclick="<?php echo $onclick; ?>"<?php } ?> <?php if ($this->_strlen($title) > 0) { ?>title="<?php echo $title; ?>"<?php } ?>><?php echo $text; ?></a><?php
    }

    function image($src, $alt, $lazy, $width = '56', $height = '56', $def_ava = 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7', $atts = '') {
        ?><img <?php if ($lazy) { ?>src="<?php echo $def_ava; ?>" data-<?php } ?>src="<?php echo $src; ?>" class="rplg-review-avatar<?php if ($lazy) { ?> rplg-blazy<?php } ?>" alt="<?php echo $alt; ?>" width="<?php echo $width; ?>" height="<?php echo $height; ?>" title="<?php echo $alt; ?>" onerror="if(this.src!='<?php echo $def_ava; ?>')this.src='<?php echo $def_ava; ?>';" <?php echo $atts; ?>><?php
    }

    private function js_loader($func, $data = '') {
        ?><img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" alt="" onload="(function(el, data) { var t = setInterval(function () { if (window.<?php echo $func; ?>){ <?php echo $func; ?>(el, data); clearInterval(t); } }, 200); })(this.parentNode<?php if (strlen($data) > 0) { ?>, <?php echo str_replace('"', '\'', $data); } ?>);" data-exec="false" style="display:none"><?php
    }

    private function trim_text($text, $size) {
        if ($size > 0 && $this->_strlen($text) > $size) {
            $sub_text = $this->_substr($text, 0, $size);
            $idx = $this->_strrpos($sub_text, ' ') + 1;

            if ($idx < 1 || $size - $idx > ($size / 2)) {
                $idx = $size;
            }
            if ($idx > 0) {
                $visible_text = $this->_substr($text, 0, $idx - 1);
                $invisible_text = $this->_substr($text, $idx - 1, $this->_strlen($text));
            }
            echo $visible_text;
            if ($this->_strlen($invisible_text) > 0) {
                ?><span>... </span><span class="rplg-more"><?php echo $invisible_text; ?></span><span class="rplg-more-toggle"><?php echo __('read more', 'brb'); ?></span><?php
            }
        } else {
            echo $text;
        }
    }

    private function correct_url_proto($url){
        return substr($url, 0, 2) == '//' ? 'https:' . $url : $url;
    }

    private function get_short_last_name($author_name){
        $names = explode(" ", $author_name);
        if (count($names) > 1) {
            $last_index = count($names) - 1;
            $last_name = $names[$last_index];
            if ($this->_strlen($last_name) > 1) {
                $last_char = $this->_substr($last_name, 0, 1);
                $last_name = $this->_strtoupper($last_char) . ".";
                $names[$last_index] = $last_name;
                return implode(" ", $names);
            }
        }
        return $author_name;
    }

    private function _strlen($str) {
        return function_exists('mb_strlen') ? mb_strlen($str, 'UTF-8') : strlen($str);
    }

    private function _strrpos($haystack, $needle, $offset = 0) {
        return function_exists('mb_strrpos') ? mb_strrpos($haystack, $needle, $offset, 'UTF-8') : strrpos($haystack, $needle, $offset);
    }

    private function _substr($str, $start, $length = NULL) {
        return function_exists('mb_substr') ? mb_substr($str, $start, $length, 'UTF-8') : substr($str, $start, $length);
    }

    private function _strtoupper($str) {
        return function_exists('mb_strtoupper') ? mb_strtoupper($str, 'UTF-8') : strtoupper($str);
    }

}
