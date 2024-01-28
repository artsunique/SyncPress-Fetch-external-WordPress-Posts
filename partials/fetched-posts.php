<?php
// Ensure direct file access is blocked.
if (!defined('ABSPATH')) {
    exit;
}
?>

<?php if (empty($fetched_posts)) { ?>
<p class="text-base text-slate-600"><?php echo __('No fetched posts found', 'syncpress'); ?></p>
<?php } else { ?>
<h2 class="mt-8 mb-4 text-2xl text-slate-800"><?php echo __('Fetched Posts', 'syncpress'); ?></h2>
<table class="w-full mt-8 border-0 border-none">
    <thead>
        <tr class="w-full pb-4 border border-b-slate-600">

            <th class="text-base text-left text-slate-600"><?php echo __('ID', 'syncpress'); ?></th>
            <th class="text-base text-left text-slate-600"><?php echo __('Title', 'syncpress'); ?></th>

            <th class="text-base text-right text-slate-600"><?php echo __('Action', 'syncpress'); ?></th>

        </tr>
    </thead>
    <tbody>
        <?php foreach ($fetched_posts as $post_id) { ?>
        <tr class="w-full h-16 border-b border-dotted border-slate-400">
            <td class="text-base text-left"><?php echo $post_id; ?></td>
            <td class="text-base text-left"><?php echo get_the_title($post_id); ?></td>

            <td class="text-base text-right">

                <form
                    method="post"
                    class="flex justify-end gap-4"
                >
                    <?php wp_nonce_field('my-plugin-delete-post'); ?>
                    <input
                        type="hidden"
                        name="post_id"
                        value="<?php echo $post_id; ?>"
                    >

                    <button
                        type="submit"
                        class="flex justify-center p-2 text-white bg-red-600 rounded-full hover:bg-red-800 hover:animate-pulse"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                            class="w-4 h-4"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"
                            />
                        </svg>
                    </button>

                    <a
                        href="<?php echo get_edit_post_link($post_id); ?>"
                        class="flex justify-center p-2 bg-green-400 rounded-full text-green-950 hover:bg-green-800 hover:animate-pulse"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                            class="w-4 h-4"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125"
                            />
                        </svg>
                    </a>
                </form>
            </td>
        </tr>
        <?php } ?>
    </tbody>
</table>
<form
    method="post"
    style="margin-top: 20px;"
>
    <?php wp_nonce_field('my-plugin-delete-all-posts'); ?>
    <input
        type="hidden"
        name="action"
        value="delete_all_posts"
    >
    <button
        type="submit"
        class="px-6 py-2 text-base text-white bg-red-500 rounded-md hover:bg-red-700 hover:text-white hover:transition-colors hover:animate-pulse"
    ><?php echo __('Delete All Posts', 'syncpress'); ?>
        </1button>
</form>

<?php } ?>