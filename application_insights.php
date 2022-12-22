<?php

/**
 * Open Telemetry
 *
 * Plugin to add open telemetry
 *
 * @author  Adam Welle
 *
 * Copyright (C) Carnegie Mellon University
 *
 * This program is a Roundcube (https://roundcube.net) plugin.
 * For more information see README.md.
 * For configuration see config.inc.php.dist.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Roundcube. If not, see https://www.gnu.org/licenses/.
 */
class application_insights extends rcube_plugin
{
    //public $task = '^((?!login).)*$';
    //private $config = [];
    private $rcube;

    public function init()
    {
        #$this->rcube = rcube::get_instance();

        #$key = $this->rcube->config->get('application_insights_instrumentation_key');

	require_once('ai.php');

	$this->include_script('snippet.js');
    }
}
