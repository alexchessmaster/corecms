<?php

namespace Database\Seeders;

use App\Models\Page;
use App\Models\Field;
use App\Models\Widget;
use App\Models\Language;
use App\Models\FieldValue;
use Illuminate\Support\Str;
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
        $this->create(4, 19, 'item_5_link', ['en' => "/en/web-development", 'da' => '/' . Str::slug('Webudvikling')]);

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

        $this->create(7, 20, 'subtitle', ['en' => 'Our Mode', 'da' => 'Vores Metode']);
        $this->create(7, 20, 'title', ['en' => 'How we do', 'da' => 'Sådan gør vi']);
        $this->create(7, 20, 'description', ['en' => 'Save time and money with our powerful method.', 'da' => 'Spar tid og penge med vores effektive metode.']);
        $this->create(7, 20, 'link_text', ['en' => 'Contact us', 'da' => 'Kontakt os']);
        $this->create(7, 20, 'link_url', ['en' => '/en/contact', 'da' => '/da/kontakt']);
        $this->create(7, 20, 'bg_image', ['en' => '/uploads/nordicstandard/bg-shape-1.svg', 'da' => '/uploads/nordicstandard/bg-shape-1.svg'], 'file');
        $this->create(7, 20, 'section_1_image', ['en' => '/uploads/nordicstandard/hwd-icon-1.svg', 'da' => '/uploads/nordicstandard/hwd-icon-1.svg'], 'file');
        $this->create(7, 20, 'section_1_title', ['en' => 'Brainstroming', 'da' => 'Brainstorming']);
        $this->create(7, 20, 'section_1_paragraph', ['en' => 'Ideas', 'da' => 'Ideer']);
        $this->create(7, 20, 'section_2_image', ['en' => '/uploads/nordicstandard/hwd-icon-2.svg', 'da' => '/uploads/nordicstandard/hwd-icon-2.svg'], 'file');
        $this->create(7, 20, 'section_2_title', ['en' => 'Product', 'da' => 'Produkt']);
        $this->create(7, 20, 'section_2_paragraph', ['en' => 'Design', 'da' => 'Design']);
        $this->create(7, 20, 'section_3_image', ['en' => '/uploads/nordicstandard/hwd-icon-3.svg', 'da' => '/uploads/nordicstandard/hwd-icon-3.svg'], 'file');
        $this->create(7, 20, 'section_3_title', ['en' => 'Front-End', 'da' => 'Front-End']);
        $this->create(7, 20, 'section_3_paragraph', ['en' => 'Development', 'da' => 'Udvikling']);
        $this->create(7, 20, 'section_4_image', ['en' => '/uploads/nordicstandard/hwd-icon-4.svg', 'da' => '/uploads/nordicstandard/hwd-icon-4.svg'], 'file');
        $this->create(7, 20, 'section_4_title', ['en' => 'SEO', 'da' => 'SEO']);
        $this->create(7, 20, 'section_4_paragraph', ['en' => 'Optimization', 'da' => 'Optimering']);
        $this->create(7, 20, 'section_5_image', ['en' => '/uploads/nordicstandard/hwd-icon-5.svg', 'da' => '/uploads/nordicstandard/hwd-icon-5.svg'], 'file');
        $this->create(7, 20, 'section_5_title', ['en' => 'Back-End', 'da' => 'Back-End']);
        $this->create(7, 20, 'section_5_paragraph', ['en' => 'Development', 'da' => 'Udvikling']);
        $this->create(7, 20, 'section_6_image', ['en' => '/uploads/nordicstandard/hwd-icon-6.svg', 'da' => '/uploads/nordicstandard/hwd-icon-6.svg'], 'file');
        $this->create(7, 20, 'section_6_title', ['en' => 'Digital', 'da' => 'Digital']);
        $this->create(7, 20, 'section_6_paragraph', ['en' => 'Marketing', 'da' => 'Marketing']);

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

        // Contact us Page
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

        // App development Page
        $this->create(12, 22, 'subtitle', ['en' => 'INNOVATION', 'da' => 'INNOVATION']);
        $this->create(12, 22, 'title', ['en' => 'Top-tier Mobile App Development', 'da' => 'Topklasses Mobil App-udvikling']);
        $this->create(12, 22, 'description', ['en' => 'We create apps that are simple, powerful, and tailored to your needs, making your ideas come to life.', 'da' => 'Vi skaber apps, der er enkle, kraftfulde og tilpasset dine behov, som får dine ideer til at leve.']);

        $this->create(13, 26, 'subtitle', ['en' => 'MOBILE APPS MADE EASY', 'da' => 'MOBILE APPS GJORT NEMT']);
        $this->create(13, 26, 'title', ['en' => 'Custom Mobile App Solutions', 'da' => 'Skræddersyede Mobil App-løsninger']);
        $this->create(13, 26, 'description', ['en' => 'Our team builds user-friendly mobile apps that help bring your ideas to life and grow your business.', 'da' => 'Vores team bygger brugervenlige mobilapps, der hjælper med at realisere dine ideer og vækste din virksomhed.']);
        $this->create(13, 26, 'image', ['en' => '/uploads/nordicstandard/app-development-image-1.jpeg'], 'file');
        $this->create(13, 26, 'image_alt', ['en' => 'Mobile app development', 'da' => 'Mobil app-udvikling']);
        $this->create(13, 26, 'content', ['en' => 'We specialize in creating mobile apps that work seamlessly on all devices. Whether you need an app for iOS, Android, or both, we ensure the best experience for your users. Let us make your vision a reality.', 'da' => 'Vi specialiserer os i at skabe mobilapps, der fungerer problemfrit på alle enheder. Uanset om du har brug for en app til iOS, Android eller begge, sikrer vi den bedste oplevelse for dine brugere. Lad os gøre din vision til virkelighed.'], 'textarea_large');

        $this->create(14, 25, 'subtitle', ['en' => "Affordable Plans", 'da' => "Overkommelige Planer"]);
        $this->create(14, 25, 'title', ['en' => "Choose the Right Plan for Your App", 'da' => "Vælg Den Rette Plan Til Din App"]);
        $this->create(14, 25, 'description', ['en' => "We offer flexible pricing to help you build your mobile app without breaking the bank. Pick the plan that works best for you.", 'da' => "Vi tilbyder fleksible priser, der hjælper dig med at bygge din mobilapp uden at sprænge budgettet. Vælg den plan, der passer bedst til dig."]);
        $this->create(14, 25, 'plan_1_price', ['en' => "$10", 'da' => "$10"]);
        $this->create(14, 25, 'plan_1_duration', ['en' => "per month", 'da' => "pr. måned"]);
        $this->create(14, 25, 'plan_1_title', ['en' => "Starter Package", 'da' => "Startpakke"]);
        $this->create(14, 25, 'plan_1_description', ['en' => "Perfect for small projects", 'da' => "Perfekt til små projekter"]);
        $this->create(14, 25, 'plan_1_item_1', ['en' => "30 Days Free Trial", 'da' => "30 Dages Gratis Prøveperiode"]);
        $this->create(14, 25, 'plan_1_item_2', ['en' => "Basic App Features", 'da' => "Grundlæggende App-funktioner"]);
        $this->create(14, 25, 'plan_1_item_3', ['en' => "Secure Data Backup", 'da' => "Sikker Datasikkerhedskopiering"]);
        $this->create(14, 25, 'plan_1_item_4', ['en' => "Cloud Sync", 'da' => "Cloud-synkronisering"]);
        $this->create(14, 25, 'plan_1_item_5', ['en' => "App Updates", 'da' => "App-opdateringer"]);
        $this->create(14, 25, 'plan_1_link', ['en' => "/contact", 'da' => "/kontakt"]);
        $this->create(14, 25, 'plan_1_link_text', ['en' => "Contact Us", 'da' => "Kontakt Os"]);
        $this->create(14, 25, 'plan_2_price', ['en' => "$20", 'da' => "$20"]);
        $this->create(14, 25, 'plan_2_duration', ['en' => "per month", 'da' => "pr. måned"]);
        $this->create(14, 25, 'plan_2_title', ['en' => "Standard Package", 'da' => "Standardpakke"]);
        $this->create(14, 25, 'plan_2_description', ['en' => "For growing businesses", 'da' => "Til voksende virksomheder"]);
        $this->create(14, 25, 'plan_2_item_1', ['en' => "30 Days Free Trial", 'da' => "30 Dages Gratis Prøveperiode"]);
        $this->create(14, 25, 'plan_2_item_2', ['en' => "Advanced App Features", 'da' => "Avancerede App-funktioner"]);
        $this->create(14, 25, 'plan_2_item_3', ['en' => "Priority Support", 'da' => "Prioriteret Support"]);
        $this->create(14, 25, 'plan_2_item_4', ['en' => "Secure Data Backup", 'da' => "Sikker Datasikkerhedskopiering"]);
        $this->create(14, 25, 'plan_2_item_5', ['en' => "Cloud Sync & Updates", 'da' => "Cloud-synkronisering & Opdateringer"]);
        $this->create(14, 25, 'plan_2_link', ['en' => "/contact", 'da' => "/kontakt"]);
        $this->create(14, 25, 'plan_2_link_text', ['en' => "Contact Us", 'da' => "Kontakt Os"]);
        $this->create(14, 25, 'plan_3_price', ['en' => "$30", 'da' => "$30"]);
        $this->create(14, 25, 'plan_3_duration', ['en' => "per month", 'da' => "pr. måned"]);
        $this->create(14, 25, 'plan_3_title', ['en' => "Premium Package", 'da' => "Premiumpakke"]);
        $this->create(14, 25, 'plan_3_description', ['en' => "For full-scale app development", 'da' => "Til fuld skala app-udvikling"]);
        $this->create(14, 25, 'plan_3_item_1', ['en' => "30 Days Free Trial", 'da' => "30 Dages Gratis Prøveperiode"]);
        $this->create(14, 25, 'plan_3_item_2', ['en' => "All Premium Features", 'da' => "Alle Premium-funktioner"]);
        $this->create(14, 25, 'plan_3_item_3', ['en' => "Custom App Design", 'da' => "Skræddersyet App-design"]);
        $this->create(14, 25, 'plan_3_item_4', ['en' => "Dedicated Support", 'da' => "Dedikeret Support"]);
        $this->create(14, 25, 'plan_3_item_5', ['en' => "Frequent App Updates", 'da' => "Hyppige App-opdateringer"]);
        $this->create(14, 25, 'plan_3_link', ['en' => "/contact", 'da' => "/kontakt"]);
        $this->create(14, 25, 'plan_3_link_text', ['en' => "Contact Us", 'da' => "Kontakt Os"]);

        $this->create(15, 24, 'image', ['en' => '/uploads/nordicstandard/app-development-image-2.jpeg'], 'file');
        $this->create(15, 24, 'image_alt', ['en' => 'Mobile app development', 'da' => 'Mobil app-udvikling']);
        $this->create(15, 24, 'title', ['en' => 'Why Choose Our Mobile App Development Services?', 'da' => 'Hvorfor Vælge Vores Mobile App-udviklingstjenester?']);
        $this->create(15, 24, 'content', ['en' => "<p>We don't offer one-size-fits-all solutions. Each mobile app we develop is tailored specifically to your business needs and goals. Whether you're looking for an app that is simple and efficient, or one with advanced features, we've got you covered.</p><p>Our mobile app development services are designed to be flexible and scalable, ensuring that your app grows with your business. We focus on creating apps that are not only user-friendly but also optimized for performance and security, so you can trust that your app will serve you and your customers well for years to come.</p>", 'da' => "<p>Vi tilbyder ikke universelle løsninger. Hver mobilapp, vi udvikler, er skræddersyet specifikt til dine forretningsbehov og mål. Uanset om du søger en app, der er enkel og effektiv, eller en med avancerede funktioner, har vi løsningen.</p><p>Vores mobile app-udviklingstjenester er designet til at være fleksible og skalerbare, hvilket sikrer, at din app vokser med din virksomhed. Vi fokuserer på at skabe apps, der ikke blot er brugervenlige, men også optimeret for ydeevne og sikkerhed, så du kan stole på, at din app vil tjene dig og dine kunder godt i mange år fremover.</p>"], 'textarea_large');

        $this->create(16, 23, 'image', ['en' => '/uploads/nordicstandard/app-development-image-3.jpeg'], 'file');
        $this->create(16, 23, 'image_alt', ['en' => 'Mobile app development', 'da' => 'Mobil app-udvikling']);
        $this->create(16, 23, 'title', ['en' => 'Build Your Mobile App with Confidence', 'da' => 'Byg Din Mobilapp Med Selvtillid']);
        $this->create(16, 23, 'content', ['en' => "<p>We specialize in creating mobile apps that are designed with your business in mind. Whether you're launching a new app or improving an existing one, we work closely with you to bring your vision to life.</p><p>Our team focuses on making sure your app is not only functional but also easy to use. From smooth navigation to secure data handling, we ensure that your app provides a seamless experience for your users. Trust us to deliver an app that will help you grow and connect with your audience.</p>", 'da' => "<p>Vi specialiserer os i at skabe mobilapps, der er designet med din virksomhed for øje. Uanset om du lancerer en ny app eller forbedrer en eksisterende, arbejder vi tæt sammen med dig for at bringe din vision til live.</p><p>Vores team fokuserer på at sikre, at din app ikke blot er funktionel, men også let at bruge. Fra smidig navigation til sikker datahåndtering garanterer vi, at din app giver en problemfri oplevelse for dine brugere. Stol på os med at levere en app, der vil hjælpe dig med at vokse og forbinde med din målgruppe.</p>"], 'textarea_large');

        // Hosting Service Page
        $i = 176;
        $this->addFieldValue($i++, 17, 22, 'subtitle', ['en' => 'RELIABLE HOSTING', 'da' => 'PÅLIDELIG HOSTING']);
        $this->addFieldValue($i++, 17, 22, 'title', ['en' => 'Top-tier Hosting Solutions', 'da' => 'Topklasse Hosting-løsninger']);
        $this->addFieldValue($i++, 17, 22, 'description', ['en' => 'Our hosting services are fast, secure, and tailored to meet your needs, ensuring your website or application runs smoothly.', 'da' => 'Vores hostingtjenester er hurtige, sikre og tilpasset dine behov, hvilket sikrer, at din hjemmeside eller applikation kører problemfrit.']);

        $this->addFieldValue($i++, 18, 26, 'subtitle', ['en' => 'HOSTING MADE SIMPLE', 'da' => 'HOSTING GJORT ENKELT']);
        $this->addFieldValue($i++, 18, 26, 'title', ['en' => 'Custom Hosting Plans', 'da' => 'Skræddersyede Hosting-planer']);
        $this->addFieldValue($i++, 18, 26, 'description', ['en' => 'We offer flexible and reliable hosting options that keep your website or app running seamlessly.', 'da' => 'Vi tilbyder fleksible og pålidelige hostingmuligheder, der holder din hjemmeside eller app kørende uden problemer.']);
        $this->addFieldValue($i++, 18, 26, 'image', ['en' => '/uploads/nordicstandard/hosting-image-1.jpeg'], 'file');
        $this->addFieldValue($i++, 18, 26, 'image_alt', ['en' => 'Hosting services', 'da' => 'Hostingtjenester']);
        $this->addFieldValue($i++, 18, 26, 'content', ['en' => 'Our hosting services are designed to provide maximum uptime, speed, and security. Whether you need shared hosting, VPS, or a dedicated server, we\'ve got you covered. Enjoy hassle-free hosting with our expert support team available to assist you every step of the way.', 'da' => 'Vores hostingtjenester er designet til at give maksimal oppetid, hastighed og sikkerhed. Uanset om du har brug for delt hosting, VPS eller en dedikeret server, har vi løsningen. Nyd problemfri hosting med vores ekspertteam, der er klar til at hjælpe dig hele vejen.'], 'textarea_large');

        $this->addFieldValue($i++, 19, 25, 'subtitle', ['en' => "Affordable Plans", 'da' => "Overkommelige Planer"]);
        $this->addFieldValue($i++, 19, 25, 'title', ['en' => "Choose the Right Hosting Plan", 'da' => "Vælg Den Rette Hosting-plan"]);
        $this->addFieldValue($i++, 19, 25, 'description', ['en' => "We offer competitive pricing and a variety of hosting plans to suit your needs. Pick the plan that works best for you.", 'da' => "Vi tilbyder konkurrencedygtige priser og forskellige hostingplaner, der passer til dine behov. Vælg den plan, der fungerer bedst for dig."]);

        $this->addFieldValue($i++, 19, 25, 'plan_1_price', ['en' => "$5", 'da' => "$5"]);
        $this->addFieldValue($i++, 19, 25, 'plan_1_duration', ['en' => "per month", 'da' => "pr. måned"]);
        $this->addFieldValue($i++, 19, 25, 'plan_1_title', ['en' => "Basic Hosting", 'da' => "Basis Hosting"]);
        $this->addFieldValue($i++, 19, 25, 'plan_1_description', ['en' => "Perfect for small websites", 'da' => "Perfekt til små hjemmesider"]);
        $this->addFieldValue($i++, 19, 25, 'plan_1_item_1', ['en' => "10 GB Storage", 'da' => "10 GB Lagerplads"]);
        $this->addFieldValue($i++, 19, 25, 'plan_1_item_2', ['en' => "100 GB Bandwidth", 'da' => "100 GB Båndbredde"]);
        $this->addFieldValue($i++, 19, 25, 'plan_1_item_3', ['en' => "Free SSL Certificate", 'da' => "Gratis SSL-certifikat"]);
        $this->addFieldValue($i++, 19, 25, 'plan_1_item_4', ['en' => "Basic Support", 'da' => "Basis Support"]);
        $this->addFieldValue($i++, 19, 25, 'plan_1_item_5', ['en' => "Daily Backups", 'da' => "Daglige Sikkerhedskopier"]);
        $this->addFieldValue($i++, 19, 25, 'plan_1_link', ['en' => "/contact", 'da' => "/kontakt"]);
        $this->addFieldValue($i++, 19, 25, 'plan_1_link_text', ['en' => "Contact Us", 'da' => "Kontakt Os"]);

        $this->addFieldValue($i++, 19, 25, 'plan_2_price', ['en' => "$15", 'da' => "$15"]);
        $this->addFieldValue($i++, 19, 25, 'plan_2_duration', ['en' => "per month", 'da' => "pr. måned"]);
        $this->addFieldValue($i++, 19, 25, 'plan_2_title', ['en' => "Advanced Hosting", 'da' => "Avanceret Hosting"]);
        $this->addFieldValue($i++, 19, 25, 'plan_2_description', ['en' => "For growing websites", 'da' => "Til voksende hjemmesider"]);
        $this->addFieldValue($i++, 19, 25, 'plan_2_item_1', ['en' => "50 GB Storage", 'da' => "50 GB Lagerplads"]);
        $this->addFieldValue($i++, 19, 25, 'plan_2_item_2', ['en' => "Unlimited Bandwidth", 'da' => "Ubegrænset Båndbredde"]);
        $this->addFieldValue($i++, 19, 25, 'plan_2_item_3', ['en' => "Free SSL Certificate", 'da' => "Gratis SSL-certifikat"]);
        $this->addFieldValue($i++, 19, 25, 'plan_2_item_4', ['en' => "Priority Support", 'da' => "Prioriteret Support"]);
        $this->addFieldValue($i++, 19, 25, 'plan_2_item_5', ['en' => "Automatic Backups", 'da' => "Automatiske Sikkerhedskopier"]);
        $this->addFieldValue($i++, 19, 25, 'plan_2_link', ['en' => "/contact", 'da' => "/kontakt"]);
        $this->addFieldValue($i++, 19, 25, 'plan_2_link_text', ['en' => "Contact Us", 'da' => "Kontakt Os"]);

        $this->addFieldValue($i++, 19, 25, 'plan_3_price', ['en' => "$25", 'da' => "$25"]);
        $this->addFieldValue($i++, 19, 25, 'plan_3_duration', ['en' => "per month", 'da' => "pr. måned"]);
        $this->addFieldValue($i++, 19, 25, 'plan_3_title', ['en' => "Premium Hosting", 'da' => "Premium Hosting"]);
        $this->addFieldValue($i++, 19, 25, 'plan_3_description', ['en' => "For large-scale websites or applications", 'da' => "Til store hjemmesider eller applikationer"]);
        $this->addFieldValue($i++, 19, 25, 'plan_3_item_1', ['en' => "Unlimited Storage", 'da' => "Ubegrænset Lagerplads"]);
        $this->addFieldValue($i++, 19, 25, 'plan_3_item_2', ['en' => "Unlimited Bandwidth", 'da' => "Ubegrænset Båndbredde"]);
        $this->addFieldValue($i++, 19, 25, 'plan_3_item_3', ['en' => "Dedicated IP", 'da' => "Dedikeret IP"]);
        $this->addFieldValue($i++, 19, 25, 'plan_3_item_4', ['en' => "24/7 Support", 'da' => "24/7 Support"]);
        $this->addFieldValue($i++, 19, 25, 'plan_3_item_5', ['en' => "Custom Backups", 'da' => "Tilpassede Sikkerhedskopier"]);
        $this->addFieldValue($i++, 19, 25, 'plan_3_link', ['en' => "/contact", 'da' => "/kontakt"]);
        $this->addFieldValue($i++, 19, 25, 'plan_3_link_text', ['en' => "Contact Us", 'da' => "Kontakt Os"]);

        $this->addFieldValue($i++, 20, 24, 'image', ['en' => '/uploads/nordicstandard/hosting-image-2.jpeg'], 'file');
        $this->addFieldValue($i++, 20, 24, 'image_alt', ['en' => 'Hosting services', 'da' => 'Hostingtjenester']);
        $this->addFieldValue($i++, 20, 24, 'title', ['en' => 'Why Choose Our Hosting Services?', 'da' => 'Hvorfor Vælge Vores Hostingtjenester?']);
        $this->addFieldValue($i++, 20, 24, 'content', ['en' => "<p>We provide reliable hosting solutions tailored to your business needs. With advanced security features and optimized performance, you can trust that your website or application will perform at its best.</p><p>Our hosting plans are flexible and scalable, ensuring they grow with your business. We focus on delivering services that are easy to manage, with regular backups, top-notch security, and 24/7 support to keep your business online and running smoothly.</p>", 'da' => "<p>Vi leverer pålidelige hostingløsninger, der er skræddersyet til dine forretningsbehov. Med avancerede sikkerhedsfunktioner og optimeret ydeevne kan du stole på, at din hjemmeside eller applikation præsterer bedst muligt.</p><p>Vores hostingplaner er fleksible og skalerbare, hvilket sikrer, at de vokser med din virksomhed. Vi fokuserer på at levere tjenester, der er nemme at administrere, med regelmæssige sikkerhedskopier, topmoderne sikkerhed og 24/7 support for at holde din virksomhed online og kørende problemfrit.</p>"], 'textarea_large');

        $this->addFieldValue($i++, 21, 23, 'image', ['en' => '/uploads/nordicstandard/hosting-image-3.jpeg'], 'file');
        $this->addFieldValue($i++, 21, 23, 'image_alt', ['en' => 'Hosting services', 'da' => 'Hostingtjenester']);
        $this->addFieldValue($i++, 21, 23, 'title', ['en' => 'Host Your Website with Confidence', 'da' => 'Host Din Hjemmeside Med Selvtillid']);
        $this->addFieldValue($i++, 21, 23, 'content', ['en' => "<p>We offer hosting solutions that ensure your website or application remains online and performs at its peak. From robust security features to lightning-fast speeds, our hosting services are designed with your success in mind.</p><p>Partner with us to experience hassle-free hosting backed by a dedicated team of professionals who are ready to assist you at any time. Let us handle your hosting needs, so you can focus on growing your business.</p>", 'da' => "<p>Vi tilbyder hostingløsninger, der sikrer, at din hjemmeside eller applikation forbliver online og yder på sit højeste niveau. Fra robuste sikkerhedsfunktioner til lynhurtige hastigheder er vores hostingtjenester designet med din succes for øje.</p><p>Indgå partnerskab med os for at opleve problemfri hosting støttet af et dedikeret team af professionelle, der er klar til at hjælpe dig til enhver tid. Lad os tage os af dine hostingbehov, så du kan fokusere på at vækste din virksomhed.</p>"], 'textarea_large');

        // IT Consultancy Service Page
        $i = 176;
        $this->addFieldValue($i++, 22, 22, 'subtitle', ['en' => 'EXPERT GUIDANCE', 'da' => 'EKSPERT VEJLEDNING']);
        $this->addFieldValue($i++, 22, 22, 'title', ['en' => 'Professional IT Consultancy Services', 'da' => 'Professionelle IT-konsulenttjenester']);
        $this->addFieldValue($i++, 22, 22, 'description', ['en' => 'We help businesses streamline their IT operations, improve efficiency, and drive innovation through tailored solutions.', 'da' => 'Vi hjælper virksomheder med at effektivisere deres IT-drift, forbedre effektiviteten og fremme innovation gennem skræddersyede løsninger.']);

        $this->addFieldValue($i++, 23, 26, 'subtitle', ['en' => 'TAILORED SOLUTIONS', 'da' => 'SKRÆDDERSYEDE LØSNINGER']);
        $this->addFieldValue($i++, 23, 26, 'title', ['en' => 'Your Trusted IT Consulting Partner', 'da' => 'Din betroede IT-konsulentpartner']);
        $this->addFieldValue($i++, 23, 26, 'description', ['en' => 'Our team provides expert advice and customized IT solutions to help your business thrive in a fast-paced digital world.', 'da' => 'Vores team yder ekspertråd og tilpassede IT-løsninger for at hjælpe din virksomhed med at trives i en hurtig digital verden.']);
        $this->addFieldValue($i++, 23, 26, 'image', ['en' => '/uploads/nordicstandard/it-consultancy-image-1.jpeg'], 'file');
        $this->addFieldValue($i++, 23, 26, 'image_alt', ['en' => 'IT Consultancy services', 'da' => 'IT-konsulenttjenester']);
        $this->addFieldValue($i++, 23, 26, 'content', ['en' => 'Our IT consultancy services are designed to help your business overcome challenges and achieve its technology goals. Whether you need guidance on system integration, cloud solutions, or cybersecurity, we deliver results tailored to your needs. Partner with us to ensure your IT infrastructure is robust, scalable, and future-proof.', 'da' => 'Vores IT-konsulenttjenester er designet til at hjælpe din virksomhed med at overvinde udfordringer og nå sine teknologiske mål. Uanset om du har brug for vejledning inden for systemintegration, cloud-løsninger eller cybersikkerhed, leverer vi resultater, der er skræddersyet til dine behov. Samarbejd med os for at sikre, at din IT-infrastruktur er robust, skalerbar og fremtidssikret.'], 'textarea_large');

        $this->addFieldValue($i++, 24, 25, 'subtitle', ['en' => "Flexible Plans", 'da' => 'Fleksible Planer']);
        $this->addFieldValue($i++, 24, 25, 'title', ['en' => "Choose the Right IT Consulting Plan", 'da' => 'Vælg den rigtige IT-konsulentplan']);
        $this->addFieldValue($i++, 24, 25, 'description', ['en' => "We offer flexible pricing plans to meet your IT consultancy needs without exceeding your budget.", 'da' => 'Vi tilbyder fleksible prisplaner, der opfylder dine IT-konsulentbehov uden at overskride dit budget.']);
        $this->addFieldValue($i++, 24, 25, 'plan_1_price', ['en' => "$50", 'da' => '50 kr.']);
        $this->addFieldValue($i++, 24, 25, 'plan_1_duration', ['en' => "per hour", 'da' => 'pr. time']);
        $this->addFieldValue($i++, 24, 25, 'plan_1_title', ['en' => "Basic Plan", 'da' => 'Basis Plan']);
        $this->addFieldValue($i++, 24, 25, 'plan_1_description', ['en' => "Perfect for small businesses", 'da' => 'Perfekt til små virksomheder']);
        $this->addFieldValue($i++, 24, 25, 'plan_1_item_1', ['en' => "Initial IT Assessment", 'da' => 'Indledende IT-vurdering']);
        $this->addFieldValue($i++, 24, 25, 'plan_1_item_2', ['en' => "System Recommendations", 'da' => 'Systemanbefalinger']);
        $this->addFieldValue($i++, 24, 25, 'plan_1_item_3', ['en' => "On-demand Consultation", 'da' => 'Konsultation efter behov']);
        $this->addFieldValue($i++, 24, 25, 'plan_1_item_4', ['en' => "Basic Support", 'da' => 'Grundlæggende support']);
        $this->addFieldValue($i++, 24, 25, 'plan_1_item_5', ['en' => "Documentation Provided", 'da' => 'Dokumentation leveret']);
        $this->addFieldValue($i++, 24, 25, 'plan_1_link', ['en' => "/contact", 'da' => '/kontakt']);
        $this->addFieldValue($i++, 24, 25, 'plan_1_link_text', ['en' => "Contact Us", 'da' => 'Kontakt Os']);
        $this->addFieldValue($i++, 24, 25, 'plan_2_price', ['en' => "$100", 'da' => '100 kr.']);
        $this->addFieldValue($i++, 24, 25, 'plan_2_duration', ['en' => "per hour", 'da' => 'pr. time']);
        $this->addFieldValue($i++, 24, 25, 'plan_2_title', ['en' => "Standard Plan", 'da' => 'Standard Plan']);
        $this->addFieldValue($i++, 24, 25, 'plan_2_description', ['en' => "For mid-sized businesses", 'da' => 'Til mellemstore virksomheder']);
        $this->addFieldValue($i++, 24, 25, 'plan_2_item_1', ['en' => "Comprehensive IT Assessment", 'da' => 'Omfattende IT-vurdering']);
        $this->addFieldValue($i++, 24, 25, 'plan_2_item_2', ['en' => "System Design and Implementation", 'da' => 'Systemdesign og implementering']);
        $this->addFieldValue($i++, 24, 25, 'plan_2_item_3', ['en' => "Proactive Monitoring", 'da' => 'Proaktiv overvågning']);
        $this->addFieldValue($i++, 24, 25, 'plan_2_item_4', ['en' => "24/7 Support", 'da' => '24/7 Support']);
        $this->addFieldValue($i++, 24, 25, 'plan_2_item_5', ['en' => "Custom Reports", 'da' => 'Tilpassede rapporter']);
        $this->addFieldValue($i++, 24, 25, 'plan_2_link', ['en' => "/contact", 'da' => '/kontakt']);
        $this->addFieldValue($i++, 24, 25, 'plan_2_link_text', ['en' => "Contact Us", 'da' => 'Kontakt Os']);
        $this->addFieldValue($i++, 24, 25, 'plan_3_price', ['en' => "$150", 'da' => '150 kr.']);
        $this->addFieldValue($i++, 24, 25, 'plan_3_duration', ['en' => "per hour", 'da' => 'pr. time']);
        $this->addFieldValue($i++, 24, 25, 'plan_3_title', ['en' => "Premium Plan", 'da' => 'Premium Plan']);
        $this->addFieldValue($i++, 24, 25, 'plan_3_description', ['en' => "For enterprise-level businesses", 'da' => 'Til virksomheder på virksomhedsniveau']);
        $this->addFieldValue($i++, 24, 25, 'plan_3_item_1', ['en' => "Dedicated IT Consultant", 'da' => 'Dedikeret IT-konsulent']);
        $this->addFieldValue($i++, 24, 25, 'plan_3_item_2', ['en' => "End-to-End IT Management", 'da' => 'Fuld IT-administration']);
        $this->addFieldValue($i++, 24, 25, 'plan_3_item_3', ['en' => "Customized IT Solutions", 'da' => 'Skræddersyede IT-løsninger']);
        $this->addFieldValue($i++, 24, 25, 'plan_3_item_4', ['en' => "Priority Support", 'da' => 'Prioriteret support']);
        $this->addFieldValue($i++, 24, 25, 'plan_3_item_5', ['en' => "Ongoing Optimization", 'da' => 'Løbende optimering']);
        $this->addFieldValue($i++, 24, 25, 'plan_3_link', ['en' => "/contact", 'da' => '/kontakt']);
        $this->addFieldValue($i++, 24, 25, 'plan_3_link_text', ['en' => "Contact Us", 'da' => 'Kontakt Os']);

        $this->addFieldValue($i++, 25, 24, 'image', ['en' => '/uploads/nordicstandard/it-consultancy-image-2.jpeg'], 'file');
        $this->addFieldValue($i++, 25, 24, 'image_alt', ['en' => 'IT Consultancy services', 'da' => 'IT-konsulenttjenester']);
        $this->addFieldValue($i++, 25, 24, 'title', ['en' => 'Why Choose Our IT Consultancy Services?', 'da' => 'Hvorfor vælge vores IT-konsulenttjenester?']);
        $this->addFieldValue($i++, 25, 24, 'content', ['en' => "<p>We provide businesses with a comprehensive range of IT consultancy services that are tailored to meet their unique needs. From infrastructure upgrades to digital transformation, our experts work with you to identify and implement the best solutions for your business.</p><p>Our team stays up-to-date with the latest technologies, ensuring that we deliver innovative solutions that enhance your operations and boost your competitive edge. Trust us to help you navigate the complexities of IT and drive your business forward.</p>", 'da' => "<p>Vi tilbyder virksomheder en omfattende vifte af IT-konsulenttjenester, der er skræddersyet til at opfylde deres unikke behov. Fra infrastruktur-opgraderinger til digital transformation arbejder vores eksperter sammen med dig for at identificere og implementere de bedste løsninger for din virksomhed.</p><p>Vores team holder sig ajour med de seneste teknologier og sikrer, at vi leverer innovative løsninger, der forbedrer dine processer og styrker din konkurrenceevne. Stol på os for at hjælpe dig med at navigere gennem IT-kompleksiteten og drive din virksomhed fremad.</p>"], 'textarea_large');

        $this->addFieldValue($i++, 26, 23, 'image', ['en' => '/uploads/nordicstandard/it-consultancy-image-3.jpeg'], 'file');
        $this->addFieldValue($i++, 26, 23, 'image_alt', ['en' => 'IT Consultancy services', 'da' => 'IT-konsulenttjenester']);
        $this->addFieldValue($i++, 26, 23, 'title', ['en' => 'Achieve Your Goals with Expert IT Consultancy', 'da' => 'Opnå dine mål med ekspert IT-konsulentbistand']);
        $this->addFieldValue($i++, 26, 23, 'content', ['en' => "<p>Our IT consultancy services are built to align with your business objectives. From IT strategy development to implementation, we guide you through every step of the process to ensure success.</p><p>Let us help you optimize your IT infrastructure, streamline operations, and unlock the full potential of technology for your business. With our expertise, you can focus on what matters most – growing your business.</p>", 'da' => "<p>Vores IT-konsulenttjenester er bygget til at være i overensstemmelse med dine forretningsmål. Fra IT-strategiudvikling til implementering vejleder vi dig gennem hver eneste trin i processen for at sikre succes.</p><p>Lad os hjælpe dig med at optimere din IT-infrastruktur, strømline driften og frigøre den fulde teknologiske potentiale for din virksomhed. Med vores ekspertise kan du fokusere på det vigtigste – at vækste din virksomhed.</p>"], 'textarea_large');

        // Secure VPN Service Page
        $i = 176;
        $this->addFieldValue($i++, 27, 22, 'subtitle', ['en' => 'SECURITY AND PRIVACY', 'da' => 'SIKKERHED OG PRIVATLIV']);
        $this->addFieldValue($i++, 27, 22, 'title', ['en' => 'Top-notch Secure VPN Services', 'da' => 'Førsteklasses Sikker VPN-tjenester']);
        $this->addFieldValue($i++, 27, 22, 'description', ['en' => 'Our VPN solutions ensure your data is protected and your online activity remains private, giving you peace of mind.', 'da' => 'Vores VPN-løsninger sikrer, at dine data er beskyttet, og din onlineaktivitet forbliver privat, så du kan være tryg.']);

        $this->addFieldValue($i++, 28, 26, 'subtitle', ['en' => 'BROWSE SAFELY', 'da' => 'SURF SIKKERT']);
        $this->addFieldValue($i++, 28, 26, 'title', ['en' => 'Reliable and Fast VPN Solutions', 'da' => 'Pålidelige og Hurtige VPN-løsninger']);
        $this->addFieldValue($i++, 28, 26, 'description', ['en' => 'Enjoy secure, private, and fast connections with our VPN services, designed to keep your data safe.', 'da' => 'Nyd sikre, private og hurtige forbindelser med vores VPN-tjenester, designet til at beskytte dine data.']);
        $this->addFieldValue($i++, 28, 26, 'image', ['en' => '/uploads/nordicstandard/secure-vpn-image-1.jpeg', 'da' => '/uploads/nordicstandard/secure-vpn-image-1.jpeg'], 'file');
        $this->addFieldValue($i++, 28, 26, 'image_alt', ['en' => 'Secure VPN services', 'da' => 'Sikre VPN-tjenester']);
        $this->addFieldValue($i++, 28, 26, 'content', ['en' => 'Our Secure VPN service protects your online activities with advanced encryption technology. Whether you’re browsing, streaming, or working remotely, our VPN ensures your privacy and security without compromising speed. Experience reliable, uninterrupted access to the web, no matter where you are.','da' => 'Vores sikre VPN-tjeneste beskytter dine onlineaktiviteter med avanceret krypteringsteknologi. Uanset om du surfer, streamer eller arbejder eksternt, sikrer vores VPN din privatliv og sikkerhed uden at gå på kompromis med hastigheden. Oplev pålidelig, uafbrudt adgang til internettet, uanset hvor du er.'], 'textarea_large');

        $this->addFieldValue($i++, 29, 25, 'subtitle', ['en' => "Affordable Plans", 'da' => "Overkommelige Planer"]);
        $this->addFieldValue($i++, 29, 25, 'title', ['en' => "Choose the Right VPN Plan", 'da' => "Vælg den Rette VPN-plan"]);
        $this->addFieldValue($i++, 29, 25, 'description', ['en' => "We offer flexible and cost-effective VPN plans to keep your data safe and your connections secure.", 'da' => "Vi tilbyder fleksible og omkostningseffektive VPN-planer for at beskytte dine data og sikre dine forbindelser."]);
        $this->addFieldValue($i++, 29, 25, 'plan_1_price', ['en' => "$8", 'da' => "8$"]);
        $this->addFieldValue($i++, 29, 25, 'plan_1_duration', ['en' => "per month", 'da' => "pr. måned"]);
        $this->addFieldValue($i++, 29, 25, 'plan_1_title', ['en' => "Basic Plan", 'da' => "Basisplan"]);
        $this->addFieldValue($i++, 29, 25, 'plan_1_description', ['en' => "Perfect for individual use", 'da' => "Perfekt til individuel brug"]);
        $this->addFieldValue($i++, 29, 25, 'plan_1_item_1', ['en' => "Unlimited Bandwidth", 'da' => "Ubegrænset Båndbredde"]);
        $this->addFieldValue($i++, 29, 25, 'plan_1_item_2', ['en' => "AES 256-bit Encryption", 'da' => "AES 256-bit Kryptering"]);
        $this->addFieldValue($i++, 29, 25, 'plan_1_item_3', ['en' => "Access to 1 Server", 'da' => "Adgang til 1 Server"]);
        $this->addFieldValue($i++, 29, 25, 'plan_1_item_4', ['en' => "Basic Support", 'da' => "Basis Support"]);
        $this->addFieldValue($i++, 29, 25, 'plan_1_item_5', ['en' => "No Activity Logs", 'da' => "Ingen Aktivitetslogfiler"]);
        $this->addFieldValue($i++, 29, 25, 'plan_1_link', ['en' => "/contact", 'da' => "/contact"]);
        $this->addFieldValue($i++, 29, 25, 'plan_1_link_text', ['en' => "Contact Us", 'da' => "Kontakt Os"]);
        $this->addFieldValue($i++, 29, 25, 'plan_2_price', ['en' => "$15", 'da' => "15$"]);
        $this->addFieldValue($i++, 29, 25, 'plan_2_duration', ['en' => "per month", 'da' => "pr. måned"]);
        $this->addFieldValue($i++, 29, 25, 'plan_2_title', ['en' => "Advanced Plan", 'da' => "Avanceret Plan"]);
        $this->addFieldValue($i++, 29, 25, 'plan_2_description', ['en' => "For power users and businesses", 'da' => "Til krævende brugere og virksomheder"]);
        $this->addFieldValue($i++, 29, 25, 'plan_2_item_1', ['en' => "Unlimited Bandwidth", 'da' => "Ubegrænset Båndbredde"]);
        $this->addFieldValue($i++, 29, 25, 'plan_2_item_2', ['en' => "AES 256-bit Encryption", 'da' => "AES 256-bit Kryptering"]);
        $this->addFieldValue($i++, 29, 25, 'plan_2_item_3', ['en' => "Access to 1 Server", 'da' => "Adgang til 1 Server"]);
        $this->addFieldValue($i++, 29, 25, 'plan_2_item_4', ['en' => "Priority Support", 'da' => "Prioriteret Support"]);
        $this->addFieldValue($i++, 29, 25, 'plan_2_item_5', ['en' => "No Activity Logs", 'da' => "Ingen Aktivitetslogfiler"]);
        $this->addFieldValue($i++, 29, 25, 'plan_2_link', ['en' => "/contact", 'da' => "/contact"]);
        $this->addFieldValue($i++, 29, 25, 'plan_2_link_text', ['en' => "Contact Us", 'da' => "Kontakt Os"]);
        $this->addFieldValue($i++, 29, 25, 'plan_3_price', ['en' => "$25", 'da' => "25$"]);
        $this->addFieldValue($i++, 29, 25, 'plan_3_duration', ['en' => "per month", 'da' => "pr. måned"]);
        $this->addFieldValue($i++, 29, 25, 'plan_3_title', ['en' => "Premium Plan", 'da' => "Premium Plan"]);
        $this->addFieldValue($i++, 29, 25, 'plan_3_description', ['en' => "For advanced security and performance", 'da' => "Til avanceret sikkerhed og ydeevne"]);
        $this->addFieldValue($i++, 29, 25, 'plan_3_item_1', ['en' => "Unlimited Bandwidth", 'da' => "Ubegrænset Båndbredde"]);
        $this->addFieldValue($i++, 29, 25, 'plan_3_item_2', ['en' => "AES 256-bit Encryption", 'da' => "AES 256-bit Kryptering"]);
        $this->addFieldValue($i++, 29, 25, 'plan_3_item_3', ['en' => "Access to 1 Server", 'da' => "Adgang til 1 Server"]);
        $this->addFieldValue($i++, 29, 25, 'plan_3_item_4', ['en' => "24/7 Priority Support", 'da' => "24/7 Prioriteret Support"]);
        $this->addFieldValue($i++, 29, 25, 'plan_3_item_5', ['en' => "No Activity Logs", 'da' => "Ingen Aktivitetslogfiler"]);
        $this->addFieldValue($i++, 29, 25, 'plan_3_link', ['en' => "/contact", 'da' => "/contact"]);
        $this->addFieldValue($i++, 29, 25, 'plan_3_link_text', ['en' => "Contact Us", 'da' => "Kontakt Os"]);

        $this->addFieldValue($i++, 30, 24, 'image', ['en' => '/uploads/nordicstandard/secure-vpn-image-2.jpeg', 'da' => '/uploads/nordicstandard/secure-vpn-image-2.jpeg'], 'file');
        $this->addFieldValue($i++, 30, 24, 'image_alt', ['en' => 'Secure VPN services', 'da' => 'Sikre VPN-tjenester']);
        $this->addFieldValue($i++, 30, 24, 'title', ['en' => 'Why Choose Our Secure VPN Services?', 'da' => 'Hvorfor Vælge Vores Sikker VPN-tjenester?']);
        $this->addFieldValue($i++, 30, 24, 'content', ['en' => "<p>Our Secure VPN services provide you with enhanced online security, ensuring your sensitive data is protected from hackers and prying eyes. With global server locations, you can access content securely and bypass geo-restrictions with ease.</p><p>Our solutions are easy to use and backed by a dedicated support team, ensuring a smooth experience. Enjoy fast speeds, robust encryption, and unlimited bandwidth, all while maintaining your online privacy.</p>", 'da' => "<p>Vores sikre VPN-tjenester giver dig forbedret online sikkerhed og sikrer, at dine følsomme data er beskyttet mod hackere og nysgerrige øjne. Med globale serverlokationer kan du få adgang til indhold sikkert og omgå geografiske begrænsninger med lethed.</p><p>Vores løsninger er nemme at bruge og understøttes af et dedikeret supportteam, der sikrer en problemfri oplevelse. Nyd hurtige hastigheder, robust kryptering og ubegrænset båndbredde, alt sammen mens du opretholder din online privatliv.</p>"], 'textarea_large');

        $this->addFieldValue($i++, 31, 23, 'image', ['en' => '/uploads/nordicstandard/secure-vpn-image-3.jpeg', 'da' => '/uploads/nordicstandard/secure-vpn-image-3.jpeg'], 'file');
        $this->addFieldValue($i++, 31, 23, 'image_alt', ['en' => 'Secure VPN services', 'da' => 'Sikre VPN-tjenester']);
        $this->addFieldValue($i++, 31, 23, 'title', ['en' => 'Stay Safe Online with Our Secure VPN', 'da' => 'Forbliv Sikker Online med Vores Sikker VPN']);
        $this->addFieldValue($i++, 31, 23, 'content', ['en' => "<p>Our Secure VPN ensures your data is protected while you browse, stream, or work online. With cutting-edge encryption and a global network of servers, you can access the web securely and privately.</p><p>Whether you need a VPN for personal or professional use, we offer reliable solutions to meet your needs. Trust us to keep your online activities safe and secure at all times.</p>", 'da' => "<p>Vores sikre VPN sikrer, at dine data er beskyttet, mens du surfer, streamer eller arbejder online. Med avanceret kryptering og et globalt netværk af servere kan du få adgang til internettet sikkert og privat.</p><p>Uanset om du har brug for en VPN til personlig eller professionel brug, tilbyder vi pålidelige løsninger, der opfylder dine behov. Stol på os til at holde dine online aktiviteter sikre og beskyttede til enhver tid.</p>"], 'textarea_large');

        // AI Services Page Content
        $i = 176;
        $this->addFieldValue($i++, 32, 22, 'subtitle', ['en' => 'EMPOWERING INNOVATION', 'da' => 'STYRKER INNOVATION']);
        $this->addFieldValue($i++, 32, 22, 'title', ['en' => 'Cutting-Edge AI Services', 'da' => 'Førende AI-tjenester']);
        $this->addFieldValue($i++, 32, 22, 'description', ['en' => 'We harness the power of Artificial Intelligence to help your business innovate, automate, and grow.', 'da' => 'Vi udnytter kunstig intelligens til at hjælpe din virksomhed med at innovere, automatisere og vokse.']);

        $this->addFieldValue($i++, 33, 26, 'subtitle', ['en' => 'AI MADE ACCESSIBLE', 'da' => 'AI GJORT TILGÆNGELIGT']);
        $this->addFieldValue($i++, 33, 26, 'title', ['en' => 'Transform Your Business with AI', 'da' => 'Transformer din virksomhed med AI']);
        $this->addFieldValue($i++, 33, 26, 'description', ['en' => 'Our AI services are designed to deliver practical, scalable, and impactful solutions tailored to your business needs.', 'da' => 'Vores AI-tjenester er designet til at levere praktiske, skalerbare og effektive løsninger, der er skræddersyet til dine forretningsmål.']);
        $this->addFieldValue($i++, 33, 26, 'image', ['en' => '/uploads/nordicstandard/ai-service-image-1.jpeg'], 'file');
        $this->addFieldValue($i++, 33, 26, 'image_alt', ['en' => 'AI Services', 'da' => 'AI-tjenester']);
        $this->addFieldValue($i++, 33, 26, 'content', ['en' => 'From data analysis to automation, we specialize in creating AI solutions that drive efficiency and innovation. Partner with us to implement AI technologies that are custom-built for your business, helping you stay ahead of the competition.', 'da' => 'Fra dataanalyse til automatisering specialiserer vi os i at skabe AI-løsninger, der driver effektivitet og innovation. Samarbejd med os om at implementere AI-teknologier, der er specielt bygget til din virksomhed, og hjælp dig med at være foran konkurrenterne.'], 'textarea_large');

        $this->addFieldValue($i++, 34, 25, 'subtitle', ['en' => "Flexible Plans", 'da' => 'Fleksible Planer']);
        $this->addFieldValue($i++, 34, 25, 'title', ['en' => "Choose the Right AI Service Plan", 'da' => 'Vælg den rigtige AI-serviceplan']);
        $this->addFieldValue($i++, 34, 25, 'description', ['en' => "We offer flexible and scalable pricing plans that cater to businesses of all sizes.", 'da' => 'Vi tilbyder fleksible og skalerbare prisplaner, der passer til virksomheder af alle størrelser.']);
        $this->addFieldValue($i++, 34, 25, 'plan_1_price', ['en' => "$500", 'da' => '500 kr.']);
        $this->addFieldValue($i++, 34, 25, 'plan_1_duration', ['en' => "per project", 'da' => 'pr. projekt']);
        $this->addFieldValue($i++, 34, 25, 'plan_1_title', ['en' => "Starter Plan", 'da' => 'Startplan']);
        $this->addFieldValue($i++, 34, 25, 'plan_1_description', ['en' => "Ideal for small-scale AI projects", 'da' => 'Ideel til små AI-projekter']);
        $this->addFieldValue($i++, 34, 25, 'plan_1_item_1', ['en' => "Initial AI Assessment", 'da' => 'Indledende AI-vurdering']);
        $this->addFieldValue($i++, 34, 25, 'plan_1_item_2', ['en' => "Basic AI Models", 'da' => 'Grundlæggende AI-modeller']);
        $this->addFieldValue($i++, 34, 25, 'plan_1_item_3', ['en' => "Data Analysis & Reports", 'da' => 'Dataanalyse & rapporter']);
        $this->addFieldValue($i++, 34, 25, 'plan_1_item_4', ['en' => "Documentation Included", 'da' => 'Dokumentation inkluderet']);
        $this->addFieldValue($i++, 34, 25, 'plan_1_item_5', ['en' => "Custom functions", 'da' => 'Tilpassede funktioner']);
        $this->addFieldValue($i++, 34, 25, 'plan_1_link', ['en' => "/contact", 'da' => '/kontakt']);
        $this->addFieldValue($i++, 34, 25, 'plan_1_link_text', ['en' => "Contact Us", 'da' => 'Kontakt Os']);

        $this->addFieldValue($i++, 34, 25, 'plan_2_price', ['en' => "$1500", 'da' => '1500 kr.']);
        $this->addFieldValue($i++, 34, 25, 'plan_2_duration', ['en' => "per project", 'da' => 'pr. projekt']);
        $this->addFieldValue($i++, 34, 25, 'plan_2_title', ['en' => "Advanced Plan", 'da' => 'Avanceret Plan']);
        $this->addFieldValue($i++, 34, 25, 'plan_2_description', ['en' => "For mid-sized businesses", 'da' => 'Til mellemstore virksomheder']);
        $this->addFieldValue($i++, 34, 25, 'plan_2_item_1', ['en' => "Custom AI Models", 'da' => 'Tilpassede AI-modeller']);
        $this->addFieldValue($i++, 34, 25, 'plan_2_item_2', ['en' => "Integration with Existing Systems", 'da' => 'Integration med eksisterende systemer']);
        $this->addFieldValue($i++, 34, 25, 'plan_2_item_3', ['en' => "Proactive Support", 'da' => 'Proaktiv support']);
        $this->addFieldValue($i++, 34, 25, 'plan_2_item_4', ['en' => "Comprehensive Reports", 'da' => 'Omfattende rapporter']);
        $this->addFieldValue($i++, 34, 25, 'plan_2_item_5', ['en' => "Custom functions", 'da' => 'Tilpassede funktioner']);
        $this->addFieldValue($i++, 34, 25, 'plan_2_link', ['en' => "/contact", 'da' => '/kontakt']);
        $this->addFieldValue($i++, 34, 25, 'plan_2_link_text', ['en' => "Contact Us", 'da' => 'Kontakt Os']);

        $this->addFieldValue($i++, 34, 25, 'plan_3_price', ['en' => "$5000", 'da' => '5000 kr.']);
        $this->addFieldValue($i++, 34, 25, 'plan_3_duration', ['en' => "per project", 'da' => 'pr. projekt']);
        $this->addFieldValue($i++, 34, 25, 'plan_3_title', ['en' => "Enterprise Plan", 'da' => 'Virksomhedsplan']);
        $this->addFieldValue($i++, 34, 25, 'plan_3_description', ['en' => "For large-scale AI solutions", 'da' => 'Til AI-løsninger i stor skala']);
        $this->addFieldValue($i++, 34, 25, 'plan_3_item_1', ['en' => "Enterprise-Grade AI Solutions", 'da' => 'Virksomhedsklasse AI-løsninger']);
        $this->addFieldValue($i++, 34, 25, 'plan_3_item_2', ['en' => "Full System Integration", 'da' => 'Fuld systemintegration']);
        $this->addFieldValue($i++, 34, 25, 'plan_3_item_3', ['en' => "Ongoing Optimization", 'da' => 'Løbende optimering']);
        $this->addFieldValue($i++, 34, 25, 'plan_3_item_4', ['en' => "Dedicated AI Team", 'da' => 'Dedikeret AI-team']);
        $this->addFieldValue($i++, 34, 25, 'plan_3_item_5', ['en' => "Custom functions", 'da' => 'Tilpassede funktioner']);
        $this->addFieldValue($i++, 34, 25, 'plan_3_link', ['en' => "/contact", 'da' => '/kontakt']);
        $this->addFieldValue($i++, 34, 25, 'plan_3_link_text', ['en' => "Contact Us", 'da' => 'Kontakt Os']);

        $this->addFieldValue($i++, 35, 24, 'image', ['en' => '/uploads/nordicstandard/ai-service-image-2.jpeg'], 'file');
        $this->addFieldValue($i++, 35, 24, 'image_alt', ['en' => 'AI Solutions', 'da' => 'AI-løsninger']);
        $this->addFieldValue($i++, 35, 24, 'title', ['en' => 'Why Choose Our AI Services?', 'da' => 'Hvorfor vælge vores AI-tjenester?']);
        $this->addFieldValue($i++, 35, 24, 'content', ['en' => "<p>Our AI solutions are tailored to address your unique business challenges and goals. Whether you need machine learning models, natural language processing, or predictive analytics, we provide cutting-edge tools that transform your operations.</p><p>We combine technical expertise with a deep understanding of your business to deliver AI-driven solutions that enhance productivity, reduce costs, and enable data-driven decision-making. Let us help you unlock the full potential of AI for your organization.</p>", 'da' => "<p>Vores AI-løsninger er skræddersyet til at imødekomme dine unikke forretningsmæssige udfordringer og mål. Uanset om du har brug for machine learning-modeller, naturlig sprogbehandling eller prædiktiv analyse, leverer vi avancerede værktøjer, der transformerer dine processer.</p><p>Vi kombinerer teknisk ekspertise med dyb forståelse af din virksomhed for at levere AI-drevne løsninger, der øger produktiviteten, reducerer omkostninger og muliggør databaseret beslutningstagning. Lad os hjælpe dig med at frigøre det fulde potentiale af AI for din organisation.</p>"], 'textarea_large');

        $this->addFieldValue($i++, 36, 23, 'image', ['en' => '/uploads/nordicstandard/ai-service-image-3.jpeg'], 'file');
        $this->addFieldValue($i++, 36, 23, 'image_alt', ['en' => 'AI Solutions', 'da' => 'AI-løsninger']);
        $this->addFieldValue($i++, 36, 23, 'title', ['en' => 'Leverage the Power of AI', 'da' => 'Udnyt AI\'s kraft']);
        $this->addFieldValue($i++, 36, 23, 'content', ['en' => "<p>We provide AI solutions that are both innovative and practical, ensuring they align with your specific business needs. From automating repetitive tasks to providing advanced insights, we empower your organization to thrive in the digital age.</p><p>Work with our team to build AI solutions that enhance your operations, improve customer experiences, and give you a competitive edge. Let us turn your AI vision into reality.</p>", 'da' => "<p>Vi leverer AI-løsninger, der er både innovative og praktiske, og som sikrer, at de passer til dine specifikke forretningsmæssige behov. Fra automatisering af gentagne opgaver til at give avancerede indsigter bemyndiger vi din organisation til at trives i den digitale tidsalder.</p><p>Samarbejd med vores team om at bygge AI-løsninger, der forbedrer dine processer, øger kundeoplevelsen og giver dig et konkurrencemæssigt forspring. Lad os omsætte din AI-vision til virkelighed.</p>"], 'textarea_large');

        // not translated:
        // $i = 176;
        // $this->addFieldValue($i++, 32, 22, 'subtitle', ['en' => 'EMPOWERING INNOVATION']);
        // $this->addFieldValue($i++, 32, 22, 'title', ['en' => 'Cutting-Edge AI Services']);
        // $this->addFieldValue($i++, 32, 22, 'description', ['en' => 'We harness the power of Artificial Intelligence to help your business innovate, automate, and grow.']);

        // $this->addFieldValue($i++, 33, 26, 'subtitle', ['en' => 'AI MADE ACCESSIBLE']);
        // $this->addFieldValue($i++, 33, 26, 'title', ['en' => 'Transform Your Business with AI']);
        // $this->addFieldValue($i++, 33, 26, 'description', ['en' => 'Our AI services are designed to deliver practical, scalable, and impactful solutions tailored to your business needs.']);
        // $this->addFieldValue($i++, 33, 26, 'image', ['en' => '/uploads/nordicstandard/ai-service-image-1.jpeg'], 'file');
        // $this->addFieldValue($i++, 33, 26, 'image_alt', ['en' => 'AI Services']);
        // $this->addFieldValue($i++, 33, 26, 'content', ['en' => 'From data analysis to automation, we specialize in creating AI solutions that drive efficiency and innovation. Partner with us to implement AI technologies that are custom-built for your business, helping you stay ahead of the competition.', 'textarea_large']);

        // $this->addFieldValue($i++, 34, 25, 'subtitle', ['en' => "Flexible Plans"]);
        // $this->addFieldValue($i++, 34, 25, 'title', ['en' => "Choose the Right AI Service Plan"]);
        // $this->addFieldValue($i++, 34, 25, 'description', ['en' => "We offer flexible and scalable pricing plans that cater to businesses of all sizes."]);
        // $this->addFieldValue($i++, 34, 25, 'plan_1_price', ['en' => "$500"]);
        // $this->addFieldValue($i++, 34, 25, 'plan_1_duration', ['en' => "per project"]);
        // $this->addFieldValue($i++, 34, 25, 'plan_1_title', ['en' => "Starter Plan"]);
        // $this->addFieldValue($i++, 34, 25, 'plan_1_description', ['en' => "Ideal for small-scale AI projects"]);
        // $this->addFieldValue($i++, 34, 25, 'plan_1_item_1', ['en' => "Initial AI Assessment"]);
        // $this->addFieldValue($i++, 34, 25, 'plan_1_item_2', ['en' => "Basic AI Models"]);
        // $this->addFieldValue($i++, 34, 25, 'plan_1_item_3', ['en' => "Data Analysis & Reports"]);
        // $this->addFieldValue($i++, 34, 25, 'plan_1_item_4', ['en' => "Documentation Included"]);
        // $this->addFieldValue($i++, 34, 25, 'plan_1_item_5', ['en' => "Custom functions"]);
        // $this->addFieldValue($i++, 34, 25, 'plan_1_link', ['en' => "/contact"]);
        // $this->addFieldValue($i++, 34, 25, 'plan_1_link_text', ['en' => "Contact Us"]);
        // $this->addFieldValue($i++, 34, 25, 'plan_2_price', ['en' => "$1500"]);
        // $this->addFieldValue($i++, 34, 25, 'plan_2_duration', ['en' => "per project"]);
        // $this->addFieldValue($i++, 34, 25, 'plan_2_title', ['en' => "Advanced Plan"]);
        // $this->addFieldValue($i++, 34, 25, 'plan_2_description', ['en' => "For mid-sized businesses"]);
        // $this->addFieldValue($i++, 34, 25, 'plan_2_item_1', ['en' => "Custom AI Models"]);
        // $this->addFieldValue($i++, 34, 25, 'plan_2_item_2', ['en' => "Integration with Existing Systems"]);
        // $this->addFieldValue($i++, 34, 25, 'plan_2_item_3', ['en' => "Proactive Support"]);
        // $this->addFieldValue($i++, 34, 25, 'plan_2_item_4', ['en' => "Comprehensive Reports"]);
        // $this->addFieldValue($i++, 34, 25, 'plan_2_item_5', ['en' => "Custom functions"]);
        // $this->addFieldValue($i++, 34, 25, 'plan_2_link', ['en' => "/contact"]);
        // $this->addFieldValue($i++, 34, 25, 'plan_2_link_text', ['en' => "Contact Us"]);
        // $this->addFieldValue($i++, 34, 25, 'plan_3_price', ['en' => "$5000"]);
        // $this->addFieldValue($i++, 34, 25, 'plan_3_duration', ['en' => "per project"]);
        // $this->addFieldValue($i++, 34, 25, 'plan_3_title', ['en' => "Enterprise Plan"]);
        // $this->addFieldValue($i++, 34, 25, 'plan_3_description', ['en' => "For large-scale AI solutions"]);
        // $this->addFieldValue($i++, 34, 25, 'plan_3_item_1', ['en' => "Enterprise-Grade AI Solutions"]);
        // $this->addFieldValue($i++, 34, 25, 'plan_3_item_2', ['en' => "Full System Integration"]);
        // $this->addFieldValue($i++, 34, 25, 'plan_3_item_3', ['en' => "Ongoing Optimization"]);
        // $this->addFieldValue($i++, 34, 25, 'plan_3_item_4', ['en' => "Dedicated AI Team"]);
        // $this->addFieldValue($i++, 34, 25, 'plan_3_item_5', ['en' => "Custom functions"]);
        // $this->addFieldValue($i++, 34, 25, 'plan_3_link', ['en' => "/contact"]);
        // $this->addFieldValue($i++, 34, 25, 'plan_3_link_text', ['en' => "Contact Us"]);

        // $this->addFieldValue($i++, 35, 24, 'image', ['en' => '/uploads/nordicstandard/ai-service-image-2.jpeg'], 'file');
        // $this->addFieldValue($i++, 35, 24, 'image_alt', ['en' => 'AI Solutions']);
        // $this->addFieldValue($i++, 35, 24, 'title', ['en' => 'Why Choose Our AI Services?']);
        // $this->addFieldValue($i++, 35, 24, 'content', ['en' => "<p>Our AI solutions are tailored to address your unique business challenges and goals. Whether you need machine learning models, natural language processing, or predictive analytics, we provide cutting-edge tools that transform your operations.</p><p>We combine technical expertise with a deep understanding of your business to deliver AI-driven solutions that enhance productivity, reduce costs, and enable data-driven decision-making. Let us help you unlock the full potential of AI for your organization.</p>"], 'textarea_large');

        // $this->addFieldValue($i++, 36, 23, 'image', ['en' => '/uploads/nordicstandard/ai-service-image-3.jpeg'], 'file');
        // $this->addFieldValue($i++, 36, 23, 'image_alt', ['en' => 'AI Solutions']);
        // $this->addFieldValue($i++, 36, 23, 'title', ['en' => 'Leverage the Power of AI']);
        // $this->addFieldValue($i++, 36, 23, 'content', ['en' => "<p>We provide AI solutions that are both innovative and practical, ensuring they align with your specific business needs. From automating repetitive tasks to providing advanced insights, we empower your organization to thrive in the digital age.</p><p>Work with our team to build AI solutions that enhance your operations, improve customer experiences, and give you a competitive edge. Let us turn your AI vision into reality.</p>"], 'textarea_large');
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

    private function addFieldValue($fieldId, $page_widget_id, $widget_id, $key, $values = [], $type = 'input')
    {
        $fieldValue = new FieldValue;
        $fieldValue->page_widget_id = $page_widget_id;
        $fieldValue->field_id = $fieldId; //header
        $fieldValue->setTranslations('value', $values);
        $fieldValue->save();
    }
}
