<h2 class="mt-8 mb-4 text-2xl text-slate-800"><?php echo __('Import History', 'syncpress'); ?></h2>
<?php if (empty($import_history)) { ?>
<p class="text-base text-slate-600"><?php echo __('No import history found.', 'syncpress'); ?></p>
<?php } else { ?>
<table class="w-full mt-8 border-0 border-none">
    <thead>
        <tr class="w-full pb-4 border border-b-slate-600">
            <th class="text-base text-left text-slate-600"><?php echo __('Date/Time', 'syncpress'); ?></th>
            <th class="text-base text-center text-slate-600"><?php echo __('Posts', 'syncpress'); ?></th>
            <th class="pl-8 text-base text-left text-slate-600"><?php echo __('Source', 'syncpress'); ?></th>
            <th class="text-base text-right text-slate-600"><?php echo __('Errors', 'syncpress'); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($import_history as $history) { ?>
        <tr class="w-full h-12 border-b border-dotted border-slate-400">
            <td class="text-base text-left"><?php echo date('d.m.Y'); ?> @
                <?php echo date('h:i A', strtotime($history['date'])); ?></td>
            <td class="text-base text-center text-slate-500"><?php echo $history['num_posts']; ?></td>
            <td class="pl-8 text-base text-left text-slate-500">
                <a
                    href="<?php echo esc_url($history['source']); ?>"
                    target="_blank"
                    rel="noopener noreferrer"
                >
                    <?php echo substr(trim($history['source']), 0, 40); ?>…
                </a>
            </td>
            <?php if (!empty($history['errors'])) { ?>
            <td class="text-base text-right text-<?php echo 'red-500'; ?>">
                <?php echo implode(', ', $history['errors']); ?></td>
            <?php } else { ?>
            <td class="text-base text-right text-<?php echo 'green-500'; ?>">
                <?php echo __('None', 'syncpress'); ?>


            </td>
            <?php } ?>
        </tr>
        <?php } ?>
    </tbody>
</table>
<form
    method="post"
    style="margin-top: 20px;"
>
    <?php wp_nonce_field('my-plugin-clear-history'); ?>
    <input
        type="hidden"
        name="action"
        value="clear_history"
    >

    <input
        type="submit"
        class="px-6 py-2 text-base text-white bg-green-500 rounded-md hover:bg-green-700 hover:text-white hover:transition-colors hover:animate-pulse"
        value="<?php echo __('Clear History', 'syncpress'); ?>"
    >
</form>
<?php } ?>