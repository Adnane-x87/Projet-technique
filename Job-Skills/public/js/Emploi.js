const table = document.getElementById('jobs-grid');
const modal = document.getElementById('contactModal');

// 1. Recherche
document.getElementById('search')?.addEventListener('input', e => {
    fetch(`${window.CONTACT_ROUTES.search}?search=${e.target.value}`, { 
        headers: { 'X-Requested-With': 'XMLHttpRequest' } 
    })
    .then(r => r.text())
    .then(html => table.innerHTML = html);
});

// 2. Filtre
document.getElementById('skill-filter')?.addEventListener('change', e => {
    const searchVal = document.getElementById('search').value;
    fetch(`${window.CONTACT_ROUTES.search}?search=${searchVal}&skill=${e.target.value}`, { 
        headers: { 'X-Requested-With': 'XMLHttpRequest' } 
    })
    .then(r => r.text())
    .then(html => table.innerHTML = html);
});

// 3. Ajout
document.getElementById('contactForm')?.addEventListener('submit', e => {
    e.preventDefault();
    fetch(e.target.action, {
        method: 'POST',
        body: new FormData(e.target),
        headers: { 
            'X-Requested-With': 'XMLHttpRequest', 
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content 
        }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            modal.classList.add('hidden');
            e.target.reset();
            document.getElementById('success-msg').innerText = data.message;
            document.getElementById('search').dispatchEvent(new Event('input'));
            setTimeout(() => document.getElementById('success-msg').innerText = '', 3000);
        }
    });
});

// 4. Modal
document.getElementById('openModal')?.addEventListener('click', () => modal.classList.remove('hidden'));
document.querySelectorAll('.close-btn')?.forEach(btn => btn.addEventListener('click', () => modal.classList.add('hidden')));
window.onclick = (e) => { if(e.target == modal) modal.classList.add('hidden'); }
