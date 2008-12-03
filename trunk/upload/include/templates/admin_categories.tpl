{include file = "admin_header.tpl"}
	<title>{$strings.admin}: {$strings.categories} &middot; {$site_title}</title>
</head>
<body class="cancel">
	<div id="holder">

	<h1>{$strings.categories}</h1>

{include file = "admin_menu.tpl"}

	<div id="content">

{if isset($action)}
{if $action eq "edit"}
	<form action="categories.php" class="delete" method="post">

	<input name="action" type="hidden" value="save"/>
	<input name="category" type="hidden" value="{$category.id}"/>

	<p><label for="name">{$strings.name}</label>
	<input id="name" maxlength="100" name="name" size="30" type="text" value="{$category.name}"/></p>

	<input class="button" type="submit" value="{$strings.save}"/>

	</form>
	
	<form action="categories.php" class="delete" method="get"><input class="button" type="submit" value="Cancel"/></form>
{elseif $action eq "delete_confirm"}
		<p>{$strings.category_delete_confirm1} <q>{$category.name}</q> {$strings.category_delete_confirm2}</p>

		<p>{$strings.perm_warning}</p>

		<form action="categories.php" class="delete" method="post">
			<input name="action" type="hidden" value="delete"/>
			<input name="category" type="hidden" value="{$category.id}"/>

			<input type="submit" value="{$strings.delete}"/>
		</form>

	<form action="categories.php" method="get"><input type="submit" value="{$strings.cancel}"/></form>
{/if}
{elseif $categories_count > 0}
	<div class="category">
	<h2>Categories</h2>
	<table>
		<thead>
			<tr>
				<th>{$strings.id}</th>
				<th>{$strings.name}</th>
				<th>{$strings.actions}</th>
			</tr>
		</thead>
		<tbody>
{foreach value = category from = $categories}
			<tr>
				<td>{$category.id}</td>
				<td>{$category.name}</td>
				<td><form action="categories.php" class="admin" method="post">
				<input name="action" type="hidden" value="edit"/>
				<input name="category" type="hidden" value="{$category.id}"/>
				<input type="submit" value="{$strings.edit}"/>
				</form>

				<form action="categories.php" class="admin" method="post">
				<input name="action" type="hidden" value="delete_confirm"/>
				<input name="category" type="hidden" value="{$category.id}"/>
				<input type="submit" value="{$strings.delete}"/>
				</form></td>
			</tr>
{/foreach}
		</tbody>
	</table>
	</div>
{else}
	<p>{$strings.no_categories}</p>
{/if}
{if empty($action)}
<h2>{$strings.add_category}</h2>

	<form action="categories.php" method="post">
		<input name="action" type="hidden" value="add"/>

		<p><label for="name">{$strings.name}</label>
		<input id="name" maxlength="100" name="name" size="30" type="text"/></p>

		<p><input type="submit" value="{$strings.add_category}"/></p>
	</form>
{/if}
	</div>

{include file = "footer.tpl"}
