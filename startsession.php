<?php
require_once('appvars.php');

function startAppSession() {
    if ((session_save_path() != SESSION_DIR) || !isset($_SESSION)) {
        // Check if session directory exists or create it here
        if (!is_dir(SESSION_DIR)) {
            mkdir(SESSION_DIR, 0750, true);
        }
        session_save_path(SESSION_DIR);
        session_start();
    }
}
