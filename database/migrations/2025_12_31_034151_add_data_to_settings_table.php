<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Insert or update settings
        $settings = [
            [
                'key' => 'notification-email-enabled',
                'value' => 'false',
                'description' => 'If true, you will receive email notifications; if false, you will not.'
            ],
            [
                'key' => 'notification-slack-enabled',
                'value' => 'false',
                'description' => 'If true, you will receive Slack notifications; if false, you will not.'
            ],
            [
                'key' => 'notification-email-recipients',
                'value' => '',
                'description' => 'Enter the email addresses to receive notifications, separated by commas. Leave empty to disable.'
            ],
            [
                'key' => 'notification-slack-webhook',
                'value' => 'false',
                'description' => 'Enter the the slack webhook URL. Leave empty to disable.'
            ],
            [
                'key' => 'notification-on-contact-form',
                'value' => '',
                'description' => 'Receive a notification when someone submits the contact us form.'
            ],
            [
                'key' => 'notification-on-reservation',
                'value' => '',
                'description' => 'Receive a notification when someone makes a reservation.'
            ],
            [
                'key' => 'notification-on-payment',
                'value' => '',
                'description' => 'Receive a notification when someone makes a payment.'
            ],
            [
                'key' => 'notification-on-user-registration',
                'value' => '',
                'description' => 'Receive a notification when a new user registers.'
            ],
        ];
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            //
        });
    }
};
