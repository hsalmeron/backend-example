<?php
/*
 * Example 8 - Using OAuth access token to list profiles of an account.
 */

use Mollie\Api\Exceptions\ApiException;

try {
    /*
     * Initialize the Mollie API library with your OAuth access token.
     */
    require "initialize_with_oauth.php";

    /*
     * Get the all the profiles for this account.
     */
    $profiles = $mollie->profiles->all();

    foreach ($profiles as $profile) {
        echo '<div style="line-height:40px; vertical-align:top">';

        echo htmlspecialchars($profile->name) .
            ' - ' . htmlspecialchars($profile->website) .
            ' (' . htmlspecialchars($profile->id) . ')';

        echo '</div>';
    }
} catch (ApiException $e) {
    echo "API call failed: " . htmlspecialchars($e->getMessage());
}
