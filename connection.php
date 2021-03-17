 <?php
/*mysqli_connect("servername","username","password","databasename");*/
        //$link = mysqli_connect("localhost", "root", "******", "mysql");
        $link = mysqli_connect("localhost", "newuser", "password", "mysql");
        if (mysqli_connect_error()) {

            die ("Database Connection Error");

}


?>
