<h2 class="mb-8 text-2xl">Start importing Posts</h2>
<form method="post">
    <?php wp_nonce_field('my-plugin-fetch-posts'); ?>
    <div class="flex justify-between gap-8 mb-8">

        <div class="flex flex-col flex-grow">
            <label class="text-sm font-bold text-slate-700">Site URL</label>
            <input
                class="w-full h-12 px-8 rounded-sm border-slate-500"
                type="url"
                placeholder="https://example.com"
                name="site_url"
                required
            >
        </div>

        <div class="flex flex-col">
            <label class="text-sm font-bold text-slate-700">Number of Posts</label>
            <input
                type="number"
                placeholder="10"
                name="num_posts"
                class="h-12 px-8 rounded-sm border-slate-500"
                required
            >
        </div>
        <div class="flex flex-col flex-grow">
            <label class="text-sm font-bold text-slate-700">Import Type</label>
            <select
                name="import_type"
                class="h-12 px-8 rounded-sm border-slate-500"
            >
                <option value="posts">Posts</option>
                <option value="pages">Pages</option>
            </select>
        </div>
        <div class="flex flex-col flex-grow">
            <label
                class="text-sm font-bold text-slate-700"
                for="post_type"
            >Import into</label>
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

    <input
        type="submit"
        class="items-center justify-center gap-3 px-8 py-3 text-2xl text-white bg-green-600 rounded-md hover:bg-green-400-700 hover:text-white hover:transition-colors hover:animate-pulse"
        value="Fetch Posts"
    >



</form>