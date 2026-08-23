<?php

// File where the visitor count is stored
$file = __DIR__ . '/visits.txt';


// Create the file if it does not exist
if (!file_exists($file)) {

    file_put_contents($file, '0');

}


// Open the counter file
$fp = fopen($file, 'c+');


// Check that the file opened successfully
if ($fp === false) {

    http_response_code(500);
    echo '0';
    exit;

}


// Lock the file so simultaneous visitors
// do not overwrite each other's counts
if (flock($fp, LOCK_EX)) {

    // Read current count
    rewind($fp);

    $content = stream_get_contents($fp);

    $count = (int)trim($content);


    // Increase visitor count
    $count++;


    // Clear old content
    ftruncate($fp, 0);

    rewind($fp);


    // Save new count
    fwrite($fp, (string)$count);

    fflush($fp);


    // Release lock
    flock($fp, LOCK_UN);

}


// Close file
fclose($fp);


// Return count to JavaScript
header('Content-Type: text/plain');

echo $count;

?>