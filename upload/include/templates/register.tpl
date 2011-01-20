{include file = "header.tpl"}
	<title>{$strings.register} &middot; {$site_title}</title>
</head>
<body>
	<div id="holder">

	<h1>{$strings.register}</h1>

{include file = "menu.tpl"}

	<div id="content">

<form action="register.php" id="register" method="post">

<fieldset>
	<legend>{$strings.register_as_user}</legend>

	{$strings.registerform}

	<p><label for="username">{$strings.username}</label>
	<input id="username" maxlength="50" name="username" size="20" type="text"/></p>

	<p><label for="email">{$strings.email_add}</label>
	<input id="email" maxlength="300" name="email" size="30" type="text"/></p>
</fieldset>

	<p><input type="submit" value="{$strings.register}"/></p>

</form>

</div>

{include file = "footer.tpl"}
