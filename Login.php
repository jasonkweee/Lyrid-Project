<!DOCTYPE html>
<html lang = 'en'>
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</head>
<style>
    body{
        background-image: url('Photos/space.jpg');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100vh;
        }
    
    .form-container {
        width: 400px;
        padding: 20px;
        border: 2px solid purple;
        border-radius: 8px;
        background-color: #282b30;
        margin: auto;
        color: whitesmoke;
    }
    .btn-custom {
            background-color: purple;
            color: white;
        }
    .alert-container {
            width: 400px;
            margin: auto;
        }
    .alert {
        display: none;
        }
</style>
<body>
    <div>
        <div class="alert-container">
            <?php
                require_once 'Setting/Authentication.php';

                $auth = new Authentication('localhost', 'debian-sys-maint', '0DXddFBx19pUUQ6F', 'Product_inventory'); 

                if (isset($_COOKIE['rememberMeUsername']) && isset($_COOKIE['rememberMePassword'])) {
                    $_POST['Username'] = $_COOKIE['rememberMeUsername'];
                    $_POST['Password'] = $_COOKIE['rememberMePassword'];
                    $_POST['submitLogin'] = true;
                }

                if (isset($_POST['submitLogin'])) {
                    $username = $_POST['Username'];
                    $password = $_POST['Password']; 
                    if ($auth->login($username, $password)) {
                        if (isset($_POST['rememberMe'])) {
                            // Set cookies to remember the user
                            setcookie('rememberMeUsername', $_POST['Username'], time() + (86400 * 30), "/"); // 30 days
                            setcookie('rememberMePassword', $_POST['Password'], time() + (86400 * 30), "/"); // 30 days
                        } else {
                            // Clear cookies if "Remember Me" is not checked
                            setcookie('rememberMeUsername', "", time() - 3600, "/");
                            setcookie('rememberMePassword', "", time() - 3600, "/");
                        }
                        header("Location: index.php");
                        exit();
                    }else{
                        echo '<div class="alert alert-danger d-flex align-items-center text-center  mt-3 role="alert">
                                Invalid Login Credentials
                            </div>';
                    } 
                }
            ?>
        </div>
        <div class="form-container">
            <form action="Login.php" method="POST">
                <h1 class=" text-center">Login</h1>
                <div class = "mt-3 mb-3 text-center ">
                    <input type="text" class="form-control" id="Username" name="Username"  placeholder="Username" autocomplete="off" required>
                </div>
                <div class = "mb-3 text-center">
                    <input type="password" class="form-control" id="Password" name="Password" placeholder="Password" autocomplete="off" required>
                </div>
                <div class = "d-flex justify-content-between mb-3">
                    <label><input type="checkbox" > Remember Me <br></label>
                </div>
                
                <button type ="submit" class = "btn btn-custom w-100" name = "submitLogin">Login</button>
            </form>
        </div>
    </div>
</body>
<script>
    <?php if (!empty($result) && $result->num_rows == 0): ?>
        document.querySelector('.alert').style.display = 'block';?>
    <?php endif; ?>
</script>
</html>