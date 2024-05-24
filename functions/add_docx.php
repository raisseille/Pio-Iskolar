<?php
    global $conn;

//* DOCUMENT SUBMISSION - SCHOLAR *//
    if(isset($_POST['submission'])) {
        global $sem;
        global $year;
        global $batch;
        $id = $_SESSION['uid'];
        $date = date("Y-m-d");
        
        

        $file_fields = ['COR', 'TOR', 'SCF'];

        foreach($file_fields as $field) {
            if(!empty($_FILES[$field]['tmp_name']) && is_uploaded_file($_FILES[$field]['tmp_name'])) {
                $query = "SELECT batch_num, last_name, first_name, middle_name FROM scholar WHERE scholar_id = '$id'";
            $result = $conn->query($query);
            while ($row = $result->fetch_assoc()){
                $level = ($batch - $row['batch_num']) + 1;
                $name = $row['last_name'].'_'.$row['first_name'].'_'.$row['middle_name'].'_Year'.$level.'_Sem'.$sem.'_'.$field.'.pdf';
            }
                
                $upload_temp = $_FILES[$field]['tmp_name'];

                move_uploaded_file($upload_temp,"../assets/$name");
                $insert = "INSERT INTO submission (submit_id, scholar_id, sub_date, doc_name, doc_type, acad_year, sem, doc_status) VALUES (NULL, '$id', '$date', '$name', '$field', '$year', '$sem', 'PENDING')";
                $execute = $conn->query($insert);
                if (!$execute) {
                    die(mysqli_error($conn));
                }
            }
        }
        
        header('Location: ./history.php');
        die;
    }

//* DOCUMENT UPLOAD - ADMIN *//

//* PENDING DOCUMENTS *//
function hasPendingDocument($scholar_id, $doc_type) {
    global $conn;
    $query = "SELECT COUNT(*) as count FROM submission WHERE scholar_id = ? AND doc_type = ? AND doc_status = 'PENDING'";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("is", $scholar_id, $doc_type);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row['count'] > 0;
}
?>