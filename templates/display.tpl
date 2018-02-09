<!DOCTYPE html>
<html>
	<head>
		<title>{$title}</title>
		<style>
		@page {
		    size: letter;
		    margin: 1in;
		}

		@media print {
		    .page {
		        page-break-after: always;
		    }
		}

		@media screen {
		    .page {
		        margin: 1in;
		    }
		}

		body {
		    font-family: Helvetica, Arial, sans-serif;
		}

		small {
		    color: #ccc;
		    font-weight: normal;
		}

		.geshi ol {
			list-style-type: none;
			margin-left: 0;
		}

		.geshi ol > li {
			counter-increment: linecounter;
			text-indent: -3em;
		}

		.geshi ol > li:before {
			content: counter(linecounter) " ";
			float: left;
			color: lightgray;
			width: 3em;
		}

		.geshi ol:first-child {
			counter-reset: linecounter;
		}

		</style>
	</head>
	<body>
		<div class="page">
			{include file="commit.tpl"}
		</div>

		{foreach $files as $file}
			<div class="page">
				<h1><small>{$file['path']}/</small> {$file['filename']}</h1>

				{$file['content']}
			</div>
		{/foreach}
	</body>
</html>
