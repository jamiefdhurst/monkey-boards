	<ul id="menu">{foreach value = menu from = $main_menu}{if $menu.display eq 0}

		<li><a href="../{$menu.link}">{$menu.item}</a></li>{elseif $menu.display eq 1}{if $logged_in eq "true"}

		<li>{if $current_page neq $menu.link}<a href="../{$menu.link}">{/if}{$menu.item}{if $current_page neq $menu.link}</a>{/if}</li>{/if}{elseif $menu.display eq 2}{if $logged_in neq "true"}

		<li>{if $current_page neq $menu.link}<a href="../{$menu.link}">{/if}{$menu.item}{if $current_page neq $menu.link}</a>{/if}</li>{/if}{elseif $menu.display eq 3}{if $logged_in eq "true" and $user_type eq 3}

		<li>{$menu.item}</li>{/if}{/if}{/foreach}

	</ul>

	<ul id="menu-admin">
{foreach value = menu from = $admin_menu}
		<li>{if $current_page neq $menu.link}<a href="{$menu.link}">{/if}{$menu.item}{if $current_page neq $menu.link}</a>{/if}</li>
{/foreach}
	</ul>
