{include file = "admin_header.tpl"}
	<title>{$strings.admin}: {$strings.users} &middot; {$site_title}</title>
</head>
<body>
	<div id="holder">

	<h1>{$strings.users}</h1>

{include file = "admin_menu.tpl"}

	<div id="content">

{if isset($action)}
{if $action eq "edit"}
	<h2>{$strings.user_details}</h2>

	<form action="users.php" method="post">

	<input name="action" type="hidden" value="save"/>
	<input name="user" type="hidden" value="{$user.username}"/>

	<p><label for="username">{$strings.username}</label>
	<input id="username" maxlength="50" name="username" size="20" type="text" value="{$user.username}"/></p>

	<p><label for="title">{$strings.title}</label>
	<select name="type" {if $user.type eq 3 and $admins_count eq 1}disabled="disabled"{/if}>
		<option value="1"{if $user.type eq 1} selected="selected"{/if}>{$strings.member}</option>
		<option value="2"{if $user.type eq 2} selected="selected"{/if}>{$strings.moderator}</option>
		<option value="3"{if $user.type eq 3} selected="selected"{/if}>{$strings.administrator}</option>
	</select></p>
{if $user.type eq 3 and $admins_count eq 1}
	<input name="type" type="hidden" value="3"/>
{/if}

	<p><label for="email">{$strings.email_add}</label>
	<input id="email" maxlength="300" name="email" size="40" type="text" value="{$user.email}"/></p>

	<p><input type="submit" value="{$strings.save}"/></p>

	</form>

	<h2>{$strings.new_pw}</h2>

	<form action="users.php" method="post">

	<input name="action" type="hidden" value="password"/>
	<input name="user" type="hidden" value="{$user.username}"/>

	<p><label for="password">{$strings.password}</label>
	<input id="password" maxlength="50" name="password" size="20" type="text"/></p>

	<p><input type="submit" value="{$strings.submit}"/></p>

	</form>
{elseif $action eq "delete_confirm"}
{if $user.type eq 3 and $admins_count eq 1}
		<p>{$strings.only_admin_delete} <q>{$user.username}</q>.</p>

		<form action="users.php" class="delete" method="get"><input type="submit" value="{$strings.cancel}"/></form>
{else}
		<p>{$strings.user_delete_confirm} <q>{$user.username}</q>?</p>

		<p>{$strings.perm_warning}</p>

		<form action="users.php" class="delete" method="post">
			<input name="action" type="hidden" value="delete"/>
			<input name="user" type="hidden" value="{$user.username}"/>

			<input type="submit" value="{$strings.delete}"/>
		</form>

	<form action="users.php" class="delete" method="get"><input type="submit" value="{$strings.cancel}"/></form>
{/if}
{/if}
{elseif $users_count > 0}
		<p>{$strings.banned_shown}</p>

		<div class="category">
		<h2>Users</h2>
		<table id="users">
			<thead>
				<tr>
					<th>{$strings.username}</th>
					<th>{$strings.title}</th>
					<th>{$strings.actions}</th>
				</tr>
			</thead>
			<tbody>
{foreach value = user from = $users}
				<tr>
					<td>{if $user.disabled eq 1}<del>{/if}{$user.username}{if $user.disabled eq 1}</del>{/if}</td>
					<td>{$user.title}</td>
					<td>
					<form action="users.php" class="users" method="post">
					<input name="action" type="hidden" value="edit"/>
					<input name="user" type="hidden" value="{$user.username}"/>
					<input type="submit" value="{$strings.edit}"/></form>

					<form action="users.php" class="users" method="post">
					<input name="action" type="hidden" value="{if $user.disabled eq 1}un{/if}ban"/>
					<input name="user" type="hidden" value="{$user.username}"/>
					<input {if $user.type eq 3}disabled="disabled"{/if} type="submit" value="{if $user.disabled eq 1}{$strings.unban}{else}{$strings.ban}{/if}"/>
					</form>
					<form action="users.php" class="users" method="post">
					<input name="action" type="hidden" value="delete_confirm"/>
					<input name="user" type="hidden" value="{$user.username}"/>
					<input type="submit" value="{$strings.delete}"/>
					</form>
					</td>
				</tr>
{/foreach}
			</tbody>
		</table>
		</div>
{/if}

	</div>

{include file = "footer.tpl"}
