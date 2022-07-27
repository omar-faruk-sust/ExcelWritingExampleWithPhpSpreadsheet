<?php

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$start_time = microtime(true);
$initial_memory = memory_get_usage();
$initial_memory = memory_get_peak_usage();

// Creates New Spreadsheet
$spreadsheet = new Spreadsheet();

// Retrieve the current active worksheet
$sheet = $spreadsheet->getActiveSheet();

// sample data
$data= [
    0 => [
        0 => 'Group Admin',
        1 => '12/09/2019 - 13:24',
        2 => 'Voir',
        3 => 'Mon compte',
        4 => 'macOS/Chrome-76.0',
      ],
      1 => [
        0 => 'Group Admin',
        1 => '12/09/2019 - 13:25',
        2 => 'Voir',
        3 => 'Gestion des documents',
        4 => 'macOS/Chrome-76.0',
      ],
      2 => [
        0 => 'Group Admin',
        1 => '17/06/2022 - 14:20',
        2 => 'Voir',
        3 => 'Accès refusé',
        4 => 'macOS/Firefox-101.0',
      ]
];

//set column header
//set your own column header
$column_header=["Nom", "Date", "Actions", "Pages", "Appareil/Navigateur"];
$j = 1;
foreach($column_header as $x_value) {
  $sheet->setCellValueByColumnAndRow($j, 1, $x_value);
  $j++;
}

//set value row
for($i = 0; $i < 50000; $i++)
{
  //set value for cell
  $row = $data[rand(0,2)];
  $j = 1;

  foreach($row as $x => $x_value) {
    $sheet->setCellValueByColumnAndRow($j, $i + 2, $x_value);
    $j = $j + 1;
  }

}

// Write an .xlsx file
$writer = new Xlsx($spreadsheet);

// Save .xlsx file to the files directory
$writer->save('files/demo_example.xlsx');

$spreadsheet->disconnectWorksheets();
unset($spreadsheet);

echo "\n\r";
$final_memory = memory_get_usage();
$peak_memory = memory_get_peak_usage();

// End clock time in seconds
$end_time = microtime(true);

// Calculate script execution time
$execution_time = ($end_time - $start_time);


echo "</br>";
echo "Initial Memory use: " . round(($initial_memory/(1024*1024)), 2) . "MB\n\r";

echo "</br>";
echo "Final Memory use: " . round(($final_memory/(1024*1024)), 2) . "MB\n\r";

echo "</br>";
echo "Peak Memory use: " . round(($peak_memory/(1024*1024)), 2) . "MB\n\r";

echo "</br>";
echo " Execution time of script = ".$execution_time." sec";


/**
 * Here is the amount of time and memory without cell caching
 * Initial Memory use: 0.43MB
 * Final Memory use: 26.7MB
 * Peak Memory use: 122.46MB
 * Execution time of script = 41.365000963211 sec
 */

?>