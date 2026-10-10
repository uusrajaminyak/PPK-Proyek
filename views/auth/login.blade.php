@include('auth.partials.portal-login', [
    'portalTitle' => 'Portal Mahasiswa/Dosen',
    'loginAction' => route('login'),
    'alternatePortalUrl' => route('login.staff'),
    'alternatePortalLabel' => 'Beralih ke portal staf/admin',
    'showRegistration' => true,
    'cardClass' => 'login-card--student',
])
