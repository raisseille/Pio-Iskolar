<?php
include('../functions/general.php');
include('../functions/view_docx.php');

$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$recordsPerPage = 15;
$search = isset($_GET['search']) ? $_GET['search'] : '';
$sortColumn = isset($_GET['sort']) ? $_GET['sort'] : 'sub_date';
$sortOrder = isset($_GET['order']) ? $_GET['order'] : 'DESC';

$totalRecords = getTotalRecords($search);
$totalPages = ceil($totalRecords / $recordsPerPage);

function getSortIcon($column) {
    global $sortColumn, $sortOrder;
    if ($sortColumn === $column) {
        return $sortOrder === 'DESC' ? '<ion-icon name="chevron-up-outline"></ion-icon>' : '<ion-icon name="chevron-down-outline"></ion-icon>';
    } else {
        return '<ion-icon name="chevron-expand-outline"></ion-icon>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Requirement History</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link rel="stylesheet" href="css/history.css">
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/topbar.css">
    <link rel="stylesheet" href="css/notif.css">
    <link rel="stylesheet" href="css/error.css">
    <link rel="stylesheet" href="css/page.css">
</head>
<body>
    <!-- SIDEBAR -->
    <?php include('sk_navbar.php'); ?>

    <!-- TOP BAR -->
    <div class="main">
        <div class="topBar">
            <div class="headerRight">
                <div class="notif" id="clickableIcon">
                    <ion-icon name="notifications-outline" onclick="openOverlay()"></ion-icon>
                </div>
                <a class="user" href="profile.php" id="clickableIcon">
                    <img src="images/profile.png" alt="">
                </a>
            </div>
        </div>

        <!-- TOP NAV -->
        <div class="details">
            <center>
                <h1>REQUIREMENTS</h1>
                <div class="topnav">
                    <a href="documents.php">Requirements</a>
                    <a href="#">History</a>
                </div>
            </center>
        </div>

        <!-- SUBMISSION HISTORY -->
        <div class="table">
            <table>
                <tr style="font-weight: bold;">
                    <td style="width:15%">
                        <a href="?page=<?= $currentPage ?>&search=<?= $search ?>&sort=sub_date&order=<?= $sortColumn === 'sub_date' && $sortOrder === 'ASC' ? 'DESC' : 'ASC' ?>">
                            Submission Date <?= getSortIcon('sub_date') ?>
                        </a>
                    </td>
                    <td style="width:50%"> 
                        <a href="?page=<?= $currentPage ?>&search=<?= $search ?>&sort=doc_name&order=<?= $sortColumn === 'doc_name' && $sortOrder === 'ASC' ? 'DESC' : 'ASC' ?>">
                            Document Name <?= getSortIcon('doc_name') ?>
                        </a>
                    </td>
                    <td style="width:10%">Type</td>
                    <td style="width:18%">
                        <a href="?page=<?= $currentPage ?>&search=<?= $search ?>&sort=doc_status&order=<?= $sortColumn === 'doc_status' && $sortOrder === 'ASC' ? 'DESC' : 'ASC' ?>">
                            Status <?= getSortIcon('doc_status') ?>
                        </a>
                    </td>
                    <td>Actions</td>
                </tr>
                <?php docxDisplay($_SESSION["sid"], $currentPage, $recordsPerPage, $search, $sortColumn, $sortOrder);?>
            </table>
        </div>
        
        <!-- PAGINATION -->
        <?php include('pagination.php'); ?>
    </div>

    <!-- VIEW MODAL -->
    <div id="viewOverlay" class="view">
        <div class="view-content">
            <h2 id="view-doc_name">Document Name</h2>
            <span class="closeView" onclick="closePrev()">&times;</span>
            <div id="denialReason" style="display: none;">
                <h3 id="denialReasonHeader">REASON FOR DECLINING: <span id="denialReasonText" style="text-decoration: underline;"></span></h3>
            </div>
            <br>
            <center>
                <div id="pdfViewer" style="width: 700px; height: 100%; border: 1px solid #ccc;"></div>
            </center>
        </div>
    </div>

    <!-- DELETE MODAL -->
    <div id="deleteOverlay" class="deleteOverlay">
        <div class="delete-content">
            <div class="infos">
                <h2>Confirm Delete</h2>
                <span class="closeDelete" onclick="closeDelete()">&times;</span>
            </div>
            <div class="message">
                <h4>Are you sure you want to delete this?</h4>
            </div>
            <div class="button-container">
                <form id="deleteForm" method="post" action="">
                    <input type="hidden" id="delete-id" name="id">
                    <input type="hidden" id="delete-name" name="name">
                    <button type="submit" name="delete" class="yes-button">Yes</button>
                    <button type="button" class="no-button" onclick="closeDelete()">No</button>
                </form>
            </div>
        </div>
    </div>

    <!-- NOTIFICATION -->
    <?php include('notification.php'); ?>

    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.1.81/pdf.min.js"></script>
    <script src="../functions/page.js"></script>
    <script src="../functions/notif.js"></script>
    <script>
        function openPrev(elem) {
            document.getElementById("viewOverlay").style.display = "block";
            
            const status = elem.getAttribute("data-doc_status");
            const reason = elem.getAttribute("data-doc_reason") || "";

            document.getElementById("view-doc_name").innerText = elem.getAttribute("data-doc_name");

            if (status === "DECLINED") {
                document.getElementById("denialReason").style.display = "block";
                document.getElementById("denialReasonText").innerText = reason;
            } else {
                document.getElementById("denialReason").style.display = "none";
            }

            const pdfPath = '../assets/' + elem.getAttribute("data-doc_name");
            loadPDF(pdfPath);
        }

        function closePrev() {
            document.getElementById("viewOverlay").style.display = "none";
        }

        function loadPDF(pdfPath) {
            var pdfjsLib = window['pdfjs-dist/build/pdf'];
            pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.1.81/pdf.worker.min.js';

            var loadingTask = pdfjsLib.getDocument(pdfPath);
            loadingTask.promise.then(function(pdf) {
                pdf.getPage(1).then(function(page) {
                    var scale = 1.5;
                    var viewport = page.getViewport({ scale: scale });

                    var canvas = document.createElement('canvas');
                    var context = canvas.getContext('2d');
                    canvas.height = viewport.height;
                    canvas.width = viewport.width;

                    var renderContext = {
                        canvasContext: context,
                        viewport: viewport
                    };
                    var renderTask = page.render(renderContext);
                    renderTask.promise.then(function() {
                        var pdfViewer = document.getElementById('pdfViewer');
                        pdfViewer.innerHTML = '';
                        pdfViewer.appendChild(canvas);
                    });
                });
            }, function (reason) {
                console.error(reason);
            });
        }

        function openDelete(elem) {
            document.getElementById("delete-id").value = elem.getAttribute("data-id");
            document.getElementById("delete-name").value = elem.getAttribute("data-name");
            document.getElementById("deleteOverlay").style.display = "block";
        }

        function closeDelete() {
            document.getElementById("deleteOverlay").style.display = "none";
        }
    </script>
</body>
</html>
