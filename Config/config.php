<?
/**
 * array to configure many things
 */

return array(
	'session.handler.class' => 'Framework\Session\SessionHandler',
	'session.handler.options' => array(
		'sessionLifeTime' => 1800,
		'prefix' => 'uat_',
	),
	'memcache.params' => array(
		'className' => 'Framework\MemcachedClient',
		'persistentId' => 'frameworkTest',
		'options' => array(
			'host' => 'mcl',
			'port' => '11211',
		),
	),
	'routing.map' => 'Config/routing.php',
	'translator.path' => 'Resource/Translation/hu/',
	'templating.template_dir' => 'Resource/Template/',
	'templating.layout' => 'layout.tpl',
	'url.root' => 'http://framework.test/',
	'url.static' => 'http://static.framework.test/',
	'captcha.config' => array(
		'font_path' => __DIR__.'/../Font/',
		'image_path' => __DIR__.'/../Static/img',
	),
	'captcha.url' => '/captcha/show/?'.microtime(),
	'response.default.assignable' => array(
		'userData' => array(
			'from' => 'session',
			'key' => 'user_data',
			'obj_key' => 'login',
			'default' => ''
		)
	),
	'database.connection.data' => include(__DIR__.'/db.php'),
	'mailer.config' => array(
		'senderMail' => 'Framework test site <info@test.com>',
		'replyTo' => 'Framework test site <info@test.com>',
		'mail' => '<info@test.com>',
		'templatePath' => 'Resource/Template/Mail/',
		'charset' => 'utf-8',
	),
	'mail.debug.address' => 'matheasrex@gmail.com',
);
