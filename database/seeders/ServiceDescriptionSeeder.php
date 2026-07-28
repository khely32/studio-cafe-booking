<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;

class ServiceDescriptionSeeder extends Seeder
{
    public function run(): void
    {
        Service::where('name', 'Maternity Photoshoot Package')->update(['description' => '1-hour studio photoshoot with professional photographer
2 backdrop setups
2 Spotlight lighting effects
2-3 outfits (provided by client)
FREE use of studio dress (brown or black, size Small-Medium)
FREE prints: 2 pieces of 5R or 1 A4 size
Enhanced soft copies delivered via Google Drive
Editing turnaround: 3 days

P.S. For additional props during the photoshoot, kindly bring your babys ultrasound photo, shoes, or a piece of clothing.

For your comfort and to achieve a smoother look in photos, we also recommend wearing a nipple cover/tape or a skin-tone strapless bra during the session.']);

        Service::where('name', 'Boho Beige Themed Pre-Birthday Shoot')->update(['description' => 'Boho Beige themed
45 minutes session
Unlimited clicks with photographer
Complimentary few shots with family
All enhanced soft copies
3 days editing time frame
1 printed A4 photo

*Suggested color of outfit
Cream, ivory, or off-white
Beige, camel, or tan
Warm browns (chocolate, chestnut, rust)
Olive or sage green']);

        Service::where('name', 'Sunset Garden Themed Pre-Birthday Shoot')->update(['description' => 'Sunset Floral theme set-up with White, wood and basket chairs
45 minutes session
Unlimited clicks with photographer
Complimentary few shots with family
All enhanced soft copies
3 days editing time frame
1 printed A4 photo

*Suggested color of outfit
Soft Peach / Coral
Warm Orange / Tangerine
Golden Yellow / Mustard
Cream / Ivory / Off-White florals
Terracotta / Rust
Olive Green / Sage']);

        Service::where('name', 'FEELS LIKE HOME Self Shoot')->update(['description' => 'Inspired by slow living and cozy spaces, this setup is perfect for capturing families, couples, and friends in a warm, relaxed atmosphere.

Good for 1 to 4 pax
35 minutes unlimited shoot
Whole body and portrait shots
All enhanced soft copies
1 to 2 days editing timeframe
Photos delivered via Google Drive
1 printed A4 photo']);

        Service::where('name', 'FEELS LIKE HOME Session with Photographer')->update(['description' => 'Inspired by slow living and cozy spaces, this setup is perfect for capturing families, couples, and friends in a warm, relaxed atmosphere.

Good for 1 to 4 pax
35 minutes unlimited shoot
Whole body, focus, landscape and portrait shots
All enhanced soft copies
3 days editing timeframe
Photos delivered via Google Drive
1 printed A4 photo

*Suggested color of outfit
Soft Neutrals (best choice): Cream, Ivory, Beige, Oatmeal, Soft white
Muted Greens: Sage, Olive, Eucalyptus
Warm Earth Tones: Camel, Light brown, Mocha, Taupe']);

        Service::where('name', 'Safari Themed Pre-Birthday Photoshoot')->update(['description' => '1 hour session with our professional photographer
Complimentary few shots with family
All enhanced soft copies
3 days editing time frame
1 printed A4 photo

*suggested color of outfit
Khaki / Beige / Sand
Brown / Tan / Camel
Olive or Sage Green
Soft Mustard']);

        Service::where('name', 'Graduation Photoshoot Package')->update(['description' => 'What is Included:
20 minutes session with our professional photographer
Black backdrop with illusion effect
Complete toga set (toga, hood, and cap)
Hood color options: Yellow, Green, Sky Blue, or LPU Academic Hood
Creative shots
Up to 2 outfit changes (with 5-minute pause time)
Unlimited shots
All photos professionally enhanced
1pc 5R photo print
Soft copies delivered via Google Drive within 3 days

FREE BONUS: group shots with your peers
Valid for 3 persons or more']);

        Service::where('name', 'Birthday Glow-Up Session')->update(['description' => '1 pax
30-minutes photoshoot with photographer
1 backdrop color of your choice
Free use of props
Spotlight lighting effect for a dramatic studio look
Guided posing to bring out your best angles
1 5R printed copy
Get ALL soft copies via google drive (enhanced)

Perfect for:
- Pre-birthday photos
- Birthday invitations
- Social media countdown posts
- Personal milestone portraits']);

        Service::where('name', 'Studio Rent')->update(['description' => 'Air-conditioned studio
Inclusions:
- Light set-up (1 steady soft ball light)
- Free use of chairs and studio props
- 1 backdrop of your choice: Tropical Green, Chocolate Brown, White, Beige, Black

Studio Hours
Weekdays: 10:00 AM - 5:00 PM
Weekends: 9:00 AM - 12:00 NN

Bring your own camera
Perfect for portraits, small product shoots, and creative sessions.']);
    }
}
