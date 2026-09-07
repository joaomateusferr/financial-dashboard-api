<?php

session_start();
if(!empty($_SESSION['UserID'])){

    $DashboardTabsConstants = App\Constants\DashboardTabsConstants::getDashboardTabs();
    $Redirect = '/dashboard/'.array_key_first($DashboardTabsConstants);
    header("Location: $Redirect");
    exit;

}

?>

<section class="page-shell">

    <div class="page-card">

        <h1 class="page-title">Home</h1>
        <p class="page-description">Go to login ...</p>
        <div class="page-footer">
            Do you want to log in? <a href="/login">Login</a>
        </div>

    </div>

</section>