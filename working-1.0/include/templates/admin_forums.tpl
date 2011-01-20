{include file = "admin_header.tpl"}
	<title>{$strings.admin}: {$strings.forums} &middot; 
{$site_title}</title>
</head>
<body class="cancel">
	<div id="holder">

	<h1>{$strings.forums}</h1>

{include file = "admin_menu.tpl"}

	<div id="content">

{if isset($action)}
{if $action eq "edit"}
	<form action="forums.php" class="delete" method="post">

	<input name="action" type="hidden" value="save"/>
	<input name="forum" type="hidden" value="{$forum.id}"/>

	<p><label for="id">{$strings.id}</label>
	<input id="id" maxlength="50" name="id" size="20" type="text" value="{$forum.id}"/></p>

	<p><label for="category">{$strings.category}</label>
	<select id="category" name="category">
{foreach value = category from = $categories}
		<option value="{$category.id}">{$category.name}</option>
{/foreach}
	</select></p>

	<p><label for="name">{$strings.name}</label>
	<input id="name" maxlength="100" name="name" size="30" type="text" value="{$forum.name}"/></p>

	<p><label for="description">{$strings.desc}</label>
	<input id="description" maxlength="100" name="description" size="40" type="text" value="{$forum.blurb}"/></p>

	<input class="button" type="submit" value="{$strings.save}"/>

	</form>
	
	<form action="forums.php" class="delete" method="get"><input class="button" type="submit" value="Cancel"/></form>
{elseif $action eq "delete_confirm"}
		<p>{$strings.forum_delete_confirm1} <q>{$forum.name}</q> {$strings.forum_delete_confirm2}</p>

		<p>{$strings.perm_warning}</p>

		<form action="forums.php" class="delete" method="post">
			<input name="action" type="hidden" value="delete"/>
			<input name="forum" type="hidden" value="{$forum.id}"/>

			<input type="submit" value="{$strings.delete}"/>
		</form>

	<form action="forums.php" class="delete" method="get"><input 
type="submit" value="{$strings.cancel}"/></form>
{/if}
{elseif $categories_count > 0}
{foreach value = category from = $categories}

{if count($category.forums) gt 0}
	<div class="category">
	<h2>{$category.name}</h2>
	<table>
		<thead>
			<tr>
				<th>{$strings.id}</th>
				<th>{$strings.name}</th>
				<th>{$strings.desc}</th>
				<th>{$strings.actions}</th>
			</tr>
		</thead>
		<tbody>
{foreach value = forum from = $category.forums}
			<tr>
				<td>{$forum.id}</td>
				<td>{$forum.name}</td>
				<td>{$forum.blurb}</td>
				<td><form action="forums.php" class="admin" method="post">
				<input name="action" type="hidden" value="edit"/>
				<input name="forum" type="hidden" value="{$forum.id}"/>
				<input type="submit" value="{$strings.edit}"/>
				</form>

				<form action="forums.php" class="admin" method="post">
				<input name="action" type="hidden" value="delete_confirm"/>
				<input name="forum" type="hidden" value="{$forum.id}"/>
				<input type="submit" value="{$strings.delete}"/>
				</form></td>
			</tr>
{/foreach}
		</tbody>
	</table>
	</div>
{else}
	<p>{$strings.no_forums}</p>
{/if}
{/foreach}
{else}
	<p>{$strings.no_forums}</p>
{/if}
{if empty($action) and $categories_count neq 0}
<h2>{$strings.add_forum}</h2>

	<form action="forums.php" method="post">
		<input name="action" type="hidden" value="add"/>

		<p><label for="category">{$strings.category}</label>
		<select id="category" name="category">
{foreach value = category from = $categories}
			<option value="{$category.id}">{$category.name}</option>
{/foreach}
		</select></p>

		<p><label for="name">{$strings.name}</label>
		<input id="name" maxlength="100" name="name" size="30" type="text"/></p>

		<p><label for="description">{$strings.desc}</label>
		<input id="description" maxlength="100" name="description" size="40" type="text"/></p>

		<p><input type="submit" value="{$strings.add_forum}"/></p>
	</form>
{/if}
	</div>

{include file = "footer.tpl"}
