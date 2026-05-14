<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<style>
  body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 20px; }
  .wrap { max-width: 600px; margin: 0 auto; background: #fff; border-radius: 8px; overflow: hidden; }
  .header { background: #173153; padding: 28px 32px; color: #fff; }
  .header h1 { margin: 0; font-size: 20px; }
  .header p { margin: 6px 0 0; opacity: .8; font-size: 14px; }
  .body { padding: 32px; }
  .plan-box { background: #f0f6ff; border-left: 4px solid #1d93ff; border-radius: 6px; padding: 18px 22px; margin-bottom: 24px; }
  .plan-box h2 { margin: 0 0 6px; font-size: 18px; color: #173153; }
  .plan-box p { margin: 4px 0 0; color: #555; font-size: 14px; }
  .steps { margin: 24px 0; }
  .step { display: flex; align-items: flex-start; margin-bottom: 14px; }
  .step-num { background: #173153; color: #fff; border-radius: 50%; width: 26px; height: 26px; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: bold; flex-shrink: 0; margin-right: 12px; margin-top: 2px; }
  .step-text h4 { margin: 0 0 2px; font-size: 14px; color: #173153; }
  .step-text p { margin: 0; font-size: 13px; color: #666; }
  .cta { text-align: center; margin: 28px 0 0; }
  .cta a { background: #173153; color: #fff; text-decoration: none; padding: 12px 28px; border-radius: 6px; font-size: 14px; display: inline-block; }
  .footer { background: #f4f4f4; padding: 18px 32px; font-size: 12px; color: #999; text-align: center; }
</style>
</head>
<body>
<div class="wrap">
  <div class="header">
    <h1>We've received your order request</h1>
    <p>ARS Developer will be in touch within 1 business day</p>
  </div>
  <div class="body">
    <p>Hi <strong>{{ $payload['name'] ?? 'there' }}</strong>,</p>
    <p>Thank you for choosing ARS Developer. Here's a summary of what you've requested:</p>

    <div class="plan-box">
      <h2>{{ $payload['plan'] ?? 'Selected Package' }}</h2>
      <p>Billing: {{ ucfirst(str_replace('_', ' ', $payload['billing'] ?? '')) }}</p>
      @if(!empty($payload['price']) && $payload['price'] > 0)
        <p>Price: <strong>GBP {{ number_format((float)$payload['price'], 2) }}</strong></p>
      @else
        <p>Price: <strong>Custom — we'll confirm with a quote</strong></p>
      @endif
    </div>

    <p><strong>What happens next:</strong></p>
    <div class="steps">
      <div class="step">
        <div class="step-num">1</div>
        <div class="step-text">
          <h4>We review your request</h4>
          <p>Our team reviews your selected package and any requirements you've shared.</p>
        </div>
      </div>
      <div class="step">
        <div class="step-num">2</div>
        <div class="step-text">
          <h4>Invoice sent to your email</h4>
          <p>We send a formal invoice to {{ $payload['email'] ?? 'your email' }} within 1 business day.</p>
        </div>
      </div>
      <div class="step">
        <div class="step-num">3</div>
        <div class="step-text">
          <h4>Project kicks off</h4>
          <p>Once payment is confirmed, your project is scheduled and delivery begins.</p>
        </div>
      </div>
    </div>

    <p style="font-size:13px;color:#666;">Questions? Reply to this email or contact us at <a href="mailto:info@arsdeveloper.co.uk">info@arsdeveloper.co.uk</a></p>

    <div class="cta">
      <a href="https://arsdeveloper.co.uk/contact">Contact Us</a>
    </div>
  </div>
  <div class="footer">ARS Developer · Stoke-on-Trent, UK · arsdeveloper.co.uk</div>
</div>
</body>
</html>
