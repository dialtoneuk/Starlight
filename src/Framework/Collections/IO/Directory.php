<?php
    namespace Starlight\Framework\Collections\IO;

    /**
     * Lewis Lancaster 2017
     *
     * Class Directory
     *
     * @package Starlight\Framework\Collections\IO
     */

    use Exception;
    use stdClass;

    class Directory
    {

        /**
         * @var string
         */

        protected $directory;

        /**
         * Directory constructor.
         *
         * @param $directory
         *
         * @throws Exception
         */

        public function __construct( $directory )
        {

            if( file_exists( STARLIGHT_FILE_PATH . $directory ) == false )
            {

                throw new Exception('Directory ' . $directory . ' does not exist');
            }

            if( is_dir( STARLIGHT_FILE_PATH . $directory ) == false )
            {

                throw new Exception('Path is not a directory');
            }

            $this->directory = $directory;
        }

        /**
         * Gets the files in a directory
         *
         * @param string $suffix
         *
         * @return stdClass|File
         *
         * @throws Exception
         */

        public function getFiles( $suffix='php' )
        {

            $files = glob( $this->getRealPath() . "*." . $suffix );

            if( empty( $files ) )
            {

                throw new Exception('Directory is empty');
            }

            $classes = new stdClass();

            foreach( $files as $file )
            {

                $classes->{ explode('.', $file )[0] } = new File( explode( STARLIGHT_FILE_PATH , $file )[1] );
            }

            return $classes;
        }

        /**
         * Gets all of the files inside this dir
         *
         * @return stdClass
         *
         * @throws Exception
         */

        public function getContents()
        {

            $files = glob( $this->getRealPath() . "*" );

            if( empty( $files ) )
            {

                throw new Exception('Directory is empty');
            }

            $classes = new stdClass();

            foreach( $files as $file )
            {

                $classes->{ explode('.', $file )[0] } = new File( explode( STARLIGHT_FILE_PATH , $file )[1] );
            }

            return $classes;
        }

        /**
         * Gets the directories inside this directory
         *
         * @return stdClass
         *
         * @throws Exception
         */

        public function getDirs()
        {

            $dirs = glob( $this->getRealPath() . "*", GLOB_ONLYDIR );

            if( empty( $dirs ) )
            {

                throw new Exception('Directory is empty');
            }

            $classes = new stdClass();

            foreach( $dirs as $dir )
            {

                $classes->{ $dir } = new Directory( explode( STARLIGHT_FILE_PATH , $dir )[1] );
            }

            return $classes;
        }

        /**
         * Returns true if we have dirs
         *
         * @return bool
         */

        public function hasDirs()
        {

            $files = glob( $this->getRealPath() . "*", GLOB_ONLYDIR );

            if( empty( $files ) )
            {

                return false;
            }

            return true;
        }

        /**
         * Creates a file inside this directory
         *
         * @param $file
         *
         * @param null $data
         *
         * @return File
         *
         * @throws Exception
         */

        public function create( $file, $data=null )
        {

            file_put_contents( $this->getRealPath() . $file, $data );

            if( file_exists( $this->getRealPath() . $file ) == false )
            {

                throw new Exception('Failed to create file possibly due to a permissions error');
            }

            return new File( $this->directory . $file );
        }

        /**
         * Checks if a file exists in this directory
         *
         * @param $file
         *
         * @return bool
         */

        public function exists( $file )
        {

            if( file_exists( $this->getRealPath() . $file ) == false )
            {

                return false;
            }

            return true;
        }

        /**
         * Deletes this directory
         */

        public function delete()
        {

            rmdir( $this->getRealPath() );
        }

        /**
         * Deletes a file inside this dir
         *
         * @param $file
         */

        public function deleteFile( $file )
        {

            unlink( $this->getRealPath() . $file );
        }

        /**
         * Deletes all the files in a directory with out removing the directory
         */

        public function clear()
        {

            $files = $this->getContents();

            /** @var File $file */

            foreach($files as $file )
            {

                $file->delete();
            }
        }

        /**
         * The same as has files except it looks for files with a suffix instead of all files
         *
         * @param string $suffix
         *
         * @return bool
         */

        public function search( $suffix='php' )
        {

            $files = glob( $this->getRealPath() . "*." . $suffix );

            if( empty( $files ) )
            {

                return false;
            }

            return true;
        }

        /**
         * Returns true if we have files
         *
         * @return bool
         */

        public function hasFiles()
        {

            $files = glob( $this->getRealPath() . "*" );

            if( empty( $files ) )
            {

                return false;
            }

            return true;
        }

        /**
         * Gets the real path of the file
         *
         * @return string
         */

        private function getRealPath()
        {

            return STARLIGHT_FILE_PATH . $this->directory;
        }
    }