 <?php


/**
 * Setting administrative for Bento
 */
 
 function dul_bento_admin_overview($form, &$form_state)
	
	$form['bento_results_url'] = array(
		'#type' => 'textfield',
		'#default_value' => '',	// use "variable_get()" to fetch this value
		'#required' => TRUE,
		'#title' => 'Bento Results URL',
		);
		
	$form['bento_circ_status'] = array(
		'#type' => 'textfield',
		'#default_value' => '',	// use "variable_get()" to fetch this value
		'#required' => TRUE,
		'#title' => 'Variable for URL Availability',
		);

	$form['actions'] = array(
		'#type' => 'actions',
		);
		
	$form['actions']['submit'] = array(
		'#type' => 'submit',
		'#value' => 'Submit',
		);

	return $form;
}

function dul_bento_admin_setting_submit($form, &$form_state) {
	
	$bento_results_url = $form_state['values']['bento_results_url'];
	
	$bento_circ_status = $form_state['values']['bento_circ_status'];

}



