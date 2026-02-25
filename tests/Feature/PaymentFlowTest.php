<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class PaymentFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware(VerifyCsrfToken::class);
        Mail::fake();

        config()->set('services.stripe.key', 'pk_test_ars');
        config()->set('services.stripe.secret', 'sk_test_ars');
        config()->set('services.stripe.webhook_secret', 'whsec_test_ars');
    }

    public function test_direct_order_contact_submit_returns_checkout_url_and_sanitizes_project_type(): void
    {
        Http::fake([
            'https://api.stripe.com/v1/checkout/sessions' => Http::response([
                'id' => 'cs_direct_1',
                'url' => 'https://checkout.stripe.com/c/pay/cs_direct_1',
            ], 200),
        ]);

        $response = $this->postJson(route('contact.submit'), [
            'form_type' => 'pricing_order',
            'name' => 'Anas Tanveer',
            'email' => 'anas@example.com',
            'phone' => '+44747803428',
            'company' => 'ARS Developer Ltd',
            'project_type' => 'Ecommerce Scale (Subscription) - GBP 999.00 | Coupon FIRST20 Applied',
            'message' => 'Please start immediately with kickoff plan.',
            'selected_plan_price' => 999,
            'final_quote_preview' => 799.20,
            'payment_intent' => 'kickoff_payment',
            'start_order_payment' => true,
            'coupon_code' => 'FIRST20',
            'coupon_discount' => 199.80,
        ]);

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('redirect_url', 'https://checkout.stripe.com/c/pay/cs_direct_1');

        $project = Project::query()->firstOrFail();
        $invoice = Invoice::query()->firstOrFail();

        $this->assertSame('Ecommerce Scale (Subscription)', $project->type);
        $this->assertTrue(strlen((string) $project->type) <= 80);
        $this->assertNotEmpty($project->portal_token);
        $this->assertNotEmpty($invoice->public_token);
        $this->assertSame((float) 799.20, (float) $invoice->amount);
        Http::assertSentCount(1);
    }

    public function test_direct_order_returns_503_when_stripe_checkout_cannot_be_created(): void
    {
        Http::fake([
            'https://api.stripe.com/v1/checkout/sessions' => Http::response([
                'error' => 'stripe unavailable',
            ], 500),
        ]);

        $response = $this->postJson(route('contact.submit'), [
            'form_type' => 'pricing_order',
            'name' => 'Client One',
            'email' => 'client1@example.com',
            'project_type' => 'Website Care Plan - GBP 236 | Coupon FIRST5 Applied',
            'message' => 'start order',
            'selected_plan_price' => 236,
            'payment_intent' => 'kickoff_payment',
            'start_order_payment' => true,
        ]);

        $response->assertStatus(503)
            ->assertJsonPath('success', false);
    }

    public function test_client_portal_pay_redirects_to_stripe_checkout_url(): void
    {
        [$project, $invoice] = $this->createProjectAndInvoice([
            'amount' => 8000,
            'paid_amount' => 0,
            'status' => 'unpaid',
        ]);

        Http::fake([
            'https://api.stripe.com/v1/checkout/sessions' => Http::response([
                'id' => 'cs_portal_1',
                'url' => 'https://checkout.stripe.com/c/pay/cs_portal_1',
            ], 200),
        ]);

        $response = $this->post(route('client.portal.pay', ['token' => $project->portal_token]), [
            'invoice_id' => $invoice->id,
            'amount' => 1200,
            'method' => 'Portal Payment',
            'reference' => 'REF-PORTAL-1',
        ]);

        $response->assertRedirect('https://checkout.stripe.com/c/pay/cs_portal_1');
        $this->assertDatabaseCount('payments', 0);
    }

    public function test_client_portal_success_records_payment_once_only(): void
    {
        [$project, $invoice] = $this->createProjectAndInvoice([
            'amount' => 2500,
            'paid_amount' => 0,
            'status' => 'unpaid',
        ]);

        Http::fake([
            'https://api.stripe.com/v1/checkout/sessions/cs_portal_paid_1*' => Http::response([
                'id' => 'cs_portal_paid_1',
                'payment_status' => 'paid',
                'amount_total' => 250000,
                'payment_intent' => ['id' => 'pi_portal_1'],
                'metadata' => [
                    'project_id' => (string) $project->id,
                    'invoice_id' => (string) $invoice->id,
                    'portal_token' => (string) $project->portal_token,
                    'user_reference' => 'PORTAL-SUCCESS-1',
                ],
            ], 200),
        ]);

        $this->get(route('client.portal.pay.success', [
            'token' => $project->portal_token,
            'session_id' => 'cs_portal_paid_1',
        ]))->assertRedirect(route('client.portal', ['token' => $project->portal_token]));

        $this->assertDatabaseCount('payments', 1);
        $this->assertDatabaseHas('payments', [
            'project_id' => $project->id,
            'invoice_id' => $invoice->id,
            'reference' => 'cs_portal_paid_1',
        ]);

        $invoice->refresh();
        $project->refresh();
        $this->assertSame('paid', (string) $invoice->status);
        $this->assertSame(2500.0, (float) $invoice->paid_amount);
        $this->assertSame(2500.0, (float) $project->paid_total);

        $this->get(route('client.portal.pay.success', [
            'token' => $project->portal_token,
            'session_id' => 'cs_portal_paid_1',
        ]))->assertRedirect(route('client.portal', ['token' => $project->portal_token]));

        $this->assertDatabaseCount('payments', 1);
    }

    public function test_public_invoice_quick_pay_redirects_to_stripe_checkout(): void
    {
        [$project, $invoice] = $this->createProjectAndInvoice([
            'amount' => 900,
            'paid_amount' => 0,
            'status' => 'unpaid',
            'show_pay_button' => true,
        ]);

        Http::fake([
            'https://api.stripe.com/v1/checkout/sessions' => Http::response([
                'id' => 'cs_invoice_1',
                'url' => 'https://checkout.stripe.com/c/pay/cs_invoice_1',
            ], 200),
        ]);

        $response = $this->get(route('invoice.public.pay-now', ['token' => $invoice->public_token]));

        $response->assertRedirect('https://checkout.stripe.com/c/pay/cs_invoice_1');
    }

    public function test_public_invoice_success_records_payment_once_only(): void
    {
        [$project, $invoice] = $this->createProjectAndInvoice([
            'amount' => 1200,
            'paid_amount' => 0,
            'status' => 'unpaid',
            'show_pay_button' => true,
        ]);

        Http::fake([
            'https://api.stripe.com/v1/checkout/sessions/cs_invoice_paid_1*' => Http::response([
                'id' => 'cs_invoice_paid_1',
                'payment_status' => 'paid',
                'amount_total' => 120000,
                'payment_intent' => ['id' => 'pi_invoice_1'],
                'metadata' => [
                    'project_id' => (string) $project->id,
                    'invoice_id' => (string) $invoice->id,
                    'portal_token' => (string) $project->portal_token,
                    'invoice_public_token' => (string) $invoice->public_token,
                    'user_reference' => 'INVOICE-SUCCESS-1',
                ],
            ], 200),
        ]);

        $this->get(route('invoice.public.pay.success', [
            'token' => $invoice->public_token,
            'session_id' => 'cs_invoice_paid_1',
        ]))->assertRedirect(route('invoice.public.show', ['token' => $invoice->public_token]));

        $this->assertDatabaseCount('payments', 1);
        $this->assertDatabaseHas('payments', [
            'project_id' => $project->id,
            'invoice_id' => $invoice->id,
            'reference' => 'cs_invoice_paid_1',
        ]);

        $invoice->refresh();
        $project->refresh();
        $this->assertSame('paid', (string) $invoice->status);
        $this->assertSame(1200.0, (float) $invoice->paid_amount);
        $this->assertSame(1200.0, (float) $project->paid_total);

        $this->get(route('invoice.public.pay.success', [
            'token' => $invoice->public_token,
            'session_id' => 'cs_invoice_paid_1',
        ]))->assertRedirect(route('invoice.public.show', ['token' => $invoice->public_token]));

        $this->assertDatabaseCount('payments', 1);
    }

    public function test_stripe_webhook_rejects_invalid_signature(): void
    {
        $payload = json_encode([
            'type' => 'checkout.session.completed',
            'data' => [
                'object' => [
                    'id' => 'cs_invalid_1',
                    'metadata' => ['portal_token' => 'nope'],
                ],
            ],
        ], JSON_THROW_ON_ERROR);

        $response = $this->call(
            'POST',
            route('stripe.webhook'),
            [],
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_STRIPE_SIGNATURE' => 't=123,v1=bad_signature',
            ],
            $payload
        );

        $response->assertStatus(400)
            ->assertJsonPath('ok', false);
    }

    public function test_stripe_webhook_accepts_valid_signature_and_syncs_payment(): void
    {
        [$project, $invoice] = $this->createProjectAndInvoice([
            'amount' => 1500,
            'paid_amount' => 0,
            'status' => 'unpaid',
        ]);

        Http::fake([
            'https://api.stripe.com/v1/checkout/sessions/cs_webhook_1*' => Http::response([
                'id' => 'cs_webhook_1',
                'payment_status' => 'paid',
                'amount_total' => 150000,
                'payment_intent' => ['id' => 'pi_webhook_1'],
                'metadata' => [
                    'project_id' => (string) $project->id,
                    'invoice_id' => (string) $invoice->id,
                    'portal_token' => (string) $project->portal_token,
                    'user_reference' => 'WEBHOOK-1',
                ],
            ], 200),
        ]);

        $payload = json_encode([
            'type' => 'checkout.session.completed',
            'data' => [
                'object' => [
                    'id' => 'cs_webhook_1',
                    'metadata' => [
                        'portal_token' => $project->portal_token,
                    ],
                ],
            ],
        ], JSON_THROW_ON_ERROR);

        $timestamp = time();
        $signature = hash_hmac('sha256', $timestamp . '.' . $payload, 'whsec_test_ars');

        $response = $this->call(
            'POST',
            route('stripe.webhook'),
            [],
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_STRIPE_SIGNATURE' => 't=' . $timestamp . ',v1=' . $signature,
            ],
            $payload
        );

        $response->assertOk()->assertJsonPath('ok', true);

        $this->assertDatabaseHas('payments', [
            'project_id' => $project->id,
            'invoice_id' => $invoice->id,
            'reference' => 'cs_webhook_1',
        ]);

        $invoice->refresh();
        $project->refresh();
        $this->assertSame('paid', (string) $invoice->status);
        $this->assertSame(1500.0, (float) $invoice->paid_amount);
        $this->assertSame(1500.0, (float) $project->paid_total);
    }

    private function createProjectAndInvoice(array $invoiceOverrides = []): array
    {
        $client = Client::query()->create([
            'name' => 'Test Client',
            'email' => 'test+' . uniqid() . '@example.com',
            'phone' => '+447400000000',
            'company' => 'Test Co',
            'country' => 'UK',
        ]);

        $project = Project::query()->create([
            'client_id' => $client->id,
            'title' => 'Test Project',
            'type' => 'Website Project',
            'status' => 'planning',
            'start_date' => now()->toDateString(),
            'delivery_date' => now()->addMonth()->toDateString(),
            'delivery_months' => 1,
            'budget_total' => 0,
            'paid_total' => 0,
            'currency' => 'GBP',
            'portal_token' => 'portal_' . uniqid('', true),
            'description' => 'Test description',
        ]);

        $invoice = Invoice::query()->create(array_merge([
            'project_id' => $project->id,
            'invoice_number' => 'INV-T-' . strtoupper(substr(uniqid('', true), -8)),
            'invoice_date' => now()->toDateString(),
            'due_date' => now()->addWeek()->toDateString(),
            'amount' => 100,
            'paid_amount' => 0,
            'status' => 'unpaid',
            'notes' => 'Test invoice',
            'public_token' => 'pub_' . uniqid('', true),
            'show_pay_button' => true,
            'invoice_payload' => ['source' => 'test'],
        ], $invoiceOverrides));

        return [$project, $invoice];
    }
}
