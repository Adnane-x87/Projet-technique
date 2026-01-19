const table = document.getElementById('jobs-grid');
const modal = document.getElementById('contactModal');

document.getElementById('search')?.addEventListener('input', e => {
    fetch(`${window.CONTACT_ROUTES.search}?search=${e.target.value}`, { 
        headers: { 'X-Requested-With': 'XMLHttpRequest' } 
    })
    .then(r => r.text())
    .then(html => {
        if (table) table.innerHTML = html;
    });
});

document.getElementById('skill-filter')?.addEventListener('change', e => {
    const searchVal = document.getElementById('search').value;
    fetch(`${window.CONTACT_ROUTES.search}?search=${searchVal}&skill=${e.target.value}`, { 
        headers: { 'X-Requested-With': 'XMLHttpRequest' } 
    })
    .then(r => r.text())
    .then(html => {
        if (table) table.innerHTML = html;
    });
});

document.getElementById('contactForm')?.addEventListener('submit', e => {
    e.preventDefault();
    const formData = new FormData(e.target);
    
    const submitBtn = e.target.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;
    submitBtn.textContent = 'Création...';
    submitBtn.disabled = true;

    fetch(e.target.action, {
        method: 'POST',
        body: formData,
        headers: { 
            'X-Requested-With': 'XMLHttpRequest', 
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            if (modal) modal.classList.add('hidden');
            e.target.reset();
            
            const successMsg = document.getElementById('success-msg');
            if (successMsg) {
                successMsg.innerText = data.message;
                setTimeout(() => successMsg.innerText = '', 3000);
            }

            document.getElementById('search').dispatchEvent(new Event('input'));
        } else {
            alert('Erreur: ' + (data.message || 'Vérifiez les données'));
        }
    })
    .catch(err => {
        console.error(err);
        alert('Une erreur est survenue lors de la création.');
    })
    .finally(() => {
        submitBtn.textContent = originalText;
        submitBtn.disabled = false;
    });
});

document.getElementById('openModal')?.addEventListener('click', () => {
    if (modal) modal.classList.remove('hidden');
});

document.querySelectorAll('.close-btn')?.forEach(btn => {
    btn.addEventListener('click', () => {
        if (modal) modal.classList.add('hidden');
    });
});

window.addEventListener('click', (e) => {
    if (e.target === modal) {
        if (modal) modal.classList.add('hidden');
    }
});