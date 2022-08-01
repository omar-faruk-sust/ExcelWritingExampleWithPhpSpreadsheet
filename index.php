<?php

require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Settings;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use OmarPackage\CustomCacheBridge;


$start_time = microtime(true);
$initial_memory = memory_get_usage();
$initial_memory = memory_get_peak_usage();

$write_limit_per_itteration = 50000;
$total_number_of_records    = 500000;


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

$actualData = [];
for($i = 0; $i < $total_number_of_records; $i++)
{
   //set value for cell
   $actualData[] = $data[rand(0,2)];
}

$total_itteration_needed = $total_number_of_records / $write_limit_per_itteration;

//set column header
//set your own column header
$column_header=["Name", "Age", "email", "Country", "Address"];

$rowCounter  = 0;
$dataCounter = 0;

for($iteration = 1; $iteration <= $total_itteration_needed; $iteration++) {

  $currentItterationLimitTotal = $iteration * $write_limit_per_itteration;

  // Creates New Spreadsheet
  $spreadsheet = new Spreadsheet();

  // Retrieve the current active worksheet
  $sheet = $spreadsheet->getActiveSheet();

  //Write the header
  $j = 1;
  foreach($column_header as $x_value) {
    $sheet->setCellValueByColumnAndRow($j, 1, $x_value);
    $j++;
  }


  for($r = 0; $r < $currentItterationLimitTotal; $r++) {

    $j = 1;

    $data = $actualData[$rowCounter];

    //Write the data rows
    foreach($data as $x => $x_value) {
      $sheet->setCellValueByColumnAndRow($j, $dataCounter + 2, $x_value);
      $j = $j + 1;
    }
    
    
    if($rowCounter == $currentItterationLimitTotal)
    {
      // Write an .xlsx file
      $writer = new Xlsx($spreadsheet);
      $writer->setPreCalculateFormulas(false);

      // Save .xlsx file to the files directory
      $writer->save('files/'.'demo_without_cache_'.$iteration.'.xlsx');

      // then release the memory
      $spreadsheet->__destruct();
      $sheet->__destruct();
      $sheet = null;
      $spreadsheet = null;
      $writer = null;
      unset($writer);
      gc_collect_cycles();
    }

    $rowCounter++;
  }
    
}



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
 * Here is the amount of time and memory without cell caching
 * Initial Memory use: 0.43MB
 * Final Memory use: 5.55MB
 * Peak Memory use: 28.24MB
 * Execution time of script = 7.3950650691986 sec
 */
?>