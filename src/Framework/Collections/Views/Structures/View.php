<?php
    namespace Starlight\Framework\Collections\Views\Structures;

    /**
     * Lewis Lancaster 2017
     *
     * Interface View
     *
     * @package Starlight\Framework\Collections\Views\Structures
     */

    interface View
    {


        /**
         * Returns the routing of this page
         *
         * @return array
         */

        public function routes();
    }