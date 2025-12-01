@extends('admin.partials.app')
@section('content-card-title', 'Booking Calendar')
@section('content-body')
<style>
    .calendar-container {
        background: #fff;
        border-radius: 8px;
        padding: 20px;
    }
    .current-datetime {
        font-size: 1.5rem;
        font-weight: bold;
        color: #007bff;
        margin-bottom: 20px;
        text-align: center;
    }
    .view-buttons {
        margin-bottom: 20px;
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }
    .calendar-day {
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 15px;
        background: #f8f9fa;
    }
    .calendar-day-header {
        font-size: 1.2rem;
        font-weight: bold;
        margin-bottom: 10px;
        color: #333;
        border-bottom: 2px solid #007bff;
        padding-bottom: 5px;
    }
    .reservation-block {
        background: #ffffff;
        border-left: 4px solid #007bff;
        padding: 10px;
        margin-bottom: 10px;
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.3s;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .reservation-block:hover {
        transform: translateX(5px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }
    .reservation-block.status-confirmed {
        border-left-color: #28a745;
        background: #f0fff4;
    }
    .reservation-block.status-pending {
        border-left-color: #ffc107;
        background: #fffbf0;
    }
    .reservation-block.status-cancelled {
        border-left-color: #dc3545;
        background: #fff0f0;
    }
    .reservation-time {
        font-weight: bold;
        color: #007bff;
        font-size: 0.95rem;
    }
    .reservation-name {
        font-size: 1.1rem;
        margin: 5px 0;
    }
    .reservation-details {
        font-size: 0.9rem;
        color: #666;
    }
    .no-reservations {
        text-align: center;
        padding: 40px;
        color: #999;
        font-size: 1.1rem;
    }
    .modal-body .info-row {
        margin-bottom: 10px;
        padding: 8px;
        border-bottom: 1px solid #eee;
    }
    .modal-body .info-label {
        font-weight: bold;
        display: inline-block;
        width: 150px;
    }
    .navigation-buttons {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }
</style>

<div class="container-fluid calendar-container">
    <!-- Current Date and Time -->
    <div class="current-datetime" id="currentDateTime"></div>

    <!-- Navigation -->
    <div class="navigation-buttons">
        <div>
            @if($view === 'month')
                <a href="{{ route('admin.booking-reservations.calendar', ['view' => $view, 'date' => $startDate->copy()->subMonth()->format('Y-m-d')]) }}" 
                   class="btn btn-secondary">
                    <i class="fas fa-chevron-left"></i> Previous Month
                </a>
            @elseif($view === 'week')
                <a href="{{ route('admin.booking-reservations.calendar', ['view' => $view, 'date' => $startDate->copy()->subWeek()->format('Y-m-d')]) }}" 
                   class="btn btn-secondary">
                    <i class="fas fa-chevron-left"></i> Previous Week
                </a>
            @else
                <a href="{{ route('admin.booking-reservations.calendar', ['view' => $view, 'date' => $startDate->copy()->subDay()->format('Y-m-d')]) }}" 
                   class="btn btn-secondary">
                    <i class="fas fa-chevron-left"></i> Previous Day
                </a>
            @endif
        </div>
        
        <a href="{{ route('admin.booking-reservations.calendar', ['view' => $view, 'date' => now()->format('Y-m-d')]) }}" 
           class="btn btn-primary">
            <i class="fas fa-calendar-day"></i> Today
        </a>

        <div>
            @if($view === 'month')
                <a href="{{ route('admin.booking-reservations.calendar', ['view' => $view, 'date' => $startDate->copy()->addMonth()->format('Y-m-d')]) }}" 
                   class="btn btn-secondary">
                    Next Month <i class="fas fa-chevron-right"></i>
                </a>
            @elseif($view === 'week')
                <a href="{{ route('admin.booking-reservations.calendar', ['view' => $view, 'date' => $startDate->copy()->addWeek()->format('Y-m-d')]) }}" 
                   class="btn btn-secondary">
                    Next Week <i class="fas fa-chevron-right"></i>
                </a>
            @else
                <a href="{{ route('admin.booking-reservations.calendar', ['view' => $view, 'date' => $startDate->copy()->addDay()->format('Y-m-d')]) }}" 
                   class="btn btn-secondary">
                    Next Day <i class="fas fa-chevron-right"></i>
                </a>
            @endif
        </div>
    </div>

    <!-- View Options -->
    <div class="view-buttons">
        <a href="{{ route('admin.booking-reservations.calendar', ['view' => 'today']) }}" 
           class="btn btn-{{ $view === 'today' ? 'primary' : 'outline-primary' }}">
            <i class="fas fa-sun"></i> Today
        </a>
        <a href="{{ route('admin.booking-reservations.calendar', ['view' => 'today_tomorrow']) }}" 
           class="btn btn-{{ $view === 'today_tomorrow' ? 'primary' : 'outline-primary' }}">
            <i class="fas fa-calendar-plus"></i> Today & Tomorrow
        </a>
        <a href="{{ route('admin.booking-reservations.calendar', ['view' => 'week']) }}" 
           class="btn btn-{{ $view === 'week' ? 'primary' : 'outline-primary' }}">
            <i class="fas fa-calendar-week"></i> This Week
        </a>
        <a href="{{ route('admin.booking-reservations.calendar', ['view' => 'month']) }}" 
           class="btn btn-{{ $view === 'month' ? 'primary' : 'outline-primary' }}">
            <i class="fas fa-calendar-alt"></i> This Month
        </a>
        <a href="{{ route('admin.booking-reservations.index') }}" 
           class="btn btn-outline-secondary">
            <i class="fas fa-list"></i> List View
        </a>
    </div>

    <!-- Calendar Days -->
    <div class="calendar-days">
        @if($reservations->isEmpty())
            <div class="no-reservations">
                <i class="fas fa-calendar-times fa-3x mb-3"></i>
                <p>No reservations found for this period.</p>
            </div>
        @else
            @php
                $currentDate = $startDate->copy();
                $displayedDates = [];
            @endphp

            @while($currentDate <= $endDate)
                @php
                    $dateKey = $currentDate->format('Y-m-d');
                    $dayReservations = $reservations->get($dateKey, collect());
                @endphp

                <div class="calendar-day">
                    <div class="calendar-day-header">
                        <i class="fas fa-calendar-day"></i>
                        {{ $currentDate->format('l, F j, Y') }}
                        <span class="badge bg-secondary">{{ $dayReservations->count() }} Reservations</span>
                    </div>

                    @if($dayReservations->isEmpty())
                        <p class="text-muted" style="padding: 10px;">No reservations for this day.</p>
                    @else
                        @foreach($dayReservations->sortBy(function($res) { return $res->timeSlot->start_time; }) as $reservation)
                            <div class="reservation-block status-{{ $reservation->status }}" 
                                 onclick="showReservationModal({{ $reservation->id }})"
                                 data-id="{{ $reservation->id }}">
                                <div class="reservation-time">
                                    <i class="fas fa-clock"></i>
                                    {{ $reservation->timeSlot->start_time->format('H:i') }} - 
                                    {{ $reservation->timeSlot->end_time->format('H:i') }}
                                </div>
                                <div class="reservation-name">
                                    <i class="fas fa-user"></i> {{ $reservation->name }}
                                </div>
                                <div class="reservation-details">
                                    <span class="badge bg-{{ $reservation->status === 'confirmed' ? 'success' : ($reservation->status === 'pending' ? 'warning' : 'danger') }}">
                                        {{ ucfirst($reservation->status) }}
                                    </span>
                                    @if($reservation->user)
                                        <span class="badge bg-info">Registered</span>
                                    @else
                                        <span class="badge bg-secondary">Guest</span>
                                    @endif
                                    @if($reservation->service)
                                        | Service: {{ $reservation->service }}
                                    @endif
                                    @if($reservation->isExpired())
                                        <span class="badge bg-danger">EXPIRED</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>

                @php
                    $currentDate->addDay();
                @endphp
            @endwhile
        @endif
    </div>
</div>

<!-- Modal for Reservation Details -->
<div class="modal fade" id="reservationModal" tabindex="-1" aria-labelledby="reservationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reservationModalLabel">Reservation Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modalContent">
                <div class="text-center">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <a href="#" id="editReservationLink" class="btn btn-primary" target="_blank">
                    <i class="fas fa-edit"></i> Edit Reservation
                </a>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
// Update current date and time every second
function updateDateTime() {
    const now = new Date();
    const options = { 
        weekday: 'long', 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit'
    };
    document.getElementById('currentDateTime').textContent = now.toLocaleString('en-US', options);
}

// Update immediately and then every second
updateDateTime();
setInterval(updateDateTime, 1000);

// Show reservation modal
function showReservationModal(reservationId) {
    const modal = new bootstrap.Modal(document.getElementById('reservationModal'));
    const modalContent = document.getElementById('modalContent');
    const editLink = document.getElementById('editReservationLink');
    
    // Set edit link
    editLink.href = '/admin/booking-reservations/' + reservationId + '/edit';
    
    // Show loading
    modalContent.innerHTML = '<div class="text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>';
    
    // Fetch reservation details
    fetch('/admin/booking-reservations/' + reservationId + '/details')
        .then(response => response.json())
        .then(data => {
            let html = '<div class="reservation-details-content">';
            
            // Status
            let statusBadge = data.status === 'confirmed' ? 'success' : (data.status === 'pending' ? 'warning' : 'danger');
            html += '<div class="info-row">';
            html += '<span class="info-label">Status:</span>';
            html += '<span class="badge bg-' + statusBadge + '">' + data.status.toUpperCase() + '</span>';
            html += '</div>';
            
            // Personal Info
            html += '<div class="info-row"><span class="info-label">Name:</span>' + data.name + '</div>';
            html += '<div class="info-row"><span class="info-label">Email:</span>' + data.email + '</div>';
            
            if (data.mobile_number) {
                html += '<div class="info-row"><span class="info-label">Mobile:</span>' + data.mobile_number + '</div>';
            }
            
            if (data.age) {
                html += '<div class="info-row"><span class="info-label">Age:</span>' + data.age + '</div>';
            }
            
            // User info
            if (data.user) {
                html += '<div class="info-row"><span class="info-label">Registered User:</span>' + data.user.name + ' (' + data.user.email + ')</div>';
            } else {
                html += '<div class="info-row"><span class="info-label">Booking Type:</span><span class="badge bg-secondary">Guest Booking</span></div>';
            }
            
            // Time Slot
            if (data.time_slot) {
                html += '<div class="info-row"><span class="info-label">Time Slot:</span>' + 
                        new Date(data.time_slot.start_time).toLocaleString() + ' - ' + 
                        new Date(data.time_slot.end_time).toLocaleTimeString() + '</div>';
                html += '<div class="info-row"><span class="info-label">Capacity:</span>' + 
                        data.time_slot.max_capacity + '</div>';
            }
            
            // Service
            if (data.service) {
                html += '<div class="info-row"><span class="info-label">Service:</span>' + data.service + '</div>';
            }
            
            // Expires At
            if (data.expires_at) {
                html += '<div class="info-row"><span class="info-label">Expires At:</span>' + 
                        new Date(data.expires_at).toLocaleString() + '</div>';
            }
            
            // Comments
            if (data.comments) {
                html += '<div class="info-row"><span class="info-label">Comments:</span><br><div class="mt-2 p-2 border rounded">' + 
                        data.comments + '</div></div>';
            }
            
            // Timestamps
            html += '<div class="info-row"><span class="info-label">Created:</span>' + 
                    new Date(data.created_at).toLocaleString() + '</div>';
            html += '<div class="info-row"><span class="info-label">Updated:</span>' + 
                    new Date(data.updated_at).toLocaleString() + '</div>';
            
            html += '</div>';
            
            modalContent.innerHTML = html;
        })
        .catch(error => {
            modalContent.innerHTML = '<div class="alert alert-danger">Error loading reservation details.</div>';
            console.error('Error:', error);
        });
    
    modal.show();
}
</script>
@endsection
