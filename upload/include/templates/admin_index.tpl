{include file = "admin_header.tpl"}
	<title>{$strings.admin} &middot; {$site_title}</title>
</head>
<body>
	<div id="holder">

	<h1>{$strings.admin}</h1>

{include file = "admin_menu.tpl"}

	<div id="content">

		<p>{$strings.admin_welcome}</p>

		<ul>
			<li>{$strings.forums_title}</li>
			<li>{$strings.settings_title}</li>
			<li>{$strings.ban_title}</li>
			<li>{$strings.censor_title}</li>
			<li>{$strings.stats_title}</li>
		</ul>

		<h2>{$strings.stats}</h2>

		<dl id="stats">
			<dt>{$strings.script} {$strings.version}</dt>
			<dd>{$strings.app_name} {$app_version}</dd>

			<dt>{$strings.php} {$strings.version}</dt>
			<dd>{$strings.php} {$php_version}</dd>

			<dt>{$strings.sqlite} {$strings.version}</dt>
			<dd>{$strings.sqlite} {$sqlite_version}</dd>

			<dt>{$strings.database}</dt>
			<dd>{$db_rows} {$strings.rows}, {$db_filesize} 
KiB</dd>
		</dl>

	</div>

{include file = "footer.tpl"}
