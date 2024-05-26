<?php 
include_once('../functions/general.php');
include('../functions/view_docx.php');
include('../functions/display_prof.php');

$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$recordsPerPage = 15;
$search = isset($_GET['search']) ? $_GET['search'] : '';

$totalRecords = getTotalRecords($search);
$totalPages = ceil($totalRecords / $recordsPerPage);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Documents</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link rel="stylesheet" href="css/ad_docu.css">
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/topbar.css">
    <link rel="stylesheet" href="css/notif.css">
    <link rel="stylesheet" href="css/error.css">
    <link rel="stylesheet" href="css/page.css">
    <style>
</style>
</head>
<body>
    <!-- SIDEBAR -->
    <?php include('ad_navbar.php');?>

    <!-- TOP BAR -->
    <div class="main">
        <div class="topBar">
            <a href="./ad_scholar.php" style="text-decoration:none">
            <button class="headerBack" href="./ad_scholar.php" id="clickableIcon">
                <ion-icon name="chevron-back-outline"></ion-icon>
                <h1>Back</h1>
            </button>
            </a>

            <div class="headerRight">
                <div class="notif">
                    <ion-icon name="notifications-outline" onclick="openOverlay()"></ion-icon>
                </div>

                <a class="user" href="ad_settings.php">
                    <img src="images/profile.png" alt="">
                </a>
            </div>
        </div>

        <!-- TOP NAV -->
        <?php navDisplay();?>

        
        <div class="info">
            <div class="search">
                <form action="" method="get">
                    <label>
                        <input type="text" name="search" placeholder="Search here" value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
                        <ion-icon name="search-outline" onclick="this.closest('form').submit();"></ion-icon>
                    </label>
                </form>
            </div> 
        </div>

        <!-- DOCUMENTS -->
        <div class="table">
            <table>
                <tr style="font-weight: bold;">
                    <td> Document Name </td>
                    <td style="width:12%"> Date </td>
                    <td style="width:10%"> Type </td>
                    <td style="width:10%"> Status </td>
                    <td style="width:8%; text-align: right;"> Actions </td>
                </tr>
                <?php docxList($currentPage, $recordsPerPage, $search)?>
            </table>
        </div> 

        <!-- PAGINATION -->
        <?php include('pagination.php');?>
    </div>
    
    <!-- VIEW MODAL -->
    <div id="viewOverlay" class="view">
        <div class="view-content">
            <h2 id="view-doc_name">Document Name</h2>
            <span class="closeView" onclick="closePrev()">&times;</span>
            <form id="updateForm" method="post" action="">
                <input type="hidden" id="update-doc_id" name="doc_id">
                <select id="update-status" name="status">
                    <option value="" disabled selected>CLICK TO UPDATE STATUS</option>
                    <option value="APPROVED">APPROVED</option>
                    <option value="DECLINED">DECLINED</option>
                </select>
                <div id="denialReason" style="display: none;">
                    <h3>DECLINED: REASON</h3>
                    <textarea name="reason" id="denialReasonText"></textarea>
                </div>
                <center>
                <button id="updateButton" type="submit" name="update" class="btnAdd">Update</button>
                </center>
            </form>
            <br>
            <div id="pdfViewer" style="width: 700px; height: 100%; border: 1px solid #ccc;"></div>
        </div>
    </div>

   <!-- APPROVE MODAL -->
    <div id="approveOverlay" class="deleteOverlay">
        <div class="delete-content">
            <div class="infos">
                <h2>Confirm Approval</h2>
                <span class="closeDelete" onclick="closeApprove()">&times;</span>
            </div>
            <div class="message">
                <h4>Are you sure you want to approve this document?</h4>
            </div>
            <div class="button-container">
                <form id="approveForm" method="post" action="">
                    <input type="hidden" id="approve-id" name="doc_id">
                    <button type="submit" name="approve" class="yes-button">Yes</button>
                    <button type="button" class="no-button" onclick="closeApprove()">No</button>
                </form>
            </div>
        </div>
    </div>

    <!-- DECLINE MODAL -->
    <div id="declineOverlay" class="deleteOverlay">
        <div class="delete-content">
            <div class="infos">
                <h2>Confirm Decline</h2>
                <span class="closeDecline" onclick="closeDecline()">&times;</span>
            </div>
            <div class="message">
                <h4>Are you sure you want to decline this document?</h4>
                <textarea name="reason" id="declineReasonText" placeholder="Reason for declining"></textarea>
            </div>
            <div class="button-container">
                <form id="declineForm" method="post" action="">
                    <input type="hidden" id="decline-id" name="doc_id">
                    <button type="submit" name="decline" class="yes-button">Yes</button>
                    <button type="button" class="no-button" onclick="closeDecline()">No</button>
                </form>
            </div>
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
                    <button class="no-button" onclick="closeDelete()"> No </button>
                </form>
            </div>
        </div>
    </div>
    
    <!-- NOTIFICATION -->
    <?php include('notification.php');?>

    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.1.81/pdf.min.js"></script>
    <script src="../functions/page.js"></script>
    <script src="../functions/notif.js"></script>
    <script>
        // VIEW
        function openPrev(elem) {
            const status = elem.getAttribute("data-doc_status");
            const reason = elem.getAttribute("data-doc_reason") || "";

            document.getElementById("view-doc_name").innerText = elem.getAttribute("data-doc_name");
            document.getElementById("update-doc_id").value = elem.getAttribute("data-submit_id");

            if (status === "PENDING") {
                document.getElementById("update-status").style.display = "block";
                document.getElementById("updateButton").style.display = "block";
            } else {
                document.getElementById("update-status").style.display = "none";
                document.getElementById("updateButton").style.display = "none";
            }

            document.getElementById("denialReason").style.display = status === "DECLINED" ? "block" : "none";
            document.getElementById("denialReasonText").innerText = reason;
            document.getElementById("denialReasonText").readOnly = true;

            const pdfPath = '../assets/' + elem.getAttribute("data-doc_name");
            loadPDF(pdfPath);

            document.getElementById("viewOverlay").style.display = "block";
        }

        document.getElementById("update-status").addEventListener("change", function() {
            const denialReason = document.getElementById("denialReason");
            if (this.value === "DECLINED") {
                denialReason.style.display = "block";
            } else {
                denialReason.style.display = "none";
            }
        });


        function loadPDF(pdfPath) {
            var pdfjsLib = window['pdfjs-dist/build/pdf'];
            pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.1.81/pdf.worker.min.js';

            var loadingTask = pdfjsLib.getDocument(pdfPath);
            loadingTask.promise.then(function(pdf) {
                console.log('PDF loaded');

                pdf.getPage(1).then(function(page) {
                    console.log('Page loaded');

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
                        console.log('Page rendered');
                        var pdfViewer = document.getElementById('pdfViewer');
                        pdfViewer.innerHTML = '';
                        pdfViewer.appendChild(canvas);
                    });
                });
            }, function (reason) {
                console.error(reason);
            });
        }
        
        function closePrev() {
            document.getElementById("viewOverlay").style.display = "none";
        }

        document.getElementById("update-status").addEventListener("change", function() {
            const denialReason = document.getElementById("denialReason");
            if (this.value === "DECLINED") {
                denialReason.style.display = "block";
            } else {
                denialReason.style.display = "none";
            }
        });

        // APPROVE
        function openApprove(elem) {
            document.getElementById("approve-id").value = elem.getAttribute("data-id");
            document.getElementById("approveOverlay").style.display = "block";
        }

        function closeApprove() {
            document.getElementById("approveOverlay").style.display = "none";
        }

        // DECLINE
        function openDecline(elem) {
            document.getElementById("decline-id").value = elem.getAttribute("data-id");
            document.getElementById("declineOverlay").style.display = "block";
        }

        function closeDecline(elem) {
            document.getElementById("declineOverlay").style.display = "none";
        }

        // DELETE
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
