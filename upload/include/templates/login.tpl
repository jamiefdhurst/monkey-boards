{if $mode eq "forgot"}
{include file = "header.tpl"}
	<title>{$strings.forgotten_pw} &middot; {$site_title}</title>
</head>
<body>
	<div id="holder">

	<h1>{$strings.forgotten_pw}</h1>

{include file = "menu.tpl"}

	<div id="content">

		<p>{$strings.forgotten_pw_blurb}</p>

		<form action="login.php?mode=reset" method="post">
			<p><label for="username">{$strings.username}</label>
			<input id="username" maxlength="50" name="username" size="20" type="text"/></p>

			<p><label for="email">{$strings.email_add}</label>
			<input id="email" maxlength="300" name="email" size="30" type="text"/></p>

			<p><input name="submit" type="submit" value="{$strings.submit}"/>
			<input name="cancel" type="submit" value="{$strings.cancel}"/></p>
		</form>

	</div>

{include file = "footer.tpl"}
{else}
{include file = "header.tpl"}
	<title>{$strings.login} &middot; {$site_title}</title>
</head>
<body>
	<div id="holder">

	<h1>{$strings.login}</h1>

{include file = "menu.tpl"}

	<div id="content">

<form action="login.php" id="login" method="post">

<fieldset>
	<legend>{$strings.login}</legend>

	<p><label for="username">{$strings.username}</label>
	<input id="username" maxlength="50" name="username" size="20" type="text"/></p>

	<p><label for="password">{$strings.password}</label>
	<input id="password" name="password" size="20" type="password"/></p>

	<p><input type="submit" value="{$strings.login}"/></p>
</fieldset>

</form>

<p><a href="login.php?mode=forgot">{$strings.forgot_pw}</a></p>

</div>

{include file = "footer.tpl"}
{/if}
