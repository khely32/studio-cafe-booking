<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Addon;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('addons', function (Blueprint $table) {
            $table->string('category')->nullable()->after('description');
            $table->unsignedInteger('sort_order')->default(0)->after('price');
        });

        $menu = [
            ['name' => 'Adult (7 y/o up)', 'description' => null, 'price' => 249, 'category' => 'Extra person', 'sort_order' => 1],
            ['name' => 'Kids (6 y/o below)', 'description' => null, 'price' => 199, 'category' => 'Extra person', 'sort_order' => 2],
            ['name' => 'Infant (5 months and below)', 'description' => null, 'price' => 0, 'category' => 'Extra person', 'sort_order' => 3],
            ['name' => '1 Pet', 'description' => null, 'price' => 0, 'category' => 'Pets', 'sort_order' => 4],
            ['name' => 'Extra pet', 'description' => null, 'price' => 199, 'category' => 'Pets', 'sort_order' => 5],
            ['name' => '10 minutes', 'description' => null, 'price' => 249, 'category' => 'Extend time', 'sort_order' => 6],
            ['name' => 'Additional Backdrop', 'description' => 'Beige, tropical green, Chocolate brown, black and White', 'price' => 199, 'category' => null, 'sort_order' => 7],
            ['name' => '4R', 'description' => null, 'price' => 80, 'category' => 'Printed copy', 'sort_order' => 8],
            ['name' => '5R', 'description' => null, 'price' => 110, 'category' => 'Printed copy', 'sort_order' => 9],
            ['name' => 'Photo card 2 pcs', 'description' => null, 'price' => 100, 'category' => 'Printed copy', 'sort_order' => 10],
            ['name' => 'Photo strip 2 pcs', 'description' => null, 'price' => 100, 'category' => 'Printed copy', 'sort_order' => 11],
            ['name' => 'A4', 'description' => null, 'price' => 150, 'category' => 'Printed copy', 'sort_order' => 12],
            ['name' => 'A4 with frame', 'description' => 'black, wood & white', 'price' => 380, 'category' => 'Printed copy', 'sort_order' => 13],
            ['name' => 'Number Balloon', 'description' => '2ft cream caramel in color', 'price' => 50, 'category' => null, 'sort_order' => 14],
            ['name' => 'Fake Cake', 'description' => null, 'price' => 60, 'category' => null, 'sort_order' => 15],
            ['name' => '10 minutes', 'description' => null, 'price' => 500, 'category' => "Photographer's Fee", 'sort_order' => 16],
            ['name' => '20 minutes', 'description' => null, 'price' => 700, 'category' => "Photographer's Fee", 'sort_order' => 17],
            ['name' => '30 minutes', 'description' => null, 'price' => 900, 'category' => "Photographer's Fee", 'sort_order' => 18],
            ['name' => '1 hour', 'description' => null, 'price' => 1250, 'category' => "Photographer's Fee", 'sort_order' => 19],
            ['name' => 'Hair & Make up Artist', 'description' => null, 'price' => 1800, 'category' => null, 'sort_order' => 20],
        ];

        $newNames = array_column($menu, 'name');

        Addon::whereNotIn('name', $newNames)->delete();

        foreach ($menu as $item) {
            Addon::updateOrCreate(
                ['name' => $item['name']],
                $item + ['is_active' => true]
            );
        }
    }

    public function down(): void
    {
        Schema::table('addons', function (Blueprint $table) {
            $table->dropColumn(['category', 'sort_order']);
        });
    }
};
