<div class="container">
	<div class="readable-width">
		<input name="commit" type="hidden" value="{$commit['url']}" />
		
		{$commit['message']}
		
		<p>{$commit['author']['name']} <small>&lt;{$commit['author']['email']}&gt;</small><br />
		{date('g:i a, l, F j, Y', strtotime($commit['author']['date']))}</p>
	</div>
</div>