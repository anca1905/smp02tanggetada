@extends('layouts.app')

@section('title', 'Dashboard Guru')

@section('content')
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">
            Welcome Back, {{ explode(' ', $teacher->name)[0] }} 👋
        </h2>
        <p class="text-gray-500 text-sm">Hari ini tanggal {{ $todayFormatted }}</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-16 h-16 bg-blue-50 rounded-bl-full -mr-2 -mt-2"></div>
            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2 relative z-10">Status Datang</h3>
            <div class="flex items-center relative z-10">
                <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center mr-3">
                    <i class="fas fa-sign-in-alt text-lg"></i>
                </div>
                <p class="text-base font-bold text-gray-800">{{ $status_datang }}</p>
            </div>
        </div>

        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-16 h-16 bg-green-50 rounded-bl-full -mr-2 -mt-2"></div>
            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2 relative z-10">Status Pulang</h3>
            <div class="flex items-center relative z-10">
                <div class="w-10 h-10 rounded-full bg-green-100 text-green-600 flex items-center justify-center mr-3">
                    <i class="fas fa-sign-out-alt text-lg"></i>
                </div>
                <p class="text-base font-bold text-gray-800">{{ $status_pulang }}</p>
            </div>
        </div>

        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-16 h-16 bg-yellow-50 rounded-bl-full -mr-2 -mt-2"></div>
            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2 relative z-10">Kehadiran Bulan Ini</h3>
            <div class="flex items-center relative z-10">
                <div class="w-10 h-10 rounded-full bg-yellow-100 text-yellow-600 flex items-center justify-center mr-3">
                    <i class="fas fa-calendar-check text-lg"></i>
                </div>
                <p class="text-2xl font-bold text-gray-800">
                    {{ $totalHadir }}
                    <span class="text-xs text-gray-400 font-normal">/ {{ $totalHariKerja }} hari</span>
                </p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">

        <div class="lg:col-span-2 bg-white p-6 rounded-xl shadow-sm border border-gray-200">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold text-gray-700">Persentase Kehadiran</h3>
                <select onchange="window.location.href='?bulan='+this.value"
                    class="text-sm border border-gray-300 rounded-lg px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @foreach (range(1, 12) as $m)
                        <option value="{{ $m }}" {{ $bulan == $m ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($m)->isoFormat('MMMM') }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex space-x-2 mb-4">
                <button id="tab-datang"
                    class="px-4 py-1.5 text-xs font-bold rounded-full bg-blue-600 text-white transition-colors"
                    onclick="switchTab('datang')">Absen Datang</button>
                <button id="tab-pulang"
                    class="px-4 py-1.5 text-xs font-bold rounded-full bg-gray-100 text-gray-600 hover:bg-gray-200 transition-colors"
                    onclick="switchTab('pulang')">Absen Pulang</button>
            </div>

            <div class="relative h-64 w-full">
                <canvas id="chart-attendance"></canvas>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
            <h3 class="text-lg font-bold text-gray-700 mb-4">Aktivitas Terbaru</h3>
            <div class="space-y-4">
                @forelse ($aktivitas as $log)
                    @php
                        $bgIcon = 'bg-gray-100 text-gray-500';
                        $icon = 'fa-info';

                        if ($log->tipe == 'datang') {
                            $bgIcon = 'bg-blue-100 text-blue-600';
                            $icon = 'fa-sign-in-alt';
                        } elseif ($log->tipe == 'pulang') {
                            $bgIcon = 'bg-green-100 text-green-600';
                            $icon = 'fa-sign-out-alt';
                        } elseif ($log->tipe == 'absensi') {
                            $bgIcon = 'bg-purple-100 text-purple-600';
                            $icon = 'fa-clipboard-check';
                        }
                    @endphp
                    <div class="flex items-start pb-3 border-b border-gray-50 last:border-0">
                        <div
                            class="flex-shrink-0 w-8 h-8 rounded-full {{ $bgIcon }} flex items-center justify-center mt-1">
                            <i class="fas {{ $icon }} text-xs"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-800">{{ $log->title }}</p>
                            <p class="text-xs text-gray-400">{{ $log->time->diffForHumans() }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-400 text-center py-4">Belum ada aktivitas hari ini.</p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="text-lg font-bold text-gray-700">Riwayat Presensi Terakhir</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 text-xs uppercase font-semibold text-gray-500">
                    <tr>
                        <th class="px-6 py-3">Tanggal</th>
                        <th class="px-6 py-3">Jam Datang</th>
                        <th class="px-6 py-3">Jam Pulang</th>
                        <th class="px-6 py-3">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($riwayat as $row)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 font-medium text-gray-900">{{ $row['tgl'] }}</td>
                            <td class="px-6 py-4 text-blue-600">{{ $row['datang'] }}</td>
                            <td class="px-6 py-4 text-green-600">{{ $row['pulang'] }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    {{ $row['status'] }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-gray-400">Belum ada data riwayat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @push('js')
        <script>
            const datangData = @json($chartDataDatang);
            const pulangData = @json($chartDataPulang);

            const daysInMonth = datangData.length;
            const labels = Array.from({
                length: daysInMonth
            }, (_, i) => i + 1);

            const ctx = document.getElementById('chart-attendance').getContext('2d');

            let myChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Absen Datang',
                        data: datangData,
                        borderColor: '#3b82f6', 
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true,
                        pointRadius: 2,
                        pointHoverRadius: 5
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 1.5, 
                            ticks: {
                                display: false
                            }, 
                            grid: {
                                borderDash: [2, 4]
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.raw === 1 ? 'Hadir' : 'Tidak Hadir';
                                }
                            }
                        }
                    }
                }
            });

            function switchTab(type) {
                const btnDatang = document.getElementById('tab-datang');
                const btnPulang = document.getElementById('tab-pulang');

                if (type === 'datang') {
                    btnDatang.className = "px-4 py-1.5 text-xs font-bold rounded-full bg-blue-600 text-white transition-colors";
                    btnPulang.className =
                        "px-4 py-1.5 text-xs font-bold rounded-full bg-gray-100 text-gray-600 hover:bg-gray-200 transition-colors";

                    myChart.data.datasets[0].label = 'Absen Datang';
                    myChart.data.datasets[0].data = datangData;
                    myChart.data.datasets[0].borderColor = '#3b82f6';
                    myChart.data.datasets[0].backgroundColor = 'rgba(59, 130, 246, 0.1)';
                } else {
                    btnPulang.className =
                    "px-4 py-1.5 text-xs font-bold rounded-full bg-green-600 text-white transition-colors";
                    btnDatang.className =
                        "px-4 py-1.5 text-xs font-bold rounded-full bg-gray-100 text-gray-600 hover:bg-gray-200 transition-colors";

                    myChart.data.datasets[0].label = 'Absen Pulang';
                    myChart.data.datasets[0].data = pulangData;
                    myChart.data.datasets[0].borderColor = '#10b981'; 
                    myChart.data.datasets[0].backgroundColor = 'rgba(16, 185, 129, 0.1)';
                }
                myChart.update();
            }
        </script>
    @endpush
@endsection
