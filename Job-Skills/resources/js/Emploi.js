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
        jobsGrid.className = "grid sm:grid-cols-2 lg:grid-cols-3 gap-6";
        jobsGrid.innerHTML = emplois.map(job => {
            const skillBadge = job.skills.length > 0 ? 
                `<span class="py-1 px-3 bg-white/90 backdrop-blur shadow-sm rounded-full text-[10px] font-bold text-blue-600 uppercase tracking-widest">${job.skills[0].name}</span>` : '';
            
            return `
            <a href="${job.url}" class="group flex flex-col bg-white border border-gray-200 rounded-2xl shadow-sm hover:shadow-md transition-all overflow-hidden h-full">
                <div class="aspect-video relative overflow-hidden bg-slate-50 border-b border-gray-100">
                    ${job.image ? 
                        `<img src="/storage/${job.image}" class="object-cover w-full h-full group-hover:scale-105 transition-transform duration-500" alt="${job.company}">` : 
                        `<div class="flex items-center justify-center h-full bg-slate-50">
                            <svg class="size-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>`
                    }
                    <div class="absolute top-3 right-3 text-right">
                        ${skillBadge}
                    </div>
                </div>

                <div class="p-5 flex flex-col flex-1">
                    <h3 class="text-lg font-bold text-gray-800 group-hover:text-blue-600 transition-colors">
                        ${job.title}
                    </h3>
                    <p class="mt-2 text-sm text-gray-500 line-clamp-2">
                        ${job.description}
                    </p>
                    
                    <div class="mt-auto pt-4 border-t border-gray-100 flex items-center justify-between">
                        <div class="flex items-center gap-x-2">
                            <div class="size-6 bg-blue-100 rounded-full flex items-center justify-center text-blue-600">
                                <svg class="size-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                            <span class="text-xs font-medium text-gray-600 line-clamp-1">${job.company}</span>
                        </div>
                        <div class="flex items-center text-blue-600 font-semibold text-sm">
                            Voir détails
                            <svg class="size-4 ms-1 group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </a>
        `}).join('');
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
            jobsTableBody.innerHTML = `
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                        <div class="flex flex-col items-center">
                            <svg class="h-12 w-12 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                            </svg>
                            <p class="text-lg font-medium">Aucune offre trouvée</p>
                        </div>
                    </td>
                </tr>`;
            return;
        }

        jobsTableBody.innerHTML = emplois.map(job => {
            const skillsHtml = job.skills.slice(0, 2).map(s =>
                `<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-700">${s.name}</span>`
            ).join('');

            const extraSkills = job.skills.length > 2 ?
                `<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-50 text-blue-600">+${job.skills.length - 2}</span>` : '';

            const imageHtml = job.image ?
                `<img src="/storage/${job.image}" class="h-8 w-8 rounded-md object-cover mr-3 border border-gray-100 shadow-sm">` :
                `<div class="h-8 w-8 rounded-md bg-gray-100 flex items-center justify-center mr-3 border border-gray-100">
                    <svg class="h-4 w-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-2a1 1 0 00-1-1H9a1 1 0 00-1 1v2a1 1 0 01-1 1H4a1 1 0 110-2V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z" clip-rule="evenodd"/></svg>
                </div>`;

            const safeTitle = job.title.replace(/'/g, "\\'").replace(/"/g, '&quot;');
            const safeCompany = job.company.replace(/'/g, "\\'").replace(/"/g, '&quot;');
            const safeDesc = job.description.replace(/'/g, "\\'").replace(/"/g, '&quot;').replace(/\n/g, '\\n');
            const skillsJson = JSON.stringify(job.skills.map(s => s.id));

            return `
                <tr class="hover:bg-gray-50/80 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-semibold text-gray-900">${job.title}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            ${imageHtml}
                            <div class="text-sm text-gray-600">${job.company}</div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex flex-wrap gap-1.5">
                            ${skillsHtml}
                            ${extraSkills}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-xs text-gray-500">${job.date}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex justify-end gap-2">
                            <button onclick='openEditModal(${job.id}, "${safeTitle}", "${safeCompany}", "${safeDesc}", ${skillsJson})'
                                class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Modifier">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </button>
                            <button onclick="deleteJob(${job.id})" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Supprimer">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        }).join('');
    }

    function openCreateModal() {
        const modal = document.getElementById('jobModal');
        const form = document.getElementById('jobForm');
        const modalTitle = document.getElementById('modalTitle');
        const method = document.getElementById('method');
        
        console.log('openCreateModal called', { modal, form, modalTitle });
        
        currentJobId = null;
        if (modalTitle) modalTitle.textContent = 'Ajouter une offre';
        if (method) method.value = 'POST';
        if (form) form.reset();
        document.querySelectorAll('input[name="skills[]"]').forEach(cb => cb.checked = false);
        if (modal) {
            console.log('Adding active class to modal');
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        } else {
            console.log('Modal not found!');
        }
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

        if (jobModal) {
            jobModal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
    }
    window.openEditModal = openEditModal;

    function closeModal() {
        const modal = document.getElementById('jobModal');
        if (modal) {
            modal.classList.remove('active');
            document.body.style.overflow = '';
        }
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

    // Modal click handler - delegate
    document.addEventListener('click', (e) => {
        const modal = document.getElementById('jobModal');
        if (e.target === modal) {
            closeModal();
        }
    });

    const form = document.getElementById('jobForm');
    if (form) {
        form.addEventListener('submit', handleFormSubmit);
    }

    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') {
            closeModal();
            closeJobModal();
        }
    });

});
