@extends('layouts.admin')
@section('title', 'Check Availability - ')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Check Availability</h1>
        <p class="text-gray-600">View and manage booking schedules</p>
    </div>
    <a href="{{ route('admin.bookings.create') }}" class="px-4 py-2 bg-[#0EA5E9] text-white rounded-lg hover:bg-sky-600 transition-colors shadow-sm font-medium flex items-center">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        New Booking
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
    <!-- Legend -->
    <div class="flex flex-wrap gap-4 mb-6 text-sm">
        <div class="flex items-center"><span class="w-3 h-3 rounded-full bg-blue-500 mr-2 shadow-sm"></span> <span class="text-gray-700 font-medium">Confirmed</span></div>
        <div class="flex items-center"><span class="w-3 h-3 rounded-full bg-green-500 mr-2 shadow-sm"></span> <span class="text-gray-700 font-medium">In-Use</span></div>
        <div class="flex items-center"><span class="w-3 h-3 rounded-full bg-amber-500 mr-2 shadow-sm"></span> <span class="text-gray-700 font-medium">Pending</span></div>
    </div>

    <!-- Calendar Container -->
    <div id="calendar" class="w-full"></div>
</div>

<!-- Event Details Modal -->
<div id="eventModal" class="fixed inset-0 z-50 hidden flex items-center justify-center transition-opacity" style="background-color: rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px);">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-md mx-4 overflow-hidden pt-4 pb-6 px-6 relative transform transition-all">
        <div class="flex justify-between items-start mb-1">
            <h3 id="modalTitle" class="text-xl font-bold text-gray-800"></h3>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-700 transition-colors bg-gray-100 hover:bg-gray-200 rounded-full p-1 flex-shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <p class="text-sm font-medium text-[#0EA5E9] mb-4 flex items-center" id="modalCustomer">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
            <span></span>
        </p>

        <div class="space-y-4">
            <div>
                <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Current Status</span>
                <div>
                    <span id="modalStatus" class="font-semibold mt-1 inline-block px-3 py-1 rounded-md text-xs text-white shadow-sm"></span>
                </div>
            </div>
            
            <div class="grid grid-cols-2 gap-4 bg-gray-50 p-3 rounded-lg border border-gray-100">
                <div>
                    <span class="text-xs font-bold text-gray-500 uppercase flex items-center">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Start Time
                    </span>
                    <p id="modalStart" class="font-medium mt-1 text-sm text-gray-800"></p>
                </div>
                <div>
                    <span class="text-xs font-bold text-gray-500 uppercase flex items-center">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        End Time
                    </span>
                    <p id="modalEnd" class="font-medium mt-1 text-sm text-gray-800"></p>
                </div>
            </div>
            
            <div>
                <span class="text-xs font-bold text-gray-500 uppercase flex items-center mb-2">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                    Reserved Items
                </span>
                <ul id="modalItems" class="text-sm text-gray-700 bg-gray-50 p-3 rounded-lg border border-gray-100 space-y-2 max-h-32 overflow-y-auto custom-scrollbar">
                </ul>
            </div>
        </div>
        
        <div class="mt-6">
            <a id="modalLink" href="#" class="block w-full text-center px-4 py-2.5 bg-gray-100 text-gray-800 font-semibold rounded-lg hover:bg-gray-200 transition-colors shadow-sm">
                View Booking Details
            </a>
        </div>
    </div>
</div>

<!-- FullCalendar Integration -->
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>

