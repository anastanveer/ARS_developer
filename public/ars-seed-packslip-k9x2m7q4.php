<?php
// One-time blog post seed script — auto-deletes after use

$dbPath = __DIR__ . '/../database/database.sqlite';

if (!file_exists($dbPath)) {
    die('DB not found at: ' . $dbPath);
}

try {
    $pdo = new PDO('sqlite:' . $dbPath);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if already exists
    $check = $pdo->prepare("SELECT COUNT(*) FROM blog_posts WHERE slug = ?");
    $check->execute(['how-to-print-packing-slips-shopify-free-app']);
    if ($check->fetchColumn() > 0) {
        echo '<p style="color:green;font-family:sans-serif;font-size:18px;">&#10003; Blog post already exists in database. Visit: <a href="https://arsdeveloper.co.uk/blog/how-to-print-packing-slips-shopify-free-app">View Blog Post</a></p>';
        // Self-delete
        @unlink(__FILE__);
        die();
    }

    $now = date('Y-m-d H:i:s');

    $content = <<<'HTML'
<p>Shopify is excellent at taking orders — but when it comes to fulfillment, the default admin leaves a lot to be desired. There is no built-in packing slip designer, no bulk fulfillment tool and no branded order tracking page. Most Shopify merchants end up paying for three to five separate apps just to cover the basics.</p>

<p>This guide walks you through exactly how to print packing slips on Shopify, fulfill orders in bulk and send automated shipping notifications — without paying a penny.</p>

<h2>Does Shopify Have a Built-In Packing Slip Feature?</h2>

<p>Technically, yes. Shopify lets you print a basic order summary from the order detail page. But it is a plain, unbranded document with no logo, no custom fields and no ability to print multiple orders at once. For any serious fulfillment operation, it falls short immediately.</p>

<p>You need a dedicated Shopify packing slip app if you want:</p>

<ul>
  <li>Your logo and brand colours on every slip</li>
  <li>Custom fields — gift messages, handling notes, barcodes, SKUs</li>
  <li>Bulk printing across dozens or hundreds of orders at once</li>
  <li>Multi-location and 3PL-ready CSV exports</li>
</ul>

<h2>The Problem With Most Shopify Fulfillment Apps</h2>

<p>Search the Shopify App Store for "packing slips" and you will find dozens of options — most of them charging $10 to $20 per month per feature. If you also need bulk fulfillment, branded tracking and WhatsApp order alerts, you are easily spending $50 or more per month across multiple apps.</p>

<p>This is exactly the gap <a href="https://packslip.arsdeveloper.co.uk/" target="_blank" rel="noopener">PackSlip</a> was built to close.</p>

<h2>What Is PackSlip?</h2>

<p>PackSlip is a free Shopify app that bundles 34 fulfillment and post-purchase modules into a single clean dashboard inside your Shopify admin. No extra logins, no separate subscriptions, no credit card required — ever.</p>

<p>The core modules include:</p>

<ul>
  <li><strong>Packing Slips</strong> — Custom branded templates with your logo, colours and fonts. Print single orders or entire batches in one PDF.</li>
  <li><strong>Bulk Fulfillment</strong> — Upload a CSV of tracking numbers and fulfill hundreds of orders in one click. Customers are notified automatically.</li>
  <li><strong>Branded Order Tracking</strong> — A custom tracking page on your own store domain with a progress bar, event timeline and full brand styling.</li>
  <li><strong>WhatsApp &amp; Email Alerts</strong> — Automatic messages when orders are confirmed, dispatched and delivered.</li>
  <li><strong>Order Exports</strong> — CSV and XLSX exports formatted for any 3PL or warehouse.</li>
  <li><strong>Storefront Tools</strong> — Product reviews, abandoned cart recovery, email capture, back-in-stock alerts and trust badges.</li>
</ul>

<p>All of the above — and 28 additional modules — are completely free.</p>

<h2>How to Print Packing Slips on Shopify With PackSlip</h2>

<h3>Step 1: Install PackSlip Free</h3>
<p>Visit <a href="https://packslip.arsdeveloper.co.uk/" target="_blank" rel="noopener">packslip.arsdeveloper.co.uk</a> and click <strong>Install Free on Shopify</strong>. No credit card required.</p>

<h3>Step 2: Open Your Dashboard</h3>
<p>Once installed, PackSlip appears directly inside your Shopify admin. All 34 modules unlock immediately.</p>

<h3>Step 3: Set Up Your Packing Slip Template</h3>
<p>Navigate to the <strong>Packing Slips</strong> module. Upload your logo, set your brand colour and select which order fields to display.</p>

<h3>Step 4: Print Single or Bulk Orders</h3>
<p>To print in bulk: select multiple orders, open PackSlip's bulk print view and download a single PDF containing all slips. No page limits, no order caps.</p>

<h2>Bulk Fulfillment — Fulfill Hundreds of Orders in One Click</h2>

<ol>
  <li>Export your pending orders list from Shopify as a CSV</li>
  <li>Add the corresponding tracking numbers from your courier</li>
  <li>Upload the CSV into PackSlip's bulk fulfillment module</li>
  <li>PackSlip marks every order as fulfilled and notifies each customer automatically</li>
</ol>

<h2>Branded Order Tracking</h2>

<p>PackSlip replaces the generic courier tracking page with a branded experience on your own domain — progress bar, event timeline, your logo and colours. Customers stay on your store, not a courier website.</p>

<h2>What Does PackSlip Cost?</h2>

<table style="width:100%;border-collapse:collapse;margin:24px 0;">
  <thead>
    <tr style="background:#0d1f38;color:#fff;">
      <th style="padding:12px 16px;text-align:left;">Feature</th>
      <th style="padding:12px 16px;text-align:left;">Typical App Cost</th>
      <th style="padding:12px 16px;text-align:left;">PackSlip</th>
    </tr>
  </thead>
  <tbody>
    <tr style="border-bottom:1px solid #e2e8f0;">
      <td style="padding:12px 16px;">Packing slip printing</td>
      <td style="padding:12px 16px;color:#e53e3e;">$10/mo</td>
      <td style="padding:12px 16px;color:#22c55e;font-weight:700;">&#10003; Free</td>
    </tr>
    <tr style="border-bottom:1px solid #e2e8f0;background:#f8fafc;">
      <td style="padding:12px 16px;">Branded order tracking</td>
      <td style="padding:12px 16px;color:#e53e3e;">$12/mo</td>
      <td style="padding:12px 16px;color:#22c55e;font-weight:700;">&#10003; Free</td>
    </tr>
    <tr style="border-bottom:1px solid #e2e8f0;">
      <td style="padding:12px 16px;">Bulk fulfillment tool</td>
      <td style="padding:12px 16px;color:#e53e3e;">$15/mo</td>
      <td style="padding:12px 16px;color:#22c55e;font-weight:700;">&#10003; Free</td>
    </tr>
    <tr style="border-bottom:1px solid #e2e8f0;background:#f8fafc;">
      <td style="padding:12px 16px;">WhatsApp / email alerts</td>
      <td style="padding:12px 16px;color:#e53e3e;">$19/mo</td>
      <td style="padding:12px 16px;color:#22c55e;font-weight:700;">&#10003; Free</td>
    </tr>
    <tr style="background:#0d1f38;color:#fff;font-weight:700;">
      <td style="padding:12px 16px;">Total</td>
      <td style="padding:12px 16px;">$56/mo</td>
      <td style="padding:12px 16px;color:#4ade80;">$0</td>
    </tr>
  </tbody>
</table>

<h2>Is PackSlip Safe and GDPR Compliant?</h2>

<p>PackSlip connects to Shopify via the official Shopify API only. It does not sell data or share data with third parties. Built by <a href="https://arsdeveloper.co.uk/" target="_blank" rel="noopener">ARS Developer Ltd</a>, UK registered company (Co. No: 17039150), Stoke-on-Trent, Staffordshire. Fully GDPR ready.</p>

<h2>Frequently Asked Questions</h2>

<h3>Does PackSlip work with my Shopify theme?</h3>
<p>Yes. PackSlip runs inside your Shopify admin and its storefront features install on any theme in one click.</p>

<h3>Is there a limit on packing slips or orders?</h3>
<p>No. There are no order limits, usage caps or tiered restrictions on any of the 34 modules.</p>

<h3>Which Shopify plans does PackSlip support?</h3>
<p>All plans — Basic, Shopify, Advanced and Plus.</p>

<h3>Do I need to enter card details to install?</h3>
<p>No. PackSlip is free to install and free to use indefinitely.</p>

<h3>Can PackSlip handle bulk tracking number uploads?</h3>
<p>Yes. Upload a CSV of order numbers and tracking numbers — PackSlip fulfills every order and notifies each customer in one action.</p>

<h2>Ready to Simplify Your Shopify Fulfillment?</h2>

<p style="text-align:center;margin:32px 0;">
  <a href="https://packslip.arsdeveloper.co.uk/" target="_blank" rel="noopener" style="display:inline-block;background:#1d93ff;color:#fff;text-decoration:none;padding:16px 36px;border-radius:50px;font-size:16px;font-weight:700;">Install PackSlip Free on Shopify &#8594;</a>
</p>

<p>All 34 modules unlock immediately. No card, no trial, no waiting.</p>
HTML;

    $sql = "INSERT INTO blog_posts
        (title, slug, category, author_name, excerpt, content, featured_image, featured_image_alt,
         is_published, published_at, meta_title, meta_description, meta_keywords,
         og_title, og_description, og_image, twitter_title, twitter_description, twitter_image,
         canonical_url, sort_order, created_at, updated_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'How to Print Packing Slips on Shopify — Free App + Complete Guide (2025)',
        'how-to-print-packing-slips-shopify-free-app',
        'Shopify',
        'Anas Tanveer',
        "Shopify's default packing slip is plain and unbranded. This step-by-step guide shows how to print branded packing slips, bulk-fulfill orders and send automated WhatsApp alerts — all free with PackSlip, the 34-module Shopify app.",
        $content,
        'assets/images/blog/packslip-shopify-packing-slip-guide.png',
        'How to print packing slips on Shopify — PackSlip free app dashboard',
        1,
        $now,
        'How to Print Packing Slips on Shopify Free | PackSlip App 2025',
        'Step-by-step guide to printing branded packing slips on Shopify. PackSlip gives you packing slips, bulk fulfillment, branded tracking, WhatsApp alerts and 34 modules — completely free.',
        'shopify packing slip, shopify packing slip app free, print packing slips shopify, how to print packing slips on shopify, shopify fulfillment app free, bulk fulfillment shopify, shopify order tracking app free, packslip shopify',
        'How to Print Packing Slips on Shopify — Free with PackSlip',
        'Print branded packing slips, bulk-fulfill orders and send WhatsApp shipping alerts — all free. PackSlip gives Shopify stores 34 fulfillment modules at $0.',
        'assets/images/blog/packslip-shopify-packing-slip-guide.png',
        'Free Shopify Packing Slip App — 34 Modules at $0',
        'PackSlip: branded packing slips, bulk fulfillment, order tracking and WhatsApp alerts. Free for every Shopify store.',
        'assets/images/blog/packslip-shopify-packing-slip-guide.png',
        'https://arsdeveloper.co.uk/blog/how-to-print-packing-slips-shopify-free-app',
        0,
        $now,
        $now,
    ]);

    // Self-delete this file
    @unlink(__FILE__);

    echo '<div style="font-family:sans-serif;padding:40px;max-width:500px;margin:80px auto;text-align:center;border:2px solid #22c55e;border-radius:16px;">';
    echo '<h2 style="color:#22c55e;">&#10003; Blog Post Created!</h2>';
    echo '<p style="color:#374151;">The PackSlip blog post has been added to the live database.</p>';
    echo '<p><a href="https://arsdeveloper.co.uk/blog/how-to-print-packing-slips-shopify-free-app" style="background:#1d93ff;color:#fff;padding:12px 28px;border-radius:8px;text-decoration:none;font-weight:700;">View Blog Post Live &#8594;</a></p>';
    echo '<p style="color:#9ca3af;font-size:13px;margin-top:20px;">This script has self-deleted for security.</p>';
    echo '</div>';

} catch (Exception $e) {
    echo '<p style="color:red;font-family:sans-serif;">Error: ' . htmlspecialchars($e->getMessage()) . '</p>';
}
