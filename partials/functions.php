<?php function my_plugin_fetch_posts() {
if (isset($_POST['site_url'], $_POST['num_posts'], $_POST['post_type'], $_POST['import_type']) &&
check_admin_referer('my-plugin-fetch-posts')) {
$import_type = sanitize_text_field($_POST['import_type']);
$endpoint = $import_type === 'pages' ? 'wp-json/wp/v2/pages' : 'wp-json/wp/v2/posts';
$url = esc_url_raw(sanitize_text_field($_POST['site_url']) . '/' . $endpoint . '?per_page=' .
intval($_POST['num_posts']) . '&_embed');
$post_type = sanitize_text_field($_POST['post_type']); // Der ausgewählte Post-Typ

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
// Fehlerbehandlung
return;
}

$posts = json_decode(wp_remote_retrieve_body($response), true);
if (empty($posts)) {
// Keine Posts gefunden
return;
}

$imported_posts = 0;
foreach ($posts as $post) {
// Erstellen des neuen Posts
$new_post_id = wp_insert_post([
'post_type' => $post_type,
'post_title' => sanitize_text_field($post['title']['rendered']),
'post_content' => wp_kses_post($post['content']['rendered']),
'post_excerpt' => wp_kses_post($post['excerpt']['rendered']),
'post_status' => 'draft',
'post_date' => sanitize_text_field($post['date']),
'post_format' => isset($post['format']) ? sanitize_text_field($post['format']) : 'standard',
]);

if (!is_wp_error($new_post_id)) {
// Erfolgreicher Import, Verarbeitung weiterer Details
$fetched_posts[] = $new_post_id;
$imported_posts++;

// Verarbeitung des Featured Images
if (isset($post['_embedded']['wp:featuredmedia'][0]['source_url'])) {
$image_url = $post['_embedded']['wp:featuredmedia'][0]['source_url'];

$image_id = my_plugin_insert_attachment_from_url($image_url);
if (!is_wp_error($image_id)) {
set_post_thumbnail($new_post_id, $image_id);
} else {
// Fehlerbehandlung, z.B. Logging des Fehlers
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

// Admin-Benachrichtigung
} else {
// Benachrichtigung, dass Posts bereits zuvor abgerufen wurden
}
}
}



add_action('admin_init', 'my_plugin_fetch_posts');

function my_plugin_delete_post()
{
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

add_action('admin_notices', function () {
?>

<div
    id="modal"
    class="fixed inset-0 hidden w-full h-full overflow-y-auto bg-gray-600 bg-opacity-50"
    style="z-index: 1000;"
>
    <div class="relative p-5 mx-auto bg-green-400 rounded-md shadow-lg top-20 w-96">
        <div id="modal-content">
            <p class="text-lg text-center text-green-800"><?php _e('Aktion erfolgreich durchgeführt.', 'my-plugin'); ?>
            </p>

        </div>
    </div>
</div>

<script type="text/javascript">
document.addEventListener('DOMContentLoaded', function() {
    var modal = document.getElementById('modal');
    var postDeleted = true; // Set this variable according to your logic

    if (postDeleted) {
        modal.style.display = 'block';
        setTimeout(function() {
            modal.style.display = 'none';
        }, 1200);
    }
});
</script>


<?php
        });
    } elseif (isset($_POST['action']) && $_POST['action'] === 'delete_all_posts' && check_admin_referer('my-plugin-delete-all-posts')) {
        $fetched_posts = get_option('my_plugin_fetched_posts', array());
        foreach ($fetched_posts as $post_id) {
            wp_delete_post($post_id, true);
        }
        update_option('my_plugin_fetched_posts', array());

        add_action('admin_notices', function () {
            ?>
<div class="notice notice-success">
    <p><?php _e('All fetched posts deleted successfully.', 'my-plugin'); ?></p>
</div>
<?php
        });
    } elseif (isset($_POST['action']) && $_POST['action'] === 'clear_history' && check_admin_referer('my-plugin-clear-history')) {
        update_option('my_plugin_import_history', array());

        add_action('admin_notices', function () {
            ?>


<div
    id="modalHistory"
    class="fixed inset-0 hidden w-full h-full overflow-y-auto bg-gray-600 bg-opacity-50"
    style="z-index: 1000;"
>
    <div class="relative p-5 mx-auto bg-green-400 rounded-md shadow-lg top-20 w-96">
        <div id="modal-content">
            <p class="text-lg text-center text-green-800">
                <?php _e('Import history cleared successfully.', 'my-plugin'); ?></p>

        </div>
    </div>
</div>

<script type="text/javascript">
document.addEventListener('DOMContentLoaded', function() {
    var modal = document.getElementById('modalHistory');
    var postDeleted = true; // Set this variable according to your logic

    if (postDeleted) {
        modal.style.display = 'block';
        setTimeout(function() {
            modal.style.display = 'none';
        }, 1200);
    }
});
</script>





<?php
        });
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

?>