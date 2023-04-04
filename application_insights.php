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
    //private $config = [];
    private $rcube;

    private $telemetryClient;
    private $telemetryChannel;
    private $context;
    private $host;

    public function init()
    {


        #$this->rcube = rcube::get_instance();
        #$key = $this->rcube->config->get('application_insights_instrumentation_key');

	$this->include_script('snippet.js');

	$this->add_hook('login_after', [$this, 'login_after']);

	//login_failed
	$this->add_hook('message_before_send', [$this, 'message_before_send']);
	//$this->add_hook('message_sent', [$this, 'message_sent']);
	$this->add_hook('message_read', [$this, 'message_read']);
	//error_page

    }

    public function setup() {

	require_once __DIR__ . '/vendor/autoload.php';

	$this->host = $_SERVER['SERVER_NAME'];

	//TODO get url from config or env var
	$endpointUrl = getenv('ENDPOINT_URL');
	//TODO get key from config or env var
	$instrumentationKey = getenv('INSTRUMENTATION_KEY');
	$this->telemetryChannel = new \ApplicationInsights\Channel\Telemetry_Channel($endpointUrl);
	$this->telemetryClient = new \ApplicationInsights\Telemetry_Client(NULL, $this->telemetryChannel);
	$this->context = $this->telemetryClient->getContext();
        
	// Necessary
	$this->context->setInstrumentationKey($instrumentationKey);
    }

    public function login_after($args) {
        $this->setup();

	// Optional
	$this->context->getSessionContext()->setId(session_id());
	//$context->getUserContext()->setId('YOUR USER ID');
	//$context->getApplicationContext()->setVer('YOUR VERSION');
	//$context->getLocationContext()->setIp('YOUR IP');

	// Start tracking
	//$telemetryClient->trackDependency('Query table', "SQL", 'SELECT * FROM users;', time(), 122, true);
	//$telemetryClient->trackMetric('myMetric', 42.0);


	$name = $_SESSION['username'];
	$this->telemetryClient->trackEvent("$this->host user $name login");
	$this->telemetryClient->flush();

    }

    function message_before_send($args) {
	$this->setup();
	$message = $args['message'];
	$to = $args['mailto'];
	$from = $args['from'];
	$options = $args['options'];
	$headers = $message->headers();
	$mymessage = $message->getMessage();
	
	$this->telemetryClient->trackEvent("$this->host user $from messge before send to $to with subject $mymessage->subject");
	$this->telemetryClient->flush();
    }

    function message_read($args) {
	$this->setup();
	$uid = $args['uid'];
	$mailbox = $args['mailbox'];
	$name = $_SESSION['username'];
	$message = $args['message'];
	$to = $message->get_header('to');
	$from = $message->get_header('from');
	$subject = $message->get_header('subject');
	
	$this->telemetryClient->trackEvent("$this->host $name read $uid from $mailbox from $from to $to subject $subject");
	$this->telemetryClient->flush();
    }
}
