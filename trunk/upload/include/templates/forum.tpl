{include file = "header.tpl"}
	<title>{$forum.name} &middot; {$site_title}</title>
</head>
<body>
	<div id="holder">

	<h1>{$forum.name}</h1>

{include file = "menu.tpl"}

	<div id="content">

	<p><a href="index.php">{$strings.home}</a> &rArr; {$forum.name}</p>

{if $topics_count > 0}
	<table id="topics">
		<thead>
			<tr>
				<th>{$strings.topic}</th>
				<th>{$strings.replies}</th>
				<th>{$strings.views}</th>
				<th>{$strings.last_post}</th>
			</tr>
		</thead>
		<tbody>
{foreach value = topic from = $sticky_topics}
			<tr>
				<td class="topic">{$strings.sticky}: <a 
href="topic.php?id={$topic.id}">{$topic.subject}</a> {$strings.by} {if $logged_in 
eq "true"}<a href="user.php?u={$topic.author}">{/if}{$topic.author}{if $logged_in eq "true"}</a>{/if}</td>
				<td>{$topic.replies}</td>
				<td>{$topic.views}</td>
				<td>{$topic.last_post}</td>
			</tr>
{/foreach}
{foreach value = topic from = $normal_topics}
			<tr>
				<td class="topic"><a 
href="topic.php?id={$topic.id}">{$topic.subject}</a> {$strings.by} {if $logged_in 
eq "true"}<a href="user.php?u={$topic.author}">{/if}{$topic.author}{if $logged_in eq "true"}</a>{/if}</td>
				<td>{$topic.replies}</td>
				<td>{$topic.views}</td>
				<td>{$topic.last_post}</td>
			</tr>
{/foreach}
		</tbody>
	</table>
{else}
	<p>{$strings.no_topics}</p>
{/if}
{if $logged_in eq "true"}
	<h2>{$strings.new_topic}</h2>

	<form action="post.php" method="post">
		<input name="mode" type="hidden" value="topic"/>
		<input name="forum" type="hidden" value="{$forum.id}"/>

		<p><label for="subject">{$strings.subject}</label>
		<input id="subject" maxlength="60" name="subject" size="30" type="text"/></p>

		<p><label for="message">{$strings.message}</label><br/>
		<textarea cols="60" id="message" name="message" rows="12"></textarea></p>

{include file = "post_footer.tpl"}
	</form>
{/if}

</div>

{include file = "footer.tpl"}
