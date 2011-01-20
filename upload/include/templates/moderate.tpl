{if isset($action)}
{include file = "header.tpl"}
	<title>{$strings.topic_mod} &middot; {$site_title}</title>
</head>
<body>
	<div id="holder">

	<h1>{$strings.topic_mod}</h1>

{include file = "menu.tpl"}

	<div id="content">
{if $action eq "delete_confirm"}
		<p>{$strings.topic_delete_confirm1} <q>{$topic.subject}</q> {$strings.topic_delete_confirm2}</p>

		<p>{$strings.perm_warning}</p>

		<form action="moderate.php" class="delete" method="post">
			<input name="action" type="hidden" value="delete"/>
			<input name="topic" type="hidden" value="{$topic.id}"/>
			<input type="submit" value="{$strings.delete}"/>
		</form>

		<form action="topic.php" class="delete" method="get">
			<input name="id" type="hidden" value="{$topic.id}"/>
			<input type="submit" value="{$strings.cancel}"/>
		</form>
{elseif $action eq "move_select"}
		<p>{$strings.move_select} <q>{$topic.subject}</q>:</p>

		<form action="moderate.php" class="delete" method="post">
			<input name="action" type="hidden" value="move"/>
			<input name="topic" type="hidden" value="{$topic.id}"/>

			<p><select name="forum">
				<option value="">Please select&hellip;</option>
{foreach value = category from = $categories}
			<optgroup label="{$category.name}">
{foreach value = forum from = $category.forums}
				<option {if $forum.id eq $topic.forum}disabled="disabled" {/if}value="{$forum.id}">{$forum.name}</option>
{/foreach}
			</optgroup>
{/foreach}
			</select></p>

			<input type="submit" value="{$strings.move}"/>

		</form>

		<form action="topic.php" class="delete" method="get">
			<input name="id" type="hidden" value="{$topic.id}"/>
			<input type="submit" value="{$strings.cancel}"/>
		</form>
{/if}
	</div>

{include file = "footer.tpl"}
{else}
<form action="moderate.php" class="mod" method="post">
	<input name="action" type="hidden" value="{if $topic.sticky eq 1}un{/if}stick"/>
	<input name="topic" type="hidden" value="{$topic.id}"/>
	<input type="submit" value="{if $topic.sticky eq 1}{$strings.unstick}{else}{$strings.stick}{/if}"/>
</form>

<form action="moderate.php" class="mod" method="post">
	<input name="action" type="hidden" value="{if $topic.locked eq 1}un{/if}lock"/>
	<input name="topic" type="hidden" value="{$topic.id}"/>
	<input type="submit" value="{if $topic.locked eq 1}{$strings.unlock}{else}{$strings.lock}{/if}"/>
</form>

<form action="moderate.php" class="mod" method="post">
	<input name="action" type="hidden" value="move_select"/>
	<input name="topic" type="hidden" value="{$topic.id}"/>
	<input type="submit" value="{$strings.move}"/>
</form>

<form action="moderate.php" class="mod" method="post">
	<input name="action" type="hidden" value="delete_confirm"/>
	<input name="topic" type="hidden" value="{$topic.id}"/>
	<input type="submit" value="{$strings.delete}"/>
</form>
{/if}