<style>
    /* FullCalendar Customizations styling */
    .fc-theme-standard td, .fc-theme-standard th { border-color: #f1f5f9; }
    .fc-theme-standard .fc-scrollgrid { border-color: #e2e8f0; border-radius: 0.5rem; overflow: hidden; }
    
    .fc .fc-toolbar-title { font-size: 1.25rem; font-weight: 700; color: #1e293b; }
    
    .fc .fc-button-primary { 
        background-color: white; 
        color: #475569; 
        border: 1px solid #cbd5e1; 
        text-transform: capitalize;
        font-weight: 500;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        transition: all 0.2s;
    }
    .fc .fc-button-primary:not(:disabled):active, 
    .fc .fc-button-primary:not(:disabled).fc-button-active { 
        background-color: #f1f5f9; 
        color: #0ea5e9; 
        border-color: #cbd5e1; 
    }
    .fc .fc-button-primary:hover { 
        background-color: #f8fafc; 
        color: #0ea5e9; 
    }
    
    .fc-direction-ltr .fc-button-group > .fc-button:focus { box-shadow: none; z-index: 0; }
    
    /* Today cell highlight */
    .fc-day-today { background-color: #f0f9ff !important; }
    
    /* Header styling */
    .fc-col-header-cell { 
        background-color: #f8fafc; 
        padding: 10px 0 !important; 
        text-transform: uppercase;
        font-size: 0.75rem;
        font-weight: 600;
        letter-spacing: 0.05em;
        color: #64748b;
    }
    
    /* Event styling */
    .fc-event { 
        cursor: pointer; 
        border: none; 
        border-radius: 4px;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        transition: transform 0.1s, box-shadow 0.1s;
    }
    .fc-event:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }
    .fc-daygrid-event {
        margin: 2px 4px !important;
        padding: 3px 6px;
    }
    .fc-timegrid-event {
        padding: 4px; border-radius: 6px;
    }
    .fc-event-title, .fc-event-time { 
        font-weight: 600; 
        font-size: 0.8rem;
    }
    
    /* Custom Scrollbar for items list */
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 2px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            height: 700,
            slotMinTime: '06:00:00',
            slotMaxTime: '23:00:00',
            expandRows: true,
            dayMaxEvents: true, // Allow "more" link when too many events
            events: '/admin/api/availability/events',
            eventTimeFormat: {
                hour: 'numeric',
                minute: '2-digit',
                meridiem: 'short'
            },
            // Enhance the visual for events
            eventDidMount: function(info) {
                // Determine icon based on status
                const props = info.event.extendedProps;
                let opacity = '1';
                
                // Add a little dot for daygrid if needed or customize HTML here
            },
            eventClick: function(info) {
                const props = info.event.extendedProps;
                
                // Set text fields
                document.getElementById('modalTitle').textContent = info.event.title.split(' (')[0]; 
                document.getElementById('modalCustomer').querySelector('span').textContent = props.customerName;
                
                // Format Status indicator
                const statusEl = document.getElementById('modalStatus');
                statusEl.textContent = props.status;
                // reset classes
                statusEl.className = 'font-semibold mt-1 inline-block px-3 py-1 rounded-md text-xs text-white shadow-sm';
                
                if (props.status === 'Confirmed') statusEl.classList.add('bg-blue-500');
                else if (props.status === 'In-Use') statusEl.classList.add('bg-green-500');
                else statusEl.classList.add('bg-amber-500');
                
                // Format details
                const formatOpts = { month: 'short', day: 'numeric', hour: 'numeric', minute: '2-digit' };
                document.getElementById('modalStart').textContent = info.event.start.toLocaleString('en-US', formatOpts);
                document.getElementById('modalEnd').textContent = info.event.end ? info.event.end.toLocaleString('en-US', formatOpts) : 'TBD';
                
                // Populate Items Map
                const itemsListEl = document.getElementById('modalItems');
                itemsListEl.innerHTML = '';
                if(props.items && props.items.length) {
                    props.items.forEach(item => {
                        const li = document.createElement('li');
                        li.className = 'flex items-start text-gray-700';
                        // Add small dot icon
                        li.innerHTML = `<svg class="w-4 h-4 text-[#0EA5E9] mt-0.5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg> <span>${item}</span>`;
                        itemsListEl.appendChild(li);
                    });
                } else {
                    itemsListEl.innerHTML = '<li class="text-gray-400 italic font-medium px-2 py-1">No items listed</li>';
                }
                
                // Setup Navigation Link
                document.getElementById('modalLink').href = `/admin/bookings/${info.event.id}`;
                
                // Open Modal with a quick fade-in
                const modal = document.getElementById('eventModal');
                modal.classList.remove('hidden');
            },
            dateClick: function(info) {
                // Future enhancement: Open a dropdown directly on the day or redirect to create booking
                // Since this might annoy people when clicking dead space, we make it an explicit decision if needed.
                // Redirect user to create page with ?date parameter (can implement autofill via JS on create page later)
                // window.location.href = `/admin/bookings/create?date=${info.dateStr.split('T')[0]}`;
            }
        });
        calendar.render();
    });
    
    function closeModal() {
        document.getElementById('eventModal').classList.add('hidden');
    }
    
    // Close modal on outside click
    window.addEventListener('click', function(e) {
        const modal = document.getElementById('eventModal');
        // Because of the nested structure, check specifically against the overlay background
        if (e.target === modal) {
            closeModal();
        }
    });
    
    // Support Escape key to close modal
    window.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeModal();
        }
    });
</script>
@endsection
