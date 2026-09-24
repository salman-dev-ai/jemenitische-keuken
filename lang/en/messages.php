<?php

declare(strict_types=1);

/**
 * ═══════════════════════════════════════════════════════════════════════════
 * 📄 المسار: lang/en/messages.php
 * ═══════════════════════════════════════════════════════════════════════════
 *
 * 🎯 الغرض:
 *    English translations file — mirrors the structure of lang/ar/messages.php.
 *
 * 🧩 يعتمد على:
 *    - Laravel 12.x Translator
 *    - Mirrors ar/messages.php key structure (no missing keys)
 *
 * ⚠️ تحذيرات مهمة:
 *    - Keep keys in sync with ar/nl versions to avoid missing translations.
 *
 * 🕒 آخر تحديث: 2026-09-24
 * ═══════════════════════════════════════════════════════════════════════════
 */

return [
    // ═══════════════════════════════════════════
    // 🧭 Navigation
    // ═══════════════════════════════════════════
    'nav' => [
        'home'         => 'Home',
        'about'        => 'Our Story & Heritage',
        'menu'         => 'Food Menu',
        'gallery'      => 'Gallery',
        'reservation'  => 'Book a Table',
        'contact'      => 'Contact Us',
        'bookTableBtn' => 'Book Your Table Now',

        'primary'      => 'Primary navigation',
        'secondary'    => 'Secondary navigation',
        'mobile'       => 'Mobile menu',
        'toggleMenu'   => 'Open or close menu',
    ],

    // ═══════════════════════════════════════════
    // 🏷️ Brand
    // ═══════════════════════════════════════════
    'brand' => [
        'name'        => 'Yemeni Kitchen',
        'subName'     => 'JEMENITISCHE KEUKEN',
        'slogan'      => 'The Origin of Mandi | أصل المندي',
        'description' => 'We take you on an authentic culinary journey...',
    ],

    // ═══════════════════════════════════════════
    // 🏠 Home Page
    // ═══════════════════════════════════════════
    'home' => [
        'greetingBadge'   => 'Welcome to the House of Yemeni Generosity and Authenticity',
        'heroTitle'       => 'The Royal Taste of Yemeni Mandi',
        'heroSubtitle'    => 'Slow-cooked over Samr wood fire and prepared with secret Yemeni spices...',
        'ctaReserve'      => 'Book Your Table',
        'ctaMenu'         => 'Explore Our Royal Menu',
        'hospitalityNote' => 'Complimentary hospitality service and Adeni tea for all guests',

        'stats' => [
            'years'  => ['value' => '+25',  'label' => 'Years of Mandi Experience'],
            'halal'  => ['value' => '100%', 'label' => 'Fresh Local Halal Meat Every Day'],
            'spices' => ['value' => '+18',  'label' => 'Rare and Authentic Yemeni Spices'],
            'rating' => ['value' => '4.9★', 'label' => 'Guest Satisfaction Rating'],
        ],

        'majlis' => [
            'badge' => 'Hospitality & Family Privacy',
            'title' => 'An Atmosphere Blending the Yemeni Majlis with European Elegance',
            'desc'  => 'Choose the seating experience that suits you best...',
            'features' => [
                'Private traditional floor-seating Majlis areas for families with complete privacy.',
                'Spacious royal tables prepared for special occasions and elegant gatherings.',
                'Complimentary Yemeni frankincense and oud fragrance after every meal.',
            ],
            'cta'   => 'Book Your Preferred Seating in Advance',
            'quote' => 'In Yemen, hospitality is not merely about food; it is a tradition of generosity and love passed down from one generation to the next.',
        ],
    ],

    // ═══════════════════════════════════════════
    // 🍽️ Menu
    // ═══════════════════════════════════════════
    'menu' => [
        'badge'          => 'Royal Dishes',
        'title'          => 'Traditional Yemeni Cuisine Categories',
        'subtitle'       => 'A rich menu carefully categorized according to the highest standards of quality and authentic Yemeni flavor',
        'dishes'         => 'Dishes',
        'all'            => 'All Dishes',
        'selected'       => 'Selected',
        'browse'         => 'Explore Dishes',
        'orderNow'       => 'Book to Taste It',
        'empty'          => 'There are currently no available dishes in this category.',
        'chefSpecial'    => "Chef's Royal Special",
        'addToCart'      => 'Add to Cart',
        'cartReady'      => 'Your royal cart is ready',
        'total'          => 'Total',
        'checkout'       => 'Checkout & Book Now',
        'cart'           => 'Your Cart & Reservation',
        'items'          => 'selected items',
        'emptyText'      => "You haven't added any dishes to your feast yet.",
        'browseMenu'     => 'Browse Menu Now',
        'selectedDishes' => 'Selected Dishes for the Feast',
        'featured'       => 'Featured',
        'spicy'          => 'Spicy',
        'removeFromCart' => 'Remove dish from cart',
    ],

    // ═══════════════════════════════════════════
    // 📅 Reservation
    // ═══════════════════════════════════════════
    'reservation' => [
        'badge'                       => 'Instant Online Reservation',
        'title'                       => 'Book Your Table',
        'subtitle'                    => 'Choose the number of guests, preferred time, and seating location, and we will prepare the perfect table for you.',
        'tablesAvailable'             => 'Table available at this time',
        'welcome'                     => 'Welcome, :name. Your reservation has been successfully confirmed.',
        'referenceCode'               => 'Reservation Reference Code',
        'date'                        => 'Date',
        'time'                        => 'Time',
        'guests'                      => 'Number of Guests',
        'guestsCount'                 => ':count guests',
        'tableNumber'                 => 'Table Number',
        'table'                       => 'Table',
        'errorTitle'                  => 'Please Check Your Information',
        'step1'                       => 'Guest & Time Details',
        'partySize'                   => 'Number of Guests',
        'guestsLabel'                 => 'Guests',
        'reservationDate'             => 'Reservation Date',
        'today'                       => 'Today',
        'tomorrow'                    => 'Tomorrow',
        'preferredTime'               => 'Preferred Arrival Time',
        'email'                       => 'Email Address',
        'sessionDuration'             => 'Available seating duration: 90 minutes',
        'step2'                       => 'Choose Your Table & Seating Location',
        'capacity'                    => 'Capacity: :count guests',
        'available'                   => 'Available',
        'selected'                    => 'Selected ✓',
        'mainHall'                    => 'Main Hall',
        'instantConfirm'              => 'Instant Confirmation',
        'noTables'                    => 'There are no available tables for :count guests at this time. Please try another time or date.',
        'step3'                       => 'Contact Details & Special Requests',
        'fullName'                    => 'Full Name',
        'fullNamePlaceholder'         => 'Enter your name',
        'phone'                       => 'Phone Number for Confirmation',
        'specialRequests'             => 'Special Notes or Requests (Optional)',
        'specialRequestsPlaceholder'  => 'Do you have a special occasion? Need high chairs or specific arrangements?',
        'submit'                      => 'Submit and Confirm Reservation',
        'processing'                  => 'Processing your reservation and checking availability...',
        'contactDetails'              => 'Reservation & Contact Details',

        // Service Type
        'serviceType'                 => 'Service Type',
        'dineIn'                      => 'Dine In 🍽️',
        'takeaway'                    => 'Takeaway 🥡',
        'delivery'                    => 'Home Delivery 🚚',

        'orderTotal'                  => 'Total Order Amount:',
        'confirmReservation'          => 'Confirm Reservation & Receive WhatsApp Notification',
        'processingReservation'       => 'Registering reservation and sending WhatsApp details...',
        'smsNotice'                   => 'A copy of your order will be sent to your WhatsApp for confirmation',
        'requestsPlaceholderMenu'     => 'Example: Please prepare Adeni tea, or there is a nut allergy...',

        // General messages
        'success'                     => 'Your reservation request has been successfully submitted! We will confirm it shortly.',
        'generic_error'               => 'An unexpected error occurred. Please try again later.',
        'rate_limit_exceeded'         => 'Too many attempts. Please try again in a moment.',
    ],

    // ═══════════════════════════════════════════
    // 🛒 Order
    // ═══════════════════════════════════════════
    'order' => [
        // General
        'generic_error'       => 'We could not complete your order. Please try again.',
        'rate_limit_exceeded' => 'Too many orders. Please wait a minute.',

        // Delivery fields (required only for DELIVERY)
        'address_required'    => 'Delivery address is required.',
        'city_required'       => 'City is required.',
        'postal_required'     => 'Postal code is required.',

        // Phone format
        'phone_format'        => 'Invalid phone number (Arabic and English digits are allowed).',
       // ... existing keys ...

        'error_title'         => 'Order could not be completed',
        'validation_error'    => 'Please check the fields',
        'rate_limit_exceeded' => 'Too many order attempts. Please try again in %d seconds.',
            'validation_summary'  => ':count error(s) preventing checkout — please review the highlighted fields.',
        'rate_limit_exceeded' => 'Too many order attempts. Please try again in %d seconds.',
    ],

    // ═══════════════════════════════════════════
    // 💾 Remember Me + Address Fields
    // ═══════════════════════════════════════════
    'remember' => [
        'welcome_back'         => 'Welcome back! 👋',
        'filled_automatically' => 'Your details have been filled automatically.',
        'not_you'              => 'Not you?',
        'checkbox'             => 'Remember me next time 💾',
        'confirm_forget'       => 'Are you sure? Your saved details will be cleared.',

        'forgotten_title'      => 'Your details have been cleared',
        'forgotten_message'    => 'You can start fresh.',

        'delivery_details'     => 'Delivery Address Details 📍',
        'address'              => 'Full Address',
        'address_placeholder'  => 'e.g. Kerkstraat 123, II',
        'city'                 => 'City',
        'city_placeholder'     => 'Amsterdam',
        'postal_code'          => 'Postal Code',
        'postal_placeholder'   => '1012 NK',
    ],

    // ═══════════════════════════════════════════
    // 🔔 Notifications (Toast) — single source
    // ═══════════════════════════════════════════
    'notifications' => [
        'reservation_title'   => '🎉 Reservation Confirmed!',
        'reservation_message' => 'Your reservation has been received.',
        'order_title'         => '👑 Order Received!',
        'order_message'       => 'Our team will contact you shortly.',
        'preorder_title'      => '🛒 Pre-order Added!',
        'preorder_message'    => 'We will prepare your dishes before you arrive.',
    ],

    // ═══════════════════════════════════════════
    // 🖼️ Gallery
    // ═══════════════════════════════════════════
    'gallery' => [
        'badge'      => 'Traditional Heritage Gallery',
        'title'      => 'Authentic Moments Filled with the Aroma of Wood Fire and Spices',
        'subtitle'   => 'Discover our hospitality, traditional Mandi and Muthbi cooking, and authentic Yemeni seating experiences',
        'categories' => 'Gallery Categories',
        'loading'    => 'Loading images...',
        'empty'      => 'No images available in this category yet.',
    ],

    // ═══════════════════════════════════════════
    // 👣 Footer
    // ═══════════════════════════════════════════
    'footer' => [
        'quickLinks'       => 'Quick Links',
        'rights'           => 'All rights reserved to Yemeni Kitchen',
        'contact'          => 'Contact Us',
        'welcome'          => 'We welcome you daily',
        'hours'            => 'Opening Hours',
        'hoursUnavailable' => 'Please contact us for opening hours.',
        'reserveNow'       => 'Book Your Table Now',
        'whatsapp'         => 'Contact via WhatsApp',
        'viewMap'          => 'View Location on Map',
    ],

    // ═══════════════════════════════════════════
    // ⭐ Reviews
    // ═══════════════════════════════════════════
    'reviews' => [
        'badge'    => 'Guest Reviews',
        'title'    => 'What Lovers of Yemeni Taste in Europe Say',
        'stats'    => 'Excellent 4.9 out of 5 rating based on over 1,450 verified reviews',
        'verified' => 'Verified Guest',
        'favorite' => 'Favorite Dish',
    ],

    // ═══════════════════════════════════════════
    // ⚠️ Error Messages (Services/Exceptions)
    // ═══════════════════════════════════════════
    'errors' => [
        // ReservationService
        'missing_customer_data'   => 'Please enter name and phone number.',
        'missing_required_fields' => 'Required fields are missing (date and time).',
        'invalid_party_size'      => 'Invalid party size (1-20).',
        'invalid_status'          => 'Invalid reservation status.',
        'invalid_date_format'     => 'Invalid date format.',
        'invalid_time_format'     => 'Invalid time format.',
        'past_date'               => 'Cannot book a past date.',
        'time_conflict'           => 'Sorry, this time is fully booked. Please choose another time.',

        // OrderService
        'empty_cart'              => 'Cannot create an order without items.',
        'menu_item_unavailable'   => 'The requested item (#:id) is not available.',
        'invalid_quantity'        => 'Invalid quantity for ":name" (value: :qty).',
        'invalid_menu_item_id'    => 'Invalid menu item ID.',
        'invalid_reservation'     => 'The linked reservation does not exist.',
        'invalid_order_status'    => 'Invalid order status.',
        'invalid_order_type'      => 'Invalid order type.',
    ],

    // ═══════════════════════════════════════════
    // 📞 Contact
    // ═══════════════════════════════════════════
    'contact' => [
        'badge' => 'Get in touch with us – our hospitality awaits you',
        'title' => 'We are delighted to welcome you and answer your questions',

        'form' => [
            'title'   => 'Send Us a Direct Message',
            'success' => 'Your message has been sent successfully. We will get back to you as soon as possible.',
            'name'    => 'Full Name *',
            'email'   => 'Email Address *',
            'phone'   => 'Phone Number / WhatsApp',
            'subject' => 'Inquiry Type',
            'message' => 'Your Message or Special Request *',
            'submit'  => 'Send Message Now →',

            'subjects' => [
                'inquiry'  => 'General inquiry about the restaurant and menu',
                'event'    => 'Private event or large family Majlis reservation',
                'catering' => 'Catering and external banquet services',
            ],
        ],

        'details' => [
            'address'      => 'Address & Location',
            'addressValue' => 'Damrak 45, Amsterdam, Netherlands',
            'hours'        => 'Opening Hours',
            'hoursValue'   => 'Daily from 12:00 to 23:30',
        ],
    ],

    // ═══════════════════════════════════════════
    // 🔢 Enums
    // ═══════════════════════════════════════════
    'enums' => [
        'order_status' => [
            'pending'    => 'Pending',
            'processing' => 'Processing',
            'completed'  => 'Completed',
            'cancelled'  => 'Cancelled',
        ],
        'order_type' => [
            'pickup'    => 'Pickup (Takeaway)',
            'dine_in'   => 'Dine-in',
            'preorder'  => 'Pre-order with Reservation',
        ],
        'reservation_status' => [
            'pending'   => 'Pending Confirmation',
            'confirmed' => 'Confirmed',
            'seated'    => 'Guests Seated',
            'cancelled' => 'Cancelled',
            'no_show'   => 'No Show',
        ],
    ],

    // ═══════════════════════════════════════════
    // ♿ Accessibility & UI
    // ═══════════════════════════════════════════
    'accessibility' => [
        'loading'     => 'Loading...',
        'scrollToTop' => 'Scroll to top',
    ],
];