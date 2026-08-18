
document.addEventListener('DOMContentLoaded', function () {
    const hamburgerMenu = document.getElementById('hamburgerMenu');
    const navLinks = document.getElementById('navLinks');

    if (hamburgerMenu) {
        hamburgerMenu.addEventListener('click', function () {
            navLinks.classList.toggle('active');
        });
    }

    const profileDropdownButton = document.getElementById('profile-dropdown-button');
    const profileDropdownMenu = document.getElementById('profile-dropdown-menu');
    const profileDropdownContainer = document.getElementById('profile-dropdown-container');

    if (profileDropdownButton && profileDropdownMenu && profileDropdownContainer) {
        profileDropdownButton.addEventListener('click', function (event) {
            event.stopPropagation();
            profileDropdownMenu.classList.toggle('hidden');
        });

        document.addEventListener('click', function (event) {
            if (!profileDropdownContainer.contains(event.target)) {
                profileDropdownMenu.classList.add('hidden');
            }
        });
    }
});
