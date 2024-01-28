<?php
/**
 * Plugin Name: SyncPress
 * Plugin URI: https://www.artsunique.de
 * Description: SyncPress, developed by Arts Unique, is a versatile plugin designed to fetch and import posts from external WordPress sites. It facilitates seamless content integration and management for your WordPress website.
 * Version: 1.3
 * Author: Andreas Burget, Arts Unique
 * Author URI: https://www.artsunique.de
 * License: CC BY 4.0
 * License URI: https://creativecommons.org/licenses/by/4.0/
 * Text Domain: syncpress
 * Domain Path: /languages
 * Requires at least: WordPress 5.0
 * Requires PHP: 7.0 or higher
 * Tested up to: WordPress 5.8
 * Stable tag: 1.3
 */

// Plugin-Textdomain laden
function syncpress_plugin_menu()
{
    add_menu_page(
        'SyncPress',                  // Seitentitel
        'SyncPress',                  // Menütitel
        'manage_options',             // Benötigte Berechtigung
        'syncpress',                  // Menü-Slug
        'syncpress_plugin_options',   // Funktion zum Anzeigen der Seite
        'dashicons-admin-site',       // Dashicon-Symbol
        100                           // Menüposition
    );
}

add_action('admin_menu', 'syncpress_plugin_menu');

// Load plugin textdain
function syncpress_load_textdomain() {
    load_plugin_textdomain('syncpress', false, basename(dirname(__FILE__)) . '/languages/');
}
add_action('init', 'syncpress_load_textdomain');

// Load plugin styles
function my_styles()
{
    wp_enqueue_style('my-plugin-tailwind-styles', plugin_dir_url(__FILE__) . '/dist/tailwind.css', true);
}

add_action('admin_enqueue_scripts', 'my_styles');

include_once plugin_dir_path(__FILE__) . 'partials/messages.php';

