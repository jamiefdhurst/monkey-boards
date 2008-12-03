{include file = "header.tpl"}
	<title>{$site_title}</title>
</head>
<body id="index">
	<div id="holder">

	<h1><span>{$site_title}</span></h1>

{include file = "menu.tpl"}

	<div id="content">

{if $categories_count gt 0}
{foreach value = category from = $forums}
	<div class="category">
	<h2>{$category.name}</h2>
	<table>
		<thead>
			<tr>
				<th>{$strings.forum}</th>
				<th>{$strings.topics}</th>
				<th>{$strings.posts}</th>
				<th>{$strings.last_post}</th>
			</tr>
		</thead>
		<tbody>
	{foreach value=forum from=$category.forums}
			<tr>
				<td class="forum"><a href="forum.php?n={$forum.id}">{$forum.name}</a><br/>
				{$forum.blurb}</td>
				<td>{$forum.topics}</td>
				<td>{$forum.posts}</td>
				<td>{$forum.last_post}</td>
			</tr>
	{/foreach}
		</tbody>
	</table>
	</div>
{/foreach}
{else}
	<p>{$strings.no_forums}</p>
{/if}

<ul id="site-info">
	<li>{$strings.total_users} {$site_info.users}</li>
	<li>{$strings.total_topics} {$site_info.topics}</li>
	<li>{$strings.total_posts} {$site_info.posts}</li>
</ul>

</div>

{include file = "footer.tpl"}
