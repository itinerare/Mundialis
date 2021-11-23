# Mundialis Contribution Guide

For support and general questions and discussions, please visit the [support Discord server](https://discord.gg/mVqUzgQXMd)! Please do not use the issue tracker for general support questions. Please also remember that unless explicitly stated otherwise/you have hired services to that end, all support is free and offered on a volunteer basis, and there is no guarantee or obligation upon maintainers and/or community members to provide support.

The following are accepted uses for the [issue tracker](https://github.com/itinerare/Mundialis/issues):
- Bug reports
- Feature or enhancement requests (within reason)-- note that these may be denied if they are deemed out of scope of the project and/or are not feasible to implement for any reason.

## Opening an Issue
### Reporting a bug

File bugs in the [issue tracker](https://github.com/itinerare/Mundialis/issues). Please follow these guidelines:

- Search existing issues first! Make sure your issue hasn't already been reported.
- Stay on topic, but describe the issue in detail so that others can reproduce it.
- Provide screenshot(s) if possible.

### Feature requests

It's recommended to discuss potential new features in the [support Discord](https://discord.gg/mVqUzgQXMd) before creating an issue, as this helps check that it is valid for a feature request and if it would be useful to others-- something which increases its likelihood of being implemented. Please also check that your request has not already been posted on the [issue tracker](https://github.com/itinerare/Mundialis/issues).

Avoid listing multiple requests in one issue. One issue per request makes it easier to track and discuss it. If need be, you may make multiple issues (within reason), but do not spam. Do not make multiple issues for the same request.

## Contributing Code

If you want to start contributing to the project's code, please follow these guidelines before creating a pull request: 

- Bugfixes are always welcome. Start by reviewing the [list of bugs](https://github.com/itinerare/Mundialis/issues?q=is%3Aissue+is%3Aopen+label%3Abug)!
- A good way to easily start contributing is to pick and work on a [good first issue](https://github.com/itinerare/Mundialis/issues?q=is%3Aissue+is%3Aopen+label%3A%22good+first+issue%22). We try to make these issues as clear as possible and provide basic info on how the code should be changed, and if something is unclear feel free to ask for more information on the issue.
- Before adding a new feature, ask about it in the [issue tracker](https://github.com/itinerare/Mundialis/issues) or the [support Discord](https://discord.gg/mVqUzgQXMd), or check if there is an existing issue to make sure the new functionality is desired. 
- **Changes that will consist of more than 50 lines of code should be discussed the [support Discord](https://discord.gg/mVqUzgQXMd)**, so that you don't spend too much time implementing something that might not be accepted.
- Pull requests that make many changes using an automated tool, like for spell fixing, styling, etc. will not be accepted. An exception would be if the changes have been discussed in the forum and someone has agreed to review **and test** the pull request.
- It's recommended to run tests for at minimum the general area(s) you are modifying! While all tests are run by default on creation of a pull request, running tests can help catch issues. Note that if your changes require tests to be added and/or updated, you **must** do so. For more information on working with tests, see [here](https://github.com/itinerare/Mundialis/wiki/Working-With-Tests).
- Be sure to include any instructions, such as running `php artisan migrate`, in your pull request!
- If in doubt, provide more information, not less. It can sometimes be difficult to tell from code alone what the purpose of a change is, so you should explain as best you can.

### General Architecture

Generally speaking, Mundialis and its branches are structured and maintained in keeping with [GitFlow](https://datasift.github.io/gitflow/IntroducingGitFlow.html). Loosely, this means that there are two persistent branches:

- The default/primary branch-- main, in this case-- which always contains the current release/the current stable version, and
- The development branch-- develop, in this case-- which always contains the latest work-- features, fixes, and so on. It is by definition unstable.

There are additionally three types of impermanent branches. Each branch of these types is destined to be merged into another.

- **Feature Branches**
    - These follow the naming scheme `feature/FEATURE-NAME-HERE`
    - While these are called feature branches, they can contain new features and/or non-emergency bugfixes!
    - New features are always merged into develop and develop only.
    - These branches are created branched off of develop (if they contain features or there is no current release branch) or the current release branch (if there is one, and the feature branch contains only bugfixes for the current content within it).
- **Release Branches**
    - These follow the naming scheme `release/UPCOMING-VERSION-HERE`.
    - These are created by a maintainer of the repo when an upcoming release is feature-complete or otherwise ready, and are branched off of develop.
    - Once they are created, no additional features or work from develop will be merged into these branches, only bugfixes for the features already in them.
    - Conversely, bugfixes for features contained in a release branch are merged directly into the release branch and the release branch only. In the event that a release branch receives bugfixes, said release branch is periodically merged back into develop to incorporate them.
    - In essence, the purpose of a release branch is to isolate, test, and if necessary fix a static set of features.
    - When a release branch is sufficiently stable and any necessary preparations complete, the branch will be merged into master and become the new current release.
- **Hotfix branches**
    - These follow the naming scheme `hotfix/HOTFIX-NAME-HERE`.
    - These contain emergency bugfixes, e.g. fixes for critical issues or security vulnerabilities.
    - These are directly branched off of main/the current release and merged back into master when complete.
    - In such an event, main is merged back into develop as well to incorporate fixes.

In summary:

- When contributing to Mundialis, you will create either a feature or (more rarely) a hotfix branch.
- If you are creating a new feature, it must be isolated in its own feature branch and a PRed into develop.

If you are fixing bug(s), there are a few possibilities depending on the circumstances:

- If there is currently a release branch, make a feature branch off of the release branch or check out the release branch itself.
    - Once you have done so, fix bug(s) with the current content of the release branch, then push the branch to your fork and make a PR to the release branch.
    - **Prioritize fixing bugs in the release branch first and foremost.** This allows the branch to better achieve stability and move toward full release.
- If there is not currently a release branch, or if your fix(es) concern content that is only present in develop, make a feature branch off of develop or check out develop itself.
    - Alternately put: **do not PR bugfixes that apply to an extant release branch to develop**. Isolate them from any changes relevant only to develop on a branch created from the release branch and PR them to the release branch. This helps the release branch achieve stability and move toward full release.
- As a general rule, making feature branches for bugfixes vs. checking out develop or the release branch and committing them directly is recommended, but not required, as you will need to push the relevant branch to your fork and make a PR regardless.
- If you are making a hotfix for a critical issue present **in the current release**, create a new hotfix branch off of main, make the necessary changes, and PR it back into main.

### Commit Formatting
Commits to this project follow [Conventional Commits](https://www.conventionalcommits.org/en/v1.0.0/) formatting. Contributors are *highly recommended* to follow this formatting when making pull requests (it also makes it very easy to make pull requests!). It is also recommended to focus on atomic commits (commit each fix or task as a separate change, and only commit when a block of work is complete)-- this makes it much easier both to review and manage changes.

### About abandoned pull requests

In the case where a pull request is started but not finished and the contributor is nonresponsive despite efforts to contact them, the pull request will be closed regardless of its status. It is up to contributors to finish work, make any requested changes, etc., not maintainers.

However, knowledge from the issue and/or pull request may be used to create a new pull request, potentially based on the changes from the closed pull request.
