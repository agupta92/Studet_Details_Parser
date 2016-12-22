<?php 
require  __DIR__.'/ImportCsv.php';
/*
*@author 	Ankit Gupta <agupta_92@yahoo.co.in>
*Class to Input Student Details Sheet and Insert it into Database
*/
class importStudentDetailsClass extends csvimport{

	function __construct($filename){
		//$filename = 'file2.xls'; // CSV file to be imported to MySQL
		parent::__construct($filename);
		$this->insertData();
	}

	//Function to Insert data into Database using mysql connection
	function insertData(){

		foreach ($this->sheetData as $key => $value) {
			$phone_no = $value['C'];
			$doj = date("Y-m-d", strtotime($value['F']));
			$dob = date("Y-m-d", strtotime($value['E']));
			if ($this->checkIfAlreadyThere($phone_no)){
				echo "Record Already Present: ". $value['C'];
				continue;
			} else {
				$today = date("Y-m-d H:i:s"); 
				$address_array = $this->breakAddress($value['D']);
				$sql_insert = "INSERT INTO `student_details`(`student_id`, `student_name`, `student_contact_no`, `student_area`, `student_city`, `student_state`, `student_pincode`, `student_email_id`, `created_at`, `student_dob`, `student_gender`) VALUES ('DEFAULT','".$value['B']."','".$value['C']."','".$address_array['area']."','".$address_array['city']."','".$address_array['state']."','".$address_array['pincode']."','".$address_array['email']."','".$doj."','".$dob."','".strtolower($value['G'])."')";
				$result = $this->execute($sql_insert);
				if ($result){
					echo "New Row Inserted in Student Details Table\n";
				} else {
					echo "Error occured for: ". $value['B'];
				}
			}
		}
	}

	/*Function to check if the record for that particular Student details already there or not.
	*@param phone number
	*/
	function checkIfAlreadyThere($phone_no){
		$sql_check = "SELECT * FROM `student_details` WHERE `student_contact_no` = '".$phone_no."'";
		$result = $this->execute($sql_check);
		$no_of_records = $this->count_check($result);
		return $no_of_records;
	}

	/*
	* Funtion to bread address column and map it to 7 parameters.
	*@param $address Address picked from the sheet
	*/
	function breakAddress($address){
		$address_break = explode(':', $address);
		$array_map = array('area' => '','phone_no' => '','locality' => '','city' => '','state' => '','pincode' => '','email' => '');
		$i = 0;
		foreach ($array_map as $key => $value) {
			if($key == 'phone_no' && !is_numeric($address_break[$i])){
				continue;
			} else if ($key == 'pincode' && !is_numeric($address_break[$i])){
				continue;
			}else if ($key == 'email' && filter_var($address_break[$i], FILTER_VALIDATE_EMAIL)){
				continue;
			}
			$array_map[$key] = $address_break[$i];
			$i++;			
		}
		return $array_map;
	}
}

//Creating object of Import Student Details Class
new importStudentDetailsClass('file2.xls');
?>