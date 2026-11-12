=== Dear Timeline (Best Vertical & Horizontal Timeline) ===
Contributors: ankit07721
Tags: timeline, vertical timeline, horizontal timeline, history, roadmap, responsive
Requires at least: 5.6
Tested up to: 7.1
Requires PHP: 7.4
Stable tag: 1.4.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A premium, responsive timeline plugin for WordPress with **three layout modes**: Horizontal (scroll-snap), Classic (premium zigzag), and Flat (modern cards). Text-first horizontal cards with bottom-anchored images, a centralized visual editor with live preview, and inline add/edit/delete directly from the timeline.

== Description ==

DearTimeline lets you create beautiful, responsive timelines with zero bloat. Unlike other plugins that ship heavy JS slider libraries (Slick.js, Owl Carousel), DearTimeline uses pure CSS techniques — **CSS Scroll Snap** for the horizontal layout and **CSS Flexbox/Grid** for vertical layouts — keeping your pages lightning-fast.

### Layouts

*   **Horizontal** `[deartimeline layout="horizontal"]` — a scroll-snap-powered horizontal track with premium text-first cards: date badge and title on top, description, and a full-bleed image anchored at the bottom of every card. Includes color-coded milestone dots on a continuous spine, scroll progress bar, arrow navigation, keyboard arrows, and drag-to-scroll.
*   **Classic** `[deartimeline layout="classic"]` — premium vertical zigzag with refined calendar badges, colored accent bars, a gradient spine, hover glow dots, and animated entrance effects.
*   **Flat** `[deartimeline layout="flat"]` — modern side-by-side image and text cards alternating around a thin vertical spine with scroll-reveal animations.

### Premium Admin Experience

DearTimeline features a completely redesigned admin interface that lets you manage events directly from a live preview:

*   **Live Preview First** — the "Live Desktop Preview" metabox sits at the top of the editor, refreshed on every save so you see changes instantly without leaving the page.
*   **Inline Quick-Add** — a pulsing "+" button on the timeline spine opens a premium glassmorphism modal for adding new events in seconds.
*   **Inline Edit/Delete** — hover any event card to reveal edit (✎) and delete (×) buttons. Edit opens the same premium modal pre-filled; delete removes with a confirmation prompt.
*   **Premium Modal** — the quick-add/edit modal features a gradient glass header, elastic slide-up animation, uppercase labels with smooth focus rings, custom scrollbar, and a gradient submit button.

### Core Features

*   **Three Layouts**: Horizontal (scroll-snap), Classic (zigzag), Flat (modern cards) — each with its own distinct premium design.
*   **Horizontal Layout Redesign**: Text-first cards — calendar date badge and event title side by side on top, description below, and a full-bleed featured image anchored to the bottom. Uniform card heights, a continuous milestone spine that scrolls with the content, per-event connector colors, and refined hover lift with image zoom.
*   **Smart Horizontal Navigation**: Arrow buttons with disabled states at the edges, scroll progress bar, keyboard arrow keys, and desktop drag-to-scroll — with native touch swiping on mobile.
*   **Classic Layout**: Clean calendar badge with colored month strip, 2px gradient spine line, refined dot with hover glow, white card with colored accent bar on hover, and staggered entrance animations.
*   **Per-Event Colors**: Custom background color applied to calendar date boxes and connector lines across all layouts (flat, classic, horizontal).
*   **Centralized Workspace**: Manage titles, dates, descriptions, featured images, and custom dimensions from a single dashboard.
*   **Integrated Live Preview**: Embedded preview refreshes on save — see your timeline update in real time.
*   **Scroll Reveal Animations**: Vertical items fade and slide in via IntersectionObserver — choose from fade, flip, or zoom.
*   **Precision Image Controls**: Custom width and height per event image with `object-fit: cover`.
*   **CSS Scroll Snap**: Horizontal layout uses native browser scroll-snapping — zero JavaScript slider libraries.
*   **Accessible & Focusable**: The horizontal timeline is keyboard-focusable with a visible focus ring and an accessible region label.
*   **Lightweight & Compatible**: Requires WordPress 5.6+ and PHP 7.4+; tested up to WordPress 7.1 and PHP 8.2. No jQuery slider dependencies, no external CSS frameworks.

