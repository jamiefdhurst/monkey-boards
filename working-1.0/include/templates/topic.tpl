{include file = "header.tpl"}
	<title>{$topic.subject} &middot; {$site_title}</title>
</head>
<body>
	<div id="holder">

	<h1>{$topic.subject}</h1>

{include file = "menu.tpl"}

	<div id="content">

	<p><a href="index.php">{$strings.home}</a> &rArr; <a href="forum.php?n={$topic.forum}">{$topic.forum_name}</a> &rArr; {$topic.subject}</p>

{if $user_type gte 2}{include file = "moderate.tpl"}{/if}

	<table id="topic">
		<thead>
			<tr>
				<th>{$strings.author}</th>
				<th>{$strings.message}</th>
			</tr>
		</thead>
		<tbody>
{foreach value = post from = $posts}
			<tr id="p{$post.timestamp}">
				<td id="author">{if $logged_in eq "true"}<a href="user.php?u={$post.username}">{/if}{if $logged_in neq "true"}<strong>{/if}{$post.username}{if $logged_in neq "true"}</strong>{/if}{if $logged_in eq "true"}</a>{/if}<br/>
				{$post.user_title}</td>
				<td><p><a href="#p{$post.timestamp}">{$post.stamp}</a>{if $logged_in eq "true" and $post.editable eq 1 and $username eq $post.username} &ndash; <a href="edit.php?post={$post.id}">edit</a>{/if}</p>
				{$post.message}</td>
			</tr>
{/foreach}
		</tbody>
	</table>
{if $logged_in eq "true"}
	<h2>{$strings.post_reply}</h2>
{if $topic.locked eq 0}

	<form action="post.php" method="post">
		<input name="mode" type="hidden" value="reply"/>
		<input name="topic" type="hidden" value="{$topic.id}"/>

		<p><textarea cols="50" id="message" name="message" rows="10"></textarea></p>

{include file = "post_footer.tpl"}
	</form>
{else}

	<p>{$strings.topic_locked}</p>
{/if}
{/if}

</div>

{include file = "footer.tpl"}