function syncpress_plugin_options()
{
if (!current_user_can('manage_options')) {
    wp_die(__('You do not have sufficient permissions to access this page.'));
}

$active_tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'import-posts';
$fetched_posts = get_option('my_plugin_fetched_posts', []);
$import_history = get_option('my_plugin_import_history', []);

?>

<div class="p-20 mx-auto my-12 rounded-md bg-sky-100 max-w-7xl">
    <h1 class="text-4xl font-light"><?php echo __('External WP Posts Importer', 'syncpress'); ?></h1>

    <div class="flex justify-between my-8 gap-x-8">
        <a
            href="?page=syncpress&tab=import-posts"
            class="bg-blue-600 grow flex gap-2 justify-center items-center text-lg text-white rounded-md px-4  hover:bg-blue-700 hover:text-white hover:transition-colors hover:animate-pulse py-3 <?php echo $active_tab === 'import-posts' ? 'nav-tab-active' : ''; ?>"
        >
            <div><?php echo __('Import Posts', 'syncpress'); ?></div>
            <svg
                fill="none"
                viewBox="0 0 24 24"
                stroke-width="1.5"
                stroke="currentColor"
                class="w-6 h-6"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3"
                />
            </svg>


        </a>
        <a
            href="?page=syncpress&tab=fetched-posts"
            class="bg-blue-600 grow flex justify-center gap-3 items-center text-lg text-white rounded-md px-4 hover:bg-blue-700 hover:text-white hover:transition-colors hover:animate-pulse py-3 <?php echo $active_tab === 'fetched-posts' ? 'nav-tab-active' : ''; ?>"
        >
            <div><?php echo __('Fetched Posts', 'syncpress'); ?></div>
            <svg
                fill="none"
                viewBox="0 0 24 24"
                stroke-width="1.5"
                stroke="currentColor"
                class="w-6 h-6"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="m9 13.5 3 3m0 0 3-3m-3 3v-6m1.06-4.19-2.12-2.12a1.5 1.5 0 0 0-1.061-.44H4.5A2.25 2.25 0 0 0 2.25 6v12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9a2.25 2.25 0 0 0-2.25-2.25h-5.379a1.5 1.5 0 0 1-1.06-.44Z"
                />
            </svg>

        </a>
        <a
            href="?page=syncpress&tab=import-history"
            class="bg-blue-600 grow flex justify-center items-center text-lg text-white rounded-md px-4 gap-2 hover:text-white hover:transition-colors hover:animate-pulse py-3 <?php echo $active_tab === 'import-history' ? 'nav-tab-active' : ''; ?>"
        >

            <div><?php echo __('Import History', 'syncpress'); ?></div>
            <svg
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke-width="1.5"
                stroke="currentColor"
                class="w-6 h-6"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"
                />
            </svg>

        </a>
        <a
            href="?page=syncpress&tab=how-to-use"
            class="bg-blue-600 grow flex items-center gap-3 justify-center text-lg text-white rounded-md px-4 py-2 hover:text-white hover:transition-colors hover:animate-pulse py-3 <?php echo $active_tab === 'how-to-use' ? 'nav-tab-active' : ''; ?>"
        >

            <div><?php echo __('How To Use', 'syncpress'); ?></div>
            <svg
                fill="none"
                viewBox="0 0 24 24"
                stroke-width="1.5"
                stroke="currentColor"
                class="w-6 h-6"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M16.712 4.33a9.027 9.027 0 0 1 1.652 1.306c.51.51.944 1.064 1.306 1.652M16.712 4.33l-3.448 4.138m3.448-4.138a9.014 9.014 0 0 0-9.424 0M19.67 7.288l-4.138 3.448m4.138-3.448a9.014 9.014 0 0 1 0 9.424m-4.138-5.976a3.736 3.736 0 0 0-.88-1.388 3.737 3.737 0 0 0-1.388-.88m2.268 2.268a3.765 3.765 0 0 1 0 2.528m-2.268-4.796a3.765 3.765 0 0 0-2.528 0m4.796 4.796c-.181.506-.475.982-.88 1.388a3.736 3.736 0 0 1-1.388.88m2.268-2.268 4.138 3.448m0 0a9.027 9.027 0 0 1-1.306 1.652c-.51.51-1.064.944-1.652 1.306m0 0-3.448-4.138m3.448 4.138a9.014 9.014 0 0 1-9.424 0m5.976-4.138a3.765 3.765 0 0 1-2.528 0m0 0a3.736 3.736 0 0 1-1.388-.88 3.737 3.737 0 0 1-.88-1.388m2.268 2.268L7.288 19.67m0 0a9.024 9.024 0 0 1-1.652-1.306 9.027 9.027 0 0 1-1.306-1.652m0 0 4.138-3.448M4.33 16.712a9.014 9.014 0 0 1 0-9.424m4.138 5.976a3.765 3.765 0 0 1 0-2.528m0 0c.181-.506.475-.982.88-1.388a3.736 3.736 0 0 1 1.388-.88m-2.268 2.268L4.33 7.288m6.406 1.18L7.288 4.33m0 0a9.024 9.024 0 0 0-1.652 1.306A9.025 9.025 0 0 0 4.33 7.288"
                />
            </svg>
        </a>
    </div>


    <?php if ($active_tab === 'import-posts') { ?>

    <?php // Prüfen, ob eine Nachricht gesetzt wurde
        $message = isset($_GET['message']) ? sanitize_text_field($_GET['message']) : '';

        // Erfolgsmeldung anzeigen, wenn der Import erfolgreich war
        if ($message === 'success') {
            syncpress_show_success_message(__('Posts wurden erfolgreich importiert.', 'syncpress'));
        }

        // Fehlermeldung anzeigen, wenn der Import fehlgeschlagen ist
        if ($message === 'error') {
            syncpress_show_error_message(__('Fehler beim Import der Posts.', 'syncpress'));
        }
        ?>


    <h2 class="mb-8 text-2xl"><?php echo __('Start importing Posts', 'syncpress'); ?></h2>
    <form method="post">
        <?php wp_nonce_field('my-plugin-fetch-posts'); ?>
        <div class="flex justify-between gap-8 mb-8">
            <div class="flex flex-col flex-grow">
                <label class="text-sm font-bold text-slate-700"><?php echo __('Site URL', 'syncpress'); ?></label>
                <input
                    class="w-full h-12 px-8 rounded-sm border-slate-500"
                    type="url"
                    placeholder="https://example.com"
                    name="site_url"
                    required
                >
            </div>

            <div class="flex flex-col">
                <label
                    class="text-sm font-bold text-slate-700"><?php echo __('Number of Posts', 'syncpress'); ?></label>
                <input
                    type="number"
                    placeholder="10"
                    name="num_posts"
                    class="h-12 px-8 rounded-sm border-slate-500"
                    required
                >
            </div>
            <div class="flex flex-col flex-grow">
                <label class="text-sm font-bold text-slate-700"><?php echo __('Post Type', 'syncpress'); ?></label>
                <select
                    name="import_type"
                    class="h-12 px-8 rounded-sm border-slate-500"
                >
                    <option value="posts"><?php echo __('Posts', 'syncpress'); ?></option>
                    <option value="pages"><?php echo __('Pages', 'syncpress'); ?></option>
                </select>
            </div>
            <div class="flex flex-col flex-grow">
                <label
                    class="text-sm font-bold text-slate-700"
                    for="post_type"
                >
                    <?php echo __('Import into', 'syncpress'); ?></label>
                <select
                    name="post_type"
                    id="post_type"
                    class="w-full h-12 px-8 rounded-sm border-slate-500"
                >
                    <?php
                            $post_types = get_post_types(array('public' => true), 'objects');
                            foreach ($post_types as $post_type) {
                                // Überspringen Sie bestimmte Post-Typen
                                if (in_array($post_type->name, array('attachment', 'revision', 'nav_menu_item', 'custom_css', 'customize_changeset', 'oembed_cache', 'user_request'))) {
                                    continue;
                                }
                                echo '<option value="' . esc_attr($post_type->name) . '">' . esc_html($post_type->labels->singular_name) . '</option>';
                            }
                            ?>
                </select>
            </div>
        </div>

        <div class="flex justify-center mt-12 w-full">
            <button
                type="submit"
                class="flex items-center justify-center text-xl text-white outline-offset-8 outline-dotted outline-green-600 bg-green-600 rounded-full h-40 w-40 hover:bg-green-400-700 hover:text-white hover:transition-colors animate-pulse"
            >
                <svg
                    class="w-20 h-20"
                    viewBox="0 0 24 24"
                    fill="currentColor"
                >
                    <g>
                        <path
                            fill="none"
                            d="M0 0h24v24H0z"
                        ></path>
                        <path
                            d="M9.83 8.79L8 9.456V13H6V8.05h.015l5.268-1.918c.244-.093.51-.14.782-.131a2.616 2.616 0 0 1 2.427 1.82c.186.583.356.977.51 1.182A4.992 4.992 0 0 0 19 11v2a6.986 6.986 0 0 1-5.402-2.547l-.697 3.956L15 16.17V23h-2v-5.898l-2.27-1.904-.727 4.127-6.894-1.215.348-1.97 4.924.868L9.83 8.79zM13.5 5.5a2 2 0 1 1 0-4 2 2 0 0 1 0 4z"
                        ></path>
                    </g>
                </svg>
            </button>
        </div>
    </form>
    <?php } elseif ($active_tab === 'fetched-posts') { ?>

    <?php include plugin_dir_path(__FILE__) . 'partials/fetched-posts.php'; ?>


    <?php } elseif ($active_tab === 'import-history') { ?>
    <?php include plugin_dir_path(__FILE__) . 'partials/history.php'; ?>

    <?php } elseif ($active_tab === 'how-to-use') { ?>
    <?php include plugin_dir_path(__FILE__) . 'partials/how-to-use.php'; ?>
    <?php } ?>
</div>

<?php
}








