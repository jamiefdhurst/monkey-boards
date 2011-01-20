{include file = "header.tpl"}
	<title>Monkey Board Installation</title>
</head>
<body>
	<div id="holder">

{if $install_errors eq "none"}
	<h1>Monkey Boards installation</h1>

	<ul id="menu">
		<li><strong>Please note:</strong> This is <em>alpha</em> software and is not suitable for production servers</li>
	</ul>

	<div id="content">

	<p>Welcome to Monkey Boards installation! You are about to install Monkey Boards. In order to install Monkey Boards you must complete the form set out below. If you encounter any difficulties with the installation, please refer to the documentation.</p>

	<form action="install.php" id="install" method="post">

	<fieldset>
		<legend>Enter the name of your database</legend>

		<p>The name of the database that Monkey Boards will be installed into.</p>

		<p><label for="database">Database name</label><br/>
		<input id="database" maxlength="25" name="database" size="25" type="text" value="db-alpha"/></p>
	</fieldset>

	<fieldset>
		<legend>Enter administrator username</legend>

		<p>The username of the forum administrator. You can later create more administrators and moderators.</p>

		<p><label for="username">Administrator username</label><br/>
		<input id="username" maxlength="50" name="username" size="20" type="text"/></p>
	</fieldset>

	<fieldset>
		<legend>Enter and confirm administrator password</legend>

		<p><label for="password">Password</label><br/>
		<input id="password" maxlength="128" name="password" size="20" type="password"/></p>

		<p><label for="cpassword">Confirm password</label><br/>
		<input id="cpassword" maxlength="128" name="cpassword" size="20" type="password"/></p>
	</fieldset>

	<fieldset>
		<legend>Enter administrator's email</legend>

		<p>The e-mail address of the forum administrator.</p>

		<p><label for="email">Administrator's e-mail</label><br/>
		<input id="email" maxlength="300" name="email" size="20" type="text"/></p>
	</fieldset>

	<fieldset>
		<legend>Enter title of site</legend>

		<p>The title of the message boards.</p>

		<p><label for="site_title">Site title</label><br/>
		<input id="site_title" maxlength="100" name="site_title" size="25" type="text"/></p>
	</fieldset>

	<p><strong>Please note:</strong> installation can take 3&ndash;10 seconds depending on server speed. Please be patient.</p>

	<p><input type="submit" value="Start install"/></p>

</form>
{else}
	<h1>Monkey Boards installation</h1>

	<div id="content">

	<p>Welcome to Monkey Boards. One or more problems have been detected with your server configuration.<br/>
	You must fix them problems before you can install Monkey Boards.</p>

{foreach value = problem from = $install_errors}
	<p>{$problem}</p>
{/foreach}
{/if}

</div>

{include file = "footer.tpl"}
