=== Inlarge – Inline Image Zoom ===
Contributors: kyo-ichida
Tags: image, zoom, enlarge, lightbox, gallery
Requires at least: 5.0
Tested up to: 7.0
Requires PHP: 7.0
Stable tag: 1.2.1
License: MIT
License URI: https://opensource.org/licenses/MIT

Inline image zoom for WordPress. Enlarges images in place without covering the page, so the surrounding text stays readable.

== Description ==

Inlarge brings the [abc-enlarge](https://github.com/kyocom/abc-enlarge) jQuery library to WordPress. Unlike typical lightbox plugins that dim the whole screen, it enlarges the clicked image **in place** — the article text around it stays readable. Ideal for web magazines and long-form articles.

👉 **[Try the live demo](https://kyocom.github.io/abc-enlarge/demo/index.html)** — click an image and watch the text keep flowing around it. A before/after comparison is in the screenshots below.

**What it does**

* Automatically adds the `abc-enlarge` class to linked images in your post content (only images wrapped in a link to an image file, so nothing breaks).
* Swaps in the high-resolution image from the link's `href` on click, and restores the small one on collapse.
* On portrait phones, expands the image into a horizontally scrollable, auto-centered view.
* Can also apply to WordPress galleries (classic and block), making those images enlargeable regardless of their link setting. Off by default — tick it per post.
* Lets you disable enlargement per post — enabled by default.

== Installation ==

1. Upload the `inlarge` folder to `/wp-content/plugins/`, or install the ZIP via Plugins → Add New → Upload Plugin.
2. Activate the plugin through the Plugins menu in WordPress.
3. Make sure your images link to the media file (in the block/classic editor, set the image link to "Media File"). Those images are enlarged automatically.

== Usage ==

Under **Settings → Inlarge**, tick which post types (post, page, custom post types) enlargement runs on. Only checked post types are enabled; all eligible types are checked by default.

Within an enabled post type, each post also has controls in the **Inlarge** box:

* Block editor: the box appears at the bottom of the editor.
* Classic editor: the box appears in the right-hand sidebar.

The "Enable image enlargement for this post" checkbox is checked by default; uncheck it to opt that post out. The **Apply to WordPress galleries** checkbox is **off by default** — tick it when you also want images inside classic and block galleries to be enlargeable.

== Frequently Asked Questions ==

= Which images get enlarged? =

Outside galleries: only images wrapped in a link that points to an image file (`.jpg`, `.png`, `.gif`, `.webp`, `.avif`, `.bmp`, `.svg`). This is what WordPress produces when an image links to its Media File. Images without such a link are left untouched so they never break.

Inside WordPress galleries: nothing, unless you tick **Apply to WordPress galleries** on the post (it is off by default). With it on, gallery images are made enlargeable regardless of their link setting: the plugin resolves a full-size image URL (from the image's attachment ID, or by dropping the resize suffix from the filename) and never swaps in a non-image, so images don't break.

= Does it work with the block editor? =

Yes. The per-post toggles work in both the block editor and the classic editor.

= Does it work with custom post types? =

Yes. The script and auto-class run on every singular view, and the per-post toggles are shown for posts, pages, and public custom post types that support the editor.

= How do I load the unminified script for debugging? =

Define `SCRIPT_DEBUG` as `true` in `wp-config.php` and the plugin loads the non-minified build.

== Screenshots ==

1. Before — the image sits inline in the article at its normal size.
2. After one click — the image is enlarged in place and swapped to the high-resolution file. No overlay, no dimming: the article text below stays readable and the scroll position never moves. Click again to restore.

== Changelog ==

= 1.2.1 =
* **Changed default:** "Apply to WordPress galleries" is now **off** by default and opt-in per post. Gallery markup varies between themes, so galleries are no longer touched unless you ask for them. Posts where you had already unticked the box are unaffected; posts relying on the old default need the box ticked to keep enlarging gallery images.
* Add a plugin icon, before/after screenshots, and a link to the live demo.

= 1.2.0 =
* Add a **Settings → Inlarge** page to choose which post types (post, page, and public custom post types) image enlargement runs on. Only checked post types are enabled. Defaults to all eligible post types.

= 1.1.2 =
* Reword the per-post control to "Enable image enlargement for this post", checked by default. Unchecking it is evaluated first, before any class is added, so opted-out posts get no `abc-enlarge` markup at all. No change to existing posts (default stays enabled).

= 1.1.1 =
* Update the bundled abc-enlarge script to v1.0.3, adding a gallery CSS rule so an enlarged image fills its `.gallery-item` cell.

= 1.1.0 =
* Add a per-post "Apply to WordPress galleries" option (on by default). Classic and block gallery images become enlargeable regardless of their link setting.
* Extend the per-post toggle to public custom post types that support the editor.

= 1.0.0 =
* Initial release. Auto-adds the `abc-enlarge` class to linked content images and adds a per-post enable/disable toggle.