function my_plugin_fetch_posts() {
    if (isset($_POST['site_url'], $_POST['num_posts'], $_POST['post_type'], $_POST['import_type']) && check_admin_referer('my-plugin-fetch-posts')) {
        $import_type = sanitize_text_field($_POST['import_type']);
        $endpoint = $import_type === 'pages' ? 'wp-json/wp/v2/pages' : 'wp-json/wp/v2/posts';
        $url = esc_url_raw(sanitize_text_field($_POST['site_url']) . '/' . $endpoint . '?per_page=' . intval($_POST['num_posts']) . '&_embed');
        $post_type = sanitize_text_field($_POST['post_type']);

        $fetched_posts = get_option('my_plugin_fetched_posts', array());
        $import_history = get_option('my_plugin_import_history', array());

        $previously_fetched = false;
        foreach ($import_history as $history) {
            if ($history['source'] === $url) {
                $previously_fetched = true;
                break;
            }
        }

        if (!$previously_fetched) {
            $response = wp_remote_get($url);
            if (is_wp_error($response)) {
                syncpress_show_admin_notice('Fehler bei der Anfrage: ' . $response->get_error_message(), 'error');
                return;
            }

            $posts = json_decode(wp_remote_retrieve_body($response), true);
            if (empty($posts)) {
                syncpress_show_admin_notice('Keine Posts zum Importieren gefunden.', 'error');
                return;
            }

            $imported_posts = 0;
            foreach ($posts as $post) {
                $new_post_id = wp_insert_post([
                    'post_type'    => $post_type,
                    'post_title'   => sanitize_text_field($post['title']['rendered']),
                    'post_content' => wp_kses_post($post['content']['rendered']),
                    'post_excerpt' => wp_kses_post($post['excerpt']['rendered']),
                    'post_status'  => 'draft',
                    'post_date'    => sanitize_text_field($post['date']),
                    'post_format'  => isset($post['format']) ? sanitize_text_field($post['format']) : 'standard',
                ]);

                if (!is_wp_error($new_post_id)) {
                    $fetched_posts[] = $new_post_id;
                    $imported_posts++;

                    if (isset($post['_embedded']['wp:featuredmedia'][0]['source_url'])) {
                        $image_url = $post['_embedded']['wp:featuredmedia'][0]['source_url'];
                        $image_id = my_plugin_insert_attachment_from_url($image_url);
                        if (!is_wp_error($image_id)) {
                            set_post_thumbnail($new_post_id, $image_id);
                        } else {
                            error_log('Fehler beim Herunterladen des Bildes: ' . $image_id->get_error_message());
                        }
                    }
                }
            }

            update_option('my_plugin_fetched_posts', $fetched_posts);
            $import_history[] = [
                'date' => current_time('Y-m-d H:i:s'),
                'num_posts' => $imported_posts,
                'source' => $url,
                'errors' => [],
            ];
            update_option('my_plugin_import_history', $import_history);

            if ($imported_posts > 0) {
                syncpress_show_admin_notice('Posts erfolgreich importiert.');
            } else {
                syncpress_show_admin_notice('Keine Posts importiert.', 'error');
            }
        } else {
            syncpress_show_admin_notice('Diese Posts wurden bereits zuvor importiert.', 'error');
        }
    }
}

