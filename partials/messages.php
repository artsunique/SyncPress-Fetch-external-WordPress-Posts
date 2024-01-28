<?php
// Ensure direct file access is blocked.
if (!defined('ABSPATH')) {
    exit;
}

// Function to display admin notices as modals.
function syncpress_show_admin_notice($message, $type = 'success') {
    add_action('admin_footer', function() use ($message, $type) {
        // Define background color based on message type.
        $bgColorClass = $type === 'success' ? 'bg-green-500' : 'bg-red-500';

        // Validate and sanitize the message input
        $message = sanitize_text_field($message);

        ?>

<!-- Modal Structure -->
<div
    id="syncpress-modal"
    class="fixed inset-0 top-40 z-[99999] hidden overflow-y-auto"
    role="dialog"
    aria-labelledby="modal-title"
    aria-modal="true"
>
    <div class="flex items-start justify-center min-h-screen pt-20 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Modal Background Overlay -->
        <div
            class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
            aria-hidden="true"
        ></div>
        <!-- Modal Content -->
        <div
            class="inline-block overflow-hidden text-left align-top transition-all transform <?php echo $bgColorClass; ?> rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="p-6">
                <div class="flex justify-center items-center gap-6">
                    <!-- SVG path for icon -->
                    <svg
                        class="w-12 h-12"
                        viewBox="0 0 24 24"
                        fill="currentColor"
                    >
                        <path
                            d="M20.7 12.6c-.1-.3-.2-.6-.4-.9.3-.6.5-1 .3-1.9-.2-.8-.8-1.7-1.6-2.5l-1.7-.9.8-1.8c.4-.8.4-1.8-.1-2.5-.5-.8-1.4-1.3-2.3-1.3-1 0-1.8.6-2.3 1.5 0 0-.8-.2-1.8.1l-1.3 1.2s-1.7.5-2 2-.4 3.4-.8 4.1-2.5 3.3-3.7 4.7c-.9 1.1-.7 2.3.1 3.2l5 5c.8.8 2.7 1.1 4-.3 1.4-1.4.5-3.3.5-3.3.4-.3 1.5-1.2 2.6-1.5s2.8-.9 3.7-2.1c.8-1 1.2-1.8 1-2.8zm-9.3 8.3c-.4.4-1 .4-1.4 0l-4.2-4.2c-.2-.2-.3-.4-.3-.7s.1-.5.3-.7l.7-.7 4.9 4.9c.2.2.3.4.3.7 0 .3-.1.5-.3.7zm6.6-9.9c-.5 0-.7-.5-1.1-.9s-1.3-.5-2-.2-1 1-1 1.7c0 1 .7 1.9 1.7 1.9.7 0 .9-.1 1.3-.3.6-.4.8-.8 1.2-.8s.7.2.7.8-.6 1.3-1.2 1.7-1.1.5-1.7.6c-.6.1-1 .1-1.8.6-.7.4-1.4.9-1.8 1.3l-4.6-4.6c.9-1.1 1.7-2.3 1.8-2.9l.7-3.9c.1-.4.4-.5.6-.5.3 0 .6.2.7.4l.4-1.3c.1-.3.4-.5.6-.5.4 0 .8.3.7.8l-.5 2.4c.6-1.2 1.5-2.7 2.1-3.8.2-.3.4-.6.9-.6s.9.6.7 1.1c-.2.4-2 3.5-2.8 4.8-.1.1 0 .2.2.1.3-.2 1-.7 1.7-.7 1.2-.1 1.8.4 2.1.6.4.3.8.8 1.1 1.3.2.5-.2.9-.7.9zm-.2.7c-.4 0-.7.1-.9.4s-.6.7-1.1.7c-.7 0-1.1-.5-1.1-1.1s.4-1 1.1-1c.5 0 .9.4 1.1.7s.5.3.9.3z"
                        ></path>
                    </svg>
                    <!-- Message Text -->
                    <h3
                        class="text-xl"
                        id="modal-title"
                    ><?php echo esc_html($message); ?></h3>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript to Display and Auto-hide Modal -->
<script type="text/javascript">
document.addEventListener('DOMContentLoaded', function() {
    var modal = document.getElementById('syncpress-modal');

    // Modal einblenden
    modal.classList.remove('modal-hidden');
    modal.classList.add('modal-visible');

    setTimeout(function() {
        // Modal ausblenden
        modal.classList.remove('modal-visible');
        modal.classList.add('modal-hidden');
    }, 1400);
});
</script>

<?php
    });
}