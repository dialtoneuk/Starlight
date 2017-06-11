<?php
    namespace Starlight\Framework\Collections;

    /**
     * Lewis Lancaster 2017
     *
     * Class Factory
     *
     * @package Starlight\Framework\Collections
     */

    use Exception;
    use stdClass;

    class Factory
    {

        /**
         * @var string
         */

        protected $namespace;

        /**
         * @var stdClass
         */

        protected $classes;

        /**
         * Factory constructor.
         *
         * @param string $namespace
         *
         * @throws Exception
         */

        public function __construct( string $namespace )
        {

            if( class_exists( $namespace ) == false )
            {

                throw new Exception('Namespace ' . $namespace . ' does not exist');
            }

            $this->namespace = $namespace;

            $this->classes = new stdClass();
        }

        /**
         * Creates a new class
         *
         * @param $name
         *
         * @param $class
         */

        public function create( $name, $class )
        {

            $header = $this->getHeader( $class );

            $this->classes->$name = new $header;
        }

        /**
         * Returns the key of a class inside this array ( can be used to check if a class already exists depending
         * on the object )
         *
         * @param $class
         *
         * @return int|null|string
         */

        public function search( $class )
        {

            foreach( $this->classes as $key=>$value )
            {

                if( $value == $class )
                {

                    return $key;
                }
            }

            return null;
        }

        /**
         * Returns true if this class is set
         *
         * @param $name
         *
         * @return bool
         */

        public function has( $name )
        {

            return isset( $this->classes->$name );
        }

        /**
         * Deletes a class from the factories internal class array
         *
         * @param $name
         */

        public function delete( $name )
        {

            unset( $this->classes->$name );
        }

        /**
         * Clears the classes
         */

        public function clear()
        {

            $this->classes = new stdClass();
        }

        /**
         * Returns true if a class exists in this namespace
         *
         * @param $class
         *
         * @return bool
         */

        public function exists( $class )
        {

            if( class_exists( $this->namespace . $class ) == false )
            {

                return false;
            }

            return true;
        }

        /**
         * Gets the header of the class, the namespace plus the class name
         *
         * @param $class
         *
         * @return string
         */

        private function getHeader( $class )
        {

            return $this->namespace . $class;
        }
    }