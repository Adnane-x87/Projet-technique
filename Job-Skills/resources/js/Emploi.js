document.addEventListener('DOMContentLoaded', function() {
    
    const searchInput = document.getElementById('search-input');
    const skillFilter = document.getElementById('skill-filter');
    const resetBtn = document.getElementById('reset-filters');
    const paginationContainer = document.getElementById('pagination-container');
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    
    let debounceTimer;
    let currentJobId = null;

    const jobsGrid = document.getElementById('jobs-grid');
    const resultsCount = document.getElementById('results-count');
    const emptyState = document.getElementById('empty-state');
    
    const jobsTableBody = document.getElementById('jobs-table-body');
    const jobModal = document.getElementById('jobModal');
    const jobForm = document.getElementById('jobForm');

    function resetFilters() {
        if (searchInput) searchInput.value = '';
        if (skillFilter) skillFilter.value = '';
        window.location.href = window.location.pathname;
    }
    window.resetFilters = resetFilters;

    function fetchJobsPublic() {
        const search = searchInput?.value || '';
        const skill = skillFilter?.value || '';

        if (!search && !skill) {
            window.location.href = window.location.pathname;
            return;
        }

        const params = new URLSearchParams();
        if (search) params.append('search', search);
        if (skill) params.append('skill', skill);

        fetch('/api/emplois?' + params.toString())
            .then(res => res.json())
            .then(data => {
                if (resultsCount) {
                    resultsCount.textContent = data.count + ' offre' + (data.count > 1 ? 's' : '');
                }
                
                if (paginationContainer) paginationContainer.style.display = 'none';

                if (data.count === 0) {
                    if (jobsGrid) jobsGrid.classList.add('hidden');
                    if (emptyState) emptyState.classList.remove('hidden');
                } else {
                    if (jobsGrid) jobsGrid.classList.remove('hidden');
                    if (emptyState) emptyState.classList.add('hidden');
                    renderJobsPublic(data.emplois);
                }
            })
            .catch(err => console.error(err));
    }

    function renderJobsPublic(emplois) {
        if (!jobsGrid) return;
        jobsGrid.innerHTML = emplois.map(job => `
            <div class="card">
                <div style="display: flex; gap: 20px; align-items: start; margin-bottom: 15px;">
                    ${job.image ? `<img src="/storage/${job.image}" alt="${job.company}" style="width: 80px; height: 80px; object-fit: cover; border-radius: 8px;">` : ''}
                    <div>
                        <h2 style="font-size: 1.5rem; margin-bottom: 5px;">${job.title}</h2>
                        <p style="color: #666; font-size: 1.1rem;">${job.company}</p>
                    </div>
                </div>

                <div style="margin-bottom: 20px; color: #444; line-height: 1.6; white-space: pre-line;">
                    ${job.description}
                </div>

                <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                    ${job.skills.map(s => `<span style="background: #e0e0e0; padding: 5px 12px; border-radius: 4px; font-size: 14px;">${s.name}</span>`).join('')}
                </div>

                <div style="margin-top: 20px; text-align: right;">
                    <a href="${job.url}" class="btn btn-primary">Voir l'offre</a>
                </div>
            </div>
        `).join('');
    }

    function fetchJobsAdmin() {
        const search = searchInput?.value || '';
        const skill = skillFilter?.value || '';

        if (!search && !skill) {
            window.location.href = window.location.pathname;
            return;
        }

        const params = new URLSearchParams();
        if (search) params.append('search', search);
        if (skill) params.append('skill', skill);

        fetch('/api/emplois?' + params.toString())
            .then(res => res.json())
            .then(data => {
                if (paginationContainer) paginationContainer.style.display = 'none';
                renderJobsAdmin(data.emplois);
            })
            .catch(err => console.error(err));
    }

    function renderJobsAdmin(emplois) {
        if (!jobsTableBody) return;
        
        if (emplois.length === 0) {
            jobsTableBody.innerHTML =
                `<tr><td colspan="5" style="padding: 30px; text-align: center; color: #666;">Aucune offre trouvée.</td></tr>`;
            return;
        }

        jobsTableBody.innerHTML = emplois.map(job => {
            const skillsHtml = job.skills.slice(0, 2).map(s =>
                `<span style="background: #e0e0e0; padding: 2px 6px; border-radius: 3px; font-size: 11px; margin-right: 3px;">${s.name}</span>`
            ).join('');

            const extraSkills = job.skills.length > 2 ?
                `<span style="color: #999; font-size: 11px;">+${job.skills.length - 2}</span>` : '';

            const imageHtml = job.image ?
                `<img src="/storage/${job.image}" style="width: 30px; height: 30px; object-fit: cover; border-radius: 4px;">` :
                '';

            const safeTitle = job.title.replace(/'/g, "\\'").replace(/"/g, '&quot;');
            const safeCompany = job.company.replace(/'/g, "\\'").replace(/"/g, '&quot;');
            const safeDesc = job.description.replace(/'/g, "\\'").replace(/"/g, '&quot;').replace(/\n/g, '\\n');
            const skillsJson = JSON.stringify(job.skills.map(s => s.id));

            return `
                <tr style="border-bottom: 1px solid #eee;">
                    <td style="padding: 12px 0;">${job.title}</td>
                    <td style="padding: 12px 0;">
                        <div style="display: flex; align-items: center; gap: 10px;">
                            ${imageHtml}
                            ${job.company}
                        </div>
                    </td>
                    <td style="padding: 12px 0;">
                        ${skillsHtml}
                        ${extraSkills}
                    </td>
                    <td style="padding: 12px 0; color: #666; font-size: 13px;">
                        ${job.date}
                    </td>
                    <td style="padding: 12px 0; text-align: right;">
                        <button onclick='openEditModal(${job.id}, "${safeTitle}", "${safeCompany}", "${safeDesc}", ${skillsJson})'
                            class="btn" style="padding: 4px 10px; font-size: 12px;">Modifier</button>
                        
                        <button onclick="deleteJob(${job.id})" class="btn btn-danger"
                            style="padding: 4px 10px; font-size: 12px;">Supprimer</button>
                    </td>
                </tr>
            `;
        }).join('');
    }

    function openCreateModal() {
        currentJobId = null;
        const modalTitle = document.getElementById('modalTitle');
        const method = document.getElementById('method');
        
        if (modalTitle) modalTitle.textContent = 'Ajouter une offre';
        if (method) method.value = 'POST';
        if (jobForm) jobForm.reset();
        document.querySelectorAll('input[name="skills[]"]').forEach(cb => cb.checked = false);
        if (jobModal) jobModal.style.display = 'flex';
    }
    window.openCreateModal = openCreateModal;

    function openEditModal(id, title, company, description, skillIds) {
        currentJobId = id;
        const modalTitle = document.getElementById('modalTitle');
        const method = document.getElementById('method');
        const titleInput = document.getElementById('title');
        const companyInput = document.getElementById('company');
        const descriptionInput = document.getElementById('description');

        if (modalTitle) modalTitle.textContent = 'Modifier l\'offre';
        if (method) method.value = 'PUT';
        if (titleInput) titleInput.value = title;
        if (companyInput) companyInput.value = company;
        if (descriptionInput) descriptionInput.value = description;

        document.querySelectorAll('input[name="skills[]"]').forEach(cb => {
            cb.checked = skillIds.includes(parseInt(cb.value));
        });

        if (jobModal) jobModal.style.display = 'flex';
    }
    window.openEditModal = openEditModal;

    function closeModal() {
        if (jobModal) jobModal.style.display = 'none';
    }
    window.closeModal = closeModal;

    async function deleteJob(id) {
        if (!confirm('Supprimer cette offre ?')) return;

        try {
            const response = await fetch(`/emplois/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            });

            if (response.ok) {
                fetchJobsAdmin();
            } else {
                alert('Erreur lors de la suppression');
            }
        } catch (e) {
            console.error(e);
            alert('Erreur de connexion');
        }
    }
    window.deleteJob = deleteJob;

    async function handleFormSubmit(e) {
        e.preventDefault();

        const submitBtn = document.getElementById('submitBtn');
        if (!submitBtn) return;
        
        const originalBtnText = submitBtn.textContent;
        submitBtn.textContent = 'Enregistrement...';
        submitBtn.disabled = true;

        const formData = new FormData(e.target);

        const url = currentJobId ?
            `/emplois/${currentJobId}` :
            window.emploisStoreRoute;

        try {
            const response = await fetch(url, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            if (response.ok) {
                closeModal();
                fetchJobsAdmin();
            } else {
                const data = await response.json();
                alert(data.message || 'Une erreur est survenue');
            }
        } catch (error) {
            console.error(error);
            alert('Erreur de connexion');
        } finally {
            submitBtn.textContent = originalBtnText;
            submitBtn.disabled = false;
        }
    }
    window.handleFormSubmit = handleFormSubmit;

    function showModal() {
        const backdrop = document.getElementById('job-modal-backdrop');
        const modal = document.getElementById('job-modal');
        if (backdrop) backdrop.classList.remove('hidden');
        if (modal) modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
    window.showModal = showModal;

    function closeJobModal() {
        const backdrop = document.getElementById('job-modal-backdrop');
        const modal = document.getElementById('job-modal');
        if (backdrop) backdrop.classList.add('hidden');
        if (modal) modal.classList.add('hidden');
        document.body.style.overflow = '';
    }
    window.closeJobModal = closeJobModal;

    const isPublicPage = !!jobsGrid;
    const isAdminPage = !!jobsTableBody;
    const fetchFunction = isAdminPage ? fetchJobsAdmin : fetchJobsPublic;

    if (searchInput) {
        searchInput.addEventListener('input', () => {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(fetchFunction, 300);
        });
    }

    if (skillFilter) {
        skillFilter.addEventListener('change', fetchFunction);
    }

    if (resetBtn) {
        resetBtn.addEventListener('click', resetFilters);
    }

    if (jobModal) {
        jobModal.addEventListener('click', (e) => {
            if (e.target === jobModal) {
                closeModal();
            }
        });
    }

    if (jobForm) {
        jobForm.addEventListener('submit', handleFormSubmit);
    }

    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') {
            closeModal();
            closeJobModal();
        }
    });

});
