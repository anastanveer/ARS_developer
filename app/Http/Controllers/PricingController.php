<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\CouponRedemption;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class PricingController extends Controller
{
    public function index(): View
    {
        $liveCoupons = collect();

        if (Schema::hasTable('coupons')) {
            $liveCoupons = Coupon::query()
                ->where('is_active', true)
                ->where(function ($query) {
                    $query->whereNull('expires_at')
                        ->orWhere('expires_at', '>', now());
                })
                ->where(function ($query) {
                    $query->whereNull('usage_limit')
                        ->orWhereColumn('used_count', '<', 'usage_limit');
                })
                ->orderByRaw('CASE WHEN expires_at IS NULL THEN 1 ELSE 0 END')
                ->orderBy('expires_at')
                ->orderByDesc('id')
                ->limit(10)
                ->get([
                    'id',
                    'code',
                    'title',
                    'discount_type',
                    'discount_value',
                    'currency',
                    'usage_limit',
                    'used_count',
                    'expires_at',
                ]);
        }

        return view('pages.pricing', [
            'liveCoupons' => $this->formatLiveCoupons($liveCoupons),
        ]);
    }

    public function previewCoupon(Request $request): JsonResponse
    {
        $data = $request->validate([
            'code' => ['required', 'string', 'max:40'],
            'plan_price' => ['required', 'numeric', 'min:0'],
            'billing' => ['nullable', 'in:subscription,one_time'],
            'email' => ['nullable', 'email', 'max:180'],
        ]);

        $coupon = Coupon::query()
            ->whereRaw('UPPER(code) = ?', [strtoupper(trim((string) $data['code']))])
            ->first();

        if (!$coupon || !$coupon->is_active) {
            return response()->json([
                'ok' => true,
                'valid' => false,
                'message' => 'Coupon not found or inactive.',
            ]);
        }

        if ($coupon->expires_at && $coupon->expires_at->isPast()) {
            return response()->json([
                'ok' => true,
                'valid' => false,
                'message' => 'Coupon has expired.',
            ]);
        }

        if (!is_null($coupon->usage_limit) && (int) $coupon->used_count >= (int) $coupon->usage_limit) {
            return response()->json([
                'ok' => true,
                'valid' => false,
                'message' => 'Coupon usage limit reached.',
            ]);
        }

        $email = trim((string) ($data['email'] ?? ''));
        if ($email !== '' && class_exists(CouponRedemption::class)) {
            $alreadyRedeemed = CouponRedemption::query()
                ->whereRaw('LOWER(email) = ?', [strtolower($email)])
                ->exists();

            if ($alreadyRedeemed) {
                return response()->json([
                    'ok' => true,
                    'valid' => false,
                    'message' => 'Coupon already used on this client email.',
                ]);
            }
        }

        $basePrice = (float) $data['plan_price'];
        $rawDiscount = 0.0;
        if ($coupon->discount_type === 'percent') {
            $rawDiscount = $basePrice * ((float) $coupon->discount_value / 100);
        } else {
            $rawDiscount = (float) $coupon->discount_value;
        }
        $discountWasCapped = $rawDiscount > $basePrice;
        $discount = $rawDiscount;
        $discount = max(0.0, min($basePrice, $discount));
        $finalPrice = max(0.0, $basePrice - $discount);

        return response()->json([
            'ok' => true,
            'valid' => true,
            'code' => strtoupper((string) $coupon->code),
            'title' => (string) ($coupon->title ?: 'Offer'),
            'discount_type' => (string) $coupon->discount_type,
            'discount_value' => (float) $coupon->discount_value,
            'base_price' => round($basePrice, 2),
            'raw_discount_amount' => round($rawDiscount, 2),
            'discount_amount' => round($discount, 2),
            'final_price' => round($finalPrice, 2),
            'discount_capped' => $discountWasCapped,
            'currency' => (string) ($coupon->currency ?: 'GBP'),
            'billing_note' => ($data['billing'] ?? '') === 'subscription'
                ? 'Discount applies on first invoice unless agreed otherwise.'
                : 'Discount applies on project kickoff invoice.',
            'message' => $discountWasCapped
                ? 'Coupon value was adjusted to the selected package amount.'
                : 'Coupon applied successfully.',
        ]);
    }

    private function formatLiveCoupons(Collection $coupons): Collection
    {
        return $coupons->map(function (Coupon $coupon) {
            $code = strtoupper((string) $coupon->code);
            $title = trim((string) ($coupon->title ?: 'Limited Offer'));
            $currency = strtoupper((string) ($coupon->currency ?: 'GBP'));
            $discountValue = (float) $coupon->discount_value;

            $discountLabel = $coupon->discount_type === 'percent'
                ? rtrim(rtrim(number_format($discountValue, 2, '.', ''), '0'), '.') . '% OFF'
                : 'Up to ' . $currency . ' ' . number_format($discountValue, 2) . ' OFF';

            $remainingUses = null;
            if (!is_null($coupon->usage_limit)) {
                $remainingUses = max(0, (int) $coupon->usage_limit - (int) $coupon->used_count);
            }

            return [
                'code' => $code,
                'title' => $title,
                'discount_label' => $discountLabel,
                'expires_label' => $coupon->expires_at ? $coupon->expires_at->format('d M Y') : 'No expiry',
                'remaining_uses' => $remainingUses,
            ];
        });
    }
}
