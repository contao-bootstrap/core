<?php

return array(
	'dropdown' => array(
		'toggle'   => '<b class="caret"></b>',
		'formless' => array('mod_quicklink'),
	),

	'icons' => array(
		'sets' => array(
			'glyphicons' => array(
				'path'       => 'system/modules/bootstrap-core/config/glyphicons.php',
				'stylesheet' => 'system/modules/bootstrap-core/assets/css/glyphicons.css',
				'template'   => '<span class="glyphicon glyphicon-%s"></span>',
			)
		)
	),

	'layout' => array(
		'metapalette' => array(
			'+title' => array('layoutType')
		)
	)
);
