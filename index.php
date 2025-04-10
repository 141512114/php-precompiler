<?php

use General\Test;

require_once __DIR__ . '/config/config.php';

####################################

$test = new Test();
$currentFile = $test->getCurrentFile();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>PHP Precompiler/Caching</title>
    <link rel="stylesheet" href="assets/stylesheets/style.min.css">
</head>
<body>
<section class="container-xxl">
    <div class="row py-5">
        <div class="col vstack row-gap-3">
            <?= $test->render(); // How does isolation work in PHP? Which generally defined variables/constants/... can I access inside a class? ?>

            <div class="bg-black text-light p-3">
                <h2 class="fw-semibold text-danger">Output of current file:</h2>
                <p class="m-0">
                    <?php

                    if (!empty($currentFile) && $_SESSION['is_precompiled'] === false) {
                        $fileUrl = $currentFile;

                        if (is_file($fileUrl)) {
                            // Sanitize file contents to prevent unwanted layout crashes (and more).
                            // Will be handled differently in the future, this is just for testing purposes
                            echo nl2br(htmlspecialchars(file_get_contents($fileUrl)));
                            $_SESSION['is_precompiled'] = true;
                        }
                    }

                    ?>
                </p>
            </div>
        </div>
    </div>
</section>
</body>
</html>
