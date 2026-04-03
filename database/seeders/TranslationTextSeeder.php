<?php

namespace Database\Seeders;


use App\Modules\TranslationTexts\Models\TranslationText;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TranslationTextSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->general();
        $this->navbar();
        $this->footer();
    }

    private function general()
    {
        $translationText = new TranslationText;
        $translationText->key = 'email';
        $translationText->setTranslation('text', 'fa', 'example@gmail.com');
        $translationText->save();

        $translationText = new TranslationText;
        $translationText->key = 'Phone';
        $translationText->setTranslation('text', 'fa', '+4570000000');
        $translationText->save();

        $translationText = new TranslationText;
        $translationText->key = 'email_us';
        $translationText->setTranslation('text', 'fa', 'Email');
        $translationText->save();
    }

    private function navbar()
    {

    }

    private function footer()
    {
        $translationText = new TranslationText;
        $translationText->key = 'footer_contact_us';
        $translationText->setTranslation('text', 'fa', 'Contact us');
        $translationText->save();

        $translationText = new TranslationText;
        $translationText->key = 'footer_text_phone';
        $translationText->setTranslation('text', 'fa', 'Phone:');
        $translationText->save();

        $translationText = new TranslationText;
        $translationText->key = 'footer_text_mail';
        $translationText->setTranslation('text', 'fa', 'Mail:');
        $translationText->save();

        $translationText = new TranslationText;
        $translationText->key = 'footer_text_1_1';
        $translationText->setTranslation('text', 'fa', '1');
        $translationText->save();

        $translationText = new TranslationText;
        $translationText->key = 'footer_text_1_2';
        $translationText->setTranslation('text', 'fa', 'day');
        $translationText->save();

        $translationText = new TranslationText;
        $translationText->key = 'footer_text_1_3';
        $translationText->setTranslation('text', 'fa', 'Response Time');
        $translationText->save();

        $translationText = new TranslationText;
        $translationText->key = 'footer_text_2_1';
        $translationText->setTranslation('text', 'fa', '99%');
        $translationText->save();

        $translationText = new TranslationText;
        $translationText->key = 'footer_text_2_2';
        $translationText->setTranslation('text', 'fa', '');
        $translationText->save();

        $translationText = new TranslationText;
        $translationText->key = 'footer_text_2_3';
        $translationText->setTranslation('text', 'fa', 'Client Satisfaction');
        $translationText->save();

        $translationText = new TranslationText;
        $translationText->key = 'footer_text_3_1';
        $translationText->setTranslation('text', 'fa', '11+');
        $translationText->save();

        $translationText = new TranslationText;
        $translationText->key = 'footer_text_3_2';
        $translationText->setTranslation('text', 'fa', 'Years');
        $translationText->save();

        $translationText = new TranslationText;
        $translationText->key = 'footer_text_3_3';
        $translationText->setTranslation('text', 'fa', 'Field Experience');
        $translationText->save();
    }
}
