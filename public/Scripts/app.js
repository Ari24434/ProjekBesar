(function () {
  const baseUrl = (window.APP_BASE_URL || '').replace(/\/$/, '');
  const userRoutes = {
    beranda: '/user/beranda',
    dashboard: '/user/beranda',
    tryout: '/user/daftar-tryout',
    'daftar-tryout': '/user/daftar-tryout',
    riwayat: '/user/riwayat',
    analisis: '/user/analisis',
    profil: '/user/profil',
    hasil: '/user/hasil-tryout',
    exam: '/user/soal-tryout',
  };
  const adminRoutes = {
    beranda: '/Admin/beranda',
    dashboard: '/Admin/beranda',
    peserta: '/Admin/kelola-peserta',
    tryout: '/Admin/kelola-tryout',
    soal: '/Admin/kelola-soal',
    nilai: '/Admin/nilai-hasil',
    laporan: '/Admin/laporan-rekap',
    pengaturan: '/Admin/pengaturan',
  };

  function getSidebar() {
    return document.getElementById('sidebar');
  }

  function getBackdrop() {
    let backdrop = document.querySelector('.sidebar-backdrop');

    if (!backdrop) {
      backdrop = document.createElement('div');
      backdrop.className = 'sidebar-backdrop';
      backdrop.addEventListener('click', closeSb);
      document.body.appendChild(backdrop);
    }

    return backdrop;
  }

  function openSb() {
    const sidebar = getSidebar();

    if (!sidebar) return;

    sidebar.classList.add('open');
    getBackdrop().classList.add('show');
    document.body.style.overflow = 'hidden';
  }

  function closeSb() {
    const sidebar = getSidebar();
    const backdrop = document.querySelector('.sidebar-backdrop');

    if (sidebar) {
      sidebar.classList.remove('open');
    }

    if (backdrop) {
      backdrop.classList.remove('show');
    }

    document.body.style.overflow = '';
  }

  window.toggleSb = function () {
    const sidebar = getSidebar();

    if (!sidebar) return;

    if (sidebar.classList.contains('open')) {
      closeSb();
      return;
    }

    openSb();
  };

  window.nav = function (target) {
    const path = userRoutes[target] || adminRoutes[target] || target;

    if (!path) return;

    if (/^https?:\/\//.test(path)) {
      window.location.href = path;
      return;
    }

    window.location.href = baseUrl + (path.startsWith('/') ? path : '/' + path);
  };

  window.togglePwd = function () {
    const password = document.getElementById('password');
    const eyeIcon = document.getElementById('eyeIcon');

    if (!password) return;

    const shouldShow = password.type === 'password';
    password.type = shouldShow ? 'text' : 'password';
    eyeIcon?.classList.toggle('bi-eye', !shouldShow);
    eyeIcon?.classList.toggle('bi-eye-slash', shouldShow);
  };

  window.handleLogin = function (event) {
    event.preventDefault();
    window.location.href = baseUrl + '/user/beranda';
  };

  window.showToast = function (message, type) {
    if (window.Swal) {
      const normalizedType = type === 'error' || type === 'success' || type === 'warning' || type === 'info'
        ? type
        : 'info';

      Swal.fire({
        icon: normalizedType,
        title: normalizedType === 'success' ? 'Berhasil' : (normalizedType === 'error' ? 'Gagal' : 'Informasi'),
        text: message || '',
        confirmButtonText: 'OK',
        confirmButtonColor: normalizedType === 'error' ? '#DC2626' : '#1E54B7',
        timer: normalizedType === 'success' ? 2400 : undefined,
        timerProgressBar: normalizedType === 'success'
      });
      return;
    }

    const toast = document.createElement('div');
    toast.className = 'app-toast ' + (type || 'info');
    toast.textContent = message;
    document.body.appendChild(toast);

    requestAnimationFrame(function () {
      toast.classList.add('show');
    });

    window.setTimeout(function () {
      toast.classList.remove('show');
      window.setTimeout(function () {
        toast.remove();
      }, 220);
    }, 2600);
  };

  document.addEventListener('click', function (event) {
    const link = event.target.closest('.sidebar .nav-item');

    if (link && window.matchMedia('(max-width: 780px)').matches) {
      closeSb();
    }
  });

  window.addEventListener('resize', function () {
    if (!window.matchMedia('(max-width: 780px)').matches) {
      closeSb();
    }
  });
})();
