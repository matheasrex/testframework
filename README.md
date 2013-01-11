Hi!

This is a framework with a small test site. It is really simple and should be extended for production work but it's good enough for the current purpose.

To use it:

* create a mysql database and a user 
* rename Config/db-sample.php to Config/db.php and fill it with the proper data
* create a userbase table
* create a host to the servers ip for static.framework.test and framework.test in your hosts file (or change it in Config/config.php)
* on your server's virtual host send all the requests to Web/index.php
* you ne a configured memcahched with the host 'mcl' - or you can change it in Config/config.php
* configure details in Config/config.php (e.g host, email, memcached config, session lifetime etc.)
* Then you can customize it and test it

TODO:

* entitymanager is really week yet, no insert or update is supported
* headers should be set only in \Framework\Response througj $request->headers e.g
* \Framework\Connection should have some more functions such as transaction handling, restore and backup state, last insert id getter sequence generator, etc
* \Framework\Mailer could habe a property for logging to database or filesístem the mails
... and so on...
