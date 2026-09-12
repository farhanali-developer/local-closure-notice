# Local Closure Notice

A lightweight WordPress plugin that gives local businesses a one-click banner or popup to announce holiday closures, weather closures, or "closed today" — without editing a single page.

Built for salons, clinics, restaurants, and other local businesses where the person managing the website isn't necessarily comfortable editing pages, and the closure is often decided the same morning.

## Features

- **Banner or popup** — a bar pinned to the top/bottom of the site, or a centered modal shown on page load
- **Custom title, message, and colors** — background and text color pickers, no CSS required
- **Auto-expire** — set an end date/time and the notice hides itself automatically, no need to remember to turn it off
- **Schedule in advance** — optionally set a "display from" date so a closure can be queued up before it starts
- **Dismissible** — an optional close button; the dismissal is remembered in the visitor's browser (localStorage) so it doesn't reappear on every page view
- Zero external dependencies, no tracking, no bloat — nothing in the database beyond the plugin's own settings

## Requirements

- WordPress 5.8+
- PHP 7.2+

## Installation

### From WordPress.org

Search for "Local Closure Notice" in **Plugins → Add New**, install, and activate.

### Manual / from source

```bash
git clone https://github.com/farhanali-developer/local-closure-notice.git
```

Copy (or symlink) the `local-closure-notice` folder into `wp-content/plugins/`, then activate it from **Plugins → Installed Plugins**.

## Usage

After activating, go to **Settings → Closure Notice**:

1. Write a title and message (e.g. "We're Closed" / "Closed today for the Labor Day holiday — back tomorrow at 9am.")
2. Choose **Banner** or **Popup**, and pick colors to match your brand
3. Optionally set **Display from** and **Auto-expire on** to schedule the closure
4. Check **Show notice** and save

The notice appears on the front end immediately (or at the scheduled start time), and disappears on its own once the end date passes — no follow-up visit to the settings page required.

## License

GPLv2 or later — see [LICENSE.txt](LICENSE.txt) or https://www.gnu.org/licenses/gpl-2.0.html
