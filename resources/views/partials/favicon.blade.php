@php
    $schoolFavicon = !empty($site_settings['school_logo'])
        ? asset('storage/' . $site_settings['school_logo'])
        : 'https://upload.wikimedia.org/wikipedia/commons/9/9c/Logo_of_Ministry_of_Education_and_Culture_of_Republic_of_Indonesia.svg';
@endphp
{{-- Dynamic School Logo Favicon --}}
<link rel="icon" type="image/png" href="{{ $schoolFavicon }}">
<link rel="shortcut icon" href="{{ $schoolFavicon }}">
<link rel="apple-touch-icon" href="{{ $schoolFavicon }}">
