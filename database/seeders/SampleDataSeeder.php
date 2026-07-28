<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Page;
use App\Models\TeamMember;
use App\Models\Template;
use App\Models\Poll;
use App\Models\PollOption;

class SampleDataSeeder extends Seeder
{
    public function run(): void
    {
        // Pages
        Page::firstOrCreate(['slug' => 'about'], [
            'title' => 'About Us',
            'content' => '<h2>Welcome to 56\'30 Studio Cafe</h2><p>We are a premium self-capture photography studio located in the heart of the city. Our mission is to provide a fun, comfortable, and professional space where you can create lasting memories with your loved ones.</p><p>Founded in 2024, we combine the convenience of self-service photography with the quality of professional equipment and studio lighting.</p>',
            'meta_title' => 'About Us - 56\'30 Studio Cafe',
            'meta_description' => 'Learn about our self-capture photography studio.',
            'is_published' => true,
        ]);

        Page::firstOrCreate(['slug' => 'rules'], [
            'title' => 'Studio Rules',
            'content' => '<h2>Studio Rules & Guidelines</h2><ul><li>Maximum of 6 pax per session for standard packages</li><li>Please arrive 10 minutes before your scheduled time</li><li>Footwear must be removed before entering the shooting area</li><li>No food or drinks inside the studio</li><li>Handle all props with care - damage fees may apply</li><li>Cancellation must be made at least 24 hours in advance</li><li>50% downpayment is required to confirm your booking</li></ul>',
            'meta_title' => 'Studio Rules - 56\'30 Studio Cafe',
            'meta_description' => 'Studio rules and guidelines for our guests.',
            'is_published' => true,
        ]);

        Page::firstOrCreate(['slug' => 'privacy'], [
            'title' => 'Privacy Policy',
            'content' => '<h2>Privacy Policy</h2><p>Your privacy is important to us. We collect only the information necessary to process your bookings and provide our services.</p><p>We do not sell or share your personal information with third parties. Your data is stored securely and is only accessed by authorized staff members.</p>',
            'meta_title' => 'Privacy Policy - 56\'30 Studio Cafe',
            'is_published' => true,
        ]);

        // Team Members
        TeamMember::firstOrCreate(['email' => 'maria@5630studiocafe.com'], ['name' => 'Maria Santos', 'role' => 'Studio Manager', 'phone' => '+63 917 123 4567', 'bio' => 'Maria has been managing the studio since day one, ensuring every guest has an amazing experience.', 'sort_order' => 1]);
        TeamMember::firstOrCreate(['email' => 'juan@5630studiocafe.com'], ['name' => 'Juan Dela Cruz', 'role' => 'Head Photographer', 'phone' => '+63 918 234 5678', 'bio' => 'Juan brings 8 years of photography experience to help set up the perfect lighting and angles.', 'sort_order' => 2]);
        TeamMember::firstOrCreate(['email' => 'ana@5630studiocafe.com'], ['name' => 'Ana Reyes', 'role' => 'Customer Relations', 'phone' => '+63 919 345 6789', 'bio' => 'Ana is your first point of contact, always ready to help with bookings and inquiries.', 'sort_order' => 3]);

        // Templates
        Template::firstOrCreate(['title' => 'Booking Confirmation'], [
            'type' => 'email',
            'subject' => 'Booking Confirmed! Ref: {{booking_ref}}',
            'body' => '<h2>Hi {{customer_name}},</h2><p>Your booking has been confirmed!</p><ul><li>Package: {{package_name}}</li><li>Date: {{booking_date}}</li><li>Time: {{booking_time}}</li><li>Total: ₱{{total_amount}}</li></ul><p>We look forward to seeing you!</p>',
            'is_active' => true,
        ]);

        Template::firstOrCreate(['title' => 'Payment Reminder'], [
            'type' => 'email',
            'subject' => 'Payment Reminder - Booking {{booking_ref}}',
            'body' => '<h2>Hi {{customer_name}},</h2><p>This is a friendly reminder to complete your downpayment of 50% for your upcoming booking.</p><p>Booking Reference: {{booking_ref}}<br>Amount Due: ₱{{total_amount}}</p>',
            'is_active' => true,
        ]);

        // Polls
        if (Poll::count() === 0) {
            $poll = Poll::create([
                'question' => 'Which photo theme would you like us to add next?',
                'description' => 'Help us decide our next studio theme! Vote for your favorite.',
                'is_active' => true,
                'allow_multiple' => false,
            ]);
            PollOption::create(['poll_id' => $poll->id, 'text' => 'Korean Hanbok']);
            PollOption::create(['poll_id' => $poll->id, 'text' => 'Japanese Yukata']);
            PollOption::create(['poll_id' => $poll->id, 'text' => 'Vintage Filipino']);
            PollOption::create(['poll_id' => $poll->id, 'text' => 'Neon Cyberpunk']);

            $poll2 = Poll::create([
                'question' => 'What time slot do you prefer for weekend bookings?',
                'description' => 'Your feedback helps us optimize our schedule.',
                'is_active' => true,
                'allow_multiple' => true,
            ]);
            PollOption::create(['poll_id' => $poll2->id, 'text' => 'Early morning (8-10 AM)']);
            PollOption::create(['poll_id' => $poll2->id, 'text' => 'Mid-morning (10 AM-12 NN)']);
            PollOption::create(['poll_id' => $poll2->id, 'text' => 'Early afternoon (1-3 PM)']);
            PollOption::create(['poll_id' => $poll2->id, 'text' => 'Late afternoon (3-5 PM)']);
        }
    }
}
