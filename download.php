<?php

/**
 * GET LINKS
 */

echo 'Starting ... \n\r';
echo 'Getting links ... \n\r';

$command = escapeshellcmd("python3 ./Scripts/url.py");

$output = shell_exec($command);

$pattern = '/https:\/\/www\.youtube\.com\/watch\?v=[\w-]+/';

preg_match_all($pattern, $output, $matches);

$links = $matches[0];

/**
 *  DEBRID LINKS
 */

echo 'Debriding links ... \n\r';

$mp3_links = array();

foreach ($links as $link) {
    $url = "https://api.real-debrid.com/rest/1.0/unrestrict/link?auth_token=XQWTQE7VFPXBWINIKMRPIQ4DGNBWME6IN2OVSH3H7SF7EOMKSCZA";

    $data = [
        'link' => $link
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    $response = curl_exec($ch);
    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
    }
    curl_close($ch);

    $data = json_decode($response, true);

    foreach ($data['alternative'] as $item) {
        if (isset($item['download']) && pathinfo($item['download'], PATHINFO_EXTENSION) == 'mp3') {
            $mp3_links[] = $item['download'];
        }
    }
}



//print_r($mp3_links);

/**
 * Create folder if no exists
 */

echo 'Creating folder ... \n\r';

$folderPath = './downloads';

if (!file_exists($folderPath)) {
    // Create the folder
    mkdir($folderPath, 0777, true); // 0777 is the default mode, true creates parent directories if they don't exist
    echo "Folder created successfully.";
} else {
    echo "Folder already exists.";
}

/**
 * Download files
 */

echo 'Downloading files ... \n\r';

foreach ($mp3_links as $mp3_link)
{
    $command = escapeshellcmd("wget -P " . $folderPath . " " . $mp3_link);

    $output = shell_exec($command);
}
