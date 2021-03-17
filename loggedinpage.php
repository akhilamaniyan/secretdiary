<?php
    $diaryContent ="";
    session_start();

    if (array_key_exists("id", $_COOKIE)) {

        $_SESSION['id'] = $_COOKIE['id'];

    }

    if (array_key_exists("id", $_SESSION) && $_SESSION['id']) {

        include("connection.php");
        $query ="SELECT diary FROM register where id=".mysqli_real_escape_string($link,$_SESSION['id'])." LIMIT 1 ";
        $row = mysqli_fetch_array(mysqli_query($link,$query));
        $diaryContent = $row['diary'];

    } else {

        header("Location: secretdairy.php");

    }
        include("header.php");

?>

<nav class="navbar navbar-light bg-faded navbar-fixed-top">

Work is in progres....
  <a class="navbar-brand" href="#">Secret Diary</a>
      <div class="pull-xs-right">
      <a href ='secretdairy.php?logout=1'>
        <button class="btn btn-success-outline" type="submit" name="submit">Logout</button></a>
    </div>

</nav>

    <div class="container-fluid" id="containerLoggedInPage">

        <textarea id="diary" class="form-control"><?php echo $diaryContent; ?></textarea>
    </div>

<?php
    include("footer.php");
?>
