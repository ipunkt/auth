<?php
/**
 * This config file concerns the displaying of all forms.
 * 
 * The default settings will display a plain form on an empty page.
 * 
 * To select a different version of the premade form, see the 'views' variable
 * Note that you can also change them entirely by publishing them using the command `php artisan view:publish`
 * 
 * To have the the premade forms display inside your own layout take a look at the 'extends' array.
 */
return [
	/**
	 * Selecte your premade forms type.
	 * 
	 * Possible values:
	 * - plain: straight forms
	 * - bootstrap: forms with bootstrap classing
	 * - custom: specifcy your own view names in the custom_views variable
	 */
	'views' => 'plain',

	/**
	 * These view names will be used if views is set to 'custom'
	 */
	'custom_views' => [
		'login' => null,
		'register' => null,
		'remind' => null,
		'reset' => null,
	],

	/**
	 * This is used in premade views to allow integration in a surrounding layout
	 */
	'extends' => [
		/**
		 * The layout view you want to extend.
		 * If you have no layout you want to extend use 'auth::nomaster'
		 */
		'view' => 'auth::nomaster',

		/**
		 * The name of the content section of your layout view
		 * If you have no layout you want to extend use 'content' with the 'auth::nomaster' layout
		 */
		'section' => 'content'
	]
];
 