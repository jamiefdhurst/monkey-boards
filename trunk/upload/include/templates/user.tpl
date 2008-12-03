{include file = "header.tpl"}
	<title>{$user.username} &middot; {$site_title}</title>
</head>
<body>
	<div id="holder">

	<h1>{$user.username}</h1>

{include file = "menu.tpl"}

	<div id="content">

<dl>
	<dt>{$strings.username}</dt>
	<dd>{$user.username}</dd>

	<dt>{$strings.title}</dt>
	<dd>{$user.title}</dd>

	<dt>{$strings.email}</dt>
	<dd>{$user.email}</dd>

	<dt>{$strings.registered}</dt>
	<dd>{$user.registered}</dd>

	<dt>{$strings.posts}</dt>
	<dd>{$user.posts}</dd>

	<dt>Last post</dt>
	<dd>{$user.last_post}</dd>
{if isset($user.name) }
	<dt>Name</dt>
	<dd>{$user.name}</dd>
{/if}
</dl>

</div>

{include file = "footer.tpl"}
