<?php
    namespace Starlight\Framework\Collections;

    /**
     * Lewis Lancaster 2017
     *
     * Class Container
     *
     * @package Starlight\Framework\Collections
     */

    use stdClass;

    class Container
    {

        /**
         * @var stdClass
         */

        protected $classes;

        /**
         * Container constructor.
         */

        public function __construct()
        {

            $this->classes = new stdClass();
        }

        /**
         * Sets a class to the container
         *
         * @param $name
         *
         * @param $class
         */

        public function __set( $name, $class )
        {

            $this->classes->$name = $class;
        }

        /**
         * Gets a class
         *
         * @param $name
         *
         * @return mixed
         */

        public function __get( $name )
        {

            return $this->classes->$name;
        }

        /**
         * Returns true if this class is set in the container
         *
         * @param $name
         *
         * @return bool
         */

        public function __isset( $name )
        {

            return isset( $this->classes->$name );
        }

        /**
         * Clears the container
         */

        public function clear()
        {

            $this->classes = new stdClass();
        }

        /**
         * Returns the classes
         *
         * @return stdClass
         */

        public function getClasses()
        {

            return $this->classes;
        }
    }