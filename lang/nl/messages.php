<?php

declare(strict_types=1);

/**
 * ═══════════════════════════════════════════════════════════════════════════
 * 📄 المسار: lang/nl/messages.php
 * ═══════════════════════════════════════════════════════════════════════════
 *
 * 🎯 الغرض:
 *    Nederlandse vertalingen — spiegelt structuur van ar/en versies.
 *
 * 🧩 يعتمد على:
 *    - Laravel 12.x Translator
 *    - Mirror van ar/messages.php (geen ontbrekende keys)
 *
 * ⚠️ تحذيرات مهمة:
 *    - Houd keys synchroon met ar/en versies.
 *
 * 🕒 آخر تحديث: 2026-09-24
 * ═══════════════════════════════════════════════════════════════════════════
 */

return [
    // ═══════════════════════════════════════════
    // 🧭 Navigatie
    // ═══════════════════════════════════════════
    'nav' => [
        'home' => 'Home',
        'about' => 'Ons verhaal & erfgoed',
        'menu' => 'Menukaart',
        'gallery' => 'Galerij',
        'reservation' => 'Tafel reserveren',
        'contact' => 'Neem contact met ons op',
        'bookTableBtn' => 'Reserveer nu uw tafel',                // روابط القائمة الجديدة (تسميات ثابتة)

        'offers' => 'Aanbiedingen',
        'reserve' => 'Reserveren',
        'order' => 'Bestellen',
        'contactLink' => 'Contact',

        'primary' => 'Primaire navigatie',
        'secondary' => 'Secundaire navigatie',
        'mobile' => 'Mobiel menu',
        'toggleMenu' => 'Menu openen of sluiten',
        'galleryHeritage' => 'Galerij & Erfgoed',
        'restaurantInfo' => 'Restaurantinformatie',

        // Serviceknoppen in de header
        'whatsapp' => 'WhatsApp',
        'delivery' => 'Bezorgen',
    ],

    'cookies' => [
        'aria' => 'Melding over cookies',
        'title' => 'Cookies',
        'message' => 'We gebruiken alleen functionele cookies die noodzakelijk zijn voor de werking van de website, zoals het onthouden van je winkelmandje en je gegevens voor je volgende bestelling. We gebruiken geen analytische cookies of trackingcookies.',
        'accept' => 'Akkoord',
        'necessary' => 'Alleen noodzakelijke cookies',
    ],

    // ═══════════════════════════════════════════
    // 🏷️ Merk
    // ═══════════════════════════════════════════
    'brand' => [
        'name' => 'Jemenitische Keuken',
        'subName' => 'JEMENITISCHE KEUKEN',
        'slogan' => 'De oorsprong van Mandi',
        'description' => 'Wij nemen u mee op een authentieke culinaire reis...',
    ],

    // ═══════════════════════════════════════════
    // 🏠 Homepage
    // ═══════════════════════════════════════════
    'home' => [
        'greetingBadge' => 'Welkom in het huis van Jemenitische gastvrijheid en authenticiteit',
        'heroTitle' => 'De koninklijke smaak van Jemenitische Mandi',
        'heroSubtitle' => 'Langzaam bereid boven een vuur van Samr-hout en op smaak gebracht met geheime Jemenitische kruiden...',
        'ctaReserve' => 'Reserveer uw tafel',
        'ctaMenu' => 'Bekijk onze koninklijke menukaart',
        'hospitalityNote' => 'Gratis gastvrijheid en Adeni-thee voor al onze gasten',

        'stats' => [
            'years' => ['value' => '+25',  'label' => 'Jaar ervaring met Mandi'],
            'halal' => ['value' => '100%', 'label' => 'Dagelijks vers lokaal halal vlees'],
            'spices' => ['value' => '+18',  'label' => 'Zeldzame en authentieke Jemenitische kruiden'],
            'rating' => ['value' => '4.9★', 'label' => 'Tevredenheidsscore van onze gasten'],
        ],

        'majlis' => [
            'badge' => 'Gastvrijheid & privacy voor gezinnen',
            'title' => 'Een sfeer waarin de Jemenitische Majlis samenkomt met Europese elegantie',
            'desc' => 'Kies de zitplaats die het beste bij u past...',
            'features' => [
                'Traditionele privé-Majlis-zitplaatsen voor gezinnen met volledige privacy.',
                'Ruime koninklijke tafels, perfect voor speciale gelegenheden en stijlvolle bijeenkomsten.',
                'Gratis Jemenitische wierook en oudh-geur na iedere maaltijd.',
            ],
            'cta' => 'Reserveer vooraf uw favoriete zitplaats',
            'quote' => 'In Jemen draait gastvrijheid niet alleen om eten; het is een traditie van vrijgevigheid en liefde die van generatie op generatie wordt doorgegeven.',
        ],
    ],

    // ═══════════════════════════════════════════
    // 🍽️ Menukaart
    // ═══════════════════════════════════════════
    'menu' => [
        'badge' => 'Koninklijke gerechten',
        'title' => 'Traditionele Jemenitische gerechten',
        'subtitle' => 'Een uitgebreide menukaart, zorgvuldig samengesteld volgens de hoogste normen voor kwaliteit en authentieke Jemenitische smaak',
        'dishes' => 'Gerechten',
        'all' => 'Alle gerechten',
        'selected' => 'Geselecteerd',
        'browse' => 'Gerechten bekijken',
        'orderNow' => 'Reserveer om dit te proeven',
        'empty' => 'Er zijn momenteel geen beschikbare gerechten in deze categorie.',
        'chefSpecial' => 'Koninklijke specialiteit van de chef',
        'addToCart' => 'In winkelmandje',
        'cartReady' => 'Uw koninklijke winkelwagen is klaar',
        'total' => 'Totaal',
        'checkout' => 'Afrekenen & Nu boeken',
        'cart' => 'Uw winkelwagen & Reservering',
        'items' => 'geselecteerde items',
        'emptyText' => 'U heeft nog geen gerechten aan uw feestmaal toegevoegd.',
        'browseMenu' => 'Menu nu bekijken',
        'selectedDishes' => 'Geselecteerde gerechten voor het feestmaal',
        'featured' => 'Aanbevolen',
        'spicy' => 'Pittig',
        'removeFromCart' => 'Gerecht uit winkelwagen verwijderen',
        'popular_title' => 'Populaire gerechten',   // nl — كما طلبت

        'free_drink_badge' => 'Inclusief gratis drankje',
        'free_drink_hint' => 'Kies je gratis drankje in de winkelwagen.',
        'free_drink_none' => 'Momenteel geen gratis drankje beschikbaar.',
        'free_drink_added_hint' => 'Dit gerecht is inclusief een gratis drankje — kies het in de winkelwagen.',

    ],

    // ═══════════════════════════════════════════
    // 📅 Reservering
    // ═══════════════════════════════════════════
    'reservation' => [
        'badge' => 'Direct online reserveren',
        'title' => 'Reserveer uw tafel',
        'subtitle' => 'Kies het aantal gasten, uw gewenste tijd en de gewenste zitplaats. Wij zorgen vervolgens voor de perfecte tafel.',
        'tablesAvailable' => 'Tafel beschikbaar op dit tijdstip',
        'welcome' => 'Welkom, :name. Uw reservering is succesvol bevestigd.',
        'referenceCode' => 'Reserveringscode',
        'date' => 'Datum',
        'time' => 'Tijd',
        'guests' => 'Aantal gasten',
        'guestsCount' => ':count gasten',
        'tableNumber' => 'Tafelnummer',
        'table' => 'Tafel',
        'errorTitle' => 'Controleer uw gegevens',
        'step1' => 'Gegevens over gasten & tijd',
        'partySize' => 'Aantal gasten',
        'guestsLabel' => 'Gasten',
        'reservationDate' => 'Reserveringsdatum',
        'today' => 'Vandaag',
        'tomorrow' => 'Morgen',
        'preferredTime' => 'Gewenste aankomsttijd',
        'email' => 'E-mailadres',
        'sessionDuration' => 'Beschikbare zitduur: 90 minuten',
        'step2' => 'Kies uw tafel & zitplaats',
        'capacity' => 'Capaciteit: :count gasten',
        'available' => 'Beschikbaar',
        'selected' => 'Geselecteerd ✓',
        'mainHall' => 'Hoofdzaal',
        'instantConfirm' => 'Directe bevestiging',
        'noTables' => 'Er zijn op dit tijdstip geen beschikbare tafels voor :count gasten. Probeer een ander tijdstip of een andere datum.',
        'step3' => 'Contactgegevens & speciale verzoeken',
        'fullName' => 'Volledige naam',
        'fullNamePlaceholder' => 'Vul uw naam in',
        'phone' => 'Telefoonnummer voor bevestiging',
        'specialRequests' => 'Speciale opmerkingen of verzoeken (optioneel)',
        'specialRequestsPlaceholder' => 'Heeft u een speciale gelegenheid, kinderstoelen of bepaalde wensen?',
        'submit' => 'Verstuur & bevestig reservering',
        'processing' => 'Uw reservering wordt verwerkt en de beschikbaarheid wordt gecontroleerd...',
        'contactDetails' => 'Reservering & Contactgegevens',

        // Type Service
        'serviceType' => 'Type Service',
        'dineIn' => 'Dine In 🍽️',
        'takeaway' => 'Afhalen 🥡',
        'delivery' => 'Bezorging aan huis 🚚',

        'orderTotal' => 'Totaal orderbedrag:',
        'confirmReservation' => 'Bevestig reservering & Ontvang WhatsApp-melding',
        'processingReservation' => 'Reservering registreren en WhatsApp-gegevens verzenden...',
        'smsNotice' => 'Een kopie van uw bestelling wordt voor bevestiging naar uw WhatsApp verzonden',
        'requestsPlaceholderMenu' => 'Voorbeeld: Bereid alsjeblieft Adeni-thee, of er is een notenallergie...',

        // Algemene berichten
        'success' => 'Uw reserveringsaanvraag is succesvol verzonden! Wij bevestigen deze zo snel mogelijk.',
        'generic_error' => 'Er is een onverwachte fout opgetreden. Probeer het later opnieuw.',
        'rate_limit_exceeded' => 'Te veel pogingen. Probeer het over een ogenblik opnieuw.',
    ],

    // ═══════════════════════════════════════════
    // 🛒 Bestelling
    // ═══════════════════════════════════════════
    'order' => [
        // Algemeen
        'generic_error' => 'We konden uw bestelling niet voltooien. Probeer het opnieuw.',
        'rate_limit_exceeded' => 'Te veel bestelpogingen. Probeer het over %d seconden opnieuw.',

        // Bezorgvelden (verplicht bij DELIVERY)
        'address_required' => 'Bezorgadres is verplicht.',
        'city_required' => 'Stad is verplicht.',
        'postal_required' => 'Postcode is verplicht.',

        // Telefoonformaat
        'phone_format' => 'Ongeldig telefoonnummer (Arabische en Engelse cijfers zijn toegestaan).',

        // Fouttitels & validatie
        'error_title' => 'Bestelling kon niet worden voltooid',
        'validation_error' => 'Controleer de velden',
        'validation_summary' => ':count fout(en) verhinderen afrekenen — controleer de gemarkeerde velden.',
    ],

    // ═══════════════════════════════════════════
    //   Onthoud mij + Adresvelden
    // ═══════════════════════════════════════════
    'remember' => [
        'welcome_back' => 'Welkom terug! 👋',
        'filled_automatically' => 'Uw gegevens zijn automatisch ingevuld.',
        'not_you' => 'Niet jou?',
        'checkbox' => 'Onthoud mij de volgende keer  ',
        'confirm_forget' => 'Weet je het zeker? Je opgeslagen gegevens worden gewist.',
        'confirm_title' => 'Gegevens wissen bevestigen',
        'confirm_yes' => 'Ja, wis mijn gegevens',

        'forgotten_title' => 'Je gegevens zijn gewist',
        'forgotten_message' => 'Je kunt opnieuw beginnen.',

        'delivery_details' => 'Bezorgadres Gegevens  ',
        'address' => 'Volledig Adres',
        'address_placeholder' => 'bijv. Kerkstraat 123, II',
        'city' => 'Stad',
        'city_placeholder' => 'Amsterdam',
        'postal_code' => 'Postcode',
        'postal_placeholder' => '1012 NK',
    ],

    // ═══════════════════════════════════════════
    // ✅ Algemene bevestigingsdialoog (vervanger van wire:confirm)
    // ═══════════════════════════════════════════
    'confirm' => [
        'yes' => 'Bevestigen',
        'cancel' => 'Annuleren',
    ],

    // ═══════════════════════════════════════════
    // 🔔 Meldingen (Toast) — enkele bron
    // ═══════════════════════════════════════════
    'notifications' => [
        'reservation_title' => '🎉 Reservering bevestigd!',
        'reservation_message' => 'Uw reservering is ontvangen.',
        'order_title' => '👑 Bestelling ontvangen!',
        'order_message' => 'Ons team neemt binnenkort contact met u op.',
        'preorder_title' => '🛒 Voorbestelling toegevoegd!',
        'preorder_message' => 'We bereiden uw gerechten voor uw aankomst.',
    ],

    // ═══════════════════════════════════════════
    // 📲 Bevestigingsbericht reservering (WhatsApp)
    // ═══════════════════════════════════════════
    'reservation_confirmation' => [
        'toast_title' => '✅ Reservering bevestigd',
        'toast_body' => 'Stuur de bevestiging naar :name via WhatsApp.',
        'send_action' => 'Bevestiging via WhatsApp versturen',
        'whatsapp_title' => '*Bevestiging van uw reservering bij :restaurant*',
        'whatsapp_greeting' => 'Hallo :name,',
        'whatsapp_intro' => 'Wij bevestigen graag uw reservering. Dit zijn de gegevens:',
        'whatsapp_reference' => 'Referentie: :code',
        'whatsapp_datetime' => 'Datum en tijd: :date om :time',
        'whatsapp_guests' => 'Aantal personen: :count',
        'whatsapp_requests' => 'Speciale wensen: :requests',
        'whatsapp_footer' => 'We kijken ernaar uit u te verwelkomen. Antwoord op dit bericht voor wijzigingen.',
    ],

    // ═══════════════════════════════════════════
    // 📲 Statusupdate van bestelling naar klant (WhatsApp)
    // ═══════════════════════════════════════════
    'order_status' => [
        'toast_title' => '📦 Bestelstatus bijgewerkt',
        'toast_body' => 'Stuur de update van bestelling :number naar de klant via WhatsApp.',
        'send_action' => 'Bestelupdate via WhatsApp versturen',
        'whatsapp_title' => '*Update van uw bestelling bij :restaurant*',
        'whatsapp_greeting' => 'Hallo :name,',
        'whatsapp_intro_completed' => 'Uw bestelling is succesvol afgerond. Bedankt voor uw bezoek!',
        'whatsapp_intro_cancelled' => 'Helaas moeten wij u melden dat uw bestelling is geannuleerd. Antwoord op dit bericht voor vragen.',
        'whatsapp_order_number' => 'Bestelnummer: :number',
        'whatsapp_status' => 'Status: :status',
        'whatsapp_items_header' => 'Artikelen:',
        'whatsapp_total' => 'Totaal: :total',
        'whatsapp_footer' => 'Wij verwelkomen u graag opnieuw.',
    ],

    // ═══════════════════════════════════════════
    // 🖼️ Galerij
    // ═══════════════════════════════════════════
    'gallery' => [
        'badge' => 'Traditionele erfgoedgalerij',
        'title' => 'Authentieke momenten, gevuld met de geur van houtvuur en kruiden',
        'subtitle' => 'Ontdek onze gastvrijheid, traditionele Mandi- en Muthbi-bereiding en authentieke Jemenitische zitplaatsen',
        'categories' => 'Galerijcategorieën',
        'loading' => 'Afbeeldingen laden...',
        'empty' => 'Nog geen afbeeldingen in deze categorie.',
        'galleryHeritage' => 'Galerij & Erfgoed',
    ],

    // ═══════════════════════════════════════════
    // 👣 Footer
    // ═══════════════════════════════════════════
    'footer' => [
        'quickLinks' => 'Snelle links',
        'rights' => 'Alle rechten voorbehouden aan Jemenitische Keuken',
        'contact' => 'Neem contact met ons op',
        'welcome' => 'Wij heten u dagelijks welkom',
        'hours' => 'Openingstijden',
        'hoursUnavailable' => 'Neem contact met ons op voor de openingstijden.',
        'reserveNow' => 'Reserveer nu uw tafel',
        'whatsapp' => 'Contact via WhatsApp',
        'viewMap' => 'Bekijk locatie op de kaart',
    ],

    // ═══════════════════════════════════════════
    // ⭐ Recensies
    // ═══════════════════════════════════════════
    'reviews' => [
        'badge' => 'Gastenbeoordelingen',
        'title' => 'Wat liefhebbers van Jemenitische smaak in Europa zeggen',
        'stats' => 'Uitstekend beoordeeld met 4.9 uit 5 op basis van 1.450+ geverifieerde beoordelingen',
        'verified' => 'Geverifieerde Gast',
        'favorite' => 'Favoriete gerecht',
    ],

    // ═══════════════════════════════════════════
    // ⚠️ Foutmeldingen (Services/Exceptions)
    // ═══════════════════════════════════════════
    'errors' => [
        // ReservationService
        'missing_customer_data' => 'Voer naam en telefoonnummer in.',
        'missing_required_fields' => 'Verplichte velden ontbreken (datum en tijd).',
        'invalid_party_size' => 'Ongeldig aantal personen (1-20).',
        'invalid_status' => 'Ongeldige reserveringsstatus.',
        'invalid_date_format' => 'Ongeldig datumformaat.',
        'invalid_time_format' => 'Ongeldig tijdformaat.',
        'past_date' => 'Kan niet boeken in het verleden.',
        'time_conflict' => 'Sorry, dit tijdstip is volgeboekt. Kies een ander tijdstip.',

        // OrderService
        'empty_cart' => 'Kan geen bestelling aanmaken zonder gerechten.',
        'menu_item_unavailable' => 'Het gevraagde gerecht (#:id) is niet beschikbaar.',
        'invalid_quantity' => 'Ongeldige hoeveelheid voor ":name" (waarde: :qty).',
        'invalid_menu_item_id' => 'Ongeldig menu-item ID.',
        'invalid_reservation' => 'De gekoppelde reservering bestaat niet.',
        'invalid_order_status' => 'Ongeldige bestelstatus.',
        'invalid_order_type' => 'Ongeldig besteltype.',

        'free_drink_not_eligible' => 'Het  gerecht ":name" is niet inclusief een gratis drankje.',
        'invalid_free_drink' => 'Het gekozen drankje is niet toegestaan voor dit gerecht.',
        'free_drink_quantity_exceeded' => 'Je hebt het toegestane aantal gratis drankjes voor ":name" overschreden (max: :max).',
    ],

    // ═══════════════════════════════════════════
    // 📞 Contactsectie
    // ═══════════════════════════════════════════
    'contact' => [
        'badge' => 'Neem contact met ons op',
        'title' => 'Wij ontvangen u graag en beantwoorden uw vragen',
        'subtitle' => 'Ons team staat klaar om al uw vragen te beantwoorden en heet u van harte welkom in ons restaurant in Amsterdam.',

        // Kaart- en contactkaarten
        'directions' => 'Routebeschrijving',
        'call_us' => 'Bel ons',
        'phone_label' => 'Telefoonnummer',
        'phone_hint' => 'Bereikbaar tijdens openingstijden',
        'write_us' => 'Mail ons',
        'email_label' => 'E-mailadres',
        'email_hint' => 'Wij reageren zo snel mogelijk',

        'form' => [
            'title' => 'Stuur ons een bericht',
            'subtitle' => 'Uw mening is belangrijk voor ons. Schrijf ons en wij reageren zo snel mogelijk.',
            'success' => 'Uw bericht is succesvol verzonden. Wij nemen zo snel mogelijk contact met u op.',

            // Let op: het sterretje (*) wordt in de template toegevoegd
            'name' => 'Volledige naam',
            'email' => 'E-mailadres',
            'phone' => 'Telefoon- / WhatsApp-nummer',
            'whatsapp' => 'Telefoon- / WhatsApp-nummer',
            'subject' => 'Soort vraag',
            'message' => 'Uw bericht of speciale verzoek',

            'name_placeholder' => 'Vul uw volledige naam in',
            'email_placeholder' => 'Vul uw e-mailadres in',
            'whatsapp_placeholder' => 'Vul uw telefoon- of WhatsApp-nummer in',
            'message_placeholder' => 'Schrijf hier uw bericht...',

            'submit' => 'Bericht nu verzenden →',
            'submit_whatsapp' => 'Bericht verzenden',
            'whatsapp_unavailable' => 'De WhatsApp-service is momenteel niet beschikbaar',
            'whatsapp_note' => 'WhatsApp wordt op uw apparaat geopend — druk in de app op Verzenden om het bericht te versturen.',

            'subjects' => [
                'inquiry' => 'Algemene vraag over het restaurant en het menu',
                'event' => 'Reservering voor een privé-evenement of grote familiebijeenkomst',
                'catering' => 'Cateringdiensten en externe feestmaaltijden',
            ],
        ],

        'details' => [
            'address' => 'Adres en locatie',
            'addressValue' => 'Damrak 45, Amsterdam, Nederland',
            'hours' => 'Openingstijden',
            'hoursValue' => 'Dagelijks van 12:00 tot 23:30',
        ],
    ],
    // ═══════════════════════════════════════════
    // 🔢 Enums
    // ═══════════════════════════════════════════
    'enums' => [
        'order_status' => [
            'pending' => 'In behandeling',
            'processing' => 'Wordt bereid',
            'completed' => 'Voltooid',
            'cancelled' => 'Geannuleerd',
        ],
        'order_type' => [
            'pickup' => 'Afhalen',
            'dine_in' => 'Ter plaatse eten',
            'preorder' => 'Voorbestelling bij reservering',
        ],
        'reservation_status' => [
            'pending' => 'In afwachting van bevestiging',
            'confirmed' => 'Bevestigd',
            'seated' => 'Gasten aanwezig',
            'cancelled' => 'Geannuleerd',
            'no_show' => 'Niet komen opdagen',
        ],
    ],

    // ═══════════════════════════════════════════
    // ♿ Toegankelijkheid & UI
    // ═══════════════════════════════════════════
    'accessibility' => [
        'loading' => 'Laden...',
        'scrollToTop' => 'Terug naar boven',
    ],

    'discount_type' => [
        'percentage' => 'Percentage',
        'fixed' => 'Vast bedrag',
    ],
    'media_type' => [
        'image' => 'Afbeelding',
        'video' => 'Video',
    ],
];
