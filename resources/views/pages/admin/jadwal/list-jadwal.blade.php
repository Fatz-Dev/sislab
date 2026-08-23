@extends('layouts.app')

@section('title', 'Jadwal Ruangan Praktikum')

@section('content')
    <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-6 bg-slate-50/50 dark:bg-zinc-900">
            <h2 class="text-2xl font-bold text-slate-800 dark:text-white m-0">Jadwal Kelas Praktikum</h2>
            <p class="text-sm text-slate-500 mt-1">Lihat jadwal seluruh kelas praktikum berdasarkan ruangan laboratorium yang tersedia.</p>
        </div>
    </div>

    <section class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-slate-200 overflow-hidden mt-6">
        <div class="px-6 py-5 border-b border-slate-200 bg-slate-50/50 dark:bg-zinc-800 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <h3 class="text-lg font-bold text-slate-800 dark:text-white m-0">Filter Jadwal</h3>
            <div class="w-full sm:w-1/3">
                <select id="ruanganSelector" class="w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:border-blue-500 focus:ring-blue-500 text-sm dark:bg-zinc-700 dark:border-zinc-600 dark:text-white">
                    <option value="">-- Semua Ruangan --</option>
                    @foreach($ruangans as $ruangan)
                        <option value="{{ $ruangan->id }}">{{ $ruangan->nama_ruangan }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="p-6">
            <div id="calendar" class="w-full dark:text-slate-300"></div>
        </div>
    </section>

    <!-- Style Override untuk FullCalendar agar serasi dengan tailwind dan dark mode -->
    <style>
        .fc {
            font-family: inherit;
        }
        .fc-theme-standard td, .fc-theme-standard th, .fc-theme-standard .fc-scrollgrid {
            border-color: #e2e8f0;
        }
        .dark .fc-theme-standard td, .dark .fc-theme-standard th, .dark .fc-theme-standard .fc-scrollgrid {
            border-color: #3f3f46; /* zinc-700 */
        }
        .fc .fc-col-header-cell-cushion {
            padding: 8px 4px;
            color: #475569;
            font-weight: 600;
        }
        .dark .fc .fc-col-header-cell-cushion {
            color: #cbd5e1;
        }
        .fc .fc-timegrid-slot-label-cushion {
            color: #64748b;
        }
        .dark .fc .fc-timegrid-slot-label-cushion {
            color: #94a3b8;
        }
        .fc-event {
            border: none !important;
            border-radius: 4px;
            padding: 2px 4px;
            font-size: 0.85em;
            font-weight: 500;
            cursor: pointer;
            transition: opacity 0.2s;
            color: #ffffff !important;
        }
        .fc-event:hover {
            opacity: 0.9;
        }
        .fc-daygrid-event {
            white-space: normal !important;
            align-items: center;
        }
        
        /* Tambahan responsive untuk mobile */
        @media (max-width: 640px) {
            .fc .fc-toolbar.fc-header-toolbar {
                flex-direction: column;
                gap: 12px;
                margin-bottom: 1em !important;
            }
            .fc-toolbar-title {
                font-size: 1.25rem !important;
                text-align: center;
            }
            .fc .fc-toolbar-chunk {
                display: flex;
                justify-content: center;
                flex-wrap: wrap;
                gap: 8px;
            }
            .fc .fc-button {
                padding: 0.4em 0.65em;
                font-size: 0.85em;
            }
        }
    </style>

    <!-- Import FullCalendar via CDN -->
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var ruanganSelector = document.getElementById('ruanganSelector');

            let calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridDay'
                },
                allDaySlot: false,
                slotMinTime: '07:00:00',
                slotMaxTime: '18:00:00',
                hiddenDays: [], 
                dayHeaderFormat: window.innerWidth < 640 ? { weekday: 'short' } : { weekday: 'long' },
                slotDuration: '00:30:00',
                locale: 'id',
                height: 600, // Memberikan tinggi absolut agar tidak collapse
                eventDisplay: 'block', // Membuat warna full background
                eventContent: function(info) {
                    if (info.view.type === 'dayGridMonth') {
                        return { html: '<div class="text-xs truncate w-full text-center px-1">' + info.event.extendedProps.roomName + '</div>' };
                    } else {
                        return { html: '<div class="text-xs leading-tight p-0.5">' + info.event.title + '</div>' };
                    }
                },
                dateClick: function(info) {
                    // Ketika tanggal di bulan diklik, langsung pindah ke timeGridWeek di tanggal tersebut
                    calendar.changeView('timeGridWeek', info.dateStr);
                },
                events: function(info, successCallback, failureCallback) {
                    let ruanganId = ruanganSelector.value;
                    let url = '{{ route("admin.jadwal.data") }}';
                    if (ruanganId) {
                        url += '?ruangan_id=' + ruanganId;
                    }

                    fetch(url)
                        .then(response => response.json())
                        .then(data => {
                            successCallback(data);
                        })
                        .catch(error => {
                            console.error('Error fetching schedule data:', error);
                            failureCallback(error);
                        });
                },
                eventClick: function(info) {
                    if (info.event.url) {
                        info.jsEvent.preventDefault();
                        window.location.href = info.event.url;
                    }
                }
            });

            calendar.render();

            // Memperbarui jadwal ketika ruangan diganti
            ruanganSelector.addEventListener('change', function() {
                calendar.refetchEvents();
            });
        });
    </script>
@endsection