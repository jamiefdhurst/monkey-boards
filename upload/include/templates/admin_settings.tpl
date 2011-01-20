{include file = "admin_header.tpl"}
	<title>{$strings.admin}: {$strings.settings} &middot; {$site_title}</title>
</head>
<body>
	<div id="holder">

	<h1>{$strings.settings}</h1>

{include file = "admin_menu.tpl"}

	<div id="content">

		<form action="settings.php" method="post">
			<fieldset>
				<legend>{$strings.essentials}</legend>

				<p><label for="site_title">{$strings.site_title}</label>
				<input id="site_title" maxlength="100" name="site_title" size="30" type="text" value="{$site_title}"/></p>

				<p><label for="base_address">{$strings.base_address}</label>
				<input id="base_address" maxlength="200" name="base_address" size="50" type="text" value="{$base_address}"/></p>

				<p><label for="date_format">{$strings.date_format}</label>
				<input id="date_format" maxlength="20" name="date_format" size="20" type="text" value="{$date_format}"/></p>

				<p><label for="site_style">{$strings.site_style}</label>
				<select id="site_style" name="site_style">
{foreach value = entry from = $styles}
					<option{if $style eq $entry} selected="selected"{/if}>{$entry}</option>
{/foreach}
				</select></p>
			</fieldset>

			<fieldset>
				<legend>{$strings.welcome_email}</legend>

				<p><label for="email_from">{$strings.email_from}</label>
				<input id="email_from" maxlength="100" name="email_from" size="25" type="text" value="{$email_from}"/></p>
			</fieldset>
			<p><input type="submit" value="{$strings.save}"/></p>
		</form>

	</div>

{include file = "footer.tpl"}
