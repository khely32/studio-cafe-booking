<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\Addon;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            [
                'name' => 'SELFIE',
                'description' => "1 pax\n10 minutes unlimited self shoot\n1 Backdrop color of your choice\nFree use of props\nGet ALL soft copies via google drive (enhanced)",
                'price' => 399,
                'duration_minutes' => 10,
                'max_pax' => 1,
                'sort_order' => 1,
            ],
            [
                'name' => 'DUO',
                'description' => "1 to 2 pax\n20 minutes unlimited self shoot\n1 backdrop color of your choice\nFree use of props\n1 4R printed copy\nGet ALL soft copies via google drive (enhanced)",
                'price' => 699,
                'duration_minutes' => 20,
                'max_pax' => 2,
                'sort_order' => 2,
            ],
            [
                'name' => 'BONDING',
                'description' => "1 to 4 pax\n30 minutes unlimited self shoot\n1 backdrop color of your choice\nGet ALL soft copies via google drive (enhanced)\nFree use of props\n2 4R photo print",
                'price' => 1149,
                'duration_minutes' => 30,
                'max_pax' => 4,
                'sort_order' => 3,
            ],
            [
                'name' => 'PARTY',
                'description' => "1 to 7 pax\n1 hour unlimited self shoot\n2 backdrop colors of your choice\nGet ALL soft copies via google drive (enhanced)\nFree use of props\n2 5R or 1 A4 photo prints",
                'price' => 1649,
                'duration_minutes' => 60,
                'max_pax' => 7,
                'sort_order' => 4,
            ],
            [
                'name' => 'Maternity Photoshoot Package',
                'description' => "1-hour studio photoshoot with professional photographer\n2 backdrop setups\n2 Spotlight lighting effects\n2-3 outfits (provided by client)\nFREE use of studio dress (brown or black, size Small-Medium)\nFREE prints: 2 pieces of 5R or 1 A4 size\nEnhanced soft copies delivered via Google Drive\nEditing turnaround: 3 days",
                'price' => 2499,
                'duration_minutes' => 60,
                'max_pax' => 2,
                'sort_order' => 5,
            ],
            [
                'name' => 'Boho Beige Themed Pre-Birthday Shoot',
                'description' => "Boho Beige themed\n45 minutes session\nUnlimited clicks with photographer\nComplimentary few shots with family\nAll enhanced soft copies\n3 days editing time frame\n1 printed A4 photo\n\nSuggested outfit colors: Cream, ivory, off-white, beige, camel, tan, warm browns, olive or sage green",
                'price' => 2999,
                'duration_minutes' => 45,
                'max_pax' => 4,
                'sort_order' => 6,
            ],
            [
                'name' => 'Sunset Garden Themed Pre-Birthday Shoot',
                'description' => "Sunset Floral theme set-up with White, wood and basket chairs\n45 minutes session\nUnlimited clicks with photographer\nComplimentary few shots with family\nAll enhanced soft copies\n3 days editing time frame\n1 printed A4 photo\n\nSuggested outfit colors: Soft Peach, Coral, Warm Orange, Golden Yellow, Cream, Ivory, Terracotta, Olive Green",
                'price' => 2999,
                'duration_minutes' => 45,
                'max_pax' => 4,
                'sort_order' => 7,
            ],
            [
                'name' => 'FEELS LIKE HOME Self Shoot',
                'description' => "Inspired by slow living and cozy spaces, perfect for families, couples, and friends.\n\nGood for 1 to 4 pax\n35 minutes unlimited shoot\nWhole body and portrait shots\nAll enhanced soft copies\n1 to 2 days editing timeframe\nPhotos delivered via Google Drive\n1 printed A4 photo",
                'price' => 1499,
                'duration_minutes' => 35,
                'max_pax' => 4,
                'sort_order' => 8,
            ],
            [
                'name' => 'FEELS LIKE HOME Session with Photographer',
                'description' => "Inspired by slow living and cozy spaces, perfect for families, couples, and friends.\n\nGood for 1 to 4 pax\n35 minutes unlimited shoot\nWhole body, focus, landscape and portrait shots\nAll enhanced soft copies\n3 days editing timeframe\nPhotos delivered via Google Drive\n1 printed A4 photo\n\nSuggested outfit: Soft Neutrals (Cream, Ivory, Beige), Muted Greens (Sage, Olive), Warm Earth Tones (Camel, Light brown)",
                'price' => 2499,
                'duration_minutes' => 35,
                'max_pax' => 4,
                'sort_order' => 9,
            ],
            [
                'name' => 'Safari Themed Pre-Birthday Photoshoot',
                'description' => "1 hour session with our professional photographer\nComplimentary few shots with family\nAll enhanced soft copies\n3 days editing time frame\n1 printed A4 photo\n\nSuggested outfit colors: Khaki, Beige, Sand, Brown, Tan, Camel, Olive or Sage Green, Soft Mustard",
                'price' => 3499,
                'duration_minutes' => 60,
                'max_pax' => 4,
                'sort_order' => 10,
            ],
            [
                'name' => 'Graduation Photoshoot Package',
                'description' => "20 minutes session with our professional photographer\nBlack backdrop with illusion effect\nComplete toga set (toga, hood, and cap)\nHood color options: Yellow, Green, Sky Blue, or LPU Academic Hood\nCreative shots\nUp to 2 outfit changes (with 5-minute pause time)\nUnlimited shots\nAll photos professionally enhanced\n1pc 5R photo print\nSoft copies delivered via Google Drive within 3 days\n\nFREE BONUS: Group shots with your peers (valid for 3 persons or more)",
                'price' => 1299,
                'duration_minutes' => 20,
                'max_pax' => 5,
                'sort_order' => 11,
            ],
            [
                'name' => 'Birthday Glow-Up Session',
                'description' => "1 pax\n30-minutes photoshoot with photographer\n1 backdrop color of your choice\nFree use of props\nSpotlight lighting effect for a dramatic studio look\nGuided posing to bring out your best angles\n1 5R printed copy\nGet ALL soft copies via google drive (enhanced)\n\nPerfect for: Pre-birthday photos, Birthday invitations, Social media countdown posts, Personal milestone portraits",
                'price' => 1999,
                'duration_minutes' => 30,
                'max_pax' => 1,
                'sort_order' => 12,
            ],
            [
                'name' => 'Studio Rent',
                'description' => "Air-conditioned studio\n\nInclusions:\n- Light set-up (1 steady soft ball light)\n- Free use of chairs and studio props\n- 1 backdrop of your choice: Tropical Green, Chocolate Brown, White, Beige, Black\n\nStudio Hours:\nWeekdays: 10:00 AM - 5:00 PM\nWeekends: 9:00 AM - 12:00 NN\n\nBring your own camera. Perfect for portraits, small product shoots, and creative sessions.",
                'price' => 700,
                'duration_minutes' => 60,
                'max_pax' => 7,
                'sort_order' => 13,
            ],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }

        $addons = [
            ['name' => 'Extra Person (Adult 7y/o+)', 'description' => 'Additional adult participant', 'price' => 249],
            ['name' => 'Extra Person (Kids 6y/o below)', 'description' => 'Additional child participant', 'price' => 199],
            ['name' => 'Extra Pet', 'description' => 'Additional pet (1st pet is free)', 'price' => 199],
            ['name' => 'Extended Time (10 min)', 'description' => 'Additional 10 minutes', 'price' => 249],
            ['name' => 'Additional Backdrop', 'description' => 'Extra backdrop color change', 'price' => 199],
            ['name' => 'Printed Copy 4R', 'description' => '4R photo print', 'price' => 80],
            ['name' => 'Printed Copy 5R', 'description' => '5R photo print', 'price' => 110],
            ['name' => 'Photo Card (2 pcs)', 'description' => 'Photo card prints', 'price' => 100],
            ['name' => 'Photo Strip (2 pcs)', 'description' => 'Photo strip prints', 'price' => 100],
            ['name' => 'Printed Copy A4', 'description' => 'A4 photo print', 'price' => 150],
            ['name' => 'A4 with Frame', 'description' => 'A4 photo with frame (black, wood, or white)', 'price' => 380],
            ['name' => 'Number Balloon', 'description' => '2ft cream caramel colored balloon', 'price' => 50],
            ['name' => 'Fake Cake', 'description' => 'Decorative fake cake prop', 'price' => 60],
            ['name' => 'Photographer (10 min)', 'description' => 'Professional photographer for 10 minutes', 'price' => 500],
            ['name' => 'Photographer (20 min)', 'description' => 'Professional photographer for 20 minutes', 'price' => 700],
            ['name' => 'Photographer (30 min)', 'description' => 'Professional photographer for 30 minutes', 'price' => 900],
            ['name' => 'Photographer (1 hour)', 'description' => 'Professional photographer for 1 hour', 'price' => 1250],
            ['name' => 'Hair & Makeup Artist', 'description' => 'Professional HMU service', 'price' => 1800],
        ];

        foreach ($addons as $addon) {
            Addon::create($addon);
        }
    }
}
