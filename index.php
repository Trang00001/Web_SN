<?php
/**
 * Entry point - Direct access to home page (bypass login for testing)
 */

// Redirect directly to home page
header('Location: /app/views/pages/posts/home.php');
exit();
?>