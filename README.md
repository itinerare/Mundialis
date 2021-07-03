# Mundialis
Mundialis is a Laravel-based framework for managing story, character, and world information. It aims to reduce the friction of recording these by streamlining the process of template creation, use, and information formatting.

- Demo Site:
- Support Discord: https://discord.gg/mVqUzgQXMd

### Why Mundialis?
There are certainly preexisting solutions for keeping and organizing this kind of information, both standalone and service-based. However, I have yet to see one that did not more or less boil down to some implementation of a wiki, and rightly so; wiki software is very robust, meant to handle a great breadth and potentially depth of information, depending on the specific implementation and how it's used. 

The trouble arises from this last point. I have traditionally used a [MediaWiki](https://www.mediawiki.org/wiki/MediaWiki) instance for this purpose. While it is exceedingly powerful and meets most of my needs, it has a few notable drawbacks: namely, all non-basic formatting and organization need to be set up and manually maintained per page. Wiki software will generally provide tools to help with this, but there is only so much it can do on its own. Moreover, there are certain things a general-purpose app like that is simply not built to handle efficiently-- in this context, consider timelines or handling a lexicon for a conlang-- which may be prohibitively effort and/or time-intensive to manually document, format, etc.

Thus, this project was born out of a desire for a more efficient way to record world information-- one which takes some of the advantages of a wiki framework while taking a more specialized approach, bringing some minor automation to bear so that you can spend less time formatting and organizing your information and more time creating. Moreover, since it's a self-contained app rather than a service, you can host it locally/on your own machine or on a server and manage your project's information on your own terms.

## Features
TBA
- Support for multiple users with read and optional write permissions (note that this is explicitly **not** designed for many people, just a small group, e.g. friends.)

## Setup
TBA

## Contributing
Thank you for considering contributing to Mundialis! Please see the [Contribution Guide]() for information on how best to contribute.

### Extending Mundialis
If you are interested in providing optional/plugin-type functionality for Mundialis, please contact me first and foremost; while I am open to developing plugin support and would rather do so before any are made, I will not be doing so until there is concrete interest in it.

## Credits
Beyond dependencies and contributions, this project owes its existence in part to some projects and people who provided valuable inspiration and insight.

- [Lorekeeper](https://github.com/corowne/lorekeeper), a framework for running ARPG sites. Much of its blood-- and certainly many of the lessons I have learned while contributing to the project-- are still in Mundialis.
- @preimpression's excellent [World Expansion](http://wiki.lorekeeper.me/index.php?title=Extensions:World_Expansion) extension for Lorekeeper, which brings a sort of wiki-lite functionality to it and which helped inspire Mundialis by demonstrating the efficacy of automating this kind of documentation somewhat.

## Contact
If you have any questions, please contact me via email at [queries@itinerare.net](emailto:queries@itinerare.net).
