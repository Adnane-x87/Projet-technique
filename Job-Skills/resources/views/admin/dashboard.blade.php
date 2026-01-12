@extends('layouts.app')

@section('content')
    <div style="max-width: 1200px; margin: 0 auto; padding: 20px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <h1 style="font-size: 1.75rem;">Tableau de bord</h1>
            <button onclick="openCreateModal()" class="btn btn-primary">+ Ajouter une offre</button>
        </div>

        <!-- Stats -->
        <div
            style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 15px; margin-bottom: 30px;">
            <div class="card" style="text-align: center;">
                <p style="color: #666; font-size: 12px; text-transform: uppercase;">Total Offres</p>
                <p style="font-size: 2rem; font-weight: bold;">{{ $emplois->count() }}</p>
            </div>
            <div class="card" style="text-align: center;">
                <p style="color: #666; font-size: 12px; text-transform: uppercase;">Compétences</p>
                <p style="font-size: 2rem; font-weight: bold;">{{ $skills->count() }}</p>
            </div>
        </div>

        <!-- Search & Filter (AJAX) -->
        <div class="card" style="margin-bottom: 30px;">
            <div style="display: flex; gap: 15px; align-items: center; width: 100%;">
                <input type="text" id="search-input" placeholder="Rechercher par titre, entreprise..."
                    style="padding: 12px 16px; border: 1px solid #ddd; border-radius: 6px; flex: 1; min-width: 545px; font-size: 16px;">

                <select id="skill-filter"
                    style="padding: 12px 16px; border: 1px solid #ddd; border-radius: 6px; min-width: 200px; font-size: 16px;">
                    <option value="">Toutes les compétences</option>
                    @foreach ($skills as $skill)
                        <option value="{{ $skill->id }}">
                            {{ $skill->name }}
                        </option>
                    @endforeach
                </select>

                <button type="button" onclick="resetFilters()" class="btn"
                    style="padding: 12px 20px; font-size: 16px;">Réinitialiser</button>
            </div>
        </div>

        <!-- Job List -->
        <div class="card">
            <h2 style="font-size: 1.25rem; margin-bottom: 15px;">Offres d'emploi</h2>

            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 2px solid #ddd; text-align: left;">
                        <th style="padding: 10px 0;">Titre</th>
                        <th style="padding: 10px 0;">Entreprise</th>
                        <th style="padding: 10px 0;">Compétences</th>
                        <th style="padding: 10px 0;">Date</th>
                        <th style="padding: 10px 0; text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody id="jobs-table-body">
                    @forelse($emplois as $emploi)
                        <tr style="border-bottom: 1px solid #eee;">
                            <td style="padding: 12px 0;">{{ $emploi->title }}</td>
                            <td style="padding: 12px 0;">
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    @if ($emploi->image)
                                        <img src="{{ asset('storage/' . $emploi->image) }}"
                                            style="width: 30px; height: 30px; object-fit: cover; border-radius: 4px;">
                                    @endif
                                    {{ $emploi->company }}
                                </div>
                            </td>
                            <td style="padding: 12px 0;">
                                @foreach ($emploi->skills->take(2) as $skill)
                                    <span
                                        style="background: #e0e0e0; padding: 2px 6px; border-radius: 3px; font-size: 11px; margin-right: 3px;">{{ $skill->name }}</span>
                                @endforeach
                                @if ($emploi->skills->count() > 2)
                                    <span
                                        style="color: #999; font-size: 11px;">+{{ $emploi->skills->count() - 2 }}</span>
                                @endif
                            </td>
                            <td style="padding: 12px 0; color: #666; font-size: 13px;">
                                {{ $emploi->created_at->format('d/m/Y') }}</td>
                            <td style="padding: 12px 0; text-align: right;">
                                <button
                                    onclick='openEditModal({{ $emploi->id }}, {{ json_encode($emploi->title) }}, {{ json_encode($emploi->company) }}, {{ json_encode($emploi->description) }}, {{ json_encode($emploi->skills->pluck('id')) }})'
                                    class="btn" style="padding: 4px 10px; font-size: 12px;">Modifier</button>

                                <form action="{{ route('emplois.destroy', $emploi) }}" method="POST"
                                    style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger"
                                        style="padding: 4px 10px; font-size: 12px;"
                                        onclick="return confirm('Supprimer cette offre ?')">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="padding: 30px; text-align: center; color: #666;">Aucune offre
                                trouvée.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal -->
    <div id="jobModal"
        style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
        <div class="card"
            style="width: 100%; max-width: 600px; max-height: 90vh; overflow-y: auto; position: relative;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h2 id="modalTitle" style="font-size: 1.5rem; margin: 0;">Ajouter une offre</h2>
                <button onclick="closeModal()"
                    style="background: none; border: none; font-size: 1.5rem; cursor: pointer;">&times;</button>
            </div>

            <form id="jobForm" onsubmit="handleFormSubmit(event)">
                @csrf
                <input type="hidden" id="method" name="_method" value="POST">

                <div style="margin-bottom: 15px;">
                    <label for="title" style="display: block; margin-bottom: 5px; font-weight: 500;">Titre du
                        poste</label>
                    <input type="text" id="title" name="title" required>
                </div>

                <div style="margin-bottom: 15px;">
                    <label for="company"
                        style="display: block; margin-bottom: 5px; font-weight: 500;">Entreprise</label>
                    <input type="text" id="company" name="company" required>
                </div>

                <div style="margin-bottom: 15px;">
                    <label for="description"
                        style="display: block; margin-bottom: 5px; font-weight: 500;">Description</label>
                    <textarea id="description" name="description" rows="4" required></textarea>
                </div>

                <div style="margin-bottom: 15px;">
                    <label for="image" style="display: block; margin-bottom: 5px; font-weight: 500;">Image
                        (optionnel)</label>
                    <input type="file" id="image" name="image" accept="image/*" style="padding: 5px;">
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 10px; font-weight: 500;">Compétences</label>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px;">
                        @foreach ($skills as $skill)
                            <label style="display: flex; align-items: center; gap: 5px; cursor: pointer;">
                                <input type="checkbox" name="skills[]" value="{{ $skill->id }}"
                                    id="skill_{{ $skill->id }}" style="width: auto;">
                                {{ $skill->name }}
                            </label>
                        @endforeach
                    </div>
                </div>

                <div style="display: flex; gap: 10px; justify-content: flex-end;">
                    <button type="button" onclick="closeModal()" class="btn">Annuler</button>
                    <button type="submit" class="btn btn-primary" id="submitBtn">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let currentJobId = null;
        let debounceTimer;

        const searchInput = document.getElementById('search-input');
        const skillFilter = document.getElementById('skill-filter');
        const jobsTableBody = document.getElementById('jobs-table-body');
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Search Logic
        function resetFilters() {
            searchInput.value = '';
            skillFilter.value = '';
            fetchJobs();
        }

        function fetchJobs() {
            const search = searchInput.value;
            const skill = skillFilter.value;

            const params = new URLSearchParams();
            if (search) params.append('search', search);
            if (skill) params.append('skill', skill);

            fetch('/api/emplois?' + params.toString())
                .then(res => res.json())
                .then(data => {
                    renderJobs(data.emplois);
                })
                .catch(err => console.error(err));
        }

        searchInput.addEventListener('input', () => {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(fetchJobs, 300);
        });

        skillFilter.addEventListener('change', fetchJobs);

        function renderJobs(emplois) {
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

                // Escape strings using a simpler approach safer for template literals
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

        // Action Logic
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
                    // Refresh the list while keeping filters
                    fetchJobs();
                } else {
                    alert('Erreur lors de la suppression');
                }
            } catch (e) {
                console.error(e);
                alert('Erreur de connexion');
            }
        }

        // Modal Logic
        function openCreateModal() {
            currentJobId = null;
            document.getElementById('modalTitle').textContent = 'Ajouter une offre';
            document.getElementById('method').value = 'POST';
            document.getElementById('jobForm').reset();
            document.querySelectorAll('input[name="skills[]"]').forEach(cb => cb.checked = false);
            document.getElementById('jobModal').style.display = 'flex';
        }

        function openEditModal(id, title, company, description, skillIds) {
            currentJobId = id;
            document.getElementById('modalTitle').textContent = 'Modifier l\'offre';
            document.getElementById('method').value = 'PUT';

            document.getElementById('title').value = title;
            document.getElementById('company').value = company;
            document.getElementById('description').value = description;

            document.querySelectorAll('input[name="skills[]"]').forEach(cb => {
                cb.checked = skillIds.includes(parseInt(cb.value));
            });

            document.getElementById('jobModal').style.display = 'flex';
        }

        function closeModal() {
            document.getElementById('jobModal').style.display = 'none';
        }

        async function handleFormSubmit(e) {
            e.preventDefault();

            const submitBtn = document.getElementById('submitBtn');
            const originalBtnText = submitBtn.textContent;
            submitBtn.textContent = 'Enregistrement...';
            submitBtn.disabled = true;

            const formData = new FormData(e.target);

            const url = currentJobId ?
                `/emplois/${currentJobId}` :
                '{{ route('emplois.store') }}';

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
                    fetchJobs(); // Refresh via Ajax
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

        document.getElementById('jobModal').addEventListener('click', (e) => {
            if (e.target === document.getElementById('jobModal')) {
                closeModal();
            }
        });
    </script>
@endsection
