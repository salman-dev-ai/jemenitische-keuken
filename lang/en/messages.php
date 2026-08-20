<?php

return [
    // ===== Navigation =====
    'nav' => [
        'home' => 'Home',
        'about' => 'Our Story & Heritage',
        'menu' => 'Food Menu',
        'gallery' => 'Gallery',
        'reservation' => 'Book a Table',
        'contact' => 'Contact Us',
        'bookTableBtn' => 'Book Your Table Now',
    ],

    // ===== Brand =====
    'brand' => [
        'name' => 'Yemeni Kitchen',
        'subName' => 'JEMENITISCHE KEUKEN',
        'slogan' => 'The Origin of Mandi | أصل المندي',
        'description' => 'We take you on an authentic culinary journey...',
    ],

    // ===== Home Page =====
    'home' => [
        'greetingBadge' => 'Welcome to the House of Yemeni Generosity and Authenticity',
        'heroTitle' => 'The Royal Taste of Yemeni Mandi',
        'heroSubtitle' => 'Slow-cooked over Samr wood fire and prepared with secret Yemeni spices...',
        'ctaReserve' => 'Book Your Table',
        'ctaMenu' => 'Explore Our Royal Menu',
        'hospitalityNote' => 'Complimentary hospitality service and Adeni tea for all guests',

        'stats' => [
            'years' => [
                'value' => '+25',
                'label' => 'Years of Mandi Experience',
            ],

            'halal' => [
                'value' => '100%',
                'label' => 'Fresh Local Halal Meat Every Day',
            ],

            'spices' => [
                'value' => '+18',
                'label' => 'Rare and Authentic Yemeni Spices',
            ],

            'rating' => [
                'value' => '4.9★',
                'label' => 'Guest Satisfaction Rating',
            ],
        ],

        'pillars' => [
            'badge' => 'The Pillars of Yemeni Authenticity in Our Restaurant',
            'title' => 'The Secret Behind an Unforgettable Yemeni Taste',
            'subtitle' => 'We preserve every detail of traditional cooking without compromising on quality or authentic flavor',

            'items' => [
                'wood' => [
                    'icon' => '🪵',
                    'title' => 'Traditional Wood-Fired Cooking',
                    'desc' => 'Slow cooking of meat and rice over authentic Samr wood fire...',
                ],

                'stone' => [
                    'icon' => '🍲',
                    'title' => 'Traditional Sanaa Clay Pots',
                    'desc' => 'Handcrafted traditional cooking vessels made from volcanic stone...',
                ],

                'flint' => [
                    'icon' => '🔥',
                    'title' => 'Flint-Stone Muthbi',
                    'desc' => 'Chicken and meat grilled over authentic flint stones...',
                ],

                'honey' => [
                    'icon' => '🍯',
                    'title' => 'Yemeni Coffee & Doani Honey',
                    'desc' => 'We source pure Doani honey and authentic Yemeni coffee...',
                ],
            ],
        ],

        'majlis' => [
            'badge' => 'Hospitality & Family Privacy',
            'title' => 'An Atmosphere Blending the Yemeni Majlis with European Elegance',
            'desc' => 'Choose the seating experience that suits you best...',

            'features' => [
                'Private traditional floor-seating Majlis areas for families with complete privacy.',
                'Spacious royal tables prepared for special occasions and elegant gatherings.',
                'Complimentary Yemeni frankincense and oud fragrance after every meal.',
            ],

            'cta' => 'Book Your Preferred Seating in Advance',

            'quote' => 'In Yemen, hospitality is not merely about food; it is a tradition of generosity and love passed down from one generation to the next.',
        ],
    ],

    // ===== Menu =====
    'menu' => [
        'badge' => 'Royal Dishes',
        'title' => 'Traditional Yemeni Cuisine Categories',
        'subtitle' => 'A rich menu carefully categorized according to the highest standards of quality and authentic Yemeni flavor',
        'dishes' => 'Dishes',
        'all' => 'All Dishes',
        'selected' => 'Selected',
        'browse' => 'Explore Dishes',
        'orderNow' => 'Book to Taste It',
        'empty' => 'There are currently no available dishes in this category.',
        'chefSpecial' => "Chef's Royal Special",
    ],

    // ===== Reservation =====
    'reservation' => [
        'badge' => 'Instant Online Reservation',
        'title' => 'Book Your Table',
        'subtitle' => 'Choose the number of guests, preferred time, and seating location, and we will prepare the perfect table for you.',
        'tablesAvailable' => 'Table available at this time',
        'success' => 'Your reservation request has been successfully submitted! We will confirm it shortly.',
        'welcome' => 'Welcome, :name. Your reservation has been successfully confirmed.',
        'referenceCode' => 'Reservation Reference Code',
        'date' => 'Date',
        'time' => 'Time',
        'guests' => 'Number of Guests',
        'guestsCount' => ':count guests',
        'tableNumber' => 'Table Number',
        'table' => 'Table',
        'errorTitle' => 'Please Check Your Information',

        'step1' => 'Guest & Time Details',
        'partySize' => 'Number of Guests',
        'guestsLabel' => 'Guests',
        'reservationDate' => 'Reservation Date',
        'today' => 'Today',
        'tomorrow' => 'Tomorrow',
        'preferredTime' => 'Preferred Arrival Time',
        'sessionDuration' => 'Available seating duration: 90 minutes',

        'step2' => 'Choose Your Table & Seating Location',
        'capacity' => 'Capacity: :count guests',
        'available' => 'Available',
        'selected' => 'Selected ✓',
        'mainHall' => 'Main Hall',
        'instantConfirm' => 'Instant Confirmation',

        'noTables' => 'There are no available tables for :count guests at this time. Please try another time or date.',

        'step3' => 'Contact Details & Special Requests',
        'fullName' => 'Full Name',
        'fullNamePlaceholder' => 'Enter your name',
        'phone' => 'Phone Number for Confirmation',
        'specialRequests' => 'Special Notes or Requests (Optional)',
        'specialRequestsPlaceholder' => 'Do you have a special occasion, need children’s chairs, or require any specific arrangements?',
        'submit' => 'Submit & Confirm Reservation',
        'processing' => 'Processing your reservation and checking availability...',
    ],

    // ===== Gallery =====
    'gallery' => [
        'badge' => 'Traditional Heritage Gallery',
        'title' => 'Authentic Moments Filled with the Aroma of Wood Fire and Spices',
        'subtitle' => 'Discover our hospitality, traditional Mandi and Muthbi cooking, and authentic Yemeni seating experiences',
    ],

    // ===== Footer =====
    'footer' => [
        'quickLinks' => 'Quick Links',
        'rights' => 'All rights reserved to Yemeni Kitchen - Jemenitische Keuken',
    ],

    // ===== Error Messages =====
    'errors' => [
        'table_capacity' => 'Table number (:table) cannot accommodate :count guests. Its maximum capacity is :capacity.',
        'table_inactive' => 'The selected table is currently unavailable for reservations.',
        'time_conflict' => 'The table is already reserved at this time or within a two-hour window. Please choose another time or a different table.',
    ],

  // ===== Contact Messages =====
    'contact' => [

        'badge' => 'Get in touch with us – our hospitality awaits you',

        'title' => 'We are delighted to welcome you and answer your questions',

        'form' => [

            'title' => 'Send Us a Direct Message',

            'success' => 'Your message has been sent successfully. We will get back to you as soon as possible.',

            'name' => 'Full Name *',

            'email' => 'Email Address *',

            'phone' => 'Phone Number / WhatsApp',

            'subject' => 'Inquiry Type',

            'message' => 'Your Message or Special Request *',

            'submit' => 'Send Message Now →',

            'subjects' => [

                'inquiry' => 'General inquiry about the restaurant and menu',

                'event' => 'Private event or large family Majlis reservation',

                'catering' => 'Catering and external banquet services',

            ],
        ],

        'details' => [

            'address' => 'Address & Location',

            'addressValue' => 'Damrak 45, Amsterdam, Netherlands',

            'hours' => 'Opening Hours',

            'hoursValue' => 'Daily from 12:00 to 23:30',

        ],
    ],

];
