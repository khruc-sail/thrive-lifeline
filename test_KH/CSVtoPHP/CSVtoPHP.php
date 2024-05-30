<?php
/*
Plugin Name: CSV to PHP
Plugin URI: https://github.com/khruc-sail/thrive-lifeline/tree/d59726f87327825c7547e7f6fae340d5a9a5359e/test_KH/CSVtoPHP
Description: Test script to read a CSV file and display its contents in PHP.
Version: 2.1.0
Author: Ko Horiuchi
*/

// Path to the CSV file relative to this plugin directory
$resourcesFile = plugin_dir_path(__FILE__) . 'TESTthrive_resources.csv';

// Register the shortcode
add_shortcode('displayResources', 'displayResourcesShortcode');

function displayResourcesShortcode() {
    global $resourcesFile;

    // Buffer output to return it properly
    ob_start();

    // Open the CSV file for reading
    if (($fileHandle = fopen($resourcesFile, 'r')) !== false) {
        echo '<table style="border-collapse: collapse; width: 100%;">';

        // Read the CSV file line by line
        while (($row = fgetcsv($fileHandle)) !== false) {
            echo '<tr>';
            foreach ($row as $cell) {
                echo '<td style="border: 1px solid #ddd; padding: 8px;">' . htmlspecialchars($cell) . '</td>';
            }
            echo '</tr>';
        }
        echo '</table>';

        // Close the file handle
        fclose($fileHandle);
    } else {
        // Error opening the file
        return '<div class="notice notice-error is-dismissible">Error opening ' . $resourcesFile . '</div>';
    }

    // Return the buffered content as a string
    return ob_get_clean();
}

// Add a menu item to the plugin settings page
add_action('admin_menu', 'CSVtoPHP_pluginMenu');

function CSVtoPHP_pluginMenu() {
    $hook = add_menu_page(
        'CSV to PHP Instructions',  // Page title
        'CSV to PHP',               // Menu title
        'manage_options',           // Capability
        'csv-to-php',               // Menu slug
        'CSVtoPHP_displayInstructions', // Callback function
        'dashicons-media-code',     // Icon URL
        100                         // Position
    );

    add_action("load-$hook", 'csv_to_php_add_help_tab');
}

function CSVtoPHP_displayInstructions() {
    ?>
    <div class="wrap">
        <h1>CSV to PHP Plugin Instructions</h1>
        <h2>How to Use This Plugin</h2>
        <ol>
            <li>Ensure the CSV file <code>TESTthrive_resources.csv</code> is placed in the plugin directory: <code><?php echo plugin_dir_path(__FILE__); ?></code>.</li>
            <li>Activate the plugin through the 'Plugins' menu in WordPress.</li>
            <li>To display the CSV contents on a page or post, use the shortcode <code>[displayResources]</code>.</li>
            <li>Insert the shortcode in the content area where you want the CSV contents to appear.</li>
        </ol>
        <h2>Example</h2>
        <p>Edit a page or post and add the following shortcode:</p>
        <pre><code>[displayResources]</code></pre>
        <p>The contents of the CSV file will be displayed as a table in the location where you added the shortcode.</p>
    </div>
    <?php
}

function csv_to_php_add_help_tab() {
    $screen = get_current_screen();
    $screen->add_help_tab(array(
        'id'      => 'csv_to_php_help_tab',
        'title'   => 'Usage Instructions',
        'content' => '<h2>CSV to PHP Plugin Instructions</h2>
                        <ol>
                            <li>Ensure the CSV file <code>TESTthrive_resources.csv</code> is placed in the plugin directory: <code>' . plugin_dir_path(__FILE__) . '</code>.</li>
                            <li>Activate the plugin through the "Plugins" menu in WordPress.</li>
                            <li>To display the CSV contents on a page or post, use the shortcode <code>[displayResources]</code>.</li>
                            <li>Insert the shortcode in the content area where you want the CSV contents to appear.</li>
                        </ol>
                        <h2>Example</h2>
                        <p>Edit a page or post and add the following shortcode:</p>
                        <pre><code>[displayResources]</code></pre>
                        <p>The contents of the CSV file will be displayed as a table in the location where you added the shortcode.</p>'
    ));
}
?>
