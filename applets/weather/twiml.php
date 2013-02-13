<?php
$ci =& get_instance();

$flow_type = AppletInstance::getFlowType();
$location = AppletInstance::getValue('location', 'auto');

if($location == 'preset')
  $zip = AppletInstance::getValue('preset-location');
elseif($location == 'auto') {
  $from_or_to = 'From';
  if(isset($_REQUEST['Direction']) && !in_array($_REQUEST['Direction'], array('inbound', 'incoming')))
    $from_or_to = 'To';
  if(!empty($_REQUEST[$from_or_to . 'Zip']))
    $zip = $_REQUEST[$from_or_to . 'Zip'];
}

$settings = PluginData::get('settings');
if(is_object($settings))
	$settings = get_object_vars($settings);

if($flow_type != 'voice' && $location == 'prompt')
  $zip = $_REQUEST['Body'];

$digits = clean_digits($ci->input->get_post('Digits'));
if(!empty($digits))
  $zip = $digits;

$response = new TwimlResponse;

if(!empty($zip)) {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "http://api.wunderground.com/api/{$settings['api_key']}/conditions/q/{$zip}.json");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	$weather = json_decode(curl_exec($ch));
	curl_close($ch);

	if(AppletInstance::getFlowType() == 'voice') {
  	if($weather->error)
      $response->say($weather->error->description, array(
        'voice' => $ci->vbx_settings->get('voice', $ci->tenant->id),
        'voice_language' => $ci->vbx_settings->get('voice_language', $ci->tenant->id)
      ));
    else
  		$response->say("The current conditions in {$weather->current_observation->display_location->city}, {$weather->current_observation->display_location->state} are {$weather->current_observation->temp_f} degrees and {$weather->current_observation->weather}.", array(
  			'voice' => $ci->vbx_settings->get('voice', $ci->tenant->id),
  			'voice_language' => $ci->vbx_settings->get('voice_language', $ci->tenant->id)
  		));
		$next = AppletInstance::getDropZoneUrl('next');
		if(!empty($next))
			$response->redirect($next);
	}
	else {
  	if($weather->error)
      $response->sms($weather->error->description);
		else
		  $response->sms("{$weather->current_observation->display_location->city}, {$weather->current_observation->display_location->state}: {$weather->current_observation->temp_f}&deg;F and {$weather->current_observation->weather}.");
  }
}
elseif($flow_type == 'voice' && $location == 'prompt') {
  $gather = $response->gather(array('numDigits' => 5));
  $gather->say('Please enter your ZIP code.', array(
		'voice' => $ci->vbx_settings->get('voice', $ci->tenant->id),
		'voice_language' => $ci->vbx_settings->get('voice_language', $ci->tenant->id)
	));
	$response->redirect();
}

$response->respond();