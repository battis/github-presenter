<html>
	<head>
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