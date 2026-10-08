@extends('layouts.header')

@section('pageTitle')
    {{ config('app.name') }} | Manage Contacts
@endsection

@section('section')
    @can('Manage Contacts')
        <link rel="stylesheet" href="{{ asset('assets/css/report.css') }}">
        @push('css')
            <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/contacts-index.css') }}">
        @endpush

        <div class="container-fluid p-0">
            <div class="row align-items-center mb-4 g-3">
                <div class="col-12 col-md-6">
                    <h1 class="h3 mb-1"><strong>Contacts</strong> Management</h1>
                    <p class="text-muted small mb-0" id="totalContactsDisplay">
                        <i class="fas fa-users me-1"></i> Total contacts: <span id="totalContactsCount"
                            class="fw-bold text-primary">0</span>
                    </p>
                </div>
                <div class="col-12 col-md-6 text-md-end">
                    <div class="d-flex flex-wrap gap-2 justify-content-md-end">
                        <div class="search-container flex-grow-1 flex-md-grow-0">
                            <i class="fas fa-search"></i>
                            <input type="text" id="searchInput" class="form-control shadow-none"
                                placeholder="Search by name, phone...">
                        </div>
                        @can('Contact Export')
                            <button class="btn btn-outline-primary d-flex align-items-center px-3"
                                onclick="exportContacts()">
                                <i class="fas fa-file-export me-2"></i> Export
                            </button>
                        @endcan
                        @can('Contact Create')
                            <button class="btn btn-primary d-flex align-items-center px-3 shadow-sm"
                                onclick="openAddContact()">
                                <i class="fas fa-plus-circle me-2"></i> Add Contact
                            </button>
                        @endcan
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm overflow-hidden">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-4" style="width: 40px;">
                                        <div class="form-check">
                                            <input class="form-check-input shadow-none" type="checkbox"
                                                id="selectAllContacts">
                                        </div>
                                    </th>
                                    <th>Contact Detail</th>
                                    <th>Phone Number</th>
                                    <th>Email Address</th>
                                    <th class="text-end pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="contactTable">
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <div class="spinner-border spinner-border-sm text-primary me-2"></div>
                                        Loading contacts...
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- Pagination -->
                    <div class="d-flex flex-wrap justify-content-between align-items-center p-4 border-top">
                        <div class="text-muted small mb-2 mb-md-0" id="paginationInfo">
                            Showing <span class="fw-bold text-dark">0</span> to <span class="fw-bold text-dark">0</span> of
                            <span class="fw-bold text-dark">0</span> contacts
                        </div>
                        <nav aria-label="Page navigation" id="paginationNav">
                            <ul class="pagination pagination-sm mb-0 gap-1" id="paginationList">
                                <!-- Pagination items will be injected here -->
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    @endcan

    <!-- Modal -->
    <div class="modal fade" id="contactModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form id="contactForm" class="modal-content border-0 shadow-lg">
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold fs-4" id="modalTitle">Add Contact</h5>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body py-4">
                    <input type="hidden" id="mode" value="add">
                    <input type="hidden" id="contact_id">

                    <div class="mb-3">
                        <label for="name" class="form-label small fw-bold text-muted text-uppercase">Full Name</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i
                                    class="fas fa-user text-muted"></i></span>
                            <input type="text" id="name" class="form-control bg-light border-start-0 shadow-none"
                                placeholder="Enter name">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label small fw-bold text-muted text-uppercase">Phone
                            Number</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i
                                    class="fas fa-phone text-muted"></i></span>
                            <input type="text" id="phone" class="form-control bg-light border-start-0 shadow-none"
                                placeholder="e.g. 9876543210">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label small fw-bold text-muted text-uppercase">Email
                            Address</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i
                                    class="fas fa-envelope text-muted"></i></span>
                            <input type="email" id="email" class="form-control bg-light border-start-0 shadow-none"
                                placeholder="example@mail.com">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4 shadow-sm">Save Contact</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>
        let contacts = [];
        let filteredContacts = [];
        let selectedContactIds = [];
        let currentPage = 1;
        let rowsPerPage = 10;

        $(document).ready(function() {
            // Setup CSRF token for all AJAX requests
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            loadContacts();

            // Search functionality
            $('#searchInput').on('input', function() {
                const term = $(this).val().trim();
                filteredContacts = contacts;

                if (term.length >= 3) {
                    filteredContacts = contacts.filter(c =>
                        c.name.toLowerCase().includes(term.toLowerCase()) ||
                        c.phone_number.includes(term) ||
                        (c.email_id ?? '').toLowerCase().includes(term.toLowerCase())
                    );
                }

                currentPage = 1; // Reset to first page on search
                $('#totalContactsCount').text(filteredContacts.length);
                renderContacts();
            });

            // Select All logic
            $('#selectAllContacts').on('change', function() {
                const isChecked = $(this).is(':checked');
                const start = (currentPage - 1) * rowsPerPage;
                const end = Math.min(start + rowsPerPage, filteredContacts.length);
                const currentPageData = filteredContacts.slice(start, end);

                currentPageData.forEach(c => {
                    const index = selectedContactIds.indexOf(c.id);
                    if (isChecked && index === -1) {
                        selectedContactIds.push(c.id);
                    } else if (!isChecked && index !== -1) {
                        selectedContactIds.splice(index, 1);
                    }
                });
                renderContacts();
            });

            // Individual checkbox logic
            $(document).on('change', '.contact-checkbox', function() {
                const id = $(this).val();
                const index = selectedContactIds.indexOf(id);
                if ($(this).is(':checked')) {
                    if (index === -1) selectedContactIds.push(id);
                } else {
                    if (index !== -1) selectedContactIds.splice(index, 1);
                    $('#selectAllContacts').prop('checked', false);
                }
            });
        });

        /* Load all contacts from API */
        function loadContacts() {
            $.get("{{ url('/api/contacts') }}", function(res) {
                if (res && res.status === 'error') {
                    console.error("API Error:", res.message, res.error);
                    Swal.fire('Error', res.message || 'Failed to load contacts', 'error');
                    contacts = [];
                } else if (res && res.Message) {
                    // Handle MyOperator direct API error messages (like auth failures)
                    console.error("MyOperator API Error:", res.Message);
                    Swal.fire('API Authorization Error', res.Message, 'error');
                    contacts = [];
                } else if (res && res.data && res.data.results) {
                    contacts = res.data.results;
                } else if (res && res.data) {
                    // If results array is missing but data is an array
                    contacts = Array.isArray(res.data) ? res.data : [res.data];
                } else if (res && res.results) {
                    contacts = res.results;
                } else {
                    contacts = [];
                }
                
                filteredContacts = contacts;
                $('#totalContactsCount').text(contacts.length);
                renderContacts();
            }).fail(function() {
                contacts = [];
                filteredContacts = [];
                $('#totalContactsCount').text(0);
                renderContacts();
                Swal.fire('Error', 'Failed to fetch contacts from server.', 'error');
            });
        }

        /* Render contacts in table with pagination */
        function renderContacts() {
            let html = '';

            // Pagination Calculations
            const totalItems = filteredContacts.length;
            const totalPages = Math.ceil(totalItems / rowsPerPage);
            const start = (currentPage - 1) * rowsPerPage;
            const end = Math.min(start + rowsPerPage, totalItems);
            const pageData = filteredContacts.slice(start, end);

            // Sync Header Checkbox (Select All)
            const allSelectedOnPage = pageData.length > 0 && pageData.every(c => selectedContactIds.includes(c.id
            .toString()));
            $('#selectAllContacts').prop('checked', allSelectedOnPage);

            // Update Pagination Info
            $('#paginationInfo').html(
                `Showing <span class="fw-bold text-dark">${totalItems > 0 ? start + 1 : 0}</span> to <span class="fw-bold text-dark">${end}</span> of <span class="fw-bold text-dark">${totalItems}</span> contacts`
                );

            if (!pageData.length) {
                html = `<tr><td colspan="5" class="text-center py-5">
                            <i class="fas fa-folder-open fa-2x text-muted mb-2"></i><br>
                            <span class="text-muted">No contacts found</span>
                        </td></tr>`;
                $('#paginationNav').hide();
            } else {
                $('#paginationNav').show();
                pageData.forEach(c => {
                    const initials = c.name ? c.name.split(' ').map(n => n[0]).join('').substring(0, 2)
                    .toUpperCase() : '?';
                    const isChecked = selectedContactIds.includes(c.id.toString()) ? 'checked' : '';
                    html += `
                    <tr>
                        <td class="ps-4">
                            <div class="form-check">
                                <input class="form-check-input shadow-none contact-checkbox" type="checkbox" value="${c.id}" ${isChecked}>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="contact-avatar me-3">${initials}</div>
                                <div>
                                    <div class="fw-bold text-dark">${c.name ?? 'Unknown'}</div>
                                    <div class="small text-muted">ID: #${c.id.substring(0, 8)}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="small fw-medium"><i class="fas fa-phone-alt me-2 text-muted"></i>${c.phone_number}</div>
                        </td>
                        <td>
                            <div class="small"><i class="far fa-envelope me-2 text-muted"></i>${c.email_id ?? '--'}</div>
                        </td>
                        <td class="text-end pe-4">
                            <div class="d-flex justify-content-end gap-2">
                                @if (auth()->user()->hasPermissionTo('Contact Edit'))
                                <button class="btn btn-sm btn-light-info shadow-none border-0" onclick="openEditContact('${c.id}')" title="Edit Contact">
                                    <i class="fas fa-edit"></i>
                                </button>
                                @endif
                                @if (auth()->user()->hasPermissionTo('Contact Delete'))
                                <button class="btn btn-sm btn-light-danger shadow-none border-0" onclick="deleteContact('${c.id}')" title="Delete Contact">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    `;
                });

                // Generate Pagination Links
                let paginationHtml = '';

                // Prev Button
                paginationHtml += `
                    <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
                        <a class="page-link shadow-none" href="javascript:void(0)" onclick="changePage(${currentPage - 1})">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    </li>
                `;

                // Page Numbers
                for (let i = 1; i <= totalPages; i++) {
                    if (totalPages > 7) {
                        // Logic to show limited pages with dots if needed (simplified for now)
                        if (i === 1 || i === totalPages || (i >= currentPage - 1 && i <= currentPage + 1)) {
                            paginationHtml += `
                                <li class="page-item ${currentPage === i ? 'active' : ''}">
                                    <a class="page-link shadow-none" href="javascript:void(0)" onclick="changePage(${i})">${i}</a>
                                </li>
                            `;
                        } else if (i === currentPage - 2 || i === currentPage + 2) {
                            paginationHtml +=
                                `<li class="page-item disabled"><span class="page-link border-0">...</span></li>`;
                        }
                    } else {
                        paginationHtml += `
                            <li class="page-item ${currentPage === i ? 'active' : ''}">
                                <a class="page-link shadow-none" href="javascript:void(0)" onclick="changePage(${i})">${i}</a>
                            </li>
                        `;
                    }
                }

                // Next Button
                paginationHtml += `
                    <li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
                        <a class="page-link shadow-none" href="javascript:void(0)" onclick="changePage(${currentPage + 1})">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </li>
                `;

                $('#paginationList').html(paginationHtml);
            }
            $('#contactTable').html(html);
        }

        function changePage(page) {
            currentPage = page;
            renderContacts();
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }

        function exportContacts() {
            let dataToExport = [];

            // Priority 1: Export selected records
            if (selectedContactIds.length > 0) {
                dataToExport = contacts.filter(c => selectedContactIds.includes(c.id.toString()));
            }
            // Priority 2: Export filtered/searched records
            else {
                const term = $('#searchInput').val().trim();
                dataToExport = filteredContacts;
            }

            if (dataToExport.length === 0) {
                Swal.fire({
                    icon: 'info',
                    title: 'No Data',
                    text: 'There are no contacts to export.',
                    timer: 2000,
                    showConfirmButton: false
                });
                return;
            }

            // Create CSV content
            let csvRows = ["Name,Phone Number,Email Address"];
            dataToExport.forEach(c => {
                csvRows.push([
                    `"${(c.name || '').replace(/"/g, '""')}"`,
                    `"${(c.phone_number || '').replace(/"/g, '""')}"`,
                    `"${(c.email_id || '').replace(/"/g, '""')}"`
                ].join(","));
            });

            const csvString = csvRows.join("\n");
            const blob = new Blob([csvString], {
                type: 'text/csv;charset=utf-8;'
            });
            const url = URL.createObjectURL(blob);

            const link = document.createElement("a");
            link.setAttribute("href", url);
            link.setAttribute("download", `contacts_export_${new Date().toISOString().slice(0, 10)}.csv`);
            link.style.visibility = 'hidden';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);

            Swal.fire({
                icon: 'success',
                title: 'Export Successful',
                text: `${dataToExport.length} ${selectedContactIds.length > 0 ? 'selected' : ''} contacts have been exported.`,
                timer: 2000,
                showConfirmButton: false
            });
        }

        /* Open modal to add contact */
        function openAddContact() {
            $('#modalTitle').text('Add Contact');
            $('#mode').val('add');
            $('#contact_id').val('');
            $('#name').val('');
            $('#phone').val('').prop('disabled', false);
            $('#email').val('');
            $('#contactModal').modal('show');
        }

        /* Open modal to edit contact */
        function openEditContact(id) {
            let c = contacts.find(x => x.id == id);
            if (!c) return;

            $('#modalTitle').text('Edit Contact');
            $('#mode').val('edit');
            $('#contact_id').val(c.id);
            $('#name').val(c.name);
            $('#phone').val(c.phone_number).prop('disabled', true);
            $('#email').val(c.email_id);
            $('#contactModal').modal('show');
        }

        /* Save contact (Add or Edit) */
        $('#contactForm').submit(function(e) {
            e.preventDefault();
            let mode = $('#mode').val();
            let id = $('#contact_id').val();
            let url = mode === 'edit' ? "{{ url('/api/contacts') }}/" + id : "{{ url('/api/contacts/create') }}";
            let method = mode === 'edit' ? 'PATCH' : 'POST';

            let data = {
                name: $('#name').val(),
                email_id: $('#email').val(),
                marketing_opt_in: true
            };
            
            if (mode !== 'edit') {
                data.phone_number = $('#phone').val();
                data.country_code = '91';
            }

            $.ajax({
                url: url,
                method: method,
                contentType: 'application/json',
                data: JSON.stringify(data),
                success: function(res) {
                    if (res && (res.status === 'error' || res.Message)) {
                        Swal.fire('Error', res.message || res.Message || 'Failed to save contact', 'error');
                        return;
                    }
                    $('#contactModal').modal('hide');
                    loadContacts();
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: mode === 'edit' ? 'Contact updated successfully!' :
                            'Contact added successfully!',
                        timer: 2000,
                        showConfirmButton: false
                    });
                },
                error: function(xhr) {
                    let errorMessage = 'Failed to save contact.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    } else if (xhr.responseText) {
                        errorMessage += ' ' + xhr.responseText;
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: errorMessage
                    });
                }
            });
        });

        /* Delete contact */
        function deleteContact(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ url('/api/contacts') }}/" + id,
                        method: 'DELETE',
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(res) {
                            if (res && (res.status === 'error' || res.Message)) {
                                Swal.fire('Error', res.message || res.Message || 'Failed to delete contact', 'error');
                                return;
                            }
                            loadContacts();
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: 'Contact has been deleted.',
                                timer: 2000,
                                showConfirmButton: false
                            });
                        },
                        error: function(xhr) {
                            let errorMessage = 'Failed to delete contact.';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMessage = xhr.responseJSON.message;
                            }
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: errorMessage
                            });
                        }
                    });
                }
            });
        }
    </script>
@endpush
