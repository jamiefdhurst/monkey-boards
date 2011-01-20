{include file = "header.tpl"}
	<title>{$strings.search} &middot; {$site_title}</title>
</head>
<body>
	<div id="holder">

	<h1>{$strings.search}</h1>

{include file = "menu.tpl"}

	<div id="content">

<form action="search.php" id="search" method="get">
	<fieldset>
		<legend>{$strings.enter_search}</legend>

		<p><label for="keywords">{$strings.keyword_search}</label>
		<input id="keywords" maxlength="128" name="keywords" size="25" type="text"{if isset($keywords)}value="{$keywords}"{/if}/></p>

		<p><label for="author">{$strings.author_search}</label>
		<input id="author" maxlength="128" name="author" size="15" type="text"{if isset($author)}value="{$author}"{/if}/></p>
	</fieldset>

	<fieldset>
		<legend>{$strings.search_location}</legend>

		<p><label for="forum">{$strings.forum}</label>
		<select id="forum" name="forum">
				<option value="">{$strings.all_forums}</option>
{foreach value = category from = $categories}
			<optgroup label="{$category.name}">
{foreach value = forum from = $category.forums}
				<option {if isset($selected_forum) and $selected_forum eq $forum.id}selected="selected" {/if}value="{$forum.id}">{$forum.name}</option>
{/foreach}
			</optgroup>
{/foreach}
			</select></p>

		<p><label for="where">{$strings.search_scope}</label>
		<select id="where" name="where">
			<option {if isset($where) and $where eq 1}selected="selected" {/if}value="1">{$strings.scope_both}</option>
			<option {if isset($where) and $where eq 2}selected="selected" {/if}value="2">{$strings.scope_text}</option>
			<option {if isset($where) and $where eq 3}selected="selected" {/if}value="3">{$strings.scope_subject}</option>
		</select></p>
	</fieldset>

	<p><input type="submit" value="{$strings.search}"/></p>
</form>

{if isset($results)}
	<div class="category">
	<h2>Search results</h2>
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
{foreach value = result from = $results}
			<tr>
				<td class="topic"><a href="topic.php?id={$result.id}">{$result.subject}</a> {$strings.by} {if $logged_in 
eq "true"}<a href="user.php?u={$result.author}">{/if}{$result.author}{if $logged_in eq "true"}</a>{/if}</td>
				<td>{$result.replies}</td>
				<td>{$result.views}</td>
				<td>{$result.last_post}</td>
			</tr>
{/foreach}
		</tbody>
	</table>
	</div>
{/if}

</div>

{include file = "footer.tpl"}
