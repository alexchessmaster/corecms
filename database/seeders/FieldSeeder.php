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
        $this->create(1, 4, 'header', ['en' => 'Empowering business with modern web tools and technologies', 'da' => 'Styrkelse af virksomheden med moderne webværktøjer og -teknologier',]);
        $this->create(1, 4, 'description', ['en' => 'Welcome to Nordicstandard web consulting and solutions.', 'da' => 'Velkommen til Nordicstandard webrådgivning og løsninger.',]);
        $this->create(1, 4, 'image_1', ['en' => '/uploads/nordicstandard/bg-shape-6.svg', 'da' => '/uploads/nordicstandard/bg-shape-6.svg',]);
        $this->create(1, 4, 'image_2', ['en' => '/uploads/nordicstandard/bg-shape-5.svg', 'da' => '/uploads/nordicstandard/bg-shape-5.svg',]);

        $this->create(2, 17, 'callback_url', ['en' => env('APP_URL') . '/api/contact-us', 'da' => env('APP_URL') . '/api/contact-us']);
        $this->create(2, 17, 'you_can_reach_us_anytime_via', ['en' => 'You can reach us anytime via', 'da' => 'Du kan kontakte os når som helst via']);
        $this->create(2, 17, 'you_can_reach_us_anytime_via_email', ['en' => 'sales@nordicstandard.net', 'da' => 'sales@nordicstandard.net']);
        $this->create(2, 17, 'first_name', ['en' => 'First Name', 'da' => 'Fornavn']);
        $this->create(2, 17, 'first_name_placeholder', ['en' => 'First Name', 'da' => 'Fornavn']);
        $this->create(2, 17, 'last_name', ['en' => 'Last Name', 'da' => 'Efternavn']);
        $this->create(2, 17, 'last_name_placeholder', ['en' => 'Last Name', 'da' => 'Efternavn']);
        $this->create(2, 17, 'email', ['en' => 'Email', 'da' => 'Email']);
        $this->create(2, 17, 'email_placeholder', ['en' => 'Your Email', 'da' => 'Din Email']);
        $this->create(2, 17, 'phone_number', ['en' => 'Phone Number', 'da' => 'Telefonnummer']);
        $this->create(2, 17, 'phone_number_placeholder', ['en' => 'Your Phone Number', 'da' => 'Dit Telefonnummer']);
        $this->create(2, 17, 'country_number', ['en' => 'Country', 'da' => 'Land']);
        $this->create(2, 17, 'country_number_placeholder', ['en' => 'Your Country', 'da' => 'Dit Land']);
        $this->create(2, 17, 'type_of_your_company', ['en' => 'What‘s the type of your company?', 'da' => 'Hvilken type virksomhed har du?']);
        $this->create(2, 17, 'company_1', ['en' => 'SAAS', 'da' => 'SAAS']);
        $this->create(2, 17, 'company_2', ['en' => 'Banking', 'da' => 'Bank']);
        $this->create(2, 17, 'company_3', ['en' => 'Agency', 'da' => 'Bureau']);
        $this->create(2, 17, 'company_4', ['en' => 'Business', 'da' => 'Forretning']);
        $this->create(2, 17, 'company_5', ['en' => 'Other', 'da' => 'Andet']);
        $this->create(2, 17, 'what_you_need_from_us', ['en' => 'What you need from us?', 'da' => 'Hvad har du brug for fra os?']);
        $this->create(2, 17, 'item_1', ['en' => 'app-design', 'da' => 'app-design']);
        $this->create(2, 17, 'item_2', ['en' => 'web-design', 'da' => 'web-design']);
        $this->create(2, 17, 'item_3', ['en' => 'hosting', 'da' => 'hosting']);
        $this->create(2, 17, 'item_4', ['en' => 'consultancy', 'da' => 'konsulentydelser']);
        $this->create(2, 17, 'item_5', ['en' => 'VPN', 'da' => 'VPN']);
        $this->create(2, 17, 'item_6', ['en' => 'Other', 'da' => 'Andet']);
        $this->create(2, 17, 'message', ['en' => 'Message', 'da' => 'Besked']);
        $this->create(2, 17, 'message_placeholder', ['en' => 'Leave us a message', 'da' => 'Efterlad os en besked']);
        $this->create(2, 17, 'budget', ['en' => 'Budget', 'da' => 'Budget']);
        $this->create(2, 17, 'budget_placeholder', ['en' => 'Your Budget', 'da' => 'Dit Budget']);
        $this->create(2, 17, 'checkbox_message', ['en' => 'Click the box and agree to our', 'da' => 'Klik på boksen og accepter vores']);
        $this->create(2, 17, 'checkbox_message_text', ['en' => 'terms and conditions.', 'da' => 'vilkår og betingelser.']);
        $this->create(2, 17, 'checkbox_message_link', ['en' => '/en/terms', 'da' => '/en/terms']);
        $this->create(2, 17, 'send', ['en' => 'send', 'da' => 'send']);        

        $widget = Widget::find(17);
        $widget->locked_fields_value = true;
        $widget->save();

        $this->create(3, 18, 'subtitle', ['en' => 'OUR SERVICES', 'da' => 'VORES TJENESTER']);
        $this->create(3, 18, 'title', ['en' => 'We provide best services', 'da' => 'Vi leverer de bedste tjenester']);
        $this->create(3, 18, 'description', ['en' => 'Our consulting process begins with a thorough assessment of your current infrastructure, workflows, and pain points.', 'da' => 'Vores rådgivningsproces begynder med en grundig vurdering af din nuværende infrastruktur, arbejdsprocesser og udfordringer.']);
        $this->create(3, 18, 'service_1_image', ['en' => '/uploads/nordicstandard/service-01.png', 'da' => '/uploads/nordicstandard/service-01.png'], 'file');
        $this->create(3, 18, 'service_1_title', ['en' => 'Web development', 'da' => 'Webudvikling']);
        $this->create(3, 18, 'service_1_description_1', ['en' => 'Stay ahead of the curve', 'da' => 'Hold dig foran kurven']);
        $this->create(3, 18, 'service_1_description_2', ['en' => 'high-end web technologies.', 'da' => 'høj-end webteknologier.']);
        $this->create(3, 18, 'service_1_link', ['en' => '/en/web-development', 'da' => '/en/web-development']);
        $this->create(3, 18, 'service_2_image', ['en' => '/uploads/nordicstandard/service-02.jpg', 'da' => '/uploads/nordicstandard/service-02.jpg'], 'file');
        $this->create(3, 18, 'service_2_title', ['en' => 'App development', 'da' => 'App-udvikling']);
        $this->create(3, 18, 'service_2_description_1', ['en' => 'We have a best team for', 'da' => 'Vi har det bedste team til']);
        $this->create(3, 18, 'service_2_description_2', ['en' => 'you mobile application.', 'da' => 'din mobilapplikation.']);
        $this->create(3, 18, 'service_2_link', ['en' => '', 'da' => '']);
        $this->create(3, 18, 'service_3_image', ['en' => '/uploads/nordicstandard/service-03.png', 'da' => '/uploads/nordicstandard/service-03.png'], 'file');
        $this->create(3, 18, 'service_3_title', ['en' => 'Hosting', 'da' => 'Hosting']);
        $this->create(3, 18, 'service_3_description_1', ['en' => 'Host and maintenance your app', 'da' => 'Host og vedligehold din app']);
        $this->create(3, 18, 'service_3_description_2', ['en' => 'from startup to enterprize.', 'da' => 'fra startup til virksomhed.']);
        $this->create(3, 18, 'service_3_link', ['en' => '', 'da' => '']);
        $this->create(3, 18, 'service_4_image', ['en' => '/uploads/nordicstandard/service-04.png', 'da' => '/uploads/nordicstandard/service-04.png'], 'file');
        $this->create(3, 18, 'service_4_title', ['en' => 'IT consultancy', 'da' => 'IT-rådgivning']);
        $this->create(3, 18, 'service_4_description_1', ['en' => 'We help you to choose', 'da' => 'Vi hjælper dig med at vælge']);
        $this->create(3, 18, 'service_4_description_2', ['en' => 'the right technologies.', 'da' => 'de rigtige teknologier.']);
        $this->create(3, 18, 'service_4_link', ['en' => '', 'da' => '']);
        $this->create(3, 18, 'service_5_image', ['en' => '/uploads/nordicstandard/service-05.jpg', 'da' => '/uploads/nordicstandard/service-05.jpg'], 'file');
        $this->create(3, 18, 'service_5_title', ['en' => 'Secure VPN', 'da' => 'Sikker VPN']);
        $this->create(3, 18, 'service_5_description_1', ['en' => 'Explore the internet', 'da' => 'Udforsk internettet']);
        $this->create(3, 18, 'service_5_description_2', ['en' => 'with security and safety.', 'da' => 'med sikkerhed og tryghed.']);
        $this->create(3, 18, 'service_5_link', ['en' => '', 'da' => '']);

        $this->create(4, 19, 'subtitle', ['en' => 'SPECIALIZATION', 'da' => 'SPECIALISERING']);
        $this->create(4, 19, 'title', ['en' => 'What our company does for you', 'da' => 'Hvad vores virksomhed gør for dig']);
        $this->create(4, 19, 'menu_1_image', ['en' => '/uploads/nordicstandard/hwd-icon-1.svg', 'da' => '/uploads/nordicstandard/hwd-icon-1.svg'], 'file');
        $this->create(4, 19, 'menu_1_title', ['en' => 'Web development', 'da' => 'Webudvikling']);
        $this->create(4, 19, 'menu_2_image', ['en' => '/uploads/nordicstandard/hwd-icon-4.svg', 'da' => '/uploads/nordicstandard/hwd-icon-4.svg'], 'file');
        $this->create(4, 19, 'menu_2_title', ['en' => 'App development', 'da' => 'App-udvikling']);
        $this->create(4, 19, 'menu_3_image', ['en' => '/uploads/nordicstandard/hwd-icon-6.svg', 'da' => '/uploads/nordicstandard/hwd-icon-6.svg'], 'file');
        $this->create(4, 19, 'menu_3_title', ['en' => 'Hosting', 'da' => 'Hosting']);
        $this->create(4, 19, 'menu_4_image', ['en' => '/uploads/nordicstandard/hwd-icon-2.svg', 'da' => '/uploads/nordicstandard/hwd-icon-2.svg'], 'file');
        $this->create(4, 19, 'menu_4_title', ['en' => 'IT Consultancy', 'da' => 'IT-rådgivning']);
        $this->create(4, 19, 'menu_5_image', ['en' => '/uploads/nordicstandard/hwd-icon-3.svg', 'da' => '/uploads/nordicstandard/hwd-icon-3.svg'], 'file');
        $this->create(4, 19, 'menu_5_title', ['en' => 'Secure VPN', 'da' => 'Sikker VPN']);
        $this->create(4, 19, 'item_1_image', ['en' => '/uploads/nordicstandard/about2-tab-1.png', 'da' => '/uploads/nordicstandard/about2-tab-1.png'], 'file');
        $this->create(4, 19, 'item_1_icon', ['en' => '/uploads/nordicstandard/hwd-icon-1.svg', 'da' => '/uploads/nordicstandard/hwd-icon-1.svg'], 'file');
        $this->create(4, 19, 'item_1_title', ['en' => "Transform Your Ideas into Reality with Expert Web Development", 'da' => "Forvandl dine idéer til virkelighed med ekspert webudvikling"]);
        $this->create(4, 19, 'item_1_description', ['en' => "At the heart of every successful online venture lies a robust website, developed with precision and crafted for excellence. Our Web Development service stands at the forefront, turning vivid dreams and complex ideas into digital realities. With a dedicated team of experts, we specialize in creating high-performance, user-centric websites that not only look stunning but also function seamlessly across all devices. From e-commerce platforms to bespoke informational sites, our approach is tailored to meet your specific needs, ensuring your online presence is not just visible, but vibrant and effective. Partner with us, and let's build the digital foundation that propels your business to new heights.", 'da' => "Kernen i enhver succesfuld online satsning er en robust hjemmeside, udviklet med præcision og designet til perfektion. Vores webudviklingstjeneste står i spidsen for at omdanne levende drømme og komplekse idéer til digitale realiteter. Med et dedikeret team af eksperter specialiserer vi os i at skabe højtydende, brugervenlige hjemmesider, der ikke kun ser fantastiske ud, men også fungerer problemfrit på alle enheder. Fra e-handelsplatforme til skræddersyede informationssider, vores tilgang er tilpasset dine specifikke behov, hvilket sikrer, at din online tilstedeværelse ikke kun er synlig, men levende og effektiv. Samarbejd med os, og lad os bygge det digitale fundament, der løfter din virksomhed til nye højder."]);
        $this->create(4, 19, 'item_1_link', ['en' => "#", 'da' => "#"]);
        $this->create(4, 19, 'item_2_image', ['en' => '/uploads/nordicstandard/about2-tab-2.png', 'da' => '/uploads/nordicstandard/about2-tab-2.png'], 'file');
        $this->create(4, 19, 'item_2_icon', ['en' => '/uploads/nordicstandard/hwd-icon-4.svg', 'da' => '/uploads/nordicstandard/hwd-icon-4.svg'], 'file');
        $this->create(4, 19, 'item_2_title', ['en' => "Unlock Next-Level Engagement with Premier Mobile App Development", 'da' => "Skab næste niveau af engagement med førsteklasses mobilappudvikling"]);
        $this->create(4, 19, 'item_2_description', ['en' => "In today's fast-paced digital ecosystem, mobile apps serve as the cornerstone of engagement and user retention. Our Mobile App Development service is crafted to catapult your business into the hands of your target audience, delivering an interface that is not only intuitive but also profoundly impactful. We specialize in designing and developing mobile applications that offer seamless functionality, complemented by captivating aesthetics for both Android and iOS platforms. Our approach is to understand your unique requirements, enabling us to deliver personalized solutions that amplify your reach, foster loyalty, and drive conversions. Let's navigate the future of engagement together, creating mobile experiences that resonate and endure.", 'da' => "I nutidens hurtige digitale økosystem fungerer mobilapps som hjørnestenen for engagement og brugerfastholdelse. Vores mobilappudviklingstjeneste er designet til at bringe din virksomhed direkte til dine målgrupper med en grænseflade, der både er intuitiv og yderst effektiv. Vi specialiserer os i at designe og udvikle mobilapplikationer, der tilbyder problemfri funktionalitet, kombineret med iøjnefaldende æstetik til både Android og iOS-platforme. Vores tilgang er at forstå dine unikke krav, så vi kan levere skræddersyede løsninger, der udvider din rækkevidde, skaber loyalitet og driver konverteringer. Lad os sammen skabe fremtidens engagement med mobiloplevelser, der resonerer og varer ved."]);
        $this->create(4, 19, 'item_2_link', ['en' => "#", 'da' => "#"]);
        $this->create(4, 19, 'item_3_image', ['en' => '/uploads/nordicstandard/about2-tab-3.png', 'da' => '/uploads/nordicstandard/about2-tab-3.png'], 'file');
        $this->create(4, 19, 'item_3_icon', ['en' => '/uploads/nordicstandard/hwd-icon-6.svg', 'da' => '/uploads/nordicstandard/hwd-icon-6.svg'], 'file');
        $this->create(4, 19, 'item_3_title', ['en' => "Reliable Website Hosting to Keep Your Digital Presence Always Online", 'da' => "Pålidelig webhosting til at holde din digitale tilstedeværelse altid online"]);
        $this->create(4, 19, 'item_3_description', ['en' => "In the digital age, the uptime, speed, and security of your website are paramount. Our Website Hosting service provides a robust and reliable home for your digital presence, ensuring your website remains accessible, fast, and secure around the clock. With state-of-the-art infrastructure and unparalleled support, we cater to businesses of all sizes, offering scalable solutions that grow with your needs. From shared hosting environments for small startups to dedicated servers for large enterprises, our tailored hosting plans guarantee maximum performance and minimum downtime. Trust us to keep your website always online, letting you focus on what you do best—growing your business.", 'da' => "I den digitale tidsalder er oppetid, hastighed og sikkerhed for din hjemmeside altafgørende. Vores webhostingtjeneste giver et robust og pålideligt hjem til din digitale tilstedeværelse, der sikrer, at din hjemmeside forbliver tilgængelig, hurtig og sikker døgnet rundt. Med avanceret infrastruktur og enestående support henvender vi os til virksomheder af alle størrelser og tilbyder skalerbare løsninger, der vokser med dine behov. Fra delte hostingmiljøer for små startups til dedikerede servere for store virksomheder, garanterer vores skræddersyede hostingplaner maksimal ydeevne og minimal nedetid. Stol på os for at holde din hjemmeside altid online, så du kan fokusere på det, du gør bedst—at vækste din virksomhed."]);
        $this->create(4, 19, 'item_3_link', ['en' => "#", 'da' => "#"]);
        $this->create(4, 19, 'item_4_image', ['en' => '/uploads/nordicstandard/about2-tab-4.png', 'da' => '/uploads/nordicstandard/about2-tab-4.png'], 'file');
        $this->create(4, 19, 'item_4_icon', ['en' => '/uploads/nordicstandard/hwd-icon-2.svg', 'da' => '/uploads/nordicstandard/hwd-icon-2.svg'], 'file');
        $this->create(4, 19, 'item_4_title', ['en' => "Drive Your Business Forward with Expert IT Consultancy", 'da' => "Fremdriv din virksomhed med ekspert IT-rådgivning"]);
        $this->create(4, 19, 'item_4_description', ['en' => "In the ever-changing landscape of technology, staying ahead requires not only adapting to changes but anticipating them. Our IT Consultancy service is designed to do just that. We provide comprehensive, forward-thinking advice and solutions that prepare your business for the future. With a team of seasoned IT professionals, we offer insights and expertise across various domains, including cloud services, cybersecurity, network infrastructure, and software development. Whether you're looking to refine your IT strategy, streamline operations, or enhance security, our bespoke consultancy services are tailored to meet your specific needs. Let us be the catalyst that propels your business to new technological heights, optimizing performance and driving innovation.", 'da' => "I det evigt foranderlige teknologilandskab kræver det ikke kun tilpasning, men også forudseenhed at være på forkant. Vores IT-rådgivningstjeneste er designet til netop det. Vi tilbyder omfattende, fremadskuende rådgivning og løsninger, der forbereder din virksomhed på fremtiden. Med et team af erfarne IT-professionelle tilbyder vi indsigt og ekspertise på tværs af forskellige områder, herunder cloud-tjenester, cybersikkerhed, netværksinfrastruktur og softwareudvikling. Uanset om du ønsker at forbedre din IT-strategi, optimere driften eller forbedre sikkerheden, er vores skræddersyede rådgivningstjenester tilpasset dine specifikke behov. Lad os være katalysatoren, der løfter din virksomhed til nye teknologiske højder, optimerer ydeevnen og driver innovation."]);
        $this->create(4, 19, 'item_4_link', ['en' => "#", 'da' => "#"]);
        $this->create(4, 19, 'item_5_image', ['en' => '/uploads/nordicstandard/about2-tab-5.png', 'da' => '/uploads/nordicstandard/about2-tab-5.png'], 'file');
        $this->create(4, 19, 'item_5_icon', ['en' => '/uploads/nordicstandard/hwd-icon-3.svg', 'da' => '/uploads/nordicstandard/hwd-icon-3.svg'], 'file');
        $this->create(4, 19, 'item_5_title', ['en' => "Privacy and Security on Every Device: Our Cross-Platform VPN Solution", 'da' => "Privatliv og sikkerhed på alle enheder: Vores VPN-løsning på tværs af platforme"]);
        $this->create(4, 19, 'item_5_description', ['en' => "In the diverse ecosystem of digital devices, our Secure VPN service stands out by offering full-scale protection across all major operating systems, including Android, iOS, Windows, Linux, and Mac. This unparalleled compatibility ensures that no matter what device you use to connect to the internet, your online activities remain private and secure. Our VPN service is designed with versatility in mind, allowing seamless integration and consistent user experience on smartphones, tablets, laptops, and desktops alike. With just one subscription, protect every device you own from cyber threats, enjoy unrestricted internet access, and maintain your anonymity across all platforms. Embrace a worry-free digital life with our comprehensive, cross-platform VPN service.", 'da' => "I det mangfoldige økosystem af digitale enheder skiller vores sikre VPN-tjeneste sig ud ved at tilbyde fuldskala beskyttelse på tværs af alle større operativsystemer, herunder Android, iOS, Windows, Linux og Mac. Denne enestående kompatibilitet sikrer, at uanset hvilken enhed du bruger til at forbinde til internettet, forbliver dine online aktiviteter private og sikre. Vores VPN-tjeneste er designet med alsidighed for øje og tillader problemfri integration og ensartet brugeroplevelse på både smartphones, tablets, laptops og desktops. Med kun ét abonnement kan du beskytte alle dine enheder mod cybertrusler, nyde ubegrænset internetadgang og opretholde anonymitet på tværs af alle platforme. Lev et bekymringsfrit digitalt liv med vores omfattende VPN-løsning på tværs af platforme."]);
        $this->create(4, 19, 'item_5_link', ['en' => "#", 'da' => "#"]);

        $this->create(5, 16, 'articles_callback_url', ['en' => env('APP_URL') . '/api/articles']);
        $this->create(5, 16, 'categories_callback_url', ['en' => env('APP_URL') . '/api/categories']);

        $this->create(6, 5, 'blue', ['en' => 'EMPOWERMENT', 'da' => 'EMPOWERMENT']);
        $this->create(6, 5, 'title', ['en' => 'Leading Web App Development', 'da' => 'Førende webappudvikling']);
        $this->create(6, 5, 'description', ['en' => 'We build custom software that allows businesses to meet their needs and constraints.', 'da' => 'Vi udvikler skræddersyet software, der gør det muligt for virksomheder at imødekomme deres behov og begrænsninger.']);
        $this->create(6, 5, 'right_text_1', ['en' => '+11', 'da' => '+11']);
        $this->create(6, 5, 'right_text_2', ['en' => 'Years', 'da' => 'År']);
        $this->create(6, 5, 'right_text_3', ['en' => 'Experience', 'da' => 'Erfaring']);
        $this->create(6, 5, 'link_text', ['en' => 'Contact us', 'da' => 'Kontakt os']);
        $this->create(6, 5, 'link_url', ['en' => '/en/contact', 'da' => '/en/contact'], 'file');
        $this->create(6, 5, 'image_main', ['en' => '/uploads/nordicstandard/bg1-1.png', 'da' => '/uploads/nordicstandard/bg1-1.png'], 'file');
        $this->create(6, 5, 'image_icon', ['en' => '/uploads/nordicstandard/icon1.svg', 'da' => '/uploads/nordicstandard/icon1.svg'], 'file');
        $this->create(6, 5, 'image_main_mobile', ['en' => '/uploads/nordicstandard/bg1.png', 'da' => '/uploads/nordicstandard/bg1.png'], 'file');

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

        $this->create(8, 21, 'subtitle', ['en' => 'WHAT WE\'RE OFFERING', 'da' => 'HVAD VI TILBYDER']);
        $this->create(8, 21, 'title_part_1', ['en' => 'We Provide Best Web', 'da' => 'Vi Leverer De Bedste Web']);
        $this->create(8, 21, 'title_part_2', ['en' => 'services.', 'da' => 'tjenester.']);
        $this->create(8, 21, 'description', ['en' => 'One fundamental aspect of IT services is infrastructure management. This involves the design, implementation, and maintenance of the software, networks, and servers.', 'da' => 'Et grundlæggende aspekt af IT-tjenester er infrastrukturstyring. Dette omfatter design, implementering og vedligeholdelse af software, netværk og servere.']);
        $this->create(8, 21, 'service_1_image', ['en' => '/uploads/nordicstandard/service-icon-1.svg'], 'file');
        $this->create(8, 21, 'service_1_title', ['en' => 'Development', 'da' => 'Udvikling']);
        $this->create(8, 21, 'service_1_link', ['en' => '/en/contact']);
        $this->create(8, 21, 'service_1_description', ['en' => 'We\'re committed to building sustainable and high-quality PHP solutions.', 'da' => 'Vi er dedikerede til at bygge bæredygtige og højkvalitets PHP-løsninger.']);
        $this->create(8, 21, 'service_2_image', ['en' => '/uploads/nordicstandard/service-icon-2.svg'], 'file');
        $this->create(8, 21, 'service_2_title', ['en' => 'Front-end', 'da' => 'Front-end']);
        $this->create(8, 21, 'service_2_link', ['en' => '/en/contact']);
        $this->create(8, 21, 'service_2_description', ['en' => 'We build with high-end front-end tecnologies like React js', 'da' => 'Vi bygger med high-end front-end teknologier som React js']);
        $this->create(8, 21, 'service_3_image', ['en' => '/uploads/nordicstandard/service-icon-3.svg'], 'file');
        $this->create(8, 21, 'service_3_title', ['en' => 'Wordpress', 'da' => 'Wordpress']);
        $this->create(8, 21, 'service_3_link', ['en' => '/en/contact']);
        $this->create(8, 21, 'service_3_description', ['en' => 'We enhance customer experiences for success.', 'da' => 'Vi forbedrer kundeoplevelser for succes.']);
        $this->create(8, 21, 'service_4_image', ['en' => '/uploads/nordicstandard/service-icon-4.svg'], 'file');
        $this->create(8, 21, 'service_4_title', ['en' => 'Web Design', 'da' => 'Webdesign']);
        $this->create(8, 21, 'service_4_link', ['en' => '/en/contact']);
        $this->create(8, 21, 'service_4_description', ['en' => 'We create vibrant, intuitive and minimalist web', 'da' => 'Vi skaber levende, intuitive og minimalistiske websider']);
        $this->create(8, 21, 'service_5_image', ['en' => '/uploads/nordicstandard/service-icon-5.svg'], 'file');
        $this->create(8, 21, 'service_5_title', ['en' => 'IT Support', 'da' => 'IT Support']);
        $this->create(8, 21, 'service_5_link', ['en' => '/en/contact']);
        $this->create(8, 21, 'service_5_description', ['en' => 'We offers expert assistance for your IT issues.', 'da' => 'Vi tilbyder ekspert assistance til dine IT-problemer.']);

        $this->create(9, 5, 'blue', ['en' => 'CONTACT US', 'da' => 'KONTAKT OS']);
        $this->create(9, 5, 'title', ['en' => 'Let’s Build Your Vision Together', 'da' => 'Lad os bygge din vision sammen']);
        $this->create(9, 5, 'description', ['en' => 'At Leading Web App Development, we specialize in creating custom software tailored to your business needs and constraints. With a proven track record of success, we empower businesses to thrive in the digital age.', 'da' => 'Hos Leading Web App Development specialiserer vi os i at skabe skræddersyet software tilpasset dine forretningsbehov og begrænsninger. Med en dokumenteret succesrate styrker vi virksomheder til at trives i den digitale tidsalder.']);
        $this->create(9, 5, 'right_text_1', ['en' => 'Proven', 'da' => 'Bevist']);
        $this->create(9, 5, 'right_text_2', ['en' => 'Expertise', 'da' => 'Ekspertise']);
        $this->create(9, 5, 'right_text_3', ['en' => 'Innovative', 'da' => 'Innovativ']);
        $this->create(9, 5, 'image_main', ['en' => '/uploads/nordicstandard/bg1-1.png', 'da' => '/uploads/nordicstandard/bg1-1.png'], 'file');
        $this->create(9, 5, 'image_icon', ['en' => '/uploads/nordicstandard/icon1.svg', 'da' => '/uploads/nordicstandard/icon1.svg'], 'file');
        $this->create(9, 5, 'image_main_mobile', ['en' => '/uploads/nordicstandard/bg1.png', 'da' => '/uploads/nordicstandard/bg1.png'], 'file');        

        $this->create(10, 7, 'space', ['en' => '4']);
    }

    private function create($page_widget_id, $widget_id, $key, $values = [], $type = 'input')
    {
        $field = new Field;
        $field->widget_id = $widget_id;
        $field->type = $type;
        $field->key = $key;
        $field->save();

        if (!empty($values)) {
            $fieldValue = new FieldValue;
            $fieldValue->page_widget_id = $page_widget_id;
            $fieldValue->field_id = $field->id; //header
            $fieldValue->setTranslations('value', $values);
            $fieldValue->save();
        }
    }
}
