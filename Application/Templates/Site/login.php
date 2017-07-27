<h1>Управление счетом</h1>
<h2>Авторизация</h2>
<?php if ($error_message ?? '' !== ''): ?>
    <span style="color:red"><?=$error_message?></span>
<?php endif ?>
<form method="POST">
	<label>Логин</label>
	<input name="login" value="<?=$login ?? ''?>"></input>
	<label>Пароль</label>
	<input name="password" type="password"></input>
	<button name="submit">Войти</button>
</form>