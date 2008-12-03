{include file = "header.tpl"}
	<title>{$strings.profile} &middot; {$site_title}</title>
</head>
<body>
	<div id="holder">

	<h1>{$user.username}</h1>

{include file = "menu.tpl"}

	<div id="content">

	<form action="profile.php" method="post">

		<fieldset>
			<legend>Enter your personal details</legend>
			<p><label for="name">Name
			<input id="name" maxlength="100" name="name" size="25" type="text" value="{$user.name}"/></label></p>
		</fieldset>

		<fieldset>
			<legend>Enter a valid email address</legend>
			<p><label for="email">Email
			<input id="email" maxlength="300" name="email" size="30" type="text" value="{$user.email}"/></label></p>
		</fieldset>

		<fieldset>
			<legend>Change your password</legend>

			<p><label for="newpw">New password <input id="newpw" maxlength="128" name="newpw" size="20" type="password"/></label></p>
			<p><label for="newpw2">Confirm new password <input id="newpw2" maxlength="128" name="newpw2" size="20" type="password"/></label></p>
		</fieldset>

		<p><input type="submit" value="{$strings.save}"/></p>

	</form>

	</div>

{include file = "footer.tpl"}
