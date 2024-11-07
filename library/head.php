<!-- header -->
<head>
    <meta charset="UTF-8">
    <!-- create a viewport scale that is fit to the user's screen -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- must be the file to access -->
    <?php
    require_once "CGparking.php";
    // if userID has been aquired
    $checkCurrPage = CGparking::sessionManagment("ACC", "userID");
    if(!$checkCurrPage) {
        $userRegis = $_COOKIE['userRegis'] ?? NULL;
        if($userRegis) {
            echo "<link rel=\"stylesheet\" href=\"style/register.css\">";
        }
        else {
            echo "<link rel=\"stylesheet\" href=\"style/index.css\">";
        }
    }
    else {
        $style = $_COOKIE['style'] ?? 4;
        switch($style) {
            case 0:
                echo "<link rel=\"stylesheet\" href=\"style/nav.css\">";
                echo "<link rel=\"stylesheet\" href=\"style/history.css\">";
                break;
            case 1:
                echo "<link rel=\"stylesheet\" href=\"style/about_me.css\">";
                echo "<link rel=\"stylesheet\" href=\"style/nav.css\">";
                break;
            default:
                echo "<link rel=\"stylesheet\" href=\"style/nav.css\">";
                echo "<link rel=\"stylesheet\" href=\"style/dashboard_PMS.css\">";
                break;
        }
    }
    ?>
    <title>
        <?php
            CGparking::tabTitle("Welcome to PMS!", "WNR");
        ?>
    </title>
</head>