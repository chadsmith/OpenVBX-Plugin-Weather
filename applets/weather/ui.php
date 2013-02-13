<?php
  $flow_type = AppletInstance::getFlowType();
  $location = AppletInstance::getValue('location', 'auto');
?>
<div class="vbx-applet vbx-applet-weather">
	<h2>Location</h2>
	<div class="radio-table">
		<table>
			<tr class="radio-table-row first <?php echo ($location == 'auto') ? 'on' : 'off' ?>">
				<td class="radio-cell">
					<input type="radio" name="location" value="auto" <?php echo $location == 'auto' ? 'checked="checked"' : '' ?> />
				</td>
				<td class="content-cell">
					<h4>Detect <?php echo $flow_type == 'voice' ? 'caller' : 'sender'; ?>'s location</h4>
				</td>
			</tr>
			<tr class="radio-table-row <?php echo ($location == 'prompt') ? 'on' : 'off' ?>">
				<td class="radio-cell">
					<input type="radio" name="location" value="prompt" <?php echo $location == 'prompt' ? 'checked="checked"' : '' ?> />
				</td>
				<td class="content-cell">
					<h4><?php echo $flow_type == 'voice' ? 'Prompt for location' : 'Use message body'; ?></h4>
				</td>
			</tr>
			<tr class="radio-table-row last <?php echo ($location == 'preset') ? 'on' : 'off' ?>">
				<td class="radio-cell">
					<input type="radio" name="location" value="preset" <?php echo $location == 'preset' ? 'checked="checked"' : '' ?> />
				</td>
				<td class="content-cell">
					<div class="vbx-input-container input">
						<input type="text" class="medium" name="preset-location" value="<?php echo AppletInstance::getValue('preset-location') ?>" placeholder="ZIP code" />
					</div>
				</td>
			</tr>
		</table>
	</div>
<?php if($flow_type == 'voice'): ?>
  <br/>
	<h2>Next</h2>
	<p>After reading the weather, continue to the next applet</p>
	<div class="vbx-full-pane">
		<?php echo AppletUI::DropZone('next'); ?>
	</div>
<?php endif; ?>
</div>