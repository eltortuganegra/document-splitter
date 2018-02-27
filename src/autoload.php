<?php

//spl_autoload_register(
//    function($class) {
//        static $classes = null;
//        if ($classes === null) {
//            $classes = array(
//                'documentsplitter' => '/DocumentSplitter.php',
//                'splitter' => '/Splitter.php'
//            );
//
//            $cn = strtolower($class);
//            if (isset($classes[$cn])) {
//                require __DIR__ . $classes[$cn];
//            }
//        }
//    },
//    true,
//    false
//);

spl_autoload_register(function ($class_name) {

    echo "ZZZZZZZZZZZZ: ($class_name)";

    include $class_name . '.php';
});
