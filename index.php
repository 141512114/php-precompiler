<?php

use General\Test;

require_once __DIR__ . '/config/config.php';

####################################

$test        = new Test();
$currentFile = $_FILEANALYZER->getCurrentFile();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>PHP Precompiler/Caching</title>
    <link rel="stylesheet" href="src/assets/stylesheets/style.min.css">
</head>
<body>
<section class="container-xxl">
    <div class="row vstack row-gap-3 py-5">
        <div class="col-12">
            <h2>Call to function render() inside the Test class:</h2>
            <?= $test->render(); // How does isolation work in PHP? Which generally defined variables/constants/... can I access inside a class?                 ?>
        </div>

        <div class="col-12 bg-info p-3">
            <h2>Directly including the snippet:</h2>
            <?php include __DIR__ . '/views/testView.php'; ?>
        </div>

        <div class="col-12 bg-black text-light p-3">
            <div class="mb-3">
                <h2 class="fw-semibold text-danger">Output of the current file:</h2>
                <small class="bg-white text-muted px-2">(<?= $currentFile; ?>)</small>
            </div>
            <p class="m-0">
                <?php

                if ( !empty( $currentFile ) && $_SESSION[ 'is_precompiled' ] === FALSE ) {
                    $fileUrl = $currentFile;

                    if ( is_file( $fileUrl ) ) {
                        echo $_FILEHANDLER->sanitizeContents( $_FILEHANDLER->getFileContents( $fileUrl ) );
                        $_SESSION[ 'is_precompiled' ] = TRUE;
                    } else {
                        echo 'File not found.';
                    }
                }

                ?>
            </p>
        </div>

        <div class="col-12 bg-black text-light p-3">
            <h2 class="fw-semibold text-danger">Read file and show its contents:</h2>
            <div class="mb-3">
                <?php

                $includes = $_FILEANALYZER->findIncludes( $currentFile );

                ?>
                <h3 class="fw-semibold text-warning">Includes found:</h3>
                <ul class="list-group">
                    <?php

                    if ( !empty( $includes ) ) {
                        foreach ( $includes as $include ) {
                            echo '<li class="list-group-item">' . htmlspecialchars( $include ) . '</li>';
                        }
                    } else {
                        echo '<li class="list-group-item">No includes found.</li>';
                    }

                    ?>
                </ul>
            </div>
            <div>
                <h3 class="fw-semibold">File contents:</h3>
                <div class="vstack row-gap-3">
                    <?php

                    foreach ( $includes as $include ):
                        echo '<pre class="bg-dark-subtle text-dark m-0 p-3">';

                        $include = eval( 'return ' . $include . ';' ); // Evaluate the include path to get the actual file path (UNSAFE)

                        if ( !empty( $include ) && is_file( $include ) ) {
                            echo $_FILEHANDLER->sanitizeContents( $_FILEHANDLER->getFileContents( $include ) );
                        } else {
                            echo '<p class="text-center text-danger m-0">File not found or cannot be read.</p>';
                        }

                        echo '</pre>';
                    endforeach;

                    ?>
                </div>
            </div>
        </div>
    </div>
</section>
</body>
</html>
