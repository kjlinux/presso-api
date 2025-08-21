<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Transaction;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = User::create([
            'name' => 'Marie Diallo',
            'pressing_name' => 'Pressing Excellence',
            'phone_number' => '0123456789',
            'country_code' => '+225',
            'country_name' => 'Côte d\'Ivoire',
            'pin_code' => '1234',
        ]);

        $transactions = [
            [
                'user_id' => $user->id,
                'customer_name' => 'Amadou Sy',
                'amount' => 2500,
                'quantity' => 5,
                'category' => 'lavage-simple',
                'payment_method' => 'cash',
                'inventory' => [
                    [
                        'id' => 1,
                        'quantity' => 3,
                        'clothingType' => 'chemise',
                        'color' => 'blanc'
                    ],
                    [
                        'id' => 2,
                        'quantity' => 2,
                        'clothingType' => 'pantalon',
                        'color' => 'noir'
                    ]
                ],
                'notes' => 'Client régulier',
                'status' => 'en-cours',
                'created_at' => now()->subDays(1),
            ],
            [
                'user_id' => $user->id,
                'customer_name' => 'Fatou Ba',
                'amount' => 3000,
                'quantity' => 3,
                'category' => 'lavage-repassage',
                'payment_method' => 'orange-money',
                'inventory' => [
                    [
                        'id' => 1,
                        'quantity' => 2,
                        'clothingType' => 'veste',
                        'color' => 'bleu'
                    ],
                    [
                        'id' => 2,
                        'quantity' => 1,
                        'clothingType' => 'chemise',
                        'color' => 'blanc'
                    ]
                ],
                'status' => 'termine',
                'created_at' => now()->subDays(2),
            ],
            [
                'user_id' => $user->id,
                'customer_name' => 'Ibrahima Kane',
                'amount' => 4500,
                'quantity' => 3,
                'category' => 'nettoyage-sec',
                'payment_method' => 'wave',
                'inventory' => [
                    [
                        'id' => 1,
                        'quantity' => 1,
                        'clothingType' => 'costume',
                        'color' => 'noir'
                    ],
                    [
                        'id' => 2,
                        'quantity' => 2,
                        'clothingType' => 'chemise',
                        'color' => 'blanc'
                    ]
                ],
                'notes' => 'Livraison urgente demandée',
                'status' => 'recupere',
                'created_at' => now()->subDays(3),
            ],
            [
                'user_id' => $user->id,
                'customer_name' => 'Aissatou Diop',
                'amount' => 1500,
                'quantity' => 5,
                'category' => 'repassage-seul',
                'payment_method' => 'cash',
                'inventory' => [
                    [
                        'id' => 1,
                        'quantity' => 5,
                        'clothingType' => 't-shirt',
                        'color' => 'rouge'
                    ]
                ],
                'status' => 'en-attente',
                'created_at' => now(),
            ]
        ];

        foreach ($transactions as $transactionData) {
            Transaction::create($transactionData);
        }

        $this->command->info('Base de données initialisée avec succès !');
        $this->command->info('Utilisateur de test :');
        $this->command->info('Téléphone : +225 0123456789');
        $this->command->info('PIN : 1234');
    }
}