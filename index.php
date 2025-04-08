<?php

use General\Test;

require_once __DIR__ . '/config/initializer.php';

$test = new Test();

?>

<!DOCTYPE html>
<html lang="en">
    <body>
        <?= $test->render(); ?>
    </body>
</html>
