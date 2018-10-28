{extends file="form.tpl"}

{block name="form-content"}

	<div class="form-group">
		<label for="commit_url" class="control-label col-sm-{$formLabelWidth}">Commit URL</label>
		<div class="col-sm-{12 - $formLabelWidth}">
			<input id="commit_url" name="commit_url" type="text" class="form-control" placeholder="https://github.com/smcs/adv-topics-demos/commit/746c47de51b2813032910da39ddece8c1aec8b99" />
			<p class="help-block">Paste the URL of your commit from the GitHub history page</p>
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-offset-{$formLabelWidth}">
			<div class="checkbox">
				<label for="ignore_invisible">
					<input id="ignore_invisible" name="ignore_invisible" type="checkbox" value="true" checked="checked" />
					Ignore &ldquo;invisible&rdquo; <code>.dot_files</code>
				</label>
			</div>
		</div>
	</div>

	<div class="form-group">
		<label for="regex_filter" class="control-label col-sm-{$formLabelWidth}">Filter RegEx</label>
		<div class="col-sm-{12 - 2 * $formLabelWidth}">
			<input id="regex_filter" name="regex_filter" type="text" class="form-control" placeholder=".*\.java" value=".*\.(java|swift|storyboard|php|css|html?|js)$" />
		</div>
		<div class="checkbox">
			<label for="enable_filter">
				<input id="enable_filter" name="enable_filter" type="checkbox" value="true" checked="checked" />
				Enable filename filtering
			</label>
		</div>
	</div>
{/block}

{block name="form-buttons"}
        
	<div class="form-group">
                <div class="col-sm-offset-{$formLabelWidth} col-sm-{12 - $formLabelWidth}">
                    <button type="submit" class="btn btn-primary has-spinner">{$formButton|default: "Choose Files"}</button>
                </div>    
            </div>
            
        {/block}

