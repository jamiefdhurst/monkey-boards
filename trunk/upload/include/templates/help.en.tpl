	<title>Help &middot; {$site_title}</title>
</head>
<body>
	<div id="holder">

	<h1>Help</h1>

{include file = "menu.tpl"}

	<div id="content">

		<h2 id="auto-link">Automatic linking</h2>

		<p>When you enter a web address, such as <a href="http://google.com/">http://google.com/</a>, or email address, such as <a href="mailto:john@smith.com">john@smith.com</a>, it will be automatically converted into a clickable link.</p>

		<p><strong>Please note:</strong> web addresses will only be clickable if they have <q>http://</q> at the beginning.</p>

		<h2 id="html-support">HTML support</h2>

		<p>Certain HTML tags are allowed to be included in your posts.</p>

		<table id="htmltags">
			<thead>
				<tr>
					<th>Tag</th>
					<th>Example</th>
					<th>Shows as</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><code>&lt;em&gt;</code></td>
					<td>This is <code>&lt;em&gt;</code>emphasis<code>&lt;/em&gt;</code>.</td>
					<td>This is <em>emphasis</em>.</td>
				</tr>
				<tr>
					<td><code>&lt;strong&gt;</code></td>
					<td>This is <code>&lt;strong&gt;</code>strong emphasis<code>&lt;/strong&gt;</code>.</td>
					<td>This is <strong>strong emphasis</strong>.</td>
				</tr>
				<tr>
					<td><code>&lt;abbr&gt;</code></td>
					<td><code>&lt;abbr title="HyperText Markup Language"&gt;</code>HTML<code>&lt;/abbr&gt;</code></td>
					<td><abbr title="HyperText Markup Language">HTML</abbr></td>
				</tr>
				<tr>
					<td><code>&lt;acronym&gt;</code></td>
					<td><code>&lt;acronym title="CSS"&gt;</code>Cascading Style Sheets<code>&lt;/acronym&gt;</code></td>
					<td><acronym title="Cascading Style Sheets">CSS</acronym></td>
				</tr>
				<tr>
					<td><code>&lt;blockquote&gt;</code></td>
					<td><code>&lt;blockquote cite="http://jamesgreenwood.me.uk/blockquote.php"&gt;</code><br/>A large quotation. If you want to, use the cite attribute to specify the web address of where you're quoting from.<code>&lt;/blockquote&gt;</code></td>
					<td><blockquote cite="http://jamesgreenwood.me.uk/blockquote.php">A large quotation. If you want to, use the cite attribute to specify the web address of where you're quoting from.</blockquote></td>
				</tr>
				<tr>
					<td><code>&lt;cite&gt;</code></td>
					<td>The monkey called <code>&lt;cite&gt;</code>Jeremy<code>&lt;/cite&gt;</code> disappeared.</td>
					<td>The monkey called <cite>Jeremy</cite> disappeared.</td>
				</tr>
				<tr>
					<td><code>&lt;code&gt;</code></td>
					<td><code>&lt;code&gt;</code>&amp;lt;strong&amp;gt;How to show code&amp;lt;/strong&amp;gt;<code>&lt;/code&gt;</code></td>
					<td><code>&lt;strong&gt;How to show code&lt;/strong&gt;</code></td>
				</tr>
				<tr>
					<td><code>&lt;del&gt;</code></td>
					<td>It was<code>&lt;del&gt;</code>n't<code>&lt;/del&gt;</code> funny&hellip;</td>
					<td>It was<del>n't</del> funny&hellip;</td>
				</tr>
				<tr>
					<td><code>&lt;ins&gt;</code></td>
					<td>It was really <code>&lt;ins&gt;</code>very<code>&lt;/ins&gt;</code> good.</td>
					<td>It was really <ins>very</ins> good.</td>
				</tr>
				<tr>
					<td><code>&lt;dfn&gt;</code></td>
					<td>You are using <code>&lt;dfn title="discussion boards"&gt;</code>Monkey Boards<code>&lt;/dfn&gt;</code>.</td>
					<td>You are using <dfn title="discussion boards">Monkey Boards</dfn>.</td>
				</tr>
				<tr>
					<td><code>&lt;kbd&gt;</code></td>
					<td>Type <code>&lt;kbd&gt;</code>www.google.com<code>&lt;/kbd&gt;</code> in your browser.</td>
					<td>Type <kbd>www.google.com</kbd> in your browser.</td>
				</tr>
				<tr>
					<td><code>&lt;samp&gt;</code></td>
					<td>The program will then output <code>&lt;samp&gt;</code>Access Denied<code>&lt;/samp&gt;</code>.</td>
					<td>The program will then output <samp>Access Denied</samp>.</td>
				</tr>
				<tr>
					<td><code>&lt;var&gt;</code></td>
					<td>Then set the <code>&lt;var&gt;</code>installed<code>&lt;/var&gt;</code> variable to 1.</td>
					<td>Then set the <var>installed</var> variable to 1.</td>
				</tr>
			</tbody>
		</table>

		<p>For more in-depth information on how to use these tags correctly, please see <a href="http://htmldog.com/reference/htmltags/">HTML Dog's reference page</a>.</p>

		<h2 id="smilies">Smilies</h2>

		<p>You can enter special codes in your post which will be converted to the relevant smiley below:</p>

		<table id="smilies">
			<thead>
				<tr>
					<th></th>
					<th>Name</th>
					<th>Code</th>
				</tr>
			</thead>
			<tbody>
{foreach value = smiley from = $smilies}
				<tr>
					<td><img alt="{$smiley.title}" height="18" src="{$smiley.image}" width="18"/></td>
					<td>{$smiley.title}</td>
					<td>{$smiley.pattern}</td>
				</tr>
{/foreach}
			</tbody>
		</table>
		<p><strong>Note:</strong> You can leave out the <q>nose</q> part and the smiley will still work.</p>
	</div>
