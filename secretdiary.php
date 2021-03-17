<?php

    session_start();

    $error = "";
    $success = "";

    if (array_key_exists("logout", $_GET)) {

        unset($_SESSION);
        setcookie("id", "", time() - 60*60);
        $_COOKIE["id"] = "";

        session_destroy();

    } else if ((array_key_exists("id", $_SESSION) AND $_SESSION['id']) OR (array_key_exists("id", $_COOKIE) AND $_COOKIE['id'])) {

        header("Location: loggedinpage.php");

    }

    if (array_key_exists("submit", $_POST)) {

        include("connection.php");

/*      if (!$_POST['name']) {
            $error .= "Name is required<br>";
        }*/

        if (!$_POST['email']) {

            $error .= "An email address is required<br>";

        }

        if (!$_POST['password']) {

            $error .= "A password is required<br>";

        }

        if ($error != "") {

            $error = "<p>There were error(s) in your form:</p>".$error;

        } else {

            if ($_POST['signUp'] == '1') {

                $query = "SELECT id FROM register WHERE email = '".mysqli_real_escape_string($link, $_POST['email'])."' LIMIT 1";

                $result = mysqli_query($link, $query);

                if (mysqli_num_rows($result) > 0) {

                    $error = "That email address is taken.";

                } else {

                    $query = "INSERT INTO register (email, password) VALUES ('".mysqli_real_escape_string($link, $_POST['email'])."', '".mysqli_real_escape_string($link, $_POST['password'])."')";

                    if (!mysqli_query($link, $query)) {

                        $error = "<p>Could not sign you up - please try again later.</p>";

                    } else {

                        $query = "UPDATE register SET password = '".md5(md5(mysqli_insert_id($link)).$_POST['password'])."' WHERE id = ".mysqli_insert_id($link)." LIMIT 1";

                        mysqli_query($link, $query);

                        $_SESSION['id'] = mysqli_insert_id($link);

                        if ($_POST['stayLoggedIn'] == '1') {

                            setcookie("id", mysqli_insert_id($link), time() + 60*60*24*365);

                       }

                        $success = "<p>You have Signed Up successfully - Please login with your email and password.</p>";
                        //header("Location: loggedinpage.php");

                    }
                }

            } else {

                    $query = "SELECT * FROM register WHERE email = '".mysqli_real_escape_string($link, $_POST['email'])."'";

                    $result = mysqli_query($link, $query);

                    $row = mysqli_fetch_array($result);

                    if (isset($row)) {

                       $hashedPassword = md5(md5($row['id']).$_POST['password']);

                        if ($hashedPassword == $row['password']) {

                            $_SESSION['id'] = $row['id'];

                             //if(isset($_POST['stayLoggedIn'])){
                            //if (isset($_POST['stayLoggedIn']) AND $_POST['stayLoggedIn'] == '1') {

                                setcookie("id", $row['id'], time() + 60*60*24*365);

                            //}

                            header("Location: loggedinpage.php");

                        } else {

                            $error = "That email/password combination could not be found.";

                        }

                    } else {

                        $error = "That email/password combination could not be found.";

                    }

                }

        }


    }


?>

<?php include("header.php"); ?>

<div class="container" id="homePageContainer">
        <h1>Secret Diary</h1>
        <p><strong>Store your thoughts securely.</strong></p>
        <div id="error"><?php if ($error!=""){
                echo '<div class="alert alert-danger" role="alert">'.$error.'</div>';
                }?></div>
        <div id="success"><?php if ($success!=""){
                echo '<div class="alert alert-success" role="alert">'.$success.'</div>';
                }?></div>
        <form method="post" id="signUpForm">
                <p>Interested? Sign Up now.</p>
                <!-- <fieldset class="form-group">
                        <input class="form-control" type="name" name="name" placeholder="Enter your name">
                </fieldset> -->
                <fieldset class="form-group">
                        <input class="form-control" type="email" name="email" placeholder="Your Email">
                </fieldset>
                <fieldset class="form-group">
                        <input class="form-control" type="password" name="password" placeholder="Password">
                </fieldset>
                <div class="checkbox">
                <label>
                        <input type="checkbox" name="stayLoggedIn" value= 1>Stay Logged in.
                </label>
                </div>
                        <input type="hidden" name="signUp" value="1">
                <fieldset class="form-group">
                        <input class="btn btn-success" type="submit" name="submit" value="Sign Up!">
                </fieldset>
                <p><a class="toggleForms">Log In </a></p>
        </form>
        <form method="post" id="logInForm">
                 <p>Log in using your username & password.</p>
                 <!--<fieldset class="form-group">
                        <input class="form-control" type="name" name="name" placeholder="Enter your name">
                 </fieldset>-->
                 <fieldset class="form-group">
                        <input class="form-control" type="email" name="email" placeholder="Your Email">
                 </fieldset>
                 <fieldset class="form-group">
                        <input class="form-control" type="password" name="password" placeholder="Password">
                 </fieldset>
                <div class="checkbox">
                <label>
                    <input type="checkbox" name="stayLoggedIn" value=1>Stay Logged in.
                </label>
                </div>
                        <input type="hidden" name="signUp" value="0">
                <fieldset class="form-group">
                        <input class="btn btn-success" type="submit" name="submit" value="Log In!">
                </fieldset>
                <p><a class="toggleForms">Sign Up</a></p>

        </form>
</div>
<?php include("footer.php"); ?>
