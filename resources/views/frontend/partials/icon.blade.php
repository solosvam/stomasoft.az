<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
@switch($n)
    @case('users')<path d="M17 21v-2a4 4 0 00-4-4H7a4 4 0 00-4 4v2"/><circle cx="10" cy="7" r="4"/><path d="M21 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/>@break
    @case('calendar')<rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18M9 16l2 2 4-4"/>@break
    @case('wallet')<path d="M20 12V8a2 2 0 00-2-2H5a2 2 0 010-4h12"/><path d="M3 5v14a2 2 0 002 2h15a1 1 0 001-1v-4"/><path d="M21 12h-4a2 2 0 000 4h4z"/>@break
    @case('chart')<path d="M3 3v18h18"/><path d="M7 15l4-4 3 3 5-6"/>@break
    @case('link')<path d="M10 13a5 5 0 007.07 0l3-3a5 5 0 00-7.07-7.07l-1.5 1.5"/><path d="M14 11a5 5 0 00-7.07 0l-3 3a5 5 0 007.07 7.07l1.5-1.5"/>@break
    @case('file')<path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6M8 13h8M8 17h5"/>@break
    @case('rx')<rect x="5" y="3" width="14" height="18" rx="2"/><path d="M9 3V2h6v1M9 9h6M9 13h6M9 17h3"/>@break
    @case('box')<path d="M21 8l-9-5-9 5v8l9 5 9-5z"/><path d="M3 8l9 5 9-5M12 13v8"/>@break
    @case('shield')<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="M9 12l2 2 4-4"/>@break
    @case('check')<path d="M20 6L9 17l-5-5"/>@break
    @case('arrow')<path d="M5 12h14M12 5l7 7-7 7"/>@break
    @case('globe')<circle cx="12" cy="12" r="10"/><path d="M2 12h20M12 2a15 15 0 010 20M12 2a15 15 0 000 20"/>@break
    @case('server')<rect x="2" y="3" width="20" height="8" rx="2"/><rect x="2" y="13" width="20" height="8" rx="2"/><path d="M6 7h.01M6 17h.01"/>@break
    @case('browser')<rect x="2" y="4" width="20" height="16" rx="2"/><path d="M2 9h20M6 6.5h.01M9 6.5h.01"/>@break
    @case('pin')<path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z"/><circle cx="12" cy="9" r="2.5"/>@break
    @case('search')<circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>@break
    @case('phone')<path d="M22 16.9v3a2 2 0 01-2.2 2 19.8 19.8 0 01-8.6-3.1 19.5 19.5 0 01-6-6A19.8 19.8 0 012.1 4.2 2 2 0 014.1 2h3a2 2 0 012 1.7c.1 1 .4 1.9.7 2.8a2 2 0 01-.5 2.1L8.1 9.9a16 16 0 006 6l1.3-1.3a2 2 0 012.1-.4c.9.3 1.8.6 2.8.7a2 2 0 011.7 2z"/>@break
@endswitch
</svg>
