<?php
require_once  __DIR__.'/ImportCsv.php';
/*
*@author 	Ankit Gupta <agupta_92@yahoo.co.in>
*Class to Input Student Fee Sheet and Insert it into Database
*/
class importStudentFeesClass extends csvimport{

	function __construct($filename){
		//$filename = 'file1.xls'; // CSV file to be imported to MySQL
		parent::__construct($filename);
		$this->insertData();
	}

	//Funtion to Insert Data into Database using Mysql Connection
	function insertData(){
		foreach ($this->sheetData as $key => $value) {
			$phone_no = $value['B'];
			$paymenDate = date("Y-m-d", strtotime($value['C']));;
			if ($this->checkIfAlreadyThere($phone_no, $paymenDate)){
				echo "Record Already Present: ". $value['B'];
				continue;
			} else {
				$today = date("Y-m-d H:i:s"); 
				$sql_check = "INSERT INTO `student_fee_details`(`fee_id`, `student_contact_no`, `fee_type`, `fee_payment_date`, `fee_amount`, `late_fee`, `created_at`) VALUES (DEFAULT,".$value['B'].",'".strtolower($value['A'])."','".$paymenDate."',".$value['D'].",".$value['E'].",'".$today."')";
				$result = $this->execute($sql_check);
				if ($result){
					echo "New Row Inserted in Fees Table\n";
				} else {
					echo "Error occured for: ". $value['B'];
				}
			}
		}
	}

	/*Function to check if the record for that particular Student Fee details already there or not.
	*@param phone number
	*@param $date fee submission date
	*/
	function checkIfAlreadyThere($phone_no, $date){
		$sql_check = "SELECT * FROM `student_fee_details` WHERE `student_contact_no` = '".$phone_no."' AND `fee_payment_date` = '".$date."'";
		$result = $this->execute($sql_check);
		$no_of_records = $this->count_check($result);
		return $no_of_records;
	}

}

//Creating Object of ImportStudentFee Class
new importStudentFeesClass('file1.xls');
?>