### Best Use Cases

*   **Company History** — showcase your brand's journey with the horizontal layout for decades-long stories or classic for detailed entries.
*   **Product Roadmaps** — keep customers informed about upcoming features and past milestones.
*   **Personal Portfolios** — display career progression or life story in a visually engaging way.
*   **Event Schedules** — multi-day conferences or workshops with swipeable horizontal agenda.
*   **Step-by-Step Guides** — any content that benefits from a left-to-right or top-to-bottom narrative.

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/deartimeline` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress.
3. Use the `[deartimeline]` shortcode in any page or post.

== Usage ==

### Basic Shortcode
`[deartimeline]` (displays Flat layout by default)

### Switching Layouts
- **Horizontal Layout**: `[deartimeline layout="horizontal"]` — text-first cards on a continuous horizontal spine: date badge and title on top, description, and the featured image anchored at the bottom. CSS Scroll Snap, arrow buttons, keyboard arrows, drag-to-scroll, and a scroll progress bar.
- **Classic Layout**: `[deartimeline layout="classic"]` — premium vertical zigzag with calendar badges and animated entrance.
- **Flat Layout**: `[deartimeline layout="flat"]` — modern side-by-side cards with image and text.

### Animations (Vertical layouts only)
Choose from professional entrance effects:
- `[deartimeline animation="fade-in"]` (Default)
- `[deartimeline animation="flip-up"]`
- `[deartimeline animation="zoom"]`

== Screenshots ==

1. Live Desktop Preview at the top of the editor.
2. Premium quick-add modal with glassmorphism design.
3. Classic layout — premium zigzag with calendar badges and accent bars.
4. Horizontal timeline layout — text-first cards with bottom-anchored images, milestone spine, and scroll progress bar.

== Changelog ==

= 1.4.0 =
* **Horizontal Layout Redesign**: Cards are now text-first — calendar date badge and event title sit side by side at the top, followed by the description, with a full-bleed featured image anchored to the bottom of every card. Cleaner visual hierarchy and a more professional milestone look.
* **Uniform Card Heights**: The track now stretches items so every card is the same height, regardless of description length.
* **Continuous Spine**: Milestone line is now drawn per item, so it scrolls perfectly with the content and always aligns with the color-coded dots.
* **Scroll Progress Bar**: A slim gradient progress indicator under the track shows how far you've scrolled.
* **Keyboard Navigation**: Focus the timeline and use the left/right arrow keys to move between events.
* **Drag-to-Scroll**: Click and drag the track on desktop, just like a native slider. Touch swiping still works out of the box.
* **Accessibility**: Timeline region is now focusable with an accessible label and a visible focus ring.
* **Per-Event Connector Colors**: The connector line from each card to its milestone dot now uses the event's custom color.
* **Compatibility**: Verified against WordPress 7.1 and PHP 8.2; plugin headers aligned to `Requires PHP: 7.4`.

= 1.3.2 =
* **Admin UI Overhaul**: Removed "Event Styling" metabox; "Live Desktop Preview" moved to top priority; inline "+" button with "Add New Event" label on timeline spine; inline edit (✎) and delete (×) action buttons on hover for both flat and classic layouts.
* **Quick-Add/Edit Modal**: Premium glassmorphism modal with gradient header, elastic slide-up animation, uppercase labels, smooth focus rings, custom scrollable body, gradient submit button with micro-interactions.
* **Background Color Everywhere**: Custom event background color now applies to date boxes in all three layouts — flat, classic, and horizontal.
* **Horizontal Layout Polish**: Color applied to calendar month strip; `--tsp-bg` variable set per-item.
* **Enhanced Hover Effects Across All Layouts**: Bouncy card transforms, gradient accent strips, refined shadow stacks.

= 1.3.1 =
* New **Horizontal Layout**: `[deartimeline layout="horizontal"]` — a fully responsive, scroll-snap-powered horizontal timeline. Zero JS slider libraries: uses CSS Flexbox + native Scroll Snap for buttery-smooth mobile swiping and desktop arrow navigation.
* Horizontal cards feature a premium gradient accent bar, calendar-style date box (month/day/year), and per-event color-coded dot markers on a gradient spine line.
* Left/Right navigation buttons with smart disabled state (hidden at start/end of track), implemented in lightweight Vanilla JS with requestAnimationFrame throttling.
* Enhanced card design across all layouts: refined shadows, smoother hover transitions, and consistent min-height for visual balance.

= 1.3.0 =
* Complete flat layout overhaul: modern card-based UI with white cards, rounded corners, and subtle shadows.
* New CSS variables system for easy customization.
* Circular dot markers with indigo accent border instead of square nodes.
* Pill-shaped year separator labels.
* Soft gray central line (configurable).
* Responsive mobile layout: single-sided spine with stacked content.
* Scroll reveal animations via IntersectionObserver.
* Improved zigzag alternation with reliable flexbox ordering.

= 1.2.0 =
* Integrated Featured Image selector into the central meta box.
* Added custom Width and Height controls for event images.
* Added "Live Desktop Preview" directly to the Event Edit screen.
* Refactored frontend popup to support batch event creation.

= 1.1.0 =
* Improved zigzag layout with straight-line alignment.
* Standardized image heights (300px).
* Fixed text domain issues and compliance headers.

= 1.0.0 =
* Initial release.

== Frequently Asked Questions ==

= How do I use the shortcode? =
Simply add `[deartimeline]` to any page or post. By default, this will display all your published timeline events in the Flat layout.

= Can I change the layout? =
Yes! You can choose between three professional styles:
- **Horizontal**: `[deartimeline layout="horizontal"]` — text-first cards with the image anchored at the bottom, connected by a continuous milestone spine. Arrow buttons, keyboard arrows, drag-to-scroll, and a progress bar. Perfect for mobile-first, swipeable timelines.
- **Classic**: `[deartimeline layout="classic"]` — premium vertical zigzag with refined calendar badges, colored accent bars, and animated entrance.
- **Flat**: `[deartimeline layout="flat"]` — modern card-based layout with side-by-side image and text panels.

= Are there any animation options? =
For vertical layouts, use the `animation` parameter to pick your entrance effect:
- `[deartimeline animation="fade-in"]` (Subtle)
- `[deartimeline animation="flip-up"]` (Dynamic)
- `[deartimeline animation="zoom"]` (Engaging)

= How do I add or edit events from the admin? =
In the WordPress admin, open a timeline event. The "Live Desktop Preview" at the top shows your timeline. Click the "+" button on the spine to add a new event, or hover any card to reveal the edit (✎) and delete (×) buttons. The premium modal handles all CRUD operations with a clean, responsive form.

= Where do I set the image size? =
On the event edit screen, look for the "Image Width" and "Image Height" fields below the title. You can use pixels (e.g., 300px) or percentages (e.g., 100%).

= How does the horizontal layout work without JavaScript? =
The horizontal timeline uses native CSS `scroll-snap-type: x mandatory` and `overflow-x: auto`. The scroll track works on any device without JavaScript — scroll with a trackpad, swipe on mobile, or focus the track and use the arrow keys. The Vanilla JS layer (arrow buttons, progress bar, drag-to-scroll, item-by-item keyboard navigation) enhances but never replaces the core scroll experience.

= Is DearTimeline compatible with the latest WordPress and PHP versions? =
Yes. DearTimeline requires WordPress 5.6+ and PHP 7.4+, and is tested up to WordPress 7.1 and PHP 8.2. It uses no deprecated PHP functions and no external slider libraries, so it stays fast and compatible as WordPress and PHP evolve.

= Does the horizontal layout work on mobile? =
Yes. Cards use native touch swiping with CSS Scroll Snap, and the layout automatically tightens spacing and card widths on smaller screens. On very narrow screens you can still use the arrow buttons or swipe between events.

== Upgrade Notice ==

= 1.4.0 =
Major horizontal layout redesign: text-first cards with the image anchored at the bottom, uniform card heights, a continuous milestone spine, scroll progress bar, keyboard arrow navigation, and drag-to-scroll. See changelog for full details.
