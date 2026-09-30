<?php

namespace Tests\Feature;

use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShowServiceJsonTest extends TestCase
{
    use RefreshDatabase;

    private function service(array $overrides = []): Service
    {
        return Service::create(array_merge([
            'name' => 'SELFIE',
            'description' => "1 pax\n10 minutes unlimited self shoot",
            'price' => 399.00,
            'duration_minutes' => 10,
            'max_pax' => 1,
            'image' => 'https://images.unsplash.com/photo-1531746020798-e6953c6e8e04?w=400&h=300&fit=crop',
            'is_active' => true,
        ], $overrides));
    }

    public function test_price_is_a_json_number_not_a_string(): void
    {
        $service = $this->service();

        $response = $this->getJson(route('booking.service', $service));

        $response->assertOk();

        $body = json_decode($response->getContent(), true);

        // JSON has no int/float distinction: 399.0 encodes as 399 and decodes
        // as an int. What matters is that it is a number, not a quoted string.
        $this->assertFalse(is_string($body['price']), 'price must not be a JSON string.');
        $this->assertTrue(is_int($body['price']) || is_float($body['price']));
        $this->assertSame(399, $body['price']);
    }

    public function test_the_raw_payload_contains_an_unquoted_price(): void
    {
        $service = $this->service();

        $raw = $this->getJson(route('booking.service', $service))->getContent();

        $this->assertStringContainsString('"price":399', $raw, 'price should serialise unquoted.');
        $this->assertStringNotContainsString('"price":"', $raw, 'price must not be a quoted string.');
    }

    public function test_the_other_fields_keep_their_shape(): void
    {
        $service = $this->service();

        $body = json_decode($this->getJson(route('booking.service', $service))->getContent(), true);

        $this->assertSame($service->id, $body['id']);
        $this->assertSame('SELFIE', $body['name']);
        $this->assertIsInt($body['max_pax']);
        $this->assertSame('10 minutes', $body['duration']);
        $this->assertStringContainsString('unlimited self shoot', $body['description']);
        $this->assertStringContainsString('unsplash.com', $body['image']);
    }

    public function test_fractional_and_rounding_sensitive_prices_serialise_cleanly(): void
    {
        $service = $this->service(['price' => 1234.50]);

        $raw = $this->getJson(route('booking.service', $service))->getContent();

        $this->assertStringContainsString('"price":1234.5', $raw);

        $body = json_decode($raw, true);
        $this->assertSame(1234.5, $body['price']);
    }

    public function test_a_whole_number_price_stays_numeric(): void
    {
        $service = $this->service(['price' => 400]);

        $raw = $this->getJson(route('booking.service', $service))->getContent();
        $body = json_decode($raw, true);

        $this->assertFalse(is_string($body['price']));
        $this->assertSame(400, $body['price']);
        $this->assertStringContainsString('"price":400', $raw);
    }
}
