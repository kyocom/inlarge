# Inlarge – Inline Image Zoom (WordPress plugin)

> Inline image zoom for WordPress, powered by the [abc-enlarge](https://github.com/kyocom/abc-enlarge) jQuery plugin. Enlarges images **in place** without covering the page, so the surrounding text stays readable.
> ページを覆い隠さず、文章を読める状態のまま画像を拡大する WordPress プラグイン。

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](LICENSE)

🇺🇸 [English](#english) ・ 🇯🇵 [日本語](#日本語)

---

## English

### Features

- 🏷️ **Auto class** — automatically adds the `abc-enlarge` class to linked images in post content.
- 🖼️ **Inline zoom** — no overlay; enlarges the clicked image in place while surrounding text stays readable.
- 🔍 **High-res swap** — swaps to the large image from the link's `href`, restores the small one on collapse.
- 📱 **Touch-friendly** — on portrait phones the image expands into a horizontally scrollable, auto-centered view.
- 🧩 **Gallery support** — classic `[gallery]` and block galleries can be made enlargeable regardless of link setting. Off by default, opt in per post.
- ⚙️ **Post-type settings** — a settings page to pick which post types (post, page, custom post types) enlargement runs on. All eligible types on by default.
- 🎛️ **Per-post toggles** — disable enlargement, or exclude galleries, on any individual post/page. **Both on by default.**

### How it targets images

**Outside galleries:** only `<img>` elements wrapped in an `<a>` whose `href`
points to an image file (`.jpg`, `.png`, `.gif`, `.webp`, `.avif`, `.bmp`,
`.svg`) receive the class — exactly what WordPress outputs when an image links
to its **Media File**. Unlinked images are left untouched so they never break.

```html
<a href="large.jpg"><img class="abc-enlarge" src="small.jpg" width="400" height="300"></a>
```

**Inside WordPress galleries** (opt in per post; off by default): images are made
enlargeable no matter their link setting. The plugin resolves a full-size URL
from the image's attachment ID (`wp-image-{id}`) or by dropping the `-WxH`
resize suffix, and falls back to the image's own `src` — so it never swaps in
a non-image and images can't break.

### Install

1. Copy this folder to `wp-content/plugins/inlarge/` (or upload the ZIP via **Plugins → Add New → Upload Plugin**).
2. Activate **Inlarge**.
3. Set your content images to link to the **Media File**. They are enlarged automatically.

### Settings page (post types)

Go to **Settings → Inlarge** to choose which post types image enlargement
runs on. You get one checkbox per eligible post type — `post`, `page`, and each
public custom post type that supports the editor. **Only checked post types are
enabled**; unchecking one disables enlargement (and hides the per-post box) for
that whole type. All eligible types are checked by default, so nothing changes
until you opt some out.

### Per-post option

Within an enabled post type, each post can still be tuned. In the **Inlarge**
box (bottom of the block editor, or the sidebar in the classic editor):

- **Enable image enlargement for this post** — checked by default; uncheck to turn the whole feature off for that post.
- **Apply to WordPress galleries** — off by default; tick it to also enlarge images inside classic and block galleries.

### Developer hooks

Custom post types are supported: the settings page lists every public custom
post type that supports the editor, and the script runs on any enabled type.

```php
// Change which post types are selectable on the settings page
// (default: post, page + public custom post types that support the editor).
add_filter( 'inlarge_post_types', function ( $types ) {
    $types[] = 'my_cpt';
    return $types;
} );

// Programmatically force enable/disable for a post.
add_filter( 'inlarge_is_enabled_for_post', function ( $enabled, $post ) {
    return $enabled;
}, 10, 2 );
```

Define `SCRIPT_DEBUG` as `true` to load the unminified script.

### Requirements

- WordPress 5.0+
- PHP 7.0+
- jQuery (bundled with WordPress)

### Releasing (maintainers)

This repo is the source of truth; the [wordpress.org SVN repo](https://plugins.svn.wordpress.org/inlarge/)
is synced automatically by [`.github/workflows/deploy.yml`](.github/workflows/deploy.yml)
on every tag push. To ship a new version:

1. Bump the version in `inlarge.php` (`Version:` header and `INLARGE_VERSION`) and in `readme.txt` (`Stable tag:`), and add a changelog entry to `readme.txt`.
2. Commit, then tag with the bare version number (no `v` prefix) and push the tag:
   ```bash
   git tag 1.2.1
   git push origin 1.2.1
   ```
3. The workflow builds the plugin per `.distignore` and pushes it to SVN `trunk` and a matching `tags/1.2.1`.

One-time setup: add `SVN_USERNAME` and `SVN_PASSWORD` (your wordpress.org account) as
[repo secrets](https://github.com/kyocom/inlarge/settings/secrets/actions).

### License

[MIT](LICENSE) © kyocom (Kyo Ichida)

---

## 日本語

### 特徴

- 🏷️ **クラス自動付与** — post 本文中の「画像リンク付き img」に `abc-enlarge` クラスを自動で付与。
- 🖼️ **インライン拡大** — オーバーレイなし。クリックした画像をその場で拡大し、周囲の文章は読めるまま。
- 🔍 **高解像度差し替え** — リンクの `href` に指定した大きい画像へ差し替え、縮小時に元へ復元。
- 📱 **タッチ端末対応** — スマホ縦画面では横スクロール可能なビューへ拡大し、中央へ自動スクロール。
- 🧩 **ギャラリー対応** — クラシック `[gallery]` とブロックギャラリーも、リンク設定に関わらず拡大可能にできます。**デフォルトは無効**・post 単位でオンにします。
- ⚙️ **投稿タイプ設定** — 設定ページで、拡大を動作させる投稿タイプ（post / page / カスタム投稿タイプ）を選択。対象候補はすべてデフォルト有効。
- 🎛️ **post 単位のオプション** — 各投稿・固定ページで「拡大の有効/無効」（既定は有効）と「ギャラリーへの適用」（既定は無効）を切替。

### 付与対象について

**ギャラリー以外**：画像ファイル（`.jpg` / `.png` / `.gif` / `.webp` / `.avif` /
`.bmp` / `.svg`）へのリンク `<a href>` で囲まれた `<img>` だけにクラスを付与します。
これは画像のリンク先を **「メディアファイル」** にしたときの WordPress 出力そのもの
です。リンクのない画像は対象外なので、クリックで画像が壊れることはありません。

**WordPress ギャラリー内**（オプションはデフォルト無効・post 単位でオン）：リンク設定に関わらず
ギャラリー画像を拡大可能にします。フル画像URLを添付ファイルID（`wp-image-{id}`）
や `-幅x高さ` のリサイズ接尾辞除去から解決し、最終的には画像自身の `src` に
フォールバックするため、画像以外に差し替わることはなく、画像が壊れません。

### インストール

1. このフォルダを `wp-content/plugins/inlarge/` に配置（または ZIP を **プラグイン → 新規追加 → プラグインのアップロード** から）。
2. **Inlarge** を有効化。
3. 本文画像のリンク先を **「メディアファイル」** にすると、自動的に拡大対象になります。

### 設定ページ（投稿タイプ）

**設定 → Inlarge** で、拡大を動作させる投稿タイプを選べます。`post` /
`page` と、エディターをサポートする公開カスタム投稿タイプごとにチェックボックスが
並びます。**チェックした投稿タイプだけが有効**で、外すとその投稿タイプ全体で拡大が
無効になり（編集画面のボックスも非表示）ます。対象候補は初期状態ですべてチェック
済みなので、明示的に外すまで挙動は変わりません。

### post 単位のオプション

有効な投稿タイプの中で、投稿ごとにさらに調整できます。編集画面の **Inlarge**
ボックス（ブロックエディターでは画面下部、クラシックエディターではサイドバー）:

- **この投稿で画像拡大を有効にする** — デフォルトでチェック済み。外すとその投稿の拡大機能を丸ごとオフにします。
- **WordPress ギャラリーにも適用** — **デフォルト無効**。オンにすると、クラシック／ブロック両ギャラリー内の画像も拡大対象になります。

### 開発者向けフック

カスタム投稿タイプにも対応しています。設定ページにはエディター対応の公開カスタム
投稿タイプがすべて並び、有効化した投稿タイプでスクリプトが動作します。

```php
// 設定ページで選択できる投稿タイプを変更
// （デフォルト: post, page ＋ エディター対応の公開カスタム投稿タイプ）
add_filter( 'inlarge_post_types', function ( $types ) {
    $types[] = 'my_cpt';
    return $types;
} );

// 投稿ごとに有効/無効をプログラムで制御
add_filter( 'inlarge_is_enabled_for_post', function ( $enabled, $post ) {
    return $enabled;
}, 10, 2 );
```

`SCRIPT_DEBUG` を `true` にすると非圧縮版スクリプトを読み込みます。

### ライセンス

[MIT](LICENSE) © kyocom (Kyo Ichida)
