<?php
if(count($_POST))
	PluginData::set('settings', array(
		'api_key' => $_POST['api_key']
	));
$settings = PluginData::get('settings', array(
	'api_key' => null
));
if(is_object($settings))
	$settings = get_object_vars($settings);
?>
<style>
	.vbx-weather form {
		padding: 20px 5%;
	}
	.vbx-weather form p {
		margin: 20px 0;
	}
</style>
<div class="vbx-content-main">
	<div class="vbx-content-menu vbx-content-menu-top">
		<h2 class="vbx-content-heading">Weather Settings</h2>
	</div>
	<div class="vbx-table-section vbx-weather">
		<form method="post" action="">
			<fieldset class="vbx-input-container">
				<p>
					<label class="field-label">Weather Underground API Key<br/>
						<input type="password" name="api_key" class="medium" value="<?php echo htmlentities($settings['api_key']); ?>" />
					</label>
				</p>
				<p><button type="submit" class="submit-button"><span>Save</span></button></p>
			</fieldset>
		</form>
	</div>
</div>