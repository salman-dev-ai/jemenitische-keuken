{{-- Serveerstolp uit het logo: de stolp gaat omhoog en onthult de foutcode op het bord. --}}
<svg viewBox="-20 -64 400 290" class="cloche" :class="{ 'is-open': open }" aria-hidden="true" focusable="false">
    <defs>
        <clipPath id="dome"><path d="M72 150 A108 108 0 0 1 288 150 Z"/></clipPath>
        <pattern id="lattice" width="34" height="34" patternUnits="userSpaceOnUse" patternTransform="rotate(45)">
            <path d="M0 0H34M0 0V34" stroke="#c96600" stroke-width="3" fill="none"/>
        </pattern>
    </defs>

    {{-- stoom --}}
    <g class="steam" fill="none" stroke="#c96600" stroke-width="9" stroke-linecap="round">
        <path d="M44 122 C18 92 64 70 40 30 C32 10 48 -8 58 -22"/>
        <path d="M78 112 C58 90 94 70 78 42"/>
    </g>

    {{-- bord --}}
    <path d="M14 156 C90 206 270 206 346 156 C262 178 98 178 14 156 Z" fill="#fff"/>

    {{-- foutcode (verborgen onder de stolp) --}}
    <text class="code" x="180" y="148" text-anchor="middle">{{ $code }}</text>

    {{-- stolp --}}
    <g class="lid">
        <path d="M72 150 A108 108 0 0 1 288 150 Z" fill="#350302"/>
        <g clip-path="url(#dome)">
            <rect x="60" y="30" width="240" height="125" fill="url(#lattice)" opacity=".85"/>
            <g fill="#c96600">
                <ellipse cx="150" cy="104" rx="9" ry="22" transform="rotate(-25 150 104)"/>
                <ellipse cx="212" cy="96" rx="9" ry="22" transform="rotate(25 212 96)"/>
                <ellipse cx="180" cy="128" rx="8" ry="18"/>
                <ellipse cx="116" cy="132" rx="8" ry="16" transform="rotate(-50 116 132)"/>
                <ellipse cx="246" cy="130" rx="8" ry="16" transform="rotate(50 246 130)"/>
            </g>
        </g>
        <path d="M72 150 A108 108 0 0 1 288 150 Z" fill="none" stroke="#fff" stroke-width="9" stroke-linejoin="round"/>
        <path d="M180 42 V28" stroke="#fff" stroke-width="9"/>
        <ellipse cx="180" cy="22" rx="22" ry="8" fill="#fff"/>
    </g>
</svg>
