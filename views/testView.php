<div>
    <h3 class="h4 fw-semibold">String output:</h3>
    <p class="m-0">
        <?php

        // Private property defined inside the Test class
        if (isset($this) && !empty($this->name)) {
            echo $this->name .  '<br>';
        } else {
            echo 'Property "name" not set or $this not found.<br>';
        }

        ?>
        <!-- Constant defined inside the initializer with define() -->
        <?= TEST; ?><br>
        <!-- Constant defined inside the initializer -->
        <?= TEST2; ?><br>
        <!-- Variable defined inside the initializer -->
        <?= $_TEST; ?>
    </p>
</div>
