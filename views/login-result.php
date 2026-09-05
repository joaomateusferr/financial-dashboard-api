<section class="page-shell">

    <?php

    if(!empty($Redirect)){

        header("Location: $Redirect");
        exit;

    }

    ?>

    <div class="page-card">

        <h1 class="page-title"><?php echo $Title; ?></h1>
        <p class="page-description"><?php echo $Description; ?></p>
        <div class="page-footer">
            <?php echo $Footer; ?>
        </div>

    </div>

</section>
