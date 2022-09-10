<pre>
    <?php
    // print_r($_REQUEST);
    // // print($_REQUEST);
    // print_r($GLOBALS);
    // print_r($_SERVER);
    // print_r($_REQUEST);
    // print_r($_POST);
    // print_r($_GET);
    // print_r($_FILES);
    // print_r($_ENV);
    // print_r($_COOKIE);
    // print_r($_SESSION);
    include("api/lib/User.class.php");

    $s = new User("mhemanthkmr");

    print($s->getEmail());

    // print_r(method_exists());
    ?>
</pre>