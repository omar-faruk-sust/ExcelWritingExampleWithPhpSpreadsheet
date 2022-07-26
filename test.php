<?php

    
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$initial_memory = memory_get_usage();
$initial_peak_memory = memory_get_peak_usage();

$fileName = 'test_export';
$directory = '';

$options = [
    'sheet_title' => 'Excel Import',
    'directory' => $directory,
    'add_header' => TRUE,
    'iterative' => TRUE
];

$header = [
    'Name', 'Date', 'Actions', 'Pages', 'Action Browser'
];

$values = [
    0 => [
        0 => 'Group Admin',
        1 => '12/09/219 - 12:33',
        2 => 'Voir',
        3 => 'my account',
        4 => 'macOS/Chrom-76.0'
    ],
    1 => [
        0 => 'Group Admin',
        1 => '17/09/219 - 12:33',
        2 => 'Voir',
        3 => 'Document Management',
        4 => 'macOS/Chrom-76.0'
    ],
    2 => [
        0 => 'Group Admin',
        1 => '15/09/219 - 12:33',
        2 => 'Voir',
        3 => 'Access Refuse',
        4 => 'macOS/Firefix-101.0'
    ]
];


for($i = 0; $i < 10000; $i++) {
    $row[] = $values[rand(0,2)];
}


?>