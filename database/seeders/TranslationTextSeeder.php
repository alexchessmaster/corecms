<?php

namespace Database\Seeders;


use App\Models\TranslationText;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TranslationTextSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->navbar();
        $this->footer();
    }

    private function navbar()
    {
        $translationText = new TranslationText;
        $translationText->key = 'navbar_email_us';
        $translationText->setTranslation('text', 'en', 'Email Us');
        $translationText->save();
    }

    private function footer()
    {
        $translationText = new TranslationText;
        $translationText->key = 'footer_contact_us';
        $translationText->setTranslation('text', 'en', 'Contact us');
        $translationText->save();

        $translationText = new TranslationText;
        $translationText->key = 'footer_text_phone';
        $translationText->setTranslation('text', 'en', 'Phone:');
        $translationText->save();

        $translationText = new TranslationText;
        $translationText->key = 'footer_text_mail';
        $translationText->setTranslation('text', 'en', 'Mail:');
        $translationText->save();

        $translationText = new TranslationText;
        $translationText->key = 'footer_text_1_1';
        $translationText->setTranslation('text', 'en', '1');
        $translationText->save();

        $translationText = new TranslationText;
        $translationText->key = 'footer_text_1_2';
        $translationText->setTranslation('text', 'en', 'day');
        $translationText->save();

        $translationText = new TranslationText;
        $translationText->key = 'footer_text_1_3';
        $translationText->setTranslation('text', 'en', 'Response Time');
        $translationText->save();

        $translationText = new TranslationText;
        $translationText->key = 'footer_text_2_1';
        $translationText->setTranslation('text', 'en', '99%');
        $translationText->save();

        $translationText = new TranslationText;
        $translationText->key = 'footer_text_2_2';
        $translationText->setTranslation('text', 'en', '');
        $translationText->save();

        $translationText = new TranslationText;
        $translationText->key = 'footer_text_2_3';
        $translationText->setTranslation('text', 'en', 'Client Satisfaction');
        $translationText->save();

        $translationText = new TranslationText;
        $translationText->key = 'footer_text_3_1';
        $translationText->setTranslation('text', 'en', '11+');
        $translationText->save();

        $translationText = new TranslationText;
        $translationText->key = 'footer_text_3_2';
        $translationText->setTranslation('text', 'en', 'Years');
        $translationText->save();

        $translationText = new TranslationText;
        $translationText->key = 'footer_text_3_3';
        $translationText->setTranslation('text', 'en', 'Field Experience');
        $translationText->save();
    }
}
