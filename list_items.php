<?php
// list_items.php
$dir = realpath(__DIR__ . '/../'); // Project root

function listDir($dir) {
    $items = array_diff(scandir($dir), array('.', '..'));
    $result = [];
    foreach ($items as $item) {
        $path = $dir . DIRECTORY_SEPARATOR . $item;
        $result[] = [
            'name' => $item,
            'type' => is_dir($path) ? 'folder' : 'file',
        ];
    }
    return $result;
}

header('Content-Type: application/json');
echo json_encode(listDir($dir));
