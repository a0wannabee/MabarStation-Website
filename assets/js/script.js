document.addEventListener('DOMContentLoaded', () => {
    // Flip card katalog game saat diklik.
    const steamCards = document.querySelectorAll('.steam-card');

    steamCards.forEach(card => {
        card.addEventListener('click', () => {
            const isAlreadyFlipped = card.classList.contains('is-flipped');

            steamCards.forEach(otherCard => {
                otherCard.classList.remove('is-flipped');
            });

            if (!isAlreadyFlipped) {
                card.classList.add('is-flipped');
            }
        });
    });

    // Batasi input nomor HP agar hanya menerima angka (maksimal 15 karakter).
    const phoneInputs = document.querySelectorAll('input[name="phone_number"]');
    phoneInputs.forEach(input => {
        input.addEventListener('input', () => {
            input.value = input.value.replace(/[^0-9]/g, '').slice(0, 15);
        });
    });

    // Toggling tab pada halaman profile.
    const tabButtons = document.querySelectorAll('.profile-tab-btn');
    const tabContents = document.querySelectorAll('.profile-tab-content');

    tabButtons.forEach(button => {
        button.addEventListener('click', () => {
            const target = button.dataset.target;

            tabButtons.forEach(btn => btn.classList.remove('active'));
            tabContents.forEach(content => content.classList.remove('active'));

            button.classList.add('active');
            const targetContent = document.querySelector(target);
            if (targetContent) {
                targetContent.classList.add('active');
            }
        });
    });
});