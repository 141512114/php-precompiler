<?php

use General\Test;

require_once( __DIR__ . '/config/config.php' );

####################################

$test = new Test();

$includedFiles = $_FILEREPOSITORY->getAllIncludedFiles();
$currentFile   = $_FILEREPOSITORY->getCurrentFile();

$currentPath = $currentFile->getPath();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>PHP Precompiler/Caching</title>
    <link rel="stylesheet" href="src/assets/stylesheets/css/style.min.css">
</head>
<body>
<section class="container-xxl">
    <div class="row vstack row-gap-3 py-5">
        <div class="col-12">
            <h2>Call to function render() inside the Test class:</h2>
            <?php

            // How does isolation work in PHP? Which generally defined variables/constants/... can I access inside a class?
            echo $test->render();

            ?>
        </div>

        <div class="col-12 bg-info p-3">
            <h2>Directly including the snippet:</h2>
            <?php include __DIR__ . '/views/testView.php'; ?>
        </div>

        <div class="col-12 bg-black text-light p-3">
            <div class="mb-3">
                <div class="mb-3">
                    <h2 class="fw-semibold text-danger">Output of the current file:</h2>
                    <small class="bg-white text-muted px-2">(<?= $currentPath; ?>)</small>
                </div>
                <?php

                if ( !empty( $currentFile ) ) {

                    $content = $currentFile->getContent();

                    if ( !empty( $content ) ) {
                        ?>
                        <div id="accordionCurrentFileContent" class="accordion">
                            <div class="accordion-item">
                                <h4 id="headingCurrentFile" class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-currentFile" aria-expanded="true" aria-controls="collapse-currentFile">
                                        Currently viewed file content
                                    </button>
                                </h4>
                                <div id="collapse-currentFile" class="accordion-collapse collapse" aria-labelledby="headingCurrentFile" data-bs-parent="#accordionCurrentFileContent">
                                    <div class="accordion-body"><?= $content; ?></div>
                                </div>
                            </div>
                        </div>
                        <?php

                    } else {

                        echo '<p class="m-0">File has no content.</p>';

                    }
                }

                ?>
            </div>
            <div>
                <div class="mb-3">
                    <h2 class="fw-semibold text-danger">Includes found in file:</h2>
                    <small class="bg-white text-muted px-2">(<?= $currentPath; ?>)</small>
                </div>
                <?php

                $currentIncludedFiles = $_FILEREPOSITORY->getFilesFromIncludes( $currentFile );

                if ( !empty( $currentIncludedFiles ) ): ?>
                    <div id="accordionIncludesCurrent" class="accordion">
                        <?php foreach ( $currentIncludedFiles as $index => $include ): ?>
                            <div class="accordion-item">
                                <h4 id="heading<?= $index; ?>" class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-current<?= $index; ?>" aria-expanded="true" aria-controls="collapse-current<?= $index; ?>">
                                        <?= $include->getPath(); ?>
                                    </button>
                                </h4>
                                <div id="collapse-current<?= $index; ?>" class="accordion-collapse collapse" aria-labelledby="heading<?= $index; ?>" data-bs-parent="#accordionIncludesCurrent">
                                    <div class="accordion-body"><?= $include->getContent(); ?></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else:

                    echo '<p>No includes found.</p>';

                endif;
                ?>
            </div>
        </div>

        <div class="col-12 bg-black text-light p-3">
            <h2 class="fw-semibold text-danger">Read file and show its contents:</h2>
            <div class="mb-3">
                <h3 class="fw-semibold text-warning">Includes found:</h3>
                <?php if ( !empty( $includedFiles ) ): ?>
                    <div id="accordionIncludes" class="accordion">
                        <?php foreach ( $includedFiles as $index => $include ): ?>
                            <div class="accordion-item">
                                <h4 id="heading<?= $index; ?>" class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?= $index; ?>" aria-expanded="true" aria-controls="collapse<?= $index; ?>">
                                        <?= $include->getPath(); ?>
                                    </button>
                                </h4>
                                <div id="collapse<?= $index; ?>" class="accordion-collapse collapse" aria-labelledby="heading<?= $index; ?>" data-bs-parent="#accordionIncludes">
                                    <div class="accordion-body"><?= $include->getContent(); ?></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else:

                    echo '<p>No includes found.</p>';

                endif;
                ?>
            </div>
        </div>
    </div>
</section>
<script src="src/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script src="src/assets/js/index.js" type="text/javascript"></script>
</body>
</html>
