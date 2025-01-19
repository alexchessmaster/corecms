<?php

namespace Database\Seeders;

use App\Models\Field;
use App\Models\Widget;
use App\Models\Language;
use App\Models\FieldValue;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class FieldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * 
     * 
     * 
     */
    public function run(): void
    {
        // $widgetId is in WidgetSeeder [//17  
        // page_widget_id is WidgetSeeder $page->widgets()->attach([4 => ['position' => 0]]);

        $this->create(1, 4, 'blue', ['en' => 'ACCELERATED GROWTH', 'da' => 'ACCELERERET VÆKST']); //
        $this->create(1, 4, 'header', ['en' => 'Empowering business with modern web tools and technologies','da' => 'Styrkelse af virksomheden med moderne webværktøjer og -teknologier',]);
        $this->create(1, 4, 'description', ['en' => 'Welcome to Nordicstandard web consulting and solutions.','da' => 'Velkommen til Nordicstandard webrådgivning og løsninger.',]);
        $this->create(1, 4, 'image_1', ['en' => '/uploads/nordicstandard/bg-shape-6.svg','da' => '/uploads/nordicstandard/bg-shape-6.svg',]);
        $this->create(1, 4, 'image_2', ['en' => '/uploads/nordicstandard/bg-shape-5.svg','da' => '/uploads/nordicstandard/bg-shape-5.svg',]);
        
        $this->create(5, 16, 'articles_callback_url', ['en' => env('APP_URL') . '/api/articles']);
        $this->create(5, 16, 'categories_callback_url', ['en' => env('APP_URL') . '/api/categories']);
        
        $this->create(2, 17, 'callback_url', ['en' => env('APP_URL') . '/api/contact-us']);

        $this->create(3, 18, 'subtitle', ['en' => 'OUR SERVICES']);
        $this->create(3, 18, 'title', ['en' => 'We provide best services']);
        $this->create(3, 18, 'description', ['en' => 'Our consulting process begins with a thorough assessment of your current infrastructure, workflows, and pain points.']);
        $this->create(3, 18, 'service_1_image', ['en' => '/uploads/nordicstandard/service-01.png'], 'file');
        $this->create(3, 18, 'service_1_title', ['en' => 'Web development']);
        $this->create(3, 18, 'service_1_description_1', ['en' => 'Stay ahead of the curve']);
        $this->create(3, 18, 'service_1_description_2', ['en' => 'high-end web technologies.']);
        $this->create(3, 18, 'service_1_link', ['en' => '/en/web-development']);
        $this->create(3, 18, 'service_2_image', ['en' => '/uploads/nordicstandard/service-02.jpg'], 'file');
        $this->create(3, 18, 'service_2_title', ['en' => 'App development']);
        $this->create(3, 18, 'service_2_description_1', ['en' => 'We have a best team for']);
        $this->create(3, 18, 'service_2_description_2', ['en' => 'you mobile application.']);
        $this->create(3, 18, 'service_2_link', ['en' => '']);
        $this->create(3, 18, 'service_3_image', ['en' => '/uploads/nordicstandard/service-03.png'], 'file');
        $this->create(3, 18, 'service_3_title', ['en' => 'Hosting']);
        $this->create(3, 18, 'service_3_description_1', ['en' => 'Host and maintenance your app']);
        $this->create(3, 18, 'service_3_description_2', ['en' => 'from startup to enterprize.']);
        $this->create(3, 18, 'service_3_link', ['en' => '']);
        $this->create(3, 18, 'service_4_image', ['en' => '/uploads/nordicstandard/service-04.png'], 'file');
        $this->create(3, 18, 'service_4_title', ['en' => 'IT consultancy']);
        $this->create(3, 18, 'service_4_description_1', ['en' => 'We help you to choose']);
        $this->create(3, 18, 'service_4_description_2', ['en' => 'the right technologies.']);
        $this->create(3, 18, 'service_4_link', ['en' => '']);
        $this->create(3, 18, 'service_5_image', ['en' => '/uploads/nordicstandard/service-05.jpg'], 'file');
        $this->create(3, 18, 'service_5_title', ['en' => 'Secure VPN']);
        $this->create(3, 18, 'service_5_description_1', ['en' => 'Explore the internet']);
        $this->create(3, 18, 'service_5_description_2', ['en' => 'with security and safty.']);
        $this->create(3, 18, 'service_5_link', ['en' => '']);
        
        $this->create(6, 5, 'blue', ['en' => 'EMPOWERMENT']);
        $this->create(6, 5, 'title', ['en' => 'Leading Web App Development']);
        $this->create(6, 5, 'description', ['en' => 'We build custom software that allows businesses to meet their needs and constraints.']);
        $this->create(6, 5, 'right_text_1', ['en' => '+11']);
        $this->create(6, 5, 'right_text_2', ['en' => 'Years']);
        $this->create(6, 5, 'right_text_3', ['en' => 'Experience']);
        $this->create(6, 5, 'link_text', ['en' => 'Contact us']);
        $this->create(6, 5, 'link_url', ['en' => '/en/contact']);
        $this->create(6, 5, 'image_main', ['en' => '/uploads/nordicstandard/bg1-1.png'], 'file');
        $this->create(6, 5, 'image_icon', ['en' => '/uploads/nordicstandard/icon1.svg'], 'file');
        $this->create(6, 5, 'image_main_mobile', ['en' => '/uploads/nordicstandard/bg1.png'], 'file');
        
        $this->create(7, 20, 'subtitle', ['en' => 'Our Mode']);
        $this->create(7, 20, 'title', ['en' => 'How we do']);
        $this->create(7, 20, 'description', ['en' => 'Save time and money with our powerful method.']);
        $this->create(7, 20, 'link_text', ['en' => 'Contact us']);
        $this->create(7, 20, 'link_url', ['en' => '/en/contact']);
        $this->create(7, 20, 'bg_image', ['en' => '/uploads/nordicstandard/bg-shape-1.svg'], 'file');
        $this->create(7, 20, 'section_1_image', ['en' => '/uploads/nordicstandard/hwd-icon-1.svg'], 'file');
        $this->create(7, 20, 'section_1_title', ['en' => 'Brainstroming']);
        $this->create(7, 20, 'section_1_paragraph', ['en' => 'Ideas']);
        $this->create(7, 20, 'section_2_image', ['en' => '/uploads/nordicstandard/hwd-icon-2.svg'], 'file');
        $this->create(7, 20, 'section_2_title', ['en' => 'Product']);
        $this->create(7, 20, 'section_2_paragraph', ['en' => 'Design']);
        $this->create(7, 20, 'section_3_image', ['en' => '/uploads/nordicstandard/hwd-icon-3.svg'], 'file');
        $this->create(7, 20, 'section_3_title', ['en' => 'Front-End']);
        $this->create(7, 20, 'section_3_paragraph', ['en' => 'Development']);
        $this->create(7, 20, 'section_4_image', ['en' => '/uploads/nordicstandard/hwd-icon-4.svg'], 'file');
        $this->create(7, 20, 'section_4_title', ['en' => 'SEO']);
        $this->create(7, 20, 'section_4_paragraph', ['en' => 'Optimization']);
        $this->create(7, 20, 'section_5_image', ['en' => '/uploads/nordicstandard/hwd-icon-5.svg'], 'file');
        $this->create(7, 20, 'section_5_title', ['en' => 'Back-End']);
        $this->create(7, 20, 'section_5_paragraph', ['en' => 'Development']);
        $this->create(7, 20, 'section_6_image', ['en' => '/uploads/nordicstandard/hwd-icon-6.svg'], 'file');
        $this->create(7, 20, 'section_6_title', ['en' => 'Digital']);
        $this->create(7, 20, 'section_6_paragraph', ['en' => 'Marketing']);

        $this->create(8, 21, 'subtitle', ['en' => 'WHAT WE’RE OFFERING']);
        $this->create(8, 21, 'title_part_1', ['en' => 'We Provide Best Web']);
        $this->create(8, 21, 'title_part_2', ['en' => 'services.']);
        $this->create(8, 21, 'description', ['en' => 'One fundamental aspect of IT services is infrastructure management. This involves the design, implementation, and maintenance of the software, networks, and servers.']);
        $this->create(8, 21, 'service_1_image', ['en' => '/uploads/nordicstandard/service-icon-1.svg'], 'file');
        $this->create(8, 21, 'service_1_title', ['en' => 'Development']);
        $this->create(8, 21, 'service_1_link', ['en' => '/en/contact']);
        $this->create(8, 21, 'service_1_description', ['en' => 'We’re committed to building sustainable and high-quality PHP solutions.']);
        $this->create(8, 21, 'service_2_image', ['en' => '/uploads/nordicstandard/service-icon-2.svg'], 'file');
        $this->create(8, 21, 'service_2_title', ['en' => 'Front-end']);
        $this->create(8, 21, 'service_2_link', ['en' => '/en/contact']);
        $this->create(8, 21, 'service_2_description', ['en' => 'We build with high-end front-end tecnologies like React js']);
        $this->create(8, 21, 'service_3_image', ['en' => '/uploads/nordicstandard/service-icon-3.svg'], 'file');
        $this->create(8, 21, 'service_3_title', ['en' => 'Wordpress']);
        $this->create(8, 21, 'service_3_link', ['en' => '/en/contact']);
        $this->create(8, 21, 'service_3_description', ['en' => 'We enhance customer experiences for success.']);
        $this->create(8, 21, 'service_4_image', ['en' => '/uploads/nordicstandard/service-icon-4.svg'], 'file');
        $this->create(8, 21, 'service_4_title', ['en' => 'Web Design']);
        $this->create(8, 21, 'service_4_link', ['en' => '/en/contact']);
        $this->create(8, 21, 'service_4_description', ['en' => 'We create vibrant, intuitive and minimalist web']);
        $this->create(8, 21, 'service_5_image', ['en' => '/uploads/nordicstandard/service-icon-5.svg'], 'file');
        $this->create(8, 21, 'service_5_title', ['en' => 'IT Support']);
        $this->create(8, 21, 'service_5_link', ['en' => '/en/contact']);
        $this->create(8, 21, 'service_5_description', ['en' => 'We offers expert assistance for your IT issues.']);

    }

    private function create($page_widget_id, $widget_id, $key, $values = [], $type = 'input')
    {
        $field = new Field;
        $field->widget_id = $widget_id;
        $field->type = $type;
        $field->key = $key;
        $field->save();

        if(!empty($values)){
            $fieldValue = new FieldValue;
            $fieldValue->page_widget_id = $page_widget_id;
            $fieldValue->field_id = $field->id; //header
            $fieldValue->setTranslations('value', $values);
            $fieldValue->save();
        }
    }
}
