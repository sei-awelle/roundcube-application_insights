<?php

require_once __DIR__ . '/vendor/autoload.php';

$endpointUrl = "https://usgovvirginia-1.in.applicationinsights.azure.us//v2/track";
$telemetryChannel = new \ApplicationInsights\Channel\Telemetry_Channel($endpointUrl);
$telemetryClient = new \ApplicationInsights\Telemetry_Client(NULL, $telemetryChannel);
//$telemetryChannel = $telemetryClient.getChannel();
#$telemetryChannel.setEndpointUrl($endpointUrl);

$context = $telemetryClient->getContext();

// Necessary
$context->setInstrumentationKey('feae4ab5-7c1a-fc17-b500-d7eded81eeb2');

// Optional
$context->getSessionContext()->setId(session_id());
//$context->getUserContext()->setId('YOUR USER ID');
//$context->getApplicationContext()->setVer('YOUR VERSION');
//$context->getLocationContext()->setIp('YOUR IP');

// Start tracking
//$telemetryClient->trackDependency('Query table', "SQL", 'SELECT * FROM users;', time(), 122, true);
$telemetryClient->trackMetric('myMetric', 42.0);

//$telemetryClient->trackDependency('POST', "HTTP", "https://roundcube.cwdoe.cmusei.dev", time(), 324, false, 503);
//$telemetryClient->trackRequest('POST', 'https://roundcube.cwdoe.cmusei.dev', time());
//$telemetryClient->trackRequest('GET', 'https://roundcube.cwdoe.cmusei.dev', time());

//$telemetryClient->trackEvent('name of your event');
$telemetryClient->flush();


