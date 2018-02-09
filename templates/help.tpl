{extends file="subpage.tpl"}
{block name="post-bootstrap-stylesheets"}
	<style>
		.img-responsive {
			padding: 1em;
		}
	</style>
{/block}

{block name="subcontent"}

	<div class="container">
		<div class="readable-width">
			<p>First off, let's be absolutely clear about one thing <a href="https://skunkworks.stmarksschool.org/github-presenter">GitHub Presenter</a> is a work in progress and it's a hacked-together bit of coding. Which means that, if you have either a) ideas for cool improvements or b) bugs that you have discovered, I would really, really appreciate it if you would let me know, ideally <a href="https://github.com/battis/github-presenter/issues">using the Issues forum at its GitHub repository</a>.</p>
			<p>Here's the general gist of how this thing works:</p>
			<ol>
				<li>On GitHub, look at the commit history of your repository.<br /><img class="img-responsive" src="images/commit-history.png" /></li>
				<li>Choose the commit that represents the moment in time that you are turning in as your submission for a particular assignment. You can either just copy the link to the commit&hellip;<br /><img class="img-responsive" src="images/copy-link-address.png" /><br />&hellip;or you can visit the commit page and copy its URL there.<br /><img class="img-responsive" src="images/location-bar.png" /></li>
				<li>Bop over to <a href="https://skunkworks.stmarksschool.org/github-presenter">GitHub Presenter</a> and paste the commit URL into the obvious place.<br /><img class="img-responsive" src="images/paste-url.png" /></li>
				<li>Leave all the settings as they are and click choose files.</li>
				<li>You'll see a list of all the Java source code in your repository with, optimally, the source code for the project that you are turning in already checked off. At this point, if you need to turn in additional files (or remove extraneous files), go ahead and check/uncheck boxes. Click the Display Commit button. (Don't use the "Render as PDF" option yet -- it doesn't work yet.)<br /><img class="img-responsive" src="images/select-files.png" /><br /><br /></li>
				<li>Now you'll see your source code all pretty on the web page! <a href="http://helpdesk.stmarksschool.org/blog/how-do-i-make-a-pdf/">Print the web page as a PDF</a>.<br /><img class="img-responsive" src="images/print-pdf.png" /></li>
				<li>Submit the PDF file via Canvas.</li>
			</ol>
			<p>It sounds exhausting, but it should actually just be a few quick clicks.</p>
		</div>
	</div>

{/block}
