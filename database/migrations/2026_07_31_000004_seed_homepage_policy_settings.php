<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\Setting;

return new class extends Migration
{
    public function up(): void
    {
        $policy = <<<'HTML'
<strong>Choose an appointment type.</strong>

· To confirm your session, kindly settle payment to any of the following:

<strong>GCash / PayMaya</strong><br>
Ma. Jaliha Unlayao<br>
09533651548

You can choose to pay full package price or 50% down payment fee to reserve the time slot.
· Add-on fees and remaining balance will be settled right after the session.
· Your confirmed slot is non-refundable but can be re-scheduled.
· Re-scheduling should be made 1-2 days before the scheduled time slot.

Please settle your payment within the day and send proof of payment thru FB page messenger (Required)

<strong>SHOWING UP LATE</strong>

· Please be on time. We have a 10-minutes grace period before we start the photoshoot timer. (Retouch and you can change of outfit)
· If you arrive later than 15 minutes, your photoshoot time will be reduced.
· No show means cancelled slot. (down payment will be forfeited)

I have read and agree to 56'30 Studio's Policy (Required)

THANK YOU AND SEE YOU!
HTML;

        $guides = <<<'HTML'
· This is a self-capture studio. just you, the camera, and remote.
· Photographer is also available by client's request. (additional charge on your chosen package)
· Receive all soft copies the within 1 day through Google drive, 2 to 3 days for those who avail with photographer session.
· For special packages and add-ons, you will select photos for printing after photoshoot (7 to 15 minutes selection and printing time)
· 1 backdrop color of your choice. Additional P199 fee per backdrop change.
· Free use of all props at the studio: sunglasses, headbands, artificial flowers, crowns & sash.
· You can bring your own props: balloons, fresh flowers, cake, costumes, etc.
· No hair and makeup time before photoshoot. Retouch only. (except for client with HMUA appointment)
· You can bring your own hair and makeup artist. Book the studio (P700 per hour) before your photoshoot time slot for hair and makeup session.
· Babies are free of charge (5 months and below)
· We are a pet-friendly studio. Pets must be on leash and in diapers before and after the photoshoot.
· Food and drinks are not allowed inside the studio. We have waiting area and cafe where you can eat your snacks.
HTML;

        Setting::set('home_policy', trim($policy));
        Setting::set('home_guides', trim($guides));
    }

    public function down(): void
    {
        Setting::whereIn('key', ['home_policy', 'home_guides'])->delete();
    }
};
