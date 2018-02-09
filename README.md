# GitHub Presenter

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/battis/github-presenter/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/battis/github-presenter/?branch=master)

Present a GitHub repo (or a selection of files from a repo) referenced by a particular commit. By default, this will select all the files that share a root-level subdirectory with files that were changed in the commit referenced (but this is user-modifiable when the script runs -- add additional files or remove pre-selected files).

[And example of the output is included in the repository.](https://github.com/battis/github-presenter/blob/master/doc/example.pdf)

### Install

  1. Clone the repository
  2. [`composer install -o --prefer-dist`](https://getcomposer.org)
  3. Acquire a [GitHub personal access token](https://github.com/settings/tokens) _read:org, read:user, repo_
  4. Edit `secrets.xml` to include the token
  5. Install [wkhtmltopdf](https://wkhtmltopdf.org/downloads.html)
  6. Edit `secrets.xml` to include path to `wkhtmltopdf` executable
