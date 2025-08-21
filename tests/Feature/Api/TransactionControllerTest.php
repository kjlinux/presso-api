<?php

use App\Models\User;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;

uses(RefreshDatabase::class);

describe('TransactionController@index', function () {
    beforeEach(function () {
        $this->user = User::create([
            'id' => Str::uuid(),
            'name' => 'Test User',
            'pressing_name' => 'Test Pressing',
            'phone_number' => '+2250707123456',
            'country_code' => '+225',
            'country_name' => 'Côte d\'Ivoire',
            'pin_code' => '1234',
        ]);
        $this->actingAs($this->user);
    });

    test('peut récupérer les transactions de l\'utilisateur authentifié', function () {
        for ($i = 0; $i < 3; $i++) {
            Transaction::create([
                'id' => Str::uuid(),
                'user_id' => $this->user->id,
                'customer_name' => "Client $i",
                'amount' => 1000 + ($i * 500),
                'quantity' => $i + 1,
                'category' => 'lavage-simple',
                'payment_method' => 'cash',
                'inventory' => null,
                'notes' => "Note $i",
                'status' => 'en-attente',
            ]);
        }

        $otherUser = User::create([
            'id' => Str::uuid(),
            'name' => 'Other User',
            'pressing_name' => 'Other Pressing',
            'phone_number' => '+2250748654321',
            'country_code' => '+225',
            'country_name' => 'Côte d\'Ivoire',
            'pin_code' => '5678',
        ]);

        for ($i = 0; $i < 2; $i++) {
            Transaction::create([
                'id' => Str::uuid(),
                'user_id' => $otherUser->id,
                'customer_name' => "Other Client $i",
                'amount' => 2000,
                'quantity' => 1,
                'category' => 'nettoyage-sec',
                'payment_method' => 'wave',
                'inventory' => null,
                'notes' => null,
                'status' => 'termine',
            ]);
        }

        $response = $this->getJson('/api/transactions');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'transactions',
                    'pagination' => [
                        'current_page',
                        'last_page',
                        'per_page',
                        'total'
                    ],
                    'summary' => [
                        'total_revenue',
                        'total_quantity',
                        'count'
                    ]
                ]
            ])
            ->assertJson([
                'success' => true
            ]);

        expect($response->json('data.transactions'))->toHaveCount(3);
        expect($response->json('data.pagination.total'))->toBe(3);
    });
});

describe('TransactionController@store', function () {
    beforeEach(function () {
        $this->user = User::create([
            'id' => Str::uuid(),
            'name' => 'Test User',
            'pressing_name' => 'Test Pressing',
            'phone_number' => '+2250707123456',
            'country_code' => '+225',
            'country_name' => 'Côte d\'Ivoire',
            'pin_code' => '1234',
        ]);
        $this->actingAs($this->user);

        $this->validData = [
            'customer_name' => 'Jean Dupont',
            'amount' => 1500.50,
            'quantity' => 3,
            'category' => 'lavage-simple',
            'payment_method' => 'cash',
            'inventory' => [
                [
                    'id' => 1,
                    'quantity' => 2,
                    'clothingType' => 'pantalon',
                    'color' => 'noir'
                ]
            ],
            'notes' => 'Vêtements délicats',
            'status' => 'en-attente'
        ];
    });

    test('peut créer une nouvelle transaction avec des données valides', function () {
        $response = $this->postJson('/api/transactions', $this->validData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'transaction'
                ]
            ])
            ->assertJson([
                'success' => true,
                'message' => 'Transaction enregistrée avec succès'
            ]);

        $this->assertDatabaseHas('transactions', [
            'user_id' => $this->user->id,
            'customer_name' => 'Jean Dupont',
            'amount' => 1500.50,
            'quantity' => 3,
            'category' => 'lavage-simple',
            'payment_method' => 'cash',
            'notes' => 'Vêtements délicats',
            'status' => 'en-attente'
        ]);
    });
});
