<!DOCTYPE html>
<html>
<head>
    <?php
        include 'AddOns/header.php';
        session_start(); 
        session_unset();    
        session_destroy();
    ?>
    <style>
        body, html {
            height: 100%;
            margin: 0;
        }
        .back-body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
            background-color: #f8f9fa; /* Example background color */
        }
        .content-wrapper {
            text-align: center;
        }
    </style>
</head>
<body class="back-body">
    <div class="content-wrapper">
        <div class="container-l m-5 bg-dark text-white p-3">
            <h1 class="text-center m-5">You Have Been Logged Out</h1>
            <div class="d-grid gap-4 d-md-flex justify-content-center m-5">
                <button class="btn btn-light me-md-4 p-3 btn-lg" type="button" onclick="window.location.href='Login.php'">Back to Login</button>
            </div>
        </div>
    </div>
</body>
</html>