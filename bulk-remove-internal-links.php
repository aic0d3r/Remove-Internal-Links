<?php
/*
Plugin Name: Bulk Remove Internal Links
Description: Bulk remove internal links from WordPress posts or pages automatically
Version: 1.0
Author: aicoder
Author URI: https://aic0d3r.github.io/Remove-Internal-Links/
License: GPL v2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Tested up to: 6.7.1
*/

// Add admin menu page
add_action('admin_menu', 'remove_internal_links_add_admin_menu');
function remove_internal_links_add_admin_menu() {
    add_options_page('Remove Internal Links', 'Remove Links', 'manage_options', 'bulk-remove-internal-links', 'remove_internal_links_options_page');
}

// Plugin options page
function remove_internal_links_options_page() {
    // Check user capabilities
    if (!current_user_can('manage_options')) {
        return;
    }

    // Handle form submission
    if (
        isset($_POST['remove_internal_links_remove']) && 
        isset($_POST['remove_internal_links_nonce']) && 
        wp_verify_nonce(
            sanitize_text_field(wp_unslash($_POST['remove_internal_links_nonce'])),
            'remove_internal_links_nonce'
        )
    ) {
        remove_internal_links_process_link_removal();
    }
    ?>
    <div class="wrap">
        <h1><?php echo esc_html__('Remove Internal Links', 'bulk-remove-internal-links'); ?></h1>
        <form method="post">
            <?php wp_nonce_field('remove_internal_links_nonce', 'remove_internal_links_nonce'); ?>
            <table class="form-table">
                <tr>
                    <th scope="row"><?php echo esc_html__('Select Post Type', 'bulk-remove-internal-links'); ?></th>
                    <td>
                        <select name="remove_internal_links_post_type">
                            <option value="post"<?php selected(esc_attr(sanitize_text_field(wp_unslash($_POST['remove_internal_links_post_type'] ?? ''))), 'post'); ?>><?php echo esc_html__('Posts', 'bulk-remove-internal-links'); ?></option>
                            <option value="page"<?php selected(esc_attr(sanitize_text_field(wp_unslash($_POST['remove_internal_links_post_type'] ?? ''))), 'page'); ?>><?php echo esc_html__('Pages', 'bulk-remove-internal-links'); ?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php echo esc_html__('Select Processing Speed', 'bulk-remove-internal-links'); ?></th>
                    <td>
                        <select name="remove_internal_links_timeout">
                            <option value="10"<?php selected(esc_attr(sanitize_text_field(wp_unslash($_POST['remove_internal_links_timeout'] ?? ''))), '10'); ?>><?php echo esc_html__('Fast (10ms)', 'bulk-remove-internal-links'); ?></option>
                            <option value="50"<?php selected(esc_attr(sanitize_text_field(wp_unslash($_POST['remove_internal_links_timeout'] ?? '50'))), '50'); ?>><?php echo esc_html__('Average (50ms)', 'bulk-remove-internal-links'); ?></option>
                            <option value="100"<?php selected(esc_attr(sanitize_text_field(wp_unslash($_POST['remove_internal_links_timeout'] ?? ''))), '100'); ?>><?php echo esc_html__('Slow (100ms)', 'bulk-remove-internal-links'); ?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php echo esc_html__('Affiliate Link Prefix', 'bulk-remove-internal-links'); ?></th>
                    <td>
                        <input type="text" name="remove_internal_links_affiliate_prefix" value="<?php echo esc_attr(sanitize_text_field(wp_unslash($_POST['remove_internal_links_affiliate_prefix'] ?? ''))); ?>" placeholder="<?php echo esc_attr__('e.g., /go/', 'bulk-remove-internal-links'); ?>" />
                        <p class="description"><?php echo esc_html__('Enter the prefix used for affiliate links to ignore during the link removal process.', 'bulk-remove-internal-links'); ?></p>
                    </td>
                </tr>
            </table>
            <p><strong><?php echo esc_html__('Warning:', 'bulk-remove-internal-links'); ?></strong> <?php echo esc_html__('Please backup your database before removing links.', 'bulk-remove-internal-links'); ?></p>
            <?php submit_button(__('Remove Links', 'bulk-remove-internal-links'), 'primary', 'remove_internal_links_remove'); ?>
        </form>
        <h2><?php echo esc_html__('Plugin Usage', 'bulk-remove-internal-links'); ?></h2>
        <ol>
            <li><?php echo esc_html__('Select Posts or Pages to remove internal links from.', 'bulk-remove-internal-links'); ?></li>
            <li><?php
                /* translators: %1$s: Opening <b> tag, %2$s: Closing </b> tag */
                echo sprintf(esc_html__('Choose the %1$sProcessing Speed%2$s: "Fast" for high-performance servers, "Average" for most, or "Slow" for shared hosting.', 'bulk-remove-internal-links'), '<b>', '</b>');
            ?></li>
            <li><?php echo esc_html__('Enter the prefix used for affiliate links that should be ignored during link removal (e.g., /go/, /recommend/).', 'bulk-remove-internal-links'); ?></li>
            <li><?php echo esc_html__('Click the "Remove Links" button to start link removal.', 'bulk-remove-internal-links'); ?></li>
            <li><?php echo esc_html__('It could finish quickly or slowly, depending on how many posts/pages, chosen speed and hosting platform. You will be notified.', 'bulk-remove-internal-links'); ?></li>
        </ol>
        <p><strong><?php echo esc_html__('Note:', 'bulk-remove-internal-links'); ?></strong> <?php
            /* translators: %s: Link to Rankmath SEO plugin */
            echo sprintf(wp_kses(__('Some plugins like <a href="%s" target="_blank">Rankmath SEO</a> are known to drastically slow down the process. Disable them temporarily.', 'bulk-remove-internal-links'), array('a' => array('href' => array(), 'target' => array()))), esc_url('https://rankmath.com/?ref=breakdevize'));
        ?></p>
        <h2><?php echo esc_html__('Support the Plugin Developer and Your Projects', 'bulk-remove-internal-links'); ?></h2>
        <p style="font-size:15px;"><?php echo esc_html__('Love this plugin? Skip the endless manual work of linking and writing.', 'bulk-remove-internal-links'); ?></p>
        <p style="font-size:15px;"><?php echo esc_html__('Grab my go-to tools to fast-track your website projects!', 'bulk-remove-internal-links'); ?></p>
        <ul style="list-style-type:disc; font-size:15px; padding-left:20px;">
            <li><?php
                /* translators: %s: Link to SEO Writing AI */
                echo sprintf(wp_kses(__('<a href="%s" target="_blank">Auto write humanized, deep web and helpful content in bulk</a> - Use coupon DEAL25 for 25%% OFF.', 'bulk-remove-internal-links'), array('a' => array('href' => array(), 'target' => array()))), esc_url('https://seowriting.ai?fp_ref=aicoder'));
            ?></li>
            <li><?php
                /* translators: %s: Link to Cloudways hosting */
                echo sprintf(wp_kses(__('<a href="%s" target="_blank">Get blazing fast, reliable and managed VPS hosting from Cloudways</a> - Choose Linode for best performance.', 'bulk-remove-internal-links'), array('a' => array('href' => array(), 'target' => array()))), esc_url('https://www.cloudways.com/en/?id=1266451'));
            ?></li>
            <li><?php
                /* translators: %s: Link to LinkWhisper */
                echo sprintf(wp_kses(__('<a href="%s" target="_blank">Link Whisper is my main linking tool but now outperformed by Linksy</a>', 'bulk-remove-internal-links'), array('a' => array('href' => array(), 'target' => array()))), esc_url('https://linkwhisper.com/ref/3493/'));
            ?></li>
            <li><?php
                /* translators: %s: Link to Linksy */
                echo sprintf(wp_kses(__('<a href="%s" target="_blank">Linksy for better AI (bulk) linking where Link Whisper shows no/bad suggestions</a> - Use coupon ADM10OFF for 10%% OFF.', 'bulk-remove-internal-links'), array('a' => array('href' => array(), 'target' => array()))), esc_url('https://plugli.com/linksy/ref/39/'));
            ?></li>
            <li><?php
                /* translators: %s: Link to Linkboss */
                echo sprintf(wp_kses(__('<a href="%s" target="_blank">Linkboss for easy AI (bulk) silo interlinking</a>', 'bulk-remove-internal-links'), array('a' => array('href' => array(), 'target' => array()))), esc_url('https://linkboss.io/?ref=aicoder'));
            ?></li>
        </ul>
        <p style="font-size:15px;"><?php echo esc_html__('Thanks so much for using my affiliate links to get you going. I truly appreciate you guys.', 'bulk-remove-internal-links'); ?></p>
    </div>
    <?php
}

