<?php $role = $_SESSION['user_role'] ?? 0; ?>
<aside class="w-64 bg-white border-r border-gray-200 hidden md:flex flex-col h-full min-h-screen shadow-sm z-20">
    <div class="h-16 flex items-center justify-center border-b border-gray-200">
        <h1 class="text-2xl font-bold text-emerald-600 tracking-wider">DESA<span class="text-slate-700">KU</span></h1>
    </div>

    <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto custom-scrollbar">
        
        <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Menu Utama</p>
        
        <a href="dashboard.php" class="flex items-center px-4 py-3 text-slate-600 hover:bg-emerald-50 hover:text-emerald-600 rounded-lg transition-colors group">
            <svg class="w-5 h-5 mr-3 group-hover:text-emerald-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
            <span class="font-medium">Dashboard</span>
        </a>

        <?php if($role == 1 || $role == 3): ?>
        <p class="px-4 text-xs font-semibold text-gray-400 uppercase mt-6 mb-2 tracking-wider">Sekretariat</p>
        
        <a href="berita.php" class="flex items-center px-4 py-3 text-slate-600 hover:bg-emerald-50 hover:text-emerald-600 rounded-lg transition-colors">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
            <span class="font-medium">Kelola Berita</span>
        </a>

        <a href="data_penduduk.php" class="flex items-center px-4 py-3 text-slate-600 hover:bg-emerald-50 hover:text-emerald-600 rounded-lg transition-colors">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            <span class="font-medium">Data Penduduk</span>
        </a>

        <a href="inventaris.php" class="flex items-center px-4 py-3 text-slate-600 hover:bg-emerald-50 hover:text-emerald-600 rounded-lg transition-colors">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
            <span class="font-medium">Inventaris Desa</span>
        </a>

        <a href="kelola_user.php" class="flex items-center px-4 py-3 text-slate-600 hover:bg-emerald-50 hover:text-emerald-600 rounded-lg transition-colors">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            <span class="font-medium">Manajemen User</span>
        </a>
        <?php endif; ?>


        <?php if($role == 1 || $role == 3): ?>
        <p class="px-4 text-xs font-semibold text-gray-400 uppercase mt-6 mb-2 tracking-wider">Pelayanan</p>
        <a href="layanan_surat.php" class="flex items-center px-4 py-3 text-slate-600 hover:bg-emerald-50 hover:text-emerald-600 rounded-lg transition-colors">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
            <span class="font-medium">Layanan Surat</span>
        </a>
        <?php endif; ?>


        <?php if($role == 1 || $role == 4): ?>
        <p class="px-4 text-xs font-semibold text-gray-400 uppercase mt-6 mb-2 tracking-wider">Keuangan</p>
        <a href="apb_desa.php" class="flex items-center px-4 py-3 text-slate-600 hover:bg-emerald-50 hover:text-emerald-600 rounded-lg transition-colors">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span class="font-medium">APB & Laporan</span>
        </a>
        <?php endif; ?>


        <?php if($role == 1): ?>
        <p class="px-4 text-xs font-semibold text-gray-400 uppercase mt-6 mb-2 tracking-wider">Server</p>
        <a href="https://cpanel.byethost.com/" target="_blank" class="flex items-center px-4 py-3 text-red-600 bg-red-50 hover:bg-red-100 rounded-lg transition-colors">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
            <span class="font-medium">CPanel Hosting</span>
        </a>
        <?php endif; ?>

    </nav>
    
    <div class="p-4 border-t border-gray-200">
        <a href="logout.php" class="flex items-center px-4 py-2 text-slate-600 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
            <span class="font-medium">Logout</span>
        </a>
    </div>
</aside>