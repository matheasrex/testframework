<form class="form-signin" method="post">
	<h2 class="form-signin-heading">Please sign in</h2>
	<? if (isset($error)) :?>
		<p class="text-error"><?=$error?></p>
	<? endif ?>
	<input required type="text" name="login" class="input-block-level" placeholder="Username">
	<input required  type="password" name="password" class="input-block-level" placeholder="Password">
	<input required  type="text" name="capcha_code" class="input-block-level" placeholder="Type the text here from the image">
	<img src="<?=$config->get('captcha.url')?>" />
	<button class="btn btn-large btn-primary" type="submit">Sign in</button>
</form>