<?php
    @session_start();
	$errors = array();
	$success = array();
	$warning = array();
	$sweetAlert = array();
	$warnAlert = array();

	require '../vendor/autoload.php';

//* DATABASE CONNECTION *//
    // Credentials                              //* align the comments with this comment !!
    $serv = "localhost";                        //! replace: null
    $host = "root";                             //! replace: null
    $keys = "";                       			//! replace: null
    $dbnm = "pio_iskolar";                      //! replace: null
    $port = 3308;                               //* this is typically either 3306 OR 3307.

    // Connection
    $conn = new mysqli($serv, $host, $keys, $dbnm, $port);

	if (!$conn) {
	 die("Connection failed: " . mysqli_connect_error());
	}

//* SESSION CHECK *//
    //? if (empty($_SESSION["role"])) { header("location: ./index.php"); }
	//? else if(($_SESSION["role"] == "1")) { header("location: ./ad_dashboard.php"); } 
	//? else { header("location: ./announce.php");};

//* PARAMETER PULL *//
	//? ACADEMIC YEAR
	function academic() {
		global $conn;
		$query = "SELECT acad_year AS acad FROM batch_year ORDER BY batch_no DESC LIMIT 1";
		$result = $conn->query($query);
		if ($result->num_rows > 0) {
			return $result->fetch_assoc()['acad'];
		}
	}

	$year = academic();

	//? BATCH NUMBER
	function batch() {
		global $conn;
		$query = "SELECT batch_no AS batch FROM batch_year ORDER BY batch_no DESC LIMIT 1";
		$result = $conn->query($query);
		if ($result->num_rows > 0) {
			return $result->fetch_assoc()['batch'];
		}
	}

	$batch = batch();

	//? SEMESTER SWITCH
	function semester() {
		$current_month = date('n'); // Get the current month (1 to 12)
		
		// Check if the current month falls within Semester 1 (July to December)
		if ($current_month >= 7 && $current_month <= 12) {
			return 1; // Semester 1
		} else {
			return 2; // Semester 2
		}
	}

	// Test the function
	$sem = semester();


//* DATA LISTS *//
	function datalisting($column, $table, $id) {
		global $conn;
		if($id == "sem"){ 
			print '	
				<datalist id="sem">
					<option value="1">
					<option value="2">
					<option value="3"> 
				</datalist>
			';
		} else {
			$query = "SELECT DISTINCT $column AS a FROM $table";
			$result = $conn->query($query);
			if ($result->num_rows > 0) {
				print '<datalist id="'.$id.'">';
				while ($row = $result->fetch_assoc()) {
					print '<option value="'.$row['a'].'">';
				}
				print '</datalist>';
			}
		}
	//* SCHOLAR STATUS 	datalisting("status_name", "status", "status");
	//? SCHOOLS 		datalisting("school", "scholar", "school");
	//* COURSES 		datalisting("course", "scholar", "course");
	//? BATCH NUMBER 	datalisting("batch_no", "batch_year", "batch");
	//* ACADEMIC YEAR 	datalisting("acad_year", "batch_year", "year");
	//? SEMESTER 		datalisting("", "", "sem");
	}

//! OUTDATED !//
//* STRING SANITATION *//
	function sanitizeString($var){
		global $conn;
	    $var = strip_tags($var);
	    $var = htmlentities($var);
	    $var = stripslashes($var);
	    return $conn->real_escape_string($var);
    }
?>