<?php include_once('../functions/general.php');?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link rel="stylesheet" href="css/ad_setting.css">
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/topbar.css">
    <link rel="stylesheet" href="css/notif.css">
    <link rel="stylesheet" href="css/error.css">
    <link rel="stylesheet" href="css/page.css">
</head>


<body>
    <!-- SIDEBAR -->
    <?php include('ad_navbar.php');?>

    <!-- TOP BAR -->
    <div class="main">
        <div class="topBar">
            <div class="notif">
                <ion-icon name="notifications-outline" onclick="openOverlay()"></ion-icon>
            </div>

            <div class="search">
            </div>

            <a class="user" href="ad_settings.php">
                <img src="images/profile.png" alt="" >
            </a>

            <a class="logOut" href="front_page.php"> 
                <ion-icon name="log-out-outline"></ion-icon> 
                <h5> Log  Out </h5>
            </a>
        </div>


        <!-- ACCOUNT SETTING -->
        <div class="info">
            <h1> ACCOUNT SETTINGS </h1>
        </div>

        <div class="profile">
            <div class="profile_name">
                <img src="images/profile.png" alt="Profile Picture"> <br>
            </div>

            <div class="profile-info">
                <table>
                    <tr>
                        <th>Name:</th>
                        <td>Marcos, Dannah Lei</td>
                    </tr>
                    <tr>
                        <th>Birthday:</th>
                        <td>05/24/2003</td>
                    </tr>
                    <tr>
                        <th>Address:</th>
                        <td>Marulas, Valenzuela City, Metro Manila, Philippines</td>
                    </tr>
                    <tr>
                        <th>Postal Code:</th>
                        <td>1440</td>
                    </tr>
                    <tr style="height: 40px;">
                        <th> </th>
                        <td> </td>
                    </tr>
                    <tr>
                        <th>Contact:</th>
                        <td>0963821954</td>
                    </tr>
                    <tr>
                        <th>Email:</th>
                        <td>j**********@gmail.com</td>
                    </tr>
                    <tr style="height: 10px;">
                        <th> </th>
                        <td> </td>
                    </tr>
                    <tr style="height: 40px;">
                        <th> <a href="#" onclick="openPass()"> Change Password </a></th>
                        <td></td>
                    </tr>
                    <tr style="height: 40px;">
                        <th> </th>
                        <td> </td>
                    </tr>
                    <tr>
                        <th> <a href="front_page.php"> Log Out </a></th>
                        <td></td>
                    </tr>
                    <tr style="height: 70px;">
                        <th></th>
                        <td></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>


    <!-- CHANGE PASS -->
    <div id="passOverlay" class="passOverlay">
        <div class="pass-content">
            <div class="infos">
                <h2>Change Password</h2>
                <span class="closePass" onclick="closePass()">&times;</span>
            </div>
            <br><br>

            <div class="inner-content">
                <label class="text" for="oldPassword">Enter Current Password:</label> <br>
                <input class="input" type="password" id="oldPassword" name="oldPassword" placeholder="Current Password">
            </div>

            <div class="inner-content">
                <label class="text" for="newPassword">Enter New Password:</label> <br>
                <input class="input" type="password" id="newPassword" name="newPassword" placeholder="New Password">
            </div>

            <div class="inner-content">
                <label class="text" for="confirmPassword">Confirm Password:</label> <br>
                <input class="input" type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirm Password">
            </div>

            <div class="enter-button-container">
                <button class="enter-button"> Enter </button>
            </div>
        </div>
    </div>

    <!-- NOTIFICATION -->
    <?php include('notification.php');?>


    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script src="../functions/notif.js"></script>
    <script>
        //CHANGE PASS
        function openPass() {
            document.getElementById("passOverlay").style.display = "block";
        }
        function closePass() {
            document.getElementById("passOverlay").style.display = "none";
        }
        function submitForm() {
            closePass();
        }
    </script>
</body>
</html>