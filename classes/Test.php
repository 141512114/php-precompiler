<?php

namespace General;

class Test
{
    private string $name = "This value is inside a private property in the Test class";

    public function render(): false|string
    {
        include __DIR__ . '/../views/testView.php';
        return ob_get_clean();
    }

    public function getCurrentFile()
    {
        return pathinfo($_SERVER['PHP_SELF'], PATHINFO_BASENAME);
    }
}