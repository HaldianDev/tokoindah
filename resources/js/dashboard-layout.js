// Pastikan selalu light mode & bersihkan settingan tema sebelumnya
localStorage.removeItem('theme');
document.documentElement.classList.remove('dark');
document.body.classList.remove('dark');

// ====== Sidebar Toggle ======
let sidebarOpen = window.innerWidth >= 1024;

function toggleSidebar() {
    if (window.innerWidth >= 1024) {
        sidebarOpen = !sidebarOpen;
        document.getElementById('sidebar').classList.toggle('collapsed', !sidebarOpen);
        document.getElementById('topbar').classList.toggle('sidebar-collapsed', !sidebarOpen);
        document.getElementById('dashboardMain').classList.toggle('sidebar-collapsed', !sidebarOpen);
    } else {
        document.getElementById('sidebar').classList.toggle('mobile-open');
        document.getElementById('sidebarOverlay').classList.toggle('visible', document.getElementById('sidebar').classList.contains('mobile-open'));
    }
}
function closeSidebar() {
    document.getElementById('sidebar').classList.remove('mobile-open');
    document.getElementById('sidebarOverlay').classList.remove('visible');
}

// ====== Section Switcher ======
function switchSection(sectionId, title, breadcrumb) {
    // Hide all sections
    document.querySelectorAll('.dash-section').forEach(s => s.classList.remove('active'));
    // Show target
    const target = document.getElementById(sectionId);
    if (target) target.classList.add('active');

    // Update topbar
    if (title) document.getElementById('topbarTitle').textContent = title;
    if (breadcrumb) document.getElementById('topbarBreadcrumb').textContent = 'RumahKeramik / ' + breadcrumb;

    // Update sidebar active state
    document.querySelectorAll('.sidebar-item[data-section]').forEach(btn => {
        btn.classList.toggle('active', btn.dataset.section === sectionId);
    });

    // Close mobile sidebar
    if (window.innerWidth < 1024) closeSidebar();

    // Save active section
    sessionStorage.setItem('activeDashSection', sectionId);
}

// Init: restore last section or activate first
document.addEventListener('DOMContentLoaded', function() {
    const saved = sessionStorage.getItem('activeDashSection');
    const firstBtn = document.querySelector('.sidebar-item[data-section]');
    const firstSection = firstBtn ? firstBtn.dataset.section : null;
    const targetSection = (saved && document.getElementById(saved)) ? saved : firstSection;

    if (targetSection) {
        const btn = document.querySelector(`.sidebar-item[data-section="${targetSection}"]`);
        const title = btn ? btn.dataset.title : document.title;
        const breadcrumb = btn ? btn.dataset.breadcrumb : '';
        switchSection(targetSection, title, breadcrumb);
    }
});

// Swal Session Handler
const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3500,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer)
        toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
});

document.addEventListener('DOMContentLoaded', function() {

    // Swal confirmation for forms
    document.body.addEventListener('submit', function(e) {
        const form = e.target;
        if (form.matches('form.needs-confirmation')) {
            e.preventDefault();
            const message = form.dataset.message || 'Anda yakin ingin melanjutkan?';
            
            Swal.fire({
                title: 'Konfirmasi Tindakan',
                text: message,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Lanjutkan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }
    });
});
