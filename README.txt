Explanation:

FileName: ImportStudentDetails.php
It will be run seperately which will import File2.xls and Insert into Normalised Database table named student_details.

FileName: ImportStudentFeesClass.php
It will be run seperately which will import File1.xls and Insert it into Database table named studennt_fee_Details

FileName: config.php
It will be used as Database config. It will be git ignored.

FileName: ImportCsv.php
It is a Base class to both the above class which create Database connection and Read XLS File and store it into Array

Folder: PHPExcel-1.8
It is a opensource project availavle for effectivly reading and parsing xls files.
