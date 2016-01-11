<?php

function dul_bento_admin_settings() {


	$form['results_title'] = array(
		'#type' => 'textfield',
		'#title' => t('Bento Page Title'),
		'#description' => t('Enter the desired title for bento search results'),
		'#default_value' => variable_get('dul_bento.results_title', ''),
	);

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

	$form['bento_results_index'] = array(
		'#type' => 'checkbox',
		'#title' => t('Index the results page'),
		'#description' => t('defaults to "no" (for purposes of search engine indexing)'),
		'#default_value' => variable_get('dul_bento.bento_results_index', ''),
	);

	$form['bento_logging'] = array(
		'#type' => 'checkbox',
		'#title' => t('Activate Bento Logging'),
		'#description' => t('Dumps log file to ../backup'),
		'#default_value' => variable_get('dul_bento.bento_logging', ''),
	);

	$form['bento_logging_daily'] = array(
		'#type' => 'checkbox',
		'#title' => t('Use Daily Log Files'),
		'#description' => t('If selected, a new file will also be generate every 24 hours - only check if using logging.'),
		'#default_value' => variable_get('dul_bento.bento_logging_daily', ''),
	);

	$form['summon_other_content_types'] = array(
		'#type' => 'textarea',
		'#title' => t('Summon "Other" Content Types'),
		'#description' => t('Enter the list of content types'),
		'#default_value' => variable_get('dul_bento.summon_other_content_types', ''),
	);

	$form['summon_other_libraries'] = array(
		'#type' => 'textarea',
		'#title' => t('Summon "Other" Libraries'),
		'#description' => t('Enter the list of libraries'),
		'#default_value' => variable_get('dul_bento.summon_other_libraries', ''),
	);



	$form['#submit'][] = 'dul_bento_admin_settings_submit';

	return system_settings_form($form);
}

/**
 * Submit callback for the above form function^
 */
function dul_bento_admin_settings_submit($form, $form_state) {
	variable_set('dul_bento.results_title', $form_state['values']['results_title']);
	variable_set('dul_bento.results_url', $form_state['values']['results_url']);
	variable_set('dul_bento.summon_accessId', $form_state['values']['summon_accessId']);
	variable_set('dul_bento.summon_secretKey', $form_state['values']['summon_secretKey']);
	variable_set('dul_bento.summon_clientKey', $form_state['values']['summon_clientKey']);

	variable_set('dul_bento.bento_results_index', $form_state['values']['bento_results_index']);

	variable_set('dul_bento.bento_logging', $form_state['values']['bento_logging']);
	variable_set('dul_bento.bento_logging_daily', $form_state['values']['bento_logging_daily']);

	variable_set('dul_bento.summon_other_content_types', $form_state['values']['summon_other_content_types']);
	variable_set('dul_bento.summon_other_libraries', $form_state['values']['summon_other_libraries']);
}
