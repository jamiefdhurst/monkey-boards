{include file = "header.tpl"}
	<title>{$strings.user_list} &middot; {$site_title}</title>
</head>
<body>
	<div id="holder">

	<h1>{$strings.user_list}</h1>

{include file = "menu.tpl"}

	<div id="content">

{if $users_count > 0}
<div class="category">
<h2>User list</h2>
<table>
	<thead>
		<tr>
			<th>{$strings.username}</th>
			<th>{$strings.title}</th>
			<th>{$strings.posts}</th>
			<th>{$strings.registered}</th>
		</tr>
	</thead>
	<tbody>
{foreach value = user from = $users}
		<tr>
			<td><a href="user.php?u={$user.username}">{$user.username}</a></td>
			<td>{$user.title}</td>
			<td>{$user.posts}</td>
			<td>{$user.registered}</td>
		</tr>
{/foreach}
	</tbody>
</table>
</div>
{/if}

</div>

{include file = "footer.tpl"}
