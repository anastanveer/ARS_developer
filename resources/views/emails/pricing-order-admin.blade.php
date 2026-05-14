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
  .plan-box p { margin: 0; color: #555; font-size: 14px; }
  .field { margin-bottom: 14px; }
  .field label { display: block; font-size: 12px; color: #888; text-transform: uppercase; letter-spacing: .5px; margin-bottom: 3px; }
  .field span { font-size: 15px; color: #111; }
  .message-box { background: #f9f9f9; border-radius: 6px; padding: 14px 18px; font-size: 14px; color: #333; margin-top: 8px; }
  .footer { background: #f4f4f4; padding: 18px 32px; font-size: 12px; color: #999; text-align: center; }
  .badge { display: inline-block; background: #173153; color: #fff; border-radius: 4px; padding: 3px 10px; font-size: 12px; margin-left: 8px; }
</style>
</head>
<body>
<div class="wrap">
  <div class="header">
    <h1>New Package Order Request</h1>
    <p>Submitted from arsdeveloper.co.uk/pricing</p>
  </div>
  <div class="body">
    <div class="plan-box">
      <h2>{{ $payload['plan'] ?? 'N/A' }} <span class="badge">{{ ucfirst(str_replace('_',' ', $payload['billing'] ?? '')) }}</span></h2>
      <p>
        @if(!empty($payload['price']) && $payload['price'] > 0)
          Price: <strong>GBP {{ number_format((float)$payload['price'], 2) }}</strong>
        @else
          Price: <strong>Custom / To be quoted</strong>
        @endif
      </p>
    </div>

    <div class="field">
      <label>Full Name</label>
      <span>{{ $payload['name'] ?? '—' }}</span>
    </div>
    <div class="field">
      <label>Email Address</label>
      <span><a href="mailto:{{ $payload['email'] ?? '' }}">{{ $payload['email'] ?? '—' }}</a></span>
    </div>
    <div class="field">
      <label>Phone</label>
      <span>{{ $payload['phone'] ?? '—' }}</span>
    </div>
    @if(!empty($payload['message']))
    <div class="field">
      <label>Additional Requirements</label>
      <div class="message-box">{{ $payload['message'] }}</div>
    </div>
    @endif
    <div class="field" style="margin-top:20px;">
      <label>Submitted At</label>
      <span>{{ now()->format('d M Y, H:i') }} (UK time)</span>
    </div>
  </div>
  <div class="footer">ARS Developer — arsdeveloper.co.uk</div>
</div>
</body>
</html>
