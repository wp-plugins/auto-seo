=== Auto SEO ===
Contributors: Phillip.Gooch
Tags: pages, seo, meta-tags, admin
Requires at least: 3.2
Tested up to: 3.4
Stable tag: 1.3.2
License: GNU General Public License v2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

AutoSEO is the fastest, simplest way to add page titles, meta tags, and meta descriptions to an entire site at once.

== Description ==

AutoSEO is a simple way to add page titles, meta tags, and meta descriptions to an entire site at once. Instead of manually
going to each page and tagging them seperatly, AutoSEO provides you with one page to fill out that will genreate meta tags
on each of your pages based on your input.

Why AutoSEO

 + One page to do it all, much faster than manually submitting filling meta data on a page by page basis.
 + Designed for geolocated searches, get titles and descriptions for a specific city or multiple cities, or - or replace the city list with anything else for more general tagging.
 + Customizability, you pick the locations, keywords, and title slugs, as well as the divider and it does the rest.
 + Works with nearly all themes, even older themes and themes missing headers.
 + Did I mention how much quicker it is? Super-quick!

_Note: AutoSEO works by replacing content in the html head section and is, in all practicality, not compatible with other SEO plugins._

== Installation ==

1. Upload the `auto-seo` directory to your `/wp-content/plugins/` directory.

== Frequently Asked Questions ==

= Why aren't my tags showing up? =

You'll need at least 2 entries in each section.

= Is there a way to have the homepage use a specific location and set of slugs? =

Yes, the homepage (as defined in the Settings > Reading section) will _always_ use the first item from each section.

== Screenshots ==

1. The super fast settings page.

== Changelog ==

#### 1.0.0
 + Initial Release
#### 1.1.1
 + Gave the back end a custom icon.
 + Changed when the output buffer was cahced for better compatibility to old/bad themes (I'm looking at you fast and quick).
 + Fixed a rare bug where the title would not display - _Allegedly_
#### 1.2.1
 + Homepage now always uses first item from each list.
#### 1.2.2
 + Fixed a bug where a line of code would occasionally show at top of page.
#### 1.3.2
 + Changed Licensing for Worpdres Extend Submission.
 + Removed changelog from main file and placed it into readme.