// Process link removal
function remove_internal_links_process_link_removal() {
    // Check user capabilities
    if (!current_user_can('manage_options')) {
        return;
    }

    // Verify nonce
    if (!isset($_POST['remove_internal_links_nonce']) || !wp_verify_nonce(
        sanitize_text_field(wp_unslash($_POST['remove_internal_links_nonce'])),
        'remove_internal_links_nonce'
    )) {
        wp_die(esc_html__('Invalid nonce. Please try again.', 'bulk-remove-internal-links'));
    }

    $post_type = sanitize_text_field(wp_unslash($_POST['remove_internal_links_post_type'] ?? ''));
    $timeout = absint(wp_unslash($_POST['remove_internal_links_timeout'] ?? 50));
    $affiliate_prefix = sanitize_text_field(wp_unslash($_POST['remove_internal_links_affiliate_prefix'] ?? ''));

    if (!in_array($post_type, array('post', 'page'), true)) {
        echo '<div class="notice notice-error"><p>' . esc_html__('Invalid post type selected.', 'bulk-remove-internal-links') . '</p></div>';
        return;
    }

    $args = array(
        'post_type' => $post_type,
        'posts_per_page' => -1,
        'post_status' => 'publish',
    );

    $posts = get_posts($args);
    $total_posts = count($posts);

    foreach ($posts as $post) {
        $site_url = site_url();
        if (!empty($affiliate_prefix)) {
            $content = preg_replace('/<a\s[^>]*href=[\'"]('. preg_quote($site_url, '/') .'(?!'. preg_quote($affiliate_prefix, '/') .')[^\'"]+)[\'"].*?>(.*?)<\/a>/i', '$2', $post->post_content);
        } else {
            $content = preg_replace('/<a\s[^>]*href=[\'"]('. preg_quote($site_url, '/') .'[^\'"]+)[\'"].*?>(.*?)<\/a>/i', '$2', $post->post_content);
        }
        wp_update_post(array(
            'ID' => $post->ID,
            'post_content' => $content,
        ));

        // Delay execution based on timeout
        usleep($timeout * 1000);
    }

    /* translators: 1: Number of posts/pages processed, 2: Post type (posts or pages) */
    echo '<div class="notice notice-success"><p>' . sprintf(esc_html__('Internal link removal process completed for %1$d %2$s.', 'bulk-remove-internal-links'), absint($total_posts), esc_html($post_type)) . '</p></div>';
}
