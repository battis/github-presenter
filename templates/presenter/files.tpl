{extends file="form.tpl"}

{block name="form-content"}
	<style>
		.path {
			font-size: smaller;
		}
		
		.path.dir {
			color: gray;
		}
		
		.path.dir.project {
			color: black;
			font-size: medium;
		}
		
		.path.file {
			color: black;
			font-size: medium;
		}
	</style>

	{include file="commit.tpl"}
	
		<div class="form-group">
			<div class="checkbox">
				<label for="pdf">
					<input id="pdf" name="pdf" type="checkbox" value="true" />
					Render as a PDF file
				</label>
			</div>
		</div>
		
		<div class="form-group">
			<button type="submit" class="btn btn-primary has-spinner">Display Commit <span class="spinner"><i class="fa fa-refresh fa-spin"></i></span></button>
		</div>

	{foreach $tree as $leaf}
		<div class="form-group">
			<div class="checkbox">
				<label for="{$leaf['sha']}">
					<input id="{$leaf['sha']}" name="files[{$leaf['path']}]" type="checkbox" value="{$leaf['url']}" {if $leaf['changed']}checked="checked"{/if} />
					{assign var="first" value=true}
					{foreach $leaf['path_parts'] as $part}
						<span class="path dir {if $first}project{$first = false}{/if}">{$part} /</span>
					{/foreach}
					<span class="path file">{$leaf['filename']}</span>
				</label>
			</div>
		</div>
	{/foreach}

	{assign var="formButton" value="Display Files"}
	
{/block}

{block name="form-buttons"}{/block}