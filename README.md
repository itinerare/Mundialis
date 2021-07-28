# Mundialis
Mundialis is a Laravel-based framework for managing story, character, and world information. It aims to reduce the friction of recording these by streamlining the process of template creation, use, and information formatting.

- Demo Site:
- Support Discord: https://discord.gg/mVqUzgQXMd

### Why Mundialis?
There are certainly preexisting solutions for keeping and organizing this kind of information, both standalone and service-based. However, I have yet to see one that did not more or less boil down to some implementation of a wiki, and rightly so; wiki software is very robust, meant to handle a great breadth and potentially depth of information, depending on the specific implementation and how it's used. 

The trouble arises from this last point. I have traditionally used a [MediaWiki](https://www.mediawiki.org/wiki/MediaWiki) instance for this purpose. While it is exceedingly powerful and meets most of my needs, it has a few notable drawbacks: namely, all non-basic formatting and organization need to be set up and manually maintained per page. Wiki software will generally provide tools to help with this, but there is only so much it can do on its own. Moreover, there are certain things a general-purpose app like that is simply not built to handle efficiently-- in this context, consider timelines or handling a lexicon for a conlang-- which may be prohibitively effort and/or time-intensive to manually document, format, etc.

Thus, this project was born out of a desire for a more efficient way to record world information-- one which takes some of the advantages of a wiki framework while taking a more specialized approach, bringing some minor automation to bear so that you can spend less time formatting and organizing your information and more time creating. Moreover, since it's a self-contained app rather than a service, you can host it locally/on your own machine or on a server and manage your project's information on your own terms.

## Features
WIP
- Support for multiple users with read/write permissions handled via a lightweight rank system (user/editor/admin) (note that this is explicitly **not** designed for many people, just a small group, e.g. friends, collaborators)
- Page templates and template editing per-subject and per-category, including sub-categories, with optional selective change cascading from subject to categories and from categories to sub-categories (with optional recursive change cascading)-- that is, only new changes get applied, kinda like git but for page templates. They can be applied to all categories in a subject, all sub-categories of a category, or those and all *their* sub-categories ad infinitum. Forms for page editing are constructed from these templates, as well as pages themselves.
- Specialized functions for handling time: ability to set divisions of time (e.g. days, weeks) and set (and sort) larger portions of time (chronologies) for use as secondary categories for events/to provide more overarching order
- A specialized system for handling a project's lexicon, including configurable parts of speech and categories with an optional system for automatic conjugation/declension configurable per-category
- Pages organized by subject (people, places, things, etc) and category (including sub-categories). Includes some conveniences such as automatically generating table-of-contents based on template information and collapsible sections that load collapsed for a section if its contents are long
- Page editing built from the template system, with additional specialized fields for some subjects. Pages can also be moved or protected (set so only admins can edit)
- Wiki-like link parsing for easy/convenient linking of on-site pages within page content
- Page tagging-- either just for organization or for automated generation of navboxes-- including dedicated utility tags for keeping track of WIP, outdated, etc. pages
- Images and per-page image galleries; images can have multiple on- or off-site creators noted directly via the system, and can be associated with more than one page at once (with their validity for a given page can be tracked per-page)
- Version tracking for pages and images, including the ability to reset a page to a specific version and to restore deleted pages and images
- Various special/utility pages for keeping track of pages via maintenance reports, etc. Includes things like un-/most-tagged pages, most and least revisions, pages with a given utility tag, and wanted pages as linked to within existing pages
- A random page button (just delightful, in my opinion)

## Setup
WIP

## Contributing
Thank you for considering contributing to Mundialis! Please see the [Contribution Guide](https://github.com/itinerare/Mundialis/blob/main/CONTRIBUTING.md) for information on how best to contribute.

### Extending Mundialis
If you are interested in providing optional/plugin-type functionality for Mundialis, please contact me first and foremost; while I am open to developing plugin support and would rather do so before any are made, I will not be doing so until there is concrete interest in it.

## Credits
Beyond dependencies and contributions, this project owes its existence in part to some projects and people who provided valuable inspiration and insight.

- [Lorekeeper](https://github.com/corowne/lorekeeper), a framework for running ARPG sites. Though this has been altogether rebuilt to suit a different purpose, much of Lorekeeper's blood-- and certainly many of the lessons I have learned while contributing to the project-- can still be seen in Mundialis.
- [preimpression](https://github.com/preimpression), in particular for the excellent [World Expansion](http://wiki.lorekeeper.me/index.php?title=Extensions:World_Expansion) extension for Lorekeeper, which brings a sort of wiki-lite functionality to it and which helped inspire Mundialis.
- [ne-wt](https://github.com/ne-wt), whose work with the above as well as addition of a dictionary inspired me to build a more automated system for handling lexicons within the context of a Laravel app.
- [PolyGlot](https://github.com/DraqueT/PolyGlot), a great and very robust project for handling language construction and lexicons in-depth, which inspired Mundialis' lexicon system in part-- in particular, the automatic conjugation/declension system.

## Contact
If you have any questions, please contact me via email at [queries@itinerare.net](emailto:queries@itinerare.net).
