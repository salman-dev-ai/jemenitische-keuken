{{--
    شريط الروابط الرئيسي.
    يعرض الرئيسية والمنيو والمعرض وعن المطعم،
    ويلون الرابط النشط مع خط سفلي يتحرك فوراً عبر Alpine.
--}}
<nav class="w-full border-t border-gray-200/50 pt-2 lg:w-auto lg:border-t-0 lg:pt-0"
    aria-label="{{ __('messages.nav.primary') }}">
    <ul class="flex items-center justify-center gap-4 text-xs font-bold text-gray-700 sm:gap-6 sm:text-sm lg:gap-8">
        {{-- الرئيسية مع الخط النشط --}}
        <li>
            <a href="#home" @click="activeSection = 'home'"
                class="relative block pb-1 transition-colors duration-300 hover:text-[#E07513]"
                :class="activeSection === 'home' ? 'text-[#E07513]' : ''">
                {{ __('messages.nav.home') }}
                <span class="absolute bottom-0 left-0 h-0.5 bg-[#E07513] transition-all duration-300 ease-out"
                    :class="activeSection === 'home' ? 'w-full' : 'w-0 hover:w-full'"></span>
            </a>
        </li>

        {{-- قائمة الطعام --}}
        <li>
            <a href="#menu" @click="activeSection = 'menu'"
                class="relative block pb-1 transition-colors duration-300 hover:text-[#E07513]"
                :class="activeSection === 'menu' ? 'text-[#E07513]' : ''">
                {{ __('messages.nav.menu') }}
                <span class="absolute bottom-0 left-0 h-0.5 bg-[#E07513] transition-all duration-300 ease-out"
                    :class="activeSection === 'menu' ? 'w-full' : 'w-0 hover:w-full'"></span>
            </a>
        </li>

        {{-- معرض الصور --}}
        <li>
            <a href="#gallery" @click="activeSection = 'gallery'"
                class="relative block pb-1 transition-colors duration-300 hover:text-[#E07513]"
                :class="activeSection === 'gallery' ? 'text-[#E07513]' : ''">
                {{ __('messages.nav.gallery') }}
                <span class="absolute bottom-0 left-0 h-0.5 bg-[#E07513] transition-all duration-300 ease-out"
                    :class="activeSection === 'gallery' ? 'w-full' : 'w-0 hover:w-full'"></span>
            </a>
        </li>

        {{-- عن المطعم، والوجهة هي التذييل --}}
        <li>
            <a href="#about" @click="activeSection = 'about'"
                class="relative block pb-1 transition-colors duration-300 hover:text-[#E07513]"
                :class="activeSection === 'about' ? 'text-[#E07513]' : ''">
                {{ __('messages.nav.about') }}
                <span class="absolute bottom-0 left-0 h-0.5 bg-[#E07513] transition-all duration-300 ease-out"
                    :class="activeSection === 'about' ? 'w-full' : 'w-0 hover:w-full'"></span>
            </a>
        </li>
    </ul>
</nav>
