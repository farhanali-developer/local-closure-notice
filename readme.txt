=== Local Closure Notice ===
Contributors: farhanalidev
Donate link: https://farhanali.me/
Tags: closure, banner, popup, holiday hours, local business
Requires at least: 5.8
Tested up to: 7.1
Requires PHP: 7.2
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Announce a holiday closure, weather closure, or "closed today" with a one-click banner or popup — no page editing required. Auto-expires on a set date.

== Description ==

**Local Closure Notice** is built for salons, clinics, restaurants, and other local businesses that need to tell visitors "we're closed" without touching a page template.

Turn it on, write your message, optionally set an end date, and the notice takes care of itself — including quietly turning itself off once the closure is over.

**Core Features:**

- Banner (top or bottom bar) or popup (centered modal) display styles
- Custom title, message, background color, and text color
- Optional "display from" and "auto-expire on" scheduling — set it once, forget it
- Dismissible option so visitors can close it (remembered in their browser)
- No page editing, no shortcodes, no template changes
- Zero external dependencies, no tracking, no bloat

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/local-closure-notice` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress.
3. Go to Settings > Closure Notice to write your message and turn it on.

== Frequently Asked Questions ==

= Do I need to remember to turn the notice off? =

No — set an "Auto-expire on" date and time and the notice hides itself automatically once that passes.

= Can I schedule a closure in advance? =

Yes. Set "Display from" to a future date/time and the notice won't appear until then, even if it's already enabled.

= Will it slow down my site? =

No. The plugin only loads its (tiny) CSS/JS when the notice is actually active, and stores nothing in the database besides your settings.

= Does it work with any theme? =

Yes. The banner and popup are rendered independently of your theme's templates, in the site footer.

== Changelog ==

= 1.0.0 =
* Initial release.
