<?php

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet; 
use PhpOffice\PhpSpreadsheet\Writer\Xlsx; 

// Creates New Spreadsheet 
$spreadsheet = new Spreadsheet(); 

// Retrieve the current active worksheet 
$sheet = $spreadsheet->getActiveSheet(); 

// sample data
$data= [
    0 => [
        "name"=>"David Miah", "age"=>23, "email" => "test@gmail.com", "country" => "Canada"
    ],
    1 => [
        "name"=>"Omar Faruk", "age"=> 43, "email" => "test@gmail.com", "country" => "Bangladesh"
    ],
    2 => [
        "name"=>"Test Name", "age"=> 40, "email" => "test@gmail.com", "country" => "USA"
    ]
];

//set column header
//set your own column header
$column_header=["Name","Age", "email", "Country"];
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
$writer->save('files/demo.xlsx'); 

// $spreadsheet = new Spreadsheet();
// $sheet = $spreadsheet->getActiveSheet();
// $sheet->setCellValue('A1', 'Hello World !');

// $writer = new Xlsx($spreadsheet);
// $writer->save('hello world.xlsx');

?>