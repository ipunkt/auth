<?php

/**
 * This config file takes care of setting up the package.
 * 
 * All values herein are set so sane defaults for use with the default laravel ORM eloquent and the matching auth
 * driver.
 */
return [
    /**
     * set config value auth.model to \Ipunkt\Auth\EloquentModel?
     *
     * Leave this at true to use the User model provided in this package for your laravel authentication
     * 
     * Set this to false if you bring your own User model.
     */
    'set_usermodel' => true,
    
	/**
	 * Leave this at true if you wish to use the repository provided in this package.
	 * The default repository builds models based on the auth.model config value
	 * 
	 * Set this to false if you bring your own Repository, please make sure it binds to and implements
	 * Ipunkt\Auth\Repositories\RepositoryInterface
	 * 
	 * shall we bind our repository to Ipunkt\Auth\Repositories\RepositoryInterface or do you bring your own?
	 */
    'set_repository' => true,

	/**
	 * This is prepended to all paths in this packages routes.php
	 * the prefix may not end with a slash
	 * Also note that setting this will mean /login becomes $route_prefix.'/login', thus the default 'auth' filter won't
	 * redirect to it anymore
	 * 
	 * example: 'route_prefix' => 'auth',
	 */
	'route_prefix' => '',

    /**
     * Leave this at true to use the packages internal email reminder view.
     * 
     * Set to false and set the config value auth.reminder.email to your own view to send custom reminder emails
     * 
     * set config value auth.reminder.email to auth::reminder/email?
     */
    'set_reminder' => true,

    /**
     * This decides which routes the UserController should publish
     */
    'routes' => [
        'index',
        'create',
        'store',
        'show',
        'edit',
        'update',
        'destroy'
    ]
];
