<?php
$db = new PDO('sqlite:' . __DIR__ . '/database/database.sqlite');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$check = $db->prepare("SELECT COUNT(*) FROM blog_posts WHERE slug=?");
$check->execute(['how-to-print-packing-slips-shopify-free-app']);
if ($check->fetchColumn() > 0) {
    echo "EXISTS — blog post already in database.\n";
    exit;
}

$now = date('Y-m-d H:i:s');
$content = '<p>Shopify is excellent at taking orders — but when it comes to fulfillment, the default admin leaves a lot to be desired. There is no built-in packing slip designer, no bulk fulfillment tool and no branded order tracking page. Most Shopify merchants end up paying for three to five separate apps just to cover the basics.</p><h2>What Is PackSlip?</h2><p>PackSlip is a free Shopify app that bundles 34 fulfillment and post-purchase modules into a single clean dashboard inside your Shopify admin. No extra logins, no separate subscriptions, no credit card required.</p><ul><li><strong>Packing Slips</strong> — Custom branded templates with your logo, colours and fonts.</li><li><strong>Bulk Fulfillment</strong> — Upload a CSV of tracking numbers and fulfill hundreds of orders in one click.</li><li><strong>Branded Order Tracking</strong> — A custom tracking page on your own domain with progress bar and full branding.</li><li><strong>WhatsApp &amp; Email Alerts</strong> — Automatic messages when orders are confirmed, dispatched and delivered.</li><li><strong>Order Exports</strong> — CSV and XLSX exports for any 3PL or warehouse.</li><li><strong>Storefront Tools</strong> — Reviews, abandoned cart, email capture, back-in-stock and trust badges.</li></ul><h2>How to Print Packing Slips on Shopify With PackSlip</h2><h3>Step 1: Install PackSlip Free</h3><p>Visit <a href="https://packslip.arsdeveloper.co.uk/" target="_blank" rel="noopener">packslip.arsdeveloper.co.uk</a> and click Install Free on Shopify. No credit card required.</p><h3>Step 2: Open Your Dashboard</h3><p>Once installed, PackSlip appears directly inside your Shopify admin. All 34 modules unlock immediately.</p><h3>Step 3: Set Up Your Template</h3><p>Navigate to the Packing Slips module. Upload your logo, set your brand colour and select which fields to display — product name, SKU, quantity, gift note, barcode and more.</p><h3>Step 4: Print Single or Bulk Orders</h3><p>Select multiple orders, open PackSlip bulk print view and download a single PDF for all orders. No page limits, no order caps.</p><h2>Bulk Fulfillment</h2><ol><li>Export pending orders from Shopify as CSV</li><li>Add tracking numbers from your courier</li><li>Upload CSV to PackSlip bulk fulfillment</li><li>PackSlip marks every order fulfilled and notifies customers automatically</li></ol><h2>What Does PackSlip Cost?</h2><p>PackSlip is completely free. All 34 modules are available with no usage limits and no premium tier. Packing slips ($10/mo elsewhere), order tracking ($12/mo), bulk fulfillment ($15/mo), WhatsApp alerts ($19/mo) — all $0 with PackSlip.</p><h2>Is PackSlip GDPR Compliant?</h2><p>Yes. PackSlip connects via the official Shopify API only and does not sell data. Built by <a href="https://arsdeveloper.co.uk/" target="_blank" rel="noopener">ARS Developer Ltd</a>, UK registered (Co. No: 17039150), Stoke-on-Trent.</p><h2>Frequently Asked Questions</h2><h3>Does PackSlip work with my theme?</h3><p>Yes. It runs inside Shopify admin and storefront features install on any theme in one click.</p><h3>Is there an order limit?</h3><p>No. No limits on any of the 34 modules.</p><h3>Which Shopify plans are supported?</h3><p>All plans — Basic, Shopify, Advanced and Plus.</p><h3>Do I need a card to install?</h3><p>No. Free forever, no card required.</p><p style="text-align:center;margin:32px 0;"><a href="https://packslip.arsdeveloper.co.uk/" target="_blank" rel="noopener" style="display:inline-block;background:#1d93ff;color:#fff;text-decoration:none;padding:16px 36px;border-radius:50px;font-size:16px;font-weight:700;">Install PackSlip Free on Shopify &#8594;</a></p>';

$stmt = $db->prepare("INSERT INTO blog_posts
    (title,slug,category,author_name,excerpt,content,featured_image,featured_image_alt,
     is_published,published_at,meta_title,meta_description,meta_keywords,
     og_title,og_description,og_image,sort_order,created_at,updated_at)
    VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");

$stmt->execute([
    'How to Print Packing Slips on Shopify — Free App + Complete Guide (2025)',
    'how-to-print-packing-slips-shopify-free-app',
    'Shopify',
    'Anas Tanveer',
    'Shopify packing slip guide — print branded slips, bulk fulfill orders and send WhatsApp alerts free with PackSlip, the 34-module Shopify app.',
    $content,
    'assets/images/blog/packslip-shopify-packing-slip-guide.png',
    'How to print packing slips on Shopify — PackSlip free app dashboard',
    1,
    $now,
    'How to Print Packing Slips on Shopify Free | PackSlip App 2025',
    'Step-by-step guide to printing branded packing slips on Shopify. PackSlip gives you 34 modules — completely free.',
    'shopify packing slip, shopify packing slip app free, print packing slips shopify, how to print packing slips on shopify, shopify fulfillment app free, bulk fulfillment shopify',
    'How to Print Packing Slips on Shopify — Free with PackSlip',
    'Print branded packing slips, bulk-fulfill orders and send WhatsApp shipping alerts — all free with PackSlip.',
    'assets/images/blog/packslip-shopify-packing-slip-guide.png',
    0,
    $now,
    $now,
]);

echo "SUCCESS — Blog post created!\n";
echo "URL: https://arsdeveloper.co.uk/blog/how-to-print-packing-slips-shopify-free-app\n";
