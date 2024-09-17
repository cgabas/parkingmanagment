<!-- header -->
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- must be the file to access -->
    <?php
    require_once "CGparking.php";
    // if userID has been aquired
    $checkCurrPage = CGparking::sessionManagment("ACC", "userID");
    if($checkCurrPage) {
        echo "<link rel=\"stylesheet\" href=\"style/nav.css\">";
    }
    else {
        echo "<link rel=\"stylesheet\" href=\"style/index.css\">";
    }
    ?>
    <title>
        <?php
            CGparking::tabTitle("Welcome to PMS!", "WNR");
        ?>
    </title>
</head>