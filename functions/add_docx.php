<?php
    include_once('../functions/general.php');
    global $conn;

//* DOCUMENT SUBMISSION - SCHOLAR *//
    if(isset($_POST['submission'])) {
        global $sem;
        global $year;
        global $batch;
        $id = $_SESSION['sid'];
        $date = date("Y-m-d");
        
        $file_fields = ['COR', 'TOR', 'SCF'];
        $submitted_docs = [];
        $scholar_info = null;

        foreach($file_fields as $field) {
            if(!empty($_FILES[$field]['tmp_name']) && is_uploaded_file($_FILES[$field]['tmp_name'])) {
                if (!$scholar_info) {
                    $query = "SELECT batch_num, last_name, first_name, middle_name FROM scholar WHERE scholar_id = '$id'";
                    $result = $conn->query($query);
                    $scholar_info = $result->fetch_assoc();
                    $level = ($batch - $scholar_info['batch_num']) + 1;
                }

                $name = $scholar_info['last_name'].'_'.$scholar_info['first_name'].'_'.$scholar_info['middle_name'].'_Year'.$level.'_Sem'.$sem.'_'.$field.'.pdf';
                
                $upload_temp = $_FILES[$field]['tmp_name'];
                move_uploaded_file($upload_temp, "../assets/$name");

                $insert = "INSERT INTO submission (submit_id, scholar_id, sub_date, doc_name, doc_type, acad_year, sem, doc_status) VALUES (NULL, '$id', '$date', '$name', '$field', '$year', '$sem', 'PENDING')";
                $execute = $conn->query($insert);
                if (!$execute) {
                    die(mysqli_error($conn));
                }

                $submitted_docs[] = $name;
            }
        }

        // Generate notification
        if (!empty($submitted_docs)) {
            $admin_id = 1; // Assuming the admin user_id is 1
            $title = "{$id}-{$scholar_info['last_name']} DOCUMENT SUBMISSION";
            $content = "Documents submitted: <br><br>" . implode('<br>', $submitted_docs);
            $notif_insert = "INSERT INTO notification (user_id, date, title, content) VALUES ('$admin_id', '$date', '$title', '$content')";
            $notif_execute = $conn->query($notif_insert);
            if (!$notif_execute) {
                die(mysqli_error($conn));
            }
        }

        header('Location: ./history.php');
        die;
    }

//* DOCUMENT UPLOAD - ADMIN *//

//* PENDING DOCUMENTS *//
function hasPendingDocument($scholar_id, $doc_type) {
    global $conn;
    $query = "SELECT COUNT(*) as count FROM submission WHERE scholar_id = ? AND doc_type = ? AND (doc_status = 'PENDING' OR doc_status = 'APPROVED')";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("is", $scholar_id, $doc_type);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row['count'] > 0;
}
?>