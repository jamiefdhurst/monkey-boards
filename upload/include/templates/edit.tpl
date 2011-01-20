{include file = "header.tpl"}
	<title>{$strings.editing_post} &middot; {$site_title}</title>
</head>
<body>
	<div id="holder">

	<h1>{$strings.editing_post}</h1>

{include file = "menu.tpl"}

	<div id="content">

        <form action="edit.php" method="post">
		<input name="post" type="hidden" value="{$post.id}"/>

                <input name="mode" type="hidden" value="reply"/>
                <input name="topic" type="hidden" value="{$topic.id}"/>
{if isset($first_post) and $first_post eq true}
		<p><label for="subject">Subject
		<input id="subject" maxlength="60" name="subject" size="30" type="text" value="{$post.subject}"/></label></p>
{/if}
                <p><textarea cols="50" id="message" name="message" 
rows="10">{$post.message}</textarea></p>
{include file = "post_footer.tpl"}
        </form>

	</div>

{include file = "footer.tpl"}
