<?php
    //Autoload Composer
    require_once 'vendor/autoload.php';

    /**
     * Starlight
     *
     * Version 0.1
     *
     * Developed by Lewis Lancaster
     */

    //Lets set the filepath for Starlight to use, to instead use a static path, please comment out the following code and
    //uncomment the code below this block.

    if( isset( $_SERVER['DOCUMENT_ROOT'] ) )
    {

        define('STARLIGHT_FILE_PATH', $_SERVER['DOCUMENT_ROOT'] );
    }
    else
    {

        define('STARLIGHT_FILE_PATH', getcwd() );
    }

    /**
     * define('STARLIGHT_FILE_PATH','/var/www/html/');
     */

    //Lets start the framework

    use Starlight\Framework\Manager;
    use Starlight\Framework\Collections\Settings;
    use Starlight\Framework\Collections\IO\Directory;

    //Start the framework

    Manager::start();

    $dir = new Directory('/config/');

    if( $dir->hasFiles() )
    {

        $files = $dir->getFiles('json');

        foreach( $files as $file )
        {

            print_r( $file->getFileName() );
        }
    }

