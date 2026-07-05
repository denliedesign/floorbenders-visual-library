<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Enums\Aspect;
use App\Enums\Difficulty;
use App\Enums\Gate;
use App\Enums\MovementStatus;
use App\Enums\Realm;
use App\Models\Movement;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MovementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sortOrder = 1;

        foreach (Gate::cases() as $gate) {
            foreach (Aspect::cases() as $aspect) {
                foreach (Realm::cases() as $realm) {
                    $title = "{$gate->label()} {$aspect->label()} {$realm->label()}";

                    Movement::updateOrCreate(
                        [
                            'gate' => $gate->value,
                            'aspect' => $aspect->value,
                            'realm' => $realm->value,
                        ],
                        [
                            'title' => $title,
                            'slug' => Str::slug($title),
                            'difficulty' => Difficulty::Beginner->value,
                            'status' => MovementStatus::Draft->value,
                            'sort_order' => $sortOrder++,
                        ]
                    );
                }
            }
        }
    }
}
