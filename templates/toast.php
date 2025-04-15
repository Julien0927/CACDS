<?php
function showToast($type, $message) {
    // Type peut être 'success' ou 'error' par exemple
    echo "
    <div aria-live='polite' aria-atomic='true' class='position-fixed top-10 start-0 p-3' style='z-index: 1055;'>
        <div class='toast align-items-center text-bg-$type border-0 show' role='alert'>
            <div class='d-flex'>
                <div class='toast-body'>
                    $message
                </div>
                <button type='button' class='btn-close btn-close-white me-2 m-auto' data-bs-dismiss='toast' aria-label='Close'></button>
            </div>
        </div>
    </div>
    ";
}
?>
