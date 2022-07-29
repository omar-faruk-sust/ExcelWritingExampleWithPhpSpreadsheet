<?php

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Settings;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use OmarPackage\CustomCacheBridge;


$start_time = microtime(true);
$initial_memory = memory_get_usage();
$initial_memory = memory_get_peak_usage();

//Cache
$client = new \Memcache();
$client->connect('127.0.0.1', 11211);
$pool = new \Cache\Adapter\Memcache\MemcacheCachePool($client);


// $simpleCache = new \Cache\Bridge\SimpleCache\SimpleCacheBridge($pool);
// \PhpOffice\PhpSpreadsheet\Settings::setCache($simpleCache);


$myCustomCache = new CustomCacheBridge($pool, 360000);
\PhpOffice\PhpSpreadsheet\Settings::setCache($myCustomCache);

// Creates New Spreadsheet
$spreadsheet = new Spreadsheet();

// Retrieve the current active worksheet
$sheet = $spreadsheet->getActiveSheet();


// sample data
$data= [
  0 => [
    "name"=>"David Miah", "age"=>23, "email" => "test@gmail.com", "country" => "Canada", "address" => "123, montrea, canada"
  ],
  1 => [
    "name"=>"Omar Faruk", "age"=> 43, "email" => "test@gmail.com", "country" => "Bangladesh", "address" => "123, random address, canada"
  ],
  2 => [
    "name"=>"Test Name", "age"=> 40, "email" => "test@gmail.com", "country" => "USA", "address" => "123, Tornto, canada"
  ]
];

//set column header
//set your own column header
$column_header=["Name","Age", "email", "Country", "Address"];
$j = 1;
foreach($column_header as $x_value) {
  $sheet->setCellValueByColumnAndRow($j, 1, $x_value);
  $j++;
}

//set value row
for($i = 0; $i < 20000; $i++)
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
$writer->setPreCalculateFormulas(false);
// Save .xlsx file to the files directory
$writer->save('files/demo_with_cache1.xlsx');

// then release the memory
$spreadsheet->__destruct();
$sheet->__destruct();
$sheet = null;
$spreadsheet = null;
$writer = null;
unset($writer);

Settings::getCache()->clear();
gc_collect_cycles();



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
 * For 10k
 * Using Memcache we can save bit of memory
 * Initial Memory use: 0.43MB
 * Final Memory use: 1.26MB
 * Peak Memory use: 20.65MB
 * Execution time of script = 76.392807006836 sec
 */


 /** 
  * For 20k
  * Initial Memory use: 0.43MB
  * Final Memory use: 1.38MB
  * Peak Memory use: 34.14MB
  * Execution time of script = 113.22411704063 sec
  */
?>