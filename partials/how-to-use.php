<?php
// Ensure direct file access is blocked.
if (!defined('ABSPATH')) {
    exit;
}
?>

<!-- Heading for the 'How To Use' section -->
<h2 class="mt-8 mb-4 text-2xl text-slate-800"><?php echo __('How To Use', 'syncpress'); ?></h2>

<!-- Introduction paragraph for instructions -->
<p class="text-xl text-slate-600">
    <?php echo __('Follow these instructions to effectively use the SyncPress plugin:', 'syncpress'); ?>
</p>

<!-- Ordered list for step-by-step instructions -->
<ul class="max-w-4xl pt-8 space-y-2 text-xl text-slate-600">
    <li>
        <strong><?php echo __('Step 1:', 'syncpress'); ?></strong>
        <?php echo __('Navigate to the "Import Posts" tab. Here, input the URL of the WordPress site you wish to import posts from and specify the number of posts to import per page.', 'syncpress'); ?>
    </li>
    <li>
        <strong><?php echo __('Step 2:', 'syncpress'); ?></strong>
        <?php echo __('Click on the "Fetch Posts" button. This action initiates the import process, fetching posts from the provided URL.', 'syncpress'); ?>
    </li>
    <li>
        <strong><?php echo __('Step 3:', 'syncpress'); ?></strong>
        <?php echo __('View the fetched posts under the "Fetched Posts" tab. This tab allows for the review and management of the imported content. You have the option to edit or delete each post individually.', 'syncpress'); ?>
    </li>
    <li>
        <strong><?php echo __('Step 4:', 'syncpress'); ?></strong>
        <?php echo __('To track and review the history of your imports, go to the "Import History" tab. This section provides details such as the number of posts imported, the date and time of each import, a log of any errors encountered during the import process, and the source URL of the imported posts.', 'syncpress'); ?>
    </li>
</ul>