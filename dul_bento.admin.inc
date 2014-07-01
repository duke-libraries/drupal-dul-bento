<?php

function dul_bento_admin_settings() {
	
	$form['results_url'] = array(
		'#type' => 'textfield',
		'#title' => t('Bento URL'),
		'#description' => t('Enter the desired URL for bento search results (no initial or trailing slashes)'),
		'#default_value' => variable_get('dul_bento.results_url', ''),
	);
	
	$form['summon_accessId'] = array(
		'#type' => 'textfield',
		'#title' => t('Summon Access ID'),
		'#description' => t('Enter the Summon Access ID'),
		'#default_value' => variable_get('dul_bento.summon_accessId', ''),
	);
	
	$form['summon_secretKey'] = array(
		'#type' => 'textfield',
		'#title' => t('Summon Secret Key'),
		'#description' => t('Enter the Summon Secret Key'),
		'#default_value' => variable_get('dul_bento.summon_secretKey', ''),
	);
	
	$form['summon_clientKey'] = array(
		'#type' => 'textfield',
		'#title' => t('Summon Client Key'),
		'#description' => t('Enter the Summon Client Key'),
		'#default_value' => variable_get('dul_bento.summon_clientKey', ''),
	);
	
	
	
	$form['#submit'][] = 'dul_bento_admin_settings_submit';

	return system_settings_form($form);
}

/**
 * Submit callback for the above form function^
 */
function dul_bento_admin_settings_submit($form, $form_state) {
	variable_set('dul_bento.results_url', $form_state['values']['results_url']);
	variable_set('dul_bento.summon_accessId', $form_state['values']['summon_accessId']);
	variable_set('dul_bento.summon_secretKey', $form_state['values']['summon_secretKey']);
	variable_set('dul_bento.summon_clientKey', $form_state['values']['summon_clientKey']);
}
