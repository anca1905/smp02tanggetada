@extends('layouts.app')

@section('title', 'Kotak Masuk')

@section('content')
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold text-gray-800">Pesan dari Pengunjung</h2>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b">
                    <tr>
                        <th class="px-6 py-3">Pengirim</th>
                        <th class="px-6 py-3">Subjek</th>
                        <th class="px-6 py-3">Pesan</th>
                        <th class="px-6 py-3">Waktu</th>
                        <th class="px-6 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($messages as $msg)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-900">{{ $msg->name }}</div>
                                <div class="text-xs text-blue-600">{{ $msg->email }}</div>
                            </td>
                            <td class="px-6 py-4 font-medium">{{ $msg->subject }}</td>
                            <td class="px-6 py-4 text-gray-600 truncate max-w-xs">{{ Str::limit($msg->message, 50) }}</td>
                            <td class="px-6 py-4 text-xs">{{ $msg->created_at->diffForHumans() }}</td>
                            <td class="px-6 py-4 text-center">
                                <form onsubmit="return confirm('Hapus pesan ini?');"
                                    action="{{ route('tu.inbox.destroy', $msg->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500">Belum ada pesan masuk.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="p-4">
                {{ $messages->links() }}
            </div>
        </div>
    </div>
@endsection
