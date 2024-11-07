<script>
    function logoutConfirm() {
        var userconfirm = confirm("Do you want to logout from the system?");
        if(userconfirm) {
            // clear all cookies
            document.cookie = 'userRegis=; path=/; expires=Thu, 01 Jan 1970 00:00:00 UTC; SameSite=Lax';
            document.cookie = 'addMinute=; path=/; expires=Thu, 01 Jan 1970 00:00:00 UTC; SameSite=Lax';
            document.cookie = 'addHour=; path=/; expires=Thu, 01 Jan 1970 00:00:00 UTC; SameSite=Lax';
            document.cookie = 'userLogged=; path=/parkingmanagment; expires=Thu, 01 Jan 1970 00:00:00 UTC; SameSite=Lax';
            window.location = 'library/logout.php';
        }
    }
    function direcrUser(directUser) {
        if(directUser == 1) {
            document.cookie = 'style=1; path=/;';
            window.location = 'about_me.php';
        }
        if(directUser == 0){
            document.cookie = 'style=0; path=/;';
            window.location = 'parking_history.php';
        }
    }
</script> 
<nav class="navg">
    <!-- the image path is determine by the dashboard_PMS current location -->
    <!-- <a href="dashboard_PMS.php"><img src="sources/image/logo.png" alt="Parking Managment System"></a> -->
    <button class="nav-but" onclick="direcrUser(0);"><img src="sources/image/list.png" alt="Parking Record History"></button>
    <button class="nav-but" onclick="direcrUser(1);"><img src="sources/image/id_card.png" alt="My Personal Details"></button>
    <button class="nav-but" id="logOut" onclick="logoutConfirm();"><img src="sources/image/logout.png" alt="Log Out"></button>
</nav>