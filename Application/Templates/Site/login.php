<h2>Вход в аккаунт</h2>
<?if ($error_message ?? '' !== ''):?>
	<span color="red"><?=$error_message?></span>
<?endif?>
<form method="POST" action="/Site/login">
	<label>Логин</label>
	<input name="login" value="<?=$login ?? ''?>"></input>
	<label>Пароль</label>
	<input name="password" type="password"></input>
	<button name="submit">Войти</button>
</form>