add_action('admin_init', 'my_plugin_fetch_posts');




function my_plugin_delete_post() {
    if (isset($_POST['post_id']) && check_admin_referer('my-plugin-delete-post')) {
        $post_id = intval($_POST['post_id']);
        wp_delete_post($post_id, true);

        // Remove the post ID from fetched posts
        $fetched_posts = get_option('my_plugin_fetched_posts', array());
        $index = array_search($post_id, $fetched_posts);
        if ($index !== false) {
            unset($fetched_posts[$index]);
            update_option('my_plugin_fetched_posts', $fetched_posts);
        }

        syncpress_show_admin_notice(__('Aktion erfolgreich durchgeführt.', 'my-plugin'));
    } elseif (isset($_POST['action']) && $_POST['action'] === 'delete_all_posts' && check_admin_referer('my-plugin-delete-all-posts')) {
        $fetched_posts = get_option('my_plugin_fetched_posts', array());
        foreach ($fetched_posts as $post_id) {
            wp_delete_post($post_id, true);
        }
        update_option('my_plugin_fetched_posts', array());

        syncpress_show_admin_notice(__('All fetched posts deleted successfully.', 'syncpress'));
    } elseif (isset($_POST['action']) && $_POST['action'] === 'clear_history' && check_admin_referer('my-plugin-clear-history')) {
        update_option('my_plugin_import_history', array());

        syncpress_show_admin_notice(__('Import history cleared successfully.', 'syncpress'));
    }
}

add_action('admin_init', 'my_plugin_delete_post');

function my_plugin_insert_attachment_from_url($url)
{
    $file_array = [];
    $file_array['name'] = basename(sanitize_text_field($url));
    $file_array['tmp_name'] = download_url($url);
    if (is_wp_error($file_array['tmp_name'])) {
        @unlink($file_array['tmp_name']);
        return $file_array['tmp_name'];
    }
    $id = media_handle_sideload($file_array, 0);
    if (is_wp_error($id)) {
        @unlink($file_array['tmp_name']);
        return $id;
    }
    return $id;
}