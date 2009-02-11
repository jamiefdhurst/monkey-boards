<ul id="menu">{foreach value = menu from = $main_menu}{if $menu.display eq 0}

	<li>{if $current_page neq $menu.link}<a href="{$menu.link}">{/if}{$menu.item}{if $current_page neq $menu.link}</a>{/if}</li>{elseif $menu.display eq 1}{if $logged_in eq "true"}

	<li>{if $current_page neq $menu.link}<a href="{$menu.link}">{/if}{$menu.item}{if $current_page neq $menu.link}</a>{/if}</li>{/if}{elseif $menu.display eq 2}{if $logged_in neq "true"}

	<li>{if $current_page neq $menu.link}<a href="{$menu.link}">{/if}{$menu.item}{if $current_page neq $menu.link}</a>{/if}</li>{/if}{elseif $menu.display eq 3}{if $logged_in eq "true" and $user_type eq 3}

	<li>{if $current_page neq $menu.link}<a href="{$menu.link}">{/if}{$menu.item}{if $current_page neq $menu.link}</a>{/if}</li>{/if}{/if}{/foreach}

</ul>
