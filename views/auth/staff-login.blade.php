@include('auth.partials.portal-login', [
    'portalTitle' => 'Portal Petugas/Admin',
    'loginAction' => route('login.staff.submit'),
    'alternatePortalUrl' => route('login'),
    'alternatePortalLabel' => 'Beralih ke portal Mahasiswa/Dosen',
    'showRegistration' => false,
    'cardClass' => 'login-card--staff',
])
