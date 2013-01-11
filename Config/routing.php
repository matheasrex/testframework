<?

/**
 * list of routed objects to pathes
 * this is an exception list
 * the fallback is a contorller::function() calling
 * for a /controller/function/ request
 */

return array(
	'/' => array(
		'className' => 'Main',
		'function' => 'index',
	),
	'/login' => array(
		'className' => 'Userbase',
		'function' => 'login',
	),
	'/unauthorized' => array(
		'className' => 'Userbase',
		'function' => 'restricted',
	),
	'/404' => array(
		'className' => 'Main',
		'function' => 'notFound',
	),
	'/logout' => array(
		'className' => 'Userbase',
		'function' => 'logout',
	),
);
