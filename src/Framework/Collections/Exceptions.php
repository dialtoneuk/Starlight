<?php
    namespace Starlight\Framework\Collections;

    /**
     * Lewis Lancaster 2017
     *
     * Class Exceptions
     *
     * @package Starlight\Framework\Collections
     */

    use Error as PHPError;
    use Exception as PHPException;
    use Starlight\Framework\Collections\IO\File;

    class Exceptions
    {

        /**
         * @var mixed
         */

        public $exceptions;

        /**
         * Exceptions constructor.
         */

        public function __construct()
        {

            if( $this->hasExceptionFile() == false )
            {

                file_put_contents( STARLIGHT_FILE_PATH . Settings::getSetting('exceptions.log.location'), json_encode([]) );
            }

            $this->exceptions = $this->readExceptions();
        }

        /**
         * Handles an error
         *
         * @param PHPException $exception
         */

        public function handleException( PHPException $exception )
        {

            if( Settings::getSetting('exceptions.log.enabled') == false )
            {

                return;
            }

            $this->logException( $exception );
        }

        /**
         * Returns true if we have an error file
         *
         * @return bool
         */

        public function hasExceptionFile()
        {

            if( file_exists( STARLIGHT_FILE_PATH . Settings::getSetting('exceptions.log.location') ) == false )
            {

                return false;
            }

            return true;
        }

        /**
         * Logs an error to the file
         *
         * @param PHPException $exception
         */

        public function logException( PHPException $exception )
        {

            $this->exceptions[] = array(
                'message'   => $exception->getMessage(),
                'line'      => $exception->getLine(),
                'file'      => $exception->getFile(),
                'trace'     => $exception->getTrace()
            );

            $this->saveExceptions();
        }

        /**
         * Saves the errors
         */

        private function saveExceptions()
        {

            file_put_contents( STARLIGHT_FILE_PATH . Settings::getSetting('exceptions.log.location'), json_encode( $this->exceptions ) );
        }

        /**
         * Reads the errors from the file
         *
         * @return mixed
         *
         * @throws PHPException
         */

        private function readExceptions()
        {

            if( file_exists( STARLIGHT_FILE_PATH . Settings::getSetting('exceptions.log.location') ) == false )
            {

                throw new PHPError('Error file does not exist');
            }

            $file = new File( Settings::getSetting('exceptions.log.location') );

            if( $file->isJson() == false )
            {

                throw new PHPError('Error log is in incorrect format');
            }

            return $file->toArray();
        }
    }